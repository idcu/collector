<?php
namespace Collector\EntityBundle\SearchRepository;

use FOQ\ElasticaBundle\Repository;
use Collector\EntityBundle\Utils\Converter;

class PressRepository extends Repository{

    public function search($condition)
    {
        $query = new \Elastica_Query_Bool();
        $disabledQuery = new \Elastica_Query_Terms();
        $disabledQuery->setTerms('disabledToSearch', array(false));
        $query->addMust($disabledQuery);

        if (!is_null($condition))
        {
            if (isset($condition['keyword']) && strlen($condition['keyword'])>0)
            {
                $aryKeyword = preg_split('/[\s|\x{3000}]+/u', Converter::convertText($condition['keyword']));
                $queryString = '';
                foreach ($aryKeyword as $keyword)
                {
                    if (strlen(trim($keyword))>0)
                    {
                        if (strlen($queryString)>0) $queryString .= " AND ";
                        $queryString .= '"'.$keyword.'"';
                    }
                }
                $keywordQuery = new \Elastica_Query_QueryString();
                $keywordQuery->setFields(array('textToSearch'));
                $keywordQuery->setQuery($queryString);
//                $keywordQuery->setAnalyzer('kuromoji_analyzer');
                $query->addMust($keywordQuery);
            }
            if (isset($condition['publishDateStart']) && strlen($condition['publishDateStart'])>0)
            {
                $publishDateStartQuery = new \Elastica_Query_Range();
                $publishDateStartQuery->addField('publishDateToSearch',array(
                    'gte' => $condition['publishDateStart']
                ));
                $query->addMust($publishDateStartQuery);
            }
            if (isset($condition['publishDateEnd']) && strlen($condition['publishDateEnd'])>0)
            {
                $publishDateEndQuery = new \Elastica_Query_Range();
                $publishDateEndQuery->addField('publishDateToSearch',array(
                    'lte' => $condition['publishDateEnd']
                ));
                $query->addMust($publishDateEndQuery);
            }
        }
        $query = \Elastica_Query::create($query);
        $query->addSort(array('publishDateToSearch' => 'desc','sourceRank' => 'desc','hasImage' => 'desc'));
        return $this->findPaginated($query);
    }
}