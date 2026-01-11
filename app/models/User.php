<?php

namespace Fir\Models;

use Fir\Helpers\EmailHelper;
use Carbon\Carbon;
use Exception;

class User extends Model {

    /**
     * Calculate Total Deposits
     *
     * @param string $userid The ID of the user.
     * @return float The total sum of the deposited amount in the "deposits" table.
     */
    public function deposits(string $userid): float {
        try {
            // Calculate the sum of the deposited amount for the specified user
            $sum = $this->db->sum("deposits", "amount", ["userid" => $userid, "status" => [1]]);
            // Convert the sum to float and return
            return (float) $sum;
        } catch (Exception $e) {
            // Handle any exceptions
            error_log("Error in deposits calculation: " . $e->getMessage());
            return 0.0; // Return 0 in case of error
        }
    }

    /**
     * Calculate Total Withdrawals
     *
     * @param string $userid The ID of the user.
     * @return float The total sum of withdrawn amount in the "withdrawals" table.
     */
    public function withdrawals(string $userid): float {
        try {
            // Calculate the sum of withdrawn amount for the specified user
            $sum = $this->db->sum("withdrawals", "amount", ["userid" => $userid, "status" => [1]]);
            // Convert the sum to float and return
            return (float) $sum;
        } catch (Exception $e) {
            // Handle any exceptions
            error_log("Error in withdrawals calculation: " . $e->getMessage());
            return 0.0; // Return 0 in case of error
        }
    }

    /**
     * Calculate Total Investments
     *
     * @param string $userid The ID of the user.
     * @return float The total sum of invested amount in the "investments" table.
     */
    public function investments(string $userid): float {
        try {
            // Calculate the sum of invested amount for the specified user
            $sum = $this->db->sum("invests", "amount", ["userid" => $userid, "status" => [1]]);
            // Convert the sum to float and return
            return (float) $sum;
        } catch (Exception $e) {
            // Handle any exceptions
            error_log("Error in investments calculation: " . $e->getMessage());
            return 0.0; // Return 0 in case of error
        }
    }

    /**
     * Calculate Total Trades
     *
     * @param string $userid The ID of the user.
     * @return float The total sum of traded amount in the "trades" table.
     */
    public function trades(string $userid): float {
        try {
            // Calculate the sum of traded amount for the specified user
            $sum = $this->db->sum("trades", "amount", ["userid" => $userid, "status" => [1, 2]]);
            // Convert the sum to float and return
            return (float) $sum;
        } catch (Exception $e) {
            // Handle any exceptions
            error_log("Error in trades calculation: " . $e->getMessage());
            return 0.0; // Return 0 in case of error
        }
    }

    /**
     * Store OTP for a user
     *
     * This method stores an OTP for a user in the 'user' table.
     *
     * @param mixed $userid The ID of the user.
     * @param string $code The OTP code.
     * @return int The number of affected rows.
     */
    public function storeOtp(mixed $userid, string $code): int {
        try {
            // Update the 'user' table with the OTP code
            $update = $this->db->update('user', [
                'twofactor_code' => $code,
                'twofactor_flag' => 1
            ], [
                'userid' => $userid
            ]);
              
            return $update->rowCount();
        } catch (Exception $e) {
            // Handle any exceptions
            error_log("Error in storing OTP: " . $e->getMessage());
            return 0; // Return 0 in case of error
        }
    }

    /**
     * Get the OTP code for a user
     *
     * This method retrieves the OTP code for a user from the 'user' table.
     *
     * @param int $userid The ID of the user.
     * @return array The OTP code or null if not found.
     */
    public function getOtpCode(int $userid): array
    {
        try {
            // Retrieve the OTP code for the user from the 'user' table
            return $this->db->get('user', 'twofactor_code', ['userid' => $userid]);
        } catch (Exception $e) {
            // Handle any exceptions
            error_log("Error in getting OTP code: " . $e->getMessage());
            return []; // Return null in case of error
        }
    }

    /**
     * Retrieve transactions from the database for a given user.
     *
     * @param string $userid The user ID for whom to retrieve transactions.
     * @return array The list of transactions retrieved from the 'transactions' table.
     */
    public function getTransactions(string $userid): array
    {
        try {
            // Retrieve transactions from the 'transactions' table, filtered by user ID and ordered by creation date in descending order
            return $this->db->select("transactions", "*", [
                "userid" => $userid,
                "ORDER" => ["id" => "DESC"],
                "LIMIT" => 5
            ]);
        } catch (Exception $e) {
            // Handle exceptions, such as database errors
            error_log('Error in getTransactions(): ' . $e->getMessage());
            return []; // Return an empty array if an error occurs
        }
    }

    /**
     * Retrieve transactions from the database for a given user.
     *
     * @param string $userid The user ID for whom to retrieve transactions.
     * @return array The list of transactions retrieved from the 'transactions' table.
     */
    public function getTransaction(string $userid): array
    {
        try {
            // Retrieve transactions from the 'transactions' table, filtered by user ID and ordered by creation date in descending order
            $query = $this->db->get("transactions", "*", ["userid" => $userid]);

            // If $query is null or empty, return an empty array
            if (!$query) {
                return [];
            }

            return $query;
        } catch (Exception $e) {
            // Handle exceptions, such as database errors
            error_log('Error in getTransactions(): ' . $e->getMessage());
            return []; // Return an empty array if an error occurs
        }
    }

    /**
     * Gets transactions with pagination
     *
     * This method retrieves transactions for a specified user with pagination from the 'transactions' table.
     *
     * @param int $userid The ID of the user.
     * @param int $page The page number for pagination.
     * @return array Returns an array of transaction records.
     */
    public function transactions_limits(int $userid, int $page): array
    {
        try {
            $limit = 5; // Number of transactions per page
            $offset = ($page - 1) * $limit; // Calculate the offset based on the page number

            // Retrieve transactions for the specified user with pagination from the 'transactions' table
            return $this->db->select('transactions', '*', [
                "userid" => $userid,
                "ORDER" => ["id" => "DESC"],
                "LIMIT" => [$offset, $limit]
            ]);
        } catch (Exception $e) {
            // Handle exceptions, such as database errors
            error_log('Error in transactions_limits(): ' . $e->getMessage());
            return []; // Return an empty array if an error occurs
        }
    }

    /**
     * Update the creation and update timestamps of a deposit.
     *
     * @param string $depositId The ID of the deposit to update.
     * @return int The number of rows affected by the update operation
     */
    public function updateDepositTimestamps(string $depositId): int
    {
        try {
            // Update the creation and update timestamps of the specified deposit
            $update = $this->db->update('deposits', [
                'created_at' => date('Y-m-d H:i:s'), 
                'updated_at' => date('Y-m-d H:i:s')
            ], [
                'depositId' => $depositId
            ]);

            // Return the number of rows affected by the update operation
            return $update->rowCount(); 
        } catch (Exception $e) {
            // Handle exceptions, such as database errors
            error_log('Error in updateDepositTimestamps(): ' . $e->getMessage());
            return 0; // Return false if an error occurs
        }
    }

    /**
     * Update the rank of a user in the database.
     *
     * @param string $userid The ID of the user to update.
     * @param float $bonus The bonus amount to add to the user's balance.
     * @param int $user_ranking_id The new ranking ID to assign to the user.
     * @return int The number of ranks processed.
     */
    public function updateRank(string $userid, float $bonus, int $user_ranking_id): int
    {
        try {
            // Get the application settings
            $settings = $this->getSettings();

            // Fetch the email template with ID = 25
            $emailTemplates = $this->getEmailTemplate();
            $rankTemplate = $emailTemplates[25] ?? null;

            // Generate a transaction ID
            $trx = $this->generateTransactionID();
            
            // Generate a unique transaction ID
            $transactionId = $this->uniqueid();

            // Fetch the user associated with the rank from the database
            $user = $this->db->get('user', '*', ['userid' => $userid]);

            // Initialize the count of ranks processed
            $ranksProcessed = 0;

            // Check if the new ranking ID is different from the current one and is not set to 0
            if ($user['user_ranking_id'] != $user_ranking_id && $user_ranking_id != 0) {
                // Calculate new balance after adding bonus
                $new_balance = $user['interest_wallet'] + $bonus;

                // Update the user's rank and balance in the database
                $this->db->update('user', [
                    'interest_wallet' => $new_balance,
                    'user_ranking_id' => $user_ranking_id
                ], [
                    'userid' => $userid
                ]);

                // Insert transaction for bonus
                $this->db->insert('transactions', [
                    'transactionId' => $transactionId,
                    'userid' => $userid,
                    'trx_type' => "+",
                    'trx_id' => $trx,
                    'amount' => $bonus,
                    'post_balance' => $new_balance,
                    'wallet_type' => "interest_wallet",
                    'details' => "You just received " . $bonus . " bonus",
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s')
                ]);

                // Insert transaction for rank upgrade
                $this->db->insert('transactions', [
                    'transactionId' => $transactionId,
                    'userid' => $userid,
                    'trx_type' => "+",
                    'trx_id' => $trx,
                    'amount' => $bonus,
                    'post_balance' => $new_balance,
                    'wallet_type' => "interest_wallet",
                    'details' => "Your investment rank has just been upgraded",
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s')
                ]);

                // Check if email notification is enabled
                if ($settings["email_notification"] == 1) {

                    // Prepare email variables
                    $siteName = $settings['sitename'];
                    $siteLogo = $data['settings']['logo'];
                    $siteUrl = getenv('URL_PATH');
                    $dateNow = date('Y');
                
                    // Check if the rank template is enabled
                    if ($rankTemplate !== null && $rankTemplate['status'] == 1) {

                        // Prepare email content
                        $body = str_replace(
                            ['{FIRSTNAME}', '{LASTNAME}', '{BONUS}', '{CURRENCY}', '{SITENAME}', '{SITELOGO}', '{URL}', '{DATENOW}'],
                            [$user['firstname'], $user['lastname'], $bonus, $user['currency'], $siteName, $siteLogo, $siteUrl, $dateNow],
                            $rankTemplate['body']
                        );
                        $subject = $rankTemplate['subject'];
                        $recipientEmail = $user['email'];

                        // Send email
                        if (EmailHelper::sendEmail($settings, $recipientEmail, $subject, $body)) {
                            $ranksProcessed++; // Increment ranks processed
                        }
                    } else {
                        $ranksProcessed++; // Increment ranks processed
                    }
                } else {
                    $ranksProcessed++; // Increment ranks processed
                }
            }

            // Return the number of ranks processed
            return $ranksProcessed;
        } catch (Exception $e) {
            // Handle exceptions, such as database errors
            error_log('Error in updateRank(): ' . $e->getMessage());
            return 0; // Return false if an error occurs
        }
    }
    
    /**
     * Retrieve plans from the database.
     *
     * @return array The list of plans retrieved from the 'plans' table.
     */
    public function plans(): array
    {
        try {
            // Retrieve plans from the 'plans' table, ordered by creation date in descending order
            return $this->db->select("plans", "*", ["ORDER" => ["created_at" => "ASC"]]);
        } catch (Exception $e) {
            // Handle exceptions, such as database errors
            error_log('Error in plans(): ' . $e->getMessage());
            return []; // Return an empty array if an error occurs
        }
    }

    /**
     * Retrieve listings from the database.
     *
     * @return array The list of listings retrieved from the 'listings' table.
     */
    public function listings(): array
    {
        try {
            // Retrieve listings from the 'listings' table, ordered by creation date in descending order
            return $this->db->select("listings", "*", ["ORDER" => ["date_added" => "ASC"]]);
        } catch (Exception $e) {
            // Handle exceptions, such as database errors
            error_log('Error in listings(): ' . $e->getMessage());
            return []; // Return an empty array if an error occurs
        }
    }

    /**
     * Retrieve services from the database.
     *
     * @return array The list of services retrieved from the 'services' table.
     */
    public function services(): array
    {
        try {
            // Retrieve services from the 'services' table, ordered by title in alphabetical order
            return $this->db->select("services", "*", ["ORDER" => ["title" => "ASC"]]);
        } catch (Exception $e) {
            // Handle exceptions, such as database errors
            error_log('Error in services(): ' . $e->getMessage());
            return []; // Return an empty array if an error occurs
        }
    }

