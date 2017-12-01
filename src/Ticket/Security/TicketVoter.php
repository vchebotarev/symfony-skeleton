<?php

namespace App\Ticket\Security;

use App\Entity\Ticket;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\AccessDecisionManagerInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class TicketVoter extends Voter
{
    const CREATE  = 'ticket.create';
    const SEND    = 'ticket.message.send';
    const VIEW    = 'ticket.view';
    const ARCHIVE = 'ticket.archive';

    /**
     * @var AccessDecisionManagerInterface
     */
    protected $decisionManager;

    /**
     * @param AccessDecisionManagerInterface $decisionManager
     */
    public function __construct(AccessDecisionManagerInterface $decisionManager)
    {
        $this->decisionManager = $decisionManager;
    }

    /**
     * @inheritDoc
     */
    protected function supports($attribute, $subject)
    {
        if ($attribute == self::CREATE && $subject instanceof User) {
            return true;
        }
        if ($attribute == self::VIEW && $subject instanceof Ticket) {
            return true;
        }
        if ($attribute == self::ARCHIVE && $subject instanceof Ticket) {
            return true;
        }
        if ($attribute == self::SEND && $subject instanceof Ticket) {
            return true;
        }

        return false;
    }

    /**
     * @inheritDoc
     */
    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        /** @var User $currentUser */
        $currentUser = $token->getUser();

        if ($attribute == self::CREATE && $subject instanceof User) {
            if (!$subject->isEnabled()) {
                return false;
            }
            if ($subject->isLocked()) {
                return false;
            }
            if ($subject->isAdmin()) { //todo use decisionManager
                return false;
            }
            return true;
        }

        if ($attribute == self::VIEW && $subject instanceof Ticket) {
            if ($this->decisionManager->decide($token, [User::ROLE_ADMIN])) {
                return true;
            }
            if ($subject->getUser()->getId() == $currentUser->getId()) {
                return true;
            }
            return false;
        }

        if ($attribute == self::ARCHIVE && $subject instanceof Ticket) {
            if ($subject->getStatus() == Ticket::STATUS_ARCHIVE) {
                return false;
            }
            if ($this->decisionManager->decide($token, [User::ROLE_ADMIN])) {
                return true;
            }
            if ($subject->getUser()->getId() == $currentUser->getId()) {
                return true;
            }
            return false;
        }

        if ($attribute == self::SEND && $subject instanceof Ticket) {
            if ($subject->getStatus() == Ticket::STATUS_ARCHIVE) {
                return false;
            }
            if ($this->decisionManager->decide($token, [User::ROLE_ADMIN])) {
                return true;
            }
            if ($subject->getUser()->getId() == $currentUser->getId()) {
                return true;
            }
            return false;
        }

        throw new \RuntimeException();
    }

}
