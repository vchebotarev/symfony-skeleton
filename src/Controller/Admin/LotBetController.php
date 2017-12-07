<?php

namespace App\Controller\Admin;

use App\Entity\LotBet;
use App\Lot\Bet\Security\LotBetVoter;
use App\Symfony\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class LotBetController extends AbstractController
{
    /**
     * @param Request $request
     * @param int     $id
     * @return JsonResponse
     */
    public function deleteAction(Request $request, $id)
    {
        if (!$request->isXmlHttpRequest()) {
            throw $this->createNotFoundException();
        }

        /** @var LotBet $lotBet */
        $lotBet = $this->findById($id, LotBet::class, LotBetVoter::DELETE);

        $this->get('app.lot.bet.manager')->deleteBet($lotBet);

        return $this->json([
            'success' => 1,
        ]);
    }
}
