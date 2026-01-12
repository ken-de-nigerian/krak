<?php

declare(strict_types=1);

namespace KenDeNigerian\Krak\core\ORM;

use Medoo\Medoo;
use KenDeNigerian\Krak\core\Cache\CacheInterface;
use KenDeNigerian\Krak\core\ORM\Model;
use KenDeNigerian\Krak\core\ORM\Relationship;

/**
 * User ORM Model
 * Example implementation showing relationships and N+1 prevention
 */
class UserModel extends Model
{
    /**
     * @var string
     */
    protected string $table = 'user';

    /**
     * @var string
     */
    protected string $primaryKey = 'userid';

    /**
     * @var array<string>
     */
    protected array $fillable = [
        'username',
        'email',
        'password',
        'firstname',
        'lastname',
        'status'
    ];

    /**
     * Define investments relationship
     *
     * @return Relationship
     */
    public function investments(): Relationship
    {
        return $this->hasMany(InvestmentModel::class, 'userid', 'userid');
    }

    /**
     * Define deposits relationship
     *
     * @return Relationship
     */
    public function deposits(): Relationship
    {
        return $this->hasMany(DepositModel::class, 'userid', 'userid');
    }

    /**
     * Define withdrawals relationship
     *
     * @return Relationship
     */
    public function withdrawals(): Relationship
    {
        return $this->hasMany(WithdrawalModel::class, 'userid', 'userid');
    }
}

