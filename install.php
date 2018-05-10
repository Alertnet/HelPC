<?php
require_once './cfg.php';
// Подключаемся к серверу,
// на котором предварительно создали базу данных

mysql_connect($HOST, $USER, $PASS) or die ("Не могу создать соединение"); //устанавливаем соединение с хостом, если не получилось завершаем скрипт с ошибкой
mysql_select_db($DB) or die (mysql_error());                              //Выбор базы данных или завершение скрипта


$query = 'CREATE TABLE clients (clients_telephone int(10) NOT NULL PRIMARY KEY,
                                clients_name varchar(50),
                                clients_address varchar(200)
                                )';

mysql_query($query) or die (mysql_error());
echo 'success';
?>
