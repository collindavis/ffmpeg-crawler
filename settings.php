<?php
$GLOBALS['debug_level'] = 1;

/*
 * NOTE: all paths defined but have a trailing slash
 */

$paths = array
(
	'/Volumes/Footage1/',
);


//array of file extensions to process
$GLOBALS['types'] = array
(
	'mp4', 
	'mov', 
	'dv',
	'mpg'
);

//array of things to look for in file names that should be ignored from processing.
//this uses preg_match
$GLOBALS['ignore'] = array
(
	'/!/',
	'/Trash/',
	'/TheVolumeSettingsFolder/',
	'/xx/',
	'/^\./' //any file name starting with a period
);

$GLOBALS['transcode_to'] = '/Users/encoder/engine/output/Rawclips/';

$GLOBALS['transcode_options_hd'] = ' -y -q:v 1 -b:v 4800k -b:a 128k -strict -2';
$GLOBALS['transcode_options_sd'] = ' -y -q:v 1 -b:v 1600k -b:a 128k -strict -2';

/*
 * MySQL Connection
 */
$host = '127.0.0.1';
$dbname = 'carvid';
$user = 'root';
$pass = '';
try 
{
	require 'redbean/rb.php';
	R::setup("mysql:host=$host;dbname=$dbname",$user,$pass); 
	//R::nuke();
}
catch(Exception $e)
{
	echo $e->getMessage()."\r\n";
}


/*
 * outputs a better preg match error
 */
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
