<?php
$GLOBALS['debug_level'] = 1;

$paths = array
(
	'/home/jamesn/Documents/test/',
);


//array of file extensions to process
$GLOBALS['types'] = array('mp4');

//array of things to look for in file names that should be ignored from processing.
$GLOBALS['ignore'] = array('');



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
