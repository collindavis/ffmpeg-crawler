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
	$filepath = explode('/', $video->filename);
	$filename = end($filepath);
	$filename = explode('.', $filename);
	$filename = $filename[0].'.mp4';
	$filepath = $GLOBALS['transcode_to'].$filepath[3].'/'.$filepath[4].'/'.$filepath[5].'/'.$filepath[6].'/';
	if (!file_exists($filepath)) 
	{
		mkdir($filepath, 0777, true);
	}
	$filename = $filepath.str_replace(' ', '_', $filename);
	
	$output = $filename;
	
	$options = false;
	if(strstr($video->definition, 'HD'))
	{
		$options = $GLOBALS['transcode_options_hd'];
	}
	//elseif(strstr($video->definition, 'SD'))
	else
	{
		$options = $GLOBALS['transcode_options_sd'];
	}
	
	if($options)
	{
		echo $colors->getColoredString("transcoding {$video->id} {$video->definition}\r\n\t{$video->filename}\r\n\tto\t\r\n\t$output\r\n", 'red');
		$command = sprintf('./ffmpeg -i "%s" %s "%s" ', $video->filename, $options,  $output);
		echo $colors->getColoredString("\tRunning\r\n\t".$command."\r\n", 'blue');
		
		//Real time output
		//from http://stackoverflow.com/questions/8370628/php-shell-exec-with-realtime-updating
		if( ($fp = popen($command, "r")) ) 
		{
			$output = '';
			while( !feof($fp) )
			{
				$temp = fread($fp, 1024);
				$output .= $temp;
				echo $temp;
				flush(); // you have to flush buffer
			}
			
			if(!pclose($fp)) //Note:had to fix this, was fclose
			{
				//The command finished with an error. 
				$error = R::dispense('error');
				$video->ownError = array($error);
				$error->raw_output = $output;
				$error->repaired = false;
				$id = R::store($error);
			}
			//Mark this off in the db as transcoded
			$video->transcoded = true;
			$id = R::store($video);
		}
		else
		{
			$output = "Failed running ffmpeg, is it in the root of this folder?\r\n";
			//The command finished with an error. 
			$error = R::dispense('error');
			$video->ownError = array($error);
			$error->raw_output = $output;
			$error->repaired = false;
			$id = R::store($error);
		}
		
		
		
	}
	else
	{
		$output = 	"**** ERROR ****\r\n"
					."Unable to detect files defintion (SD or HD)\r\ndid not transcode file {$video->id} {$video->filename}\r\n";
		echo $colors->getColoredString($output, 'black', 'red');
		
		//The command finished with an error. 
		$error = R::dispense('error');
		$video->ownError = array($error);
		$error->raw_output = $output;
		$error->repaired = false;
		$id = R::store($error);
	}
}
