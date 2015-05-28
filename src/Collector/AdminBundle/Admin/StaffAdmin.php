<?php
namespace Collector\AdminBundle\Admin;

use Common\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;

class StaffAdmin extends Admin{

    protected $baseRoutePattern = 'staff';

    public function initialize()
    {
        parent::initialize();
        $this->setUniqid("staffAdmin");
    }

    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('username','text',array('label'=>'オペレータID'))
            ->add('plainPassword','repeated',array('required' => true,'type' => 'password'))
            ->add('lastName','text',array('required' => false))
            ->add('firstName','text',array('required' => false))
            ->add('email','text')
            ->add('signature','textarea',array('required' => false))
            ->add('enabled');
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('username',null,array('label'=>'オペレータID'))
            ->add('lastName')
            ->add('firstName')
            ->add('enabled')
        ;
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('username',null,array('label'=>'オペレータID'))
            ->addIdentifier('lastName')
            ->addIdentifier('firstName')
            ->add('enabled',null,array('label'=>'使用可'))
            ->add('_action', 'actions', array(
                'actions' => array(
                    'show' => array(),
                    'edit' => array(),
                    'delete' => array(),
                )
            ));
    }

    protected function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper
            ->add('username')
            ->add('lastName')
            ->add('firstName')
            ->add('signature')
            ->add('email')
            ->add('enabled')
        ;
    }
}