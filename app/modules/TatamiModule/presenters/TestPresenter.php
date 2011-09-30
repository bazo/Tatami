<?php
namespace TatamiModule;
/**
 * Description of UsersPresenter
 *
 * @author Martin
 */
class TestPresenter extends \Tatami\Presenters\BasePresenter
{
    protected function createComponentFormTest($name)
    {
	$form = new \Tatami\Forms\BaseForm($this, $name);
	$form->addUrl('url', 'Url')->changeValidationMessage('test');
	$form->addEmail('email', 'email');
	$form->addNumber('number', 'Number');
	$form->addRange('range', 'range', 0, 100, 1);
	$form->addColor('color', 'color');
	$form->addDateTime('datetime', 'datetime');
	$form->addDateTimeLocal('datetimelocal', 'datetimelocal');
	$form->addTime('time', 'time');
	$form->addMonth('month', 'month');
	$form->addWeek('week', 'week');
	
	$form->addSubmit('btnSubmit');
	$form->onSuccess[] = function(\Nette\Application\UI\Form $form){
	  var_dump($form->values);  
	};
    }
    
    public function handleTest()
    {
	set_time_limit(0);
	$limit = 100000;
	$params = $this->context->params['database'];
	for($i = 0; $i <= $limit; $i++)
	{
	    $conn = mysql_connect($params['host'], $params['user'], $params['password']);
	    mysql_select_db($params['dbname']);
	    $user = array(
		'name' => 'meno'.$i,
		'password' => 'heslo'.$i,
		'email' => 'email'.$i,
		'created' => '2011-09-12 23:03:50',
		'role_id' => 1
	    );
	    $query = sprintf('insert into tatami_user (name, password, email, created, role_id) VALUES("%s", "%s", "%s", %s, %s)',
		$user['name'], $user['password'], $user['email'], 'NOW()', $user['role_id']
		    );
	    $res = mysql_query($query);
	    if($res == false)
	    {
		var_dump($query, mysql_error());exit;
	    }
	    
	    echo $user['name'].' added'."\n";
	}
    }
    
    public function handleTestTitle()
    {
	$this->template->test = 'TEST';
	$this->invalidateControl('title');
    }
    
    protected function createComponentGrid($name)
    {
	$grid = new \Gridder\Gridder($this, $name);
	$grid->setTranslator($this->context->translator);
	
	$persister = new \Gridder\Persisters\SessionPersister($this->getSession('gridder'));
	$grid->setPersister($persister);
	
	$array = array();
	$start = microtime();
	for($i = 1; $i <= 2000; $i++)
	{
	    $array[] = array(
		'id' => (int)$i,
		'name' => 'name'.$i
	    );
	}
	$stop = microtime();
	\Nette\Diagnostics\Debugger::fireLog($stop - $start);
	
	
	$dataSource = new \Gridder\Sources\ArraySource($array);
	$dataSource->setPrimaryKey('id');
	
	$grid->setDataSource($dataSource);
	
	$grid->addColumn('id')->setAlias('Idecko')->setFilter('text');
	
	$grid->addColumn('name')->setFilter('text');
	/*
	$ac = $grid->addActionColumn('actions');
	$ac->addAction('test', 'delete!');
	$ac->addAction('test2', 'delete!');
	$ac->addAction('test3', 'delete!');
	*/
	/*
	$grid->addOperation('test', function($records){
	    $message = sprintf('You have selected %s records in method %s', count($records), __FUNCTION__);
	    $type = 'ok';
	    return new \Gridder\Message($message, $type);
	})->setAlias('TEST');
	
	$operation = new \Gridder\Operation('ohohoo');
	$callback = callback($this, 'mehehe');
	$operation->setAlias('BUBUBU')->setCallback($callback);
	
	$grid->addOperation($operation);
	 * 
	 */
    }
    
    function mehehe($records)
    {
	$message = sprintf('You have selected %s records in method %s', count($records), __FUNCTION__);
	$type = 'ok';
	//return new \Gridder\Message($message, $type);
	$this->flashMessage($message, $type);
	$this->invalidateControl('flash');
    }
    
    public function handleDelete($id)
    {
	var_dump($id);
    }
    
}