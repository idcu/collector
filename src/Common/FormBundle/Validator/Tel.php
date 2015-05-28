<?php
namespace Common\FormBundle\Validator;
use Symfony\Component\Validator\Constraints\Regex;

/**
 * @Annotation
 */
class Tel extends Regex
{
    public $message = "電話番号が不正です";
    public $pattern = '/^\d{2,4}-\d{2,4}-\d{4}$/';

    public function getRequiredOptions()
    {
        return array();
    }
}