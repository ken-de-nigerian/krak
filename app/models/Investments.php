<?php

namespace Fir\Models;

use Exception;

class Investments extends Model
{

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
     * Count Running Investments
     *
     * This method retrieves the count of Running Investments from the "invests" table in the database.
     *
     * @return int - Number of Running Investments in the "invests" table
     */
    public function CountRunningInvestments(): int
    {
        try {
            // Count the number of rows in the "invests" table
            return $this->db->count("invests", "*", [
            	"status" => 2
            ]);
        } catch (Exception $e) {
            // Handle exceptions, such as database errors
            error_log('Error in CountRunningInvestments(): ' . $e->getMessage());
            return 0; // Return 0 to indicate failure or absence of running investments
        }
    }

    /**
     * Retrieve investments from the database
     *
     * @return array The list of investments retrieved from the 'invests' table.
     */
    public function getInvestments(): array
    {
        try {
            // Retrieve investments from the 'invests' table, ordered by creation date in descending order
            return $this->db->select("invests", "*", [
                "ORDER" => ["initiated_at" => "DESC"],
                "LIMIT" => 10 // Limiting to 10 investments
            ]);
        } catch (Exception $e) {
            // Handle exceptions, such as database errors
            error_log('Error in getInvestments(): ' . $e->getMessage());
            return []; // Return an empty array if an error occurs
        }
    }

    /**
     * Retrieve investments from the database with pagination
     *
     * @param int $page The page number to retrieve investments from
     * @return array The list of investments retrieved from the 'invests' table.
     */
    public function getInvestmentsWithPagination(int $page): array
    {
        try {
            $limit = 10; // Number of investments per page
            $offset = ($page - 1) * $limit; // Calculate the offset based on the page number

            // Retrieve investments from the 'invests' table, ordered by creation date in descending order
            return $this->db->select("invests", "*", [
                "ORDER" => ["initiated_at" => "DESC"],
                "LIMIT" => [$offset, $limit] // Limiting to $limit investments starting from $offset
            ]);
        } catch (Exception $e) {
            // Handle exceptions, such as database errors
            error_log('Error in getInvestmentsWithPagination(): ' . $e->getMessage());
            return []; // Return an empty array if an error occurs
        }
    }

    /**
     * Retrieve running investments from the database
     *
     * @return array The list of investments retrieved from the 'invests' table.
     */
    public function getRunningInvestments(): array
    {
        try {
            // Retrieve investments from the 'invests' table, ordered by creation date in descending order
            return $this->db->select("invests", "*", [
            	"status" => 2,
                "ORDER" => ["initiated_at" => "DESC"],
                "LIMIT" => 10 // Limiting to 10 investments
            ]);
        } catch (Exception $e) {
            // Handle exceptions, such as database errors
            error_log('Error in getRunningInvestments(): ' . $e->getMessage());
            return []; // Return an empty array if an error occurs
        }
    }

    /**
     * Retrieve running investments from the database with pagination
     *
     * @param int $page The page number to retrieve investments from
     * @return array The list of investments retrieved from the 'invests' table.
     */
    public function getRunningInvestmentsWithPagination(int $page): array
    {
        try {
            $limit = 10; // Number of investments per page
            $offset = ($page - 1) * $limit; // Calculate the offset based on the page number

            // Retrieve investments from the 'invests' table, ordered by creation date in descending order
            return $this->db->select("invests", "*", [
            	"status" => 2,
                "ORDER" => ["initiated_at" => "DESC"],
                "LIMIT" => [$offset, $limit] // Limiting to $limit investments starting from $offset
            ]);
        } catch (Exception $e) {
            // Handle exceptions, such as database errors
            error_log('Error in getRunningInvestmentsWithPagination(): ' . $e->getMessage());
            return []; // Return an empty array if an error occurs
        }
    }

    /**
     * Retrieve completed investments from the database
     *
     * @return array The list of investments retrieved from the 'invests' table.
     */
    public function getCompletedInvestments(): array
    {
        try {
            // Retrieve investments from the 'invests' table, ordered by creation date in descending order
            return $this->db->select("invests", "*", [
            	"status" => 1,
                "ORDER" => ["initiated_at" => "DESC"],
                "LIMIT" => 10 // Limiting to 10 investments
            ]);
        } catch (Exception $e) {
            // Handle exceptions, such as database errors
            error_log('Error in getRunningInvestments(): ' . $e->getMessage());
            return []; // Return an empty array if an error occurs
        }
    }

