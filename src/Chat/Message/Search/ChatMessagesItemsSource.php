<?php

namespace App\Chat\Message\Search;

use App\Entity\ChatMessage;
use App\Search\Param;
use App\User\UserManager;
use Chebur\SearchBundle\Search\AbstractItemsSource;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\QueryBuilder;

class ChatMessagesItemsSource extends AbstractItemsSource
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
     * @var ChatMessageSearchItemTransformer
     */
    protected $chatMessageTransformer;

    /**
     * @param EntityManager                    $em
     * @param UserManager                      $userManager
     * @param ChatMessageSearchItemTransformer $messageTransformer
     */
    public function __construct(EntityManager $em, UserManager $userManager, ChatMessageSearchItemTransformer $messageTransformer)
    {
        $this->em                     = $em;
        $this->userManager            = $userManager;
        $this->chatMessageTransformer = $messageTransformer;
    }

    /**
     * @inheritDoc
     */
    protected function getItems($options = [], $sort = '', $sortOrder = '', $limit = 0, int $offset = 0) : iterable
    {
        $results = $this->getQb($options)
            ->select('cm')
            ->setMaxResults($limit)
            ->addOrderBy('cm.dateCreated', Criteria::DESC)
            ->getQuery()
            ->getResult();

        $results = array_reverse($results);

        $messages = $this->chatMessageTransformer->transform($results);

        return $messages;
    }

    /**
     * @param array $options
     * @return QueryBuilder
     */
    protected function getQb(array $options)
    {
        $expr = $this->em->getExpressionBuilder();

        $qb = $this->em
            ->createQueryBuilder()
            ->select('cm')
            ->from(ChatMessage::class, 'cm', 'cm.id')
            ->leftJoin('cm.users', 'cmu')
            ->andWhere($expr->eq('cmu.user', ':user'))
            ->setParameter('user', $this->userManager->getCurrentUser())
            ->andWhere('cmu.isDeleted = 0')
        ;

        $qb->andWhere($expr->eq('cm.chat', $options['chat']->getId()));

        if (isset($options[Param::TO]) && $options[Param::TO]) { //id вернее, потому что сообщения могут быть созданы в одну секунду
            $idTo = $options[Param::TO];
            $qb->andWhere($expr->lt('cm.id', $idTo));
        }

        return $qb;

    }

    /**
     * @inheritDoc
     */
    protected function getTotalCount($options = [])
    {
        $qb = $this->getQb($options);
        $qb->select('COUNT(cm.id)');

        return (int)$qb->getQuery()->getSingleScalarResult();
    }

}
