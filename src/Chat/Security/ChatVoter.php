<?php

namespace App\Chat\Security;

use App\Entity\Chat;
use App\Entity\ChatMessage;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class ChatVoter extends Voter
{
    const VIEW   = 'chat.view';
    const SEND   = 'chat.message.send';
    const DELETE = 'chat.delete';
    const READ   = 'chat.message.read';

    /**
     * @inheritDoc
     */
    protected function supports($attribute, $subject)
    {
        if ($subject instanceof Chat && $attribute == self::VIEW) {
            return true;
        }
        if ($subject instanceof Chat && $attribute == self::DELETE) {
            return true;
        }
        if ($subject instanceof ChatMessage && $attribute == self::DELETE) {
            return true;
        }
        if ($subject instanceof Chat && $attribute == self::SEND) {
            return true;
        }
        if ($subject instanceof User && $attribute == self::SEND) {
            return true;
        }
        if ($subject instanceof ChatMessage && $attribute == self::READ) {
            return true;
        }

        return false;
    }

    /**
     * @inheritDoc
     */
    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        /** @var User|null $currentUser */
        $currentUser = $token->getUser();
        $currentUser = $currentUser instanceof User ? $currentUser : null;

        if (!$currentUser) {
            return false;
        }

        if ($subject instanceof Chat && $attribute == self::VIEW) {
            foreach ($subject->getUsers() as $chatUser) {
                if ($currentUser->getId() == $chatUser->getUser()->getId()) {
                    return true;
                }
            }
            return false;
        }
        if ($subject instanceof Chat && $attribute == self::DELETE) {
            foreach ($subject->getUsers() as $chatUser) {
                if ($currentUser->getId() == $chatUser->getUser()->getId()) {
                    return true;
                }
            }
            return false;
        }
        if ($subject instanceof ChatMessage && $attribute == self::DELETE) {
            foreach ($subject->getUsers() as $userMessage) {
                if ($currentUser->getId() == $userMessage->getUser()->getId()) {
                    if ($userMessage->isDeleted()) {
                        return false;
                    }
                    return true;
                }
            }
            return false;
        }
        if ($subject instanceof Chat && $attribute == self::SEND) {
            $chatUsers = $subject->getUsers();
            $hasMe = false;
            $hasLocked = false;
            foreach ($chatUsers as $chatUser) {
                if ($currentUser->getId() == $chatUser->getUser()->getId()) {
                    $hasMe = true;
                }
                if ($chatUser->getUser()->isLocked()) {
                    $hasLocked = true;
                }
            }
            if (!$hasMe) {
                return false;
            }
            if (count($chatUsers) == 2 && $hasLocked) {
                return false;
            }
            return true;
        }
        if ($subject instanceof User && $attribute == self::SEND) {
            if ($subject->isLocked()) {
                return false;
            }
            if ($subject->getId() == $currentUser->getId()) {
                return false;
            }
            return true;
        }
        if ($subject instanceof ChatMessage && $attribute == self::READ) {
            foreach ($subject->getUsers() as $userMessage) {
                if ($currentUser->getId() == $userMessage->getUser()->getId()) {
                    if ($userMessage->isRead()) {
                        return false;
                    }
                    return true;
                }
            }
            return false;
        }

        throw new \RuntimeException();
    }

}
