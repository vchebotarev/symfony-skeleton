<?php

namespace App\Chat\Search;

use App\Entity\Chat;
use App\User\UserManager;
use Chebur\SearchBundle\Search\AbstractItemsSource;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\QueryBuilder;

class ChatsItemsSource extends AbstractItemsSource
{
    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * @var UserManager
     */
    protected $userManager;

    /**
     * @var ChatSearchItemTransformer
     */
    protected $chatTransformer;

    /**
     * @param EntityManager             $em
     * @param UserManager               $userManager
     * @param ChatSearchItemTransformer $chatTransformer
     */
    public function __construct(EntityManager $em, UserManager $userManager, ChatSearchItemTransformer $chatTransformer)
    {
        $this->em              = $em;
        $this->userManager     = $userManager;
        $this->chatTransformer = $chatTransformer;
    }

    /**
     * @inheritDoc
     */
    protected function getItems($options = [], $sort = '', $sortOrder = '', $limit = 0, int $offset = 0) : iterable
    {
        $expr = $this->em->getExpressionBuilder();

        $qb  = $this->getQb($options);

        $qb->select('c')
            ->addSelect($expr->max('cm.dateCreated'). ' AS HIDDEN max_dt')
            ->setMaxResults($limit)
            ->setFirstResult($offset)
            ->addGroupBy('c.id')
            ->addOrderBy('max_dt', Criteria::DESC)
        ;

        $results = $qb->getQuery()->getResult();

        $chats = $this->chatTransformer->transform($results);

        return $chats;
    }

    /**
     * @param array $options
     * @return QueryBuilder
     */
    protected function getQb(array $options)
    {

        $qb = $this->em
            ->createQueryBuilder()
            ->from(Chat::class, 'c')
            ->leftJoin('c.users', 'cu')
            ->leftJoin('c.messages', 'cm')
            ->leftJoin('cm.users', 'cmu')
            ->andWhere('cu.user = :user')
            ->andWhere('cmu.user = :user')
            ->andWhere('cmu.isDeleted = 0')
            ->setParameter('user', $this->userManager->getCurrentUser())
        ;

        return $qb;
    }

    /**
     * @inheritDoc
     */
    protected function getTotalCount($options = [])
    {
        $qb = $this->getQb($options);
        $qb->select('COUNT(DISTINCT c.id)');

        return (int)$qb->getQuery()->getSingleScalarResult();
    }

}
