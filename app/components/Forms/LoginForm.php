<?php
/**
 * LoginForm
 * @author Martin Bazik
 */
namespace Tatami\Forms;
use Tatami\Forms,
    Nette\Forms\Form,
    \Nette\ComponentModel\IContainer
;

class LoginForm extends BaseForm
{
    public function __construct(IContainer $parent = null, $name = null)
    {
        parent::__construct($parent, $name);
        $this->addText('login', 'Username:')->addRule(Form::FILLED, 'Please provide a username.');
        $this->addPassword('password', 'Password:')->addRule(Form::FILLED, 'Please provide a password.');
        $this->addCheckbox('remember', 'Remember me');
        $this->addSubmit('btnLogin', 'Login');
    }
}
