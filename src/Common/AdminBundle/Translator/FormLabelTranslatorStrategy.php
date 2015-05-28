<?php

namespace Common\AdminBundle\Translator;

use Sonata\AdminBundle\Translator\LabelTranslatorStrategyInterface;

class FormLabelTranslatorStrategy implements LabelTranslatorStrategyInterface
{
    public function getLabel($label, $context = '', $type = '')
    {
        $label = str_replace(array('_', '.'), ' ', $label);
        $label = strtolower(preg_replace('~(?<=\\w)([A-Z])~', '_$1', $label));

        return trim(ucfirst(str_replace('_', ' ', $label)));
    }
}
