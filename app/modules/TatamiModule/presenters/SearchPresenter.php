<?php
/**
 * ModulesPresenter
 * @author Martin Bazik
 */
namespace TatamiModule;

class SearchPresenter extends \Tatami\Modules\ModulePresenter
{
    public function actionDefault($search)
    {
	$searchResults = array();
	$this->template->search = $search;
	$this->template->searchResults = $searchResults;
    }
}