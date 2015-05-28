<?php
namespace Collector\AdminBundle\Admin;

use Common\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;

class StaffGroupAdmin extends Admin{

    protected $baseRoutePattern = 'staff/group';

    public function initialize()
    {
        parent::initialize();
        $this->setUniqid("staffGroupAdmin");
    }

    public function getNewInstance()
    {
        $class = $this->getClass();

        return new $class('', array());
    }

    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('name',null,array('label'=>'権限名'))
            ->add('roles', 'sonata_security_roles', array(
                'label'=>'権限設定',
                'expanded' => true,
                'multiple' => true,
                'required' => false,
                'choices' => array('ROLE_USER'=>'ユーザー')
            ))
        ;
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('name',null,array('label'=>'権限名'))
        ;
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('name',null,array('label'=>'権限名'))
            ->add('roles',null,array('label'=>'権限設定'))
        ;
    }

    protected function configureShowFields(ShowMapper $showMapper)
    {
    }
}