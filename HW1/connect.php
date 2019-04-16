<?php
	$host="";
	$dbname="";
	$dbusername="";
	$dbpassword="";
	$dsn="mysql:dbname=".$dbname.";host=".$host;

	$connect=new PDO($dsn,$dbusername,$dbpassword);
	$connect->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
?>