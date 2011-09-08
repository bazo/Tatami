<?php
namespace TatamiModule;
/**
 * Description of UsersPresenter
 *
 * @author Martin
 */
class InstallationPresenter extends BasePresenter
{
    private 
	$currentStep = 1,
	/** @var \Tatami\Installer */
	$installer,
	$sessionSection = 'installer',
	/** @var \Nette\Http\SessionSection */
	$session,
	$allSteps = 3
    ;

    public function startup()
    {
	parent::startup();
	$this->installer = new \Tatami\Installer($this->getService('robotLoader')->getIndexedClasses(), 
		$this->context->params['appDir'].'/config/config-default.neon',
                $this->context,
                $this->context->params['appDir'].'/config/config.neon');
        
	$this->session = $this->getSession($this->sessionSection);
    }

    public function actionDefault()
    {
	$this->redirect('installation:step1');
    }
    
    public function actionStep1()
    {
	$this->currentStep = 1;
        $this->installer->checkFolders();
	$databaseInfo = $this->installer->readDatabaseSettings();
	if(!empty($databaseInfo))
	    $this->session->database = $databaseInfo;
    }
    
    public function actionStep2()
    {
	$this->currentStep = 2;
    }
    
    public function actionStep3()
    {
	$this->currentStep = 3;
	$entityManager = $this->getService('EntityManager');
	$this->installer->setEntityManager($entityManager);
	$this->template->databaseInfo = $this->session->database;
	$this->template->userAccountInfo = $this->session->userAccount;
    }
    
    public function beforeRender()
    {
	parent::beforeRender();
	$this->template->currentStep = $this->currentStep;
	$this->template->allSteps = $this->allSteps;
    }
    
    public function goToPreviousStep()
    {
	$newStep = $this->currentStep - 1;
	$action = 'step'.$newStep;
	$this->redirect('installation:'.$action);
    }
    
    public function createComponentFormDatabaseInfo($name)
    {
	$form = new \Tatami\Forms\AjaxForm($this, $name);
	$form->addSelect('driver', 'Driver', $this->installer->getDatabaseDrivers());
	$form->addText('host', 'Host')->setDefaultValue('localhost');
	$form->addText('dbname', 'Database');
	$form->addText('prefix', 'Table prefix')->setDefaultValue('tatami_');
	$form->addText('user', 'User');
	$form->addText('password', 'Password');
	$form->addSubmit('btnNext', 'Next')->onClick[] = callback($this, 'formDatabaseInfoSubmitted');
	
	if(isset($this->session->database))
	    $form->setDefaults($this->session->database);
    }
    
    public function formDatabaseInfoSubmitted(\Nette\Forms\Controls\SubmitButton $button)
    {
	$values = $button->form->values;
	$this->session->database = $values;
	if($this->installer->testDatabaseConnection($values))
	{
	    try
	    {
		$this->installer->writeDatabaseSettings($values);
	    }
	    catch(Nette\FileNotFoundException $e)
	    {
		$button->form->addError($e->getMessage);
	    }
	    catch(Nette\InvalidStateException $e)
	    {
		$button->form->addError($e->getMessage);
	    }
	    $this->redirect('installation:step2');
	}
	else
	{
	    $button->form->addError('Database info incorrect');
	}
    }
    
    public function createComponentFormUserAccount($name)
    {
	$form = new \Tatami\Forms\AjaxForm($this, $name);
	$form->addText('login', 'Login');
	$form->addText('password', 'Password');
	$form->addText('email', 'E-mail')->addRule(\Nette\Forms\Form::EMAIL, 'Please provide valid email address');
	$form->addSubmit('btnPrevious', 'Previous')->setValidationScope(false)->onClick[] = callback($this, 'goToPreviousStep');
	$form->addSubmit('btnNext', 'Next')->onClick[] = callback($this, 'formUserAccountSubmitted');
	
	if(isset($this->session->userAccount))
	    $form->setDefaults($this->session->userAccount);
    }
    
    public function formUserAccountSubmitted(\Nette\Forms\Controls\SubmitButton $button)
    {
	$values = $button->form->values;
	$this->session->userAccount = $values;
	$this->redirect('installation:step3');
    }
    
    public function createComponentFormInstall($name)
    {
	$form = new \Tatami\Forms\AjaxForm($this, $name);
	$form->addSubmit('btnPrevious', 'Previous')->setValidationScope(false)->onClick[] = callback($this, 'goToPreviousStep');
	$form->addSubmit('btnInstall', 'Install')->onClick[] = callback($this, 'install');
    }
    
    public function install(\Nette\Forms\Controls\SubmitButton $button)
    {
	$login = $this->session->userAccount->login;
	$password = $this->session->userAccount->password;
	$email = $this->session->userAccount->email;
	try
	{
	    $this->installer->installDatabase();
	    $this->installer->createAdminUserAccount($login, $password, $email);
	    $this->installer->writeInstalled();
	    $this->flash('Installation successful!');
	    $this->redirect(':tatami:login:');
	}
	catch(\PDOException $e)
        {
            $button->form->addError('Installation failed: '.$e->getMessage());
        }
    }
    
    protected function createComponentCss($name)
    {
	$params = $this->context->params;
	$basePath = $this->getHttpRequest()->getUrl()->basePath;
	$css = new \Tatami\Components\WebLoader\CssLoader($this, $name, $params['wwwDir'], $basePath);
        $css->sourcePath = __DIR__ . "/../assets/css";
        $css->tempUri = $this->getHttpRequest()->getUrl()->baseUrl . "webtemp";
        $css->tempPath = $params['wwwDir'] . "/webtemp";
    }

    protected function createComponentJs($name)
    {
	$params = $this->context->params;
        
	$js = new \Tatami\Components\WebLoader\JavaScriptLoader($this, $name);
        $js->tempUri = $this->getHttpRequest()->getUrl()->baseUrl . "webtemp";
        $js->sourcePath = __DIR__ . "/../assets/js";
	$js->tempPath = $params['wwwDir'] . "/webtemp";
    }
}