<?php
/**
 * ACDbUnlockCommand class  - ACDbUnlockCommand.php file
 *
 * @author     Tyurin D. <fobia3d@gmail.com>
 * @copyright  Copyright (c) 2012 AC Software
 */

/**
 * ACDbUnlockCommand class
 *
 * @package AC.db.command
 */
class ACDbUnlockCommand extends ACDbBaseCommand
{

    protected $_command = 'UNLOCK';
    protected $_tables  = null;

    public function __construct(ACDbConnection $dbConnection)
    {
        parent::__construct($dbConnection);
        $this->_query = array('UNLOCK TABLES');
    }

    /**
     * @param string $table
     * @return ACDbUnlockCommand
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

        $this->_dbConnection->tablesLock = false;

        return $result;
    }
}