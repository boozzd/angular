<?php

try {
    $dbh = new PDO('mysql:host=localhost;dbname=havka', 'root', '123456',array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
    // foreach($dbh->query('SELECT * from city c RIGHT OUTER JOIN  city a ON c.parent = a.id WHERE c.id=3') as $row) {
    //     d($row);
    // }
} catch (PDOException $e) {
    print "Error!: " . $e->getMessage() . "<br/>";
    die();
}
$sql1 = 'SELECT c.id AS first_id, c.name AS first_name, s.id AS second_id, s.name AS second_name from city c RIGHT OUTER JOIN city s ON c.parent = s.id WHERE c.id = 3';
$sql = 'SELECT s.c_id, s.c_name FROM content s INNER JOIN city c ON s.c_city = c.id';
$sql2 = 'SELECT h.c_name FROM city c LEFT OUTER JOIN city b ON c.parent = b.id LEFT OUTER JOIN city a ON b.parent = a.id RIGHT OUTER JOIN content h ON (h.c_city = c.id OR h.c_city = b.id OR h.c_city = a.id) WHERE c.id = 3';
$sdh = $dbh->prepare($sql2);

$sdh->execute();
$res = $sdh->fetchAll(PDO::FETCH_ASSOC);
d($res);




function d($key){
	echo '<pre>';
	var_dump($key);;
	echo '</pre>';
}
