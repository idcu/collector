<?php
namespace Collector\AdminBundle\Admin;

use Common\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Validator\ErrorElement;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Translator\FormLabelTranslatorStrategy;

class PressImageAdmin extends Admin{

    public function initialize()
    {
        parent::initialize();
        $this->setUniqid('PressImageAdmin');
    }

    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('image','sonata_media_type',array(
                'required' => true,
                'provider' => 'sonata.media.provider.image',
                'context'  => 'default'))
            ->add('imageTitle',null,array('required' => false))
            ->add('imageSource',null,array('required' => false))
        ;
    }

    protected function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper
            ->add('image','image')
            ->add('imageTitle')
            ->add('imageSource')
        ;
    }
}