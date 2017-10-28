<?php

namespace App\Doctrine\ORM;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository as BaseEntityRepository;
use Doctrine\ORM\Mapping\ClassMetadata;

class EntityRepository extends BaseEntityRepository
{
    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * @param EntityManager $em
     * @param ClassMetadata $class
     */
    public function __construct(EntityManager $em, ClassMetadata $class)
    {
        parent::__construct($em, $class);
        $this->em = $this->_em;
    }

    /**
     * @todo closure
     * @todo проверить
     * @param array $criteria
     * @return int
     */
    public function countBy(array $criteria) : int
    {
        $persister = $this->em->getUnitOfWork()->getEntityPersister($this->_entityName);
        return $persister->count($criteria);
    }

    /**
     * @todo closure
     * @todo проверить
     * @param array $updates
     * @param array $criteria
     * @param array $notCriteria
     * @return int
     */
    public function updateBy(array $updates, array $criteria = [], array $notCriteria = []) : int
    {
        $qb = $this->createQueryBuilder('t')->update();
        foreach ($updates as $col => $value) {
            $qb->set($col, $value);
        }
        foreach ($criteria as $col => $value) {
            if (is_array($value)) {
                $expr = $qb->expr()->in($col, $value);
            } else {
                $expr = $qb->expr()->eq($col, $value);
            }
            $qb->andWhere($expr);
        }
        foreach ($notCriteria as $col => $value) {
            if (is_array($value)) {
                $expr = $qb->expr()->notIn($col, $value);
            } else {
                $expr = $qb->expr()->neq($col, $value);
            }
            $qb->andWhere($expr);
        }
        return $qb->getQuery()->getSingleScalarResult();
    }

}
