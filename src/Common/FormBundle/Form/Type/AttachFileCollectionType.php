<?php
namespace Common\FormBundle\Form\Type;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormInterface;
use Common\MediaBundle\Entity\Media;
//use Sonata\MediaBundle\Provider\Pool;
//use Sonata\MediaBundle\Form\DataTransformer\ProviderDataTransformer;
use Symfony\Component\Form\Extension\Core\EventListener\ResizeFormListener;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class AttachFileCollectionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $prototype = $builder->create($options['prototype_name'], 'attach_file', $options['options']);
        $builder->setAttribute('prototype', $prototype->getForm());
        $resizeListener = new ResizeFormListener(
            $options['type'],
            $options['options'],
            $options['allow_add'],
            $options['allow_delete']
        );
        $builder
            ->addEventSubscriber($resizeListener)
        ;
    }

    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars = array_replace($view->vars,array(
            'context' => $options['context'],
            'providerName' => $options['provider'],
        ));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'type' => 'attach_file',
            'provider' => 'sonata.media.provider.file',
            'context'  => 'default',
            'error_bubbling' => false,
            'prototype'     => true,
            'options'       => array(),
            'allow_add'     => true,
            'allow_delete'  => true,
        ));
    }

    public function getParent()
    {
        return 'collection';
    }

    public function getName()
    {
        return 'attach_file_collection';
    }
}