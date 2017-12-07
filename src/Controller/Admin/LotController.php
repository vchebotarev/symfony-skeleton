<?php

namespace App\Controller\Admin;

use App\Entity\Lot;
use App\Entity\LotBet;
use App\Lot\Form\Type\LotSearchFormType;
use App\Lot\Security\LotVoter;
use App\Symfony\Controller\AbstractController;
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
        $form = $this->createForm(LotSearchFormType::class);
        $form->handleRequest($request);

        $search = $this->get('chebur.search.manager')
            ->createBuilderAdmin()
            ->setItemsSource($this->get('app.lot.search.source'), $form->getData())
            ->build($request);

        return $this->render('Admin/Lot/list.html.twig', [
            'form'   => $form->createView(),
            'search' => $search,
        ]);
    }

    /**
     * @param Request $request
     * @param  int    $id
     * @return Response
     */
    public function viewAction(Request $request, $id)
    {
        /** @var Lot $lot */
        $lot = $this->findById($id, Lot::class, LotVoter::VIEW);

        return $this->render('Admin/Lot/view.html.twig', [
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

        return $this->render('Admin/Lot/view_bets.html.twig', [
            'lot'  => $lot,
            'bets' => $bets,
        ]);
    }

}
