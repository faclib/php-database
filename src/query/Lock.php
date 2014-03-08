<?php
/**
 * Lock class  - Lock.php file
 *
 * @author     Dmitriy Tyurin <fobia3d@gmail.com>
 * @copyright  Copyright (c) 2014 Dmitriy Tyurin
 */

namespace Fac\Db;

use \PDO;

/**
 * Lock class
 *
 * @package		Fac.db
 */
class Lock extends Base
{

    protected $_command = 'LOCK';
    protected $_tables  = array();

    /**
     *
     * @param PDO $db
     * @param string|array   $tables
     */
    public function __construct(PDO $db, $tables)
    {
        parent::__construct($db);

        $this->_query = array('LOCK TABLES ');

        if (is_array($tables)) {
            foreach ($tables as $key => $value) {
                if (is_numeric($key)) {
                    $key   = $value;
                    $value = 'READ';
                }
                $this->_tables[] = $key . ' ' . $value;
            }
        } else {
            $this->_tables[] = (string) $tables;
        }
    }

    /**
     *
     * @param string $table
     * @param string $write
     * @return self
     */
    public function addTable($table, $write = false)
    {
        if ($write) {
            $table .= " WRITE";
        } else {
            $table .= " READ";
        }
        $this->_tables[] = $table;
        return $this;
    }

    public function query()
    {
        // $this->db->tablesLock = true;

        $this->_query[] = implode(", ", $this->_tables);

        return parent::query();
    }
}