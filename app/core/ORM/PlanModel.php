<?php

declare(strict_types=1);

namespace KenDeNigerian\Krak\core\ORM;

use KenDeNigerian\Krak\core\ORM\Model;

/**
 * Plan ORM Model
 */
class PlanModel extends Model
{
    /**
     * @var string
     */
    protected string $table = 'plans';

    /**
     * @var string
     */
    protected string $primaryKey = 'id';

    /**
     * @var array<string>
     */
    protected array $fillable = [
        'name',
        'description',
        'min_amount',
        'max_amount',
        'interest_rate'
    ];
}

