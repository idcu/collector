<?php

namespace Collector\EntityBundle\Repository;

use Doctrine\ORM\EntityRepository;

class PressRepository extends EntityRepository
{

    public function findAtpressPresses()
    {
        $q = $this->createQueryBuilder('press');
        $q = $q
            ->where($q->expr()->like('press.sources', ':atpress'))
            ->setParameter('atpress', '%atpress.ne.jp%')
        ;
        $results = $q->getQuery()->execute();
        return $results;
    }

    public function findByIds($ids)
    {
        $q = $this->createQueryBuilder('press');
        $q = $q
            ->where($q->expr()->in('press.id', ':ids'))
            ->setParameter('ids', $ids)
        ;
        $results = $q->getQuery()->execute();
        return $results;
    }

    public function findByCompany($companyId)
    {
        $q = $this->createQueryBuilder('press');
        $q = $q
            ->where($q->expr()->eq('press.company', ':company'))
            ->setParameter('company', $companyId)
        ;
        $results = $q->getQuery()->execute();
        return $results;
    }

    //$pressCount; 记事的总数
    public function findCountCompany($id)
    {
        $q = $this->createQueryBuilder('press');
        $count = $q->select("COUNT(press.id) AS total")
            ->where('company.disabled = false')
            ->andWhere("press.company = :company")
            ->setParameter(":company", $id)
            ->getQuery()
            ->getSingleScalarResult();
        return $count;
    }

    //$sources; 记事的提供者 （多个）
    public function findSources($id)
    {
        $q = $this->createQueryBuilder('press');
        $rs = $q->select("press")
            ->where('company.disabled = false')
            ->andWhere("press.company = :company")
            ->setParameter(":company", $id)
            ->getQuery()
            ->getResult();
        $data = array();
        foreach ($rs as $key=>$val) {
            $data[$key]= $val->getSource();
        }
        return $data;
    }

    public function findPublishDate($id, $type = 'DESC')
    {
        $q = $this->createQueryBuilder('press');
        $result = $q->select("press.publishDate")
            ->where('company.disabled = false')
            ->andWhere("press.company = :company")
            ->setParameter(":company", $id)
            ->addOrderBy("press.publishDate", $type)
            ->setMaxResults(1)
            ->getQuery()
            ->getSingleResult();
        return $result['publishDate'];
    }

}
