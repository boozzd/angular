<?php
/**
 * Created by PhpStorm.
 * User: boozz
 * Date: 24.08.14
 * Time: 21:04
 */
define('DS', DIRECTORY_SEPARATOR);
$sitepath = realpath(dirname(__FILE__).DS).DS;
define('SITE_PATH', $sitepath);

define('DB_USER', "root");
define('DB_PASS', "123456");
define('DB_HOST', "localhost");
define('DB_NAME', 'angular');