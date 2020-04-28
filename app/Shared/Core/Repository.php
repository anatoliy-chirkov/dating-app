<?php

namespace Shared\Core;

use Shared\Core\Db\SQLConnection;

abstract class Repository
{
    /** @var SQLConnection $connection */
    protected $connection;

    public function __construct()
    {
        $this->connection = App::get('sqlConnection');
    }
}
