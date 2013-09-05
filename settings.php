<?php
$GLOBALS['debug_level'] = 1;

$paths = array
(
	'/home/jamesn/Documents/test/',
);


//array of file extensions to process
$types = array('mp4');



/*
 * MySQL Connection
 */
$host = 'localhost';
$dbname = 'videos';
$user = 'root';
$pass = '';
try 
{
	# MySQL with PDO_MYSQL
	$DBH = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);  
}
catch(PDOException $e)
{
	echo $e->getMessage()."\r\n";
}
