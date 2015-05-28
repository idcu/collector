<?php
namespace Common\FormBundle\Validator;
use Symfony\Component\Validator\Constraints\Regex;

/**
 * @Annotation
 */
class Kana extends Regex
{
    public $message = "全角カタカナのみ入力可能です";
    public $pattern = '/^[ァ-ヶー]+$/u';

    public function getRequiredOptions()
    {
        return array();
    }
}
