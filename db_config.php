<?php
	$db_host = "dbhome.cs.nctu.edu.tw";
	$db_name = "ca032035_cs_project1";
	$db_user = "ca032035_cs";
	$db_password = "1234abcd";
	
	$dsn = "mysql:host=$db_host;dbname=$db_name";
	$db = new PDO($dsn, $db_user, $db_password);
	$DNS = "http://people.cs.nctu.edu.tw/~ca032035/project";
?>