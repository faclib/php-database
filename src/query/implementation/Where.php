<?php

/**
 * Where class  - Where.php file
 *
 * @author     Dmitriy Tyurin <fobia3d@gmail.com>
 * @copyright  Copyright (c) 2014 Dmitriy Tyurin
 */

namespace Fac\Db;

use \PDO;

/**
 * Where class
 *
 * @package		Fac.db
 */
class Where extends Base {

    /**
     * Сортировка
     *
     * @param array|string $cols сортировать поля
     * @param bool $list преобразовать в список
     * @return self
     */
    public function order($cols, $list = true) {
        if ($this->_query['order']) {
            $this->_query['order'] .= ", ";
        }

        if ($list) {
            $cols = implode(",", parseFields($cols));
        }

        $this->_query['order'] .= $cols;
        return $this;
    }

    /**
     * Группировка
     *
     * @param array|string $cols группировать поля
     * @param bool $list преобразовать в список
     * @return self
     */
    public function group($cols, $list = true) {
        if ($list) {
            $cols = implode(",", parseFields($cols));
        }

        if (!$cols) {
            return $this;
        }
        if ($this->_query['group']) {
            $this->_query['group'] .= ", ";
        }
        $this->_query['group'] .= $cols;
        return $this;
    }

    /**
     * Добавить условие (безопасное).
     * Условие разбираеться.
     *
     * @param string $column        - поле
     * @param string $value         - значенние
     * @param string $partialMatch  - условие "<>", "<=", ">=", "<", ">", "=", "IN", "ALL", "ANY", "LIKE", "NOT LIKE"
     * @param string $operator      - оператор объединения "AND", "OR", "XOR"
     * @param boolean $escape       - флаг, надо ли экранровать значение. по умолчанию true
     * @return self
     */
    public function addWhere($column, $value, $partialMatch = '=', $operator = 'AND', $escape = true) {
        $self = $this;
        if (in_array($partialMatch, array("<>", "<=", ">=", "<", ">", "="))) {
            if ($escape) {
                $self->quoteValue($value);
            }
            $where = "$column $partialMatch $value";
        }

        if (in_array($partialMatch, array("IN", "NOT IN", "ALL", "ANY"))) {
            $value = parseFields($value);
            if ($escape) {
                array_walk($value, function(&$item) use($self) {
                    $item = $self->quoteValue($item);
                });
            }
            if (count($value) == 0) {
                $value = array("NULL");
            }
            $where = $column . " " . $partialMatch
                    . " ( " . implode(", ", $value) . " )";
        }

        if (in_array($partialMatch, array("LIKE", "NOT LIKE"))) {
            if ($escape) {
                $this->quoteValue($value);
            }
            $where = $column . " " . $partialMatch . " " . $value;
        }

        if (in_array($partialMatch, array("BETWEEN", "NOT BETWEEN"))) {
            if (is_array($value)) {
                if ($escape) {
                    array_walk($value, function(&$item) use($self) {
                        $item = $self->quoteValue($item);
                    });
                }
                $value = implode(' AND ', $value);
            }
            $where = $column . " " . $partialMatch . " " . $value;
        }


        if ($this->_query['where']) {
            if ((!in_array($operator, array("AND", "OR", "XOR")))) {
                //LOG::error("Invalid operator Where : " . $operator);    // LOG::error
                $operator = "AND";
            }
            $this->_query['where'] .= " " . $operator . " ";
        }

        $this->_query['where'] .= $where;

        return $this;
    }

    /**
     * Жесткое условие, без экронирования.
     * В случие масива параметры соединены 'AND'
     *
     * @param array|string $where - жесткое условие
     * @return self
     */
    public function where($where) {
        if (is_array($where)) {
            foreach ($where as $key => $value) {
                $where[$key] = " $key = $value ";
            }
            $where = implode('AND', $where);
        }

        $this->_query['where'] .= $where;

        return $this;
    }

    /**
     * Смещение
     *
     * @param integer $offset
     * @return self
     */
    public function offset($offset) {
        $this->_query['offset'] = $offset;
        return $this;
    }

    /**
     * Лимит выводимих записей
     *
     * @param integer $limit
     * @return self
     */
    public function limit($limit) {
        $this->_query['limit'] = $limit;
        return $this;
    }

}
