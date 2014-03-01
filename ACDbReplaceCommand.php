<?php
/**
 * ACDbReplaceCommand class  - ACDbReplaceCommand.php файл
 *
 * @author     Tyurin D. <fobia3d@gmail.com>
 * @copyright  Copyright (c) 2012 AC Software
 */

/**
 * ACDbReplaceCommand class
 *
 * @package AC.db.command
 */
class ACDbReplaceCommand extends ACDbInsertCommand
{

    public function __construct(ACDbConnection $dbConnection)
    {
        parent::__construct($dbConnection);
        $this->_command = "REPLACE";
    }
}



// рус