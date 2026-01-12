<?php

declare(strict_types=1);

namespace KenDeNigerian\Krak\models;

use KenDeNigerian\Krak\core\Model;

use KenDeNigerian\Krak\helpers\emailhelper;
use Carbon\Carbon;
use Exception;

class Cron extends Model 
{
    /**
     * Runs a cron job for managing investments.
     *
     * @return int The number of investments processed
     */
    public function cron(): int
    {
        try {

            // Get current timestamp
            $now = Carbon::now()->toDateTimeString();

            // Fetch active investments where next_time is less than or equal to the current time
            $investments = $this->db->select('invests', '*', [
                'AND' => [
                    'status' => 2,
                    'next_time[<=]' => $now
                ]
            ]);

            // Count the number of investments processed
            $investmentsProcessed = 0;

            // Update setting's last_cron field
            $this->db->update('settings', [
                'last_cron' => $now
            ], [
                'id' => 1
            ]);

            // get the settings
            $settings = $this->getSettings();

            // Fetch the email template with id = 13
            $emailTemplates = $this->getEmailTemplate();
            $interestTemplate = $emailTemplates[13] ?? null;

            // Batch fetch all users to avoid N+1 queries
            $userIds = array_unique(array_column($investments, 'userid'));
            $users = !empty($userIds) ? $this->db->select('user', '*', ['userid' => $userIds]) : [];
            $usersMap = [];
            foreach ($users as $user) {
                $usersMap[$user['userid']] = $user;
            }

            // Batch fetch all plans to avoid N+1 queries
            $planIds = array_unique(array_column($investments, 'planId'));
            $plans = !empty($planIds) ? $this->db->select('plans', '*', ['planId' => $planIds]) : [];
            $plansMap = [];
            foreach ($plans as $plan) {
                $plansMap[$plan['planId']] = $plan;
            }

            foreach ($investments as $data) {
                $user = $usersMap[$data['userid']] ?? null;
                if (!$user) {
                    continue; // Skip if user not found
                }

                // Calculate next time only once
                $nextTime = Carbon::parse($now)->addHours(intval($data['hours']))->toDateTimeString();

                if ($data['period'] == '-1') {
                    // If the investment has an indefinite period, it will run for a Lifetime
                    $this->db->update('invests', [
                        'return_rec_time[+]' => 1,
                        'last_time' => $now,
                        'next_time' => $nextTime,
                        'updated_at' => $now
                    ], ['id' => $data['id']]);

                    // Calculate new balance after adding interest
                    $newBalance = $user['interest_wallet'] + $data['interest'];

                    // Get plan data associated with the investment (from batch fetch)
                    $data['plan'] = $plansMap[$data['planId']] ?? null;

                    // Update user's interest-wallet balance
                    $this->db->update('user', [
                        'interest_wallet' => $newBalance
                    ], [
                        'userid' => $user['userid']
                    ]);

                    // Insert transaction record for the interest earned
                    $trxDetails = $data['interest'] . ' ' . 'Interest' . ' From ' . $data['plan']['name'];
                    $this->db->insert('transactions', [
                        'transactionId' => $data['investId'],
                        'userid' => $user['userid'],
                        'amount' => $data['interest'],
                        'post_balance' => $newBalance,
                        'trx_type' => '+',
                        'trx_id' => $this->generateTransactionID(),
                        'wallet_type' => 'interest_wallet',
                        'details' => $trxDetails,
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s')
                    ]);

                    // If capital needs to be returned, update the user's balance and record the transaction
                    if ($data['capital_status'] == 1) {
                        $capital = $data['amount'];
                        $returnCapital = $user['interest_wallet'] + $capital + $data['interest'];

                        $this->db->update('user', [
                            'interest_wallet' => $returnCapital
                        ], [
                            'userid' => $user['userid']
                        ]);

                        // Insert transaction record for the interest earned
                        $trxDetails = $capital . ' ' . 'Capital Back' . ' From ' . $data['plan']['name'];
                        $this->db->insert('transactions', [
                            'transactionId' => $data['investId'],
                            'userid' => $user['userid'],
                            'amount' => $capital,
                            'post_balance' => $returnCapital,
                            'trx_type' => '+',
                            'trx_id' => $this->generateTransactionID(),
                            'wallet_type' => 'interest_wallet',
                            'details' => $trxDetails,
                            'created_at' => date('Y-m-d H:i:s'),
                            'updated_at' => date('Y-m-d H:i:s')
                        ]);
                    }
                }

                if ($data['return_rec_time'] <= $data['period'] && $data['period'] != '-1') {
                    // Process incomplete investment
                    $this->db->update('invests', [
                        'return_rec_time[+]' => 1,
                        'last_time' => $now,
                        'next_time' => $nextTime,
                        'updated_at' => $now
                    ], ['id' => $data['id']]);

                    // Query the table immediately after processing incomplete investment
                    $updatedData = $this->db->select('invests', '*', ['id' => $data['id']]);

                    foreach ($updatedData as $updated) {
                        // Check if investment has reached its completion period
                        if ($updated['return_rec_time'] >= $updated['period'] && $updated['period'] != '-1') {
                            
                            $siteName = $settings['sitename'];
                            $siteLogo = $settings['logo'];
                            $siteUrl = getenv('URL_PATH');
                            $dateNow = date('Y');
                            
                            // email notification is enabled
                            if ($settings["email_notification"] == 1) {
                                // interest template is enabled
                                if ($interestTemplate !== null && $interestTemplate['status'] == 1) {

                                    // Replace the placeholders with user input in the email body
                                    $interestTemplate['body'] = str_replace(['{FIRSTNAME}', '{LASTNAME}', '{SITENAME}', '{SITELOGO}', '{URL}', '{DATENOW}'], [$user['firstname'], $user['lastname'], $siteName, $siteLogo, $siteUrl, $dateNow], $interestTemplate['body']);

                                    $recipientEmail = $user['email'];
                                    $subject = $interestTemplate['subject'];
                                    $body = $interestTemplate['body'];

                                    if (emailhelper::sendEmail($settings, $recipientEmail, $subject, $body)) {
                                        // Calculate new balance after adding interest
                                        $postBalance = $user['interest_wallet'] + $updated['interest'];

                                        // Get plan updated associated with the investment (from batch fetch)
                                        $updated['plan'] = $plansMap[$updated['planId']] ?? null;

                                        // Update user's interest-wallet balance
                                        $this->db->update('user', [
                                            'interest_wallet' => $postBalance
                                        ], [
                                            'userid' => $user['userid']
                                        ]);

                                        // Insert transaction record for the interest earned
                                        $trxDetails = $data['interest'] . ' ' . 'Interest' . ' From ' . $updated['plan']['name'];
                                        $this->db->insert('transactions', [
                                            'transactionId' => $updated['investId'],
                                            'userid' => $user['userid'],
                                            'amount' => $updated['interest'],
                                            'post_balance' => $postBalance,
                                            'trx_type' => '+',
                                            'trx_id' => $this->generateTransactionID(),
                                            'wallet_type' => 'interest_wallet',
                                            'details' => $trxDetails,
                                            'created_at' => date('Y-m-d H:i:s'),
                                            'updated_at' => date('Y-m-d H:i:s')
                                        ]);

                                        // Mark the investment as completed
                                        $this->db->update('invests', [
                                            'status' => 1
                                        ], [
                                            'id' => $updated['id']
                                        ]);

                                        // If capital needs to be returned, update the user's balance and record the transaction
                                        if ($updated['capital_status'] == 1) {
                                            $capital = $updated['amount'];
                                            $returnCapital = $user['interest_wallet'] + $capital + $updated['interest'];

                                            $this->db->update('user', [
                                                'interest_wallet' => $returnCapital
                                            ], [
                                                'userid' => $user['userid']
                                            ]);

                                            // Insert transaction record for the capital returned
                                            $trxDetails = $capital . ' ' . 'Capital Back' . ' From ' . $updated['plan']['name'];
                                            $this->db->insert('transactions', [
                                                'transactionId' => $updated['investId'],
                                                'userid' => $user['userid'],
                                                'amount' => $capital,
                                                'post_balance' => $returnCapital,
                                                'trx_type' => '+',
                                                'trx_id' => $this->generateTransactionID(),
                                                'wallet_type' => 'interest_wallet',
                                                'details' => $trxDetails,
                                                'created_at' => date('Y-m-d H:i:s'),
                                                'updated_at' => date('Y-m-d H:i:s')
                                            ]);
                                        }
                                    } else {
                                        // Calculate new balance after adding interest
                                        $postBalance = $user['interest_wallet'] + $updated['interest'];

                                        // Get plan updated associated with the investment (from batch fetch)
                                        $updated['plan'] = $plansMap[$updated['planId']] ?? null;

                                        // Update user's interest-wallet balance
                                        $this->db->update('user', [
                                            'interest_wallet' => $postBalance
                                        ], [
                                            'userid' => $user['userid']
                                        ]);

                                        // Insert transaction record for the interest earned
                                        $trxDetails = $data['interest'] . ' ' . 'Interest' . ' From ' . $updated['plan']['name'];
                                        $this->db->insert('transactions', [
                                            'transactionId' => $updated['investId'],
                                            'userid' => $user['userid'],
                                            'amount' => $updated['interest'],
                                            'post_balance' => $postBalance,
                                            'trx_type' => '+',
                                            'trx_id' => $this->generateTransactionID(),
                                            'wallet_type' => 'interest_wallet',
                                            'details' => $trxDetails,
                                            'created_at' => date('Y-m-d H:i:s'),
                                            'updated_at' => date('Y-m-d H:i:s')
                                        ]);

                                        // Mark the investment as completed
                                        $this->db->update('invests', [
                                            'status' => 1
                                        ], [
                                            'id' => $updated['id']
                                        ]);

                                        // If capital needs to be returned, update the user's balance and record the transaction
                                        if ($updated['capital_status'] == 1) {
                                            $capital = $updated['amount'];
                                            $returnCapital = $user['interest_wallet'] + $capital + $updated['interest'];

                                            $this->db->update('user', [
                                                'interest_wallet' => $returnCapital
                                            ], [
                                                'userid' => $user['userid']
                                            ]);

                                            // Insert transaction record for the capital returned
                                            $trxDetails = $capital . ' ' . 'Capital Back' . ' From ' . $updated['plan']['name'];
                                            $this->db->insert('transactions', [
                                                'transactionId' => $updated['investId'],
                                                'userid' => $user['userid'],
                                                'amount' => $capital,
                                                'post_balance' => $returnCapital,
                                                'trx_type' => '+',
                                                'trx_id' => $this->generateTransactionID(),
                                                'wallet_type' => 'interest_wallet',
                                                'details' => $trxDetails,
                                                'created_at' => date('Y-m-d H:i:s'),
                                                'updated_at' => date('Y-m-d H:i:s')
                                            ]);
                                        }
                                    }
                                } else {
                                    // Calculate new balance after adding interest
                                    $postBalance = $user['interest_wallet'] + $updated['interest'];

                                    // Get plan updated associated with the investment (from batch fetch)
                                    $updated['plan'] = $plansMap[$updated['planId']] ?? null;

                                    // Update user's interest-wallet balance
                                    $this->db->update('user', [
                                        'interest_wallet' => $postBalance
                                    ], [
                                        'userid' => $user['userid']
                                    ]);

                                    // Insert transaction record for the interest earned
                                    $trxDetails = $data['interest'] . ' ' . 'Interest' . ' From ' . $updated['plan']['name'];
                                    $this->db->insert('transactions', [
                                        'transactionId' => $updated['investId'],
                                        'userid' => $user['userid'],
                                        'amount' => $updated['interest'],
                                        'post_balance' => $postBalance,
                                        'trx_type' => '+',
                                        'trx_id' => $this->generateTransactionID(),
                                        'wallet_type' => 'interest_wallet',
                                        'details' => $trxDetails,
                                        'created_at' => date('Y-m-d H:i:s'),
                                        'updated_at' => date('Y-m-d H:i:s')
                                    ]);

                                    // Mark the investment as completed
                                    $this->db->update('invests', [
                                        'status' => 1
                                    ], [
                                        'id' => $updated['id']
                                    ]);

                                    // If capital needs to be returned, update the user's balance and record the transaction
                                    if ($updated['capital_status'] == 1) {
                                        $capital = $updated['amount'];
                                        $returnCapital = $user['interest_wallet'] + $capital + $updated['interest'];

                                        $this->db->update('user', [
                                            'interest_wallet' => $returnCapital
                                        ], [
                                            'userid' => $user['userid']
                                        ]);

                                        // Insert transaction record for the capital returned
                                        $trxDetails = $capital . ' ' . 'Capital Back' . ' From ' . $updated['plan']['name'];
                                        $this->db->insert('transactions', [
                                            'transactionId' => $updated['investId'],
                                            'userid' => $user['userid'],
                                            'amount' => $capital,
                                            'post_balance' => $returnCapital,
                                            'trx_type' => '+',
                                            'trx_id' => $this->generateTransactionID(),
                                            'wallet_type' => 'interest_wallet',
                                            'details' => $trxDetails,
                                            'created_at' => date('Y-m-d H:i:s'),
                                            'updated_at' => date('Y-m-d H:i:s')
                                        ]);
                                    }
                                }
                            }else {
                                // Calculate new balance after adding interest
                                $postBalance = $user['interest_wallet'] + $updated['interest'];

                                // Get plan updated associated with the investment (from batch fetch)
                                $updated['plan'] = $plansMap[$updated['planId']] ?? null;

                                // Update user's interest-wallet balance
                                $this->db->update('user', [
                                    'interest_wallet' => $postBalance
                                ], [
                                    'userid' => $user['userid']
                                ]);

                                // Insert transaction record for the interest earned
                                $trxDetails = $data['interest'] . ' ' . 'Interest' . ' From ' . $updated['plan']['name'];
                                $this->db->insert('transactions', [
                                    'transactionId' => $updated['investId'],
                                    'userid' => $user['userid'],
                                    'amount' => $updated['interest'],
                                    'post_balance' => $postBalance,
                                    'trx_type' => '+',
                                    'trx_id' => $this->generateTransactionID(),
                                    'wallet_type' => 'interest_wallet',
                                    'details' => $trxDetails,
                                    'created_at' => date('Y-m-d H:i:s'),
                                    'updated_at' => date('Y-m-d H:i:s')
                                ]);

                                // Mark the investment as completed
                                $this->db->update('invests', [
                                    'status' => 1
                                ], [
                                    'id' => $updated['id']
                                ]);

                                // If capital needs to be returned, update the user's balance and record the transaction
                                if ($updated['capital_status'] == 1) {
                                    $capital = $updated['amount'];
                                    $returnCapital = $user['interest_wallet'] + $capital + $updated['interest'];

                                    $this->db->update('user', [
                                        'interest_wallet' => $returnCapital
                                    ], [
                                        'userid' => $user['userid']
                                    ]);

                                    // Insert transaction record for the capital returned
                                    $trxDetails = $capital . ' ' . 'Capital Back' . ' From ' . $updated['plan']['name'];
                                    $this->db->insert('transactions', [
                                        'transactionId' => $updated['investId'],
                                        'userid' => $user['userid'],
                                        'amount' => $capital,
                                        'post_balance' => $returnCapital,
                                        'trx_type' => '+',
                                        'trx_id' => $this->generateTransactionID(),
                                        'wallet_type' => 'interest_wallet',
                                        'details' => $trxDetails,
                                        'created_at' => date('Y-m-d H:i:s'),
                                        'updated_at' => date('Y-m-d H:i:s')
                                    ]);
                                }
                            }
                        }
                    }
                }

                $investmentsProcessed++;
            }

            // Return the number of investments processed
            return $investmentsProcessed;
        } catch (Exception $e) {
            // Handle exceptions, such as database errors or date manipulation errors
            error_log('Error in cron(): ' . $e->getMessage());

            // Return -1 or another suitable value to indicate an error
            return -1;
        }
    }

