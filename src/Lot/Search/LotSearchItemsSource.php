<?php

namespace App\Lot\Search;

use App\Entity\Lot;
use App\Search\Param;
use Chebur\SearchBundle\Search\AbstractItemsSource;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\QueryBuilder;

class LotSearchItemsSource extends AbstractItemsSource
{
    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * @var LotSearchItemTransformer
     */
    protected $lotTransformer;

    /**
     * @param EntityManager            $em
     * @param LotSearchItemTransformer $lotTransformer
     */
    public function __construct(EntityManager $em, LotSearchItemTransformer $lotTransformer)
    {
        $this->em             = $em;
        $this->lotTransformer = $lotTransformer;
    }

    /**
     * @inheritDoc
     */
    protected function getItems($options = [], $sort = '', $sortOrder = '', $limit = 0, int $offset = 0) : iterable
    {
        $qb = $this->getQb($options);

        //pagination
        $qb->setMaxResults($limit);
        $qb->setFirstResult($offset);

        $qb->select('l, lu, lbl');
        $qb->leftJoin('l.user', 'lu');
        $qb->leftJoin('l.betLast', 'lbl');

        $lots = $qb->getQuery()->getResult();

        $results = $this->lotTransformer->transform($lots);

        return $results;
    }

    /**
     * @param $options
     * @return QueryBuilder
     */
    protected function getQb($options) : QueryBuilder
    {
        $qb = $this->em->createQueryBuilder();
        $qb->from(Lot::class, 'l', 'l.id');

        if (isset($options[Param::Q]) && $options[Param::STATUS] ) {
            $q = $options[Param::Q];
            if (is_numeric($q)) {
                $qb->andWhere($qb->expr()->eq('l.id', ':q'));
            } else {
                $qb->andWhere($qb->expr()->orX(
                    $qb->expr()->like('l.name', ':q'),
                    $qb->expr()->like('l.body', ':q')
                ));
            }
            $qb->setParameter('q', $q);
        }
        if (isset($options[Param::STATUS]) && $options[Param::STATUS] !== null) {
            $status = $options[Param::STATUS];
            $qb->andWhere($qb->expr()->eq('l.status', ':status'));
            $qb->setParameter('status', $status);
        }
        if (isset($options[Param::USER]) && $options[Param::USER]) {
            $userId = $options[Param::USER];
            $qb->andWhere($qb->expr()->eq('l.user', ':user'));
            $qb->setParameter('user', $userId);
        }

        return $qb;
    }

    /**
     * @inheritDoc
     */
    protected function getTotalCount($options = [])
    {
        $qb = $this->getQb($options);
        $qb->select('COUNT(DISTINCT l.id)');

        return (int)$qb->getQuery()->getSingleScalarResult();
    }

}
