<?php 

include_once('settings.php');

$videos = R::find('video');

foreach($videos as $video)
{
	if(!file_exists($video->filename))
	{
		R::trash( $video );
		echo "removing {$video->id} {$video->filename}\r\n";
	}
}
