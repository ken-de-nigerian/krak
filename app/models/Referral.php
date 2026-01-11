<?php

namespace Fir\Models;

use Exception;

class Referral extends Model {

	/**
	 * Count Referrals
	 *
	 * @param string $userid The user ID for whom to count referrals.
	 * @return int The number of referred users in the "user" table.
	 */
	public function countReferrals(string $userid): int {
	    try {
	        // Count the number of referred users in the "user" table for the given user ID
	        return $this->db->count("user", "*", ["ref_by" => $userid]);
	    } catch (Exception $e) {
	        // Handle exceptions, such as database errors
	        error_log('Error in countReferrals(): ' . $e->getMessage());
	        return 0; // Return 0 if an error occurs
	    }
	}

	/**
	 * Calculate Total Investments
	 *
	 * @param string $userid The user ID for whom to calculate total investments.
	 * @return float The total sum of invested amount in the "invests" table for the given user.
	 */
	public function countInvestments(string $userid): float {
	    try {
	        // Calculate the total sum of invested amount in the "invests" table for the given user
	        $sum = $this->db->sum("invests", "amount", [
	        	"userid" => $userid,
	        	"status" => 1
	        ]);
	        return (float) $sum;
	    } catch (Exception $e) {
	        // Handle exceptions, such as database errors
	        error_log('Error in countInvestments(): ' . $e->getMessage());
	        return 0.0; // Return 0.0 if an error occurs
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
	 * Retrieve ranks from the database.
	 *
	 * This method retrieves the latest 10 ranks from the 'user_ranking' table.
	 *
	 * @return array|null An array containing ranks, or null if no ranks are found.
	 */
	public function getRanks(): ?array {
	    try {
	        // Retrieve the latest 10 ranks from the 'user_ranking' table
	        return $this->db->select('user_ranking', '*', [
	            "ORDER" => ["id" => "ASC"],
	            "LIMIT" => 10
	        ]);
	    } catch (Exception $e) {
	        // Handle exceptions, such as database errors
	        error_log('Error in getRanks(): ' . $e->getMessage());
	        return []; // Return null if an error occurs
	    }
	}

	/**
	 * Get ranks from the database with pagination.
	 *
	 * This method retrieves ranks with pagination from the 'user_ranking' table.
	 *
	 * @param int $page The page number for pagination.
	 * @return array Returns an array of ranks.
	 */
	public function getRankingWithPagination(int $page): array
	{
	    try {
	        $limit = 10; // Number of ranks per page
	        $offset = ($page - 1) * $limit; // Calculate the offset based on the page number

	        // Retrieve ranks with pagination from the 'user_ranking' table
	        return $this->db->select('user_ranking', '*', [
	            "ORDER" => ["id" => "ASC"],
	            "LIMIT" => [$offset, $limit]
	        ]);
	    } catch (Exception $e) {
	        // Handle exceptions, such as database errors
	        error_log('Error in getRankingWithPagination(): ' . $e->getMessage());
	        return []; // Return an empty array if an error occurs
	    }
	}

	/**
     * Check if a Rank Exists
     *
     * @param int $rankingId The rankingId of the rank to check for existence.
     * @return bool Returns true if the rank exists in the "rank" table, false otherwise.
     */
    public function hasRank(int $rankingId): bool {
    	try {
        	return $this->db->has("user_ranking", ["AND" =>["rankingId" => $rankingId]]);
        } catch (Exception $e) {
	        // Handle exceptions, such as database errors
	        error_log('Error in hasRank(): ' . $e->getMessage());
	        return false; // Return an empty array if an error occurs
	    }
    }

    /**
     * Get a rank by its rankingId.
     *
     * @param int $rankingId The ID of the rank to retrieve.
     * @return array|null An array containing the rank details.
     */
    public function getRankDetails(int $rankingId): ?array
    {
        try {
            $query = $this->db->get("user_ranking", "*", ["rankingId" => $rankingId]);
            // If $query is null or empty, return an empty array
            if (!$query) {
                return [];
            }

            return $query;
        } catch (Exception $e) {
            // Handle exceptions, such as database errors
            error_log('Error in getRankDetails(): ' . $e->getMessage());
            return []; // Return null to indicate failure
        }
    }

    /**
	 * Add New Rank Details
	 *
	 * This method adds the Rank details to the database.
	 *
	 * @param string $rankingId The ID of the rank entry.
	 * @param mixed $filename The filename of the Rank image.
	 * @param string $name The name for Rank.
	 * @param string $min_invest The min_invest for Rank.
	 * @param string $min_referral The min_referral for Rank.
	 * @param string $bonus The bonus for Rank.
	 * @param int $status The status for Rank.
	 * @return int The number of rows affected by the insert operation (usually one if successful).
	 * @throws Exception If the insert operation fails.
	 */
	public function addRank(string $rankingId, mixed $filename, string $name, string $min_invest, string $min_referral, string $bonus, int $status): int {
	    try {
	        // Insert Rank details into the 'user_ranking' table
	        $insert = $this->db->insert('user_ranking', [
	            'rankingId' => $rankingId,
	            'icon' => $filename,
	            'name' => $name,
	            'min_invest' => $min_invest,
	            'min_referral' => $min_referral,
	            'bonus' => $bonus,
	            'status' => $status,
	            'created_at' => date('Y-m-d H:i:s')
	        ]);

	        // Return the number of rows affected by the insert operation
	        return $insert->rowCount();
	    } catch (Exception $e) {
	        // Handle exceptions, such as database errors
	        error_log('Error in addRank(): ' . $e->getMessage());
	        return 0; // Return 0 to indicate failure
	    }
	}

	/**
	 * Update Rank Details
	 *
	 * This method updates the Rank details in the database.
	 *
	 * @param string $filename The filename of the Rank image.
	 * @param string $name The name for Rank.
	 * @param string $min_invest The min_invest for Rank.
	 * @param string $min_referral The min_referral for Rank.
	 * @param string $bonus The bonus for Rank.
	 * @param int $status The status for Rank.
	 * @return int The number of rows affected by the update operation (usually one if successful).
	 * @throws Exception If the update operation fails.
	 */
	public function editRank(string $rankingId, string $filename, string $name, string $min_invest, string $min_referral, string $bonus, int $status): int {
	    try {
	        // Update the 'user_ranking' table with the provided Rank details where rankingId matches
	        $update = $this->db->update('user_ranking', [
	            'icon' => $filename,
	            'name' => $name,
	            'min_invest' => $min_invest,
	            'min_referral' => $min_referral,
	            'bonus' => $bonus,
	            'status' => $status
	        ], [
	            'rankingId' => $rankingId
	        ]);

	        // Return the number of rows affected by the update operation
	        return $update->rowCount();
	    } catch (Exception $e) {
	        // Handle exceptions, such as database errors
	        error_log('Error in editRank(): ' . $e->getMessage());
	        return 0; // Return 0 to indicate failure
	    }
	}

	/**
	 * Update Rank Details With No Image
	 *
	 * This method updates the Rank details in the database.
	 *
	 * @param string $name The name for Rank.
	 * @param string $min_invest The min_invest for Rank.
	 * @param string $min_referral The min_referral for Rank.
	 * @param string $bonus The bonus for Rank.
	 * @param int $status The status for Rank.
	 * @return int The number of rows affected by the update operation (usually one if successful).
	 * @throws Exception If the update operation fails.
	 */
	public function editRankNoImage(string $rankingId, string $name, string $min_invest, string $min_referral, string $bonus, int $status): int {
	    try {
	        // Update the 'user_ranking' table with the provided Rank details where rankingId matches
	        $update = $this->db->update('user_ranking', [
	            'name' => $name,
	            'min_invest' => $min_invest,
	            'min_referral' => $min_referral,
	            'bonus' => $bonus,
	            'status' => $status
	        ], [
	            'rankingId' => $rankingId
	        ]);

	        // Return the number of rows affected by the update operation
	        return $update->rowCount();
	    } catch (Exception $e) {
	        // Handle exceptions, such as database errors
	        error_log('Error in editRankNoImage(): ' . $e->getMessage());
	        return 0; // Return 0 to indicate failure
	    }
	}

	/**
	 * Retrieve users from the database who were referred by a given user.
	 *
	 * @param string $userid The user ID for whom to retrieve referred users.
	 * @return array The list of referred users retrieved from the 'user' table.
	 */
	public function getReferredUsers(string $userid): array
	{
	    try {
	        // Retrieve users from the 'user' table, filtered by user ID and ordered by registration date in descending order
	        return $this->db->select("user", "*", [
	            "ref_by" => $userid,
	            "ORDER" => ["registration_date" => "DESC"]
	        ]);
	    } catch (Exception $e) {
	        // Handle exceptions, such as database errors
	        error_log('Error in getReferredUsers(): ' . $e->getMessage());
	        return []; // Return an empty array if an error occurs
	    }
	}
}