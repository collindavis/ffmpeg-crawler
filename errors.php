<?php 

include_once('settings.php');
include_once('cli_colors.php');
$colors = new Colors();

$errors = R::find('error');

$count = 0;
foreach($errors as $error)
{
	echo $colors->getColoredString(" id: ".$error-id, 'black', 'red')."\r\n";
	echo $error->raw_output."\r\n";
	$count++;
}
echo "\r\n".$count." errors found\r\n";
