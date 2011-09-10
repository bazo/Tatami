<?php
namespace TatamiModule;
use Nette\Utils\Html;
class LoginPresenter extends TatamiPresenter
{
    private 
	/** @var \Tatami\Services\EntityManager */
	$em,
	$tokenValidity = 3600,
	/** @var \Entity\PasswordRecoveryToken */    
	$token,
	$hideForm = false
    ;

    public function startup()
    {
        parent::startup();
	if($this->getUser()->isLoggedIn()) $this->redirect(':tatami:dashboard:');
	$this->em = $this->context->entityManager;
    }

    public function beforeRender() 
    {
	parent::beforeRender();
	$this->template->hideForm = $this->hideForm;
    }

    public function actionNewPassword($token)
    {
	$tokenEntity = $this->em->getRepository('PasswordRecoveryToken')->findOneBy(array('token' => $token));
	$tokenValidity = $this->tokenValidity;
	$now = new \DateTime;
	$tokenValid = true;
	if($tokenEntity != null) #token not found
	{
	    if(($now->getTimestamp() - $tokenEntity->getCreated()->getTimestamp() > $tokenValidity) 
		    or ($tokenEntity->getUsed() == true) ) #token expired
	    {
		$tokenValid = false;
	    }
	    else #user created more tokens and is using an old one
	    {
		$user = $tokenEntity->getUser();
		$tokens = $this->em->getRepository('PasswordRecoveryToken')
			->getNewerTokens($user->id, $tokenEntity->created);
		if(count($tokens) > 0) $tokenValid = false;
	    }
	}
	else {
	    $tokenValid = false;
	}
	if($tokenValid === false) $this->flash ('Token invalid', 'error');
	elseif($tokenEntity != null) $this->token = $tokenEntity;
	$this->hideForm = !$tokenValid;
    }
    
    protected function createComponentFormLogin($name)
    {
        $form = new \Tatami\Forms\LoginForm($this, $name);
        $form->onSuccess[] = callback($this, 'formLoginSubmitted');
    }

    public function formLoginSubmitted(\Nette\Application\UI\Form $form)
    {
        $values = $form->getValues();
	try
	{
	    $this->getUser()->login($values->email, $values->password);
	    if ($values->remember) 
	    {
		$this->getUser()->setExpiration('+ 14 days', FALSE);
	    } else {
		$this->getUser()->setExpiration('+ 60 minutes', TRUE);
	    }
	    $this->redirect(':tatami:dashboard:');
	}
	catch(\Nette\Security\AuthenticationException $e)
	{
	    $form->addError($e->getMessage());
	}
    }
    
    protected function createComponentFormRecoverPassword($name)
    {
	$form = new \Tatami\Forms\AjaxForm($this, $name);
	$form->addText('email', 'Email')
		->setOption('description', 'Enter your email')
		->setRequired('Please fill %name.')
		->addRule(\Nette\Forms\Form::EMAIL);
	$form->addSubmit('btnSubmit', 'Recover');
	$form->onSuccess[] = callback($this, 'formRecoverPasswordSubmitted');
    }
    
    public function formRecoverPasswordSubmitted(\Nette\Application\UI\Form $form)
    {
	$email = $form->values->email;
	$user = $this->em->getRepository('User')->findOneBy(array('email' => $email));
	if($user == null) 
	{
	    $form->addError(sprintf('Email %s is not registered in this system.', $email));
	    $this->invalidateControl('form');
	}
	else
	{
	    $token = new \Entity\PasswordRecoveryToken;
	    $token->setUser($user);
	    $this->em->persist($token);
	    $this->em->flush();
	    $this->hideForm = true;
	    $this->mailBuilder->buildPasswordRecoveryEmail($user, $token)->send();
	    $this->flash('A message with instructions on how to reset your password has been sent to your email.');
	    $this->invalidateControl('form');
	    
	    #send the link to email
	    
	}
    }
    
    public function createComponentFormChangePassword($name)
    {
	$form = new \Tatami\Forms\AjaxForm($this, $name);
	$form->addHidden('userId', $this->token->getUser()->getId());
	$form->addProtection('Time limit 30min has expired. Send the form again', 1800);
	$form->addPassword('password', 'New password')->setRequired('Fill your new password');
	$form->addSubmit('btnSubmit', 'Save');
	$form->onSuccess[] = callback($this, 'formChangePasswordSubmitted');
    }
    
    public function formChangePasswordSubmitted(\Nette\Application\UI\Form $form)
    {
	$values = $form->values;
	$password = $values->password;
	$userId = $values->userId;
	$user = $this->em->getRepository('User')->find($userId);
	try{
	    $user->setPassword($password);
	    $this->em->persist($user);
	    
	    $this->token->setUsed(true);
	    $this->em->persist($this->token);
	    $this->em->flush();

	    $this->token = null;
	    
	    $this->mailBuilder->buildPasswordChangeConfirmationEmail($user)->send();
	    $this->flash('Password changed successfully!');
	    $this->redirect(':tatami:login:');
	}
	catch(Tatami\ValidationException $e)
	{
	    $form->addError($e->getMessage());
	    $this->invalidateControl('form');
	}
    }
}