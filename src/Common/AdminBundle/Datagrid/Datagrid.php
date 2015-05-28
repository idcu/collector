<?php
namespace Common\AdminBundle\Datagrid;

use Common\ConstantBundle\Utility\Center;
use Sonata\AdminBundle\Datagrid\Datagrid as BaseDatagrid;

class Datagrid extends BaseDatagrid{

    protected $center;

    public function setCenter(Center $center)
    {
        $this->center = $center;
    }

    public function getCenter(){
        return $this->center;
    }

    public function getResults()
    {
        $this->buildPager();

        if (!$this->results) {
            $this->results = $this->pager->getResults();
        }

        $clients = array();
        $viewAtpressIds = array();
        foreach($this->results as $result)
        {

            if (property_exists ($result , 'atpressId' ))
            {
                $clients[] = $result;
                $viewAtpressIds[] = $result->getAtpressId();
            }
            if (property_exists ($result, 'client' ))
            {
                $client = $result->getClient();
                if (!is_null($client) && property_exists ($client , 'atpressId' ))
                {
                    $clients[] = $client;
                    $viewAtpressIds[] = $client->getAtpressId();
                }
            }
        }
        if (sizeof($viewAtpressIds)>0){
            $centerData = $this->center->fetchData(array_unique($viewAtpressIds));
            $this->center->mergeCenterData($clients,$centerData);
        }
        return $this->results;
    }
} 