<?php
error_reporting(false);
define('DS', DIRECTORY_SEPARATOR);
$sitepath = realpath(dirname(__FILE__).DS).DS;
define('SITE_PATH', $sitepath);

define('DB_USER', "root");
define('DB_PASS', "123456");
define('DB_HOST', "localhost");
define('DB_NAME', 'angular');