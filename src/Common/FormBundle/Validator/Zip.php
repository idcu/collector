<?php
namespace Common\FormBundle\Validator;
use Symfony\Component\Validator\Constraints\Regex;

/**
 * @Annotation
 */
class Zip extends Regex
{
    public $message = "郵便番号が不正です";
    public $pattern = '/^\d{3}\-\d{4}$/';

    public function getRequiredOptions()
    {
        return array();
    }
}
 
