<?php

namespace Common\FormBundle\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\DependencyInjection\ContainerInterface;

class NotBlankValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        if (false === $value || (empty($value) && '0' != $value) || (!is_array($value) && mb_ereg_match("^(　)+$", $value)) ) {
            $this->context->addViolation($constraint->message);
        }
    }
}
