<?php
/**
 * Insert class  - Insert.php file
 *
 * @author     Dmitriy Tyurin <fobia3d@gmail.com>
 * @copyright  Copyright (c) 2014 Dmitriy Tyurin
 */

namespace Fac\Db;

use \PDO;
use \PDOStatement;

/**
 * Insert class
 *
 * @package		Fac.db
 */
class Insert extends Base
{

    protected $_command = 'INSERT';
    private $_values    = array();
    private $_rows      = array();
    private $_filter    = null;

    public function __construct(PDO $db)
    {
        parent::__construct($db);
    }

    public function toString()
    {
        $query = $this->_command . " "
                . $this->_query["ignore"]
                . $this->_query["into"];
        // -------

        $values = array();
        if (count($this->_rows)) {
            $values = $this->_rows;
        }
        if (count($this->_values)) {
            array_unshift($values, $this->_values);
        }

        $colums = array();
        if ($this->_filter) {
            $colums = $this->_filter;
        } else {
            $colums = array_keys($values[0]);
        }
        $query .= " (" . implode(", ", $colums) . ") VALUES ";

        $vals = array();
        foreach ($values as $row) {
            $i = each($row);
            reset($row);
            if (is_numeric($i['key'])) {
                $row    = array_slice($row, 0, $count);
                $vals[] = '(' . implode(',', $row) . ')';
            } else {
                $_row = array();
                foreach ($colums as $k) {
                    $_row[] = $row[$k];
                }
                $vals[] = '(' . implode(',', $_row) . ')';
            }
        }

        $query .= implode(',', $vals);

        return $query;
    }

    /**
     * Если указывается ключевое слово IGNORE, то команда обновления не будет
     * прервана, даже если при обновлении возникнет ошибка дублирования ключей.
     * Строки, из-за которых возникают конфликтные ситуации, обновлены не будут.
     *
     * @return self
     */
    public function ignore()
    {
        $this->_query['ignore'] = ' IGNORE ';
        return $this;
    }

    /**
     * Таблица
     * @param string $table
     * @return self
     */
    public function into($table)
    {
        $this->_query["into"] = "INTO " . $table . " ";
        return $this;
    }

    /**
     * Вставляет строки в соответствии с точно указанными в команде значениями.
     * При передачи параметра масивом, формат <ключ:значение>.
     * Значения не экранируються!
     *
     * Входной масив данных:
     * $array = (
     *   "column_1" => "param_1",
     *   "column_2" => "param_2"
     * )
     *
     * @param array   $values   масив
     * @param boolean $escape   нужно ли экранировать параметры (только при передачи масива)
     * @return self
     */
    public function values(array $values, $escape = true)
    {
        if ($escape) {
            array_walk($values, array($this, 'quoteEscape'));
        }
        $this->_values = array_merge($this->_values, $values);
        return $this;
    }

    /**
     * Ограничить вставляемые поля. Применяет к запросу только те поля,
     * что были здесь установлены.
     *
     * @param array $columns
     * @return self
     */
    public function filterColumn(array $columns)
    {
        $this->_filter = $columns;
        return $this;
    }

    /**
     * Добавить вставляемое поле
     *
     * @param string $column
     * @param string $value
     * @param bool $quoteEscape
     * @return self
     */
    public function addValue($column, $value, $quoteEscape = true)
    {
        if ($quoteEscape) {
            $value = $this->quoteEscape($value);
        }

        $this->_values[$column] = $value;
        return $this;
    }

    /**
     * Добавление еще одной строки записи.
     * Лучше добовлять после того, как сформированы поля вставки.
     *
     * Если список, то устанавливаються по порядку.
     * Если элементов больше че в первой строке, то они отбрасываються
     * -- VALUES ($v[0], $v[1], ...)
     *
     * Если асоциативный, то ключи попытаються подставиться.
     * Не существующии поля отбрасуються
     *
     * @param array $values
     */
    public function addRow($values, $escape = true)
    {
        if ($escape) {
            array_walk($values, array($this, 'quoteEscape'));
        }
        $this->_rows[] = $values;

        return $this;
    }
}