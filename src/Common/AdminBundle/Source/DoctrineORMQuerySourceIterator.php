<?php
namespace Common\AdminBundle\Source;

use Exporter\Source\DoctrineORMQuerySourceIterator as BaseDoctrineORMQuerySourceIterator;
use Doctrine\ORM\Query;
use Common\ConstantBundle\Utility\Center;

class DoctrineORMQuerySourceIterator extends BaseDoctrineORMQuerySourceIterator
{

    protected $center;

    protected $centerData;

    protected $viewAtpressIds;

    public function setCenter(Center $center)
    {
        $this->center = $center;
        if (sizeof($this->viewAtpressIds)>0){
            $this->centerData = $this->center->fetchData(array_unique($this->viewAtpressIds));
        }
    }


    public function __construct(Query $query, array $fields, $dateTimeFormat = 'r')
    {
        parent::__construct($query,$fields, $dateTimeFormat);
        $results =$query->getResult();
        $this->viewAtpressIds = array();
        foreach($results as $result)
        {

            if (property_exists ($result , 'atpressId' ))
            {
                $this->viewAtpressIds[] = $result->getAtpressId();
            }
            if (property_exists ($result, 'client' ))
            {
                $client = $result->getClient();
                if (!is_null($client) && property_exists ($client , 'atpressId' ))
                {
                    $this->viewAtpressIds[] = $client->getAtpressId();
                }
            }
        }
    }

    public function current()
    {
        $current = $this->iterator->current();
        if($this->centerData!==null) $this->center->mergeCenterData($current,$this->centerData);
        $data = array();

        foreach ($this->propertyPaths as $name => $propertyPath) {
            try {
                $data[$name] = $this->getValue($this->propertyAccessor->getValue($current[0], $propertyPath));
            } catch (UnexpectedTypeException $e) {
                //non existent object in path will be ignored
                $data[$name] = null;
            }
        }

        $this->query->getEntityManager()->getUnitOfWork()->detach($current[0]);

        return $data;
    }
}