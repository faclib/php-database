<?php
/**
 * ACDbLockCommand class  - ACDbLockCommand.php file
 *
 * @author     Tyurin D. <fobia3d@gmail.com>
 * @copyright   Copyright (c) 2013 AC Software
 */

/**
 * ACDbLockCommand class
 *
 * @package AC.db.command
 */
class ACDbLockCommand extends ACDbBaseCommand
{

    protected $_command = 'LOCK';
    protected $_tables  = array();

    /**
     *
     * @param ACDbConnection $dbConnection
     * @param string|array   $tables
     */
    public function __construct(ACDbConnection $dbConnection, $tables)
    {
        parent::__construct($dbConnection);

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
     * @return \ACDbLockCommand
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
        $this->_dbConnection->tablesLock = true;

        $this->_query[] = implode(", ", $this->_tables);

        return parent::query();
    }
}
