<?php


$values = array();
$values[] = uniqid();
$values[] = $_SERVER['REMOTE_ADDR'];
$values[] = json_encode($_POST);

$link = mysqli_connect('localhost', 'root', 'testing', 'pl');

$sql = "INSERT INTO Content(id, ip, data) VALUES ('" . implode("','", $values) . "')";
mysqli_query($link, $sql);

print_r($values[0]);

?>