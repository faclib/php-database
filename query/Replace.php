<?php
/**
 * Replace class  - Replace.php file
 *
 * @author     Dmitriy Tyurin <Fac3d@gmail.com>
 * @copyright  Copyright (c) 2014 Dmitriy Tyurin
 */

namespace Fac\Db;

use \PDO;

/**
 * Replace class
 *
 * @package		Fac.db
 */
class Replace extends Insert
{

    public function __construct(PDO $db)
    {
        parent::__construct($db);
        $this->_command = "REPLACE";
    }
}