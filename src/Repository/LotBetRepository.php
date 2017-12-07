<?php

namespace App\Repository;

use App\Doctrine\ORM\EntityRepository;
use App\Entity\Lot;
use App\Entity\LotBet;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\Query\Expr\Join;

class LotBetRepository extends EntityRepository
{
    /**
     * @param Lot $lot
     * @return LotBet[]
     */
    public function findByLot(Lot $lot)
    {
        $expr = $this->em->getExpressionBuilder();

        $qb = $this->createQueryBuilder('lb')
            ->leftJoin('lb.user', 'u')
            ->andWhere($expr->eq('lb.lot', $lot->getId()))
            ->addOrderBy('lb.dateCreated', Criteria::DESC);

        return $qb->getQuery()->getResult();
    }

    /**
     * @param Lot $lot
     * @return LotBet|null
     */
    public function findLastByLot(Lot $lot)
    {
        $expr = $this->em->getExpressionBuilder();
        $lotBet = $this->createQueryBuilder('lb')
            ->leftJoin(LotBet::class, 'lb2', Join::WITH, 'lb.dateCreated < lb2.dateCreated AND lb.lot = lb2.lot')
            ->andWhere($expr->eq('lb.lot', $lot->getId()))
            ->andWhere($expr->isNull('lb2.id'))
            ->getQuery()
            ->getOneOrNullResult();

        return $lotBet;
    }

}
