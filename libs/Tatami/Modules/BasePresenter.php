<?php
namespace TatamiModule;
use Tatami\Forms;

abstract class BasePresenter extends \Nette\Application\UI\Presenter
{
    public
        /** @persistent */
        $lang
    ;

    protected
        /** @var \Tatami\Tools\Translator */
        $translator,

        /** @var \Nette\Http\User */
        $user
    ;

    public function startup()
    {
        parent::startup();
        if (!isset($this->lang))
        {

          $this->lang = $this->getHttpRequest()->detectLanguage(array('sk', 'en'));
          if($this->lang == null) $this->lang = 'en';
          $this->canonicalize();
        }
        $this->translator = $this->context->getService('translator');
        $this->translator->setLang($this->lang);
    }
    
    //shortcut for setting the flash message and invalidating it
    public function flash($message, $type = 'ok')
    {
        $this->flashMessage($message, $type);
        $this->invalidateControl('flash');
    }

    public function getShortName()
    {
	$start = strpos($this->name, ':') + 1;
	return substr($this->name, $start);
    }
    
    //shortcut for setting the popup layout and invalidating popup
    public function popupOn()
    {
        if($this->isAjax())
        {
            $this->getSession('original_view')->original_view =  $this->getView();
            $this->setLayout('popup');
            $this->invalidateControl('popup');
        }
    }

    //shortcut for setting the normal layout and invalidating popup
    public function popupOff()
    {
        if($this->isAjax())
        {
            $this->setLayout('layout');
            $this->setView('default');
            $this->invalidateControl('popup');
        }
        else $this->redirect ('this');
    }

    public function handleClosePopup()
    {
        $this->popupOff();
    }
    
    public function beforeRender()
    {
        $this->template->setTranslator($this->translator);
    }
    
    public function createComponentPopup($name)
    {
        $popup = new \Tatami\Components\Popup($this, $name);
        $popup->setContext($this->context);
    }
    
    public function templatePrepareFilters($template)
    {
        $latte = new \Nette\Latte\Engine;
        $template->registerFilter($latte);
        $set = \Nette\Latte\Macros\MacroSet::install($latte->parser);
        $set->addMacro('css', callback('\Tatami\Components\AssetsLoader\Macro', 'macroCss'));
	$set->addMacro('js', callback('\Tatami\Components\AssetsLoader\Macro', 'macroJs'));
    }
    
    protected function createComponentAssetsLoader($name)
    {
	$params = $this->context->params;
	$assetsLoader = new \Tatami\Components\AssetsLoader\AssetsLoader($this, $name);
	$assetsLoader->setModule('tatami')
		->setModuleManager($this->context->moduleManager)
		    ->setWwwDir($params['wwwDir'])
		    ->setBasePath($basePath = $this->getHttpRequest()->getUrl()->basePath)
		    ->setWebtemp($params['wwwDir'] . "/webtemp")
		    ->setTempUrl($this->getHttpRequest()->getUrl()->baseUrl . "webtemp");
    }
}
