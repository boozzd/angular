<?php
	//Подключение к бд
	$user = 'root';
	$password = '123456';
	$db_name = 'angular';
	$host = 'localhost';
	try{
		$dbObject = new PDO('mysql:host='.$host.';dbname='.$db_name, $user, $password, array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'));
	}
	catch(PDOException $e){
		echo $e->getMessage();
		die();
	}

	//Создаю таблицу users
	$create_db = $dbObject->prepare('CREATE TABLE IF NOT EXISTS `users`
		(
			`id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
			`u_name` varchar(128) NOT NULL,
			`u_password` varchar(255) NOT NULL,
			`u_email` varchar(255) NOT NULL,
			`u_role` int(11),
			`u_sname` varchar(255),
			`u_rname` varchar(255),
			`u_about` text
		)
	');
	$res = $create_db->execute();
	
	if($res){
		echo 'Таблица users успешно создана!';
	}else{
		echo 'Таблица users не создана';
	}

	echo '<br />';
//Создаем таблицу role
	$create_db = $dbObject->prepare('CREATE TABLE IF NOT EXISTS `role`
		(
			`r_id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
			`r_role` varchar(40)
		)
	');
	$res = $create_db->execute();
	
	if($res){
		echo 'Таблица role успешно создана!';
	}else{
		echo 'Таблица role не создана';
	}
	echo '<br />';
	//Создаем таблицу places
	$create_db = $dbObject->prepare('CREATE TABLE IF NOT EXISTS `places`
		(
			`id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
			`name` varchar(255),
			`description` text,
			`image` varchar(255),
			`url` varchar(255) NOT NULL,
			`city` int(11),
			`status` tinyint(1) DEFAULT 1 NOT NULL
		)
	');
	$res = $create_db->execute();
	
	if($res){
		echo 'Таблица places успешно создана!';
	}else{
		echo 'Таблица places не создана';
	}
	echo '<br />';
//Создвем таблицу city
	$create_db = $dbObject->prepare('CREATE TABLE IF NOT EXISTS `city`
		(
			`c_id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
			`c_parent` int(11) DEFAULT NULL,
			`c_name` varchar(255) NOT NULL,
			`c_status` tinyint(1) DEFAULT 1 NOT NULL
		)
	');
	$res = $create_db->execute();
	
	if($res){
		echo 'Таблица city успешно создана!';
	}else{
		echo 'Таблица city не создана';
	}
