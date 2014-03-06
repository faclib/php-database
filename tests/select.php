<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

require_once __DIR__.'/bootstrap.php';

$cmd = new Fac\Db\Select($db);
$cmd->select('VERSION() as t');
$cmd->from('cdr')->addWhere('id', 11)->addWhere('column1', 'string', 'LIKE', 'OR');
$cmd->addWhere('column2', array(1,2,3,4), 'IN');
$cmd->leftJoinOn('tbl1', 'ct1', 'ct2');
        
echo $cmd->toString();