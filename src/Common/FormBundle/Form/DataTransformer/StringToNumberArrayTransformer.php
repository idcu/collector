<?php
namespace Common\FormBundle\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Symfony\Component\Form\Exception\UnexpectedTypeException;

class StringToNumberArrayTransformer implements DataTransformerInterface
{
    private $keys;

    public function __construct(array $keys)
    {
        $this->keys = $keys;
    }

    function transform($value)
    {
        if (null === $value) {
            return array();
        }

        if (!is_string($value)) {
            throw new UnexpectedTypeException($value, 'string');
        }
        $result = array();

        foreach (explode("-",$value) as $key =>$data) {
            $result[$this->keys[$key]] = $data;
        }
        return $result;
    }

    function reverseTransform($value)
    {
        if (!is_array($value)) {
            throw new UnexpectedTypeException($value, 'array');
        }

        $null_count = 0;
        foreach ($value as $data)
        {
            if ($data==null)
            {
                $null_count++;
                //return null;
            }
        }
        
        if ( count($value) == $null_count ) {
            return null;
        } else {
            return implode("-",$value);
        }
    }

}
