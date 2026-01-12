<?php

declare(strict_types=1);

namespace KenDeNigerian\Krak\core\ORM;

use KenDeNigerian\Krak\core\ORM\Model;

/**
 * Settings ORM Model
 */
class SettingsModel extends Model
{
    /**
     * @var string
     */
    protected string $table = 'settings';

    /**
     * @var string
     */
    protected string $primaryKey = 'id';
}

