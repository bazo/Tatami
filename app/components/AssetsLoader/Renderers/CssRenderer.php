<?php
namespace Tatami\Components\AssetsLoader\Renderers;
use Nette\Utils\Html;
/**
 * Description of CssRenderer
 *
 * @author Martin
 */
class CssRenderer extends AssetsRenderer
{
    protected 
	$prefix = 'cssloader',
	$suffix = '.css',
	$assetFolder = 'css',
	$media = null
    ;

    public function setMedia($media)
    {
	$this->media = $media;
    }

    protected function getHtml($fileName)
    {
	return Html::el('link')->rel('stylesheet')->type('text/css')->media($this->media)->href($fileName);
    }
}