    /**
     * Retrieve completed investments from the database with pagination
     *
     * @param int $page The page number to retrieve investments from
     * @return array The list of investments retrieved from the 'invests' table.
     */
    public function getCompletedInvestmentsWithPagination(int $page): array
    {
        try {
            $limit = 10; // Number of investments per page
            $offset = ($page - 1) * $limit; // Calculate the offset based on the page number

            // Retrieve investments from the 'invests' table, ordered by creation date in descending order
            return $this->db->select("invests", "*", [
            	"status" => 1,
                "ORDER" => ["initiated_at" => "DESC"],
                "LIMIT" => [$offset, $limit] // Limiting to $limit investments starting from $offset
            ]);
        } catch (Exception $e) {
            // Handle exceptions, such as database errors
            error_log('Error in getRunningInvestmentsWithPagination(): ' . $e->getMessage());
            return []; // Return an empty array if an error occurs
        }
    }

    /**
     * Retrieve Canceled investments from the database
     *
     * @return array The list of investments retrieved from the 'invests' table.
     */
    public function getCancelledInvestments(): array
    {
        try {
            // Retrieve investments from the 'invests' table, ordered by creation date in descending order
            return $this->db->select("invests", "*", [
            	"status" => 4,
                "ORDER" => ["initiated_at" => "DESC"],
                "LIMIT" => 10 // Limiting to 10 investments
            ]);
        } catch (Exception $e) {
            // Handle exceptions, such as database errors
            error_log('Error in getCancelledInvestments(): ' . $e->getMessage());
            return []; // Return an empty array if an error occurs
        }
    }

    /**
     * Retrieve Canceled investments from the database with pagination
     *
     * @param int $page The page number to retrieve investments from
     * @return array The list of investments retrieved from the 'invests' table.
     */
    public function getCancelledInvestmentsWithPagination(int $page): array
    {
        try {
            $limit = 10; // Number of investments per page
            $offset = ($page - 1) * $limit; // Calculate the offset based on the page number

            // Retrieve investments from the 'invests' table, ordered by creation date in descending order
            return $this->db->select("invests", "*", [
            	"status" => 4,
                "ORDER" => ["initiated_at" => "DESC"],
                "LIMIT" => [$offset, $limit] // Limiting to $limit investments starting from $offset
            ]);
        } catch (Exception $e) {
            // Handle exceptions, such as database errors
            error_log('Error in getCancelledInvestmentsWithPagination(): ' . $e->getMessage());
            return []; // Return an empty array if an error occurs
        }
    }

    /**
     * Retrieve Initiated investments from the database
     *
     * @return array The list of investments retrieved from the 'invests' table.
     */
    public function getInitiatedInvestments(): array
    {
        try {
            // Retrieve investments from the 'invests' table, ordered by creation date in descending order
            return $this->db->select("invests", "*", [
            	"status" => 3,
                "ORDER" => ["initiated_at" => "DESC"],
                "LIMIT" => 10 // Limiting to 10 investments
            ]);
        } catch (Exception $e) {
            // Handle exceptions, such as database errors
            error_log('Error in getInitiatedInvestments(): ' . $e->getMessage());
            return []; // Return an empty array if an error occurs
        }
    }

    /**
     * Retrieve Initiated investments from the database with pagination
     *
     * @param int $page The page number to retrieve investments from
     * @return array The list of investments retrieved from the 'invests' table.
     */
    public function getInitiatedInvestmentsWithPagination(int $page): array
    {
        try {
            $limit = 10; // Number of investments per page
            $offset = ($page - 1) * $limit; // Calculate the offset based on the page number

            // Retrieve investments from the 'invests' table, ordered by creation date in descending order
            return $this->db->select("invests", "*", [
            	"status" => 3,
                "ORDER" => ["initiated_at" => "DESC"],
                "LIMIT" => [$offset, $limit] // Limiting to $limit investments starting from $offset
            ]);
        } catch (Exception $e) {
            // Handle exceptions, such as database errors
            error_log('Error in getInitiatedInvestmentsWithPagination(): ' . $e->getMessage());
            return []; // Return an empty array if an error occurs
        }
    }

