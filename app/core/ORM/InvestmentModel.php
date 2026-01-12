<?php

declare(strict_types=1);

namespace KenDeNigerian\Krak\core\ORM;

use Medoo\Medoo;
use KenDeNigerian\Krak\core\Cache\CacheInterface;
use KenDeNigerian\Krak\core\ORM\Model;
use KenDeNigerian\Krak\core\ORM\Relationship;

/**
 * Investment ORM Model
 */
class InvestmentModel extends Model
{
    /**
     * @var string
     */
    protected string $table = 'invests';

    /**
     * @var string
     */
    protected string $primaryKey = 'investId';

    /**
     * @var array<string>
     */
    protected array $fillable = [
        'userid',
        'planId',
        'amount',
        'status',
        'initiated_at'
    ];

    /**
     * Define user relationship
     *
     * @return Relationship
     */
    public function user(): Relationship
    {
        return $this->belongsTo(UserModel::class, 'userid', 'userid');
    }

    /**
     * Define plan relationship
     *
     * @return Relationship
     */
    public function plan(): Relationship
    {
        return $this->belongsTo(PlanModel::class, 'planId', 'id');
    }
}

