<?php

/**
 * Base class  - Base.php file
 *
 * @author     Dmitriy Tyurin <fobia3d@gmail.com>
 * @copyright  Copyright (c) 2014 Dmitriy Tyurin
 */

namespace Fac\Db;

use \PDO;
use \PDOStatement;

/**
 * Base class
 *
 * @package		Fac.db
 */
class Base {

    /** @var PDO */
    protected $db;

    function __construct(PDO $db) {
//        if (!($db instanceof PDO) && !($db instanceof \mysqli)) {
//            throw new RuntimeException('Install dependencies to run project (PEAR).');
//        }
        $this->db = $db;
    }

    /** @var array  */
    protected $_query = array();

    /**
     * Используемые таблицы
     * @var array
     */
    protected $_tables = array();

    /**
     * Команда
     * @var string
     */
    protected $_command;

    /**
     * @return string
     */
    public function __toString() {
        return $this->toString();
    }

    /**
     * @return string
     */
    public function toString() {
        @extract($this->_query, EXTR_SKIP);

        $sql = $this->_command;

        if (isset($distinct))
            $sql.="\nDISTINCT";

        if (isset($calc))
            $sql.="\nSQL_CALC_FOUND_ROWS";

        if (isset($ignore))
            $sql.="\nIGNORE";

        if (isset($command))
            $sql.="\n" . $command;


        if (isset($from))
            $sql.="\nFROM " . $from;

        if (isset($join))
            $sql.="\n" . (is_array($join) ? implode("\n", $join) : $join);

        if (isset($set))
            $sql.="\nSET " . $set;

        if (isset($where))
            $sql.="\nWHERE " . $where;

        if (isset($group))
            $sql.="\nGROUP BY " . $group;

        if (isset($having))
            $sql.="\nHAVING " . $having;

        if (isset($order))
            $sql.="\nORDER BY " . $order;

        if (isset($limit))
            $sql.="\nLIMIT " . $limit;

        if (isset($union))
            $sql.= "\nUNION (\n" . (is_array($union) ? implode("\n) UNION (\n", $union) : $union) . ')';

        return $sql;
    }

    /**
     * Выполнить запрос
     * @return PDOStatement
     */
    public function query() {
        $result = $this->db->query($this->toString());

        return $result;
    }

    /**
     * Экранирует значение
     * st'r => st\'r
     *
     * @param string $value
     */
    protected function quoteValue(&$value) {
        $value = $this->db->quote($value);
        return $value;
    }

    /**
     * Экранирует значение и вводит в ковычки
     *  st'r => 'st\'r'
     *
     * @param string $value
     */
    protected function quoteEscape(&$value) {
        $value = "'" . $this->quoteValue($value) . "'";
        return $value;
    }

    /**
     * Экранирует название столбцов
     *
     * @param string $name
     * @return string
     */
    protected function quoteTable(&$name) {
        $name = '`' . $name . '`';
        return $name;
    }

}
