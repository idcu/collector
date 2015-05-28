<?php
namespace Collector\AdminBundle\Admin;

use Common\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Validator\ErrorElement;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Translator\FormLabelTranslatorStrategy;

class PressAdmin extends Admin
{
    protected $parentAssociationMapping = 'company';
    protected $selection;
    public function setSelection(Selection $selection){
        $this->selection = $selection;
    }

    public function getBaseRoutePattern()
    {
        if (!$this->baseRoutePattern) {
            if ($this->getCode() == 'collector.press.admin' && !$this->isChild()) {
                $this->baseRoutePattern = 'press';
            } else if ($this->getCode() == 'collector.press.admin' && $this->isChild()) {
                $this->baseRoutePattern = sprintf('%s/{id}/%s',
                    $this->getParent()->getBaseRoutePattern(),
                    'press'
                );
            } else {
                throw new \RuntimeException('Invalid method call due to invalid state');
            }
        }

        return $this->baseRoutePattern;
    }


    public function initialize()
    {
        parent::initialize();
        $this->setUniqid('PressAdmin');
    }

    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('title','text',array('required' => true))
            ->add('subtitle','text',array('required' => false))
            ->add('content',null,array('required' => true))
            ->add('contentText',null,array('required' => true))
//            ->add('images', 'sonata_type_collection',array_merge($default,array()))
            ->add('disabled','checkbox')
        ;
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('id')
            ->add('title')
            ->add('contentText')
        ;
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('id')
            ->addIdentifier('title')
            ->add('source')
            ->add('publishDate','datetime',array('format' => 'Y/m/d H:i:s'))
            ->add('createdAt','datetime',array('format' => 'Y/m/d H:i:s'))
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
            ->add('source')
            ->add('pressUrl')
            ->add('title')
            ->add('subtitle')
            ->add('publishDate','datetime',array('format' => 'Y/m/d H:i:s'))
            ->add('company',null,array('template' => 'CollectorAdminBundle:Type:show_press_company.html.twig'))
            ->add('content',null,array('template' => 'CollectorAdminBundle:Type:show_press_content.html.twig'))
            ->add('contentText')
            ->add('images','array',array('template' => 'CollectorAdminBundle:Type:show_press_image.html.twig'))
            ->add('imageFiles','array',array('template' => 'CollectorAdminBundle:Type:show_press_image_file.html.twig'))
            ->add('files','array',array('template' => 'CollectorAdminBundle:Type:show_press_file.html.twig'))
            ->add('createdAt','datetime',array('format' => 'Y/m/d H:i:s'))
            ->add('disabled')
        ;
    }
}