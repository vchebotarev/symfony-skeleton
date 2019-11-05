<?php

namespace App\Chat;

use App\Doctrine\Entity\Chat;
use App\Doctrine\Entity\ChatMessage;
use App\Doctrine\Entity\ChatMessageUser;
use App\Doctrine\Entity\ChatUser;
use App\Doctrine\Entity\User;
use App\User\UserManager;
use Doctrine\ORM\EntityManager;

class ChatManager
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
     * @param EntityManager $em
     * @param UserManager   $userManager
     */
    public function __construct(EntityManager $em, UserManager $userManager)
    {
        $this->em          = $em;
        $this->userManager = $userManager;
    }

    /**
     * @param int $id
     * @return ChatMessage|null
     */
    public function findChatMessageById(int $id)
    {
        $chatMessage = $this->em->getRepository(ChatMessage::class)->find($id);
        return $chatMessage;
    }

    /**
     * @param array $ids
     * @return ChatMessage[]|array
     */
    public function findChatMessagesByIds(array $ids)
    {
        $chatMessages = $this->em->getRepository(ChatMessage::class)->findBy([
            'id' => $ids,
        ]);
        return $chatMessages;
    }

    /**
     * @param User $user
     * @return mixed
     */
    public function findChatByUser(User $user)
    {
        return $this->em->getRepository(Chat::class)->findPrivateChatByUsers([
            $user,
            $this->userManager->getCurrentUser(),
        ]);
    }


    /**
     * @param Chat   $chat
     * @param string $body
     * @return ChatMessage
     */
    public function createMessage(Chat $chat, string $body) : ChatMessage
    {
        $currentUser = $this->userManager->getCurrentUser();

        $chatMessage = new ChatMessage();
        $chatMessage->setUser($currentUser);
        $chatMessage->setChat($chat);
        $chatMessage->setBody($body);

        foreach ($chat->getUsers() as $user) {
            $chatMessageUser = new ChatMessageUser();
            $chatMessageUser->setUser($user->getUser());
            $chatMessageUser->setMessage($chatMessage);
            $chatMessage->getUsers()->add($chatMessageUser);
            if ($user->getUser()->getId() == $currentUser->getId()) {
                $chatMessageUser->setIsRead(true);
            }
            $this->em->persist($chatMessageUser);
        }
        $this->em->persist($chatMessage);
        $this->em->flush();

        return $chatMessage;
    }

    /**
     * @param User   $user
     * @param string $body
     * @return ChatMessage
     */
    public function createMessageByUser(User $user, string $body) : ChatMessage
    {
        $chat = $this->findChatByUser($user);
        if (!$chat) {
            $chat = new Chat();
            $chatUser1 = new ChatUser();
            $chatUser1->setChat($chat);
            $chatUser1->setUser($user);
            $chatUser2 = new ChatUser();
            $chatUser2->setChat($chat);
            $chatUser2->setUser($this->userManager->getCurrentUser());
            $chat->getUsers()->add($chatUser1);
            $chat->getUsers()->add($chatUser2);
            $this->em->persist($chat);
            $this->em->persist($chatUser1);
            $this->em->persist($chatUser2);
            $this->em->flush();
        }

        return $this->createMessage($chat, $body);
    }

    /**
     * @param Chat $chat
     */
    public function deleteChat(Chat $chat)
    {
        foreach ($chat->getMessages() as $message) {
            $this->deleteMessage($message);
        }
    }

    /**
     * @param ChatMessage $message
     */
    public function deleteMessage(ChatMessage $message)
    {
        foreach ($message->getUsers() as $userMessage) {
            if ($userMessage->getUser()->getId() != $this->userManager->getCurrentUser()->getId()) {
                continue;
            }
            $userMessage->setIsDeleted(true);
            $this->em->persist($userMessage);
            $this->em->flush($userMessage);
        }
    }

    /**
     * @param ChatMessage $message
     */
    public function read(ChatMessage $message)
    {
        foreach ($message->getUsers() as $userMessage) {
            if ($userMessage->getUser()->getId() != $this->userManager->getCurrentUser()->getId()) {
                continue;
            }
            $userMessage->setIsRead(true);
            $this->em->persist($userMessage);
            $this->em->flush($userMessage);
        }
    }

    /**
     * @param ChatMessage $message
     * @return bool
     */
    public function isRead(ChatMessage $message)
    {
        foreach ($message->getUsers() as $userMessage) {
            if ($userMessage->getUser()->getId() != $this->userManager->getCurrentUser()->getId()) {
                continue;
            }
            return $userMessage->isRead();
        }
        return false;
    }

}
