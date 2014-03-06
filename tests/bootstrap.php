<?php
/**
 * @author Dmitriy Tyurin <fobia3d@gmail.com>
 */

// TODO: check include path INCLUDE_PATH%
// ini_set('include_path', ini_get('include_path'));

// put your code here

require_once __DIR__.'/../vendor/autoload.php';

$loader = new Composer\Autoload\ClassLoader();
$loader->findFile(__DIR__);
$loader->register();

    
$dsn = 'mysql:dbname=test;host=localhost';
$user = 'root';
$password = '';

$dbh = new PDO($dsn, $user, $password);
