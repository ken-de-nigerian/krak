<?php

namespace Fir\Models;

use Exception;

class Deposit extends Model {
    
    /**
     * Get the deposit details for the specified deposit method code.
     *
     * @param string $payment_method The method code of the deposit method
     * @return array|null The details of the deposit method, or null if not found
     */
    public function getMethod(string $payment_method): ?array
    {
        try {
            // Retrieve deposit details from the "gateway_currencies" table based on the method code
            $row = $this->db->get("gateway_currencies", "*", ["method_code" => $payment_method]);

            // Return the payment method details or null if not found
            return $row ?: null;
        } catch (Exception $e) {
            // Handle exceptions, such as database errors
            error_log('Error in getMethod(): ' . $e->getMessage());
            return null; // Return null if an error occurs
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
     * Retrieves the details of a specific deposit from the database.
     *
     * @param string $depositId The ID of the deposit to retrieve details for
     * @return array|null The details of the deposit, or null if not found
     */
    public function depositDetails(string $depositId): ?array
    {
        try {
            // Retrieve deposit details from the "plans" table based on the deposit ID
            $query = $this->db->get("deposits", "*", ["depositId" => $depositId]); 

            // If $query is null or empty, return an empty array
            if (!$query) {
                return [];
            }

            return $query;
        } catch (Exception $e) {
            // Handle exceptions, such as database errors
            error_log('Error in depositDetails(): ' . $e->getMessage());
            return []; // Return null to indicate failure
        }
    }

	/**
     * Retrieve deposits from the database for a given user.
     *
     * @param string $userid The user ID for whom to retrieve deposits.
     * @return array The list of deposits retrieved from the 'deposits' table.
     */
    public function getDeposits(string $userid): array
    {
        try {
            // Retrieve deposits from the 'deposits' table, filtered by user ID and ordered by creation date in descending order
            return $this->db->select("deposits", "*", [
                "userid" => $userid,
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
     * This method retrieves deposits for a specified user with pagination from the 'deposits' table.
     *
     * @param int $userid The ID of the user.
     * @param int $page The page number for pagination.
     * @return array Returns an array of deposits records.
     */
    public function deposits_limits(int $userid, int $page): array
    {
        try {
            $limit = 5; // Number of deposits per page
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
            error_log('Error in deposits_limits(): ' . $e->getMessage());
            return []; // Return an empty array if an error occurs
        }
    }

    /**
     * Retrieve deposits from the database for a given user.
     *
     * @param string $userid The user ID for whom to retrieve deposits.
     * @return array The list of deposits retrieved from the 'deposits' table.
     */
    public function getDepositsCompleted(string $userid): array
    {
        try {
            // Retrieve deposits from the 'deposits' table, filtered by user ID and where status is pending, ordered by creation date in descending order
            return $this->db->select("deposits", "*", [
                "userid" => $userid,
                "method_code[!]" => "", 
                'status' => 1,
                "ORDER" => ["created_at" => "DESC"],
                "LIMIT" => 5
            ]);
        } catch (Exception $e) {
            // Handle exceptions, such as database errors
            error_log('Error in getDepositsCompleted(): ' . $e->getMessage());
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
    public function completed_deposits_limits(int $userid, int $page): array
    {
        try {
            $limit = 5; // Number of deposits per page
            $offset = ($page - 1) * $limit; // Calculate the offset based on the page number

            // Retrieve deposits for the specified user with pagination from the 'deposits' table
            return $this->db->select('deposits', '*', [
                "userid" => $userid,
                "method_code[!]" => "", 
                'status' => 1,
                "ORDER" => ["created_at" => "DESC"],
                "LIMIT" => [$offset, $limit]
            ]);
        } catch (Exception $e) {
            // Handle exceptions, such as database errors
            error_log('Error in completed_deposits_limits(): ' . $e->getMessage());
            return []; // Return an empty array if an error occurs
        }
    }

    /**
     * Retrieve deposits from the database for a given user.
     *
     * @param string $userid The user ID for whom to retrieve deposits.
     * @return array The list of deposits retrieved from the 'deposits' table.
     */
    public function getDepositsPending(string $userid): array
    {
        try {
            // Retrieve deposits from the 'deposits' table, filtered by user ID and where status is pending, ordered by creation date in descending order
            return $this->db->select("deposits", "*", [
                "userid" => $userid,
                "method_code[!]" => "", 
                'status' => 2,
                "ORDER" => ["created_at" => "DESC"],
                "LIMIT" => 5
            ]);
        } catch (Exception $e) {
            // Handle exceptions, such as database errors
            error_log('Error in getDepositsPending(): ' . $e->getMessage());
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
    public function pending_deposits_limits(int $userid, int $page): array
    {
        try {
            $limit = 5; // Number of deposits per page
            $offset = ($page - 1) * $limit; // Calculate the offset based on the page number

            // Retrieve deposits for the specified user with pagination from the 'deposits' table
            return $this->db->select('deposits', '*', [
                "userid" => $userid,
                "method_code[!]" => "", 
                'status' => 2,
                "ORDER" => ["created_at" => "DESC"],
                "LIMIT" => [$offset, $limit]
            ]);
        } catch (Exception $e) {
            // Handle exceptions, such as database errors
            error_log('Error in pending_deposits_limits(): ' . $e->getMessage());
            return []; // Return an empty array if an error occurs
        }
    }

    /**
     * Retrieve deposits from the database for a given user.
     *
     * @param string $userid The user ID for whom to retrieve deposits.
     * @return array The list of deposits retrieved from the 'deposits' table.
     */
    public function getDepositsInitiated(string $userid): array
    {
        try {
            // Retrieve deposits from the 'deposits' table, filtered by user ID and where status is pending, ordered by creation date in descending order
            return $this->db->select("deposits", "*", [
                "userid" => $userid,
                "method_code[!]" => "", 
                'status' => 0,
                "ORDER" => ["created_at" => "DESC"],
                "LIMIT" => 5
            ]);
        } catch (Exception $e) {
            // Handle exceptions, such as database errors
            error_log('Error in getDepositsInitiated(): ' . $e->getMessage());
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
    public function initiated_deposits_limits(int $userid, int $page): array
    {
        try {
            $limit = 5; // Number of deposits per page
            $offset = ($page - 1) * $limit; // Calculate the offset based on the page number

            // Retrieve deposits for the specified user with pagination from the 'deposits' table
            return $this->db->select('deposits', '*', [
                "userid" => $userid,
                "method_code[!]" => "", 
                'status' => 0,
                "ORDER" => ["created_at" => "DESC"],
                "LIMIT" => [$offset, $limit]
            ]);
        } catch (Exception $e) {
            // Handle exceptions, such as database errors
            error_log('Error in initiated_deposits_limits(): ' . $e->getMessage());
            return []; // Return an empty array if an error occurs
        }
    }

    /**
     * Retrieve deposits from the database for a given user.
     *
     * @param string $userid The user ID for whom to retrieve deposits.
     * @return array The list of deposits retrieved from the 'deposits' table.
     */
    public function getDepositsCancelled(string $userid): array
    {
        try {
            // Retrieve deposits from the 'deposits' table, filtered by user ID and where status is pending, ordered by creation date in descending order
            return $this->db->select("deposits", "*", [
                "userid" => $userid,
                "method_code[!]" => "", 
                'status' => 3,
                "ORDER" => ["created_at" => "DESC"],
                "LIMIT" => 5
            ]);
        } catch (Exception $e) {
            // Handle exceptions, such as database errors
            error_log('Error in getDepositsCancelled(): ' . $e->getMessage());
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
    public function cancelled_deposits_limits(int $userid, int $page): array
    {
        try {
            $limit = 5; // Number of deposits per page
            $offset = ($page - 1) * $limit; // Calculate the offset based on the page number

            // Retrieve deposits for the specified user with pagination from the 'deposits' table
            return $this->db->select('deposits', '*', [
                "userid" => $userid,
                "method_code[!]" => "", 
                'status' => 3,
                "ORDER" => ["created_at" => "DESC"],
                "LIMIT" => [$offset, $limit]
            ]);
        } catch (Exception $e) {
            // Handle exceptions, such as database errors
            error_log('Error in cancelled_deposits_limits(): ' . $e->getMessage());
            return []; // Return an empty array if an error occurs
        }
    }
}