    /**
     * Retrieve investments from the database for a given user.
     *
     * @param string $userid The user ID for whom to retrieve investments.
     * @return array The list of investments retrieved from the 'invests' table.
     */
    public function getInvests(string $userid): array
    {
        try {
            // Retrieve investments from the 'invests' table, filtered by user ID and ordered by creation date in descending order
            return $this->db->select("invests", "*", [
                "userid" => $userid,
                "ORDER" => ["initiated_at" => "DESC"],
                "LIMIT" => 5
            ]);
        } catch (Exception $e) {
            // Handle exceptions, such as database errors
            error_log('Error in getInvests(): ' . $e->getMessage());
            return []; // Return an empty array if an error occurs
        }
    }

    /**
     * Gets invests with pagination
     *
     * This method retrieves invests for a specified user with pagination from the 'invests' table.
     *
     * @param int $userid The ID of the user.
     * @param int $page The page number for pagination.
     * @return array Returns an array of invested records.
     */
    public function investments_limits(int $userid, int $page): array
    {
        try {
            $limit = 5; // Number of investing per page
            $offset = ($page - 1) * $limit; // Calculate the offset based on the page number

            // Retrieve investing for the specified user with pagination from the 'invests' table
            return $this->db->select('invests', '*', [
                "userid" => $userid,
                "ORDER" => ["initiated_at" => "DESC"],
                "LIMIT" => [$offset, $limit]
            ]);
        } catch (Exception $e) {
            // Handle exceptions, such as database errors
            error_log('Error in investments_limits(): ' . $e->getMessage());
            return []; // Return an empty array if an error occurs
        }
    }

    /**
     * Retrieve investments from the database for a given user.
     *
     * @param string $userid The user ID for whom to retrieve investments.
     * @return array The list of investments retrieved from the 'invests' table.
     */
    public function getInvestsCompleted(string $userid): array
    {
        try {
            // Retrieve investments from the 'invests' table, filtered by user ID and where status is pending, ordered by creation date in descending order
            return $this->db->select("invests", "*", [
                "userid" => $userid,
                'status' => 1,
                "ORDER" => ["initiated_at" => "DESC"],
                "LIMIT" => 5
            ]);
        } catch (Exception $e) {
            // Handle exceptions, such as database errors
            error_log('Error in getInvestsCompleted(): ' . $e->getMessage());
            return []; // Return an empty array if an error occurs
        }
    }

    /**
     * Gets invests with pagination
     *
     * This method retrieves invests for a specified user with pagination from the 'invests' table.
     *
     * @param int $userid The ID of the user.
     * @param int $page The page number for pagination.
     * @return array Returns an array of invested records.
     */
    public function completed_investments_limits(int $userid, int $page): array
    {
        try {
            $limit = 5; // Number of investing per page
            $offset = ($page - 1) * $limit; // Calculate the offset based on the page number

            // Retrieve investing for the specified user with pagination from the 'invests' table
            return $this->db->select('invests', '*', [
                "userid" => $userid,
                'status' => 1,
                "ORDER" => ["initiated_at" => "DESC"],
                "LIMIT" => [$offset, $limit]
            ]);
        } catch (Exception $e) {
            // Handle exceptions, such as database errors
            error_log('Error in completed_investments_limits(): ' . $e->getMessage());
            return []; // Return an empty array if an error occurs
        }
    }

    /**
     * Retrieve investments from the database for a given user.
     *
     * @param string $userid The user ID for whom to retrieve investments.
     * @return array The list of investments retrieved from the 'invests' table.
     */
    public function getInvestsPending(string $userid): array
    {
        try {
            // Retrieve investments from the 'invests' table, filtered by user ID and where status is pending, ordered by creation date in descending order
            return $this->db->select("invests", "*", [
                "userid" => $userid,
                'status' => 2,
                "ORDER" => ["initiated_at" => "DESC"],
                "LIMIT" => 5
            ]);
        } catch (Exception $e) {
            // Handle exceptions, such as database errors
            error_log('Error in getInvestsPending(): ' . $e->getMessage());
            return []; // Return an empty array if an error occurs
        }
    }

    /**
     * Gets invests with pagination
     *
     * This method retrieves invests for a specified user with pagination from the 'invests' table.
     *
     * @param int $userid The ID of the user.
     * @param int $page The page number for pagination.
     * @return array Returns an array of invested records.
     */
    public function pending_investments_limits(int $userid, int $page): array
    {
        try {
            $limit = 5; // Number of investing per page
            $offset = ($page - 1) * $limit; // Calculate the offset based on the page number

            // Retrieve investing for the specified user with pagination from the 'invests' table
            return $this->db->select('invests', '*', [
                "userid" => $userid,
                'status' => 2,
                "ORDER" => ["initiated_at" => "DESC"],
                "LIMIT" => [$offset, $limit]
            ]);
        } catch (Exception $e) {
            // Handle exceptions, such as database errors
            error_log('Error in pending_investments_limits(): ' . $e->getMessage());
            return []; // Return an empty array if an error occurs
        }
    }

    /**
     * Retrieve investments from the database for a given user.
     *
     * @param string $userid The user ID for whom to retrieve investments.
     * @return array The list of investments retrieved from the 'invests' table.
     */
    public function getInvestsInitiated(string $userid): array
    {
        try {
            // Retrieve investments from the 'invests' table, filtered by user ID and where status is pending, ordered by creation date in descending order
            return $this->db->select("invests", "*", [
                "userid" => $userid,
                'status' => 3,
                "ORDER" => ["initiated_at" => "DESC"],
                "LIMIT" => 5
            ]);
        } catch (Exception $e) {
            // Handle exceptions, such as database errors
            error_log('Error in getInvestsInitiated(): ' . $e->getMessage());
            return []; // Return an empty array if an error occurs
        }
    }

    /**
     * Gets invests with pagination
     *
     * This method retrieves invests for a specified user with pagination from the 'invests' table.
     *
     * @param int $userid The ID of the user.
     * @param int $page The page number for pagination.
     * @return array Returns an array of invested records.
     */
    public function initiated_investments_limits(int $userid, int $page): array
    {
        try {
            $limit = 5; // Number of investing per page
            $offset = ($page - 1) * $limit; // Calculate the offset based on the page number

            // Retrieve investing for the specified user with pagination from the 'invests' table
            return $this->db->select('invests', '*', [
                "userid" => $userid,
                'status' => 3,
                "ORDER" => ["initiated_at" => "DESC"],
                "LIMIT" => [$offset, $limit]
            ]);
        } catch (Exception $e) {
            // Handle exceptions, such as database errors
            error_log('Error in initiated_investments_limits(): ' . $e->getMessage());
            return []; // Return an empty array if an error occurs
        }
    }

    /**
     * Retrieve investments from the database for a given user.
     *
     * @param string $userid The user ID for whom to retrieve investments.
     * @return array The list of investments retrieved from the 'invests' table.
     */
    public function getInvestsCancelled(string $userid): array
    {
        try {
            // Retrieve investments from the 'invests' table, filtered by user ID and where status is pending, ordered by creation date in descending order
            return $this->db->select("invests", "*", [
                "userid" => $userid,
                'status' => 4,
                "ORDER" => ["initiated_at" => "DESC"],
                "LIMIT" => 5
            ]);
        } catch (Exception $e) {
            // Handle exceptions, such as database errors
            error_log('Error in getInvestsCancelled(): ' . $e->getMessage());
            return []; // Return an empty array if an error occurs
        }
    }

    /**
     * Gets invests with pagination
     *
     * This method retrieves invests for a specified user with pagination from the 'invests' table.
     *
     * @param int $userid The ID of the user.
     * @param int $page The page number for pagination.
     * @return array Returns an array of invested records.
     */
    public function cancelled_investments_limits(int $userid, int $page): array
    {
        try {
            $limit = 5; // Number of investing per page
            $offset = ($page - 1) * $limit; // Calculate the offset based on the page number

            // Retrieve investing for the specified user with pagination from the 'invests' table
            return $this->db->select('invests', '*', [
                "userid" => $userid,
                'status' => 4,
                "ORDER" => ["initiated_at" => "DESC"],
                "LIMIT" => [$offset, $limit]
            ]);
        } catch (Exception $e) {
            // Handle exceptions, such as database errors
            error_log('Error in cancelled_investments_limits(): ' . $e->getMessage());
            return []; // Return an empty array if an error occurs
        }
    }

    /**
     * Retrieve time settings from the database.
     *
     * @return array The time settings retrieved from the 'time_settings' table.
     */
    public function times(): array
    {
        try {
            // Retrieve time settings from the 'time_settings' table
            return $this->db->select("time_settings", "*", []);
        } catch (Exception $e) {
            // Handle exceptions, such as database errors
            error_log('Error in times(): ' . $e->getMessage());
            return []; // Return an empty array if an error occurs
        }
    }

    /**
     * Retrieve trade time settings from the database.
     *
     * @return array The trade time settings retrieved from the 'trade_time_settings' table.
     */
    public function trade_times(): array
    {
        try {
            // Retrieve trade time settings from the 'trade_time_settings' table
            return $this->db->select("trade_time_settings", "*", []);
        } catch (Exception $e) {
            // Handle exceptions, such as database errors
            error_log('Error in trade_times(): ' . $e->getMessage());
            return []; // Return an empty array if an error occurs
        }
    }

    /**
     * Check if a plan with the specified ID exists in the database.
     *
     * @param int $planId The ID of the plan to check.
     * @return bool True if a plan with the given ID exists, false otherwise.
     */
    public function hasPlanId(int $planId): bool
    {
        try {
            // Check if a plan with the specified ID exists in the 'plans' table
            return $this->db->has("plans", ["planId" => $planId]);
        } catch (Exception $e) {
            // Handle exceptions, such as database errors
            error_log('Error in hasPlanId(): ' . $e->getMessage());
            return false; // Return false if an error occurs
        }
    }

    /**
     * Check if an investment with the specified ID exists in the database.
     *
     * @param mixed $investId The ID of the investment to check.
     * @return bool True if an investment with the given ID exists, false otherwise.
     */
    public function hasInvestment(mixed $investId): bool
    {
        try {
            // Check if an investment with the specified ID exists in the 'invests' table
            return $this->db->has("invests", ["investId" => $investId]);
        } catch (Exception $e) {
            // Handle exceptions, such as database errors
            error_log('Error in hasInvestment(): ' . $e->getMessage());
            return false; // Return false if an error occurs
        }
    }

    /**
     * Add a new deposit record to the 'deposits' table.
     *
     * @param int $depositId The ID of the deposit.
     * @param string $userid The ID of the user making the deposit.
     * @param mixed $method_code The code representing the deposit method. Can be null or string depending on the data passed.
     * @param mixed $amount The amount of the deposit. Can be null or string depending on the data passed.
     * @param mixed $trx The transaction ID associated with the deposit. Can be null or string depending on the data passed.
     * @return int The number of rows affected by the insert operation.
     */
    public function deposit(int $depositId, string $userid, mixed $method_code, mixed $amount, mixed $trx): int
    {
        try {

            // Calculate next retry time
            $nextTime = Carbon::now()->addHours(24)->toDateTimeString();

            // Insert the deposit details into the 'deposits' table
            $insert = $this->db->insert('deposits', [
                'depositId' => $depositId,
                'userid' => $userid,
                'method_code' => $method_code,
                'amount' => $amount,
                'trx' => $trx,
                'created_at' => date('Y-m-d H:i:s'),
                'next_time' => $nextTime
            ]); 

            // insert transaction
            $this->db->insert('transactions', [
                'transactionId' => $depositId,
                'userid' => $userid,
                'trx_type' => "+",
                'trx_id' => $trx,
                'amount' => $amount,
                'wallet_type' => "deposit",
                'details' => "Deposit initiated, proceed to make payment.",
                'created_at' => date('Y-m-d H:i:s')
            ]);

            // Return the number of rows affected by the insert operation
            return $insert->rowCount();  
        } catch (Exception $e) {
            // Handle exceptions, such as database errors
            error_log('Error in deposit(): ' . $e->getMessage());
            return 0; // Return 0 if an error occurs
        }
    }

