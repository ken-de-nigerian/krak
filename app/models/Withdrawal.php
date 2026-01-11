<?php

namespace Fir\Models;

use Exception;

class Withdrawal extends Model {

    /**
     * Check if withdrawals with the specified ID exist in the database.
     *
     * @param mixed $withdrawId The ID of the withdrawals to check.
     * @return bool True if withdrawals with the given ID exist, false otherwise.
     */
    public function hasWithdrawal(mixed $withdrawId): bool
    {
        try {
            // Check if withdrawals with the specified ID exist in the 'withdrawals' table
            return $this->db->has("withdrawals", ["withdrawId" => $withdrawId]);
        } catch (Exception $e) {
            // Handle exceptions, such as database errors
            error_log('Error in hasWithdrawal(): ' . $e->getMessage());
            return false; // Return false if an error occurs
        }
    }

    /**
     * Retrieves the details of a specific withdrawals from the database.
     *
     * @param string $withdrawId The ID of the withdrawals to retrieve details for
     * @return array|null The details of the withdrawals, or null if not found
     */
    public function withdrawalDetails(string $withdrawId): ?array
    {
        try {
            // Retrieve withdrawals details from the "plans" table based on the withdrawal ID
            $query = $this->db->get("withdrawals", "*", ["withdrawId" => $withdrawId]); 

            // If $query is null or empty, return an empty array
            if (!$query) {
                return [];
            }

            return $query;
        } catch (Exception $e) {
            // Handle exceptions, such as database errors
            error_log('Error in withdrawalDetails(): ' . $e->getMessage());
            return []; // Return null to indicate failure
        }
    }