    /**
     * Cancel investment and Return Capital with Interest
     *
     * This method updates the user's interest wallet with the returned capital and interest,
     * and marks the investment as canceled.
     *
     * @param int $investId The ID of the investment being canceled.
     * @param string $userid The ID of the user associated with the investment.
     * @param float $capital The amount of capital to be returned.
     * @param float $interest The amount of interest to be returned.
     * @return int The number of rows affected by the update operation (usually one if successful).
     */
    public function returnCapitalWithInterest(int $investId, string $userid, float $capital, float $interest): int {
        try {
            // Retrieve user details from the 'user' table
            $user = $this->db->get('user', '*', ["userid" => $userid]);

            // Calculate new balance in the interest wallet
            $newBalance = $user['interest_wallet'] + $capital + $interest;

            // Update the 'interest_wallet' field of the user with the new balance
            $this->db->update('user', ['interest_wallet' => $newBalance], ['userid' => $userid]);

            // Mark the investment as canceled by updating its status
            $update = $this->db->update('invests', ['status' => 4], ['investId' => $investId]);

            // Return the number of rows affected by the update operation
            return $update->rowCount();
        } catch (Exception $e) {
            // Handle exceptions, such as database errors
            error_log('Error in returnCapitalWithInterest(): ' . $e->getMessage());
            return 0; // Return 0 to indicate failure
        }
    }

    /**
     * Cancel investment and Return Capital without Interest
     *
     * This method updates the user's interest wallet with the returned capital,
     * and marks the investment as canceled.
     *
     * @param int $investId The ID of the investment being canceled.
     * @param string $userid The ID of the user associated with the investment.
     * @param float $capital The amount of capital to be returned.
     * @return int The number of rows affected by the update operation (usually one if successful).
     */
    public function returnCapitalNoInterest(int $investId, string $userid, float $capital): int {
        try {
            // Retrieve user details from the 'user' table
            $user = $this->db->get('user', '*', ["userid" => $userid]);

            // Calculate new balance in the interest wallet
            $newBalance = $user['interest_wallet'] + $capital;

            // Update the 'interest_wallet' field of the user with the new balance
            $this->db->update('user', ['interest_wallet' => $newBalance], ['userid' => $userid]);

            // Mark the investment as canceled by updating its status
            $update = $this->db->update('invests', ['status' => 4], ['investId' => $investId]);

            // Return the number of rows affected by the update operation
            return $update->rowCount();
        } catch (Exception $e) {
            // Handle exceptions, such as database errors
            error_log('Error in returnCapitalWithInterest(): ' . $e->getMessage());
            return 0; // Return 0 to indicate failure
        }
    }

    /**
     * Cancel investment and Return Interest without Capital
     *
     * This method updates the user's interest wallet with the returned interest,
     * and marks the investment as canceled.
     *
     * @param int $investId The ID of the investment being canceled.
     * @param string $userid The ID of the user associated with the investment.
     * @param float $interest The amount of interest to be returned.
     * @return int The number of rows affected by the update operation (usually one if successful).
     */
    public function returnInterestNoCapital(int $investId, string $userid, float $interest): int {
        try {
            // Retrieve user details from the 'user' table
            $user = $this->db->get('user', '*', ["userid" => $userid]);

            // Calculate new balance in the interest wallet
            $newBalance = $user['interest_wallet'] + $interest;

            // Update the 'interest_wallet' field of the user with the new balance
            $this->db->update('user', ['interest_wallet' => $newBalance], ['userid' => $userid]);

            // Mark the investment as canceled by updating its status
            $update = $this->db->update('invests', ['status' => 4], ['investId' => $investId]);

            // Return the number of rows affected by the update operation
            return $update->rowCount();
        } catch (Exception $e) {
            // Handle exceptions, such as database errors
            error_log('Error in returnCapitalWithInterest(): ' . $e->getMessage());
            return 0; // Return 0 to indicate failure
        }
    }

    /**
     * Cancel investment and Return Nothing
     *
     * @param int $investId The ID of the investment being canceled.
     * @return int The number of rows affected by the update operation (usually one if successful).
     */
    public function returnNone(int $investId): int {
        try {
            // Mark the investment as canceled by updating its status
            $update = $this->db->update('invests', ['status' => 4], ['investId' => $investId]);

            // Return the number of rows affected by the update operation
            return $update->rowCount();
        } catch (Exception $e) {
            // Handle exceptions, such as database errors
            error_log('Error in returnCapitalWithInterest(): ' . $e->getMessage());
            return 0; // Return 0 to indicate failure
        }
    }
}