<?php
namespace Common\AdminBundle\Filter;

use Sonata\AdminBundle\Form\Type\Filter\ChoiceType;
use Sonata\AdminBundle\Datagrid\ProxyQueryInterface;

class CenterStringFilter extends CenterFilter{

    public function filter(ProxyQueryInterface $queryBuilder, $alias, $field, $data)
    {
        if (!$data || !is_array($data) || !array_key_exists('value', $data)) {
            return;
        }

        $data['value'] = trim($data['value']);

        if (strlen($data['value']) == 0) {
            return;
        }

        $data['type'] = !isset($data['type']) ?  ChoiceType::TYPE_CONTAINS : $data['type'];

        $operator = $this->getOperator((int) $data['type']);
        if (!$operator) {
            $operator = 'LIKE';
        }
        $results = $this->getCenterResult($field, $operator,$data['value']);
        $parameterName = $this->getNewParameterName($queryBuilder);
        if (sizeof($results) > 0)
        {
            $this->applyWhere($queryBuilder, sprintf('%s.atpressId in (:%s)', $alias, $parameterName));
            $queryBuilder->setParameter($parameterName, $results);
        }
        else
        {
            $this->applyWhere($queryBuilder,'0=1');
        }
    }

    protected  function getCenterResult($field,$operator,$value)
    {
        $centerCondition['type'] = 'string';
        $centerCondition['operator'] = $operator;
        $centerCondition['field'] = $field;
        $centerCondition['value'] = $value;
        $centerResults = $this->center->searchData(array($centerCondition));
        return $centerResults;
    }

    private function getOperator($type)
    {
        $choices = array(
            ChoiceType::TYPE_CONTAINS         => 'LIKE',
            ChoiceType::TYPE_NOT_CONTAINS     => 'NOT LIKE',
            ChoiceType::TYPE_EQUAL            => '=',
        );

        return isset($choices[$type]) ? $choices[$type] : false;
    }

    /**
     * {@inheritdoc}
     */
    public function getDefaultOptions()
    {
        return array(
            'format'   => '%%%s%%',
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getRenderSettings()
    {
        return array('sonata_type_filter_choice', array(
            'field_type'    => $this->getFieldType(),
            'field_options' => $this->getFieldOptions(),
            'label'         => $this->getLabel()
        ));
    }
}