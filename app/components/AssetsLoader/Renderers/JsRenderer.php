<?php
namespace Tatami\Components\AssetsLoader\Renderers;
/**
 * Description of JsRenderer
 *
 * @author Martin
 */
class JsRenderer extends AssetsRenderer
{
    protected 
	$prefix = 'jsloader',
	$suffix = '.js'
    ;

    protected function getTempUrlFileName($tempUrl, $fileName)
    {
	return $tempUrl.'/js/'.$fileName;
    }
    
    protected function getHtml($fileName)
    {
	return Html::el('script')->type('text/css')->src($fileName);
    }
}