<?php
namespace Common\AdminBundle\Admin;

use Sonata\AdminBundle\Admin\Admin as BaseAdmin;
use Common\AdminBundle\Translator\FormLabelTranslatorStrategy;
use Common\AdminBundle\Source\DoctrineORMQuerySourceIterator;
use Sonata\DoctrineORMAdminBundle\Datagrid\ProxyQuery;

class Admin extends BaseAdmin{

    public function initialize()
    {
        parent::initialize();
        $this->setLabelTranslatorStrategy(new FormLabelTranslatorStrategy());
        $this->formOptions = array(
            'validation_groups' => 'admin'
        );
    }

    public function getExportFormats()
    {
        return array(
            'csv'
        );
    }

    public function getDataSourceIterator()
    {
        $datagrid = $this->getDatagrid();
        $datagrid->buildPager();
        $query = $datagrid->getQuery();
        $query->select('DISTINCT ' . $query->getRootAlias());
        $query->setFirstResult(null);
        $query->setMaxResults(null);
        $dataSourceIterator = new DoctrineORMQuerySourceIterator($query instanceof ProxyQuery ? $query->getQuery() : $query, $this->getExportFields(),'Y-m-d H:i:s');
        $dataSourceIterator->setCenter($datagrid->getCenter());
        return $dataSourceIterator;
    }
} 