<?php
$GLOBALS['debug_level'] = 1;

$path = '/home/jamesn/Documents/test/';

//array of file extensions to process
$types = array('mp4');

dir_walk('probe', $path, $types, true, $path);

function probe($file)
{
	//$command = 'ffprobe -print_format json '.$file;
	 $comand = '-v quiet -print_format json -show_format -show_streams '.$file;
	debug_output('exec '.$command, 1); 
	$output = shell_exec($command);
	//$output = preg_split('/[\r\n]+/', $output);
	$output = json_decode( $output);
	debug_output($output, 1);
	foreach($output as $key => $line);
	{
		$line = explode(':', $line);
		debug_output($key, 1);
		debug_output($line, 1);
	}
	
	
}

function transcode_video($file)
{
	//from http://trac.ffmpeg.org/wiki/Using%20FFmpeg%20from%20PHP%20scripts
	echo "Starting ffmpeg...\n\n";
	echo shell_exec("ffmpeg -y -i input.avi output.avi </dev/null >/dev/null 2>/var/log/ffmpeg.log &");
	echo "Done.\n";
}

function debug_output($output, $level = 1)
{
	if($GLOBALS['debug_level'] <= $level)
		echo "* $level * ".print_r($output, true)."\r\n";
}
/**
 * Calls a function for every file in a folder.
 *
 * @author Vasil Rangelov a.k.a. boen_robot
 *
 * @param string $callback The function to call. It must accept one argument that is a relative filepath of the file.
 * @param string $dir The directory to traverse.
 * @param array $types The file types to call the function for. Leave as NULL to match all types.
 * @param bool $recursive Whether to list subfolders as well.
 * @param string $baseDir String to append at the beginning of every filepath that the callback will receive.
 */
function dir_walk($callback, $dir, $types = null, $recursive = false, $baseDir = '') 
{
	$dir = rtrim($dir, '/');
	debug_output("opening directory ".$dir, 0);
	
	if ($dh = opendir($dir)) 
	{
		while (($file = readdir($dh)) !== false) 
		{
			$file_path = $dir.'/'.$file;
			if ($file === '.' || $file === '..') 
			{
				continue;
			}
			if (is_file($file_path)) 
			{
				debug_output('found file '.$file_path, 0);
				if (is_array($types)) 
				{
					$pathinfo = pathinfo($file_path, PATHINFO_EXTENSION);
					debug_output($pathinfo, 0);
					if (!in_array(strtolower($pathinfo), $types, true)) 
					{
						continue;
					}
				}
				$callback($baseDir . $file);
			}
			elseif($recursive && is_dir($file_path)) 
			{
				dir_walk($callback, $file_path . DIRECTORY_SEPARATOR, $types, $recursive, $baseDir . $file . DIRECTORY_SEPARATOR);
			}
			else
				debug_output('doing nothing '.$file_path);
		}
		closedir($dh);
	}
	else
	{
		echo "unable to open directory $dir\r\n";
	}
	debug_output("leaving directory ".$file, 0);
}

echo "all done\r\n";
