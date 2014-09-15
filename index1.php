<?php
	class Boozz_Debug{
 		static public function dump($value){
 			if($value){
 				echo '<pre>';
 				var_dump($value);
 				echo '</pre>';
 			}
 		}
 	}
 	$dsn = "mysql:host=localhost;dbname=angular;charset=utf-8";
 	$opt = array(
 		PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
 		PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC);
 	$user = "root";
 	$pass = "";
 	$pdo = new PDO($dsn, $user, $pass, $opt);
 	echo "ok";
 	$query = $pdo->prepare('SELECT * FROM users');
 	$query->execute();
 	Boozz_Debug::dump($query->fetch());
 	
