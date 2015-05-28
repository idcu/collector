<?php
namespace Collector\AdminBundle\Admin;

use Common\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Validator\ErrorElement;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Translator\FormLabelTranslatorStrategy;

class PressFileAdmin extends Admin{

    public function initialize()
    {
        parent::initialize();
        $this->setUniqid('PressFileAdmin');
    }

    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('file','sonata_media_type',array(
                'required' => true,
                'provider' => 'sonata.media.provider.file',
                'context'  => 'default'))
            ->add('fileTitle',null,array('required' => false))
            ->add('fileSource',null,array('required' => false))
        ;
    }

    protected function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper
            ->add('file','file')
            ->add('fileTitle')
            ->add('fileSource')
        ;
    }
}