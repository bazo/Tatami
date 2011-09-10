<?php
namespace Tatami\Components\AssetsLoader\Renderers;
use Nette\Utils\Html;
/**
 * Description of JsRenderer
 *
 * @author Martin
 */
class JsRenderer extends AssetsRenderer
{
    protected 
	$prefix = 'jsloader',
	$suffix = '.js',
	$assetFolder = 'js'
    ;

    protected function getHtml($fileName)
    {
	return Html::el('script')->type('text/javascript')->src($fileName);
    }
}