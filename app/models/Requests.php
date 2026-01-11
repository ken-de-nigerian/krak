<?php

namespace Fir\Models;

use Exception;

class Requests extends Model
{
    /**
     * Delete User Account
     * @param int $delete User ID to delete
     * @return int Total number of rows affected across all tables
     */
    public function deleteAccount(int $delete): int
    {
        try {
            // Store the user ID before deletion
            $userid = $delete;

            // Delete the user entry
            $delete = $this->db->delete('user', ["userid" => $delete]);

            // Delete corresponding entries from other tables
            $this->db->delete('address_proof', ["userid" => $userid]);
            $this->db->delete('commission_logs', ["from_id" => $userid]);
            $this->db->delete('deposits', ["userid" => $userid]);
            $this->db->delete('identity_proof', ["userid" => $userid]);
            $this->db->delete('invests', ["userid" => $userid]);
            $this->db->delete('password_resets', ["userid" => $userid]);
            // Continue deleting entries from other related tables...

            return $delete->rowCount();
        } catch (Exception $e) {
            // Handle exceptions, such as database errors
            error_log('Error in deleteAccount(): ' . $e->getMessage());
            return 0; // Return 0 to indicate failure or absence of banned users
        }
    }

    /**
     * Delete Deposit Record
     * @param string $depositId Deposit ID to delete
     * @param string $userid User ID to which the deposit belongs
     * @return int Total number of rows affected across all tables
     */
    public function deleteUserDeposit(string $depositId, string $userid): int
    {
        try {
            // Delete the deposit entry from the deposit table
            $delete = $this->db->delete('deposits', [
                "depositId" => $depositId,
                "userid" => $userid
            ]);

            return $delete->rowCount();
        } catch (Exception $e) {
            // Handle exceptions, such as database errors
            error_log('Error in deleteUserDeposit(): ' . $e->getMessage());
            return 0; // Return 0 to indicate failure or absence of banned users
        }
    }

    /**
     * Delete Withdrawal Record
     * @param string $withdrawId Withdrawal ID to delete
     * @param string $userid User ID to which the withdrawal belongs
     * @return int Total number of rows affected across all tables
     */
    public function deleteUserWithdrawal(string $withdrawId, string $userid): int
    {
        try {
            // Delete the withdrawal entry from the withdrawal table
            $delete = $this->db->delete('withdrawals', [
                "withdrawId" => $withdrawId,
                "userid" => $userid
            ]);

            return $delete->rowCount();
        } catch (Exception $e) {
            // Handle exceptions, such as database errors
            error_log('Error in deleteUserWithdrawal(): ' . $e->getMessage());
            return 0; // Return 0 to indicate failure or absence of banned users
        }
    }

    /**
     * Delete Investment Record
     * @param string $investId Investment ID to delete
     * @param string $userid User ID to which the investment belongs
     * @return int Total number of rows affected across all tables
     */
    public function deleteUserInvestment(string $investId, string $userid): int
    {
        try {
            // Delete the investment entry from the investment table
            $delete = $this->db->delete('invests', [
                "investId" => $investId,
                "userid" => $userid
            ]);

            return $delete->rowCount();
        } catch (Exception $e) {
            // Handle exceptions, such as database errors
            error_log('Error in deleteUserInvestment(): ' . $e->getMessage());
            return 0; // Return 0 to indicate failure or absence of banned users
        }
    }
}
