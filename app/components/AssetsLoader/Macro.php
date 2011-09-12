<?php
namespace Tatami\Components\AssetsLoader;
use Nette\Latte\MacroNode;
use Nette\Utils\Strings;
use Nette\Latte\PhpWriter;
/**
 * Description of Macro
 *
 * @author Martin
 */
class Macro 
{
    private static function parseModule($word)
    {
	$parts = explode(':', $word);
	return $parts[1];
    }
    
    private static function parseMedia($word)
    {
	$parts = explode(':\'', $word);
	if(!isset($parts[1])) $parts = explode(':"', $word);
	$media = $parts[1];
	$media = Strings::truncate($media, strlen($media) - 1, null);
	return $media;
    }


    private static function prepareMacro(MacroNode $node, PhpWriter $writer)
    {
	$media = null;
	while($node->tokenizer->hasNext())
	{
	    $words[] = $node->tokenizer->fetchWord();
	}
	foreach($words as $index => $word)
	{
	    if(Strings::startsWith($word, "module:"))
	    {
		$module = self::parseModule($word);
		unset($words[$index]);
	    }
	    if(Strings::startsWith($word, "media:"))
	    {
		$media = self::parseMedia($word);
		unset($words[$index]);
	    }
	}

	$param = implode(', ', $words);
	$result = $writer->write("\$assetsLoader = \$control->getWidget('assetsLoader');");
	if(isset($module))
	    $result .= $writer->write("\$assetsLoader->setModule('$module');");
	$result .= $writer->write("\$assetsLoader->setMedia('$media');");
	$result .= 'if ($assetsLoader instanceof Nette\Application\UI\IPartiallyRenderable) $assetsLoader->validateControl();';
	return array('php' => $result, 'param' => $param);
    }
    
    public static function macroCss(MacroNode $node, $writer) 
    {
	$result = self::prepareMacro($node, $writer);
	$php = $result['php'];
	$param = $result['param'];
	$php .= "\$assetsLoader->renderCss($param)";
	return $php;
    }
    
    public static function macroJs(MacroNode $node, $writer) 
    {
	$result = self::prepareMacro($node, $writer);
	$php = $result['php'];
	$param = $result['param'];
	$php .= "\$assetsLoader->renderJs($param)";
	return $php;
    }
}