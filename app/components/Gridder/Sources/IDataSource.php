<?php
namespace Gridder\Sources;
/**
 *
 * @author Martin
 */
interface IDataSource 
{
    public function getResults();
    public function getTotalCount();
    public function limit($offset, $limit);
    public function getRecordsByIds($ids);
    public function getPrimaryKey();
    public function applyFilters($filters);
    public function supportsFiltering();
    public function supportsSorting();
}