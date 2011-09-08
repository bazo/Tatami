<?php

namespace Tatami\Components\AssetsLoader\Filters;

use Nette\Utils\Strings;

/**
 * Absolutize urls in CSS
 * @author Martin Bazik
 * @author Jan Marek
 * @license MIT
 */
class DataUriFilter extends \Nette\Object 
{
    
    private 
	$sourcePath
    ;
    
    public function __construct($sourcePath) 
    {
	$this->sourcePath = $sourcePath;
    }
    
    /**
     * Invoke filter
     * @param string code
     * @param WebLoader loader
     * @param string file
     * @return string
     */
    public function __invoke($code)
    {
	// thanks to kravco
	$regexp = '~
		(?<![a-z])
		url\(                                     ## url(
			\s*                                   ##   optional whitespace
			([\'"])?                              ##   optional single/double quote
			(   (?: (?:\\\\.)+                    ##     escape sequences
				|   [^\'"\\\\,()\s]+              ##     safe characters
				|   (?(1)   (?!\1)[\'"\\\\,() \t] ##       allowed special characters
					|       ^                     ##       (none, if not quoted)
					)
				)*                                ##     (greedy match)
			)
			(?(1)\1)                              ##   optional single/double quote
			\s*                                   ##   optional whitespace
		\)                                        ## )
	~xs';
	
	$sourcePath = $this->sourcePath;
	
	return preg_replace_callback(
		$regexp,
		function ($matches) use ($sourcePath) 
		{
		    return "url('" . DataUriFilter::convert($matches[2], $sourcePath) . "')";
		},
		$code
	);
    }

    /**
     * Replace image with data uri
     * @param type $url
     * @param type $quote
     * @param type $sourcePath 
     */
    public static function convert($url, $sourcePath) 
    {
	$file = $sourcePath.DIRECTORY_SEPARATOR.$url;
	if(file_exists($file))
	{
	    $type = \Nette\Utils\MimeTypeDetector::fromFile($file);
	    return 'data:' . ($type ? "$type;" : '') . 'base64,' . base64_encode(file_get_contents($file));
	}
	else return $url;
    }

}