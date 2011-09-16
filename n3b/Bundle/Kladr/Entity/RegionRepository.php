<?php

namespace n3b\Bundle\Kladr\Entity;

use Doctrine\ORM\EntityRepository;

class RegionRepository extends EntityRepository
{
    public function getByQuery($args)
    {
        $dql = "
            SELECT r FROM n3b\Bundle\Kladr\Entity\Region r
            WHERE r.level < 4 AND LOWER(r.title) LIKE :query
            ORDER BY r.title";
        $count = "
            SELECT COUNT(r) FROM n3b\Bundle\Kladr\Entity\Region r
            WHERE r.level < 4 AND LOWER(r.title) LIKE :query";

        $q = $this->getEntityManager()->createQuery($dql);
        $q->setParameter('query', \mb_strtolower($query, 'utf-8') . '%');
        $q->setMaxResults(10);
        $qCount = $this->getEntityManager()->createQuery($count);
        $qCount->setParameter('query', \mb_strtolower($query, 'utf-8') . '%');

        $res['items'] = $q->getArrayResult();
        $res['count'] = $qCount->getSingleScalarResult();
        
        return $res;
    }
}
