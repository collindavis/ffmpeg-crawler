<?php
$GLOBALS['debug_level'] = 1;

$paths = array
(
	'/Volumes/Footage1/',
);


//array of file extensions to process
$GLOBALS['types'] = array
(
	'mp4', 
	'mov', 
	'dv'
);

//array of things to look for in file names that should be ignored from processing.
//this uses preg_match
$GLOBALS['ignore'] = array
(
	'/!/',
	'/Trash/',
	'/EXT/'
);



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


function preg_error_output($value)
{
	switch($value)
	{
		case PREG_NO_ERROR;
			return 'PREG_NO_ERROR';
			break;
		
		case PREG_INTERNAL_ERROR;
			return 'PREG_INTERNAL_ERROR';
			break;
		
		case PREG_BACKTRACK_LIMIT_ERROR;
			return 'PREG_BACKTRACK_LIMIT_ERROR';
			break;
			
		case PREG_RECURSION_LIMIT_ERROR;
			return 'PREG_RECURSION_LIMIT_ERROR';
			break;
			
		case PREG_BAD_UTF8_ERROR;
			return 'PREG_BAD_UTF8_ERROR';
			break;
			
		case PREG_BAD_UTF8_OFFSET_ERROR;
			return 'PREG_BAD_UTF8_OFFSET_ERROR';
			break;
	}
}
