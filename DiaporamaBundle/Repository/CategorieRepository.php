<?php

namespace DiaporamaBundle\Repository;

/**
 * CategorieRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class CategorieRepository extends \Doctrine\ORM\EntityRepository
{
    public function getAllCategories($langue)
    {
        $qb = $this->createQueryBuilder('c');

        /**
         * recherche via la langue
         */
        if(!empty($langue)){
            $qb->andWhere('c.langue = :langue')
               ->setParameter('langue', $langue);
        }

        $qb->orderBy('c.id', 'DESC');

        return $query = $qb->getQuery()->getResult();
    }

}
