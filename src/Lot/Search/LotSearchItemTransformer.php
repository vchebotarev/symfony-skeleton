<?php

namespace App\Lot\Search;

use App\Entity\Lot;
use Doctrine\ORM\EntityManager;

class LotSearchItemTransformer
{
    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * @var LotSearchItem[]
     */
    protected $results;

    /**
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    /**
     * @param Lot[] $lots
     * @return array
     */
    public function transform($lots)
    {
        if (empty($lots)) {
            return [];
        }
        $this->results = [];
        foreach ($lots as $k => $item) {
            $this->results[$k] = new LotSearchItem($item);
        }

        $this->fillCountBets();

        return $this->results;
    }

    protected function fillCountBets()
    {
        $lotIds = array_map(function (LotSearchItem $item){
            return $item->getId();
        }, $this->results);

        $expr = $this->em->getExpressionBuilder();
        $qb = $this->em
            ->createQueryBuilder()
            ->select('l.id')
            ->addSelect($expr->count('lb.id'). ' AS cc')
            ->from(Lot::class, 'l', 'l.id')
            ->leftJoin('l.bets', 'lb')
            ->andWhere($expr->in('l.id', $lotIds))
            ->groupBy('l.id');

        $results = $qb->getQuery()->getArrayResult();

        foreach ($results as $lotId => $c) {
            $this->results[$lotId]->setBetCount($c['cc']);
        }
    }

}
