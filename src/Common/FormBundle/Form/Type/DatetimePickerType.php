<?php
namespace Common\FormBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
* DatetimeType
*  full options
* $builder->add('updatedAt', 'datetimepicker', array( 'pickerOptions' =>
 array('format' => 'mm/dd/yyyy',
'weekStart' => 0,
'startDate' => date('m/d/Y'), //example
'endDate' => '01/01/3000', //example
'daysOfWeekDisabled' => '0,6', //example
'autoclose' => false,
'startView' => 'month',
'minView' => 'hour',
'maxView' => 'decade',
'todayBtn' => false,
'todayHighlight' => false,
'keyboardNavigation' => true,
'language' => 'en',
'forceParse' => true,
'minuteStep' => 5,
'pickerReferer ' => 'default', //deprecated
'pickerPosition' => 'bottom-right',
'viewSelect' => 'hour',
'showMeridian' => false,
'initialDate' => date('m/d/Y', 1577836800), //example
))) ;
*
*/
class DatetimePickerType extends AbstractType
{
    private $options;

    /**
    * Constructs
    *
    * @param array $options
    */
    public function __construct()
    {
        $this->options = array();
        
    }
    
    /**
    * {@inheritdoc}
    */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        
        $pickerOptions = $options['pickerOptions'];

        //Set automatically the language
        if(!isset($options['pickerOptions']['language']))
            $pickerOptions['language'] = \Locale::getDefault();
        if($pickerOptions['language'] == 'en')
            unset($pickerOptions['language']);
        
        //Set the defaut format of malot.fr/bootstrap-datetimepicker
        if(!isset($options['pickerOptions']['format']))
            $pickerOptions['format'] = 'yyyy/mm/dd hh:ii';


        $view->vars = array_replace($view->vars, array(
            'pickerOptions' => $pickerOptions,
        ));
    }

    /**
    * {@inheritdoc}
    */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $configs = $this->options;
        
        $resolver
            ->setDefaults(array(
                'widget' => 'single_text',
                'format' => function (Options $options, $value) use ($configs) {
                    if(isset($options['pickerOptions']['format']))
                        return DatetimePickerType::convertMalotToIntlFormater( $options['pickerOptions']['format'] );
                    else
                        return DatetimePickerType::convertMalotToIntlFormater( 'yyyy/mm/dd hh:ii' );
                },
                'pickerOptions' => array(),
            ));
    }

    /**
    * Convert the Bootstrap Datetimepicker date format to PHP date format
    */
    public static function convertMalotToIntlFormater($formatter)
    {
        $malotFormater  =  array("yyyy", "ss", "ii", "hh", "HH", "dd", "mm", "MM",   "yy");
        $intlFormater   =  array("yyyy", "ss", "mm", "HH", "hh", "dd", "MM", "MMMM", "yy");
        $return = str_replace($malotFormater, $intlFormater, $formatter);
        
        $malotFormater  =  array("p", "P", "s", "i", "h", "H", "d", "m", "M");
        $intlFormater   =  array("a", "a", "s", "m", "H", "h", "d", "M", "MMM");
        $return = str_replace($malotFormater, $intlFormater, $return);
        
        
        $patterns = preg_split('([\\\/.:_;,\s-\ ]{1})', $formatter);
        $exits = array();

        foreach ($patterns as $index => $val) {
            switch ($val) {
                case 'yyyy':
                    $exits[$val] = 'yyyy';
                    break;
                case 'ss':
                    $exits[$val] = 'ss';
                    break;
                case 'ii':
                    $exits[$val] = 'mm';
                    break;
                case 'hh':
                    $exits[$val] = 'HH';
                    break;
                case 'HH':
                    $exits[$val] = 'hh';
                    break;
                case 'dd':
                    $exits[$val] = 'dd';
                    break;
                case 'mm':
                    $exits[$val] = 'MM';
                    break;
                case 'MM':
                    $exits[$val] = 'MMMM';
                    break;
                case 'p':
                case 'P':
                    $exits[$val] = 'a';
                    break;
                case 's':
                    $exits[$val] = 's';
                    break;
                case 'i':
                    $exits[$val] = 'm';
                    break;
                case 'h':
                    $exits[$val] = 'H';
                    break;
                case 'H':
                    $exits[$val] = 'h';
                    break;
                case 'd':
                    $exits[$val] = 'd';
                    break;
                case 'm':
                    $exits[$val] = 'M';
                    break;
                case 'M':
                    $exits[$val] = 'MMM';
                    break;
                
            }
        }

        return str_replace(array_keys($exits), array_values($exits), $formatter);
        
    }

    
    
    /**
    * {@inheritdoc}
    */
    public function getParent()
    {
        return 'datetime';
    }
    
    /**
    * {@inheritdoc}
    */
    public function getName()
    {
        return 'datetimePicker';
    }

}
