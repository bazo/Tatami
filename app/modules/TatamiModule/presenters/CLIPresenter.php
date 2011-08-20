<?php
/**
 * CLIPresenter
 * @author Martin Bazik
 */
namespace TatamiModule;

use Nette\Callback;

class CLIPresenter extends BasePresenter
{
    /** TODO: cele zle */
    private $callbacks = array(
        'install' => 'install'
    );

    public function actionDefault()
    {
        $params = $this->getParam();
        unset($params['action']);
        foreach($params as $param => $value)
        {
            if(\in_array($param, $this->callbacks))
            {
                \call_user_func_array(\callback($this, $this->callbacks[$param]), array('param' => $value));
            }
        }

        $this->terminate();
    }

    public function install($param)
    {
        \Models\InstallModel::Install();
    }

}