    /**
     * Update the status of a deposit in the database.
     *
     * @param string $depositId The deposit ID to update
     * @param string $userid The user ID associated with the deposit
     * @param mixed $balance The new balance after deposit
     * @param mixed $fileName The name of the file associated with the deposit
     * @param string $method_name The name of the deposit method
     * @return int The number of rows affected by the update operation
     */
    public function updateDeposit(string $depositId, string $userid, mixed $balance, mixed $fileName, string $method_name): int
    {
        try {
            // Update the status of the deposit to indicate it has been initiated
            $update = $this->db->update('deposits', [
                'userid' => $userid,
                'crypto_amount' => $balance,
                'payment_proof' => $fileName
            ], [
                'depositId' => $depositId
            ]);

            // Update transaction details
            $this->db->update('transactions', [
                'details' => "Deposit Via " . $method_name,
                'updated_at' => date('Y-m-d H:i:s')
            ], [
                'transactionId' => $depositId
            ]);

            // Return the number of rows affected by the update operation
            return $update->rowCount(); 
        } catch (Exception $e) {
            // Handle exceptions, such as database errors
            error_log('Error in updateDeposit(): ' . $e->getMessage());
            return 0; // Return 0 if an error occurs
        }
    }

    /**
     * Update the status of a deposit in the database and adds investment.
     *
     * @param string $depositId The deposit ID to update.
     * @param string $userid The user ID associated with the deposit.
     * @param mixed $balance The crypto balance associated with the deposit.
     * @param mixed $fileName The filename of the payment proof.
     * @param string $method_name The name of the deposit method.
     * @param mixed $planId The ID of the investment plan.
     * @param mixed $amount The amount of the investment.
     * @param float $interest_amount The total return of the investment.
     * @param int $repeat_time The number of times the investment repeats.
     * @param int $hours The duration of each investment cycle in hours.
     * @return int The number of rows affected by the update operation.
     */
    public function planPurchaseDeposit(string $depositId, string $userid, mixed $balance, mixed $fileName, string $method_name, mixed $planId, mixed $amount, float $interest_amount, int $repeat_time, int $hours, int $capital_back_status): int
    {
        try {
            
            // Generate a unique transaction ID
            $trx = $this->generateTransactionID();
            
            // Update the status of the deposit to indicate it has been initiated
            $update = $this->db->update('deposits', [
                'userid' => $userid,
                'crypto_amount' => $balance,
                'payment_proof' => $fileName
            ], [
                'depositId' => $depositId
            ]);

            // Insert investment details
            $this->db->insert('invests', [
                'investId' => $depositId,
                'userid' => $userid,
                'planId' => $planId,
                'amount' => $amount,
                'interest' => $interest_amount,
                'period' => $repeat_time,
                'hours' => $hours,
                'status' => 3,
                'capital_status' => $capital_back_status,
                'trx' => $trx
            ]); 

            // Update transaction details
            $this->db->update('transactions', [
                'details' => "Deposit Via " . $method_name,
                'updated_at' => date('Y-m-d H:i:s')
            ], [
                'transactionId' => $depositId
            ]);

            // Return the number of rows affected by the update operation
            return $update->rowCount(); 
        } catch (Exception $e) {
            // Handle exceptions, such as database errors
            error_log('Error in planPurchaseDeposit(): ' . $e->getMessage());
            return 0; // Return 0 if an error occurs
        }
    }

    /**
     * adds method code to a deposit in the database.
     *
     * @param string $depositId The deposit ID to update.
     * @param string $method_code The method code of the deposit method.
     * @return int The number of rows affected by the update operation.
     */
    public function addMethod(string $depositId, string $method_code): int
    {
        try {
            // Update the status of the deposit to indicate it has been initiated
            $update = $this->db->update('deposits', [
                'method_code' => $method_code
            ], [
                'depositId' => $depositId
            ]);

            // Return the number of rows affected by the update operation
            return $update->rowCount(); 
        } catch (Exception $e) {
            // Handle exceptions, such as database errors
            error_log('Error in addMethod(): ' . $e->getMessage());
            return 0; // Return 0 if an error occurs
        }
    }

    /**
     * Update the status of a deposit in the database.
     *
     * @param string $depositId The deposit ID to update.
     * @param string $userid The user ID associated with the deposit.
     * @param string $method_code The method code of the deposit method.
     * @return int The number of rows affected by the update operation.
     */
    public function updateDepositStatus(string $depositId, string $userid, string $method_code): int
    {
        try {
            // Update the status of the deposit to indicate it has been initiated
            $update = $this->db->update('deposits', [
                'userid' => $userid,
                'method_code' => $method_code,
                'status' => 2
            ], [
                'depositId' => $depositId
            ]);

            // Return the number of rows affected by the update operation
            return $update->rowCount(); 
        } catch (Exception $e) {
            // Handle exceptions, such as database errors
            error_log('Error in updateDepositStatus(): ' . $e->getMessage());
            return 0; // Return 0 if an error occurs
        }
    }

    /**
     * Get the deposit details for the specified deposit ID.
     *
     * @param int $depositId The ID of the deposit
     * @return array|null The details of the deposit, or null if not found
     */
    public function getDeposit(int $depositId): ?array
    {
        try {
            // Retrieve deposit details from the "deposits" table based on the deposit ID
            $row = $this->db->get("deposits", "*", ["depositId" => $depositId]);

            // Return the deposit details or null if not found
            return $row ?: null;
        } catch (Exception $e) {
            // Handle exceptions, such as database errors
            error_log('Error in getDeposit(): ' . $e->getMessage());
            return []; // Return null if an error occurs
        }
    }

    /**
     * Get the property details for the specified property ID.
     *
     * @param int $propertyId The ID of the property
     * @return array|null The details of the property, or null if not found
     */
    public function getProperty(int $propertyId): ?array
    {
        try {
            // Retrieve property details from the "listings" table based on the deposit ID
            $row = $this->db->get("listings", "*", ["propertyId" => $propertyId]);

            // Return the property details or null if not found
            return $row ?: null;
        } catch (Exception $e) {
            // Handle exceptions, such as database errors
            error_log('Error in getProperty(): ' . $e->getMessage());
            return []; // Return null if an error occurs
        }
    }

    /**
     * Get the service details for the specified service ID.
     *
     * @param int $serviceId The ID of the service
     * @return array|null The details of the service, or null if not found
     */
    public function getService(int $serviceId): ?array
    {
        try {
            // Retrieve service details from the "services" table based on the deposit ID
            $row = $this->db->get("services", "*", ["serviceId" => $serviceId]);

            // Return the service details or null if not found
            return $row ?: null;
        } catch (Exception $e) {
            // Handle exceptions, such as database errors
            error_log('Error in getService(): ' . $e->getMessage());
            return []; // Return null if an error occurs
        }
    }

    /**
     * Get the deposit amount for the specified deposit ID.
     *
     * @param int $depositId The ID of the deposit
     * @return array|null The details of the deposit, or null if not found
     */
    public function getDepositAmount(int $depositId): ?array
    {
        try {
            // Retrieve information about the specified deposit from the 'deposits' table
            $query = $this->db->get('deposits', '*', ["AND" => ["depositId" => $depositId]]);
            
            // If $query is null or empty, return an empty array
            if (!$query) {
                return [];
            }

            return $query;
        } catch (Exception $e) {
            // Handle exceptions, such as database errors
            error_log('Error in getDepositAmount(): ' . $e->getMessage());
            return []; // Return null to indicate failure
        }
    }

    /**
     * Add a new payout record to the 'withdrawals' table.
     *
     * @param int $withdrawId The ID of the withdrawal.
     * @param string $userid The ID of the user making the withdrawal.
     * @param mixed $withdraw_code The code representing the withdrawal method. Can be null or string depending on the data passed.
     * @param mixed $amount The amount of the withdrawal. Can be null or string depending on the data passed.
     * @param mixed $trx The transaction ID associated with the withdrawal. Can be null or string depending on the data passed.
     * @return int The number of rows affected by the insert operation.
     */
    public function payout(int $withdrawId, string $userid, mixed $withdraw_code, mixed $amount, mixed $trx): int
    {
        try {
            // Insert the withdrawal details into the 'withdrawals' table
            $insert = $this->db->insert('withdrawals', [
                'withdrawId' => $withdrawId,
                'userid' => $userid,
                'withdraw_code' => $withdraw_code,
                'amount' => $amount,
                'trx' => $trx,
                'created_at' => date('Y-m-d H:i:s')
            ]); 

            // Insert transaction
            $this->db->insert('transactions', [
                'transactionId' => $withdrawId,
                'userid' => $userid,
                'trx_type' => "-",
                'trx_id' => $trx,
                'amount' => $amount,
                'wallet_type' => "interest_wallet",
                'details' => "Withdrawal initiated",
                'created_at' => date('Y-m-d H:i:s')
            ]);

            // Return the number of rows affected by the insert operation
            return $insert->rowCount();  
        } catch (Exception $e) {
            // Handle exceptions, such as database errors
            error_log('Error in payout(): ' . $e->getMessage());
            return 0; // Return 0 if an error occurs
        }
    }

    /**
     * Update the status of a withdrawal in the database.
     *
     * @param string $withdrawId The withdrawal ID to update.
     * @param string $userid The user ID associated with the withdrawal.
     * @param mixed $balance The balance to update with the withdrawal.
     * @param string $wallet The wallet address to update with the withdrawal.
     * @param string $method_name The name of the withdrawal method
     * @return int The number of rows affected by the update operation.
     */
    public function updatePayout(string $withdrawId, string $userid, mixed $balance, string $wallet, string $method_name): int
    {
        try {
            // Update the status of the withdrawal to indicate it has been initiated
            $update = $this->db->update('withdrawals', [
                'userid' => $userid,
                'crypto_amount' => $balance,
                'wallet_address' => $wallet,
                'status' => 2 
            ], [
                'withdrawId' => $withdrawId
            ]);

             // Update transaction details
            $this->db->update('transactions', [
                'details' => "Withdrawn Via " . $method_name,
                'updated_at' => date('Y-m-d H:i:s')
            ], [
                'transactionId' => $withdrawId
            ]);

            // Return the number of rows affected by the update operation
            return $update->rowCount();
        } catch (Exception $e) {
            // Handle exceptions, such as database errors
            error_log('Error in updatePayout(): ' . $e->getMessage());
            return 0; // Return 0 if an error occurs
        }
    }

    /**
     * Update the status of a withdrawal in the database.
     *
     * @param string $withdrawId The withdrawal ID to update.
     * @param string $userid The user ID associated with the withdrawal.
     * @return int The number of rows affected by the update operation.
     */
    public function updatePayoutStatus(string $withdrawId, string $userid): int
    {
        try {
            // Update the status of the withdrawal to indicate it has been initiated
            $update = $this->db->update('withdrawals', [
                'userid' => $userid,
                'status' => 2 
            ], [
                'withdrawId' => $withdrawId
            ]);

            // Return the number of rows affected by the update operation
            return $update->rowCount();
        } catch (Exception $e) {
            // Handle exceptions, such as database errors
            error_log('Error in updatePayoutStatus(): ' . $e->getMessage());
            return 0; // Return 0 if an error occurs
        }
    }

    /**
     * Check if a withdrawal method with the given withdraw code exists in the database.
     *
     * @param string $withdraw_code The withdrawal code to check for existence
     * @return bool True if the withdrawal code exists, false otherwise
     */
    public function hasWithdrawMethod(string $withdraw_code): bool
    {
        try {
            // Check if the specified withdraw code exists in the "withdraw_methods" table
            return $this->db->has("withdraw_methods", ["withdraw_code" => $withdraw_code]);
        } catch (Exception $e) {
            // Handle exceptions, such as database errors
            error_log('Error in hasWithdrawMethod(): ' . $e->getMessage());
            return false; // Return false if an error occurs
        }
    }

