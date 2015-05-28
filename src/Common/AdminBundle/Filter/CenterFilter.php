<?php
namespace Common\AdminBundle\Filter;

use Sonata\DoctrineORMAdminBundle\Filter\Filter;
use Sonata\AdminBundle\Datagrid\ProxyQueryInterface;
use Common\ConstantBundle\Utility\Center;

abstract class CenterFilter extends Filter{

    protected $center;
    public function setCenter(Center $center)
    {
        $this->center = $center;
    }
}