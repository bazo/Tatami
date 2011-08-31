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
        $this->translator = $this->context->getService('Translator');
        $this->translator->setLang($this->lang);
    }

    protected function createComponentCss($name)
    {
	$params = $this->context->params;
	$basePath = $this->getHttpRequest()->getUrl()->basePath;
	$css = new \Tatami\Components\WebLoader\CssLoader($this, $name, $params['wwwDir'], $basePath);
        $css->sourcePath = $params['assetsDir'] . "/css";
        $css->tempUri = $this->getHttpRequest()->getUrl()->baseUrl . "webtemp";
        $css->tempPath = $params['wwwDir'] . "/webtemp";
    }

    protected function createComponentJs($name)
    {
	$params = $this->context->params;
        
	$js = new \Tatami\Components\WebLoader\JavaScriptLoader($this, $name);
        $js->tempUri = $this->getHttpRequest()->getUrl()->baseUrl . "webtemp";
        $js->sourcePath = $params['assetsDir'] . "/js";
	$js->tempPath = $params['wwwDir'] . "/webtemp";
    }
    
    //shortcut for setting the flash message and invalidating it
    public function flash($message, $type = 'ok')
    {
        $this->flashMessage($message, $type);
        $this->invalidateControl('flash');
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
}
