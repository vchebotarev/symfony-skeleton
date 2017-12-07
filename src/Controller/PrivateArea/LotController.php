<?php

namespace App\Controller\PrivateArea;

use App\Entity\Lot;
use App\Entity\LotBet;
use App\Lot\Form\Type\LotCreateFormType;
use App\Lot\Form\Type\LotStartFormType;
use App\Lot\Security\LotVoter;
use App\Symfony\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class LotController extends AbstractController
{
    /**
     * @param Request $request
     * @return Response
     */
    public function listAction(Request $request)
    {
        //todo сделать поиск по аналогии с админкой

        return $this->render('PrivateArea/Lot/list.html.twig', [

        ]);
    }

    /**
     * @param Request $request
     * @param int     $id
     * @return Response
     */
    public function viewAction(Request $request, $id)
    {
        $lot = $this->findById($id, Lot::class, LotVoter::VIEW);

        return $this->render('PrivateArea/Lot/view.html.twig', [
            'lot' => $lot,
        ]);
    }

    /**
     * @param Request $request
     * @param int     $id
     * @return Response
     */
    public function viewBetsAction(Request $request, $id)
    {
        /** @var Lot $lot */
        $lot = $this->findById($id, Lot::class, LotVoter::VIEW);

        $bets = $this->getEm()->getRepository(LotBet::class)->findByLot($lot);

        return $this->render('PrivateArea/Lot/view_bets.html.twig', [
            'lot'  => $lot,
            'bets' => $bets,
        ]);
    }

    /**
     * @param Request $request
     * @return RedirectResponse|Response
     */
    public function addAction(Request $request)
    {
        $this->denyAccessUnlessGranted(LotVoter::ADD);

        $form = $this->createForm(LotCreateFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var Lot $lot */
            $lot = $form->getData();
            return $this->redirectToRoute('app_private_lot_view', [
                'id' => $lot->getId(),
            ]);
        }

        return $this->render('PrivateArea/Lot/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @param Request $request
     * @param int     $id
     * @return RedirectResponse|Response
     */
    public function startAction(Request $request, $id)
    {
        /** @var Lot $lot */
        $lot = $this->findById($id, Lot::class, LotVoter::START);

        $form = $this->createForm(LotStartFormType::class, null, [
            'lot'  => $lot,
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            return $this->redirectToRoute('app_private_lot_view', [
                'id' => $lot->getId(),
            ]);
        }

        return $this->render('PrivateArea/Lot/start.html.twig', [
            'lot'  => $lot,
            'form' => $form->createView(),
        ]);
    }

}
