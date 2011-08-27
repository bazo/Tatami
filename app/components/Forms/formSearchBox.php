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

class SearchForm extends BaseForm
{
    public function __construct(IContainer $parent = null, $name = null)
    {
        parent::__construct($parent, $name);
        $this->addText('search', 'Search')->addRule(Form::FILLED, 'Please type something');
        $this->addSubmit('btnSearch', 'Search');
    }
}