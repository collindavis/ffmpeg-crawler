<?php 

include_once('settings.php');

include_once('cli_colors.php');
// Create new Colors class
$colors = new Colors();

$videos = R::find('video',
			' transcoded = :transcoded LIMIT 2', 
				array( 
					':transcoded' => false 
				)
		);

foreach($videos as $video)
{
	$filename = explode('/', $video->filename);
	$filename = end($filename);
	$filename = explode('.', $filename);
	$filename = $filename[0].'.mp4';
	$filename = str_replace(' ', '_', $filename);
	
	$output = $GLOBALS['transcode_to'].$filename;
	
	$options = false;
	if(strstr($video->definition, 'HD'))
	{
		$options = $GLOBALS['transcode_options_hd'];
	}
	elseif(strstr($video->definition, 'HD'))
	{
		$options = $GLOBALS['transcode_options_sd'];
	}
	
	if($options)
	{
		echo  $colors->getColoredString("transcoding {$video->id} {$video->definition}\r\n\t{$video->filename}\r\n\tto\t\r\n\t$output\r\n", 'red');
		$command = sprintf('./ffmpeg -i "%s" %s "%s" ', $video->filename, $options,  $output);
		echo $colors->getColoredString("\tRunning\r\n\t".$command."\r\n", 'blue');
		
		//Real time output
		//from http://stackoverflow.com/questions/8370628/php-shell-exec-with-realtime-updating
		if( ($fp = popen($command, "r")) ) 
		{
			while( !feof($fp) )
			{
				echo fread($fp, 1024);
				flush(); // you have to flush buffer
			}
			fclose($fp);
		}
		
		//Mark this off in the db as transcoded
		$video->transcoded = true;
		$id = R::store($video);
		
	}
	else
	{
		echo $colors->getColoredString("**** ERROR ****\r\n", 'black', 'red');
		echo $colors->getColoredString("Unable to detect files definistion (SD or HD)\r\ndid not transcode file {$video->id} {$video->filename}\r\n", 'black', 'red');
	}
}
