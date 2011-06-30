<?php

namespace n3b\Bundle\Kladr\Entity;

use Doctrine\ORM\EntityRepository;

class RegionRepository extends EntityRepository
{
    public function findByLead($args)
    {
        $dql = "
            SELECT r FROM n3b\Bundle\Kladr\Entity\Region r
            WHERE r.level < 4 AND LOWER(r.title) LIKE :query
            ";
        if(isset($args['region'])) {
            $dql .= " AND SUBSTRING(r.code, 1, 2) = ".\str_pad($args['region'], 2, '0', \STR_PAD_LEFT);
        }

        $dql .= " ORDER BY r.title";

        $q = $this->getEntityManager()->createQuery($dql);
        $q->setParameter('query', \mb_strtolower($args['query'], 'utf-8').'%');

        return $q->getArrayResult();
    }

    public function setParents()
    {
        // проставляет parent_id по коду
        $sql = "
            UPDATE Region r, Region r2
            SET r.parent_id = r2.id
            WHERE r.parentCode = r2.code
                AND r.parentCode IS NOT NULL
            ";
        $stmt = $this->getEntityManager()->getConnection()->prepare($sql);
        $stmt->execute();
    }

    public function setFullParentStr()
    {
        for($i = 2; $i < 5; $i++) {
            $sql = "
                UPDATE Region r, Region r2
                SET r.parentStr = CONCAT(', ', r2.title, ' ', LOWER(r2.socr), r2.parentStr)
                WHERE r.parent_id = r2.id
                AND r.level = $i
                ";
            $stmt = $this->getEntityManager()->getConnection()->prepare($sql);
            $stmt->execute();
        }
        
    }
}
