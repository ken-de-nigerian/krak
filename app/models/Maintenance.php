<?php

declare(strict_types=1);

namespace KenDeNigerian\Krak\models;

use KenDeNigerian\Krak\core\Model;

class Maintenance extends Model
{
    /**
     * Gets the maintenance mode
     *
     * @return array
     */
    public function underMaintenance(): array
    {

        $maintenance = $this->db->get('maintenance', '*', ["id" => 1]);

        // If $maintenance is null or empty, return an empty array
        if (!$maintenance) {
            return [];
        }

        return $maintenance;
    }
}