    /**
     * Get the withdrawal details for the specified withdraw method code.
     *
     * @param string $withdraw_code The withdrawal code of the withdrawal method
     * @return array|null The details of the withdrawal method, or null if not found
     */
    public function getWithdrawMethod(string $withdraw_code): ?array
    {
        try {
            // Retrieve deposit details from the "withdraw_methods" table based on the withdrawal code
            $row = $this->db->get("withdraw_methods", "*", ["withdraw_code" => $withdraw_code]);

            // Return the withdrawal method details or null if not found
            return $row ?: null;
        } catch (Exception $e) {
            // Handle exceptions, such as database errors
            error_log('Error in getWithdrawMethod(): ' . $e->getMessage());
            return []; // Return null if an error occurs
        }
    }

    /**
     * Check if a deposit with the given depositId exists in the database.
     *
     * @param string $depositId The depositId to check for existence
     * @return bool True if the depositId exists, false otherwise
     */
    public function hasDepositId(string $depositId): bool
    {
        try {
            // Check if the specified depositId exists in the "deposits" table
            return $this->db->has("deposits", ["depositId" => $depositId]);
        } catch (Exception $e) {
            // Handle exceptions, such as database errors
            error_log('Error in hasDepositId(): ' . $e->getMessage());
            return false; // Return false if an error occurs
        }
    }

    /**
     * Check if a listings with the given propertyId exists in the database.
     *
     * @param string $propertyId The propertyId to check for existence
     * @return bool True if the propertyId exists, false otherwise
     */
    public function hasPropertyId(string $propertyId): bool
    {
        try {
            // Check if the specified propertyId exists in the "listings" table
            return $this->db->has("listings", ["propertyId" => $propertyId]);
        } catch (Exception $e) {
            // Handle exceptions, such as database errors
            error_log('Error in hasPropertyId(): ' . $e->getMessage());
            return false; // Return false if an error occurs
        }
    }

    /**
     * Check if a withdrawal with the given withdrawal ID exists in the database.
     *
     * @param string $withdrawId The withdrawal ID to check for existence
     * @return bool True if the withdrawal ID exists, false otherwise
     */
    public function hasWithdrawalId(string $withdrawId): bool
    {
        try {
            // Check if the specified withdrawal ID exists in the "withdrawals" table
            return $this->db->has("withdrawals", ["withdrawId" => $withdrawId]);
        } catch (Exception $e) {
            // Handle exceptions, such as database errors
            error_log('Error in hasWithdrawalId(): ' . $e->getMessage());
            return false; // Return false if an error occurs
        }
    }

    /**
     * Get the withdrawal details for the specified withdrawal method code.
     *
     * @param string $withdraw_code The method code of the withdrawal method
     * @return array|null The details of the withdrawal method, or null if not found
     */
    public function hasWithdrawalMethod(string $withdraw_code): ?array
    {
        try {
            // Retrieve withdrawal details from the "withdrawals" table based on the method code
            $row = $this->db->get("withdrawals", "*", ["withdraw_code" => $withdraw_code]);

            // Return the withdrawal method details or null if not found
            return $row ?: null;
        } catch (Exception $e) {
            // Handle exceptions, such as database errors
            error_log('Error in hasWithdrawalMethod(): ' . $e->getMessage());
            return []; // Return null if an error occurs
        }
    }

    /**
     * Get the withdrawal details for the specified withdrawal ID.
     *
     * @param int $withdrawId The ID of the withdrawal
     * @return array|null The details of the withdrawal, or null if not found
     */
    public function getWithdrawal(int $withdrawId): ?array
    {
        try {
            // Retrieve withdrawal details from the "withdrawals" table based on the withdrawal ID
            $row = $this->db->get("withdrawals", "*", ["withdrawId" => $withdrawId]);

            // Return the withdrawal details or null if not found
            return $row ?: null;
        } catch (Exception $e) {
            // Handle exceptions, such as database errors
            error_log('Error in getWithdrawal(): ' . $e->getMessage());
            return []; // Return null if an error occurs
        }
    }
    
    /**
     * Check if a user with the specified token is available in the database.
     *
     * @param string $token The token to check for.
     * @return bool True if a user with the token exists, false otherwise.
     */
    public function hasToken(string $token): bool
    {
        try {
            // Return true if a user with the token exists, otherwise false
            return $this->db->has("user", ["reset_code" => $token]);
        } catch (Exception $e) {
            // Handle exceptions, such as database errors
            error_log('Error in hasToken(): ' . $e->getMessage());
            return false; // Return false if an error occurs
        }
    }

    /**
     * Get the user associated with the specified token.
     *
     * @param string $token The token to search for.
     * @return array|null An array representing the user's data if found, or null if not found.
     */
    public function getWithToken(string $token): ?array
    {
        try {
            // Select user data from the "user" table where the reset_code matches the provided token
            $row = $this->db->get("user", "*", ["reset_code" => $token]);

            // Return the user's data array or null if no user is found
            return $row ?: null;
        } catch (Exception $e) {
            // Handle exceptions, such as database errors
            error_log('Error in getWithToken(): ' . $e->getMessage());
            return []; // Return null if an error occurs
        }
    }

    /**
     * Retrieves the user's identity proof document.
     *
     * @param string $userid The userid to search for.
     * @return array|null An array representing the user's identity proof data if found, or null if not found.
     */
    public function identity_proof(string $userid): ?array
    {
        try {
            // Select user data from the "identity_proof" table where the userid matches the provided userid
            // and the identity type is either 1, 2, or 3 (assuming these represent identity proof identity_proof)
            $row = $this->db->get("identity_proof", "*", ["userid" => $userid]);

            // Return the user's data array or null if no user is found
            return $row ?: null;
        } catch (Exception $e) {
            // Handle exceptions, such as database errors
            error_log('Error in identity_proof(): ' . $e->getMessage());
            return []; // Return null if an error occurs
        }
    }

    /**
     * Retrieves the user's personal address document.
     *
     * @param string $userid The userid to search for.
     * @return array|null An array representing the user's personal address data if found, or null if not found.
     */
    public function personal_address(string $userid): ?array
    {
        try {
            // Select user data from the "address_proof" table where the userid matches the provided userid,
            // and the identity type is 4 (assuming this represents personal address document)
            $row = $this->db->get("address_proof", "*", ["userid" => $userid]);

            // Return the user's data array or null if no user is found
            return $row ?: null;
        } catch (Exception $e) {
            // Handle exceptions, such as database errors
            error_log('Error in personal_address(): ' . $e->getMessage());
            return []; // Return null if an error occurs
        }
    }

    /**
     * Add Identity
     *
     * This method adds an identity record to the database and updates user information if necessary.
     *
     * @param string $uploadid The ID of the file upload.
     * @param string $userid The ID of the user who uploaded the file.
     * @param string $identity_type The type of identity document.
     * @param string $identity_number The identity number.
     * @param string $filename The name of the file.
     * @param string $type The type of file.
     * @param string $ext The file extension.
     * @param int $new_size The size of the file.
     * @return int The number of rows affected by the insert or update operation.
     */
    public function addIdentity(string $uploadid, string $userid, string $identity_type, string $identity_number, string $filename, string $type, string $ext, int $new_size): int
    {
        try {
            // Check if user exists in identity_proof table
            $user_row = $this->db->select('identity_proof', '*', [
                "userid" => $userid,
                "identity_type" => [1, 2, 3],
            ]);

            if (!$user_row) {
                // User doesn't exist in identity_proof table, so insert a new row
                $insert = $this->db->insert('identity_proof', [
                    'uploadid' => $uploadid,
                    'userid' => $userid,
                    'identity_type' => $identity_type,
                    'identity_number' => $identity_number,
                    'fileupload' => $filename,
                    'type' => $type,
                    'extension' => $ext,
                    'size' => $new_size,
                    'status' => 2,
                    'date_added' => date('Y-m-d H:i:s')
                ]);

                // Update the user's account verify status to 2
                $this->db->update('user', ['account_verify' => 2], ['userid' => $userid]);
            } else {
                // User exists in identity_proof table, so update existing row
                $update = $this->db->update('identity_proof', [
                    'uploadid' => $uploadid,
                    'identity_type' => $identity_type,
                    'identity_number' => $identity_number,
                    'fileupload' => $filename,
                    'type' => $type,
                    'extension' => $ext,
                    'size' => $new_size,
                    'status' => 2,
                    'date_added' => date('Y-m-d H:i:s')
                ], [
                    'userid' => $userid,
                    'identity_type' => [1, 2, 3],
                ]);

                // Check if the update operation was successful
                if (!$update->rowCount()) {
                    // If the update affected no rows, insert a new row
                    $insert = $this->db->insert('identity_proof', [
                        'uploadid' => $uploadid,
                        'userid' => $userid,
                        'identity_type' => $identity_type,
                        'identity_number' => $identity_number,
                        'fileupload' => $filename,
                        'type' => $type,
                        'extension' => $ext,
                        'size' => $new_size,
                        'status' => 2,
                        'date_added' => date('Y-m-d H:i:s')
                    ]);

                    // Update the user's account verify status to 2
                    $this->db->update('user', ['account_verify' => 2], ['userid' => $userid]);
                }
            }

            // Return the number of rows affected by the insert or update operation
            return isset($insert) ? $insert->rowCount() : $update->rowCount();
        } catch (Exception $e) {
            // Handle exceptions, such as database errors
            error_log('Error in addIdentity(): ' . $e->getMessage());
            return 0; // Return 0 to indicate failure
        }
    }

    /**
     * Add Address
     *
     * This method adds an identity record to the database and updates user information if necessary.
     *
     * @param string $uploadid The ID of the file upload.
     * @param string $userid The ID of the user who uploaded the file.
     * @param string $filename The name of the file.
     * @param string $type The type of file.
     * @param string $ext The file extension.
     * @param int $new_size The size of the file.
     * @return int The number of rows affected by the insert or update operation.
     */
    public function addAddress(string $uploadid, string $userid, string $filename, string $type, string $ext, int $new_size): int
    {
        try {
            // Check if user exists in the address_proof table
            $user_row = $this->db->select('address_proof', '*', [
                "userid" => $userid,
                "identity_type" => 4,
            ]);

            if (!$user_row) {
                // User doesn't exist in address_proof table, so insert a new row
                $insert = $this->db->insert('address_proof', [
                    'uploadid' => $uploadid,
                    'userid' => $userid,
                    'identity_type' => 4,
                    'fileupload' => $filename,
                    'type' => $type,
                    'extension' => $ext,
                    'size' => $new_size,
                    'status' => 2,
                    'date_added' => date('Y-m-d H:i:s')
                ]);

                // Update the user's account verify status to 2
                $this->db->update('user', ['account_verify' => 2], ['userid' => $userid]);
            } else {
                // User exists in address_proof table, so update existing row
                $update = $this->db->update('address_proof', [
                    'uploadid' => $uploadid,
                    'identity_type' => 4,
                    'fileupload' => $filename,
                    'type' => $type,
                    'extension' => $ext,
                    'size' => $new_size,
                    'status' => 2,
                    'date_added' => date('Y-m-d H:i:s')
                ], [
                    'userid' => $userid,
                    'identity_type' => 4,
                ]);

                // Check if the update operation was successful
                if (!$update->rowCount()) {
                    // If the update affected no rows, insert a new row
                    $insert = $this->db->insert('address_proof', [
                        'uploadid' => $uploadid,
                        'userid' => $userid,
                        'identity_type' => 4,
                        'fileupload' => $filename,
                        'type' => $type,
                        'extension' => $ext,
                        'size' => $new_size,
                        'status' => 2,
                        'date_added' => date('Y-m-d H:i:s')
                    ]);

                    // Update the user's account verify status to 2
                    $this->db->update('user', ['account_verify' => 2], ['userid' => $userid]);
                }
            }

            // Return the number of rows affected by the insert or update operation
            return isset($insert) ? $insert->rowCount() : $update->rowCount();
        } catch (Exception $e) {
            // Handle exceptions, such as database errors
            error_log('Error in addAddress(): ' . $e->getMessage());
            return 0; // Return 0 to indicate failure
        }
    }

