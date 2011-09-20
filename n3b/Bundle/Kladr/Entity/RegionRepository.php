<?php

namespace n3b\Bundle\Kladr\Entity;

use Doctrine\ORM\EntityRepository;

class RegionRepository extends EntityRepository
{
    public function getByQuery(array $args)
    {
        $dql = "
            SELECT PARTIAL r.{id, title, fullParentTitle, emsTo} FROM n3b\Bundle\Kladr\Entity\KladrRegion r
            WHERE r.title LIKE :query 
            ORDER BY r.title";
        $count = "
            SELECT COUNT(r) FROM n3b\Bundle\Kladr\Entity\KladrRegion r
            WHERE r.title LIKE :query";

        $q = $this->getEntityManager()->createQuery($dql);
        $q->setParameter('query', \mb_convert_case($args['query'], MB_CASE_TITLE, "UTF-8") . '%');
        $qCount = $this->getEntityManager()->createQuery($count);
        $qCount->setParameter('query', \mb_convert_case($args['query'], MB_CASE_TITLE, "UTF-8") . '%');

        $q->setMaxResults(10);
        $res['count'] = $qCount->getSingleScalarResult();
        $res['items'] = $q->getArrayResult();

        return $res;
    }
}
