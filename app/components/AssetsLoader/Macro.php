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
    private static function prepareMacro(MacroNode $node, PhpWriter $writer)
    {
	$word  = $node->tokenizer->fetchWord();
	if(Strings::startsWith($word, "'") and Strings::endsWith($word, "'"))
	{
	    $result = $writer->write("\$assetsLoader = \$control->getWidget('assetsLoader');");
	    $param = $node->args;
	}
	else
	{
	    $param = $writer->formatArray();
		if (strpos($node->args, '=>') === FALSE) {
			$param = substr($param, 6, -1); // removes array()
		}
	    $module = $word;
	    $result = $writer->write("\$assetsLoader = \$control->getWidget('assetsLoader')->setModule('$module');");
	}
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