<?php
namespace Tatami\Tools;
/**
 * Translator
 *
 * @author Martin Bazik
 */
class Translator implements \Nette\Localization\ITranslator
{
    private $lang;

    public function translate($message, $count = NULL)
    {
        return $message;
    }

    public function setLang($lang)
    {
        $this->lang = $lang;
    }
}
?>