    /**
     * Runs a cron job for managing initiated deposits.
     *
     * @return int The number of deposits processed
     */
    public function initiated(): int
    {
        try {

            // Get current timestamp
            $now = Carbon::now()->toDateTimeString();

            // Fetch initiated deposits where updated_at is greater than or equal to twentyFourHoursAgo
            $deposits = $this->db->select('deposits', '*', [
                'AND' => [
                    'status' => 0,
                    'retry[<]' => 3,
                    'next_time[<=]' => $now
                ]
            ]);

            // Count the number of deposits processed
            $depositsProcessed = 0;

            // Update setting's last_cron field
            $this->db->update('settings', ['last_deposit_cron' => $now], ['id' => 1]);

            // Get settings
            $settings = $this->getSettings();

            // Fetch the email template with id = 20
            $emailTemplates = $this->getEmailTemplate();
            $retryTemplate = $emailTemplates[20] ?? null;

            // Batch fetch all users to avoid N+1 queries
            $userIds = array_unique(array_column($deposits, 'userid'));
            $users = !empty($userIds) ? $this->db->select('user', '*', ['userid' => $userIds]) : [];
            $usersMap = [];
            foreach ($users as $user) {
                $usersMap[$user['userid']] = $user;
            }

            // Batch fetch all gateways to avoid N+1 queries
            $methodCodes = array_unique(array_filter(array_column($deposits, 'method_code')));
            $gateways = !empty($methodCodes) ? $this->db->select('gateway_currencies', '*', ['method_code' => $methodCodes]) : [];
            $gatewaysMap = [];
            foreach ($gateways as $gateway) {
                $gatewaysMap[$gateway['method_code']] = $gateway;
            }

            foreach ($deposits as $data) {
                // Fetch user with initiated deposit (from batch fetch)
                $user = $usersMap[$data['userid']] ?? null;
                if (!$user) {
                    continue; // Skip if user not found
                }

                // Get gateway data associated with the deposit (from batch fetch)
                $data['gateway'] = $gatewaysMap[$data['method_code']] ?? null;

                // if the initiated deposit has a payment gateway, then proceed
                if ($data['gateway']) {

                    // Calculate next retry time
                    $nextTime = Carbon::now()->addHours(24)->toDateTimeString();

                    if ($data['retry'] <= 3) {
                        // Process retry increment
                        $this->db->update('deposits', [
                            'retry[+]' => 1,
                            'next_time' => $nextTime,
                        ], ['id' => $data['id']]);

                        // Set variables
                        $siteName = $settings['sitename'];
                        $siteLogo = $settings['logo'];
                        $siteUrl = getenv('URL_PATH');
                        $dateNow = date('Y');

                        // email notification is enabled
                        if ($settings["email_notification"] == 1) {
                            // retry template is enabled
                            if ($retryTemplate !== null && $retryTemplate['status'] == 1) {

                                // Send retry email
                                $retryTemplate['body'] = str_replace(
                                    ['{FIRSTNAME}', '{LASTNAME}', '{AMOUNT}', '{GATEWAY}', '{CURRENCY}', '{SITENAME}', '{SITELOGO}', '{URL}', '{DATENOW}'],
                                    [$user['firstname'], $user['lastname'], $data['amount'], $data['gateway']['name'], $user['currency'], $siteName, $siteLogo, $siteUrl, $dateNow],
                                    $retryTemplate['body']
                                );

                                $recipientEmail = $user['email'];
                                $subject = $retryTemplate['subject'];
                                $body = $retryTemplate['body'];
                        
                                // Send email and update depositsProcessed count
                                if (emailhelper::sendEmail($settings, $recipientEmail, $subject, $body)) {
                                    $depositsProcessed++;
                                }
                            }else{
                                $depositsProcessed++;
                            }
                        }else{
                            $depositsProcessed++;
                        }
                    }

                    // Query the table immediately after processing retry increment
                    $updatedData = $this->db->select('deposits', '*', ['id' => $data['id']]);

                    foreach ($updatedData as $updated) {
                        // Check if deposit has reached maximum retrial times
                        if ($updated['retry'] >= 3) {
                            // Mark the deposit as rejected
                            $this->db->update('deposits', ['status' => 3], ['id' => $updated['id']]);

                            // Insert transaction record for the rejected deposit
                            $trxDetails = $updated['amount'] . ' deposit initiated via ' . $data['gateway']['name'] . ' has been rejected';
                            $this->db->insert('transactions', [
                                'transactionId' => $updated['depositId'],
                                'userid' => $user['userid'],
                                'amount' => $updated['amount'],
                                'post_balance' => $user['interest_wallet'],
                                'trx_type' => '+',
                                'trx_id' => $this->generateTransactionID(),
                                'wallet_type' => 'interest_wallet',
                                'details' => $trxDetails,
                                'created_at' => date('Y-m-d H:i:s'),
                                'updated_at' => date('Y-m-d H:i:s')
                            ]);
                        }
                    }
                }
            }

            // Return the number of deposits processed
            return $depositsProcessed;
        } catch (Exception $e) {
            // Handle exceptions, such as database errors or date manipulation errors
            error_log('Error in InitiatedDepositsCron(): ' . $e->getMessage());

            // Return -1 or another suitable value to indicate an error
            return -1;
        }
    }

