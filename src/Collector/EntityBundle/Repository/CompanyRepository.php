<?php

namespace Collector\EntityBundle\Repository;

use Doctrine\ORM\EntityRepository;

class CompanyRepository extends EntityRepository
{

    public function findSame($name)
    {
        $name = $this->stringFilter($name);

        $q = $this->createQueryBuilder('company');
        $q = $q
            ->where('company.disabled = false')
            ->andWhere($q->expr()->like('company.companyNameCanonical', ':name'))
            ->setParameter('name', '%' . $name . '%');

        $results = $q->getQuery()->execute();
        if (sizeof($results) > 0) {
            foreach ($results as $result) {
                similar_text($result->getCompanyNameCanonical(), $name, $percent);
                if ($percent > 95)
                    return $result;
            }
        }
        return null;
    }

    private function stringFilter($string)
    {
        $replace = array(
            '株式会社' => '',
            '有限会社' => '',
            '(株)' => '',
            '(有)' => '',
            '・' => '',
            ' ' => '',
            '　' => ''
        );
        return strtr(strip_tags($string), $replace);
    }

    public function findCompay()
    {
        $q = $this->createQueryBuilder('company');
        $rs = $q->select("company")
            ->getQuery()
            ->getResult();
        return $rs;
    }

}
