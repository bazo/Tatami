<?php
namespace Tatami\Components;
/**
 * Description of Shortcuts
 *
 * @author Martin
 */
class Shortcuts extends BaseControl
{
    private
	/** @var \ShortcutsManager */
	$shortcutsManager,
	    
	$showForm = false
    ;

    public function __construct(\Nette\ComponentModel\IContainer $parent, $name, \ShortcutsManager $shortcutsManager)
    {
	parent::__construct($parent, $name);
	$this->shortcutsManager = $shortcutsManager;
    }
    
    public function createComponentFormAddShortcut($name)
    {
	$form = new \Tatami\Forms\AjaxForm($this, $name);
	$form->addText('name', 'Name');
	$form->addSelect('module', 'Module', $this->shortcutsManager->getModules());
	$form->addSelect('presenter', 'Presenter', $this->shortcutsManager->getModules());
	$form->addSelect('action', 'Action', $this->shortcutsManager->getModules());
	$form->addSubmit('btnSave', 'OK')->onClick[] = callback($this, 'addShortcut');
    }
    
    public function addShortcut(\Nette\Forms\Controls\Button $button)
    {
	$values = $button->form->values;
	var_dump($values);
    }
    
    public function handleAddShortcut()
    {
	$this->showForm = true;
	$this->invalidateControl('form');
    }
    
    public function render()
    {
	$this->template->showForm = $this->showForm;
	$this->template->setFile(__DIR__ . '/shortcuts.latte');
        $this->template->render();
    }
}