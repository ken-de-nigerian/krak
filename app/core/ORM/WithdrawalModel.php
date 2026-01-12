<?php

declare(strict_types=1);

namespace KenDeNigerian\Krak\core\ORM;

use KenDeNigerian\Krak\core\ORM\Model;

/**
 * Withdrawal ORM Model
 */
class WithdrawalModel extends Model
{
    /**
     * @var string
     */
    protected string $table = 'withdrawals';

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

