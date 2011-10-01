<?php
namespace Tatami\Tools;
use Nette\Mail\IMailer;
use Nette\Mail\Message;
/**
 * Description of NullMailer
 *
 * @author Martin
 */
class NullMailer implements IMailer 
{
    /**
     * Sends email.
     * @param  Message
     * @return void
     */
    function send(Message $mail)
    {
	
    }
}