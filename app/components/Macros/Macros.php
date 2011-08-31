<?php
class Macros 
{
    public static function popup($content, $template)
    {
        $template->setFile(__DIR__. '/popup.latte');
        $template->content = $content;
        $params = $template->getParams();
        $session = $params['control']->getPresenter()->getContext()->getService('session')->getSection('popup');
        $session->html = $template->__toString();
    }
}