    /**
     * Check if a user with the specified token exists in the password_resets table and has status 1.
     *
     * @param string $token The token to check for.
     * @return bool True if a user with the token and status 1 exists, false otherwise.
     */
    public function hasTokenApproved(string $token): bool
    {
        try {
            // Check if the token exists and the status is 1 in the password_resets table
            return $this->db->has("password_resets", ["AND" => ["token" => $token, "status" => 1]]);
        } catch (Exception $e) {
            // Handle exceptions, such as database errors
            error_log('Error in hasTokenApproved(): ' . $e->getMessage());
            return false; // Return false to indicate failure
        }
    }

    /**
     * Reset the password of a user.
     *
     * @param string $password The new password.
     * @param string $token The token associated with the password reset request.
     * @param int $id The ID of the user whose password needs to be updated.
     * @return int The number of rows affected by the password reset operation.
     */
    public function resetPassword(string $password, string $token, int $id): int
    {
        try {
            // Update the password in the "user" table for the user with the specified ID
            $update = $this->db->update('user', [
                'password' => $password,
            ], [
                'userid' => $id
            ]);

            // Update the status of the password reset request to 1 in the "password_resets" table
            $this->db->update('password_resets', [
                'status' => 1,
            ], [
                'token' => $token
            ]);

            // Return the number of rows affected by the password reset operation
            return $update->rowCount(); 
        } catch (Exception $e) {
            // Handle exceptions, such as database errors
            error_log('Error in resetPassword(): ' . $e->getMessage());
            return 0; // Return 0 to indicate failure
        }
    }

    /**
     * Store a password reset code for a user.
     *
     * @param int $id The ID of the user.
     * @param string $code The password reset code.
     * @return int The number of rows affected by the operation.
     */
    public function storeResetCode(int $id, string $code): int
    {
        try {
            // Update the 'reset_code' field in the 'user' table for the user with the specified ID
            $update = $this->db->update('user', [
                'reset_code' => $code,
            ], [
                'userid' => $id
            ]);

            // Insert a record into the 'password_resets' table
            $this->db->insert('password_resets', [
                'userid' => $id,
                'token' => $code,
                'created_at' => date('Y-m-d H:i:s')
            ]); 

            // Return the number of rows affected by the operation
            // It's the sum of rows affected by updating the user's 'reset_code' field and inserting into 'password_resets' table
            return $update->rowCount();
        } catch (Exception $e) {
            // Handle exceptions, such as database errors
            error_log('Error in storeResetCode(): ' . $e->getMessage());
            return 0; // Return 0 to indicate failure
        }
    }

    /**
     * Check if the identity_proof is available in the database
     *
     * This method checks if a specific identity_proof is available for a user in the database.
     *
     * @param int $id The ID of the identity_proof.
     * @param int $userid The ID of the user.
     * @return bool Returns true if the identity_proof is available, false otherwise.
     */
    public function has_identity_proof(int $id, int $userid): bool
    {
        try {
            // Check if the identity_proof exists in the 'identity_proof' table for the specified user
            return $this->db->has("identity_proof", ["AND" => ["id" => $id, "userid" => $userid]]);
        } catch (Exception $e) {
            // Handle exceptions, such as database errors
            error_log('Error in has_identity_proof(): ' . $e->getMessage());
            return false; // Return false to indicate failure
        }
    }

    /**
     * Downloads File
     *
     * This method retrieves information about a specific file from the database.
     *
     * @param int $id The ID of the file.
     * @return array|null An array containing file information, or null if the file is not found.
     */
    public function get_identity_proof(int $id): ?array
    {
        try {
            // Retrieve information about the specified file from the 'identity_proof' table
            $query = $this->db->get('identity_proof', '*', ["AND" => ["id" => $id]]);

            // If $query is null or empty, return an empty array
            if (!$query) {
                return [];
            }

            return $query;
        } catch (Exception $e) {
            // Handle exceptions, such as database errors
            error_log('Error in get_identity_proof(): ' . $e->getMessage());
            return []; // Return null to indicate failure
        }
    }

    /**
     * Check if the address_proof is available in the database
     *
     * This method checks if a specific address_proof is available for a user in the database.
     *
     * @param int $id The ID of the address_proof.
     * @param int $userid The ID of the user.
     * @return bool Returns true if the address_proof is available, false otherwise.
     */
    public function has_address_proof(int $id, int $userid): bool
    {
        try {
            // Check if the address_proof exists in the 'address_proof' table for the specified user
            return $this->db->has("address_proof", ["AND" => ["id" => $id, "userid" => $userid]]);
        } catch (Exception $e) {
            // Handle exceptions, such as database errors
            error_log('Error in has_address_proof(): ' . $e->getMessage());
            return false; // Return false to indicate failure
        }
    }

    /**
     * Downloads File
     *
     * This method retrieves information about a specific file from the database.
     *
     * @param int $id The ID of the file.
     * @return array|null An array containing file information, or null if the file is not found.
     */
    public function get_address_proof(int $id): ?array
    {
        try {
            // Retrieve information about the specified file from the 'address_proof' table
            $query = $this->db->get('address_proof', '*', ["AND" => ["id" => $id]]);

            // If $query is null or empty, return an empty array
            if (!$query) {
                return [];
            }

            return $query;
        } catch (Exception $e) {
            // Handle exceptions, such as database errors
            error_log('Error in get_address_proof(): ' . $e->getMessage());
            return []; // Return null to indicate failure
        }
    }

    /**
     * Update the User Profile Details
     *
     * This method updates the profile details of a user in the 'user' table.
     *
     * @param string $filename The filename of the profile image.
     * @param int $userid The ID of the user.
     * @return int The number of rows affected by the update operation.
     */
    public function profileDetails(string $filename, int $userid): int
    {
        try {
            // Update the profile details of the user in the 'user' table
            $update = $this->db->update('user',[
               'imagelocation' => $filename,
            ],[
                'userid' => $userid
            ]);

            return $update->rowCount();
        } catch (Exception $e) {
            // Handle exceptions, such as database errors
            error_log('Error in profileDetails(): ' . $e->getMessage());
            return 0; // Return 0 to indicate failure
        }
    }

    /**
     * Update the User Panel Password
     *
     * This method updates the password of a user in the 'user' table.
     *
     * @param string $password The new password.
     * @param int $userid The ID of the user.
     * @return int The number of rows affected by the update operation.
     */
    public function password(string $password, int $userid): int
    {
        try {
            // Update the password of the user in the 'user' table
            $update = $this->db->update('user',[
               'password' => $password,
            ],[
                'userid' => $userid
            ]);

            return $update->rowCount();
        } catch (Exception $e) {
            // Handle exceptions, such as database errors
            error_log('Error in password(): ' . $e->getMessage());
            return 0; // Return 0 to indicate failure
        }
    }

    /**
     * Update the User Currency
     *
     * This method updates the currency of a user in the 'user' table.
     *
     * @param string $currency The new currency.
     * @param int $userid The ID of the user.
     * @return int The number of rows affected by the update operation.
     */
    public function currency(string $currency, int $userid): int
    {
        try {
            // Update the currency of the user in the 'user' table
            $update = $this->db->update('user',[
               'currency' => $currency,
            ],[
                'userid' => $userid
            ]);

            return $update->rowCount();
        } catch (Exception $e) {
            // Handle exceptions, such as database errors
            error_log('Error in currency(): ' . $e->getMessage());
            return 0; // Return 0 to indicate failure
        }
    }

    /**
     * Update the User Phone Number
     *
     * This method updates the phone number of a user in the 'user' table.
     *
     * @param string $formattedPhone The new formatted phone number.
     * @param string $country The country of the phone number.
     * @param int $userid The ID of the user.
     * @return int The number of rows affected by the update operation.
     */
    public function phone(string $formattedPhone, string $country, int $userid): int
    {
        try {
            // Update the phone number of the user in the 'user' table
            $update = $this->db->update('user', [
                'phone' => $formattedPhone,
                'country' => $country,
            ], [
                'userid' => $userid
            ]);

            return $update->rowCount();
        } catch (Exception $e) {
            // Handle exceptions, such as database errors
            error_log('Error in phone(): ' . $e->getMessage());
            return 0; // Return 0 to indicate failure
        }
    }

    /**
     * Update the User 2-factor Authentication Method
     *
     * This method updates the 2-factor Authentication of a user in the 'user' table.
     *
     * @param string $twofactor_status The auth type.
     * @param int $userid The ID of the user.
     * @return int The number of rows affected by the update operation.
     */
    public function twofactor(string $twofactor_status, int $userid): int
    {
        try {
            // Update the phone number of the user in the 'user' table
            $update = $this->db->update('user', [
                'twofactor_status' => $twofactor_status,
            ], [
                'userid' => $userid
            ]);

            return $update->rowCount();
        } catch (Exception $e) {
            // Handle exceptions, such as database errors
            error_log('Error in twofactor(): ' . $e->getMessage());
            return 0; // Return 0 to indicate failure
        }
    }

    /**
     * Update the User 2-factor Authentication Method
     *
     * This method updates the 2-factor Authentication of a user in the 'user' table.
     *
     * @param int $userid The ID of the user.
     * @return int The number of rows affected by the update operation.
     */
    public function updateTwofactor(int $userid): int
    {
        try {
            // Update the phone number of the user in the 'user' table
            $update = $this->db->update('user', [
                'twofactor_flag' => 2
            ], [
                'userid' => $userid
            ]);

            return $update->rowCount();
        } catch (Exception $e) {
            // Handle exceptions, such as database errors
            error_log('Error in updateTwofactor(): ' . $e->getMessage());
            return 0; // Return 0 to indicate failure
        }
    }

    /**
     * Add User
     *
     * This method adds a new user to the 'user' table.
     *
     * @param int $userid The ID of the user.
     * @param string $password The password of the user.
     * @param mixed $referralId The referral ID of the user. Can be null or string depending on the data passed.
     * @param string $email The email address of the user.
     * @param string $firstname The first name of the user.
     * @param string $lastname The last name of the user.
     * @param string $formattedPhone The formatted phone number of the user.
     * @param string $country The country of the user.
     * @return int The number of rows affected by the insert operation.
     */
    public function register(int $userid, string $password, mixed $referralId, string $email, string $firstname, string $lastname, string $formattedPhone, string $country): int
    {
        try {
            // Default profile image filename
            $filename = "default.png";

            // Insert the user details into the 'user' table
            $insert = $this->db->insert('user', array(
                'userid' => $userid,
                'password' => $password,
                'ref_by' => $referralId,
                'email' => $email,
                'firstname' => $firstname,
                'lastname' => $lastname,
                'phone' => $formattedPhone,
                'country' => $country,
                'imagelocation' => $filename,
                'registration_date' => date('Y-m-d H:i:s')
            )); 

            return $insert->rowCount();
        } catch (Exception $e) {
            // Handle exceptions, such as database errors
            error_log('Error in register(): ' . $e->getMessage());
            return 0; // Return 0 to indicate failure
        }
    }

    /**
     * Update the user
     * This method updates user information in the database.
     *
     * @param string $firstname The user's first name.
     * @param string $lastname The user's last name.
     * @param string $address_1 The user's address line 1.
     * @param string $address_2 The user's address line 2.
     * @param string $country The user's country.
     * @param string $city The user's city.
     * @param string $state The user's state.
     * @param string $timezone The user's timezone.
     * @param int $id The user's ID.
     * @return int Returns the number of rows affected by the update operation.
     */
    public function updateuser(string $firstname, string $lastname, string $address_1, string $address_2, string $country, string $city, string $state, string $timezone, int $id): int
    {
        try {
            // Perform the database update operation
            $Update = $this->db->update('user', [
                'firstname' => $firstname,
                'lastname' => $lastname,
                'address_1' => $address_1,
                'address_2' => $address_2,
                'country' => $country,
                'city' => $city,
                'state' => $state,
                'timezone' => $timezone
            ], [
                'userid' => $id
            ]);

            return $Update->rowCount();
        } catch (Exception $e) {
            // Handle exceptions, such as database errors
            error_log('Error in updateuser(): ' . $e->getMessage());
            return 0; // Return 0 to indicate failure
        }
    }

