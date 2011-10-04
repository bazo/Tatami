<?php
namespace TatamiModule;
use Nette\Forms\Form, Nette\Utils\Html;
/**
 * Description of UsersPresenter
 *
 * @author Martin
 */
class InstallationPresenter extends \Tatami\Presenters\BasePresenter
{
    private 
	$currentStep = 1,
	/** @var \Tatami\Installer */
	$installer,
	$sessionSection = 'installer',
	/** @var \Nette\Http\SessionSection */
	$session,
	$totalSteps = 4,
	/** @var \Tatami\Services\MailBuilder */
	$mailBuilder,
            
        $skipSendingEmail = false
    ;

    public function startup()
    {
	parent::startup();
	$params = $this->context->params;
	if(isset($params['installed']) and $params['installed'] == true)
	{
	    $this->redirect(':tatami:login:');
	}
	$this->installer = new \Tatami\Installer($this->getService('robotLoader')->getIndexedClasses(), 
		$params['appDir'].'/config/config-default.neon',
                $this->context,
                $params['appDir'].'/config/config.neon');
        
	$this->session = $this->getSession($this->sessionSection);
	$this->mailBuilder = new \Tatami\Services\MailBuilder($this);
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
    }
    
    public function actionStep4()
    {
	$this->currentStep = 4;
	$entityManager = $this->getService('entityManager');
	$this->installer->setEntityManager($entityManager);
	$this->template->databaseInfo = $this->session->database;
	$this->template->userAccountInfo = $this->session->userAccount;
    }
    
    public function beforeRender()
    {
	parent::beforeRender();
	$this->template->currentStep = $this->currentStep;
	$this->template->totalSteps = $this->totalSteps;
    }
    
    public function goToPreviousStep()
    {
	$newStep = $this->currentStep - 1;
	$action = 'step'.$newStep;
	$this->redirect('installation:'.$action);
    }
    
    protected function createComponentFormDatabaseInfo($name)
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
		$this->redirect('installation:step2');
	    }
	    catch(Nette\FileNotFoundException $e)
	    {
		$button->form->addError($e->getMessage);
	    }
	    catch(Nette\InvalidStateException $e)
	    {
		$button->form->addError($e->getMessage);
	    }
	}
	else
	{
	    $button->form->addError('Database info incorrect');
	}
    }
    
    protected function createComponentFormUserAccount($name)
    {
	$form = new \Tatami\Forms\AjaxForm($this, $name);
	$form->addText('name', 'Name');
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
    
    protected function createComponentFormMailSetup($name)
    {
	$form = new \Tatami\Forms\BaseForm($this, $name);
	
	$form->addGroup('Basic settings')->setOption('container', Html::el('div'));
	$defaultFrom = 'tatami@'.$this->context->expand('%domain%');
	$form->addText('from', 'From')->setDefaultValue($defaultFrom)->setRequired('Please fill %name');
	$form->addText('fromName', 'From name')->setDefaultValue('Tatami');
	$mailers = array(
	  'mail' => 'mail() function - default',
	  'smtp' => 'custom smtp server'
	);
	$mailer = $form->addRadioList('mailer', 'Select mailer', $mailers)->setDefaultValue('mail');
	$mailer->addCondition(Form::EQUAL, 'smtp')
                ->toggle('smtp');
	$form->addCheckbox('ignore', 'Skip sending test email');
	$form->addGroup('Smtp settings')->setOption('container', Html::el('div')->id('smtp'));
	$smtp = $form->addContainer('smtp');
	$encryptions = array(
	    'none' => 'none',
	    'ssl' => 'SSL',
	    'tls' => 'TLS'
	);
	$smtp->addRadioList('secure', 'Select encryption', $encryptions);
	$smtp->addText('host', 'Server');
	$smtp->addText('port', 'Port');
	$smtp->addText('username', 'Username');
	$smtp->addText('password', 'Password');
	
	$form->addGroup('')->setOption('container', Html::el(''));
	$form->addSubmit('btnPrevious', 'Previous')->setValidationScope(false)->onClick[] = callback($this, 'goToPreviousStep');
	$form->addSubmit('btnNext', 'Next')->onClick[] = callback($this, 'formMailSetupSubmitted');
	
	if(isset($this->session->mailSettings))
	    $form->setDefaults($this->session->mailSettings);
    }
    
    public function formMailSetupSubmitted(\Nette\Forms\Controls\SubmitButton $button)
    {
	$values = $button->form->values;
	$this->session->mailSettings = $values;
	$to = $this->session->userAccount->email;
	$this->session->skipSendingEmails = $values->ignore;
	$canContinue = true;
	try
	{
            if(!$this->session->skipSendingEmails)
            {
                switch($values->mailer)
                {
                    case 'mail':
                        $this->installer->sendTestEmailSendmail($values->from, $to);
                    break;

                    case 'smtp':
                        $this->installer->sendTestEmailSmtp($values->from, $to, (array)$values->smtp);
                    break;
                }
            }
	}
	catch(\Nette\InvalidStateException $e)
	{
	    $button->form->addError($e->getMessage());
	    $canContinue = false;
	}
	catch(\Nette\Mail\SmtpException $e)
	{
	    $button->form->addError($e->getMessage());
	    $canContinue = false;
	}
	if($canContinue)
	{
	    $mailerSettings = $values;
	    try
	    {
		$this->installer->writeMailerSettings($mailerSettings);
		$this->redirect('installation:step4');
	    }
	    catch(Nette\FileNotFoundException $e)
	    {
		$button->form->addError($e->getMessage);
	    }
	    catch(Nette\InvalidStateException $e)
	    {
		$button->form->addError($e->getMessage);
	    }
	}
    }
    
    public function createComponentFormInstall($name)
    {
	$form = new \Tatami\Forms\AjaxForm($this, $name);
	$form->addSubmit('btnPrevious', 'Previous')->setValidationScope(false)->onClick[] = callback($this, 'goToPreviousStep');
	$form->addSubmit('btnInstall', 'Install')->onClick[] = callback($this, 'install');
    }
    
    public function install(\Nette\Forms\Controls\SubmitButton $button)
    {
	$name = $this->session->userAccount->name;
	$password = $this->session->userAccount->password;
	$email = $this->session->userAccount->email;
	try
	{
	    //$this->installer->installDatabase();
	    
	    $moduleManager = $this->context->getService('moduleManager');
	    $tatamiModule = new \Tatami\Modules\TatamiModule;
	    
	    $this->installer->clearDatabase();
	    
	    $moduleManager->installModule($tatamiModule);
	    $this->installer->installUserRoles();
	    $admin = $this->installer->createAdminUserAccount($name, $password, $email);
	    $moduleManager->activateModule($tatamiModule->getName());
	    $this->installer->writeInstalled();
            if(!$this->session->skipSendingEmails)
	    {
		$this->mailBuilder->buildInstallationEmail($admin)->send();
	    }
	    
	    $this->flash('Installation successful!');
	    $this->redirect(':tatami:login:');
	}
	catch(\PDOException $e)
        {
            $button->form->addError('Installation failed: '.$e->getMessage());
        }
    }
}