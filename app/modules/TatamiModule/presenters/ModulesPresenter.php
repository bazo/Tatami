<?php
/**
 * ModulesPresenter
 * @author Martin Bazik
 */
namespace TatamiModule;

class ModulesPresenter extends \Tatami\Modules\ModulePresenter
{

    public function createComponentGrid($name)
    {
        $grid = new \Tatami\Components\Datagrid\Datagrid($this, $name);
        $grid->setEntityManager($this->em);
        $grid->setDql('select m from Entity\Module m');

        $grid->addColumn('id', 'integer');
        $grid->addColumn('name', 'text')->setTextFilter();
    }

    /*
    public function createComponentGrid2($name)
    {
        $grid = new \Datagrid\Datagrid($this, $name);
        $dataSource = new \DataGrid\DataSources\Doctrine\QueryBuilder(
            $this->em->createQueryBuilder() //$em instanceof Doctrine\ORM\EntityManager
                ->select('m.id, m.name, m.active, m.installed') //columns to be used
                ->from('Entity\Module', 'm') //master table
        );

        //provide mapping betweeen DataGrid's column names and entity columns
        $dataSource->setMapping(array(
            'id' => 'm.id',
            'name'      => 'm.name',
            'active'     => 'm.active',
            'installed'      => 'm.installed'
        ));

        //finally, set datasource to DataGrid
        $grid->setDataSource($dataSource);

        //now we're working with mapped fields
        $grid->addColumn('id', 'id')->addFilter();
        $grid->addColumn('name', 'Name')->addFilter();
        $grid->addColumn('active', 'Active')->addFilter();
        $grid->addColumn('installed', 'Installed')->addFilter();

        $grid->keyName = 'id';
        $grid->addActionColumn('actions')->addAction('Deactivate', 'deactivate!');

        return $grid;
    }
     */
}