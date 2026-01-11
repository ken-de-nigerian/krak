<?php

namespace Fir\Models;

use Exception;

class Settings extends Model
{
    /**
     * Gets the site `settings`
     *
     * @return array
     */
    public function get(): array
    {

        $settings = $this->db->get('settings', '*', ["id" => 1]);

        // If $settings is null or empty, return an empty array
        if (!$settings) {
            return [];
        }

        return $settings;
    }

    /**
     * Gets the livechat
     *
     * @return array
     */
    public function livechat(): array
    {
        $extensions = $this->db->get('extensions', '*', [
            "id" => 1,
            "status" => 1
        ]);

        // If $extensions is null or empty, return an empty array
        if (!$extensions) {
            return [];
        }

        return $extensions;
    }

    /**
     * Gets the whatsapp
     *
     * @return array
     */
    public function whatsapp(): array
    {
        $extensions = $this->db->get('extensions', '*', [
            "id" => 2,
            "status" => 1
        ]);

        // If $extensions is null or empty, return an empty array
        if (!$extensions) {
            return [];
        }

        return $extensions;
    }

    /**
     * Gets the site `withdraw_methods`
     *
     * @return array
     */
    public function getWithdraws(): array
    {

        // Fetch withdraw methods from the database
        return $this->db->select('withdraw_methods', '*',
            ["status" => 1],
            ["ORDER" => ["name" => "ASC"]]
        );
    }

    /**
     * Gets the site `withdraw_methods`
     *
     * @return array
     */
    public function getAllWithdrawMethods(): array
    {

        // Fetch withdraw methods from the database
        return $this->db->select('withdraw_methods', '*', []);
    }

    /**
     * Gets Currency
     *
     * @return string
     */
    public function getCurrency(): string
    {
        $settings = $this->db->get('settings', '*', ["id" => 1]);
        if (!$settings || empty($settings['currency'])) {
            return '';
        }
        
        $currency = $this->db->get('currency', '*', ["id" => $settings["currency"]]);
        return $currency ? ($currency["currency_symbol"] ?? '') : '';
    }

