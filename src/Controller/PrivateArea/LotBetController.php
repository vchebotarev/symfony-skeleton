<?php

namespace App\Controller\PrivateArea;

use App\Entity\Lot;
use App\Lot\Bet\Form\Type\LotBetCreateFormType;
use App\Lot\Bet\Security\LotBetVoter;
use App\Symfony\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class LotBetController extends AbstractController
{
    /**
     * @param Request $request
     * @param int     $lotId
     * @return RedirectResponse|Response
     */
    public function createAction(Request $request, $lotId)
    {
        /** @var Lot $lot */
        $lot = $this->findById($lotId, Lot::class, LotBetVoter::CREATE);

        $form = $this->createForm(LotBetCreateFormType::class, null, [
            'lot' => $lot,
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            return $this->redirectToRoute('app_private_lot_view_bets', [
                'id' => $lot->getId(),
            ]);
        }

        return $this->render('PrivateArea/LotBet/create.html.twig', [
            'form' => $form->createView(),
            'lot'  => $lot,
        ]);
    }

}
