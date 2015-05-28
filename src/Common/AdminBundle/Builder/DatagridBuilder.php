<?php
namespace Common\AdminBundle\Builder;

use Common\ConstantBundle\Utility\Center;
use Sonata\DoctrineORMAdminBundle\Builder\DatagridBuilder as DatagridBuilderBase;
use Sonata\DoctrineORMAdminBundle\Datagrid\Pager;
use Common\AdminBundle\Datagrid\Datagrid;
use Sonata\AdminBundle\Admin\AdminInterface;

class DatagridBuilder extends DatagridBuilderBase{

    protected $center;

    public function setCenter(Center $center)
    {
        $this->center = $center;
    }

    public function getBaseDatagrid(AdminInterface $admin, array $values = array())
    {
        $pager = new Pager;
        $pager->setCountColumn($admin->getModelManager()->getIdentifierFieldNames($admin->getClass()));

        $defaultOptions = array();
        if ($this->csrfTokenEnabled) {
            $defaultOptions['csrf_protection'] = false;
        }

        $formBuilder = $this->formFactory->createNamedBuilder('filter', 'form', array(), $defaultOptions);

        $datagrid = new Datagrid($admin->createQuery(), $admin->getList(), $pager, $formBuilder, $values);
        $datagrid->setCenter($this->center);
        return $datagrid;
    }
} 