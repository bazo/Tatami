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
        $this->addText('email', 'Email:')->setRequired('Please provide your %name');
        $this->addPassword('password', 'Password:')->setRequired('Please provide your %name');
        $this->addCheckbox('remember', 'Remember me');
        $this->addSubmit('btnLogin', 'Login');
    }
}
