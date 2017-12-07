<?php

namespace App\User\Review\Search;

use App\Entity\UserReview;
use App\Search\Param;
use Chebur\SearchBundle\Search\AbstractItemsSource;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\QueryBuilder;

class UserReviewItemsSource extends AbstractItemsSource
{
    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    /**
     * @inheritDoc
     */
    protected function getItems($options = [], $sort = '', $sortOrder = '', $limit = 0, int $offset = 0) : iterable
    {
        $qb = $this->getQb($options);
        $qb->select('ur, u, uc');
        $qb->leftJoin('ur.user', 'u');
        $qb->leftJoin('ur.userCreated', 'uc');
        $qb->addOrderBy('ur.dateCreated', Criteria::DESC);

        if ($limit) {
            $qb->setMaxResults($limit);
        }
        if ($offset) {
            $qb->setFirstResult($offset);
        }

        return $qb->getQuery()->getResult();
    }

    /**
     * @param array $options
     * @return QueryBuilder
     */
    protected function getQb(array $options)
    {
        $qb = $this->em->createQueryBuilder();
        $qb->from(UserReview::class, 'ur');

        if (isset($options[Param::TYPE]) && $options[Param::TYPE] !== null) {
            $type = $options[Param::TYPE];
            $qb->andWhere($qb->expr()->eq('ur.type', ':type'));
            $qb->setParameter('type', $type);
        }

        if (isset($options[Param::USER]) && $options[Param::USER]) {
            $user = $options[Param::USER];
            $qb->andWhere($qb->expr()->eq('ur.user', ':user'));
            $qb->setParameter('user', $user);
        }

        if (isset($options['user_created']) && $options['user_created']) {
            $userCreated = $options['user_created'];
            $qb->andWhere($qb->expr()->eq('ur.userCreated', ':user_created'));
            $qb->setParameter('user_created', $userCreated);
        }

        if (isset($options[Param::CREATED]) && $options[Param::CREATED]) {
            $dateCreated = $options[Param::CREATED];
            $from        = isset($dateCreated[Param::FROM]) && $dateCreated[Param::FROM] ? $dateCreated[Param::FROM] : null;
            $to          = isset($dateCreated[Param::TO]) && $dateCreated[Param::TO] ? $dateCreated[Param::TO] : null;
            if ($from) {
                $qb->andWhere($qb->expr()->gte('ur.dateCreated', ':created_from'));
                $qb->setParameter('created_from', $from);
            }
            if ($to) {
                $qb->andWhere($qb->expr()->lte('ur.dateCreated', ':created_to'));
                $qb->setParameter('created_to', $to);
            }
        }

        return $qb;
    }

    /**
     * @inheritDoc
     */
    protected function getTotalCount($options = [])
    {
        $qb = $this->getQb($options);
        $qb->select($qb->expr()->count('ur.id'));

        return $qb->getQuery()->getSingleScalarResult();
    }

}
