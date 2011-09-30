<?php
namespace Gridder;
use Gridder\Operation;
use Doctrine\ORM\EntityRepository;
use Nette\Application\UI\Control;
use Gridder\Persisters\IPersister;
use Nette\Application\UI\Form;
use Nette\ComponentModel\IContainer;
/**
 * Description of EntityBrowser
 *
 * @author Martin
 */
class Gridder extends Control
{
    private
	/** @var Sources\IDataSource */
	$dataSource,
	/** @var ColumnMapper */
	$columnMapper,
	    
	$columns = array(),
	$actionColumns = array(),    
	$operations = array(),
	$filters,
	    
	$primaryKey,
	    
	$hasOperations,
	    
	/** @var array */
        $paginatorOptions = array(
            'displayedItems' => array(2, 5, 10, 20, 30, 40, 50, 100, 200, 500, 1000, 10000),
            'defaultItem' => '10'
        ),
	$translator,
	/** @var Persisters\Persister */
	$persister,
	    
	$itemsPerPage = 10,
	$page = 1,
	$presenter
    ;
    
    public 
	/** 
	 * @internal 
	 * @var bool
	 */
        $hasFilters,
	/** @var bool */
	$autoAddFilters = false
    ;

    public function __construct(IContainer $parent = NULL, $name = NULL)
    {
	parent::__construct($parent, $name);
	$presenter = $parent->getPresenter();
	$this->presenter =& $presenter;
	$this->columnMapper = new ColumnMapper;
    }

    public function setPersister(IPersister $persister)
    {
	$this->persister = $persister;
    }
    
    /**
     * Returns paginator options
     * @return array
     */
    public function getPaginatorOptions()
    {
        return $this->paginatorOptions;
    }
    
    /**
     * Set the translator
     * @param type $translator 
     */
    public function setTranslator($translator)
    {
	$this->translator = $translator;
	$this->template->setTranslator($translator);
    }

    /**
     * Set the datasource for the table rows
     * @param Sources\IDataSource $dataSource 
     */
    public function setDataSource(Sources\IDataSource $dataSource)
    {
	$this->dataSource = $dataSource;
	$this->primaryKey = $dataSource->getPrimaryKey();
	return $this;
    }
    
    /**
     * Sets the primary key for the records, overrides the datasource setting
     * @param string $primaryKey
     * @return Gridder 
     */
    public function setPrimaryKey($primaryKey)
    {
	$this->primaryKey = $primaryKey;
	return $this;
    }
        
    /**
     * Adds a column to show
     * @param type $name
     * @param type $type
     * @return Columns\BaseColumn
     */
    public function addColumn($name, $type = 'text')
    {
	$this->columns[] = $name;
	return $this->columnMapper->map($this, $name, $type, $this->autoAddFilters);
    }
    
    /**
     * Adds an action column
     * @param type $name
     * @return Columns\ActionColumn 
     */
    public function addActionColumn($name)
    {
	$this->actionColumns[] = $name;
	$actionColumn = new Columns\ActionColumn($this, $name);
	$actionColumn->setPresenter($this->presenter);
	return $actionColumn;
    }
    
    /**
     * Adds an operation
     * @param string|Operation $name
     * @param Closure|Callback $callback
     * @return Operation
     */
    public function addOperation($name, $callback = null)
    {
	$this->hasOperations = true;
	if($name instanceof Operation)
	{
	    if(in_array($name->getName(), array_keys($this->operations)))
	    {
		throw new Exception(sprintf('Operation with name %s already exists', $name));
	    }
	    $this->operations[$name->getName()] = $name;
	    return $this->operations[$name->getName()];
	}
	else
	{
	    if(in_array($name, array_keys($this->operations)))
	    {
		throw new Exception(sprintf('Operation with name %s already exists', $name));
	    }
	    $this->operations[$name] = new Operation($name, $callback);
	    
	    return $this->operations[$name];
	}
    }
    
    public function addRecordCheckbox($id)
    {
	if(!$this['form']->isSubmitted())
	{
	    $this['form']['records']->addCheckbox($id);
	    if(isset($this->persister->recordCheckboxes))
	    {
		$checkboxes = $this->persister->recordCheckboxes;
	    }
	    else
	    {
		$checkboxes = array();
	    }
	    $checkboxes[$id] = $id;
	    $this->persister->recordCheckboxes = $checkboxes;
	}
    }
    
