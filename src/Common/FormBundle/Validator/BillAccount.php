<?php
namespace Common\FormBundle\Validator;
use Symfony\Component\Validator\Constraints\Regex;

/**
 * @Annotation
 */
class BillAccount extends Regex
{
    public $message = "全角カタカナと（ ）のみ入力可能です";
    public $pattern = '/^[\s　ァ-ヶー()（）]+$/u';

    public function getRequiredOptions()
    {
        return array();
    }
}
