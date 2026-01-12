<?php

declare(strict_types=1);

namespace KenDeNigerian\Krak\core\ORM;

use KenDeNigerian\Krak\core\ORM\Model;

/**
 * Deposit ORM Model
 */
class DepositModel extends Model
{
    /**
     * @var string
     */
    protected string $table = 'deposits';

    /**
     * @var string
     */
    protected string $primaryKey = 'id';

    /**
     * @var array<string>
     */
    protected array $fillable = [
        'userid',
        'amount',
        'status',
        'gateway'
    ];
}

