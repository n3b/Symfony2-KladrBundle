<?php

namespace n3b\Bundle\Kladr\Entity;

use Doctrine\ORM\EntityRepository;

class StreetRepository extends EntityRepository
{
    public function findByLead($args)
    {
        $args['region'] = $args['region'] ?: '77000000000';

        $dql = "
            SELECT s FROM n3b\Bundle\Kladr\Entity\Street s
            JOIN s.parent r WITH r.code = :region
            WHERE LOWER(s.title) LIKE :query
            ";
        $dql .= " ORDER BY s.title";

        $q = $this->getEntityManager()->createQuery($dql);
        $q->setParameters(array(
            'query' => \mb_strtolower($args['query'], 'utf-8').'%',
            'region' => $args['region']
            ));
        
        return $q->getArrayResult();
    }

    public function setParents()
    {
        // проставляет parent_id по коду
        $sql = "
            UPDATE Street s, Region r
            SET s.parent_id = r.id
            WHERE s.parentCode = r.code
            ";
        $stmt = $this->getEntityManager()->getConnection()->prepare($sql);
        $stmt->execute();
    }
}
