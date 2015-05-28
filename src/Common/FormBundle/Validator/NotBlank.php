<?php

namespace Common\FormBundle\Validator;
use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 *
 * @api
 */
class NotBlank extends Constraint
{
    public $message = 'This value should not be blank';
}
