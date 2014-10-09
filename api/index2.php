<?php
/**
 * Created by PhpStorm.
 * User: boozz
 * Date: 07.09.14
 * Time: 20:45
 */
$postdata = file_get_contents('php://input');
$request = json_decode($postdata);

echo json_encode($request);