<?php

/*
Есть файл CSV, в котором содержатся записи, разделённые запятыми. Причём, запятая является только разделителем и не используется непосредственно в записях. С помощью команды LOAD DATA INFILE внести записи из этого файла в таблицу базы данных. Сделать без использования bash-терминала. MySQL-сокет также не подключать. То есть не делать это: sudo mysql -S '/opt/lampp/var/mysql/mysql.sock' Терминал вообще не запускать
*/

/* Чтобы это сделать, нужно подключиться к серверу базы данных localhost в качестве пользователя root, для которого мы обычно подключаемся так же и в терминале (с помощью sudo). В phpMyAdmin пользователь root пока не имеет установленного пароля, но зато по умолчанию имеет все возможные привилегии, что нам необходимо для работы с данной программой */
$dbAdmin = 'root';
$dbServer = 'localhost';
$charst = 'utf8';

$dsn = "mysql:host=".$dbServer.";charset=".$charst;
$pdoVar = new PDO($dsn, $dbAdmin); // Пароль не нужен, если он не установлен для root в phpMyAdmin

/* Установим аттрибуты, позволяющие видеть ошибки подключения и создания в БД */
$pdoVar->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

/* Теперь создадим базу, в ней создадим таблицу, и загрузим в эту таблицу данные из файла */
$pdoVar->exec("CREATE DATABASE IF NOT EXISTS SOMEBASE CHARACTER SET utf8 COLLATE utf8_unicode_ci");
$pdoVar->query("USE SOMEBASE"); // Можно использовать и query вместо exec
$pdoVar->exec("CREATE TABLE IF NOT EXISTS Avtomobili 
    (GodVypuska SMALLINT UNSIGNED NOT NULL,
    Marka VARCHAR(30) NOT NULL,
    Model VARCHAR(30) NOT NULL,
    Opisanie VARCHAR(255) NOT NULL,
    TsenaRubley FLOAT(6,2) NOT NULL) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci");
$pdoVar->exec("LOAD DATA INFILE '/opt/lampp/htdocs/Codes/LoadDataMysqlExample/file.csv' INTO TABLE SOMEBASE.Avtomobili FIELDS TERMINATED BY ',' LINES TERMINATED BY '\n'");

$pdoVar = null;
