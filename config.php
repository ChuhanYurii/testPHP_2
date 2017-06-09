<?php

# Данные для подключения к БД
$dbHost = "localhost";
$dbUser = "root";
$dbPass = "";
$dbName = "test";

$mysqli  = @new mysqli($dbHost, $dbUser, $dbPass, $dbName);

 if (mysqli_connect_errno()) 
    	echo "Подключение невозможно: ".mysqli_connect_error();

?>