	/**
     * Retrieve withdrawals from the database for a given user.
     *
     * @param string $userid The user ID for whom to retrieve withdrawals.
     * @return array The list of withdrawals retrieved from the 'withdrawals' table.
     */
    public function getWithdrawals(string $userid): array
    {
        try {
            // Retrieve withdrawals from the 'withdrawals' table, filtered by user ID and ordered by creation date in descending order
            return $this->db->select("withdrawals", "*", [
                "userid" => $userid, 
                "ORDER" => ["created_at" => "DESC"], 
                "LIMIT" => 5 
            ]);
        } catch (Exception $e) {
            // Handle exceptions, such as database errors
            error_log('Error in getWithdrawals(): ' . $e->getMessage());
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
    public function withdrawals_limits(int $userid, int $page): array
    {
        try {
            $limit = 5; // Number of withdrawals per page
            $offset = ($page - 1) * $limit; // Calculate the offset based on the page number

            // Retrieve withdrawals for the specified user with pagination from the 'withdrawals' table
            return $this->db->select('withdrawals', '*', [
                "userid" => $userid,
                "ORDER" => ["created_at" => "DESC"],
                "LIMIT" => [$offset, $limit]
            ]);
        } catch (Exception $e) {
            // Handle exceptions, such as database errors
            error_log('Error in withdrawals_limits(): ' . $e->getMessage());
            return []; // Return an empty array if an error occurs
        }
    }

    /**
     * Retrieve withdrawals from the database for a given user.
     *
     * @param string $userid The user ID for whom to retrieve withdrawals.
     * @return array The list of withdrawals retrieved from the 'withdrawals' table.
     */
    public function getWithdrawalsCompleted(string $userid): array
    {
        try {
            // Retrieve withdrawals from the 'withdrawals' table, filtered by user ID and where status is pending, ordered by creation date in descending order
            return $this->db->select("withdrawals", "*", [
                "userid" => $userid,
                'status' => 1,
                "ORDER" => ["created_at" => "DESC"],
                "LIMIT" => 5
            ]);
        } catch (Exception $e) {
            // Handle exceptions, such as database errors
            error_log('Error in getWithdrawalsCompleted(): ' . $e->getMessage());
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
    public function completed_withdrawals_limits(int $userid, int $page): array
    {
        try {
            $limit = 5; // Number of withdrawals per page
            $offset = ($page - 1) * $limit; // Calculate the offset based on the page number

            // Retrieve withdrawals for the specified user with pagination from the 'withdrawals' table
            return $this->db->select('withdrawals', '*', [
                "userid" => $userid,
                'status' => 1,
                "ORDER" => ["created_at" => "DESC"],
                "LIMIT" => [$offset, $limit]
            ]);
        } catch (Exception $e) {
            // Handle exceptions, such as database errors
            error_log('Error in completed_withdrawals_limits(): ' . $e->getMessage());
            return []; // Return an empty array if an error occurs
        }
    }

    /**
     * Retrieve withdrawals from the database for a given user.
     *
     * @param string $userid The user ID for whom to retrieve withdrawals.
     * @return array The list of withdrawals retrieved from the 'withdrawals' table.
     */
    public function getWithdrawalsPending(string $userid): array
    {
        try {
            // Retrieve withdrawals from the 'withdrawals' table, filtered by user ID and where status is pending, ordered by creation date in descending order
            return $this->db->select("withdrawals", "*", [
                "userid" => $userid,
                'status' => 2,
                "ORDER" => ["created_at" => "DESC"],
                "LIMIT" => 5
            ]);
        } catch (Exception $e) {
            // Handle exceptions, such as database errors
            error_log('Error in getWithdrawalsPending(): ' . $e->getMessage());
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
    public function pending_withdrawals_limits(int $userid, int $page): array
    {
        try {
            $limit = 5; // Number of withdrawals per page
            $offset = ($page - 1) * $limit; // Calculate the offset based on the page number

            // Retrieve withdrawals for the specified user with pagination from the 'withdrawals' table
            return $this->db->select('withdrawals', '*', [
                "userid" => $userid,
                'status' => 2,
                "ORDER" => ["created_at" => "DESC"],
                "LIMIT" => [$offset, $limit]
            ]);
        } catch (Exception $e) {
            // Handle exceptions, such as database errors
            error_log('Error in pending_withdrawals_limits(): ' . $e->getMessage());
            return []; // Return an empty array if an error occurs
        }
    }

    /**
     * Retrieve withdrawals from the database for a given user.
     *
     * @param string $userid The user ID for whom to retrieve withdrawals.
     * @return array The list of withdrawals retrieved from the 'withdrawals' table.
     */
    public function getWithdrawalsInitiated(string $userid): array
    {
        try {
            // Retrieve withdrawals from the 'withdrawals' table, filtered by user ID and where status is pending, ordered by creation date in descending order
            return $this->db->select("withdrawals", "*", [
                "userid" => $userid,
                'status' => 0,
                "ORDER" => ["created_at" => "DESC"],
                "LIMIT" => 5
            ]);
        } catch (Exception $e) {
            // Handle exceptions, such as database errors
            error_log('Error in getWithdrawalsInitiated(): ' . $e->getMessage());
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
    public function initiated_withdrawals_limits(int $userid, int $page): array
    {
        try {
            $limit = 5; // Number of withdrawals per page
            $offset = ($page - 1) * $limit; // Calculate the offset based on the page number

            // Retrieve withdrawals for the specified user with pagination from the 'withdrawals' table
            return $this->db->select('withdrawals', '*', [
                "userid" => $userid,
                'status' => 0,
                "ORDER" => ["created_at" => "DESC"],
                "LIMIT" => [$offset, $limit]
            ]);
        } catch (Exception $e) {
            // Handle exceptions, such as database errors
            error_log('Error in initiated_withdrawals_limits(): ' . $e->getMessage());
            return []; // Return an empty array if an error occurs
        }
    }

    /**
     * Retrieve withdrawals from the database for a given user.
     *
     * @param string $userid The user ID for whom to retrieve withdrawals.
     * @return array The list of withdrawals retrieved from the 'withdrawals' table.
     */
    public function getWithdrawalsCancelled(string $userid): array
    {
        try {
            // Retrieve withdrawals from the 'withdrawals' table, filtered by user ID and where status is pending, ordered by creation date in descending order
            return $this->db->select("withdrawals", "*", [
                "userid" => $userid,
                'status' => 3,
                "ORDER" => ["created_at" => "DESC"],
                "LIMIT" => 5
            ]);
        } catch (Exception $e) {
            // Handle exceptions, such as database errors
            error_log('Error in getWithdrawalsCancelled(): ' . $e->getMessage());
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
    public function cancelled_withdrawals_limits(int $userid, int $page): array
    {
        try {
            $limit = 5; // Number of withdrawals per page
            $offset = ($page - 1) * $limit; // Calculate the offset based on the page number

            // Retrieve withdrawals for the specified user with pagination from the 'withdrawals' table
            return $this->db->select('withdrawals', '*', [
                "userid" => $userid,
                'status' => 3,
                "ORDER" => ["created_at" => "DESC"],
                "LIMIT" => [$offset, $limit]
            ]);
        } catch (Exception $e) {
            // Handle exceptions, such as database errors
            error_log('Error in cancelled_withdrawals_limits(): ' . $e->getMessage());
            return []; // Return an empty array if an error occurs
        }
    }
}