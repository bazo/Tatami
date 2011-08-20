<?php
namespace Tatami\Tools;
/**
 * Mobile Detector
 */
class MobileDetector {
    
    private 
	$accept,
	$userAgent,
	$isMobile = false,
	$isGeneric = null,
	$device = null
    ;
    
    private $mobileDevices = array(
        'android'       =>	'android',
	'iphone'	=>	'iphone',
	'ipad'		=>	'ipad',
	'windows phone os 7' 	=>	'windows phone os 7',
	'ipod'		=>	'ipod',
        'blackberry'    =>	'blackberry',
	'kindle'	=>	'kindle',
        'opera'         =>	'opera mini',
        'palm'          =>	'(avantgo|blazer|elaine|hiptop|palm|plucker|xiino)',
        'windows'       =>	'windows ce; (iemobile|ppc|smartphone)',
        'generic'       =>	'(kindle|mobile|mmp|midp|o2|pda|pocket|psp|symbian|smartphone|treo|up.browser|up.link|vodafone|wap)',
	'webkit'	=>	'webkit',
	'googletv'	=>	'googletv',
	'xoom'		=>	'xoom',
	'htc_flyer'	=>	'htc_flyer',
	'nuvifone'	=>	'nuvifone',
	'symbian'	=>	'symbian',
	'series60'	=>	'series60',
	'series70'	=>	'series70',
	'series80'	=>	'series80',
	'series90'	=>	'series90',
	'windows ce'	=>	'windows ce',
	'iemobile'	=>	'iemobile',
	'ppc'		=>	'ppc',
	'wm5 pie'	=>	'wm5 pie',
	'blackberry'	=>	'blackberry',
	'vnd.rim'	=>	'vnd.rim',
	'blackberry95'	=>	'blackberry95',
	'blackberry97'	=>	'blackberry97',
	'blackberry96'	=>	'blackberry96',
	'blackberry89'	=>	'blackberry89',
	'blackberry 98'	=>	'blackberry 98',
	'playbook'	=>	'playbook',
	'palm'		=>	'palm',
	'webos'		=>	'webos',
	'hpwos'		=>	'hpwos',
	'blazer'	=>	'blazer',
	'xiino'		=>	'xiino',
	'vnd.wap'	=>	'vnd.wap',
	'wml'		=>	'wml',
	'tablet'	=>	'tablet',
	'brew'		=>	'brew',
	'danger'	=>	'danger',
	'hiptop'	=>	'hiptop',
	'playstation'	=>	'playstation',
	'nitro'		=>	'nitro',
	'nintendo'	=>	'nintendo',
	'wii'		=>	'wii',
	'xbox'		=>	'xbox',
	'archos'	=>	'archos',
	'netfront'	=>	'netfront',
	'up.browser'	=>	'up.browser',
	'openweb'	=>	'openweb',
	'midp'		=>	'midp',
	'up.link'	=>	'up.link',
	'teleca q'	=>	'teleca q',
	'pda'		=>	'pda',
	'mini'		=>	'mini',
	'mobile'	=>	'mobile',
	'mobi'		=>	'mobi' ,
	'maemo'		=>	'maemo',
	'qt embedded'	=>	'qt embedded',
	'com2'		=>	'com2',
	'sonyericsson'	=>	'sonyericsson',
	'ericsson'	=>	'ericsson',
	'sec-sgh'	=>	'sec-sgh',
	'sony'		=>	'sony',
	'htc'		=>	'htc',
	'docomo'	=>	'docomo',
	'kddi'		=>	'kddi',
	'vodafone'	=>	'vodafone'
    );


    public function __construct() 
    {
        $this->userAgent = $_SERVER['HTTP_USER_AGENT'];
        $this->accept    = $_SERVER['HTTP_ACCEPT'];
    }

    public function detect()
    {
	if (isset($_SERVER['HTTP_X_WAP_PROFILE'])|| isset($_SERVER['HTTP_PROFILE'])) 
	{
            $this->isMobile = true;
        } 
	elseif (strpos($this->accept,'text/vnd.wap.wml') > 0 || strpos($this->accept,'application/vnd.wap.xhtml+xml') > 0) 
	{
            $this->isMobile = true;
        } 
	else 
	{
	    foreach ($this->mobileDevices as $device => $regexp) 
	    {
                if ($this->isDevice($device)) 
		{
                    $this->isMobile = true;
		    $this->device = $device;
		    break;
                }
            }
        }
	if(!$this->isMobile) $this->device = 'desktop';
	return $this;
    }

    /**
     * Overloads isAndroid() | isBlackberry() | isOpera() | isPalm() | isWindows() | isGeneric() through isDevice()
     *
     * @param string $name
     * @param array $arguments
     * @return bool
     */
    public function __call($name, $arguments) 
    {
        $device = substr($name, 2);
        if ($name == 'is' . ucfirst($device)) 
	{
            return $this->isDevice($device);
        } 
	else 
	{
            trigger_error(sprintf('Method %s not defined', $name), E_USER_ERROR);
        }
    }
    
    /**
     * Returns true if any type of mobile device detected, including special ones
     * @return bool
     */
    public function isMobile() 
    {
        return $this->isMobile;
    }

    /**
     * Tells if the device detected is what we ask
     * @param string $device
     * @return bool  
     */
    public function isDevice($device) 
    {
        $var    = 'is' . ucfirst($device);
        $return = (bool) preg_match('/' . $this->mobileDevices[$device] . '/i', $this->userAgent);

        if ($device != 'generic' && $return == true) 
	{
	    $this->isGeneric = false;
        }
        return $return;
    }
    
    /**
     * @return string Detected device 
     */
    public function getDevice()
    {
	return $this->device;
    }
}