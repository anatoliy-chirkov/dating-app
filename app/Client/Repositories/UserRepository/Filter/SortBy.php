<?php

namespace Client\Repositories\Helpers;

class SortBy implements ISql
{
    private const NAME_TO_SQL = [
        'id'        => 'id ASC',
        '-id'       => 'id DESC',
        'username'  => 'username ASC',
        '-username' => 'username DESC',
        'email'     => 'email ASC',
        '-email'    => 'email DESC',
        'text'      => 'text ASC',
        '-text'     => 'text DESC',
        'status'    => 'isCompleted ASC',
        '-status'   => 'isCompleted DESC',
    ];

    private $name;

    public function __construct(string $name)
    {
        $this->name = $name;
    }

    public function getSql()
    {
        $expr = array_key_exists($this->name, self::NAME_TO_SQL)
            ? self::NAME_TO_SQL[$this->name]
            : 'id ASC'
        ;

        return 'ORDER BY' . ' ' . $expr;
    }
}
