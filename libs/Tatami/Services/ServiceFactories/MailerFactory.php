<?php
namespace Tatami\ServiceFactories;
use Nette\DI;
/**
 * Description of MailerFactory
 *
 * @author Martin
 */
class MailerFactory 
{
    public static function create(DI\Container $container)
    {
	$mailerType = $container->params['mail']['mailer'];
	switch($mailerType)
	{
	    case 'mail':
		$mailer = new \Nette\Mail\SendmailMailer();
	    break;
	
	    case 'smtp':
		$mailer = new \Nette\Mail\SmtpMailer($container->params['mail']['smtp']);
	    break;
	}
	return $mailer;
    }
}