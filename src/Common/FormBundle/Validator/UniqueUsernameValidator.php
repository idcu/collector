<?php
namespace Common\FormBundle\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Common\ConstantBundle\Utility\Center;

class UniqueUsernameValidator extends ConstraintValidator
{
    protected $center;
    public function setCenter(Center $center)
    {
        $this->center = $center;
    }

    public function validate($value, Constraint $constraint)
    {
        if (sizeof($value) == 0) return true;
        //スペースがあるとセンター問合せ時にエラーになるのではじく
        if (preg_match('/(\s|　)/', $value)) return true;
        
        $result = $this->center->uniqueUsername($value);
        if ($result->validate!=="true") {
            $this->context->addViolation($constraint->message);
        }
        return true;
    }
}
