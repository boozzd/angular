<?php
error_reporting(false);
require_once('config.php');
try{
    $dbObject = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_USER, DB_PASS, array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'));
}catch (PDOException $e) {
    print "Error!: " . $e->getMessage() . "<br/>";
    die();
}
require_once(SITE_PATH.'library'.DS.'Application.php');
session_start();
$router = new Router($registry);
$registry->set('router',$router);
$router->setPath(SITE_PATH.'controllers');
$router->start();