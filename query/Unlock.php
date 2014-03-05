<?php
/**
 * Unlock class  - Unlock.php file
 *
 * @author     Dmitriy Tyurin <fobia3d@gmail.com>
 * @copyright  Copyright (c) 2014 Dmitriy Tyurin
 */

namespace Fobia\Db;

use \PDO;

/**
 * Unlock class
 *
 * @package		fobia.db
 */
class Unlock extends Base
{

    protected $_command = 'UNLOCK';
    protected $_tables  = null;

    public function __construct(PDO $db)
    {
        parent::__construct($db);
        $this->_query = array('UNLOCK TABLES');
    }

    /**
     * @param string $table
     * @return self
     */
    public function addTable($table)
    {
        $this->_tables[] = $table;
        return $this;
    }

    public function query()
    {
        $this->_query[] = implode(", ", $this->_tables);

        $result = parent::query();

        // $this->_dbConnection->tablesLock = false;

        return $result;
    }
}