    /**
     * Gets Currency code
     *
     * @return string
     */
    public function currency_code(): string
    {
        $settings = $this->db->get('settings', '*', ["id" => 1]);
        if (!$settings || empty($settings['currency'])) {
            return '';
        }
        
        $currency = $this->db->get('currency', '*', ["id" => $settings["currency"]]);
        return $currency ? ($currency["currency_code"] ?? '') : '';
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
     * Get All Withdrawal Gateways
     *
     * Retrieves the latest five withdrawal gateways from the "withdraw_methods" table.
     *
     * @return array An array containing the latest five withdrawal gateways from the "withdraw_methods" table.
     */
    public function getWithdrawalGateways(): array {
        try {
            // Retrieve withdrawal gateways from the 'withdraw_methods' table
            return $this->db->select('withdraw_methods', '*', [
                "ORDER" => ["status" => "ASC"], // Order by status in ascending order to get the latest entries first
                "LIMIT" => 8 // Limit the result to five entries
            ]);
        } catch (Exception $e) {
            // Handle exceptions, such as database errors
            error_log('Error in getDepositGateways(): ' . $e->getMessage());
            return []; // Return an empty array if an error occurs
        }
    }

    /**
     * Get All Withdrawal Gateways With Pagination
     *
     * Retrieves withdrawal gateways with pagination from the "withdraw_methods" table.
     *
     * @param int $page The page number for pagination.
     * @return array An array containing withdrawal gateways for the specified page.
     */
    public function getWithdrawalGatewaysWithPagination(int $page): array {
        try {
            $limit = 8; // Number of withdrawal gateways per page
            $offset = ($page - 1) * $limit; // Calculate the offset based on the page number

            // Retrieve withdrawal gateways with pagination from the 'withdraw_methods' table
            return $this->db->select('withdraw_methods', '*', [
                "ORDER" => ["status" => "ASC"], // Order by status in ascending order to get the latest entries first
                "LIMIT" => [$offset, $limit] // Apply pagination limit and offset
            ]);
        } catch (Exception $e) {
            // Handle exceptions, such as database errors
            error_log('Error in getDepositGatewaysWithPagination(): ' . $e->getMessage());
            return []; // Return an empty array if an error occurs
        }
    }

    /**
     * Gets the site `gateway_currencies`
     *
     * @return array
     */
    public function getGateways(): array
    {

        try {
            // Retrieve wallet addresses from .env
            $wallets = json_decode(getenv('WALLET_ADDRESSES'), true);

            // Ensure it's an array
            if (!is_array($wallets)) {
                return [];
            }

            // Sort by status (assuming status exists, otherwise remove sorting)
            usort($wallets, fn($a, $b) => $a['status'] <=> $b['status']);

            // Return all wallet addresses without pagination
            return $wallets;
        } catch (Exception $e) {
            // Handle exceptions
            error_log('Error in getDepositGateways(): ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Get All Deposit Gateways
     *
     * Retrieves the latest eight deposit gateways from the "gateway_currencies" table.
     *
     * @return array An array containing the latest eight deposit gateways from the "gateway_currencies" table.
     */
    public function getGatewaysWithConversion(): array {
        try {
            // Retrieve wallet addresses from .env
            $wallets = json_decode(getenv('WALLET_ADDRESSES'), true);

            // Ensure it's an array
            if (!is_array($wallets)) {
                return [];
            }

            // Sort by status (assuming status exists, otherwise remove sorting)
            usort($wallets, function ($a, $b) {
                return $a['status'] <=> $b['status'];
            });

            // Return up to eight entries
            return array_slice($wallets, 0, 7);
        } catch (Exception $e) {
            // Handle exceptions
            error_log('Error in getDepositGateways(): ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Get All Deposit Gateways from .env
     *
     * Retrieves the latest eight deposit gateways from the WALLET_ADDRESSES environment variable.
     *
     * @return array An array containing up to eight deposit gateways.
     */
    public function getDepositGateways(): array {
        try {
            // Retrieve wallet addresses from .env
            $wallets = json_decode(getenv('WALLET_ADDRESSES'), true);

            // Ensure it's an array
            if (!is_array($wallets)) {
                return [];
            }

            // Sort by status (assuming status exists, otherwise remove sorting)
            usort($wallets, function ($a, $b) {
                return $a['status'] <=> $b['status'];
            });

            // Return up to eight entries
            return array_slice($wallets, 0, 8);
        } catch (Exception $e) {
            // Handle exceptions
            error_log('Error in getDepositGateways(): ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Get All Deposit Gateways With Pagination from .env
     *
     * Retrieves deposit gateways with pagination from the WALLET_ADDRESSES environment variable.
     *
     * @param int $page The page number for pagination.
     * @return array An array containing deposit gateways for the specified page.
     */
    public function getDepositGatewaysWithPagination(int $page): array {
        try {
            $limit = 8; // Number of deposit gateways per page
            $offset = ($page - 1) * $limit;

            // Retrieve wallet addresses from .env
            $wallets = json_decode(getenv('WALLET_ADDRESSES'), true);

            // Ensure it's an array
            if (!is_array($wallets)) {
                return [];
            }

            // Sort by status (assuming status exists)
            usort($wallets, function ($a, $b) {
                return $a['status'] <=> $b['status'];
            });

            // Apply pagination
            return array_slice($wallets, $offset, $limit);
        } catch (Exception $e) {
            // Handle exceptions
            error_log('Error in getDepositGatewaysWithPagination(): ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Gets the site `gateway_currencies`
     *
     * @return array
     */
    public function getAllDepositMethod(): array
    {

        try {
            // Retrieve wallet addresses from .env
            $wallets = json_decode(getenv('WALLET_ADDRESSES'), true);

            // Ensure it's an array
            if (!is_array($wallets)) {
                return [];
            }
            
            // Return all wallet addresses without pagination
            return $wallets;
        } catch (Exception $e) {
            // Handle exceptions
            error_log('Error in getDepositGateways(): ' . $e->getMessage());
            return [];
        }
    }
}

