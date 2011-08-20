<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of AdvancedSubmitButton
 *
 * @author Martin
 */
use Nette\Forms\Controls\SubmitButton;
class AdvancedSubmitButton extends SubmitButton {

        var $icon = null;

        var $showCaption = true;

        /**
         * @param  string  caption
         * @param  string  icon url
         */
        public function __construct($caption = NULL, $icon='save')
        {
                parent::__construct($caption);
                if($icon) {
                        $this->iconPrototype->class = 'icon icon-'.$icon;
                }
                $this->control->setName("button")->type = 'submit';
                $this->control->class[] = "button";
        }

        public function getIconPrototype() {
                if(!$this->icon) {
                        $this->icon = Html::el("span");
                }
                return $this->icon;
        }

        function hideCaption(){
                $this->showCaption = false;
                return $this;
        }

        public function getControl($caption = NULL){
                $control = parent::getControl($caption);
                $control->setText(""); // Delete content
                if($this->icon) {
                        $control->class[] = "hasIcon";
                        $this->icon->alt = $this->caption;
                        $control->add($this->icon);
                }
                if($this->showCaption) {
                        $control->class[] = "hasCaption";
                        $control->add(Html::el("span class=caption")->setHtml($this->caption));
                }

                // CSS classes:
                if($this->icon and !$this->showCaption) {
                        $control->class[] = "hasIconAndNoCaption";
                }elseif(!$this->icon and $this->showCaption) {
                        $control->class[] = "hasCaptionAndNoIcon";
                }elseif($this->icon and $this->showCaption){
                        $control->class[] = "hasCaptionAndIcon";
                }

                return $control;
        }

}
