<?php
namespace Common\FormBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Form\FormBuilderInterface;
use \Common\FormBundle\Form\DataTransformer\StringToNumberArrayTransformer;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ZipType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->addViewTransformer(new StringToNumberArrayTransformer(array("zip1","zip2")))
            ->add('zip1','text',array_merge(array('max_length'=>3), $options['options']))
            ->add('zip2','text',array_merge(array('max_length'=>4), $options['options']));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'options' => array(),
            'error_bubbling' => false,
        ));
    }

    public function getName()
    {
        return 'zip';
    }
}
