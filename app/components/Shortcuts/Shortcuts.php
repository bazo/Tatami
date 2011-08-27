<?php
namespace Tatami\Components;
/**
 * Description of Shortcuts
 *
 * @author Martin
 */
class Shortcuts extends BaseControl
{
    public function render()
    {
	$this->template->setFile(__DIR__ . '/shortcuts.latte');
        $this->template->render();
    }
}