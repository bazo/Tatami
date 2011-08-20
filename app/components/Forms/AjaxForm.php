<?php
namespace Tatami\Forms;
/**
 * AjaxForm
 * @author Martin Bazik
 */

class AjaxForm extends BaseForm
{
    public function __construct(\Nette\ComponentModel\IContainer $parent = null, $name = null)
    {
        parent::__construct($parent, $name);
        $this->getElementPrototype()->class('ajax');
    }
}