    protected function createComponentForm($name)
    {
	$form = new Form($this, $name);
	$form->getElementPrototype()->class = 'ajax';
	$form->setTranslator($this->translator);
	
	if($this->hasOperations)
	{
	    $operations = array();
	    foreach($this->operations as $name => $operation)
	    {
		$operations[$name] = $operation->getAlias();
	    }
	    $form->addSelect('operation', 'Operation', $operations);
	    $form->addSubmit('btnExecuteOperation', 'Execute')->onClick[] = callback($this, 'executeOperation');
	    $records = $form->addContainer('records');
	    if($form->isSubmitted())
	    {
		if(isset($this->persister->recordCheckboxes))
		{
		    $checkboxes = $this->persister->recordCheckboxes;
		    foreach($checkboxes as $id)
		    {
			$records->addCheckbox($id); 
		    }
		}
	    }
	}
	
	if($this->hasFilters)
	{
	    
	    $filters = $form->addContainer('filters');
            foreach($this->getComponents(false, 'Gridder\Columns\IColumn') as $column)
            {
                if($column->hasFilter())
                {
                    $filters->addComponent($column->getFilter(), $column->name);
		    if($form->isSubmitted())
		    {
			$httpData = $form->getHttpData();
			if(isset($httpData['btnCancelFilters']))
			{
			    unset($this->persister->filters);
			    $filters[$column->name]->setValue(null);
			}
		    }
                    elseif (isset($this->persister->filters[$column->name]))
                    {
                        $filters[$column->name]->setDefaultValue($this->persister->filters[$column->name]->getValue());
                    }
                }
            }

            $form->addSubmit('btnApplyFilters', 'Apply filters')->onClick[] = callback($this, 'saveFilters');
            $form['btnApplyFilters']->getControlPrototype()->class = 'button apply';
            $form->addSubmit('btnCancelFilters', 'Cancel filters')->onClick[] = callback($this, 'cancelFilters');
            $form['btnCancelFilters']->getControlPrototype()->class = 'button cancel';
	}
    }
    
    public function executeOperation(\Nette\Forms\Controls\Button $button)
    {
	$values = $button->form->values;
	$operationName = $values->operation;
	$records = $values->records;
	$selectedRecordsIds = array_keys(array_filter((array)$records));
	$selectedRecords = $this->dataSource->getRecordsByIds($selectedRecordsIds);
	
	$operation = $this->operations[$operationName];
	$message = $operation->execute($selectedRecords);
	if($message instanceof Message)
	{
	    $this->flashMessage($message->getMessage(), $message->getType());
	    $this->invalidateControl('flash');
	}
    }
    
    public function saveFilters(\Nette\Forms\Controls\Button $button)
    {
	$values = $button->form->values;
	$filters = $values['filters'];
        $filterObjects = $this->filters;
        foreach($filters as $filter => $value)
        {
            $filterObjects[$filter] = $this->getComponent($filter)->getComponent('filter')->getFilter($value);//apply($this->ds, $value);
        }
        $this->persister->filters = $filterObjects;
	$this->invalidateControl();
    }
    
    public function cancelFilters(\Nette\Forms\Controls\Button $button)
    {
	$this->invalidateControl();
    }
    
    public function createComponentFormPaginator($name)
    {
	$form = new Form($this, $name);
	$form->getElementPrototype()->class = 'ajax';
	$options = array_combine(array_values($this->paginatorOptions['displayedItems']), $this->paginatorOptions['displayedItems']);
	$pageItems = array();
	for($i = 1; $i <= $this->persister->totalPages; $i++)
	{
	    $pageItems[$i] = $i;
	}
	$form->addSelect('page', 'Page', $pageItems)->setDefaultValue($this->persister->page);
	$form->addSelect('itemsPerPage', 'Items per page', $options);
	if(isset($this->persister->itemsPerPage))
	{
	    $form['itemsPerPage']->setDefaultValue($this->persister->itemsPerPage);
	}
	else
	{
	   $form['itemsPerPage']->setDefaultValue($this->paginatorOptions['defaultItem']);
	}
	    
	$form->addSubmit('btnSubmitPaginator')->getControlPrototype()->class = 'button apply';
	$form->onSuccess[] = callback($this, 'paginatorSubmitted');
    }
    
    public function paginatorSubmitted(Form $form)
    {
	unset($this->persister->recordCheckboxes);
	$values = $form->values;
	$this->itemsPerPage = (int)$values->itemsPerPage;
	$this->persister->itemsPerPage = $this->itemsPerPage;
	$this->persister->page = $this->page = (int)$values->page;
	$this->invalidateControl();
    }
    
    public function handleChangePage($page)
    {
	unset($this->persister->recordCheckboxes);
	$this->persister->page = $page;
	$this->invalidateControl();
    }
    
    public function handleReset()
    {
	$this->persister->reset();
	$this->invalidateControl();
    }
    
    public function render()
    {
	$this->template->setFile(__DIR__.'/template.latte');
	$this->template->columns = $this->columns;
	$this->template->actionColumns = $this->actionColumns;
	
	if(isset($this->persister->itemsPerPage))
	{
	    $this->itemsPerPage = $this->persister->itemsPerPage;
	}
	$totalCount = $this->dataSource->applyFilters($this->persister->filters)->getTotalCount();
	$this->persister->totalPages = $this->template->totalPages = (int)ceil($totalCount / $this->itemsPerPage);
	if(isset($this->persister->page))
	{
	    $this->page = $this->persister->page;
	}
	if($this->page > $this->template->totalPages)
	{
	    $this->page = $this->persister->page = 1;
	}
	$this->template->page = $this->page;
	$limit = $this->itemsPerPage;
        $offset = ($this->page - 1)*$limit;
	$rows = $this->dataSource->limit($offset, $limit)->getResults();
	
	$this->template->nextPage = $this->page + 1;
	$this->template->previousPage = $this->page - 1;
	$this->template->from = $offset + 1;
	$this->template->to = $offset + $limit;
	if($this->template->to > $totalCount)
	{
	    $this->template->to = $totalCount;
	}
	$this->template->totalRecords = $totalCount;
	$this->template->primaryKey = $this->primaryKey;
	$this->template->hasOperations = $this->hasOperations;
	$this->template->hasFilters = $this->hasFilters;
	$this->template->rows = $rows;
	$this->template->render();
    }
}