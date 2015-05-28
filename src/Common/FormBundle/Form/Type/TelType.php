<?php
namespace Common\FormBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;
use \Common\FormBundle\Form\DataTransformer\StringToNumberArrayTransformer;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class TelType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->addViewTransformer(new StringToNumberArrayTransformer(array("tel1","tel2","tel3")))
            ->add('tel1','text',array('max_length' => 4) + $options['options'])
            ->add('tel2','text',array('max_length' => 4) + $options['options'])
            ->add('tel3','text',array('max_length' => 4) + $options['options']);
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
        return 'tel';
    }
}