    /**
     * Check if a user with the provided referral ID exists in the database.
     *
     * @param string $referralId The referral ID to check.
     * @return bool True if a user with the referral ID exists, false otherwise.
     */
    public function isValidReferral(string $referralId): bool
    {
        try {
            // Check if the user with the given referral ID exists
            return $this->db->has("user", ["userid" => $referralId]);
        } catch (Exception $e) {
            // Handle exceptions, such as database errors
            error_log('Error in isValidReferral(): ' . $e->getMessage());
            return false; // Return false to indicate failure
        }
    }

    /**
     * Retrieve user data based on the provided referral ID.
     *
     * @param string $referralId The referral ID to retrieve user data.
     * @return array|null User data if found, null otherwise.
     */
    public function getRef(string $referralId): ?array
    {
        try {
            // Retrieve user data based on the referral ID
            $query = $this->db->get("user", "*", ["userid" => $referralId]);

            // If $query is null or empty, return an empty array
            if (!$query) {
                return [];
            }

            return $query;
        } catch (Exception $e) {
            // Handle exceptions, such as database errors
            error_log('Error in getRef(): ' . $e->getMessage());
            return []; // Return null to indicate failure
        }
    }

    /**
     * Check if a user attempted to register with an email address already registered by an admin.
     *
     * @param string $email The email address to check.
     * @return bool True if the email address is registered by an admin, false otherwise.
     */
    public function hasAdminEmail(string $email): bool
    {
        try {
            // Check if the email address is registered by an admin
            return $this->db->has("admin", ["email" => $email]);
        } catch (Exception $e) {
            // Handle exceptions, such as database errors
            error_log('Error in hasAdminEmail(): ' . $e->getMessage());
            return false; // Return false to indicate failure
        }
    }

    /**
     * Complete account setup.
     * 
     * This function updates the user's account type in the database
     * to complete the account setup process.
     *
     * @param int $userid The ID of the user whose account is being updated.
     * @return int The number of rows affected by the database update operation.
     */
    public function add_account(int $userid, string $qr_image): int
    {
        try {
            // Update the 'user' table in the database
            $update = $this->db->update(
                'user',
                [
                    'qr_image' => $qr_image
                ],
                [
                    'userid' => $userid
                ]
            );

            // Return the number of rows affected by the database update operation
            return $update->rowCount();
        } catch (Exception $e) {
            // Handle exceptions, such as database errors
            error_log('Error in add_account(): ' . $e->getMessage());
            return 0; // Return 0 to indicate failure
        }
    }

    /**
     * Add sign up bonus
     * 
     * This function adds a sign-up bonus to the user's interest wallet in the database.
     *
     * @param int $userid The ID of the user to whom the sign-up bonus is being added.
     * @param float $bonus The amount of the sign-up bonus to be added.
     * @return int The number of rows affected by the database update operation.
     */
    public function add_bonus(int $userid, float $bonus): int
    {
        try {

            // Generate a unique transaction ID
            $trx = $this->generateTransactionID();

            $bonusId = $this->uniqueid();

            // Update the 'interest_wallet' field for the user in the 'user' table
            $update = $this->db->update(
                'user',
                [
                    'interest_wallet' => $bonus
                ],
                [
                    'userid' => $userid
                ]
            );

            // Insert transaction
            $this->db->insert('transactions', [
                'transactionId' => $bonusId,
                'userid' => $userid,
                'trx_type' => "+",
                'trx_id' => $trx,
                'amount' => $bonus,
                'post_balance' => $bonus,
                'wallet_type' => "interest_wallet",
                'details' => "You have received " . $bonus . " Sign Up Bonus",
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]);

            // Return the number of rows affected by the database update operation
            return $update->rowCount();
        } catch (Exception $e) {
            // Log any exceptions that occur during the database update operation
            error_log('Error in add_bonus(): ' . $e->getMessage());
            return 0; // Return 0 to indicate failure
        }
    }

    /**
     * Check if a user with the same email exists in the database
     *
     * This method checks if a user with the specified email exists in the 'user' table.
     *
     * @param string $email The email address to check.
     * @return bool Returns true if a user with the email exists, false otherwise.
     */
    public function hasEmail(string $email): bool
    {
        try {
            // Check if a user with the specified email exists in the 'user' table
            // Return true if a user with the email exists, false otherwise
            return $this->db->has("user", ["email" => $email]);
        } catch (Exception $e) {
            // Handle exceptions, such as database errors
            error_log('Error in hasEmail(): ' . $e->getMessage());
            return false; // Return false to indicate failure
        }
    }

    /**
     * Check if a user with the same phone exists in the database
     *
     * This method checks if a user with the specified phone exists in the 'user' table.
     *
     * @param string $phone The phone address to check.
     * @return bool Returns true if a user with the phone exists, false otherwise.
     */
    public function hasPhone(string $phone): bool
    {
        try {
            // Check if a user with the specified phone exists in the 'user' table
            // Return true if a user with the phone exists, false otherwise
            return $this->db->has("user", ["phone" => $phone]);
        } catch (Exception $e) {
            // Handle exceptions, such as database errors
            error_log('Error in hasPhone(): ' . $e->getMessage());
            return false; // Return false to indicate failure
        }
    }

    /**
     * Get the user details by email
     *
     * This method retrieves user details by email from the 'user' table.
     *
     * @param string $email The email address of the user.
     * @return array The user details.
     */
    public function getEmail(string $email): array
    {
        try {
            // Retrieve user details by email from the 'user' table
            $query = $this->db->get("user", "*", ["email" => $email]);
               
            // If $query is null or empty, return an empty array
            if (!$query) {
                return [];
            }

            return $query; 
        } catch (Exception $e) {
            // Handle exceptions, such as database errors
            error_log('Error in getEmail(): ' . $e->getMessage());
            return []; // Return an empty array to indicate failure
        }
    }

    /**
     * Get the forgot password code for a user
     *
     * This method retrieves the forgot password code for a user from the 'user' table.
     *
     * @param int $id The ID of the user.
     * @return array The forgot password code or null if not found.
     */
    public function getForgotPasswordCode(int $id): array
    {
        try {
            // Retrieve the forgot password code for the user from the 'user' table
            return $this->db->get('user', 'reset_code', ['userid' => $id]);
        } catch (Exception $e) {
            // Handle exceptions, such as database errors
            error_log('Error in getForgotPasswordCode(): ' . $e->getMessage());
            return []; // Return null to indicate failure
        }
    }

    /**
     * Update the password for a user
     *
     * This method updates the password for a user in the 'user' table.
     *
     * @param string $password The new password.
     * @param int $id The ID of the user.
     * @return int The number of affected rows.
     */
    public function updatePassword(string $password, int $id): int
    {
        try {
            // Update the password for the user in the 'user' table
            $update = $this->db->update('user', [
                'password' => $password,
            ], [
                'userid' => $id
            ]);

            // Return the number of affected rows
            return $update->rowCount();
        } catch (Exception $e) {
            // Handle exceptions, such as database errors
            error_log('Error in updatePassword(): ' . $e->getMessage());
            return 0; // Return 0 to indicate failure
        }
    }

    /**
     * Retrieves the details of a specific plan from the database.
     *
     * @param string $planId The ID of the plan to retrieve details for
     * @return array|null The details of the plan, or null if not found
     */
    public function planDetails(string $planId): ?array
    {
        try {
            // Retrieve plan details from the "plans" table based on the plan ID
            $query = $this->db->get("plans", "*", ["planId" => $planId]); 

            // If $query is null or empty, return an empty array
            if (!$query) {
                return [];
            }

            return $query;
        } catch (Exception $e) {
            // Handle exceptions, such as database errors
            error_log('Error in planDetails(): ' . $e->getMessage());
            return []; // Return null to indicate failure
        }
    }

    /**
     * Retrieves the details of a specific investment from the database.
     *
     * @param string $investId The ID of the investment to retrieve details for
     * @return array|null The details of the investment, or null if not found
     */
    public function investmentDetails(string $investId): ?array
    {
        try {
            // Retrieve investment details from the "plans" table based on the investment ID
            $query = $this->db->get("invests", "*", ["investId" => $investId]); 

            // If $query is null or empty, return an empty array
            if (!$query) {
                return [];
            }

            return $query;
        } catch (Exception $e) {
            // Handle exceptions, such as database errors
            error_log('Error in investmentDetails(): ' . $e->getMessage());
            return []; // Return null to indicate failure
        }
    }

    /**
     * Retrieves the referral settings from the database.
     *
     * @return array|null The referral settings, or null if not found
     */
    public function referralSettings(): ?array
    {
        try {
            // Retrieve referral settings from the "referrals" table
            $settings = $this->db->get('referrals', '*', ["id" => 1]);

            // If $query is null or empty, return an empty array
            if (!$settings) {
                return [];
            }

            return $settings;
        } catch (Exception $e) {
            // Handle exceptions, such as database errors
            error_log('Error in referralSettings(): ' . $e->getMessage());
            return []; // Return null to indicate failure
        }
    }

    /**
     * Adds an investment with referral commission.
     *
     * @param string $investId The ID of the investment
     * @param string $userid The ID of the user making the investment
     * @param string $planId The ID of the investment plan
     * @param float $amount_new The new balance of the user's interest wallet
     * @param float $interest_amount The total return of the investment
     * @param int $repeat_time The number of times the investment repeats
     * @param int $hours The duration of each investment cycle in hours
     * @param float $amount The investment amount
     * @param string $method The payment method used for the investment
     * @param string $details Additional details about the investment
     * @param string $from_id The ID of the user making the referral
     * @param string $to_id The ID of the user receiving the referral commission
     * @param float $referralAmount The amount of the referral commission
     * @param float $referralPercentage The percentage of the referral commission
     * @param float $new_balance The new balance of the referrer's interest wallet
     * @param string $title The title of the transaction
     * @param string $trx_type The type of transaction
     * @return int The number of rows affected by the insertion
     */
    public function planPurchase(string $investId, string $userid, string $planId, float $amount_new, float $interest_amount, int $repeat_time, int $hours, float $amount, string $method, string $details, string $from_id, string $to_id, float $referralAmount, float $referralPercentage, float $new_balance, string $title, string $trx_type, int $capital_back_status): int
    {
        try {
            // Get current timestamp
            $now = Carbon::now()->toDateTimeString();

            $nextTime = Carbon::parse($now)->addHours($hours)->toDateTimeString();

            // Generate a unique transaction ID
            $trx = $this->generateTransactionID();

            // Update the user's wallet
            $this->db->update('user', ['interest_wallet' => $amount_new], ['userid' => $userid]);

            // Insert transaction
            $this->db->insert('transactions', [
                'transactionId' => $investId,
                'userid' => $userid,
                'trx_type' => $trx_type,
                'trx_id' => $trx,
                'amount' => $amount,
                'post_balance' => $amount_new,
                'wallet_type' => $method,
                'details' => $details,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]);

            // Insert referral commissions
            $this->db->insert('commission_logs', [
                'investId' => $investId,
                'from_id' => $from_id,
                'to_id' => $to_id,
                'level' => 1,
                'commission_amount' => $referralAmount,
                'main_amount' => $amount,
                'percent' => $referralPercentage,
                'title' => $title,
                'type' => "invest",
                'trx' => $trx,
                'created_at' => date('Y-m-d H:i:s')
            ]);

            // Update the referrer's wallet
            $this->db->update('user', ['interest_wallet' => $new_balance], ['userid' => $to_id]);

            // Insert the investment details
            $Insert = $this->db->insert('invests', [
                'investId' => $investId,
                'userid' => $userid,
                'planId' => $planId,
                'amount' => $amount,
                'interest' => $interest_amount,
                'period' => $repeat_time,
                'hours' => $hours,
                'next_time' => $nextTime,
                'status' => 2,
                'capital_status' => $capital_back_status,
                'trx' => $trx,
                'created_at' => date('Y-m-d H:i:s')
            ]); 

            return $Insert->rowCount();  
        } catch (Exception $e) {
            // Handle exceptions, such as database errors
            error_log('Error in planPurchase(): ' . $e->getMessage());
            return 0; // Return 0 to indicate failure
        }
    }

