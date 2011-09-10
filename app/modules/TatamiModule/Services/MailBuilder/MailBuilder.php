<?php
namespace Tatami\Services;
/**
 * Description of MailBuilder
 *
 * @author Martin
 */
use 
    Nette\Application\UI\Presenter, 
    Nette\Templating\FileTemplate, 
    Nette\Latte\Engine, 
    Nette\Latte\Macros\MacroSet,
    Nette\Mail\Message
;

class MailBuilder  
{
    private 
	/** @var Presenter */
	$presenter,
	/** @var FileTemplate */
	$template,
	$lang,
	$from,
	$fromName,
	/** @var \Nette\Mail\IMailer */
	$mailer 
    ;

    /**
     *
     * @param Presenter $presenter 
     */
    public function __construct(Presenter $presenter)
    {
	$this->presenter = $presenter;
	$this->from = $presenter->context->params['mail']['from'];
	$this->fromName = $presenter->context->params['mail']['fromName'];
	$this->lang = $presenter->lang;
	$this->template = $this->createTemplate($presenter);
	$this->mailer = $presenter->context->mailer;
    }
    
    /**
     *
     * @param Presenter $presenter
     * @return FileTemplate 
     */
    private function createTemplate(Presenter $presenter)
    {
	$template = new FileTemplate();
	$latte = new Engine;
        $template->registerFilter($latte);
        $set = MacroSet::install($latte->parser);
	$template->control = $template->presenter = $presenter;
	$template->setTranslator($presenter->context->translator);
	return $template;
    }
    
    /**
     *
     * @return Message 
     */
    private function prepareMessage()
    {
	$message = new Message;
	$message->setFrom($this->from, $this->fromName);
	$message->setMailer($this->mailer);
	return $message;
    }
    
    /**
     * Builds installation email message
     * @param \Entity\User $user
     * @return Message 
     */
    public function buildInstallationEmail(\Entity\User $user)
    {
	$this->template->user = $user;
	$this->template->setFile(__DIR__.'/templates/installation.latte');
	$text = $this->template->__toString();
	
	$message = $this->prepareMessage();
	$message->addTo($user->getEmail());
	$message->setSubject(_('Tatami installation successfull'));
	$message->setBody($text);
	return $message;
    }
    
    /**
     *
     * @param \Entity\User $user
     * @param \Entity\PasswordRecoveryToken $token
     * @return Message
     */
    public function buildPasswordRecoveryEmail(\Entity\User $user, \Entity\PasswordRecoveryToken $token)
    {
	$this->template->user = $user;
	$this->template->token = $token;
	$this->template->setFile(__DIR__.'/templates/passwordRecovery.latte');
	$text = $this->template->__toString();
	
	$message = $this->prepareMessage();
	$message->addTo($user->getEmail());
	$message->setSubject(_('Tatami password recovery'));
	$message->setBody($text);
	return $message;
    }
    
    /**
     *
     * @param \Entity\User $user
     * @return Message
     */
    public function buildPasswordChangeConfirmationEmail(\Entity\User $user)
    {
	$this->template->user = $user;
	$this->template->setFile(__DIR__.'/templates/passwordChangeConfirmation.latte');
	$text = $this->template->__toString();
	
	$message = $this->prepareMessage();
	$message->addTo($user->getEmail());
	$message->setSubject(_('Tatami password recovery'));
	$message->setBody($text);
	return $message;
    }
}