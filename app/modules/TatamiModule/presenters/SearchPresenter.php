<?php
/**
 * ModulesPresenter
 * @author Martin Bazik
 */
namespace TatamiModule;

class SearchPresenter extends BasePresenter
{
    public function actionDefault($search)
    {
	$searchResults = array();
	$this->template->search = $search;
	$this->template->searchResults = $searchResults;
    }
}