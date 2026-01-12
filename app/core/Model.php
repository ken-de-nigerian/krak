<?php

declare(strict_types=1);

namespace KenDeNigerian\Krak\core;

use Medoo\Medoo;

/**
 * The base Model upon which all the other models are extended on
 */
class Model
{
    /**
     * The database connection
     * @var Medoo
     */
    protected Medoo $db;

    /**
     * Constructor method.
     *
     * @param Medoo $db The Medoo database connection.
     */
    function __construct(Medoo $db) {
        $this->db = $db;
    }
}
