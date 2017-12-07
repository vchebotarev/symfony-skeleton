<?php

namespace App\Lot\Bet\Security;

use App\Entity\Lot;
use App\Entity\LotBet;
use App\Entity\User;
use App\Lot\Bet\LotBetManager;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\AccessDecisionManagerInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class LotBetVoter extends Voter
{
    const CREATE = 'lot.bet.create';
    const DELETE = 'lot.bet.delete';

    /**
     * @var LotBetManager
     */
    protected $lotBetManager;

    /**
     * @var AccessDecisionManagerInterface
     */
    protected $decisionManager;

    /**
     * @param LotBetManager                  $lotBetManager
     * @param AccessDecisionManagerInterface $decisionManager
     */
    public function __construct(LotBetManager $lotBetManager, AccessDecisionManagerInterface $decisionManager)
    {
        $this->lotBetManager = $lotBetManager;
        $this->decisionManager = $decisionManager;
    }

    /**
     * @inheritDoc
     */
    protected function supports($attribute, $subject)
    {
        if ($attribute == self::CREATE && $subject instanceof Lot) {
            return true;
        }
        if ($attribute == self::DELETE && $subject instanceof LotBet) {
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

        if ($attribute == self::CREATE && $subject instanceof Lot) {
            if ($subject->getStatus() != Lot::STATUS_ACTIVE) {
                return false;
            }
            if ($currentUser->getId() == $subject->getUser()->getId()) {
                return false;
            }
            $lastLot = $subject->getBetLast();
            if ($lastLot && $lastLot->getUser()->getId() == $currentUser->getId()) {
                return false;
            }
            return true;
        }
        if ($attribute == self::DELETE && $subject instanceof LotBet) {
            if ($subject->getLot()->getStatus() != Lot::STATUS_ACTIVE) {
                return false;
            }
            return $this->decisionManager->decide($token, [User::ROLE_ADMIN]);
        }

        throw new \RuntimeException();
    }

}
