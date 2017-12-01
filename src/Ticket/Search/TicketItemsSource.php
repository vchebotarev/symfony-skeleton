<?php

namespace App\Ticket\Search;

use App\User\UserManager;
use Chebur\SearchBundle\Search\AbstractItemsSource;
use Doctrine\ORM\EntityManager;

class TicketItemsSource extends AbstractItemsSource
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
     * @var TicketSearchItemTransformer
     */
    protected $ticketTransformer;

    /**
     * @param EntityManager               $em
     * @param UserManager                 $userManager
     * @param TicketSearchItemTransformer $messageTransformer
     */
    public function __construct(EntityManager $em, UserManager $userManager, TicketSearchItemTransformer $messageTransformer) {
        $this->em                = $em;
        $this->userManager       = $userManager;
        $this->ticketTransformer = $messageTransformer;
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
