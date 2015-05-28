<?php
namespace Common\FormBundle\Form\Type;

use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Sonata\AdminBundle\Form\Type\Filter\DateTimeRangeType as BaseType;

class DatetimePickerRangeType extends BaseType {

    public function getName()
    {
        return 'datetimePickerRange';
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'field_type'=>'datetimePicker',
            'field_options'=>array(
                'pickerOptions' => array(
                    'format' => 'yyyy/mm/dd',
                    'minView' => 4
                ))
        ));
    }
}