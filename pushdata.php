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

	//Вносим данные в талицу users
	$create_db = $dbObject->prepare("
		INSERT INTO `users`
		(`u_name`, `u_password`, `u_email`, `u_role`, `u_sname`, `u_rname`, `u_about`)
		VALUES ('admin', 'c4ca4238a0b923820dcc509a6f75849b', 'shapovdu@yandex.ru', 2, 'Шаповаленко', 'Дмитрий', 'студент');
	");
	$res = $create_db->execute();
	
	if($res){
		echo 'Данные в таблицу users успешно добавлены!';
	}else{
		echo 'Данные в таблицу users не добавлены';
	}

	echo '<br />';
//Вносим даные в таблицу role
	$create_db = $dbObject->prepare("
		INSERT INTO `role`
		(`r_id`,`r_role`)
		VALUES (1,'user'),
				(2,'admin');
	");
	$res = $create_db->execute();
	
	if($res){
		echo 'Данные в таблицу role успешно добавлены!';
	}else{
		echo 'Данные в таблицу role не добавлены';
	}
	echo '<br />';
	//Вносим данные в таблицу places
	$create_db = $dbObject->prepare("
		INSERT INTO `places`
		(`name`, `description`, `image`, `url`, `city`, `status`)
		VALUES ('Екатерининская площадь', 'Нынешняя Екатерининская площадь изменяла свою форму, а также 7 раз меняла своё название. В соответствии с первоначальным планом города, разрабатываемым подполковником Деволаном, пло­щадь имела круглую форму и была названа Екатеринин­ской, так как на ней была заложена при основании города Военная церковь святой Екатерины, храм небесной по­кровительницы будущей императрицы. После кончины императрицы в ноябре 1796 г. строительство церкви, как и всей Одессы, было приостановлено Павлом І. В 1821 году так и недостроенная церковь была разо­брана.', 'odessa_ekaterina.jpg', 'ekaterina', 2, 1),
			('Потёмкинская лестница', 'Потёмкинская лестница — бульварная лестница, ведущая к морю в Одессе. Архитектурное сооружение в стиле классицизма, являющееся памятником архитектуры первой половины XIX века и одной из главных достопримечательностей города. С верхних ступеней лестницы открывается широкая панорама морского порта, гавани и Одесского залива.','potem.jpg', 'potem', 2, 1),
			('Площадь Независимости', 'Центральная площадь Киева. Расположена между Крещатиком, улицами Бориса Гринченко, Софиевской, Малой Житомирской, Михайловской, Костёльной, Институтской, Архитектора Городецкого и переулком Тараса Шевченко.', 'ind.jpeg', 'independence', 3, 1);
	");
	$res = $create_db->execute();
	
	if($res){
		echo 'Данные в таблицу places успешно добавлены!';
	}else{
		echo 'Данные в таблицу places не добавлены';
	}
	echo '<br />';
//Вносим данные в таблицу city
	$create_db = $dbObject->prepare("
		INSERT INTO `city`
		(`c_id`,`c_parent`, `c_name`, `c_status`)
		VALUES (1,NULL, 'Украина', 1),
				(2, 1,'Одесса', 1),
				(3, 1, 'Киев', 1);
	");
	$res = $create_db->execute();
	
	if($res){
		echo 'Данные в таблицу city успешно добавлены!';
	}else{
		echo 'Данные в таблицу city не добавлены';
	}
