<?php
namespace Common\FormBundle\Validator;
use Symfony\Component\Validator\Constraints\Regex;

/**
 * @Annotation
 */
class AlphaNum extends Regex
{
    public $message = "半角英数字のみ入力可能です";
    public $pattern = '/^[0-9a-zA-Z]*$/';

    public function getRequiredOptions()
    {
        return array();
    }
}
