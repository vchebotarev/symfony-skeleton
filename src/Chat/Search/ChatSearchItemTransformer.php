<?php

namespace App\Chat\Search;

use App\Chat\Message\Search\ChatMessageSearchItemTransformer;
use App\Entity\Chat;
use App\Entity\ChatMessage;
use App\Entity\ChatMessageUser;
use App\Entity\ChatUser;
use App\User\UserManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Query\Expr\Join;

class ChatSearchItemTransformer
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
    protected $messageTransformer;

    /**
     * @var ChatSearchItem[]
     */
    protected $results = [];

    /**
     * @param EntityManager                    $em
     * @param UserManager                      $userManager
     * @param ChatMessageSearchItemTransformer $messageTransformer
     */
    public function __construct(EntityManager $em, UserManager $userManager, ChatMessageSearchItemTransformer $messageTransformer)
    {
        $this->em                 = $em;
        $this->userManager        = $userManager;
        $this->messageTransformer = $messageTransformer;
    }

    /**
     * @param Chat[] $chats
     * @return ChatSearchItem[]
     */
    public function transform($chats)
    {
        //Обнуляем предыдущие значения
        $this->results = [];

        //Предзаполнение
        foreach ($chats as $chat) {
            $this->results[$chat->getId()] = new ChatSearchItem($chat);
        }

        $this->fillUsers();
        $this->fillCountUnread();
        $this->fillLastMessage();

        return $this->results;
    }

    protected function fillUsers()
    {
        if (empty($this->results)) {
            return;
        }

        $chatIds = array_keys($this->results);

        $expr = $this->em->getExpressionBuilder();

        /** @var ChatUser[] $users */
        $users = $this->em->createQueryBuilder()
            ->select('cu', 'u')
            ->from(ChatUser::class, 'cu')
            ->leftJoin('cu.user', 'u')
            ->andWhere($expr->in('cu.chat', $chatIds))
            ->getQuery()
            ->getResult();

        $grouped = [];
        foreach ($users as $user) {
            $grouped[$user->getChat()->getId()][] = $user->getUser();
        }
        foreach ($grouped as $k => $item) {
            $this->results[$k]->setUsers($item);
        }
    }

    protected function fillCountUnread()
    {
        if (empty($this->results)) {
            return;
        }

        $chatIds = array_keys($this->results);

        $expr = $this->em->getExpressionBuilder();

        $counts = $this->em->createQueryBuilder()
            ->select('c.id, COUNT(cm.id) AS cc')
            ->from(Chat::class, 'c', 'c.id')
            ->leftJoin('c.messages', 'cm')
            ->leftJoin('cm.users', 'cmu')
            ->andWhere($expr->in('c.id', $chatIds))
            ->andWhere($expr->eq('cmu.user', $this->userManager->getCurrentUser()->getId()))
            ->andWhere($expr->eq('cmu.isRead', 0))
            ->addGroupBy('c.id')
            ->getQuery()
            ->getResult();

        foreach ($counts as $chatId => $c) {
            $this->results[$chatId]->setCountUnread($c['cc']);
        }
    }

    protected function fillLastMessage()
    {
        if (empty($this->results)) {
            return;
        }

        $expr    = $this->em->getExpressionBuilder();
        $chatIds = array_keys($this->results);
        $userId  = $this->userManager->getCurrentUser()->getId();

        /*
        SELECT cm.id, cm.body, SUM(IF(cm.id < cm2.id AND cm.chat_id = cm2.chat_id, 1, 0)) AS gteq
        FROM chat_message cm
        LEFT JOIN chat_message_user cmu ON cm.id = cmu.message_id
        LEFT JOIN chat_message_user cmu2 ON cmu.user_id = cmu2.user_id AND cmu2.message_id != cmu.message_id
        LEFT JOIN chat_message cm2 ON cmu2.message_id = cm2.id
        WHERE 1=1
        AND cmu.user_id = 1 AND cmu.is_deleted = 0
        AND cmu2.is_deleted = 0
        GROUP BY cm.id
        HAVING gteq = 0
         */
        /** @var ChatMessage[] $messages */
        $messages = $this->em->createQueryBuilder()
            ->from(ChatMessage::class, 'cm')
            ->select('cm')
            ->addSelect('SUM((case when cm.id < cm2.id AND cm.chat = cm2.chat then 1 else 0 end)) AS HIDDEN cc')
            ->leftJoin('cm.users', 'cmu')
            ->leftJoin(ChatMessageUser::class, 'cmu2', Join::WITH, 'cmu.user = cmu2.user AND cmu2.message != cmu.message')
            ->leftJoin('cmu2.message', 'cm2')
            ->andWhere($expr->in('cm.chat', $chatIds))
            ->andWhere('cmu.user = '.$userId)
            ->andWhere('cmu.isDeleted = 0')
            ->andWhere('cmu2.isDeleted = 0')
            ->addGroupBy('cm.id')
            ->andHaving('cc = 0')
            ->getQuery()
            ->getResult();

        $map = [];
        foreach ($messages as $mes) {
            $map[$mes->getId()] = $mes->getChat()->getId();
        }

        $messagesTransformed = $this->messageTransformer->transform($messages);

        foreach ($messagesTransformed as $mes) {
            $this->results[$map[$mes->getId()]]->setLastMessage($mes);
        }
    }

}
