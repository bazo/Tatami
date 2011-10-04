<?php
namespace Tatami\Widgets;
/**
 * Description of BaseWidget
 *
 * @author Martin
 */
abstract class BaseWidget extends \Nette\Application\UI\Control implements IWidget
{
    
    protected 
        $templateFile = 'widget.phtml',
	$caption = 'Example'
    ;
    
    public function render()
    {
        $this->template->setFile(\dirname($this->reflection->getFilename()).'/'.$this->templateFile);
	$this->template->caption = $this->caption;
        $this->template->render();
    }
}