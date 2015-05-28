<?php
namespace Common\FormBundle\Validator;
use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class UniqueUsername extends Constraint
{
    public $message = "この会員IDは既に使用されています";

    public function getRequiredOptions()
    {
        return array();
    }
}
