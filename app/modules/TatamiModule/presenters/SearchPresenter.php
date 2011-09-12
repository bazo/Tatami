<?php
/**
 * ModulesPresenter
 * @author Martin Bazik
 */
namespace TatamiModule;

class SearchPresenter extends \Tatami\Presenters\BackendPresenter
{
    public function actionDefault($search)
    {
	$searchResults = array();
	$this->template->search = $search;
	$this->template->searchResults = $searchResults;
    }
}