    /**
     * Add Investment without Referral
     *
     * This method handles the process of purchasing an investment plan without a referral commission.
     *
     * @param string $investId The ID of the investment
     * @param string $userid The ID of the user making the purchase
     * @param int $planId The ID of the investment plan being purchased
     * @param float $amount_new The new balance of the user's interest wallet after the purchase
     * @param float $interest_amount The total return expected from the investment
     * @param int $repeat_time The number of times the investment will repeat
     * @param int $hours The duration of each investment cycle in hours
     * @param float $amount The amount of the investment
     * @param string $method The method used for the investment (e.g., payment gateway)
     * @param string $details Details of the investment transaction
     * @param string $trx_type The type of transaction (e.g., 'investment')
     * @return int The number of rows affected by the insertion operation
     */
    public function planPurchaseNoRef(string $investId, string $userid, int $planId, float $amount_new, float $interest_amount, int $repeat_time, int $hours, float $amount, string $method, string $details, string $trx_type, int $capital_back_status): int
    {
        try {
            // Get current timestamp
            $now = Carbon::now()->toDateTimeString();

            $nextTime = Carbon::parse($now)->addHours($hours)->toDateTimeString();

            // Generate a unique transaction ID
            $trx = $this->generateTransactionID();

            // Update the user's wallet with the new balance
            $this->db->update('user', ['interest_wallet' => $amount_new], ['userid' => $userid]);

            // Insert transaction record
            $this->db->insert('transactions', [
                'transactionId' => $investId,
                'userid' => $userid,
                'trx_type' => $trx_type,
                'trx_id' => $trx,
                'amount' => $amount,
                'post_balance' => $amount_new,
                'wallet_type' => $method,
                'details' => $details,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]);

            // Insert investment record
            $Insert = $this->db->insert('invests', [
                'investId' => $investId,
                'userid' => $userid,
                'planId' => $planId,
                'amount' => $amount,
                'interest' => $interest_amount,
                'period' => $repeat_time,
                'hours' => $hours,
                'next_time' => $nextTime,
                'status' => 2,
                'capital_status' => $capital_back_status,
                'trx' => $trx,
                'created_at' => date('Y-m-d H:i:s')
            ]); 

            return $Insert->rowCount();  
        } catch (Exception $e) {
            // Handle exceptions, such as database errors
            error_log('Error in planPurchaseNoRef(): ' . $e->getMessage());
            return 0; // Return 0 to indicate failure
        }
    }

    /**
     * Initiates a deposit.
     *
     * @param string $depositId The ID of the deposit
     * @param string $planId The ID of the investment plan
     * @param string $userid The ID of the user initiating the deposit
     * @param float $amount The deposit amount
     * @param string $method The payment method used for the deposit
     * @param string $trx_type The type of transaction
     * @return int The number of rows affected by the insertion
     */
    public function initiate(string $depositId, string $planId, string $userid, float $amount, string $method, string $trx_type): int
    {
        try {
            // Generate a unique transaction ID
            $trx = $this->generateTransactionID();

            // Insert transaction
            $this->db->insert('transactions', [
                'transactionId' => $depositId,
                'userid' => $userid,
                'trx_type' => $trx_type,
                'trx_id' => $trx,
                'amount' => $amount,
                'wallet_type' => $method,
                'details' => "Deposit initiated, proceed to make payment.",
                'created_at' => date('Y-m-d H:i:s')
            ]);

            $insert = $this->db->insert('deposits', [
                'depositId' => $depositId,
                'planId' => $planId,
                'userid' => $userid,
                'amount' => $amount,
                'trx' => $trx,
                'created_at' => date('Y-m-d H:i:s')
            ]); 

            return $insert->rowCount();  
        } catch (Exception $e) {
            // Handle exceptions, such as database errors
            error_log('Error in initiate(): ' . $e->getMessage());
            return 0; // Return 0 to indicate failure
        }
    }

    /**
     * Checks if a referral commission exists.
     *
     * @param string $userid The ID of the user
     * @param string $ref_by The ID of the referring user
     * @return bool Whether a referral commission exists or not
     */
    public function refExists(string $userid, string $ref_by): bool
    {
        return $this->db->has("commission_logs", ["from_id" => $userid, "to_id" => $ref_by]);
    }

    /**
     * transfers funds to a user.
     *
     * @param string $transferId The transfer ID associated with the transaction.
     * @param string $senderId The ID of the user sending the transfer.
     * @param string $receiverId The ID of the user receiving the transfer.
     * @param string $receiver_email The email of the receiver.
     * @param mixed $amount The amount of the transfer.
     * @param string $note Additional notes or description for the transfer.
     * @param mixed $trx The transaction ID associated with the transfer.
     * @return int The number of rows affected by the insert operation.
     */
    public function send(string $transferId, string $senderId, string $receiverId, string $receiver_email, mixed $amount, string $note, mixed $trx): int
    {
        try {
            // Insert transfer details
            $insert = $this->db->insert('send_money', [
                'transferId' => $transferId,
                'senderId' => $senderId,
                'receiverId' => $receiverId,
                'receiver_email' => $receiver_email,
                'amount' => $amount,
                'note' => $note,
                'trx' => $trx,
                'created_at' => date('Y-m-d H:i:s')
            ]);

            // Return the number of rows affected by the insert operation
            return $insert->rowCount(); 
        } catch (Exception $e) {
            // Handle exceptions, such as database errors
            error_log('Error in send(): ' . $e->getMessage());
            return 0; // Return 0 if an error occurs
        }
    }

    /**
     * Update the funds transferred to a user.
     *
     * @param string $transferId The transfer ID associated with the transaction.
     * @param string $senderId The ID of the user sending the transfer.
     * @param string $receiverId The ID of the user receiving the transfer.
     * @param mixed $amount The amount of the transfer.
     * @param string $receiverFirstName The first name of the receiver.
     * @param string $receiverLastName The last name of the receiver.
     * @param string $senderFirstName The first name of the sender.
     * @param string $senderLastName The last name of the sender.
     * @param float $senderWallet The current balance of the sender's wallet.
     * @param float $receiverWallet The current balance of the receiver's wallet.
     * @return int The number of rows affected by the update operation.
     */
    public function updateSend(string $transferId, string $senderId, string $receiverId, mixed $amount, string $receiverFirstName, string $receiverLastName, string $senderFirstName, string $senderLastName, float $senderWallet, float $receiverWallet): int
    {
        try {
            // Calculate new wallet balances
            $newSenderWallet = $senderWallet - $amount;
            $newReceiverWallet = $receiverWallet + $amount;

            // Generate a unique transaction ID
            $trx = $this->generateTransactionID();

            // Update send_money table to mark the transfer as completed
            $update = $this->db->update('send_money', ['status' => 1], ['transferId' => $transferId]);

            // Update sender's wallet balance
            $this->db->update('user', ['interest_wallet' => $newSenderWallet], ['userid' => $senderId]);

            // Update receiver's wallet balance
            $this->db->update('user', ['interest_wallet' => $newReceiverWallet], ['userid' => $receiverId]);

            // Insert transaction details for sender
            $this->db->insert('transactions', [
                'transactionId' => $transferId,
                'userid' => $senderId,
                'trx_type' => "-",
                'trx_id' => $trx,
                'amount' => $amount,
                'wallet_type' => "interest_wallet",
                'details' => $amount . " transferred to " . $receiverFirstName . " " . $receiverLastName,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]);

            // Insert transaction details for receiver
            $this->db->insert('transactions', [
                'transactionId' => $transferId,
                'userid' => $receiverId,
                'trx_type' => "+",
                'trx_id' => $trx,
                'amount' => $amount,
                'wallet_type' => "interest_wallet",
                'details' => $amount . " received from " . $senderFirstName . " " . $senderLastName,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]);

            // Return the number of rows affected by the update operation
            return $update->rowCount(); 
        } catch (Exception $e) {
            // Handle exceptions, such as database errors
            error_log('Error in updateSend(): ' . $e->getMessage());
            return 0; // Return false if an error occurs
        }
    }

    /**
     * Check if a transfer with the given transferId exists in the database.
     *
     * @param string $transferId The transferId to check for existence
     * @return bool True if the transferId exists, false otherwise
     */
    public function hasTransferId(string $transferId): bool
    {
        try {
            // Check if the specified transferId exists in the "send_money" table
            return $this->db->has("send_money", ["transferId" => $transferId]);
        } catch (Exception $e) {
            // Handle exceptions, such as database errors
            error_log('Error in hasTransferId(): ' . $e->getMessage());
            return false; // Return false if an error occurs
        }
    }

    /**
     * Get the transfer details for the specified transfer ID.
     *
     * @param int $transferId The ID of the transfer
     * @return array|null The details of the transfer, or null if not found
     */
    public function getTransfer(int $transferId): ?array
    {
        try {
            // Retrieve transfer details from the "send_money" table based on the transfer ID
            $row = $this->db->get("send_money", "*", ["transferId" => $transferId]);

            // Return the transfer details or null if not found
            return $row ?: null;
        } catch (Exception $e) {
            // Handle exceptions, such as database errors
            error_log('Error in getTransfer(): ' . $e->getMessage());
            return []; // Return null if an error occurs
        }
    }

    /**
     * Requests funds from a user.
     *
     * @param string $requestId The request ID associated with the transaction.
     * @param string $senderId The ID of the user initiating the transfer.
     * @param string $receiverId The ID of the user receiving the transfer.
     * @param string $sender_email The email of the user initiating the transfer.
     * @param mixed $amount The amount of the transfer.
     * @param string $note Additional notes or description for the transfer.
     * @param mixed $trx The transaction ID associated with the transfer.
     * @return int The number of rows affected by the insert operation.
     */
    public function request(string $requestId, string $receiverId, string $senderId, string $sender_email, mixed $amount, string $note, mixed $trx): int
    {
        try {
            // Insert transfer details
            $insert = $this->db->insert('request_money', [
                'requestId' => $requestId,
                'receiverId' => $receiverId,
                'senderId' => $senderId,
                'sender_email' => $sender_email,
                'amount' => $amount,
                'note' => $note,
                'trx' => $trx,
                'created_at' => date('Y-m-d H:i:s')
            ]);

            // Return the number of rows affected by the insert operation
            return $insert->rowCount(); 
        } catch (Exception $e) {
            // Handle exceptions, such as database errors
            error_log('Error in request(): ' . $e->getMessage());
            return 0; // Return 0 if an error occurs
        }
    }

    /**
     * Check if a request with the given requestId exists in the database.
     *
     * @param string $requestId The requestId to check for existence
     * @return bool True if the requestId exists, false otherwise
     */
    public function hasRequestId(string $requestId): bool
    {
        try {
            // Check if the specified requestId exists in the "request_money" table
            return $this->db->has("request_money", ["requestId" => $requestId]);
        } catch (Exception $e) {
            // Handle exceptions, such as database errors
            error_log('Error in hasRequestId(): ' . $e->getMessage());
            return false; // Return false if an error occurs
        }
    }

    /**
     * Get the request details for the specified request ID.
     *
     * @param int $requestId The ID of the request
     * @return array|null The details of the request, or null if not found
     */
    public function getRequest(int $requestId): ?array
    {
        try {
            // Retrieve request details from the "request_money" table based on the request ID
            $row = $this->db->get("request_money", "*", ["requestId" => $requestId]);

            // Return the request details or null if not found
            return $row ?: null;
        } catch (Exception $e) {
            // Handle exceptions, such as database errors
            error_log('Error in getRequest(): ' . $e->getMessage());
            return []; // Return null if an error occurs
        }
    }

    /**
     * Retrieve all pending requests from the database for a specific user
     *
     * @param string $userid The ID of the user whose pending requests are to be retrieved
     * @return array|null The details of the pending requests, or null if an error occurs
     */
    public function getAllPendingRequests(string $userid): ?array
    {
        try {
            // Retrieve all request details from the "request_money" table where the senderId matches the provided user ID and status is 2 (pending)
            return $this->db->select("request_money", "*", [
                "senderId" => $userid,
                "status" => 2
            ]);
        } catch (Exception $e) {
            // Handle exceptions, such as database errors
            error_log('Error in getAllCompletedRequests(): ' . $e->getMessage());
            return []; // Return null if an error occurs
        }
    }

