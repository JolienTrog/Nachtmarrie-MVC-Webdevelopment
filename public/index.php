<?php

//use Nachtmerrie\Dispatcher;

require_once('../lib/functions.php');
require_once('../vendor/autoload.php'); //einbinden von vendor autoloader
session_start();
spl_autoload_register('\Nachtmerrie\autoloader');

Nachtmerrie\Dispatcher::dispatch();

//$dsn = 'mysql:host=localhost;dbname=nachtmerrie';
//$username = 'admin';
//$password = 'admin123';
//$dbh = new PDO($dsn, $username, $password);
//
//$sql = "SELECT * FROM item";
//$stmt = $dbh->prepare($sql);
//$stmt->execute();
//
//foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
//    echo 'ID: ' . $row['id'] . "\n";
//    echo 'NL: ' . $row['nl'] . "\n";
//    echo 'DE: ' . $row['de'] . "\n";
//}


