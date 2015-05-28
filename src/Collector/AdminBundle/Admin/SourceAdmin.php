<?php
namespace Collector\AdminBundle\Admin;

use Common\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Validator\ErrorElement;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Translator\FormLabelTranslatorStrategy;

class SourceAdmin extends Admin
{
    protected $baseRoutePattern = 'source';
    protected $selection;
    public function setSelection(Selection $selection){
        $this->selection = $selection;
    }

    public function initialize()
    {
        parent::initialize();
        $this->setUniqid('SourceAdmin');
    }

    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('sourceName','text',array('required' => true))
            ->add('sourceUrl','text',array('required' => true))
            ->add('sourceRank','integer',array('required' => true))
        ;
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('sourceName')
            ->add('sourceUrl')
        ;
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('sourceName')
            ->add('sourceUrl','url')
            ->add('sourceRank')
            ->add('_action', 'actions', array(
                'actions' => array(
                    'show' => array(),
                    'edit' => array(),
                    'delete' => array(),
                )
            ))
        ;
    }

    protected function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper
            ->add('sourceName')
            ->add('sourceUrl','url')
            ->add('sourceRank')
        ;
    }
}