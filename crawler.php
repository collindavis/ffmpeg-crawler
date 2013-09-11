<?php
include_once('settings.php');
include_once('ffprobe.php');

foreach($paths as $path)
	dir_walk('probe', $path, $GLOBALS['types'], $GLOBALS['ignore'], true, $path);

function probe($file)
{
	$ffprobe = new ffprobe($file, true);
	$metadata = $ffprobe->get_all();
	//debug_output($metadata);
	
	$path = explode('/', $file);
	
	$extra = explode('_', $path[7]);
	
	$output = array
	(
		'filename' => $metadata['format']['filename'],
		'format_long_name' => $metadata['format']['format_long_name'],
		'nb_streams' => $metadata['format']['nb_streams'],
		'duration' => $metadata['format']['duration'],
		'size' => $metadata['format']['size'],
		'bit_rate' => $metadata['format']['bit_rate'],
		'creation_time' => $metadata['format']['tags']['creation_time'],
		'make' => $path[2],
		'model' => $path[3],
		'year' => $path[4],
		'type' => $path[5],
		'source' => $path[6],
		'definition' => $extra[2],
		'action' => $extra[1],
	);
	
	debug_output($output);
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
		echo "DEBUG: $level \t\r\n".print_r($output, true)."\r\n";
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
function dir_walk($callback, $dir, $types = null, $ignore = null, $recursive = false, $baseDir = '') 
{
	$dir = rtrim($dir, '/');
	debug_output("opening directory ".$dir, 0);
	
	if ($dh = opendir($dir)) 
	{
		while (($file = readdir($dh)) !== false) 
		{
			$continue = true;
			if(isset($GLOBALS['ignore']))
			{
				foreach($GLOBALS['ignore'] as $regex)
				{
					$test = preg_match($regex, $file);
					if($test && count($test))
					{
						debug_output('found "'.$regex.'" skipping file '.$file, 1);
						$continue = false;
					}
					elseif(preg_last_error())
					{
						debug_output('Bad regex "'.$regex.'" preg_last_error = '.preg_error_output(preg_last_error()), 5);
					}
				}
			}
			
			if($continue)
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
					dir_walk($callback, $file_path . DIRECTORY_SEPARATOR, $types, $ignore, $recursive, $baseDir . $file . DIRECTORY_SEPARATOR);
				}
				else
					debug_output('doing nothing '.$file_path);
			}
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
