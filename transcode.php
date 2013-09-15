<?php 

include_once('settings.php');

$videos = R::find('video',
			' transcoded = :transcoded LIMIT 2', 
				array( 
					':transcoded' => false 
				)
		);

foreach($videos as $video)
{
	$output = '';
	echo "transcoding {$video->definition}\r\n\t{$video->filename}\r\nto\t$output\r\n";
	$command = sprintf('./ffmpeg -i "%s" %s -sameq "%s" ', $video->filename, $GLOBALS['transcode_options'],  $output);
	echo $command."\r\n";
}
