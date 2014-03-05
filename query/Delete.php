<?php
/**
 * Delete class  - Delete.php file
 *
 * @author     Dmitriy Tyurin <fobia3d@gmail.com>
 * @copyright  Copyright (c) 2014 Dmitriy Tyurin
 */

namespace Fobia\Db;

use \PDO;

/**
 * Delete class
 *
 * @package		fobia.db
 */
class Delete extends Where
{

    protected $_command = 'DELETE';

    /**
     * Оператор DELETE удаляет из таблицы tables строки, удовлетворяющие заданным
     * в where условиям, и возвращает число удаленных записей.
     * @param string|array $tables
     *
     */
    public function __construct(PDO $db)
    {
        parent::__construct($db);
    }

    /**
     * @param string $table таблицы
     * @return self
     */
    public function from($table)
    {
        $this->_query['from'] = (string) $table;
        return $this;
    }
}