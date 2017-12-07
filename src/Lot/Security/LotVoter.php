<?php

namespace App\Lot\Security;

use App\Entity\Lot;
use App\Entity\User;
use App\Lot\Bet\LotBetManager;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\AccessDecisionManagerInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class LotVoter extends Voter
{
    const ADD   = 'lot.add';
    const VIEW  = 'lot.view';
    const START = 'lot.start';

    /**
     * @var AccessDecisionManagerInterface
     */
    protected $decisionManager;

    /**
     * @var LotBetManager
     */
    protected $lotBetManager;

    /**
     * @param AccessDecisionManagerInterface $decisionManager
     * @param LotBetManager                  $lotBetManager
     */
    public function __construct(AccessDecisionManagerInterface $decisionManager, LotBetManager $lotBetManager)
    {
        $this->decisionManager = $decisionManager;
        $this->lotBetManager   = $lotBetManager;
    }

    /**
     * @inheritDoc
     */
    protected function supports($attribute, $subject)
    {
        if ($attribute == self::VIEW && $subject instanceof Lot) {
            return true;
        }
        if ($attribute == self::ADD) {
            return true;
        }
        if ($attribute == self::START && $subject instanceof Lot) {
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

        if ($attribute == self::VIEW && $subject instanceof Lot) {
            if ($currentUser->getId() == $subject->getUser()->getId()) {
                return true;
            }
            if ($subject->getStatus() == Lot::STATUS_ACTIVE) {
                return true;
            }
            if ($this->decisionManager->decide($token, [User::ROLE_ADMIN])) {
                return true;
            }
            //todo показывать если close + ставку делал
            return false;
        }
        if ($attribute == self::ADD) {
            return true;
        }
        if ($attribute == self::START && $subject instanceof Lot) {
            if ($subject->getUser()->getId() != $currentUser->getId()) {
                return false;
            }
            if ($subject->getStatus() != Lot::STATUS_DRAFT) {
                return false;
            }
            return true;
        }

        throw new \RuntimeException();
    }

}
