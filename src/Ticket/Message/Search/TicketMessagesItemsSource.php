<?php

namespace App\Ticket\Message\Search;

use App\User\UserManager;
use Chebur\SearchBundle\Search\AbstractItemsSource;
use Doctrine\ORM\EntityManager;

class TicketMessagesItemsSource extends AbstractItemsSource
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
     * @var TicketMessageSearchItemTransformer
     */
    protected $ticketMessageTransformer;

    /**
     * @param EntityManager                      $em
     * @param UserManager                        $userManager
     * @param TicketMessageSearchItemTransformer $messageTransformer
     */
    public function __construct(EntityManager $em, UserManager $userManager, TicketMessageSearchItemTransformer $messageTransformer)
    {
        $this->em                       = $em;
        $this->userManager              = $userManager;
        $this->ticketMessageTransformer = $messageTransformer;
    }

    /**
     * @inheritDoc
     */
    protected function getItems($options = [], $sort = '', $sortOrder = '', $limit = 0, int $offset = 0) : iterable
    {
        //todo
    }

    /**
     * @inheritDoc
     */
    protected function getTotalCount($options = [])
    {
        //todo
    }

}