    /**
     * Gets the site `settings`
     *
     * @return array
     */
    public function getSettings(): array
    {
        // Fetch site settings from the database
        $settings = $this->db->get('settings', '*', ["id" => 1]);

        // If $settings is null or empty, return an empty array
        if (!$settings) {
            return [];
        }

        return $settings;
    }

    /**
     * Gets all email templates from the database
     *
     * @return array An associative array containing all email templates with "id" as keys
     */
    public function getEmailTemplate(): array
    {
        
        $emailTemplates = $this->db->select('email_templates', '*', []);

        // Create an associative array with "id" as keys
        $templates = [];
        foreach ($emailTemplates as $template) {
            $templates[$template['id']] = [
                'name' => $template['name'],
                'subject' => $template['subject'],
                'body' => $template['email_body'],
                'status' => $template['email_status'],
                'created_at' => $template['created_at'],
            ];
        }

        return $templates;
    }

    /**
     * Generate a unique transaction ID
     *
     */
    private function generateTransactionID(): string
    {
        // Generate a unique transaction ID, such as using a random string or a combination of timestamp and user ID
        // Here's an example using a random string of length 10
        $chars = 'ABCDEFGHJKMNOPQRSTUVWXYZ123456789';
        $chars_len = strlen($chars);
        $trx_id = '';
        for ($i = 0; $i < 10; $i++) {
            $trx_id .= $chars[rand(0, $chars_len - 1)];
        }
        return $trx_id;
    }
}