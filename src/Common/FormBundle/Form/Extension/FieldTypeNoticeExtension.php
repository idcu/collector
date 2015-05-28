<?php
namespace Common\FormBundle\Form\Extension;

use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class FieldTypeNoticeExtension extends AbstractTypeExtension
{
    public function buildView(FormView $view, FormInterface $form,array $options)
    {
        if (array_key_exists('notice', $options)) {
            $view->vars['notice'] = $options['notice'];
        }
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setOptional(array('notice'));
    }

    public function getExtendedType()
    {
        return 'form';
    }
}