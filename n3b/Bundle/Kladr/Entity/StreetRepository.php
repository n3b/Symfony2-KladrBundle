<?php

namespace n3b\Bundle\Kladr\Entity;

use Doctrine\ORM\EntityRepository;

class StreetRepository extends EntityRepository
{

    public function getByQuery($query, $region)
    {
        $dql = "
            SELECT s FROM n3b\Bundle\Kladr\Entity\Street s
            JOIN s.parent r WITH r.code = :region
            WHERE LOWER(s.title) LIKE :query
            ORDER BY s.title";
        $count = "
            SELECT COUNT(s) FROM n3b\Bundle\Kladr\Entity\Street s
            JOIN s.parent r WITH r.code = :region
            WHERE LOWER(s.title) LIKE :query";

        $q = $this->getEntityManager()->createQuery($dql);
        $q->setParameters(array(
            'query' => \mb_strtolower($query, 'utf-8') . '%',
            'region' => $region
        ));
        $qCount = $this->getEntityManager()->createQuery($count);
        $qCount->setParameters(array(
            'query' => \mb_strtolower($query, 'utf-8') . '%',
            'region' => $region
        ));

        $res['items'] = $q->getArrayResult();
        $res['count'] = $qCount->getSingleScalarResult();

        return $res;
    }
}
