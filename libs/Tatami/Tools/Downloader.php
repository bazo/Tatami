<?php
namespace Tatami\Tools;

class Downloader extends \Nette\Object
{
    public static function download($source, $destination)
    {
	//if(!in_array("safe", stream_get_wrappers()))
	//    \Nette\Utils\SafeStream::register();
	if(extension_loaded('curl'))
	{
	    $handle = curl_init($source);
	    $file = fopen($destination, 'w');
	    curl_setopt($handle, CURLOPT_FILE, $file);
	    curl_exec($handle);
	    curl_close($handle);
	    fclose($file);
	}
    }
}