    /**
     * Update the funds requested from a user.
     *
     * @param string $requestId The transfer ID associated with the transaction.
     * @param string $receiverId The ID of the user receiving the transfer.
     * @param string $senderId The ID of the user sending the transfer.
     * @param mixed $amount The amount of the transfer.
     * @param string $senderFirstName The first name of the sender.
     * @param string $senderLastName The last name of the sender.
     * @param string $receiverFirstName The first name of the receiver.
     * @param string $receiverLastName The last name of the receiver.
     * @return int The number of rows affected by the update operation.
     */
    public function updateRequest(string $requestId, string $receiverId, string $senderId, mixed $amount, string $senderFirstName, string $senderLastName, string $receiverFirstName, string $receiverLastName): int
    {
        try {
            // Generate a unique transaction ID
            $trx = $this->generateTransactionID();

            // Update request_money table to mark the payment as pending
            $update = $this->db->update('request_money', ['status' => 2], ['requestId' => $requestId]);

            // Insert transaction details for receiver
            $this->db->insert('transactions', [
                'transactionId' => $requestId,
                'userid' => $receiverId,
                'trx_type' => "+",
                'trx_id' => $trx,
                'amount' => $amount,
                'wallet_type' => "interest_wallet",
                'details' => "You have requested " . $amount . " from " . $senderFirstName . " " . $senderLastName,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]);

            // Insert transaction details for sender
            $this->db->insert('transactions', [
                'transactionId' => $requestId,
                'userid' => $senderId,
                'trx_type' => "-",
                'trx_id' => $trx,
                'amount' => $amount,
                'wallet_type' => "interest_wallet",
                'details' => $receiverFirstName . " " . $receiverLastName. " requested " . $amount,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]);

            // Return the number of rows affected by the update operation
            return $update->rowCount(); 
        } catch (Exception $e) {
            // Handle exceptions, such as database errors
            error_log('Error in updateSend(): ' . $e->getMessage());
            return 0; // Return false if an error occurs
        }
    }

    /**
     * Get User Details
     *
     * @param int $userid The ID of the user to retrieve details for.
     * @return array|null Returns an array containing the details of the specified user, or null if the user is not found.
     */
    public function getUser(int $userid): ?array
    {
        $query = $this->db->get("user", "*", ["userid" => $userid]);

        // If $query is null or empty, return an empty array
        if (!$query) {
            return [];
        }

        return $query;
    }

    /**
     * Get Receiver Details
     *
     * Retrieves details of the user with the specified ID, representing the receiver of a payment request.
     *
     * @param int $receiverId The ID of the user to retrieve details for.
     * @return array|null Returns an array containing the details of the specified user, or null if the user is not found.
     */
    public function getReceiver(int $receiverId): ?array
    {
        // Retrieve user details from the database
        $query = $this->db->get("user", "*", ["userid" => $receiverId]);

        // If $query is null or empty, return an empty array
        if (!$query) {
            return [];
        }

        return $query;
    }

    /**
     * Approve payment request
     *
     * @param int|string $requestId The ID of the payment request to approve
     * @param int $receiverId The ID of the receiver associated with the payment request
     * @param int $senderId The ID of the sender associated with the payment request
     * @param float $amount The amount of the payment request
     * @param string $receiverFirstName The first name of the receiver
     * @param string $receiverLastName The last name of the receiver
     * @param string $senderFirstName The first name of the sender
     * @param string $senderLastName The last name of the sender
     * @param float $senderWallet The current balance of the sender's wallet
     * @param float $receiverWallet The current balance of the receiver's wallet
     * @return int The number of rows affected by the update operation
     */
    public function approve(int|string $requestId, int $receiverId, int $senderId, float $amount, string $receiverFirstName, string $receiverLastName, string $senderFirstName, string $senderLastName, float $senderWallet, float $receiverWallet): int
    {
        try {
            
            // Calculate new wallet balances
            $newSenderWallet = $senderWallet - $amount;
            $newReceiverWallet = $receiverWallet + $amount;

            // Generate a unique transaction ID
            $trx = $this->generateTransactionID();

            // Update request_money table to mark the transfer as completed
            $update = $this->db->update('request_money', [
                'status' => 1,
                'updated_at' => date('Y-m-d H:i:s')
            ], [
                'requestId' => $requestId
            ]);

            // Update sender's wallet balance
            $this->db->update('user', ['interest_wallet' => $newSenderWallet], ['userid' => $senderId]);

            // Update receiver's wallet balance
            $this->db->update('user', ['interest_wallet' => $newReceiverWallet], ['userid' => $receiverId]);

            // Insert transaction details for sender
            $this->db->insert('transactions', [
                'transactionId' => $requestId,
                'userid' => $senderId,
                'trx_type' => "-",
                'trx_id' => $trx,
                'amount' => $amount,
                'wallet_type' => "interest_wallet",
                'details' => $amount . " transferred to " . $receiverFirstName . " " . $receiverLastName,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]);

            // Insert transaction details for receiver
            $this->db->insert('transactions', [
                'transactionId' => $requestId,
                'userid' => $receiverId,
                'trx_type' => "+",
                'trx_id' => $trx,
                'amount' => $amount,
                'wallet_type' => "interest_wallet",
                'details' => $amount . " received from " . $senderFirstName . " " . $senderLastName,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]);

            // Return the number of rows affected by the update operation
            return $update->rowCount(); 
        } catch (Exception $e) {
            // Handle exceptions, such as database errors
            error_log('Error in approve(): ' . $e->getMessage());
            return 0; // Return false if an error occurs
        }
    }

    /**
     * Reject payment request
     *
     * @param int|string $requestId The ID of the payment request to reject
     * @param int $receiverId The ID of the receiver associated with the payment request
     * @param int $senderId The ID of the sender associated with the payment request
     * @param float $amount The amount of the payment request
     * @param string $receiverFirstName The first name of the receiver
     * @param string $receiverLastName The last name of the receiver
     * @param string $senderFirstName The first name of the sender
     * @param string $senderLastName The last name of the sender
     * @return int The number of rows affected by the insert operation
     */
    public function reject(int|string $requestId, int $receiverId, int $senderId, float $amount, string $receiverFirstName, string $receiverLastName, string $senderFirstName, string $senderLastName): int
    {
        try {
            // Generate a unique transaction ID
            $trx = $this->generateTransactionID();

            // Update request_money table to mark the transfer as completed
            $update = $this->db->update('request_money', [
                'status' => 3,
                'updated_at' => date('Y-m-d H:i:s')
            ], [
                'requestId' => $requestId
            ]);

            // Insert transaction details for sender
            $this->db->insert('transactions', [
                'transactionId' => $requestId,
                'userid' => $senderId,
                'trx_type' => "-",
                'trx_id' => $trx,
                'amount' => $amount,
                'wallet_type' => "interest_wallet",
                'details' => $amount . " payment request from " . $receiverFirstName . " " . $receiverLastName. " rejected",
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]);

            // Insert transaction details for receiver
            $this->db->insert('transactions', [
                'transactionId' => $requestId,
                'userid' => $receiverId,
                'trx_type' => "+",
                'trx_id' => $trx,
                'amount' => $amount,
                'wallet_type' => "interest_wallet",
                'details' => $senderFirstName . " " . $senderLastName . " has rejected your payment request of " . $amount,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]);

            // Return the number of rows affected by the insert operation
            return $update->rowCount(); 
        } catch (Exception $e) {
            // Handle exceptions, such as database errors
            error_log('Error in reject(): ' . $e->getMessage());
            return 0; // Return false if an error occurs
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
     * Add Loan
     *
     * This method handles adding loans, including notifications and updating user balance.
     *
     * @param int $userid The ID of the user applying for the loan.
     * @param int $loan_reference_id The reference ID of the loan.
     * @param float $amount The loan amount.
     * @param string $loan_remarks Remarks for the loan.
     * @return int The number of affected rows.
     */
    public function loan(int $userid, int $loan_reference_id, float $amount, string $loan_remarks, string $loan_term, string $repayment_plan, string $collateral): int
    {
        try {
            // Insert data into the 'loan' table
            $insert = $this->db->insert('loan', [
                'userid' => $userid,
                'loan_reference_id' => $loan_reference_id,
                'amount' => $amount,
                'loan_remarks' => $loan_remarks,
                'loan_term' => $loan_term,
                'repayment_plan' => $repayment_plan,
                'collateral' => $collateral,
                'loan_status' => 2,
                'created_at' => date('Y-m-d H:i:s')
            ]);

            if (!$insert->rowCount()) {
                throw new Exception('Failed to insert loan data');
            }

            return $insert->rowCount();
        } catch (Exception $e) {
            // Log or rethrow the error as needed
            throw $e;
        }
    }

    /**
     * Retrieve loans from the database for a given user.
     *
     * @param string $userid The user ID for whom to retrieve deposits.
     * @return array The list of deposits retrieved from the 'deposits' table.
     */
    public function getLoans(string $userid): array
    {
        try {
            // Retrieve loans from the 'loan' table, filtered by user ID and ordered by creation date in descending order
            return $this->db->select("loan", "*", [
                "userid" => $userid,
                "ORDER" => ["created_at" => "DESC"]
            ]);
        } catch (Exception $e) {
            // Handle exceptions, such as database errors
            error_log('Error in getLoans(): ' . $e->getMessage());
            return []; // Return an empty array if an error occurs
        }
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

    /**
     * Generate a unique ID for deposit
     *
     * @return string
     */
    private function uniqueid(): string
    {
        return substr(number_format(time() * rand(), 0, '', ''), 0, 12);
    }

    /**
     * Check if a deposit method with the given method code exists in the .env file.
     *
     * @param string $payment_method The method code to check for existence.
     * @return bool True if the method code exists, false otherwise.
     */
    public function hasMethod(string $payment_method): bool
    {
        try {
            // Retrieve wallet addresses from .env
            $wallets = json_decode(getenv('WALLET_ADDRESSES'), true);

            // Ensure it's an array
            if (!is_array($wallets)) {
                return false;
            }

            // Check if method code exists
            foreach ($wallets as $wallet) {
                if (isset($wallet['method_code']) && $wallet['method_code'] === $payment_method) {
                    return true;
                }
            }

            return false;
        } catch (Exception $e) {
            // Log the error and return false
            error_log('Error in hasMethod(): ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Get the deposit details for the specified deposit method code from .env.
     *
     * @param string $payment_method The method code of the deposit method.
     * @return array|null The details of the deposit method, or null if not found.
     */
    public function getMethod(string $payment_method): ?array
    {
        try {
            // Retrieve wallet addresses from .env
            $wallets = json_decode(getenv('WALLET_ADDRESSES'), true);

            // Ensure it's an array
            if (!is_array($wallets)) {
                return null;
            }

            // Search for the deposit method
            foreach ($wallets as $wallet) {
                if (isset($wallet['method_code']) && $wallet['method_code'] === $payment_method) {
                    return $wallet; // Return the found deposit method
                }
            }

            return null; // Return null if not found
        } catch (Exception $e) {
            // Log the error and return null
            error_log('Error in getMethod(): ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Retrieves the details of a specific deposit method from the database.
     *
     * @param string $method_code The method code of the deposit method to retrieve details for
     * @return array|null The details of the deposit method, or null if not found
     */
    public function depositDetails(string $payment_method): ?array
    {
        try {
            // Retrieve wallet addresses from .env
            $wallets = json_decode(getenv('WALLET_ADDRESSES'), true);

            // Ensure it's an array
            if (!is_array($wallets)) {
                return null;
            }

            // Search for the deposit method
            foreach ($wallets as $wallet) {
                if (isset($wallet['method_code']) && $wallet['method_code'] === $payment_method) {
                    return $wallet; // Return the found deposit method
                }
            }

            return null; // Return null if not found
        } catch (Exception $e) {
            // Log the error and return null
            error_log('Error in getMethod(): ' . $e->getMessage());
            return null;
        }
    }
}