<?php

namespace App\Chat\Message\Search;

use App\Entity\ChatMessage;
use App\Entity\ChatMessageUser;
use App\Entity\User;
use App\User\UserManager;
use Doctrine\ORM\EntityManager;

class ChatMessageSearchItemTransformer
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
     * @var ChatMessageSearchItem[]
     */
    protected $results = [];

    /**
     * @param EntityManager $em
     * @param UserManager   $userManager
     */
    public function __construct(EntityManager $em, UserManager $userManager)
    {
        $this->em          = $em;
        $this->userManager = $userManager;
    }

    /**
     * @param ChatMessage[] $chatMessages
     * @return ChatMessageSearchItem[]
     */
    public function transform($chatMessages)
    {
        $this->results = [];

        //Предзаполнение
        foreach ($chatMessages as $chatMessage) {
            $this->results[$chatMessage->getId()] = new ChatMessageSearchItem($chatMessage);
        }

        $this->fillUsers($chatMessages);
        $this->fillIsRead();

        return $this->results;
    }

    /**
     * @param ChatMessage[] $chatMessages
     */
    protected function fillUsers($chatMessages)
    {
        $userIds = array_map(function (ChatMessage $message){
            return $message->getUser()->getId();
        }, $chatMessages);
        if (empty($userIds)) {
            return;
        }

        $expr = $this->em->getExpressionBuilder();

        $users = $this->em
            ->createQueryBuilder()
            ->from(User::class, 'u', 'u.id')
            ->select('u')
            ->andWhere($expr->in('u.id', $userIds))
            ->getQuery()
            ->getResult();

        foreach ($chatMessages as $message) {
            $this->results[$message->getId()]->setUser($users[$message->getUser()->getId()]);
        }
    }

    protected function fillIsRead()
    {
        $cmIds = array_keys($this->results);
        if (empty($cmIds)) {
            return;
        }

        $expr = $this->em->getExpressionBuilder();

        $results = $this->em->createQueryBuilder()
            ->from(ChatMessageUser::class, 'cmu')
            ->select('IDENTITY(cmu.message) AS message_id, IDENTITY(cmu.user) AS user_id, cmu.isRead AS is_read')
            ->andWhere($expr->in('cmu.message', $cmIds))
            ->getQuery()
            ->getResult();

        $currentUserId = $this->userManager->getCurrentUser()->getId();

        foreach ($results as $res) {
            $message       = $this->results[$res['message_id']];
            $messageUserId = $message->getUser()->getId();
            $userId        = $res['user_id'];
            $isRead        = $res['is_read'];
            if ($currentUserId == $messageUserId) {
                if ($userId == $currentUserId) {
                    continue;
                }
                if ($isRead) {
                    $message->setIsRead(true);
                }
            } else {
                if ($userId != $currentUserId) {
                    continue;
                }
                if ($isRead) {
                    $message->setIsRead(true);
                }
            }
        }
    }

}
