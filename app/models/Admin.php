<?php

namespace Fir\Models;

use Carbon\Carbon;
use Exception;

class Admin extends Model {

    /**
     * Gets the admin lockscreen mode
     *
     * @return array
     */
    public function lockscreen(): array
    {

        $lockscreen = $this->db->get('admin', '*', ["id" => 1]);

        // If $lockscreen is null or empty, return an empty array
        if (!$lockscreen) {
            return [];
        }

        return $lockscreen;
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
     * Adds an investment with referral commission.
     *
     * This method adds an investment to the database and logs referral commissions if applicable.
     *
     * @param string $investId The ID of the investment.
     * @param float $amount The investment amount.
     * @param string $from_id The ID of the user making the referral.
     * @param string $to_id The ID of the user receiving the referral commission.
     * @param float $referralAmount The amount of the referral commission.
     * @param float $referralPercentage The percentage of the referral commission.
     * @param string $title The title of the transaction.
     * @param int $hours The duration of each investment cycle in hours.
     * @param string $details Additional details about the investment.
     * @return int The number of rows affected by the insertion.
     */
    public function planPurchaseDeposit(string $investId, float $amount, string $from_id, string $to_id, float $referralAmount, float $referralPercentage, string $title, int $hours, string $details, string $userid, float $new_balance): int
    {
        try {
            // Get current timestamp
            $now = Carbon::now()->toDateTimeString();

            // Calculate next investment cycle time
            $nextTime = Carbon::parse($now)->addHours($hours)->toDateTimeString();

            // Generate a unique transaction ID
            $trx = $this->generateTransactionID();

            // Mark the deposit as completed by updating its status
            $this->db->update('deposits', ['status' => 1], ['depositId' => $investId]);

            // Insert transaction
            $this->db->insert('transactions', [
                'transactionId' => $investId,
                'userid' => $userid,
                'trx_type' => "-",
                'trx_id' => $trx,
                'amount' => $amount,
                'wallet_type' => "interest_wallet",
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

            // Update the investment status to running and set the next cycle time
            $update = $this->db->update('invests', ['status' => 2, 'next_time' => $nextTime, 'created_at' => date('Y-m-d H:i:s')], ['investId' => $investId]); 

            return $update->rowCount();  
        } catch (Exception $e) {
            // Handle exceptions, such as database errors
            error_log('Error in planPurchaseDeposit(): ' . $e->getMessage());
            return 0; // Return 0 to indicate failure
        }
    }

    /**
     * Adds an investment without referral commission.
     *
     * This method adds an investment to the database and logs referral commissions if applicable.
     *
     * @param string $investId The ID of the investment.
     * @param float $amount The investment amount.
     * @param int $hours The duration of each investment cycle in hours.
     * @param string $details Additional details about the investment.
     * @return int The number of rows affected by the insertion.
     */
    public function planPurchaseDepositNoRef(string $investId, float $amount, int $hours, string $details, string $userid): int
    {
        try {
            // Get current timestamp
            $now = Carbon::now()->toDateTimeString();

            // Calculate next investment cycle time
            $nextTime = Carbon::parse($now)->addHours($hours)->toDateTimeString();

            // Generate a unique transaction ID
            $trx = $this->generateTransactionID();

            // Mark the deposit as completed by updating its status
            $this->db->update('deposits', ['status' => 1], ['depositId' => $investId]);

            // Insert transaction
            $this->db->insert('transactions', [
                'transactionId' => $investId,
                'userid' => $userid,
                'trx_type' => "-",
                'trx_id' => $trx,
                'amount' => $amount,
                'wallet_type' => "interest_wallet",
                'details' => $details,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]);

            // Update the investment status to running and set the next cycle time
            $update = $this->db->update('invests', ['status' => 2, 'next_time' => $nextTime, 'created_at' => date('Y-m-d H:i:s')], ['investId' => $investId]); 

            return $update->rowCount();  
        } catch (Exception $e) {
            // Handle exceptions, such as database errors
            error_log('Error in planPurchaseDeposit(): ' . $e->getMessage());
            return 0; // Return 0 to indicate failure
        }
    }

    /**
     * Approve Deposit
     *
     * This method approves a Deposit in the database by updating the 'status' field to 1.
     *
     * @param int $depositId The ID of the Deposit whose status is being approved.
     * @param string $userid The ID of the user associated with the deposit.
     * @param float $amount The amount to be added to the user's interest wallet.
     * @return int The number of rows affected by the update operation (usually one if successful).
     */
    public function approveDeposit(int $depositId, string $userid, float $amount): int {
        try {
            // Begin transaction
            $this->db->pdo->beginTransaction();

            // Retrieve user details from the 'user' table
            $user = $this->db->get('user', '*', ["userid" => $userid]);
            if (!$user) {
                throw new Exception("User not found");
            }

            // Calculate new balance in the interest wallet
            $newBalance = $user['interest_wallet'] + $amount;

            // Update the 'interest_wallet' field of the user with the new balance
            $this->db->update('user', ['interest_wallet' => $newBalance], ['userid' => $userid]);

            // Mark the deposit as completed by updating its status
            $update = $this->db->update('deposits', ['status' => 1], ['depositId' => $depositId]);

            // Commit transaction
            $this->db->pdo->commit();

            // Return the number of rows affected by the update operation
            return $update->rowCount();
        } catch (Exception $e) {
            // Rollback transaction on error
            if ($this->db->pdo->inTransaction()) {
                $this->db->pdo->rollBack();
            }
            // Handle exceptions, such as database errors
            error_log('Error in approveDeposit(): ' . $e->getMessage());
            return 0; // Return 0 to indicate failure
        }
    }

    /**
     * Reject Deposit
     *
     * This method rejects a deposit in the database by updating the 'status' field to 3.
     * If the deposit is associated with an investment, it also updates the investment status to cancel.
     *
     * @param int $depositId The ID of the deposit being rejected.
     * @return int The number of rows affected by the update operation (usually one if successful).
     */
    public function rejectDeposit(int $depositId): int {
        try {
            // Retrieve investment details from the 'invests' table
            $investment = $this->db->get('invests', '*', ["investId" => $depositId]);

            // If an investment associated with the deposit exists, update its status to cancel
            if ($investment) {
                $this->db->update('invests', ['status' => 4], ['investId' => $depositId]);
            }

            // Update the 'status' field of the 'deposits' table to 3 for the specified deposit ID
            $update = $this->db->update('deposits', ['status' => 3], ['depositId' => $depositId]);

            // Return the number of rows affected by the update operation
            return $update->rowCount();
        } catch (Exception $e) {
            // Handle exceptions, such as database errors
            error_log('Error in rejectDeposit(): ' . $e->getMessage());
            return 0; // Return 0 to indicate failure
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
     * Process Withdrawal
     *
     * This method processes a withdrawal from the user's interest wallet and updates the database accordingly.
     *
     * @param int $withdrawId The ID of the withdrawal being processed.
     * @param string $userId The ID of the user initiating the withdrawal.
     * @param float $amount The amount to be withdrawn from the user's interest wallet.
     * @return int The number of rows affected by the update operation (usually one if successful).
     */
    public function approveWithdrawal(int $withdrawId, string $userId, float $amount): int {
        try {
            // Begin transaction
            $this->db->pdo->beginTransaction();

            // Retrieve user details from the 'user' table
            $user = $this->db->get('user', '*', ["userid" => $userId]);
            if (!$user) {
                throw new Exception("User not found");
            }

            // Calculate new balance in the interest wallet after withdrawal
            $newBalance = $user['interest_wallet'] - $amount;

            // Update the 'interest_wallet' field of the user with the new balance after withdrawal
            $this->db->update('user', ['interest_wallet' => $newBalance], ['userid' => $userId]);

            // Mark the withdrawal as completed by updating its status
            $update = $this->db->update('withdrawals', ['status' => 1], ['withdrawId' => $withdrawId]);

            // Generate a unique trx ID
            $trx_id = $this->generateTransactionID();

            // Insert transaction details into the 'transactions' table
            $this->db->insert('transactions', [
                'transactionId' => $withdrawId,
                'userid' => $userId,
                'trx_type' => "-",
                'trx_id' => $trx_id,
                'amount' => $amount,
                'post_balance' => $newBalance,
                'wallet_type' => "interest_wallet",
                'details' => "Withdrawal of " . $amount . " has been approved.",
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]);

            // Commit transaction
            $this->db->pdo->commit();

            // Return the number of rows affected by the update operation
            return $update->rowCount();
        } catch (Exception $e) {
            // Rollback transaction on error
            if ($this->db->pdo->inTransaction()) {
                $this->db->pdo->rollBack();
            }
            // Handle exceptions, such as insufficient balance or database errors
            error_log('Error in approveWithdrawal(): ' . $e->getMessage());
            return 0; // Return 0 to indicate failure
        }
    }

    /**
     * Reject Withdrawal
     *
     * This method rejects a withdrawal in the database by updating the 'status' field to 3.
     *
     * @param int $withdrawId The ID of the withdrawal whose status is being approved.
     * @return int The number of rows affected by the update operation (usually one if successful).
     */
    public function rejectWithdrawal(int $withdrawId): int {
        try {

            // Update the 'status' field of the 'withdrawals' table to 3 for the specified withdrawal ID
            $update = $this->db->update('withdrawals', ['status' => 3], ['withdrawId' => $withdrawId]);

            // Return the number of rows affected by the update operation
            return $update->rowCount();
        } catch (Exception $e) {
            // Handle exceptions, such as database errors
            error_log('Error in rejectWithdrawal(): ' . $e->getMessage());
            return 0; // Return 0 to indicate failure
        }
    }

    /**
     * Retrieve transactions from the database
     *
     * @return array The list of transactions retrieved from the 'transactions' table.
     */
    public function recentTransactions(): array
    {
        try {
            // Retrieve transactions from the 'transactions' table, ordered by creation date in descending order
            return $this->db->select("transactions", "*", [
                "ORDER" => ["created_at" => "DESC"],
                "LIMIT" => 6 // Limiting to 10 transactions
            ]);
        } catch (Exception $e) {
            // Handle exceptions, such as database errors
            error_log('Error in recentTransactions(): ' . $e->getMessage());
            return []; // Return an empty array if an error occurs
        }
    }
    
    /**
     * Retrieve transactions from the database
     *
     * @return array The list of transactions retrieved from the 'transactions' table.
     */
    public function getAllTransactions(): array
    {
        try {
            // Retrieve transactions from the 'transactions' table, ordered by creation date in descending order
            return $this->db->select("transactions", "*", [
                "ORDER" => ["created_at" => "DESC"],
                "LIMIT" => 10 // Limiting to 10 transactions
            ]);
        } catch (Exception $e) {
            // Handle exceptions, such as database errors
            error_log('Error in getAllTransactions(): ' . $e->getMessage());
            return []; // Return an empty array if an error occurs
        }
    }

    /**
     * Retrieve transactions from the database with pagination
     *
     * @param int $page The page number to retrieve transactions from
     * @return array The list of transactions retrieved from the 'transactions' table.
     */
    public function getAllTransactionsWithPagination(int $page): array
    {
        try {
            $limit = 10; // Number of transactions per page
            $offset = ($page - 1) * $limit; // Calculate the offset based on the page number

            // Retrieve transactions from the 'transactions' table, ordered by creation date in descending order
            return $this->db->select("transactions", "*", [
                "ORDER" => ["created_at" => "DESC"],
                "LIMIT" => [$offset, $limit] // Limiting to $limit transactions starting from $offset
            ]);
        } catch (Exception $e) {
            // Handle exceptions, such as database errors
            error_log('Error in getAllTransactionsWithPagination(): ' . $e->getMessage());
            return []; // Return an empty array if an error occurs
        }
    }

    /**
     * Retrieve commissions from the database
     *
     * @return array The list of commissions retrieved from the 'commission_logs' table.
     */
    public function getAllCommissions(): array
    {
        try {
            // Retrieve commissions from the 'commission_logs' table, ordered by creation date in descending order
            return $this->db->select("commission_logs", "*", [
                "ORDER" => ["created_at" => "DESC"],
                "LIMIT" => 5 // Limiting to 5 commissions
            ]);
        } catch (Exception $e) {
            // Handle exceptions, such as database errors
            error_log('Error in getAllCommissions(): ' . $e->getMessage());
            return []; // Return an empty array if an error occurs
        }
    }

    /**
     * Retrieve commissions from the database with pagination
     *
     * @param int $page The page number to retrieve commissions from
     * @return array The list of commissions retrieved from the 'commission_logs' table.
     */
    public function getAllCommissionsWithPagination(int $page): array
    {
        try {
            $limit = 5; // Number of commissions per page
            $offset = ($page - 1) * $limit; // Calculate the offset based on the page number

            // Retrieve commissions from the 'commission_logs' table, ordered by creation date in descending order
            return $this->db->select("commission_logs", "*", [
                "ORDER" => ["created_at" => "DESC"],
                "LIMIT" => [$offset, $limit] // Limiting to $limit commissions starting from $offset
            ]);
        } catch (Exception $e) {
            // Handle exceptions, such as database errors
            error_log('Error in getAllCommissionsWithPagination(): ' . $e->getMessage());
            return []; // Return an empty array if an error occurs
        }
    }
    
    /**
     * Count KYC address proof
     *
     * This method retrieves the count of KYC address proofs from the "address_proof" table in the database.
     *
     * @return int - Number of KYC address proofs in the "address_proof" table
     */
    public function getKYCAddressCount(int $userid): int
    {
        try {
            // Count the number of rows in the "address_proof" table where the account verification status is 2 (KYC pending)
            return $this->db->count("address_proof", "*", [
                "userid" => $userid
            ]);
        } catch (Exception $e) {
            // Handle exceptions, such as database errors
            error_log('Error in getKYCAddressCount(): ' . $e->getMessage());
            return 0; // Return 0 to indicate failure or absence of KYC pending users
        }
    }

    /**
     * Count KYC identity proof
     *
     * This method retrieves the count of KYC identity proofs from the "identity_proof" table in the database.
     *
     * @return int - Number of KYC identity proofs in the "identity_proof" table
     */
    public function getKYCIdentityCount(int $userid): int
    {
        try {
            // Count the number of rows in the "identity_proof" table where the account verification status is 2 (KYC pending)
            return $this->db->count("identity_proof", "*", [
                "userid" => $userid
            ]);
        } catch (Exception $e) {
            // Handle exceptions, such as database errors
            error_log('Error in getKYCAddressCount(): ' . $e->getMessage());
            return 0; // Return 0 to indicate failure or absence of KYC pending users
        }
    }

    /**
     * Retrieve KYC address proofs
     *
     * This method retrieves the KYC address proofs from the "address_proof" table in the database.
     *
     * @return array - Array of KYC address proof records
     */
    public function getKYCAddressProof(int $userid): array
    {
        try {
            // Select all rows from the "address_proof" table where the account verification status is 2 (KYC address proof)
            $query = $this->db->get("address_proof", "*", ["userid" => $userid]);
            // If $query is null or empty, return null
            if (!$query) {
                return [];
            }
            return $query;
        } catch (Exception $e) {
            // Handle exceptions, such as database errors
            error_log('Error in getKYCAddressProof(): ' . $e->getMessage());
            return []; // Return an empty array to indicate failure or absence of KYC address proofs
        }
    }

    /**
     * Retrieve KYC identity proofs
     *
     * This method retrieves the KYC identity proofs from the "identity_proof" table in the database.
     *
     * @return array - Array of KYC identity proof records
     */
    public function getKYCIdentityProof(int $userid): array
    {
        try {
            // Select all rows from the "identity_proof" table where the account verification status is 2 (KYC identity proof)
            $query = $this->db->get("identity_proof", "*", ["userid" => $userid]);
            // If $query is null or empty, return null
            if (!$query) {
                return [];
            }
            return $query;
        } catch (Exception $e) {
            // Handle exceptions, such as database errors
            error_log('Error in getKYCIdentityProof(): ' . $e->getMessage());
            return []; // Return an empty array to indicate failure or absence of KYC identity proofs
        }
    }

    /**
     * Get All New Registered Users
     *
     *
     * @return array An array containing the newly registered users from the "user" table.
     */
    public function newlyRegistered(): array {
        try {
            // Retrieve newly registered users from the 'user' table
            return $this->db->select('user', '*', [
                "ORDER" => ["registration_date" => "DESC"],
                "LIMIT" => 6
            ]);
        } catch (Exception $e) {
            // Handle exceptions, such as database errors
            error_log('Error in newlyRegistered(): ' . $e->getMessage());
            return []; // Return an empty array if an error occurs
        }
    }

    /**
     * Count all users
     *
     * This method retrieves the count of all users from the "user" table in the database.
     *
     * @return int - Number of all users in the "user" table
     */
    public function AllUsersCount(): int
    {
        try {
            // Count the number of rows in the "user" table
            return $this->db->count("user", "*", []);
        } catch (Exception $e) {
            // Handle exceptions, such as database errors
            error_log('Error in BannedUsersCount(): ' . $e->getMessage());
            return 0; // Return 0 to indicate failure or absence of all users
        }
    }

    /**
     * Count active users
     *
     * This method retrieves the count of active users from the "user" table in the database.
     *
     * @return int - Number of active users in the "user" table
     */
    public function ActiveUsersCount(): int
    {
        try {
            // Count the number of rows in the "user" table where the status is 2 (active)
            return $this->db->count("user", "*", ["AND" => ["status" => 1]]);
        } catch (Exception $e) {
            // Handle exceptions, such as database errors
            error_log('Error in BannedUsersCount(): ' . $e->getMessage());
            return 0; // Return 0 to indicate failure or absence of active users
        }
    }

    /**
     * Count banned users
     *
     * This method retrieves the count of banned users from the "user" table in the database.
     *
     * @return int - Number of banned users in the "user" table
     */
    public function BannedUsersCount(): int
    {
        try {
            // Count the number of rows in the "user" table where the status is 2 (banned)
            return $this->db->count("user", "*", ["AND" => ["status" => 2]]);
        } catch (Exception $e) {
            // Handle exceptions, such as database errors
            error_log('Error in BannedUsersCount(): ' . $e->getMessage());
            return 0; // Return 0 to indicate failure or absence of banned users
        }
    }

    /**
     * Count KYC unverified users
     *
     * This method retrieves the count of KYC unverified users from the "user" table in the database.
     *
     * @return int - Number of KYC unverified users in the "user" table
     */
    public function KYCUnverifiedCount(): int
    {
        try {
            $settings = $this->db->get('settings', '*', ["id" => 1]);

            // If kyc_status is active, return users with unverified account
            if ($settings['kyc_status'] == 1) {
                // Count the number of rows in the "user" table where the account verification status is 3 (KYC unverified)
                return $this->db->count("user", "*", ["account_verify" => 3]);
            }

            // If KYC is not active, return 0
            return 0;
        } catch (Exception $e) {
            // Handle exceptions, such as database errors
            error_log('Error in KYCUnverifiedCount(): ' . $e->getMessage());
            return 0; // Return 0 to indicate failure or absence of KYC unverified users
        }
    }

    /**
     * Count KYC pending users
     *
     * This method retrieves the count of KYC pending users from the "user" table in the database.
     *
     * @return int - Number of KYC pending users in the "user" table
     */
    public function KYCPendingCount(): int
    {
        try {
            $settings = $this->db->get('settings', '*', ["id" => 1]);

            // If kyc_status is active, return users with pending account
            if ($settings['kyc_status'] == 1) {
                // Count the number of rows in the "user" table where the account verification status is 2 (KYC pending)
                return $this->db->count("user", "*", ["account_verify" => 2]);
            }

            // If KYC is not active, return 0
            return 0;
        } catch (Exception $e) {
            // Handle exceptions, such as database errors
            error_log('Error in KYCPendingCount(): ' . $e->getMessage());
            return 0; // Return 0 to indicate failure or absence of KYC pending users
        }
    }

    /**
     * Search for users based on a search term or criteria
     *
     * @param string $searchTerm - Search term or criteria
     * @return array - Array of users matching the search term
     */
    public function findUsers(string $searchTerm): array
    {
        // No try-catch block needed here as the select method does not throw exceptions
        return $this->db->select("user", "*", [
            "OR" => [
                "userid[~]" => $searchTerm,
                "firstname[~]" => $searchTerm,
                "lastname[~]" => $searchTerm,
                "email[~]" => $searchTerm
            ],
            "ORDER" => [
                "registration_date" => "DESC"
            ]
        ]);
    }

    /**
     * Search for active users based on a search term or criteria
     *
     * This method searches for active users in the "user" table based on the provided search term or criteria.
     *
     * @param string $searchTerm - Search term or criteria
     * @return array - Array of active users matching the search term
     */
    public function findActiveUsers(string $searchTerm): array
    {
        try {
            // Retrieve active users from the "user" table based on the search term
            return $this->db->select("user", "*", [
                "status" => 1,
                "OR" => [
                    "userid[~]" => $searchTerm,
                    "firstname[~]" => $searchTerm,
                    "lastname[~]" => $searchTerm,
                    "email[~]" => $searchTerm
                ],
                "ORDER" => [
                    "registration_date" => "DESC"
                ]
            ]);
        } catch (Exception $e) {
            // Handle exceptions, such as database errors
            error_log('Error in findActiveUsers(): ' . $e->getMessage());
            return []; // Return an empty array to indicate failure
        }
    }

    /**
     * Search for banned users based on a search term or criteria
     *
     * This method searches for banned users in the "user" table based on the provided search term or criteria.
     *
     * @param string $searchTerm - Search term or criteria
     * @return array - Array of banned users matching the search term
     */
    public function findBannedUsers(string $searchTerm): array
    {
        try {
            // Retrieve banned users from the "user" table based on the search term
            return $this->db->select("user", "*", [
                "status" => 2,
                "OR" => [
                    "userid[~]" => $searchTerm,
                    "firstname[~]" => $searchTerm,
                    "lastname[~]" => $searchTerm,
                    "email[~]" => $searchTerm
                ],
                "ORDER" => [
                    "registration_date" => "DESC"
                ]
            ]);
        } catch (Exception $e) {
            // Handle exceptions, such as database errors
            error_log('Error in findBannedUsers(): ' . $e->getMessage());
            return []; // Return an empty array to indicate failure
        }
    }

    /**
     * Search for KYC unverified users based on a search term or criteria
     *
     * This method searches for KYC unverified users in the "user" table based on the provided search term or criteria.
     *
     * @param string $searchTerm - Search term or criteria
     * @return array - Array of KYC unverified users matching the search term
     */
    public function findKYCUnverifiedUsers(string $searchTerm): array
    {
        try {
            // Retrieve KYC unverified users from the "user" table based on the search term
            return $this->db->select("user", "*", [
                "account_verify" => 3,
                "OR" => [
                    "userid[~]" => $searchTerm,
                    "firstname[~]" => $searchTerm,
                    "lastname[~]" => $searchTerm,
                    "email[~]" => $searchTerm
                ],
                "ORDER" => [
                    "registration_date" => "DESC"
                ]
            ]);
        } catch (Exception $e) {
            // Handle exceptions, such as database errors
            error_log('Error in findKYCUnverifiedUsers(): ' . $e->getMessage());
            return []; // Return an empty array to indicate failure
        }
    }

    /**
     * Search for KYC pending users based on a search term or criteria
     *
     * This method searches for KYC pending users in the "user" table based on the provided search term or criteria.
     *
     * @param string $searchTerm - Search term or criteria
     * @return array - Array of KYC pending users matching the search term
     */
    public function findKYCPendingUsers(string $searchTerm): array
    {
        try {
            // Retrieve KYC pending users from the "user" table based on the search term
            return $this->db->select("user", "*", [
                "account_verify" => 2,
                "OR" => [
                    "userid[~]" => $searchTerm,
                    "firstname[~]" => $searchTerm,
                    "lastname[~]" => $searchTerm,
                    "email[~]" => $searchTerm
                ],
                "ORDER" => [
                    "registration_date" => "DESC"
                ]
            ]);
        } catch (Exception $e) {
            // Handle exceptions, such as database errors
            error_log('Error in findKYCPendingUsers(): ' . $e->getMessage());
            return []; // Return an empty array to indicate failure
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

            // If $settings is null or empty, return an empty array
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
     * Update Referral Settings
     *
     * This method updates the referral details in the database.
     *
     * @param string $percent The percentage of the referral.
     * @param int $status The status for referral.
     * @return int
     */
    public function updateReferralSettings(string $percent, int $status): int {
        try {
            // Update the 'referrals' table with the provided referral details where id matches
            $update = $this->db->update('referrals', [
                'percent' => $percent,
                'status' => $status
            ], [
                'id' => 1
            ]);

            // Return the number of rows affected by the update operation
            return $update->rowCount();
        } catch (Exception $e) {
            // Handle exceptions, such as database errors
            error_log('Error in updateReferralSettings(): ' . $e->getMessage());
            return 0; // Return 0 to indicate failure
        }
    }

    /**
     * Update Time Settings
     *
     * This method updates the time details in the database.
     *
     * @param string $timeId The ID of the time setting to be updated.
     * @param string $name The name of the time setting.
     * @param int $hours The hour value for the time setting.
     * @return int Returns the number of rows affected by the update operation.
     * @throws Exception If the update operation fails.
     */
    public function updateTimeSettings(string $timeId, string $name, int $hours): int {
        try {
            // Update the 'time_settings' table with the provided time details where 'id' matches
            $update = $this->db->update('time_settings', [
                'name' => $name,
                'time' => $hours
            ], [
                'id' => $timeId
            ]);

            // Return the number of rows affected by the update operation
            return $update->rowCount();
        } catch (Exception $e) {
            // Handle exceptions, such as database errors
            error_log('Error in updateTimeSettings(): ' . $e->getMessage());
            return 0; // Return 0 to indicate failure
        }
    }

    /**
     * Insert Time Settings
     *
     * This method inserts new time details into the database.
     *
     * @param string $name The name of the time setting.
     * @param int $hours The hour value for the time setting.
     * @return int Returns the number of rows affected by the update operation.
     * @throws Exception If the insertion operation fails.
     */
    public function insertTimeSettings(string $name, int $hours): int {
        try {
            // Insert new time details into the 'time_settings' table
            $insert = $this->db->insert('time_settings', [
                'name' => $name,
                'time' => $hours,
                'created_at' => date('Y-m-d H:i:s'),
            ]);

            // Return true if the insertion was successful
            return $insert->rowCount();
        } catch (Exception $e) {
            // Handle exceptions, such as database errors
            error_log('Error in insertTimeSettings(): ' . $e->getMessage());
            return 0; // Return false to indicate failure
        }
    }

    /**
     * Update SEO Details
     *
     * This method updates the SEO details in the database.
     *
     * @param string $filename The filename of the SEO image.
     * @param string $title The title for SEO.
     * @param string $keywords The keywords for SEO.
     * @param string $description The description for SEO.
     * @return int The number of rows affected by the update operation (usually one if successful).
     * @throws Exception If the update operation fails.
     */
    public function updateSeo(string $filename, string $title, string $keywords, string $description): int {
        try {
            // Update the 'settings' table with the provided SEO details where id matches
            $update = $this->db->update('settings', [
                'seo_image' => $filename,
                'title' => $title,
                'keywords' => $keywords,
                'description' => $description,
            ], [
                'id' => 1
            ]);

            // Return the number of rows affected by the update operation
            return $update->rowCount();
        } catch (Exception $e) {
            // Handle exceptions, such as database errors
            error_log('Error in updateSeo(): ' . $e->getMessage());
            return 0; // Return 0 to indicate failure
        }
    }

    /**
     * Update SEO Details No Image
     *
     * This method updates the SEO details in the database.
     *
     * @param string $title The title for SEO.
     * @param string $keywords The keywords for SEO.
     * @param string $description The description for SEO.
     * @return int The number of rows affected by the update operation (usually one if successful).
     * @throws Exception If the update operation fails.
     */
    public function UpdateSeoNoImage(string $title, string $keywords, string $description): int {
        try {
            // Update the 'settings' table with the provided SEO details where id matches
            $update = $this->db->update('settings', [
                'title' => $title,
                'keywords' => $keywords,
                'description' => $description,
            ], [
                'id' => 1
            ]);

            // Return the number of rows affected by the update operation
            return $update->rowCount();
        } catch (Exception $e) {
            // Handle exceptions, such as database errors
            error_log('Error in UpdateSeoNoImage(): ' . $e->getMessage());
            return 0; // Return 0 to indicate failure
        }
    }

    /**
     * Update Site Details
     *
     * This method updates the site details in the database.
     *
     * @param string $sitename The name of the site.
     * @param string $timezone The timezone of the site.
     * @param int $invest_commission The investment commission rate.
     * @param float $signup_bonus_amount The signup bonus amount.
     * @param int $signup_bonus_control The signup bonus control status (1 for enabled, 2 for disabled).
     * @param int $b_transfer The balance transfer status (1 for enabled, 2 for disabled).
     * @param int $b_request The balance request status (1 for enabled, 2 for disabled).
     * @param int $user_ranking The user ranking status (1 for enabled, 2 for disabled).
     * @param int $twofa_status The two-factor authentication status (1 for enabled, 2 for disabled).
     * @param int $register_status The Registration status (1 for enabled, 2 for disabled).
     * @param int $kyc_status The KYC status (1 for enabled, 2 for disabled).
     * @return int The number of rows affected by the update operation (usually one if successful).
     */
    public function updateSite(string $sitename, string $timezone, int $invest_commission, float $signup_bonus_amount, int $signup_bonus_control, int $b_transfer, int $b_request, int $user_ranking, int $twofa_status, int $register_status, int $kyc_status): int {
        try {
            // Update the 'settings' table with the provided parameters where id is 1
            $update = $this->db->update('settings', [
                'sitename' => $sitename,
                'timezone' => $timezone,
                'invest_commission' => $invest_commission,
                'signup_bonus_amount' => $signup_bonus_amount,
                'signup_bonus_control' => $signup_bonus_control,
                'b_transfer' => $b_transfer,
                'b_request' => $b_request,
                'user_ranking' => $user_ranking,
                'twofa_status' => $twofa_status,
                'register_status' => $register_status,
                'kyc_status' => $kyc_status
            ], [
                'id' => 1
            ]);

            // Return the number of rows affected by the update operation
            return $update->rowCount();
        } catch (Exception $e) {
            // Handle exceptions, such as database errors
            error_log('Error in updateSite(): ' . $e->getMessage());
            return 0; // Return 0 to indicate failure
        }
    }

    /**
     * Update Site SMTP Configuration
     *
     * This method updates the SMTP configuration settings of the site in the database.
     *
     * @param string $smtp_host The SMTP host address.
     * @param string $smtp_username The username for SMTP authentication.
     * @param string $smtp_password The password for SMTP authentication.
     * @param string $smtp_encryption The encryption method to be used for SMTP connection (e.g., 'ssl', 'tls', or null).
     * @param int $smtp_port The port number for SMTP connection.
     * @param int $email_notification Flag indicating whether email notifications are enabled (true) or disabled (false).
     * @param string $email_provider The email provider used by the site.
     * @return int The number of rows affected by the update operation (usually one if successful).
     */
    public function updateSmtp(string $smtp_host, string $smtp_username, string $smtp_password, string $smtp_encryption, int $smtp_port, int $email_notification, string $email_provider): int {
        try {
            // Update the 'settings' table with the provided SMTP parameters where id is 1
            $update = $this->db->update('settings', [
                'smtp_host' => $smtp_host,
                'smtp_username' => $smtp_username,
                'smtp_password' => $smtp_password,
                'smtp_encryption' => $smtp_encryption,
                'smtp_port' => $smtp_port,
                'email_notification' => $email_notification,
                'email_provider' => $email_provider
            ], [
                'id' => 1
            ]);

            // Return the number of rows affected by the update operation
            return $update->rowCount();
        } catch (Exception $e) {
            // Handle exceptions, such as database errors
            error_log('Error in updateSmtp(): ' . $e->getMessage());
            return 0; // Return 0 to indicate failure
        }
    }

    /**
     * Update Site Mailjet Configuration
     *
     * This method updates the Mailjet configuration settings of the site in the database.
     *
     * @param string $mailjet_api_key The Mailjet API key.
     * @param string $mailjet_api_secret The Mailjet API secret.
     * @param int $email_notification Flag indicating whether email notifications are enabled (1) or disabled (0).
     * @param string $email_provider The email provider used by the site.
     * @return int The number of rows affected by the update operation (usually one if successful).
     * @throws Exception If there is an error executing the database query.
     */
    public function updateMailjet(string $mailjet_api_key, string $mailjet_api_secret, int $email_notification, string $email_provider): int {
        try {
            // Update the 'settings' table with the provided Mailjet parameters where id is 1
            $update = $this->db->update('settings', [
                'mailjet_api_key' => $mailjet_api_key,
                'mailjet_api_secret' => $mailjet_api_secret,
                'email_notification' => $email_notification,
                'email_provider' => $email_provider
            ], [
                'id' => 1
            ]);

            // Return the number of rows affected by the update operation
            return $update->rowCount();
        } catch (Exception $e) {
            // Handle exceptions, such as database errors
            error_log('Error in updateMailjet(): ' . $e->getMessage());
            return 0; // Return 0 to indicate failure
        }
    }

    /**
     * Update Site Logo
     *
     * This method updates the logo of the site in the database.
     *
     * @param string $fileName The filename of the new logo.
     * @return int The number of rows affected by the update operation (usually one if successful).
     */
    public function updateLogo(string $fileName): int {
        try {
            // Update the 'settings' table with the provided logo filename where id is 1
            $update = $this->db->update('settings', [
                'logo' => $fileName
            ], [
                'id' => 1
            ]);

            // Return the number of rows affected by the update operation
            return $update->rowCount();
        } catch (Exception $e) {
            // Handle exceptions, such as database errors
            error_log('Error in updateLogo(): ' . $e->getMessage());
            return 0; // Return 0 to indicate failure
        }
    }

    /**
     * Update Site Favicon
     *
     * This method updates the favicon of the site in the database.
     *
     * @param string $fileName The filename of the new favicon.
     * @return int The number of rows affected by the update operation (usually one if successful).
     */
    public function updateFavicon(string $fileName): int {
        try {
            // Update the 'settings' table with the provided favicon filename where id is 1
            $update = $this->db->update('settings', [
                'favicon' => $fileName
            ], [
                'id' => 1
            ]);

            // Return the number of rows affected by the update operation
            return $update->rowCount();
        } catch (Exception $e) {
            // Handle exceptions, such as database errors
            error_log('Error in updateFavicon(): ' . $e->getMessage());
            return 0; // Return 0 to indicate failure
        }
    }

    /**
     * Update Admin Profile Details
     *
     * This method updates the profile details of the admin in the database.
     *
     * @param string $filename The filename of the admin's profile image.
     * @param string $fullname The full name of the admin.
     * @param string $email The email address of the admin.
     * @param int $adminid The ID of the admin.
     * @return int The number of rows affected by the update operation (usually one if successful).
     */
    public function updateAdminProfile(string $filename, string $fullname, string $email, int $adminid): int {
        try {
            // Update the 'admin' table with the provided profile details where adminid matches
            $update = $this->db->update('admin', [
                'imagelocation' => $filename,
                'name' => $fullname,
                'email' => $email,
            ], [
                'adminid' => $adminid
            ]);

            // Return the number of rows affected by the update operation
            return $update->rowCount();
        } catch (Exception $e) {
            // Handle exceptions, such as database errors
            error_log('Error in updateAdminProfile(): ' . $e->getMessage());
            return 0; // Return 0 to indicate failure
        }
    }

    /**
     * Update Admin Profile Details (No Image)
     *
     * This method updates the profile details of the admin without changing the profile image in the database.
     *
     * @param string $fullname The full name of the admin.
     * @param string $email The email address of the admin.
     * @param int $adminid The ID of the admin.
     * @return int The number of rows affected by the update operation (usually one if successful).
     */
    public function NoImage(string $fullname, string $email, int $adminid): int {
        try {
            // Update the 'admin' table with the provided details where adminid matches, without changing the image
            $update = $this->db->update('admin',[
               'name' => $fullname,
               'email' => $email,
            ],[
                'adminid' => $adminid
            ]);
              
            // Return the number of rows affected by the update operation
            return $update->rowCount();  
        } catch (Exception $e) {
            // Handle exceptions, such as database errors
            error_log('Error in NoImage(): ' . $e->getMessage());
            return 0; // Return 0 to indicate failure
        }
    }

    /**
     * Update Admin Panel Password
     *
     * This method updates the password of the admin in the database.
     *
     * @param string $password The new password for the admin.
     * @param int $adminid The ID of the admin.
     * @return int The number of rows affected by the update operation (usually one if successful).
     */
    public function password(string $password, int $adminid): int {
        try {
            // Update the 'admin' table with the provided password where adminid matches
            $update = $this->db->update('admin',[
               'password' => $password,
            ],[
                'adminid' => $adminid
            ]);
              
            // Return the number of rows affected by the update operation
            return $update->rowCount();  
        } catch (Exception $e) {
            // Handle exceptions, such as database errors
            error_log('Error in password(): ' . $e->getMessage());
            return 0; // Return 0 to indicate failure
        }
    }

    /**
     * Update User Password
     *
     * This method updates the password of a user in the database.
     *
     * @param string $password The new password for the user.
     * @param int $userid The ID of the user whose password is being updated.
     * @return int The number of rows affected by the update operation (usually one if successful).
     */
    public function ResetPassword(string $password, int $userid): int {
        try {
            // Update the 'user' table with the provided password for the specified user ID
            $update = $this->db->update('user',[
               'password' => $password,
            ],[
                'userid' => $userid
            ]);
              
            // Return the number of rows affected by the update operation
            return $update->rowCount();  
        } catch (Exception $e) {
            // Handle exceptions, such as database errors
            error_log('Error in ResetPassword(): ' . $e->getMessage());
            return 0; // Return 0 to indicate failure
        }
    }

    /**
     * Get All Users
     *
     * Retrieves the latest eight users from the "user" table.
     *
     * @return array An array containing the latest eight users from the "user" table.
     */
    public function getUsers(): array {
        try {
            // Retrieve the latest 8 users from the 'user' table
            return $this->db->select('user', '*', [
                "ORDER" => ["registration_date" => "DESC"],
                "LIMIT" => 8
            ]);
        } catch (Exception $e) {
            // Handle exceptions, such as database errors
            error_log('Error in getUsers(): ' . $e->getMessage());
            return []; // Return an empty array if an error occurs
        }
    }

    /**
     * Get All Users With Pagination
     *
     * Retrieves users with pagination from the "user" table.
     *
     * @param int $page The page number for pagination.
     * @return array An array containing users for the specified page.
     */
    public function getUsersWithPagination(int $page): array {
        try {
            $limit = 8; // Number of users per page
            $offset = ($page - 1) * $limit; // Calculate the offset based on the page number

            // Retrieve users with pagination from the 'user' table
            return $this->db->select('user', '*', [
                "ORDER" => ["registration_date" => "DESC"],
                "LIMIT" => [$offset, $limit]
            ]);
        } catch (Exception $e) {
            // Handle exceptions, such as database errors
            error_log('Error in getUsersWithPagination(): ' . $e->getMessage());
            return []; // Return an empty array if an error occurs
        }
    }

    /**
     * Get All Users
     *
     * Retrieves the latest eight users from the "user" table.
     *
     * @return array An array containing all users from the "user" table.
     */
    public function Users(): array {
        try {
            // Retrieve all users from the 'user' table
            return $this->db->select('user', '*', [
                "ORDER" => ["registration_date" => "DESC"]
            ]);
        } catch (Exception $e) {
            // Handle exceptions, such as database errors
            error_log('Error in getUsers(): ' . $e->getMessage());
            return []; // Return an empty array if an error occurs
        }
    }

    /**
     * Get All Users With Empty Balance
     *
     * Retrieves users with empty balance from the "user" table.
     *
     * @return array An array containing users with empty balance from the "user" table.
     */
    public function UsersWithEmptyBalance(): array {
        try {
            // Retrieve users with empty balance from the 'user' table
            return $this->db->select('user', '*', [
                "interest_wallet" => 0.00,
                "ORDER" => ["registration_date" => "DESC"]
            ]);
        } catch (Exception $e) {
            // Handle exceptions, such as database errors
            error_log('Error in UsersWithEmptyBalance(): ' . $e->getMessage());
            return []; // Return an empty array if an error occurs
        }
    }

    /**
     * Get All Active Users
     *
     * Retrieves the latest eight active users from the "user" table.
     *
     * @return array An array containing the latest eight active users from the "user" table.
     */
    public function getActiveUsers(): array {
        try {
            // Retrieve the latest 8 active users from the 'user' table
            return $this->db->select('user', '*', [
                "status" => 1,
                "ORDER" => ["registration_date" => "DESC"],
                "LIMIT" => 8
            ]);
        } catch (Exception $e) {
            // Handle exceptions, such as database errors
            error_log('Error in getActiveUsers(): ' . $e->getMessage());
            return []; // Return an empty array if an error occurs
        }
    }

    /**
     * Get All Active Users With Pagination
     *
     * Retrieves active users with pagination from the "user" table.
     *
     * @param int $page The page number for pagination.
     * @return array An array containing active users for the specified page.
     */
    public function getActiveUsersWithPagination(int $page): array {
        try {
            $limit = 8; // Number of active users per page
            $offset = ($page - 1) * $limit; // Calculate the offset based on the page number

            // Retrieve active users with pagination from the 'user' table
            return $this->db->select('user', '*', [
                "status" => 1,
                "ORDER" => ["registration_date" => "DESC"],
                "LIMIT" => [$offset, $limit]
            ]);
        } catch (Exception $e) {
            // Handle exceptions, such as database errors
            error_log('Error in getActiveUsersWithPagination(): ' . $e->getMessage());
            return []; // Return an empty array if an error occurs
        }
    }

    /**
     * Get All banned Users
     *
     * Retrieves the latest eight banned users from the "user" table.
     *
     * @return array An array containing the latest eight banned users from the "user" table.
     */
    public function getBannedUsers(): array {
        try {
            // Retrieve the latest 8 banned users from the 'user' table
            return $this->db->select('user', '*', [
                "status" => 2,
                "ORDER" => ["registration_date" => "DESC"],
                "LIMIT" => 8
            ]);
        } catch (Exception $e) {
            // Handle exceptions, such as database errors
            error_log('Error in getBannedUsers(): ' . $e->getMessage());
            return []; // Return an empty array if an error occurs
        }
    }

    /**
     * Get All Banned Users With Pagination
     *
     * Retrieves banned users with pagination from the "user" table.
     *
     * @param int $page The page number for pagination.
     * @return array An array containing banned users for the specified page.
     */
    public function getBannedUsersWithPagination(int $page): array {
        try {
            $limit = 8; // Number of banned users per page
            $offset = ($page - 1) * $limit; // Calculate the offset based on the page number

            // Retrieve banned users with pagination from the 'user' table
            return $this->db->select('user', '*', [
                "status" => 2,
                "ORDER" => ["registration_date" => "DESC"],
                "LIMIT" => [$offset, $limit]
            ]);
        } catch (Exception $e) {
            // Handle exceptions, such as database errors
            error_log('Error in getBannedUsersWithPagination(): ' . $e->getMessage());
            return []; // Return an empty array if an error occurs
        }
    }

    /**
     * Get All KYC Unverified Users
     *
     * Retrieves the latest eight kyc unverified users from the "user" table.
     *
     * @return array An array containing the latest eight kyc unverified users from the "user" table.
     */
    public function getKYCUnverifiedUsers(): array {
        try {
            // Retrieve the latest 8 kyc unverified users from the 'user' table
            return $this->db->select('user', '*', [
                "account_verify" => 3,
                "ORDER" => ["registration_date" => "DESC"],
                "LIMIT" => 8
            ]);
        } catch (Exception $e) {
            // Handle exceptions, such as database errors
            error_log('Error in getKYCUnverifiedUsers(): ' . $e->getMessage());
            return []; // Return an empty array if an error occurs
        }
    }

    /**
     * Get All KYC Unverified Users
     *
     * Retrieves kyc unverified users from the "user" table.
     *
     * @return array An array containing kyc unverified users from the "user" table.
     */
    public function KYCUnverifiedUsers(): array {
        try {
            // Retrieve kyc unverified users from the 'user' table
            return $this->db->select('user', '*', [
                "account_verify" => 3,
                "ORDER" => ["registration_date" => "DESC"]
            ]);
        } catch (Exception $e) {
            // Handle exceptions, such as database errors
            error_log('Error in getKYCUnverifiedUsers(): ' . $e->getMessage());
            return []; // Return an empty array if an error occurs
        }
    }

    /**
     * Get All KYC Unverified Users With Pagination
     *
     * Retrieves kyc unverified users with pagination from the "user" table.
     *
     * @param int $page The page number for pagination.
     * @return array An array containing kyc unverified users for the specified page.
     */
    public function getKYCUnverifiedUsersWithPagination(int $page): array {
        try {
            $limit = 8; // Number of banned users per page
            $offset = ($page - 1) * $limit; // Calculate the offset based on the page number

            // Retrieve kyc unverified users with pagination from the 'user' table
            return $this->db->select('user', '*', [
                "account_verify" => 3,
                "ORDER" => ["registration_date" => "DESC"],
                "LIMIT" => [$offset, $limit]
            ]);
        } catch (Exception $e) {
            // Handle exceptions, such as database errors
            error_log('Error in getKYCUnverifiedUsersWithPagination(): ' . $e->getMessage());
            return []; // Return an empty array if an error occurs
        }
    }

    /**
     * Get All KYC Pending Users
     *
     * Retrieves the latest eight kyc pending users from the "user" table.
     *
     * @return array An array containing the latest eight kyc pending users from the "user" table.
     */
    public function getKYCPendingUsers(): array {
        try {
            // Retrieve the latest 8 kyc pending users from the 'user' table
            return $this->db->select('user', '*', [
                "account_verify" => 2,
                "ORDER" => ["registration_date" => "DESC"],
                "LIMIT" => 8
            ]);
        } catch (Exception $e) {
            // Handle exceptions, such as database errors
            error_log('Error in getKYCPendingUsers(): ' . $e->getMessage());
            return []; // Return an empty array if an error occurs
        }
    }

    /**
     * Get All KYC Pending Users
     *
     * Retrieves kyc pending users from the "user" table.
     *
     * @return array An array containing kyc pending users from the "user" table.
     */
    public function KYCPendingUsers(): array {
        try {
            // Retrieve kyc pending users from the 'user' table
            return $this->db->select('user', '*', [
                "account_verify" => 2,
                "ORDER" => ["registration_date" => "DESC"]
            ]);
        } catch (Exception $e) {
            // Handle exceptions, such as database errors
            error_log('Error in getKYCPendingUsers(): ' . $e->getMessage());
            return []; // Return an empty array if an error occurs
        }
    }

    /**
     * Get All KYC Pending Users With Pagination
     *
     * Retrieves kyc pending users with pagination from the "user" table.
     *
     * @param int $page The page number for pagination.
     * @return array An array containing kyc pending users for the specified page.
     */
    public function getKYCPendingUsersWithPagination(int $page): array {
        try {
            $limit = 8; // Number of banned users per page
            $offset = ($page - 1) * $limit; // Calculate the offset based on the page number

            // Retrieve kyc pending users with pagination from the 'user' table
            return $this->db->select('user', '*', [
                "account_verify" => 2,
                "ORDER" => ["registration_date" => "DESC"],
                "LIMIT" => [$offset, $limit]
            ]);
        } catch (Exception $e) {
            // Handle exceptions, such as database errors
            error_log('Error in getKYCPendingUsersWithPagination(): ' . $e->getMessage());
            return []; // Return an empty array if an error occurs
        }
    }

    /**
     * Check if a User Exists
     *
     * @param int $userid The ID of the user to check for existence.
     * @return bool Returns true if the user exists in the "user" table, false otherwise.
     */
    public function hasUserId(int $userid): bool {
        try {
            return $this->db->has("user", ["AND" =>["userid" => $userid]]);
        } catch (Exception $e) {
            // Handle exceptions, such as database errors
            error_log('Error in hasUserId(): ' . $e->getMessage());
            return false; // Return false to indicate failure
        }
    }

    /**
     * Get User Details
     *
     * @param int $userid The ID of the user to retrieve details for.
     * @return array|null Returns an array containing the details of the specified user, or null if the user is not found.
     */
    public function getUserDetails(int $userid): ?array
    {
        try {
            $query = $this->db->get("user", "*", ["userid" => $userid]);
            // If $query is null or empty, return null
            if (!$query) {
                return [];
            }
            return $query;
        } catch (Exception $e) {
            // Handle exceptions, such as database errors
            error_log('Error in getUserDetails(): ' . $e->getMessage());
            return []; // Return null to indicate failure
        }
    }

    /**
     * Get User's Referrer
     *
     * @param mixed $userid The ID of the user to retrieve details for.
     * @return array|null Returns an array containing the details of the specified user, or null if the user is not found.
     */
    public function getUserReferrer(mixed $userid): ?array
    {
        try {
            $query = $this->db->get("user", "*", ["userid" => $userid]);
            // If $query is null or empty, return null
            if (!$query) {
                return [];
            }
            return $query;
        } catch (Exception $e) {
            // Handle exceptions, such as database errors
            error_log('Error in getUserReferrer(): ' . $e->getMessage());
            return []; // Return null to indicate failure
        }
    }

    /**
     * Reset User's Password
     *
     * This method resets the password of a user in the database.
     *
     * @param int $userid The ID of the user whose password is being reset.
     * @param string $password The new password for the user.
     * @return int The number of rows affected by the update operation (usually one if successful).
     */
    public function resetUserPassword(int $userid, string $password): int {
        try {
            $update = $this->db->update('user', [
                'password' => $password,
            ], [
                'userid' => $userid
            ]);

            return $update->rowCount();
        } catch (Exception $e) {
            // Handle exceptions, such as database errors
            error_log('Error in resetUserPassword(): ' . $e->getMessage());
            return 0; // Return 0 to indicate failure
        }
    }

    /**
     * Check if a user with the same Email exists in the database
     *
     * @param string $email The email address to check.
     * @return bool Returns true if a user with the specified email exists in the "user" table, false otherwise.
     */
    public function hasEmail(string $email): bool {
        try {
            return $this->db->has("user", ["email" => $email]);
        } catch (Exception $e) {
            // Handle exceptions, such as database errors
            error_log('Error in hasEmail(): ' . $e->getMessage());
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
     * Get email templates from the database.
     *
     * This method retrieves the latest eight email templates from the 'email_templates' table.
     *
     * @return array|null An array containing email templates, or null if no templates are found.
     */
    public function getEmailTemplates(): ?array {
        try {
            // Retrieve the latest 8 email templates from the 'email_templates' table
            return $this->db->select('email_templates', '*', [
                "ORDER" => ["id" => "ASC"],
                "LIMIT" => 8
            ]);
        } catch (Exception $e) {
            // Handle exceptions, such as database errors
            error_log('Error in getEmailTemplates(): ' . $e->getMessage());
            return []; // Return null if an error occurs
        }
    }

    /**
     * Get email templates from the database with pagination.
     *
     * This method retrieves email templates with pagination from the 'email_templates' table.
     *
     * @param int $page The page number for pagination.
     * @return array Returns an array of email templates.
     */
    public function getEmailTemplatesWithPagination(int $page): array
    {
        try {
            $limit = 8; // Number of templates per page
            $offset = ($page - 1) * $limit; // Calculate the offset based on the page number

            // Retrieve templates with pagination from the 'email_templates' table
            return $this->db->select('email_templates', '*', [
                "ORDER" => ["id" => "ASC"],
                "LIMIT" => [$offset, $limit]
            ]);
        } catch (Exception $e) {
            // Handle exceptions, such as database errors
            error_log('Error in getEmailTemplatesWithPagination(): ' . $e->getMessage());
            return []; // Return an empty array if an error occurs
        }
    }

    /**
     * Check if an extension exists in the database.
     *
     * @param int $id The ID of the extension to check.
     * @return bool True if the extension exists, false otherwise.
     */
    public function hasExtensions(int $id): bool {
        try {
            return $this->db->has("extensions", ["AND" => ["id" => $id]]);
        } catch (Exception $e) {
            // Handle exceptions, such as database errors
            error_log('Error in hasExtensions(): ' . $e->getMessage());
            return false; // Return false to indicate failure
        }
    }

    /**
     * Get extensions from the database.
     *
     * @return array|null An array containing extensions, or null if no extensions are found.
     */
    public function getExtensions(): ?array {
        try {
            return $this->db->select('extensions', '*', []);
        } catch (Exception $e) {
            // Handle exceptions, such as database errors
            error_log('Error in getExtensions(): ' . $e->getMessage());
            return []; // Return null to indicate failure
        }
    }

    /**
     * Get an extensions by its ID.
     *
     * @param int $id The ID of the extensions to retrieve.
     * @return array|null An array containing the extension details, or null if not found.
     */
    public function getExtensionsDetails(int $id): ?array {
        try {
            $query = $this->db->get("extensions", "*", ["id" => $id]);
            // If $query is null or empty, return null
            if (!$query) {
                return [];
            }
            return $query;
        } catch (Exception $e) {
            // Handle exceptions, such as database errors
            error_log('Error in getExtensionsDetails(): ' . $e->getMessage());
            return []; // Return null to indicate failure
        }
    }

    /**
     * Get maintenance details from the database.
     *
     * @return array|null An array containing the maintenance details, or null if not found.
     */
    public function getMaintenanceDetails(): ?array {
        try {
            $query = $this->db->get("maintenance", "*", ["id" => 1]);
            // If $query is null or empty, return null
            if (!$query) {
                return [];
            }
            return $query;
        } catch (Exception $e) {
            // Handle exceptions, such as database errors
            error_log('Error in getMaintenanceDetails(): ' . $e->getMessage());
            return []; // Return null to indicate failure
        }
    }

    /**
     * Set maintenance mode in the database.
     *
     * @param string $details Details of the maintenance.
     * @param int $maintenance_mode The maintenance mode (1 for active, 0 for inactive).
     * @return int The number of rows affected by the database update operation.
     */
    public function setMaintenanceMode(string $details, int $maintenance_mode): int {
        try {
            // Update the maintenance mode in the 'maintenance' table
            $update = $this->db->update('maintenance', [
                'details' => $details,
                'maintenance_mode' => $maintenance_mode,
            ], [
                'id' => 1
            ]);
            // Return the number of rows affected by the update operation
            return $update->rowCount();
        } catch (Exception $e) {
            // Handle exceptions, such as database errors
            error_log('Error in setMaintenanceMode(): ' . $e->getMessage());
            return 0; // Return 0 to indicate failure
        }
    }

    /**
     * Edit an extension.
     *
     * @param int $id The ID of the extension to edit.
     * @param string $name The name of the extension.
     * @param string $script The script of the extension.
     * @param int $status The status of the extension.
     * @return int The number of rows affected by the database update operation.
     */
    public function editExtensions(int $id, string $name, string $script, int $status): int {
        try {
            // Update the extension in the extension table
            $update = $this->db->update('extensions', [
                'name' => $name,
                'script' => $script,
                'status' => $status
            ], [
                'id' => $id
            ]);
            return $update->rowCount();
        } catch (Exception $e) {
            // Handle exceptions, such as database errors
            error_log('Error in editExtensions(): ' . $e->getMessage());
            return 0; // Return 0 to indicate failure
        }
    }

    /**
     * Get an email template by its ID.
     *
     * @param int $id The ID of the email template to retrieve.
     * @return array|null An array containing the email template details, or null if not found.
     */
    public function getTemplateDetails(int $id): ?array {
        try {
            $query = $this->db->get("email_templates", "*", ["id" => $id]);
            // If $query is null or empty, return null
            if (!$query) {
                return [];
            }
            return $query;
        } catch (Exception $e) {
            // Handle exceptions, such as database errors
            error_log('Error in getTemplateDetails(): ' . $e->getMessage());
            return []; // Return null to indicate failure
        }
    }

    /**
     * Edit an email template.
     *
     * @param int $id The ID of the email template to edit.
     * @param string $name The name of the email template.
     * @param string $subject The subject of the email template.
     * @param int $email_status The status of the email template.
     * @return int The number of rows affected by the database update operation.
     */
    public function editTemplate(int $id, string $name, string $subject, int $email_status): int {
        try {
            // Update the email template in the email_templates table
            $update = $this->db->update('email_templates', [
                'name' => $name,
                'subject' => $subject,
                'email_status' => $email_status
            ], [
                'id' => $id
            ]);
            return $update->rowCount();
        } catch (Exception $e) {
            // Handle exceptions, such as database errors
            error_log('Error in editTemplate(): ' . $e->getMessage());
            return 0; // Return 0 to indicate failure
        }
    }

    /**
     * Check if an email template exists in the database.
     *
     * @param int $id The ID of the email template to check.
     * @return bool True if the email template exists, false otherwise.
     */
    public function hasEmailTemplate(int $id): bool {
        try {
            return $this->db->has("email_templates", ["AND" => ["id" => $id]]);
        } catch (Exception $e) {
            // Handle exceptions, such as database errors
            error_log('Error in hasEmailTemplate(): ' . $e->getMessage());
            return false; // Return false to indicate failure
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
            $update = $this->db->update('user',[
                'qr_image' => $qr_image
            ],[
                'userid' => $userid
            ]);

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
            $update = $this->db->update('user', [
                'interest_wallet' => $bonus
            ],[
                'userid' => $userid
            ]);

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
     * Register User
     *
     * Adds a new user to the 'user' table.
     *
     * @param int $userid The ID of the user.
     * @param string $password The password of the user.
     * @param string $email The email address of the user.
     * @param string $firstname The first name of the user.
     * @param string $lastname The last name of the user.
     * @param string $formattedPhone The formatted phone number of the user.
     * @param string $country The country of the user.
     * @return int The number of rows affected by the insert operation.
     */
    public function registerUser(int $userid, string $password, string $email, string $firstname, string $lastname, string $formattedPhone, string $country): int
    {
        try {
            // Default profile image filename
            $filename = "default.png";

            // Insert the user details into the 'user' table
            $insert = $this->db->insert('user', array(
                'userid' => $userid,
                'password' => $password,
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
            error_log('Error in registerUser(): ' . $e->getMessage());
            return 0; // Return 0 to indicate failure
        }
    }

    /**
     * Update User
     *
     * Updates a user profile in the 'user' table.
     *
     * @param int $userid The ID of the user.
     * @param string $email The email address of the user.
     * @param string $firstname The first name of the user.
     * @param string $lastname The last name of the user.
     * @param string $formattedPhone The formatted phone number of the user.
     * @param string $country The country of the user.
     * @param string $city The city of the user.
     * @param string $state The state of the user.
     * @param string $address_1 Address line 1 of the user.
     * @param string $currency The currency of the user.
     * @param int $account_verify The account verification status of the user.
     * @param int $twofactor_status The two-factor authentication status of the user.
     * @return int The number of rows affected by the update operation.
     */
    public function updateUser(int $userid, string $email, string $firstname, string $lastname, string $formattedPhone, string $address_1, string $country, string $city, string $state, string $currency, int $account_verify, int $twofactor_status): int {
        try {
            // Update the user details in the 'user' table
            $update = $this->db->update('user',[
                'email' => $email,
                'firstname' => $firstname,
                'lastname' => $lastname,
                'phone' => $formattedPhone,
                'address_1' => $address_1,
                'country' => $country,
                'city' => $city,
                'state' => $state,
                'currency' => $currency,
                'account_verify' => $account_verify,
                'twofactor_status' => $twofactor_status
            ],[
                'userid' => $userid
            ]);

            return $update->rowCount(); // Return the number of rows affected by the update operation
        } catch (Exception $e) {
            // Handle exceptions, such as database errors
            error_log('Error in updateUser(): ' . $e->getMessage());
            return 0; // Return 0 to indicate failure
        }
    }


    /**
     * Add Funds To User Account
     *
     * Adds funds to a user's account and records the transaction in the database.
     *
     * @param int $userid The ID of the user.
     * @param float $amount The amount to add to the user's account.
     * @return int The number of rows affected by the update operation.
     */
    public function addFunds(int $userid, float $amount): int
    {
        try {
            // Get user details
            $user = $this->getUserDetails($userid);

            // Check if user exists
            if ($user) {
                // Calculate new balance after adding funds
                $newBalance = $amount + $user['interest_wallet'];

                // Field to update
                $updateField = 'interest_wallet';

                // Generate a unique transaction ID and trx ID
                $trx_id = $this->generateTransactionID();
                $transactionId = $this->uniqueid();

                // Provide the appropriate details for the transaction
                $details = $amount . ' has been added to your balance';

                // Insert transaction
                $insert = $this->db->insert('transactions', array(
                    'transactionId' => $transactionId,
                    'userid' => $user['userid'],
                    'trx_type' => "+",
                    'trx_id' => $trx_id,
                    'amount' => $amount,
                    'post_balance' => $newBalance,
                    'wallet_type' => $updateField,
                    'details' => $details,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s')
                ));

                // Update the selected wallet
                $this->db->update('user', array(
                    $updateField => $newBalance
                ), array(
                    'userid' => $user['userid']
                ));

                // Return the number of rows affected by the insert operation
                return $insert->rowCount();
            } else {
                // User not found
                return 0;
            }
        } catch (Exception $e) {
            // Handle exceptions, such as database errors
            error_log('Error in addFunds(): ' . $e->getMessage());
            return 0; // Return 0 to indicate failure
        }
    }

    /**
     * Remove Funds From User Account
     *
     * Removes funds from a user's account and records the transaction in the database.
     *
     * @param int $userid The ID of the user.
     * @param float $amount The amount to remove from the user's account.
     * @param float $newBalance The new balance after removing funds.
     * @return int The number of rows affected by the update operation.
     */
    public function removeFunds(int $userid, float $amount, float $newBalance): int
    {
        try {
            // Field to update
            $updateField = 'interest_wallet';

            // Generate a unique transaction ID and trx ID
            $trx_id = $this->generateTransactionID();
            $transactionId = $this->uniqueid();

            // Provide the appropriate details for the transaction
            $details = $amount . ' has been removed from your balance';

            // Insert transaction
            $insert = $this->db->insert('transactions', array(
                'transactionId' => $transactionId,
                'userid' => $userid,
                'trx_type' => "-",
                'trx_id' => $trx_id,
                'amount' => $amount,
                'post_balance' => $newBalance,
                'wallet_type' => $updateField,
                'details' => $details,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ));

            // Update the selected wallet
            $this->db->update('user', array(
                $updateField => $newBalance
            ), array(
                'userid' => $userid
            ));

            // Return the number of rows affected by the insert operation
            return $insert->rowCount();
        } catch (Exception $e) {
            // Handle exceptions, such as database errors
            error_log('Error in removeFunds(): ' . $e->getMessage());
            return 0; // Return 0 to indicate failure
        }
    }

    /**
     * Reset User's Password
     *
     * This method resets the password of a user in the database.
     *
     * @param int $userid The ID of the user whose password is being reset.
     * @param string $password The new password for the user.
     * @return int The number of rows affected by the update operation (usually one if successful).
     */
    public function resetUser(int $userid, string $password): int {

        try {
            $update = $this->db->update('user',[
                'password' => $password,
            ],[
                'userid' => $userid
            ]);

            // Return the number of rows affected by the update operation
            return $update->rowCount();
        } catch (Exception $e) {
            // Handle exceptions, such as database errors
            error_log('Error in resetUser(): ' . $e->getMessage());
            return 0; // Return 0 to indicate failure
        }
    }

    /**
     * Block User's Account
     *
     * This method blocks a user's account in the database by updating the 'status' field to 2.
     *
     * @param int $userid The ID of the user whose account is being blocked.
     * @return int The number of rows affected by the update operation (usually one if successful).
     */
    public function blockUser(int $userid): int {

        try {
            // Update the 'status' field of the 'user' table to 2 for the specified user ID
            $update = $this->db->update('user',[
                'status' => 2,
            ],[
                'userid' => $userid
            ]);

            // Return the number of rows affected by the update operation
            return $update->rowCount();
        } catch (Exception $e) {
            // Handle exceptions, such as database errors
            error_log('Error in blockUser(): ' . $e->getMessage());
            return 0; // Return 0 to indicate failure
        }
    }

    /**
     * Activate User's Account
     *
     * This method activates a user's account in the database by updating the 'status' field to 1.
     *
     * @param int $userid The ID of the user whose account is being activated.
     * @return int The number of rows affected by the update operation (usually one if successful).
     */
    public function activateUser(int $userid): int {

        try {
            // Update the 'status' field of the 'user' table to 1 for the specified user ID
            $update = $this->db->update('user',[
                'status' => 1,
            ],[
                'userid' => $userid
            ]);

            // Return the number of rows affected by the update operation
            return $update->rowCount();
        } catch (Exception $e) {
            // Handle exceptions, such as database errors
            error_log('Error in activateUser(): ' . $e->getMessage());
            return 0; // Return 0 to indicate failure
        }
    }

    /**
     * Calculate Total Deposits
     *
     * @param string $userid The ID of the user.
     * @return float The total sum of the deposited amount in the "deposits" table.
     */
    public function depositSum(string $userid): float {
        try {
            // Calculate the sum of the deposited amount for the specified user
            $sum = $this->db->sum("deposits", "amount", [
                "method_code[!]" => "", 
                "userid" => $userid, 
                "status" => [1, 2, 3, 0]
            ]);
            // Convert the sum to float and return
            return (float) $sum;
        } catch (Exception $e) {
            // Handle any exceptions
            error_log("Error in deposits calculation: " . $e->getMessage());
            return 0.0; // Return 0 in case of error
        }
    }

    /**
     * Calculate Pending Deposits
     *
     * @param string $userid The ID of the user.
     * @return float The total sum of the deposited amount in the "deposits" table.
     */
    public function PendingDepositSum(string $userid): float {
        try {
            // Calculate the sum of the deposited amount with pending status for the specified user
            $sum = $this->db->sum("deposits", "amount", [
                "method_code[!]" => "", 
                "userid" => $userid, 
                "status" => 2
            ]);
            // Convert the sum to float and return
            return (float) $sum;
        } catch (Exception $e) {
            // Handle any exceptions
            error_log("Error in pending deposits calculation: " . $e->getMessage());
            return 0.0; // Return 0 in case of error
        }
    }

    /**
     * Calculate Completed Deposits
     *
     * @param string $userid The ID of the user.
     * @return float The total sum of the deposited amount in the "deposits" table.
     */
    public function CompletedDepositSum(string $userid): float {
        try {
            // Calculate the sum of the deposited amount with completed status for the specified user
            $sum = $this->db->sum("deposits", "amount", [
                "method_code[!]" => "", 
                "userid" => $userid, 
                "status" => 1
            ]);
            // Convert the sum to float and return
            return (float) $sum;
        } catch (Exception $e) {
            // Handle any exceptions
            error_log("Error in completed deposits calculation: " . $e->getMessage());
            return 0.0; // Return 0 in case of error
        }
    }

    /**
     * Calculate Rejected Deposits
     *
     * @param string $userid The ID of the user.
     * @return float The total sum of the deposited amount in the "deposits" table.
     */
    public function RejectedDepositSum(string $userid): float {
        try {
            // Calculate the sum of the deposited amount with rejected status for the specified user
            $sum = $this->db->sum("deposits", "amount", [
                "method_code[!]" => "", 
                "userid" => $userid, 
                "status" => 3
            ]);
            // Convert the sum to float and return
            return (float) $sum;
        } catch (Exception $e) {
            // Handle any exceptions
            error_log("Error in rejected deposits calculation: " . $e->getMessage());
            return 0.0; // Return 0 in case of error
        }
    }

    /**
     * Calculate Initiated Deposits
     *
     * @param string $userid The ID of the user.
     * @return float The total sum of the deposited amount in the "deposits" table.
     */
    public function InitiatedDepositSum(string $userid): float {
        try {
            // Calculate the sum of the deposited amount with initiated status for the specified user
            $sum = $this->db->sum("deposits", "amount", [
                "method_code[!]" => "", 
                "userid" => $userid, 
                "status" => 0
            ]);
            // Convert the sum to float and return
            return (float) $sum;
        } catch (Exception $e) {
            // Handle any exceptions
            error_log("Error in initiated deposits calculation: " . $e->getMessage());
            return 0.0; // Return 0 in case of error
        }
    }

    /**
     * Calculate Total Withdrawals
     *
     * @param string $userid The ID of the user.
     * @return float The total sum of withdrawn amount in the "withdrawals" table.
     */
    public function withdrawalSum(string $userid): float {
        try {
            // Calculate the sum of withdrawn amount for the specified user
            $sum = $this->db->sum("withdrawals", "amount", ["userid" => $userid, "status" => [1, 2, 3, 0]]);
            // Convert the sum to float and return
            return (float) $sum;
        } catch (Exception $e) {
            // Handle any exceptions
            error_log("Error in withdrawals calculation: " . $e->getMessage());
            return 0.0; // Return 0 in case of error
        }
    }

    /**
     * Calculate Pending Withdrawals
     *
     * @param string $userid The ID of the user.
     * @return float The total sum of withdrawn amount in the "withdrawals" table.
     */
    public function PendingWithdrawalSum(string $userid): float {
        try {
            // Calculate the sum of withdrawn amount with pending status for the specified user
            $sum = $this->db->sum("withdrawals", "amount", ["userid" => $userid, "status" => 2]);
            // Convert the sum to float and return
            return (float) $sum;
        } catch (Exception $e) {
            // Handle any exceptions
            error_log("Error in pending withdrawals calculation: " . $e->getMessage());
            return 0.0; // Return 0 in case of error
        }
    }

    /**
     * Calculate Completed Withdrawals
     *
     * @param string $userid The ID of the user.
     * @return float The total sum of withdrawn amount in the "withdrawals" table.
     */
    public function CompletedWithdrawalSum(string $userid): float {
        try {
            // Calculate the sum of withdrawn amount with completed status for the specified user
            $sum = $this->db->sum("withdrawals", "amount", ["userid" => $userid, "status" => 1]);
            // Convert the sum to float and return
            return (float) $sum;
        } catch (Exception $e) {
            // Handle any exceptions
            error_log("Error in completed withdrawals calculation: " . $e->getMessage());
            return 0.0; // Return 0 in case of error
        }
    }

    /**
     * Calculate Rejected Withdrawals
     *
     * @param string $userid The ID of the user.
     * @return float The total sum of withdrawn amount in the "withdrawals" table.
     */
    public function RejectedWithdrawalSum(string $userid): float {
        try {
            // Calculate the sum of withdrawn amount with rejected status for the specified user
            $sum = $this->db->sum("withdrawals", "amount", ["userid" => $userid, "status" => 3]);
            // Convert the sum to float and return
            return (float) $sum;
        } catch (Exception $e) {
            // Handle any exceptions
            error_log("Error in rejected withdrawals calculation: " . $e->getMessage());
            return 0.0; // Return 0 in case of error
        }
    }

    /**
     * Calculate Initiated Withdrawals
     *
     * @param string $userid The ID of the user.
     * @return float The total sum of withdrawn amount in the "withdrawals" table.
     */
    public function InitiatedWithdrawalSum(string $userid): float {
        try {
            // Calculate the sum of withdrawn amount with initiated status for the specified user
            $sum = $this->db->sum("withdrawals", "amount", ["userid" => $userid, "status" => 0]);
            // Convert the sum to float and return
            return (float) $sum;
        } catch (Exception $e) {
            // Handle any exceptions
            error_log("Error in initiated withdrawals calculation: " . $e->getMessage());
            return 0.0; // Return 0 in case of error
        }
    }


    /**
     * Calculate Total Investments
     *
     * @param string $userid The ID of the user.
     * @return float The total sum of invested amount in the "investments" table.
     */
    public function investmentSum(string $userid): float {
        try {
            // Calculate the sum of invested amount for the specified user
            $sum = $this->db->sum("invests", "amount", ["userid" => $userid, "status" => [1, 2, 3, 4]]);
            // Convert the sum to float and return
            return (float) $sum;
        } catch (Exception $e) {
            // Handle any exceptions
            error_log("Error in investments calculation: " . $e->getMessage());
            return 0.0; // Return 0 in case of error
        }
    }

    /**
     * Calculate Pending Investments
     *
     * @param string $userid The ID of the user.
     * @return float The total sum of invested amount in the "investments" table.
     */
    public function PendingInvestmentSum(string $userid): float {
        try {
            // Calculate the sum of invested amount with pending status for the specified user
            $sum = $this->db->sum("invests", "amount", ["userid" => $userid, "status" => 2]);
            // Convert the sum to float and return
            return (float) $sum;
        } catch (Exception $e) {
            // Handle any exceptions
            error_log("Error in pending investments calculation: " . $e->getMessage());
            return 0.0; // Return 0 in case of error
        }
    }

    /**
     * Calculate Completed Investments
     *
     * @param string $userid The ID of the user.
     * @return float The total sum of invested amount in the "investments" table.
     */
    public function CompletedInvestmentSum(string $userid): float {
        try {
            // Calculate the sum of invested amount with completed status for the specified user
            $sum = $this->db->sum("invests", "amount", ["userid" => $userid, "status" => 1]);
            // Convert the sum to float and return
            return (float) $sum;
        } catch (Exception $e) {
            // Handle any exceptions
            error_log("Error in completed investments calculation: " . $e->getMessage());
            return 0.0; // Return 0 in case of error
        }
    }

    /**
     * Calculate Cancelled Investments
     *
     * @param string $userid The ID of the user.
     * @return float The total sum of invested amount in the "investments" table.
     */
    public function CancelledInvestmentSum(string $userid): float {
        try {
            // Calculate the sum of invested amount with canceled status for the specified user
            $sum = $this->db->sum("invests", "amount", ["userid" => $userid, "status" => 4]);
            // Convert the sum to float and return
            return (float) $sum;
        } catch (Exception $e) {
            // Handle any exceptions
            error_log("Error in cancelled investments calculation: " . $e->getMessage());
            return 0.0; // Return 0 in case of error
        }
    }

    /**
     * Calculate Initiated Investments
     *
     * @param string $userid The ID of the user.
     * @return float The total sum of invested amount in the "investments" table.
     */
    public function InitiatedInvestmentSum(string $userid): float {
        try {
            // Calculate the sum of invested amount with initiated status for the specified user
            $sum = $this->db->sum("invests", "amount", ["userid" => $userid, "status" => 3]);
            // Convert the sum to float and return
            return (float) $sum;
        } catch (Exception $e) {
            // Handle any exceptions
            error_log("Error in initiated investments calculation: " . $e->getMessage());
            return 0.0; // Return 0 in case of error
        }
    }

    /**
     * Retrieve deposits from the database for a given user.
     *
     * @param string $userid The user ID for whom to retrieve deposits.
     * @return array The list of deposits retrieved from the 'deposits' table.
     */
    public function getUserDeposits(string $userid): array
    {
        try {
            // Retrieve deposits from the 'deposits' table, filtered by user ID and ordered by creation date in descending order
            return $this->db->select("deposits", "*", [
                "userid" => $userid, 
                "method_code[!]" => "",
                "ORDER" => ["created_at" => "DESC"], 
                "LIMIT" => 9 
            ]);
        } catch (Exception $e) {
            // Handle exceptions, such as database errors
            error_log('Error in getUserDeposits(): ' . $e->getMessage());
            return []; // Return an empty array if an error occurs
        }
    }

    /**
     * Gets deposits with pagination
     *
     * This method retrieves deposits for a specified user with pagination from the 'deposits' table.
     *
     * @param int $userid The ID of the user.
     * @param int $page The page number for pagination.
     * @return array Returns an array of deposits records.
     */
    public function getUserDepositsWithPagination(int $userid, int $page): array
    {
        try {
            $limit = 9; // Number of deposits per page
            $offset = ($page - 1) * $limit; // Calculate the offset based on the page number

            // Retrieve deposits for the specified user with pagination from the 'deposits' table
            return $this->db->select('deposits', '*', [
                "userid" => $userid,
                "method_code[!]" => "", 
                "ORDER" => ["created_at" => "DESC"],
                "LIMIT" => [$offset, $limit]
            ]);
        } catch (Exception $e) {
            // Handle exceptions, such as database errors
            error_log('Error in getUserDepositsWithPagination(): ' . $e->getMessage());
            return []; // Return an empty array if an error occurs
        }
    }

    /**
     * Retrieve withdrawals from the database for a given user.
     *
     * @param string $userid The user ID for whom to retrieve withdrawals.
     * @return array The list of withdrawals retrieved from the 'withdrawals' table.
     */
    public function getUserWithdrawals(string $userid): array
    {
        try {
            // Retrieve withdrawals from the 'withdrawals' table, filtered by user ID and ordered by creation date in descending order
            return $this->db->select("withdrawals", "*", [
                "userid" => $userid, 
                "ORDER" => ["created_at" => "DESC"], 
                "LIMIT" => 9 
            ]);
        } catch (Exception $e) {
            // Handle exceptions, such as database errors
            error_log('Error in getUserWithdrawals(): ' . $e->getMessage());
            return []; // Return an empty array if an error occurs
        }
    }

    /**
     * Gets withdrawals with pagination
     *
     * This method retrieves withdrawals for a specified user with pagination from the 'withdrawals' table.
     *
     * @param int $userid The ID of the user.
     * @param int $page The page number for pagination.
     * @return array Returns an array of withdrawal records.
     */
    public function getUserWithdrawalsWithPagination(int $userid, int $page): array
    {
        try {
            $limit = 9; // Number of withdrawals per page
            $offset = ($page - 1) * $limit; // Calculate the offset based on the page number

            // Retrieve withdrawals for the specified user with pagination from the 'withdrawals' table
            return $this->db->select('withdrawals', '*', [
                "userid" => $userid,
                "ORDER" => ["created_at" => "DESC"],
                "LIMIT" => [$offset, $limit]
            ]);
        } catch (Exception $e) {
            // Handle exceptions, such as database errors
            error_log('Error in getUserWithdrawalsWithPagination(): ' . $e->getMessage());
            return []; // Return an empty array if an error occurs
        }
    }

    /**
     * Retrieve investments from the database for a given user.
     *
     * @param string $userid The user ID for whom to retrieve investments.
     * @return array The list of investments retrieved from the 'invests' table.
     */
    public function getUsersInvests(string $userid): array
    {
        try {
            // Retrieve investments from the 'invests' table, filtered by user ID and ordered by creation date in descending order
            return $this->db->select("invests", "*", [
                "userid" => $userid,
                "ORDER" => ["created_at" => "DESC"],
                "LIMIT" => 9
            ]);
        } catch (Exception $e) {
            // Handle exceptions, such as database errors
            error_log('Error in getUsersInvests(): ' . $e->getMessage());
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
    public function getUsersInvestsWithPagination(int $userid, int $page): array
    {
        try {
            $limit = 9; // Number of investing per page
            $offset = ($page - 1) * $limit; // Calculate the offset based on the page number

            // Retrieve investing for the specified user with pagination from the 'invests' table
            return $this->db->select('invests', '*', [
                "userid" => $userid,
                "ORDER" => ["created_at" => "DESC"],
                "LIMIT" => [$offset, $limit]
            ]);
        } catch (Exception $e) {
            // Handle exceptions, such as database errors
            error_log('Error in getUsersInvestsWithPagination(): ' . $e->getMessage());
            return []; // Return an empty array if an error occurs
        }
    }

    /**
     * Retrieve users from the database who were referred by a given user.
     *
     * @param string $userid The user ID for whom to retrieve referred users.
     * @return array The list of referred users retrieved from the 'user' table.
     */
    public function getAllReferredUsers(string $userid): array
    {
        try {
            // Retrieve users from the 'user' table, filtered by referrer's user ID and ordered by registration date in descending order
            return $this->db->select("user", "*", [
                "ref_by" => $userid,
                "ORDER" => ["registration_date" => "DESC"],
                "LIMIT" => 9
            ]);
        } catch (Exception $e) {
            // Handle exceptions, such as database errors
            error_log('Error in getReferredUsers(): ' . $e->getMessage());
            return []; // Return an empty array if an error occurs
        }
    }

    /**
     * Retrieve users from the database who were referred by a given user, with pagination.
     *
     * @param string $userid The user ID for whom to retrieve referred users.
     * @param int $page The page number for pagination.
     * @return array The list of referred users retrieved from the 'user' table.
     */
    public function getUserReferralsWithPagination(string $userid, int $page): array
    {
        try {
            $limit = 9; 
            $offset = ($page - 1) * $limit; 

            // Retrieve users from the 'user' table, filtered by referrer's user ID and ordered by registration date in descending order
            return $this->db->select('user', '*', [
                "ref_by" => $userid,
                "ORDER" => ["registration_date" => "DESC"], 
                "LIMIT" => [$offset, $limit] 
            ]);
        } catch (Exception $e) {
            // Handle exceptions, such as database errors
            error_log('Error in getUserReferralsWithPagination(): ' . $e->getMessage());
            return []; // Return an empty array if an error occurs
        }
    }

    /**
     * Retrieve users from the database who were referred by a given user.
     *
     * @param string $userid The user ID for whom to retrieve referred users.
     * @return array The list of referred users retrieved from the 'user' table.
     */
    public function getThreeReferredUsers(string $userid): array
    {
        try {
            // Retrieve users from the 'user' table, filtered by user ID and ordered by registration date in descending order
            return $this->db->select("user", "*", [
                "ref_by" => $userid,
                "ORDER" => ["registration_date" => "DESC"],
                "LIMIT" => 3
            ]);
        } catch (Exception $e) {
            // Handle exceptions, such as database errors
            error_log('Error in getReferredUsers(): ' . $e->getMessage());
            return []; // Return an empty array if an error occurs
        }
    }

    /**
     * Retrieve commissions from the database for a given user.
     *
     * @param string $userid The user ID for whom to retrieve commissions.
     * @return array The list of commissions retrieved from the 'commissions' table.
     */
    public function getUserCommissions(string $userid): array
    {
        try {
            // Retrieve commissions from the 'commission_logs' table, filtered by user ID and ordered by creation date in descending order
            return $this->db->select("commission_logs", "*", [
                "to_id" => $userid,
                "ORDER" => ["id" => "DESC"],
                "LIMIT" => 9
            ]);
        } catch (Exception $e) {
            // Handle exceptions, such as database errors
            error_log('Error in getUserCommissions(): ' . $e->getMessage());
            return []; // Return an empty array if an error occurs
        }
    }

    /**
     * Gets commissions with pagination
     *
     * This method retrieves commissions for a specified user with pagination from the 'commissions' table.
     *
     * @param int $userid The ID of the user.
     * @param int $page The page number for pagination.
     * @return array Returns an array of transaction records.
     */
    public function getUserCommissionsWithPagination(int $userid, int $page): array
    {
        try {
            $limit = 9; // Number of commissions per page
            $offset = ($page - 1) * $limit; // Calculate the offset based on the page number

            // Retrieve commissions for the specified user with pagination from the 'commission_logs' table
            return $this->db->select('commission_logs', '*', [
                "to_id" => $userid,
                "ORDER" => ["id" => "DESC"],
                "LIMIT" => [$offset, $limit]
            ]);
        } catch (Exception $e) {
            // Handle exceptions, such as database errors
            error_log('Error in getUserCommissionsWithPagination(): ' . $e->getMessage());
            return []; // Return an empty array if an error occurs
        }
    }

    /**
     * Retrieve transactions from the database for a given user.
     *
     * @param string $userid The user ID for whom to retrieve transactions.
     * @return array The list of transactions retrieved from the 'transactions' table.
     */
    public function getUserTransactions(string $userid): array
    {
        try {
            // Retrieve transactions from the 'transactions' table, filtered by user ID and ordered by creation date in descending order
            return $this->db->select("transactions", "*", [
                "userid" => $userid,
                "ORDER" => ["id" => "DESC"],
                "LIMIT" => 9
            ]);
        } catch (Exception $e) {
            // Handle exceptions, such as database errors
            error_log('Error in getUserTransactions(): ' . $e->getMessage());
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
    public function getUserTransactionsWithPagination(int $userid, int $page): array
    {
        try {
            $limit = 9; // Number of transactions per page
            $offset = ($page - 1) * $limit; // Calculate the offset based on the page number

            // Retrieve transactions for the specified user with pagination from the 'transactions' table
            return $this->db->select('transactions', '*', [
                "userid" => $userid,
                "ORDER" => ["id" => "DESC"],
                "LIMIT" => [$offset, $limit]
            ]);
        } catch (Exception $e) {
            // Handle exceptions, such as database errors
            error_log('Error in getUserTransactionsWithPagination(): ' . $e->getMessage());
            return []; // Return an empty array if an error occurs
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
     * Calculate Total Commissions
     *
     * @param string $userid The user ID for whom to calculate total commissions.
     * @return float The total sum of commission amount in the "commission_logs" table for the given user.
     */
    public function commissions(string $userid): float {
        try {
            // Calculate the total sum of commission amount in the "commission_logs" table for the given user
            $sum = $this->db->sum("commission_logs", "commission_amount", [
                "to_id" => $userid
            ]);
            return (float) $sum;
        } catch (Exception $e) {
            // Handle exceptions, such as database errors
            error_log('Error in commissions(): ' . $e->getMessage());
            return 0.0; // Return 0.0 if an error occurs
        }
    }

    /**
     * Add a new deposit record to the 'deposits' table and corresponding transaction record to 'transactions' table.
     *
     * @param int $depositId The ID of the deposit.
     * @param string $userid The ID of the user making the deposit.
     * @param mixed $method_code The code representing the deposit method. Can be null or string depending on the data passed.
     * @param mixed $amount The amount of the deposit. Can be null or string depending on the data passed.
     * @param mixed $trx The transaction ID associated with the deposit. Can be null or string depending on the data passed.
     * @param mixed $newBalance The new balance after the deposit. Can be null or string depending on the data passed.
     * @param string $method_name The name of the deposit method.
     * @return int The number of rows affected by the insert operation.
     */
    public function addUserDeposit(int $depositId, string $userid, mixed $method_code, mixed $amount, mixed $trx, mixed $newBalance, string $method_name): int
    {
        try {

            // Insert the deposit details into the 'deposits' table
            $insert = $this->db->insert('deposits', [
                'depositId' => $depositId,
                'userid' => $userid,
                'method_code' => $method_code,
                'amount' => $amount,
                'trx' => $trx,
                'status' => 1,
                'created_at' => date('Y-m-d H:i:s')
            ]); 

            // Insert transaction details into the 'transactions' table
            $this->db->insert('transactions', [
                'transactionId' => $depositId,
                'userid' => $userid,
                'trx_type' => "+",
                'trx_id' => $trx,
                'amount' => $amount,
                'post_balance' => $newBalance,
                'wallet_type' => "deposit",
                'details' => "Deposit Via " . $method_name,
                'created_at' => date('Y-m-d H:i:s')
            ]);

            // Update the user balance
            $this->db->update('user',[
                'interest_wallet' => $newBalance,
            ],[
                'userid' => $userid
            ]);

            // Return the number of rows affected by the insert operation
            return $insert->rowCount();  
        } catch (Exception $e) {
            // Handle exceptions, such as database errors
            error_log('Error in addUserDeposit(): ' . $e->getMessage());
            return 0; // Return 0 if an error occurs
        }
    }

    /**
     * Add a new withdrawal record to the 'withdrawals' table and corresponding transaction record to 'transactions' table.
     *
     * @param int $withdrawId The ID of the withdrawal.
     * @param string $userid The ID of the user making the withdrawal.
     * @param mixed $withdraw_code The code representing the withdrawal method. Can be null or string depending on the data passed.
     * @param mixed $amount The amount of the withdrawal. Can be null or string depending on the data passed.
     * @param mixed $trx The transaction ID associated with the withdrawal. Can be null or string depending on the data passed.
     * @param string $wallet The wallet address of the user.
     * @param mixed $newBalance The new balance after the withdrawal. Can be null or string depending on the data passed.
     * @param string $method_name The name of the withdrawal method.
     * @return int The number of rows affected by the insert operation.
     */
    public function addUserPayout(int $withdrawId, string $userid, mixed $withdraw_code, mixed $amount, mixed $trx, string $wallet, mixed $newBalance, string $method_name): int
    {
        try {

            // Insert the withdrawal details into the 'withdrawals' table
            $insert = $this->db->insert('withdrawals', [
                'withdrawId' => $withdrawId,
                'userid' => $userid,
                'withdraw_code' => $withdraw_code,
                'amount' => $amount,
                'trx' => $trx,
                'wallet_address' => $wallet,
                'status' => 1,
                'created_at' => date('Y-m-d H:i:s')
            ]); 

            // Insert transaction details into the 'transactions' table
            $this->db->insert('transactions', [
                'transactionId' => $withdrawId,
                'userid' => $userid,
                'trx_type' => "-",
                'trx_id' => $trx,
                'amount' => $amount,
                'post_balance' => $newBalance,
                'wallet_type' => "deposit",
                'details' => "Withdrawn Via " . $method_name,
                'created_at' => date('Y-m-d H:i:s')
            ]);

            // Update the user balance
            $this->db->update('user',[
                'interest_wallet' => $newBalance,
            ],[
                'userid' => $userid
            ]);

            // Return the number of rows affected by the insert operation
            return $insert->rowCount();  
        } catch (Exception $e) {
            // Handle exceptions, such as database errors
            error_log('Error in addUserPayout(): ' . $e->getMessage());
            return 0; // Return 0 if an error occurs
        }
    }

    /**
     * Retrieve initiated deposits from the database
     *
     * @return array The list of deposits retrieved from the 'deposits' table.
     */
    public function allDepositsInitiated(): array
    {
        try {
            // Retrieve deposits from the 'deposits' table, ordered by creation date in descending order
            return $this->db->select("deposits", "*", [
                'status' => 0,
                "ORDER" => ["created_at" => "DESC"]
            ]);
        } catch (Exception $e) {
            // Handle exceptions, such as database errors
            error_log('Error in allDepositsInitiated(): ' . $e->getMessage());
            return []; // Return an empty array if an error occurs
        }
    }

    /**
     * Retrieve pending deposits from the database
     *
     * @return array The list of deposits retrieved from the 'deposits' table.
     */
    public function allDepositsPending(): array
    {
        try {
            // Retrieve deposits from the 'deposits' table, ordered by creation date in descending order
            return $this->db->select("deposits", "*", [
                'status' => 2,
                "ORDER" => ["created_at" => "DESC"]
            ]);
        } catch (Exception $e) {
            // Handle exceptions, such as database errors
            error_log('Error in allDepositsInitiated(): ' . $e->getMessage());
            return []; // Return an empty array if an error occurs
        }
    }

    /**
     * Add New Deposit Method Details
     *
     * Inserts the provided Deposit Method details into the database.
     *
     * @param string $method_code The unique ID of the gateway entry.
     * @param mixed $filename The filename of the Deposit Method image.
     * @param string $name The name of the Deposit Method.
     * @param string $abbreviation The abbreviation of the Deposit Method.
     * @param float $min_amount The minimum amount allowed for the Deposit Method.
     * @param float $max_amount The maximum amount allowed for the Deposit Method.
     * @param string $gateway_parameter The gateway parameter for the Deposit Method.
     * @param int $status The status of the Deposit Method (e.g., active or inactive).
     * @param int $need_proof Flag indicating whether proof is required for the Deposit Method.
     * @param string $proof_type The type of proof required for the Deposit Method.
     * @return int The number of rows affected by the insert operation (usually one if successful).
     * @throws Exception If the insert operation fails.
     */
    public function addDepositMethod(string $method_code, mixed $filename, string $name, string $abbreviation, float $min_amount, float $max_amount, string $gateway_parameter, int $status, int $need_proof, string $proof_type): int 
    {
        try {
            // Insert method details into the 'gateway_currencies' table
            $insert = $this->db->insert('gateway_currencies', [
                'method_code' => $method_code,
                'image' => $filename,
                'name' => $name,
                'abbreviation' => $abbreviation,
                'min_amount' => $min_amount,
                'max_amount' => $max_amount,
                'gateway_parameter' => $gateway_parameter,
                'status' => $status,
                'need_proof' => $need_proof,
                'proof_type' => $proof_type,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]);

            // Return the number of rows affected by the insert operation
            return $insert->rowCount();
        } catch (Exception $e) {
            // Handle exceptions, such as database errors
            error_log('Error in addDepositMethod(): ' . $e->getMessage());
            return 0; // Return 0 to indicate failure
        }
    }

    /**
     * Update Deposit Method Details
     *
     * Updates the provided Deposit Method details in the database.
     *
     * @param string $method_code The unique ID of the Deposit Method to update.
     * @param mixed $filename The filename of the updated Deposit Method image.
     * @param string $name The updated name of the Deposit Method.
     * @param string $abbreviation The updated abbreviation of the Deposit Method.
     * @param float $min_amount The updated minimum amount allowed for the Deposit Method.
     * @param float $max_amount The updated maximum amount allowed for the Deposit Method.
     * @param string $gateway_parameter The updated gateway parameter for the Deposit Method.
     * @param int $status The updated status of the Deposit Method (e.g., active or inactive).
     * @param int $need_proof The updated flag indicating whether proof is required for the Deposit Method.
     * @param string $proof_type The updated type of proof required for the Deposit Method.
     * @return int The number of rows affected by the update operation (usually one if successful).
     * @throws Exception If the update operation fails.
     */
    public function updateDepositMethod(string $method_code, mixed $filename, string $name, string $abbreviation, float $min_amount, float $max_amount, string $gateway_parameter, int $status, int $need_proof, string $proof_type): int 
    {
        try {
            // Update method details in the 'gateway_currencies' table
            $update = $this->db->update('gateway_currencies', [
                'image' => $filename,
                'name' => $name,
                'abbreviation' => $abbreviation,
                'min_amount' => $min_amount,
                'max_amount' => $max_amount,
                'gateway_parameter' => $gateway_parameter,
                'status' => $status,
                'need_proof' => $need_proof,
                'proof_type' => $proof_type
            ], [
                'method_code' => $method_code
            ]);

            // Return the number of rows affected by the update operation
            return $update->rowCount();
        } catch (Exception $e) {
            // Handle exceptions, such as database errors
            error_log('Error in updateDepositMethod(): ' . $e->getMessage());
            return 0; // Return 0 to indicate failure
        }
    }

    /**
     * Update Deposit Method Details (No Image)
     *
     * Updates the provided Deposit Method details in the database without changing the image.
     *
     * @param string $method_code The unique ID of the Deposit Method to update.
     * @param string $name The updated name of the Deposit Method.
     * @param string $abbreviation The updated abbreviation of the Deposit Method.
     * @param float $min_amount The updated minimum amount allowed for the Deposit Method.
     * @param float $max_amount The updated maximum amount allowed for the Deposit Method.
     * @param string $gateway_parameter The updated gateway parameter for the Deposit Method.
     * @param int $status The updated status of the Deposit Method (e.g., active or inactive).
     * @param int $need_proof The updated flag indicating whether proof is required for the Deposit Method.
     * @param string $proof_type The updated type of proof required for the Deposit Method.
     * @return int The number of rows affected by the update operation (usually one if successful).
     * @throws Exception If the update operation fails.
     */
    public function updateDepositMethodNoImage(string $method_code, string $name, string $abbreviation, float $min_amount, float $max_amount, string $gateway_parameter, int $status, int $need_proof, string $proof_type): int 
    {
        try {
            // Update method details in the 'gateway_currencies' table without changing the image
            $update = $this->db->update('gateway_currencies', [
                'name' => $name,
                'abbreviation' => $abbreviation,
                'min_amount' => $min_amount,
                'max_amount' => $max_amount,
                'gateway_parameter' => $gateway_parameter,
                'status' => $status,
                'need_proof' => $need_proof,
                'proof_type' => $proof_type
            ], ['method_code' => $method_code]);

            // Return the number of rows affected by the update operation
            return $update->rowCount();
        } catch (Exception $e) {
            // Handle exceptions, such as database errors
            error_log('Error in updateDepositMethodNoImage(): ' . $e->getMessage());
            return 0; // Return 0 to indicate failure
        }
    }

    /**
     * Activate Gateway Currency
     *
     * This method activates a gateway currency in the database by updating its 'status' field to 1.
     *
     * @param string $method_code The method code of the gateway currency being activated.
     * @return int The number of rows affected by the update operation (usually one if successful).
     */
    public function activateDepositMethod(string $method_code): int {
        try {
            // Update the 'status' field of the 'gateway_currencies' table to 1 for the specified method code
            $update = $this->db->update('gateway_currencies', [
                'status' => 1,
            ], [
                'method_code' => $method_code
            ]);

            // Return the number of rows affected by the update operation
            return $update->rowCount();
        } catch (Exception $e) {
            // Handle exceptions, such as database errors
            error_log('Error in activateDepositMethod(): ' . $e->getMessage());
            return 0; // Return 0 to indicate failure
        }
    }

    /**
     * Deactivate Gateway Currency
     *
     * This method deactivates a gateway currency in the database by updating its 'status' field to 0.
     *
     * @param string $method_code The method code of the gateway currency being deactivated.
     * @return int The number of rows affected by the update operation (usually one if successful).
     */
    public function deactivateDepositMethod(string $method_code): int {
        try {
            // Update the 'status' field of the 'gateway_currencies' table to 2 for the specified method code
            $update = $this->db->update('gateway_currencies', [
                'status' => 2,
            ], [
                'method_code' => $method_code
            ]);

            // Return the number of rows affected by the update operation
            return $update->rowCount();
        } catch (Exception $e) {
            // Handle exceptions, such as database errors
            error_log('Error in deactivateDepositMethod(): ' . $e->getMessage());
            return 0; // Return 0 to indicate failure
        }
    }

    /**
     * Add New Withdrawal Method Details
     *
     * Inserts the provided Withdrawal Method details into the database.
     *
     * @param string $withdraw_code The unique ID of the gateway entry.
     * @param mixed $filename The filename of the Withdrawal Method image.
     * @param string $name The name of the Withdrawal Method.
     * @param string $abbreviation The abbreviation of the Withdrawal Method.
     * @param float $min_amount The minimum amount allowed for the Withdrawal Method.
     * @param float $max_amount The maximum amount allowed for the Withdrawal Method.
     * @param int $status The status of the Withdrawal Method (e.g., active or inactive).
     * @return int The number of rows affected by the insert operation (usually one if successful).
     * @throws Exception If the insert operation fails.
     */
    public function addWithdrawalMethod(string $withdraw_code, mixed $filename, string $name, string $abbreviation, float $min_amount, float $max_amount, int $status): int 
    {
        try {
            // Insert method details into the 'withdraw_methods' table
            $insert = $this->db->insert('withdraw_methods', [
                'withdraw_code' => $withdraw_code,
                'image' => $filename,
                'name' => $name,
                'abbreviation' => $abbreviation,
                'min_amount' => $min_amount,
                'max_amount' => $max_amount,
                'status' => $status,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]);

            // Return the number of rows affected by the insert operation
            return $insert->rowCount();
        } catch (Exception $e) {
            // Handle exceptions, such as database errors
            error_log('Error in addWithdrawalMethod(): ' . $e->getMessage());
            return 0; // Return 0 to indicate failure
        }
    }

    /**
     * Update Withdrawal Method Details
     *
     * Updates the provided Withdrawal Method details in the database.
     *
     * @param string $withdraw_code The unique ID of the Withdrawal Method to update.
     * @param mixed $filename The filename of the updated Withdrawal Method image.
     * @param string $name The updated name of the Withdrawal Method.
     * @param string $abbreviation The updated abbreviation of the Withdrawal Method.
     * @param float $min_amount The updated minimum amount allowed for the Withdrawal Method.
     * @param float $max_amount The updated maximum amount allowed for the Withdrawal Method.
     * @param int $status The updated status of the Withdrawal Method (e.g., active or inactive).
     * @return int The number of rows affected by the update operation (usually one if successful).
     * @throws Exception If the update operation fails.
     */
    public function updateWithdrawalMethod(string $withdraw_code, mixed $filename, string $name, string $abbreviation, float $min_amount, float $max_amount, int $status): int 
    {
        try {
            // Update method details in the 'withdraw_methods' table
            $update = $this->db->update('withdraw_methods', [
                'image' => $filename,
                'name' => $name,
                'abbreviation' => $abbreviation,
                'min_amount' => $min_amount,
                'max_amount' => $max_amount,
                'status' => $status
            ], [
                'withdraw_code' => $withdraw_code
            ]);

            // Return the number of rows affected by the update operation
            return $update->rowCount();
        } catch (Exception $e) {
            // Handle exceptions, such as database errors
            error_log('Error in updateWithdrawalMethod(): ' . $e->getMessage());
            return 0; // Return 0 to indicate failure
        }
    }

    /**
     * Update Withdrawal Method Details (No Image)
     *
     * Updates the provided Withdrawal Method details in the database without changing the image.
     *
     * @param string $withdraw_code The unique ID of the Withdrawal Method to update.
     * @param string $name The updated name of the Withdrawal Method.
     * @param string $abbreviation The updated abbreviation of the Withdrawal Method.
     * @param float $min_amount The updated minimum amount allowed for the Withdrawal Method.
     * @param float $max_amount The updated maximum amount allowed for the Withdrawal Method.
     * @param int $status The updated status of the Withdrawal Method (e.g., active or inactive).
     * @return int The number of rows affected by the update operation (usually one if successful).
     * @throws Exception If the update operation fails.
     */
    public function updateWithdrawalMethodNoImage(string $withdraw_code, string $name, string $abbreviation, float $min_amount, float $max_amount, int $status): int 
    {
        try {
            // Update method details in the 'withdraw_methods' table without changing the image
            $update = $this->db->update('withdraw_methods', [
                'name' => $name,
                'abbreviation' => $abbreviation,
                'min_amount' => $min_amount,
                'max_amount' => $max_amount,
                'status' => $status,
            ], ['withdraw_code' => $withdraw_code]);

            // Return the number of rows affected by the update operation
            return $update->rowCount();
        } catch (Exception $e) {
            // Handle exceptions, such as database errors
            error_log('Error in updateWithdrawalMethodNoImage(): ' . $e->getMessage());
            return 0; // Return 0 to indicate failure
        }
    }

    /**
     * Activate Withdrawal Gateway Currency
     *
     * This method activates a gateway currency in the database by updating its 'status' field to 1.
     *
     * @param string $withdraw_code The method code of the gateway currency being activated.
     * @return int The number of rows affected by the update operation (usually one if successful).
     */
    public function activateWithdrawalMethod(string $withdraw_code): int {
        try {
            // Update the 'status' field of the 'withdraw_methods' table to 1 for the specified method code
            $update = $this->db->update('withdraw_methods', [
                'status' => 1,
            ], [
                'withdraw_code' => $withdraw_code
            ]);

            // Return the number of rows affected by the update operation
            return $update->rowCount();
        } catch (Exception $e) {
            // Handle exceptions, such as database errors
            error_log('Error in activateWithdrawalMethod(): ' . $e->getMessage());
            return 0; // Return 0 to indicate failure
        }
    }

    /**
     * Deactivate Withdrawal Gateway Currency
     *
     * This method deactivates a gateway currency in the database by updating its 'status' field to 0.
     *
     * @param string $withdraw_code The method code of the gateway currency being deactivated.
     * @return int The number of rows affected by the update operation (usually one if successful).
     */
    public function deactivateWithdrawalMethod(string $withdraw_code): int {
        try {
            // Update the 'status' field of the 'withdraw_methods' table to 2 for the specified method code
            $update = $this->db->update('withdraw_methods', [
                'status' => 2,
            ], [
                'withdraw_code' => $withdraw_code
            ]);

            // Return the number of rows affected by the update operation
            return $update->rowCount();
        } catch (Exception $e) {
            // Handle exceptions, such as database errors
            error_log('Error in deactivateWithdrawalMethod(): ' . $e->getMessage());
            return 0; // Return 0 to indicate failure
        }
    }

    /**
     * Approve User's Account
     *
     * This method approves an address proof in the database by updating the 'status' field to 1.
     *
     * @param int $uploadid The ID of the address proof whose status is being approved.
     * @param string $userid The ID of the user whose address proof is being approved.
     * @return int The number of rows affected by the update operation (usually one if successful).
     */
    public function approveAddressProof(int $uploadid, string $userid): int {
        try {
            // Check if user exists in identity_proof table for the specified user ID and identity types
            $user_row = $this->db->select('identity_proof', '*', [
                "userid" => $userid,
                "identity_type" => [1, 2, 3],
                "status" => 1
            ]);

            // Update the 'status' field of the 'address_proof' table to 1 for the specified upload ID
            $update = $this->db->update('address_proof', ['status' => 1], ['uploadid' => $uploadid]);

            // If user exists in identity_proof table, update user's account verify status to 1
            if ($user_row) {
                $this->db->update('user', ['account_verify' => 1], ['userid' => $userid]);
            }

            // Return the number of rows affected by the update operation
            return $update->rowCount();
        } catch (Exception $e) {
            // Handle exceptions, such as database errors
            error_log('Error in approveAddressProof(): ' . $e->getMessage());
            return 0; // Return 0 to indicate failure
        }
    }

    /**
     * Reject User's Address Proof
     *
     * This method rejects an address proof in the database by updating the 'status' field to 2.
     *
     * @param int $uploadid The ID of the address proof whose status is being rejected.
     * @param string $userid The ID of the user whose address proof is being rejected.
     * @return int The number of rows affected by the update operation (usually one if successful).
     */
    public function rejectAddressProof(int $uploadid, string $userid): int {
        try {

            // Check if user exists in identity_proof table for the specified user ID and identity types
            $user_row = $this->db->select('identity_proof', '*', [
                "userid" => $userid,
                "identity_type" => [1, 2, 3],
                "status" => 3
            ]);

            // Update the 'status' field of the 'address_proof' table to 3 for the specified upload ID
            $update = $this->db->update('address_proof', ['status' => 3], ['uploadid' => $uploadid]);

            // If user exists in identity_proof table, update user's account verify status to 3
            if ($user_row) {
                $this->db->update('user', ['account_verify' => 3], ['userid' => $userid]);
            }

            // Return the number of rows affected by the update operation
            return $update->rowCount();
        } catch (Exception $e) {
            // Handle exceptions, such as database errors
            error_log('Error in rejectAddressProof(): ' . $e->getMessage());
            return 0; // Return 0 to indicate failure
        }
    }

    /**
     * Approve User's Account
     *
     * This method approves an identity proof in the database by updating the 'status' field to 1.
     *
     * @param int $uploadid The ID of the identity proof whose status is being approved.
     * @param string $userid The ID of the user whose identity proof is being approved.
     * @return int The number of rows affected by the update operation (usually one if successful).
     */
    public function approveIdentityProof(int $uploadid, string $userid): int {
        try {
            // Check if user exists in address_proof table for the specified user ID and identity types
            $user_row = $this->db->select('address_proof', '*', [
                "userid" => $userid,
                "identity_type" => 4,
                "status" => 1
            ]);

            // Update the 'status' field of the 'identity_proof' table to 1 for the specified upload ID
            $update = $this->db->update('identity_proof', ['status' => 1], ['uploadid' => $uploadid]);

            // If a user exists in address_proof table, update user's account verify status to 1
            if ($user_row) {
                $this->db->update('user', ['account_verify' => 1], ['userid' => $userid]);
            }

            // Return the number of rows affected by the update operation
            return $update->rowCount();
        } catch (Exception $e) {
            // Handle exceptions, such as database errors
            error_log('Error in approveAddressProof(): ' . $e->getMessage());
            return 0; // Return 0 to indicate failure
        }
    }

    /**
     * Reject User's Address Proof
     *
     * This method rejects an identity proof in the database by updating the 'status' field to 2.
     *
     * @param int $uploadid The ID of the identity proof whose status is being rejected.
     * @param string $userid The ID of the user whose identity proof is being rejected.
     * @return int The number of rows affected by the update operation (usually one if successful).
     */
    public function rejectIdentityProof(int $uploadid, string $userid): int {
        try {

            // Check if user exists in address_proof table for the specified user ID and identity types
            $user_row = $this->db->select('address_proof', '*', [
                "userid" => $userid,
                "identity_type" => 4,
                "status" => 3
            ]);

            // Update the 'status' field of the 'identity_proof' table to 3 for the specified upload ID
            $update = $this->db->update('identity_proof', ['status' => 3], ['uploadid' => $uploadid]);

            // If a user exists in address_proof table, update user's account verify status to 3
            if ($user_row) {
                $this->db->update('user', ['account_verify' => 3], ['userid' => $userid]);
            }

            // Return the number of rows affected by the update operation
            return $update->rowCount();
        } catch (Exception $e) {
            // Handle exceptions, such as database errors
            error_log('Error in rejectAddressProof(): ' . $e->getMessage());
            return 0; // Return 0 to indicate failure
        }
    }

    /**
     * Count Running Withdrawals
     *
     * This method retrieves the count of Running Withdrawals from the "withdrawals" table in the database.
     *
     * @return int - Number of Running Withdrawals in the "withdrawals" table
     */
    public function CountPendingWithdrawals(): int
    {
        try {
            // Count the number of rows in the "withdrawals" table
            return $this->db->count("withdrawals", "*", [
                "status" => 2
            ]);
        } catch (Exception $e) {
            // Handle exceptions, such as database errors
            error_log('Error in CountPendingWithdrawals(): ' . $e->getMessage());
            return 0; // Return 0 to indicate failure or absence of running investments
        }
    }

    /**
     * Count Initiated Withdrawals
     *
     * This method retrieves the count of Initiated Withdrawals from the "invests" table in the database.
     *
     * @return int - Number of Initiated Withdrawals in the "withdrawals" table
     */
    public function CountInitiatedWithdrawals(): int
    {
        try {
            // Count the number of rows in the "withdrawals" table
            return $this->db->count("withdrawals", "*", [
                "status" => 0
            ]);
        } catch (Exception $e) {
            // Handle exceptions, such as database errors
            error_log('Error in CountInitiatedWithdrawals(): ' . $e->getMessage());
            return 0; // Return 0 to indicate failure or absence of running investments
        }
    }

    /**
     * Retrieve withdrawals from the database
     *
     * @return array The list of withdrawals retrieved from the 'withdrawals' table.
     */
    public function getAllWithdrawals(): array
    {
        try {
            // Retrieve withdrawals from the 'withdrawals' table, ordered by creation date in descending order
            return $this->db->select("withdrawals", "*", [
                "ORDER" => ["created_at" => "DESC"], 
                "LIMIT" => 5 
            ]);
        } catch (Exception $e) {
            // Handle exceptions, such as database errors
            error_log('Error in getAllWithdrawals(): ' . $e->getMessage());
            return []; // Return an empty array if an error occurs
        }
    }

    /**
     * Gets withdrawals with pagination
     *
     * This method retrieves withdrawals with pagination from the 'withdrawals' table.
     *
     * @param int $page The page number for pagination.
     * @return array Returns an array of withdrawal records.
     */
    public function getWithdrawalsWithPagination(int $page): array
    {
        try {
            $limit = 5; // Number of withdrawals per page
            $offset = ($page - 1) * $limit; // Calculate the offset based on the page number

            // Retrieve withdrawals for the specified user with pagination from the 'withdrawals' table
            return $this->db->select('withdrawals', '*', [
                "ORDER" => ["created_at" => "DESC"],
                "LIMIT" => [$offset, $limit]
            ]);
        } catch (Exception $e) {
            // Handle exceptions, such as database errors
            error_log('Error in getWithdrawalsWithPagination(): ' . $e->getMessage());
            return []; // Return an empty array if an error occurs
        }
    }

    /**
     * Retrieve pending withdrawals from the database
     *
     * @return array The list of withdrawals retrieved from the 'withdrawals' table.
     */
    public function getPendingWithdrawals(): array
    {
        try {
            // Retrieve withdrawals from the 'withdrawals' table, ordered by creation date in descending order
            return $this->db->select("withdrawals", "*", [
                "status" => 2,
                "ORDER" => ["created_at" => "DESC"], 
                "LIMIT" => 5 
            ]);
        } catch (Exception $e) {
            // Handle exceptions, such as database errors
            error_log('Error in getPendingWithdrawals(): ' . $e->getMessage());
            return []; // Return an empty array if an error occurs
        }
    }

    /**
     * Gets pending withdrawals with pagination
     *
     * This method retrieves pending withdrawals with pagination from the 'withdrawals' table.
     *
     * @param int $page The page number for pagination.
     * @return array Returns an array of withdrawal records.
     */
    public function getPendingWithdrawalsWithPagination(int $page): array
    {
        try {
            $limit = 5; // Number of withdrawals per page
            $offset = ($page - 1) * $limit; // Calculate the offset based on the page number

            // Retrieve withdrawals for the specified user with pagination from the 'withdrawals' table
            return $this->db->select('withdrawals', '*', [
                "status" => 2,
                "ORDER" => ["created_at" => "DESC"],
                "LIMIT" => [$offset, $limit]
            ]);
        } catch (Exception $e) {
            // Handle exceptions, such as database errors
            error_log('Error in getPendingWithdrawalsWithPagination(): ' . $e->getMessage());
            return []; // Return an empty array if an error occurs
        }
    }

    /**
     * Retrieve rejected withdrawals from the database
     *
     * @return array The list of withdrawals retrieved from the 'withdrawals' table.
     */
    public function getRejectedWithdrawals(): array
    {
        try {
            // Retrieve withdrawals from the 'withdrawals' table, ordered by creation date in descending order
            return $this->db->select("withdrawals", "*", [
                "status" => 3,
                "ORDER" => ["created_at" => "DESC"], 
                "LIMIT" => 5 
            ]);
        } catch (Exception $e) {
            // Handle exceptions, such as database errors
            error_log('Error in getPendingWithdrawals(): ' . $e->getMessage());
            return []; // Return an empty array if an error occurs
        }
    }

    /**
     * Gets rejected withdrawals with pagination
     *
     * This method retrieves rejected withdrawals with pagination from the 'withdrawals' table.
     *
     * @param int $page The page number for pagination.
     * @return array Returns an array of withdrawal records.
     */
    public function getRejectedWithdrawalsWithPagination(int $page): array
    {
        try {
            $limit = 5; // Number of withdrawals per page
            $offset = ($page - 1) * $limit; // Calculate the offset based on the page number

            // Retrieve withdrawals for the specified user with pagination from the 'withdrawals' table
            return $this->db->select('withdrawals', '*', [
                "status" => 3,
                "ORDER" => ["created_at" => "DESC"],
                "LIMIT" => [$offset, $limit]
            ]);
        } catch (Exception $e) {
            // Handle exceptions, such as database errors
            error_log('Error in getPendingWithdrawalsWithPagination(): ' . $e->getMessage());
            return []; // Return an empty array if an error occurs
        }
    }

    /**
     * Retrieve initiated withdrawals from the database
     *
     * @return array The list of withdrawals retrieved from the 'withdrawals' table.
     */
    public function getInitiatedWithdrawals(): array
    {
        try {
            // Retrieve withdrawals from the 'withdrawals' table, ordered by creation date in descending order
            return $this->db->select("withdrawals", "*", [
                "status" => 0,
                "ORDER" => ["created_at" => "DESC"], 
                "LIMIT" => 5 
            ]);
        } catch (Exception $e) {
            // Handle exceptions, such as database errors
            error_log('Error in getPendingWithdrawals(): ' . $e->getMessage());
            return []; // Return an empty array if an error occurs
        }
    }

    /**
     * Gets initiated withdrawals with pagination
     *
     * This method retrieves initiated withdrawals with pagination from the 'withdrawals' table.
     *
     * @param int $page The page number for pagination.
     * @return array Returns an array of withdrawal records.
     */
    public function getInitiatedWithdrawalsWithPagination(int $page): array
    {
        try {
            $limit = 5; // Number of withdrawals per page
            $offset = ($page - 1) * $limit; // Calculate the offset based on the page number

            // Retrieve withdrawals for the specified user with pagination from the 'withdrawals' table
            return $this->db->select('withdrawals', '*', [
                "status" => 0,
                "ORDER" => ["created_at" => "DESC"],
                "LIMIT" => [$offset, $limit]
            ]);
        } catch (Exception $e) {
            // Handle exceptions, such as database errors
            error_log('Error in getPendingWithdrawalsWithPagination(): ' . $e->getMessage());
            return []; // Return an empty array if an error occurs
        }
    }

    /**
     * Retrieve completed withdrawals from the database
     *
     * @return array The list of withdrawals retrieved from the 'withdrawals' table.
     */
    public function getCompletedWithdrawals(): array
    {
        try {
            // Retrieve withdrawals from the 'withdrawals' table, ordered by creation date in descending order
            return $this->db->select("withdrawals", "*", [
                "status" => 1,
                "ORDER" => ["created_at" => "DESC"], 
                "LIMIT" => 5 
            ]);
        } catch (Exception $e) {
            // Handle exceptions, such as database errors
            error_log('Error in getCompletedWithdrawals(): ' . $e->getMessage());
            return []; // Return an empty array if an error occurs
        }
    }

    /**
     * Gets completed withdrawals with pagination
     *
     * This method retrieves completed withdrawals with pagination from the 'withdrawals' table.
     *
     * @param int $page The page number for pagination.
     * @return array Returns an array of withdrawal records.
     */
    public function getCompletedWithdrawalsWithPagination(int $page): array
    {
        try {
            $limit = 5; // Number of withdrawals per page
            $offset = ($page - 1) * $limit; // Calculate the offset based on the page number

            // Retrieve withdrawals for the specified user with pagination from the 'withdrawals' table
            return $this->db->select('withdrawals', '*', [
                "status" => 1,
                "ORDER" => ["created_at" => "DESC"],
                "LIMIT" => [$offset, $limit]
            ]);
        } catch (Exception $e) {
            // Handle exceptions, such as database errors
            error_log('Error in getCompletedWithdrawalsWithPagination(): ' . $e->getMessage());
            return []; // Return an empty array if an error occurs
        }
    }

    /**
     * Count Running Deposits
     *
     * This method retrieves the count of Running Deposits from the "deposits" table in the database.
     *
     * @return int - Number of Running Deposits in the "deposits" table
     */
    public function CountPendingDeposits(): int
    {
        try {
            // Count the number of rows in the "deposits" table
            return $this->db->count("deposits", "*", [
                "method_code[!]" => "",
                "status" => 2
            ]);
        } catch (Exception $e) {
            // Handle exceptions, such as database errors
            error_log('Error in CountPendingDeposits(): ' . $e->getMessage());
            return 0; // Return 0 to indicate failure or absence of running investments
        }
    }

    /**
     * Count Initiated Deposits
     *
     * This method retrieves the count of Initiated Deposits from the "invests" table in the database.
     *
     * @return int - Number of Initiated Deposits in the "deposits" table
     */
    public function CountInitiatedDeposits(): int
    {
        try {
            // Count the number of rows in the "deposits" table
            return $this->db->count("deposits", "*", [
                "method_code[!]" => "",
                "status" => 0
            ]);
        } catch (Exception $e) {
            // Handle exceptions, such as database errors
            error_log('Error in CountInitiatedDeposits(): ' . $e->getMessage());
            return 0; // Return 0 to indicate failure or absence of running investments
        }
    }

    /**
     * Retrieve pending deposits from the database
     *
     * @return array The list of deposits retrieved from the 'deposits' table.
     */
    public function getPendingDeposits(): array
    {
        try {
            // Retrieve deposits from the 'deposits' table, ordered by creation date in descending order
            return $this->db->select("deposits", "*", [
                "method_code[!]" => "",
                "status" => 2,
                "ORDER" => ["created_at" => "DESC"], 
                "LIMIT" => 5 
            ]);
        } catch (Exception $e) {
            // Handle exceptions, such as database errors
            error_log('Error in getPendingDeposits(): ' . $e->getMessage());
            return []; // Return an empty array if an error occurs
        }
    }

    /**
     * Gets pending deposits with pagination
     *
     * This method retrieves pending deposits with pagination from the 'deposits' table.
     *
     * @param int $page The page number for pagination.
     * @return array Returns an array of withdrawal records.
     */
    public function getPendingDepositsWithPagination(int $page): array
    {
        try {
            $limit = 5; // Number of deposits per page
            $offset = ($page - 1) * $limit; // Calculate the offset based on the page number

            // Retrieve deposits for the specified user with pagination from the 'deposits' table
            return $this->db->select('deposits', '*', [
                "method_code[!]" => "",
                "status" => 2,
                "ORDER" => ["created_at" => "DESC"],
                "LIMIT" => [$offset, $limit]
            ]);
        } catch (Exception $e) {
            // Handle exceptions, such as database errors
            error_log('Error in getPendingDepositsWithPagination(): ' . $e->getMessage());
            return []; // Return an empty array if an error occurs
        }
    }

    /**
     * Retrieve rejected deposits from the database
     *
     * @return array The list of deposits retrieved from the 'deposits' table.
     */
    public function getRejectedDeposits(): array
    {
        try {
            // Retrieve deposits from the 'deposits' table, ordered by creation date in descending order
            return $this->db->select("deposits", "*", [
                "method_code[!]" => "",
                "status" => 3,
                "ORDER" => ["created_at" => "DESC"], 
                "LIMIT" => 5 
            ]);
        } catch (Exception $e) {
            // Handle exceptions, such as database errors
            error_log('Error in getRejectedDeposits(): ' . $e->getMessage());
            return []; // Return an empty array if an error occurs
        }
    }

    /**
     * Gets rejected deposits with pagination
     *
     * This method retrieves rejected deposits with pagination from the 'deposits' table.
     *
     * @param int $page The page number for pagination.
     * @return array Returns an array of withdrawal records.
     */
    public function getRejectedDepositsWithPagination(int $page): array
    {
        try {
            $limit = 5; // Number of deposits per page
            $offset = ($page - 1) * $limit; // Calculate the offset based on the page number

            // Retrieve deposits for the specified user with pagination from the 'deposits' table
            return $this->db->select('deposits', '*', [
                "method_code[!]" => "",
                "status" => 3,
                "ORDER" => ["created_at" => "DESC"],
                "LIMIT" => [$offset, $limit]
            ]);
        } catch (Exception $e) {
            // Handle exceptions, such as database errors
            error_log('Error in getRejectedDepositsWithPagination(): ' . $e->getMessage());
            return []; // Return an empty array if an error occurs
        }
    }

    /**
     * Retrieve initiated deposits from the database
     *
     * @return array The list of deposits retrieved from the 'deposits' table.
     */
    public function getInitiatedDeposits(): array
    {
        try {
            // Retrieve deposits from the 'deposits' table, ordered by creation date in descending order
            return $this->db->select("deposits", "*", [
                "method_code[!]" => "",
                "status" => 0,
                "ORDER" => ["created_at" => "DESC"], 
                "LIMIT" => 5 
            ]);
        } catch (Exception $e) {
            // Handle exceptions, such as database errors
            error_log('Error in getInitiatedDeposits(): ' . $e->getMessage());
            return []; // Return an empty array if an error occurs
        }
    }

    /**
     * Gets initiated deposits with pagination
     *
     * This method retrieves initiated deposits with pagination from the 'deposits' table.
     *
     * @param int $page The page number for pagination.
     * @return array Returns an array of withdrawal records.
     */
    public function getInitiatedDepositsWithPagination(int $page): array
    {
        try {
            $limit = 5; // Number of deposits per page
            $offset = ($page - 1) * $limit; // Calculate the offset based on the page number

            // Retrieve deposits for the specified user with pagination from the 'deposits' table
            return $this->db->select('deposits', '*', [
                "method_code[!]" => "",
                "status" => 0,
                "ORDER" => ["created_at" => "DESC"],
                "LIMIT" => [$offset, $limit]
            ]);
        } catch (Exception $e) {
            // Handle exceptions, such as database errors
            error_log('Error in getInitiatedDepositsWithPagination(): ' . $e->getMessage());
            return []; // Return an empty array if an error occurs
        }
    }

    /**
     * Retrieve completed deposits from the database
     *
     * @return array The list of deposits retrieved from the 'deposits' table.
     */
    public function getCompletedDeposits(): array
    {
        try {
            // Retrieve deposits from the 'deposits' table, ordered by creation date in descending order
            return $this->db->select("deposits", "*", [
                "method_code[!]" => "",
                "status" => 1,
                "ORDER" => ["created_at" => "DESC"], 
                "LIMIT" => 5 
            ]);
        } catch (Exception $e) {
            // Handle exceptions, such as database errors
            error_log('Error in getCompletedDeposits(): ' . $e->getMessage());
            return []; // Return an empty array if an error occurs
        }
    }

    /**
     * Gets completed deposits with pagination
     *
     * This method retrieves completed deposits with pagination from the 'deposits' table.
     *
     * @param int $page The page number for pagination.
     * @return array Returns an array of withdrawal records.
     */
    public function getCompletedDepositsWithPagination(int $page): array
    {
        try {
            $limit = 5; // Number of deposits per page
            $offset = ($page - 1) * $limit; // Calculate the offset based on the page number

            // Retrieve deposits for the specified user with pagination from the 'deposits' table
            return $this->db->select('deposits', '*', [
                "method_code[!]" => "",
                "status" => 1,
                "ORDER" => ["created_at" => "DESC"],
                "LIMIT" => [$offset, $limit]
            ]);
        } catch (Exception $e) {
            // Handle exceptions, such as database errors
            error_log('Error in getCompletedDepositsWithPagination(): ' . $e->getMessage());
            return []; // Return an empty array if an error occurs
        }
    }

    /**
     * Retrieve deposits from the database
     *
     * @return array The list of deposits retrieved from the 'deposits' table.
     */
    public function getDeposits(): array
    {
        try {
            // Retrieve deposits from the 'deposits' table, ordered by creation date in descending order
            return $this->db->select("deposits", "*", [
                "method_code[!]" => "",
                "ORDER" => ["created_at" => "DESC"], 
                "LIMIT" => 5 
            ]);
        } catch (Exception $e) {
            // Handle exceptions, such as database errors
            error_log('Error in getDeposits(): ' . $e->getMessage());
            return []; // Return an empty array if an error occurs
        }
    }

    /**
     * Gets deposits with pagination
     *
     * This method retrieves deposits with pagination from the 'deposits' table.
     *
     * @param int $page The page number for pagination.
     * @return array Returns an array of withdrawal records.
     */
    public function getDepositsWithPagination(int $page): array
    {
        try {
            $limit = 5; // Number of deposits per page
            $offset = ($page - 1) * $limit; // Calculate the offset based on the page number

            // Retrieve deposits for the specified user with pagination from the 'deposits' table
            return $this->db->select('deposits', '*', [
                "method_code[!]" => "",
                "ORDER" => ["created_at" => "DESC"],
                "LIMIT" => [$offset, $limit]
            ]);
        } catch (Exception $e) {
            // Handle exceptions, such as database errors
            error_log('Error in getDepositsWithPagination(): ' . $e->getMessage());
            return []; // Return an empty array if an error occurs
        }
    }

    /**
     * Adds a plan to the database.
     *
     * This method inserts a new plan into the 'transactions' table.
     *
     * @param string $planId The ID of the plan.
     * @param string $name The name of the plan.
     * @param mixed $minimum The minimum investment amount.
     * @param mixed $maximum The maximum investment amount.
     * @param mixed $fixed_amount The fixed investment amount.
     * @param int $interest The interest rate.
     * @param mixed $interest_status The status of interest.
     * @param int $times The number of times.
     * @param int $status The status of the plan.
     * @param int $featured The featured status.
     * @param int $capital_back_status The status of capital back.
     * @param int $lifetime_status The lifetime status.
     * @param int $repeat_time The repeat time.
     * @return int The number of rows affected by the insertion.
     */
    public function addPlan(string $planId, string $name, mixed $minimum, mixed $maximum, mixed $fixed_amount, mixed $interest, int $interest_status, int $times, int $status, int $featured, int $capital_back_status, int $lifetime_status, int $repeat_time): int
    {
        try {
            // Insert plan
            $insert = $this->db->insert('plans', [
                'planId' => $planId,
                'name' => $name,
                'minimum' => $minimum,
                'maximum' => $maximum,
                'fixed_amount' => $fixed_amount,
                'interest' => $interest,
                'interest_status' => $interest_status,
                'times' => $times,
                'status' => $status,
                'featured' => $featured,
                'capital_back_status' => $capital_back_status,
                'lifetime_status' => $lifetime_status,
                'repeat_time' => $repeat_time,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]);

            return $insert->rowCount();  
        } catch (Exception $e) {
            // Handle exceptions, such as database errors
            error_log('Error in addPlan(): ' . $e->getMessage());
            return 0; // Return 0 to indicate failure
        }
    }

    /**
     * Updates a plan in the database.
     *
     * This method updates an existing plan in the 'plans' table.
     *
     * @param string $planId The ID of the plan to update.
     * @param string $name The name of the plan.
     * @param mixed $minimum The minimum investment amount.
     * @param mixed $maximum The maximum investment amount.
     * @param mixed $fixed_amount The fixed investment amount.
     * @param int $interest The interest rate.
     * @param mixed $interest_status The status of interest.
     * @param int $times The number of times.
     * @param int $status The status of the plan.
     * @param int $featured The featured status.
     * @param int $capital_back_status The status of capital back.
     * @param int $lifetime_status The lifetime status.
     * @param int $repeat_time The repeat time.
     * @return int The number of rows affected by the update.
     */
    public function updatePlan(string $planId, string $name, mixed $minimum, mixed $maximum, mixed $fixed_amount, mixed $interest, int $interest_status, int $times, int $status, int $featured, int $capital_back_status, int $lifetime_status, int $repeat_time): int
    {
        try {
            // Update plan
            $update = $this->db->update('plans', [
                'name' => $name,
                'minimum' => $minimum,
                'maximum' => $maximum,
                'fixed_amount' => $fixed_amount,
                'interest' => $interest,
                'interest_status' => $interest_status,
                'times' => $times,
                'status' => $status,
                'featured' => $featured,
                'capital_back_status' => $capital_back_status,
                'lifetime_status' => $lifetime_status,
                'repeat_time' => $repeat_time
            ], [
                'planId' => $planId
            ]);

            return $update->rowCount();  
        } catch (Exception $e) {
            // Handle exceptions, such as database errors
            error_log('Error in updatePlan(): ' . $e->getMessage());
            return 0; // Return 0 to indicate failure
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
     * Get all loan logs
     *
     * This method retrieves all loan logs
     *
     * @return array The user's loan logs.
     */
    public function getLoans(): ?array
    {
        return $this->db->select('loan', '*', [
            "ORDER" => ["created_at" => "DESC"]
        ]);
    }

    /**
     * Get all loan logs of a user
     *
     * This method retrieves all loan logs associated with a user.
     *
     * @param int $userid The ID of the user.
     * @return array The user's loan logs.
     */
    public function getUserLoansLog(int $userid): ?array
    {
        return $this->db->select('loan', '*', [
            "userid" => $userid,
            "ORDER" => ["created_at" => "DESC"]
        ]);
    }

    public function getLoanDetails(string $loanId): ?array
    {
        try {
            // Retrieve plan details from the "plans" table based on the plan ID
            $query = $this->db->get("loan", "*", ["loan_reference_id" => $loanId]); 

            // If $query is null or empty, return an empty array
            if (!$query) {
                return [];
            }

            return $query;
        } catch (Exception $e) {
            // Handle exceptions, such as database errors
            error_log('Error in getLoanDetails(): ' . $e->getMessage());
            return []; // Return null to indicate failure
        }
    }

    public function updateLoan(int $loanId, int $userid, string $userNewBalance): int {
        try {

            $this->db->update('user', [
                'interest_wallet' => $userNewBalance
            ], [
                'userid' => $userid
            ]);

            $update = $this->db->update('loan', [
                'loan_status' => 1,
            ], [
                'loan_reference_id' => $loanId
            ]);

            return $update->rowCount();
        } catch (Exception $e) {
            // Handle exceptions, such as database errors
            error_log('Error in updateLoan(): ' . $e->getMessage());
            return 0; // Return 0 to indicate failure
        }
    }

    public function rejectLoan(int $loanId): int {
        try {
            $update = $this->db->update('loan', [
                'loan_status' => 3,
            ], [
                'loan_reference_id' => $loanId
            ]);

            return $update->rowCount();
        } catch (Exception $e) {
            // Handle exceptions, such as database errors
            error_log('Error in updateLoan(): ' . $e->getMessage());
            return 0; // Return 0 to indicate failure
        }
    }

    /**
     * Generate a unique transaction ID
     *
     * This method generates a unique transaction ID, which can be used for identifying transactions.
     *
     * @return string The generated unique transaction ID.
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
     * Generate a unique ID
     *
     * This method generates a unique ID, which can be used for various purposes.
     *
     * @return string The generated unique ID.
     */
    private function uniqueid(): string
    {
        // Generate a unique ID based on current timestamp and a random number
        return substr(number_format(time() * rand(), 0, '', ''), 0, 12);
    }

    /**
     * Retrieves the details of a specific deposit method from the database.
     *
     * @param string $method_code The method code of the deposit method to retrieve details for
     * @return array|null The details of the deposit method, or null if not found
     */
    public function getDepositMethod(string $payment_method): ?array
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