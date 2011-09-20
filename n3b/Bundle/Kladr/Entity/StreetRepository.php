<?php

namespace n3b\Bundle\Kladr\Entity;

use Doctrine\ORM\EntityRepository;

class StreetRepository extends EntityRepository
{

    public function getByQuery($args)
    {
        $dql = "
            SELECT PARTIAL s.{id, title} FROM n3b\Bundle\Kladr\Entity\KladrStreet s
            JOIN s.parent r WITH r.id = :region
            WHERE s.title LIKE :query
            ORDER BY s.title";
        $count = "
            SELECT COUNT(s) FROM n3b\Bundle\Kladr\Entity\KladrStreet s
            JOIN s.parent r WITH r.id = :region
            WHERE s.title LIKE :query";

        $q = $this->getEntityManager()->createQuery($dql);
        $q->setParameters(array(
            'query' => \mb_convert_case($args['query'], MB_CASE_TITLE, "UTF-8") . '%',
            'region' => $args['region']
        ));
        $qCount = $this->getEntityManager()->createQuery($count);
        $qCount->setParameters(array(
            'query' => \mb_convert_case($args['query'], MB_CASE_TITLE, "UTF-8") . '%',
            'region' => $args['region']
        ));
        
        $q->setMaxResults(10);
        $res['count'] = $qCount->getSingleScalarResult();
        $res['items'] = $q->getArrayResult();

        return $res;
    }
}
