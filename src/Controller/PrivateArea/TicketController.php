<?php

namespace App\Controller\PrivateArea;

use App\Symfony\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class TicketController extends AbstractController
{
    /**
     * @param Request $request
     * @return Response
     */
    public function listAction(Request $request)
    {
        //todo

        return $this->render('PrivateArea/Ticket/list.html.twig', [

        ]);
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function viewAction(Request $request)
    {
        //todo

        return $this->render('PrivateArea/Ticket/view.html.twig', [

        ]);
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function createAction(Request $request)
    {
        //todo

        return $this->render('PrivateArea/Ticket/create.html.twig', [

        ]);
    }

    /**
     * @param Request $request
     * @param int     $id
     * @return JsonResponse
     */
    public function archiveAction(Request $request, $id)
    {

        return $this->json([
            //todo
        ]);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function countUnreadAction(Request $request)
    {
        if (!$request->isXmlHttpRequest()) {
            throw $this->createNotFoundException();
        }

        $count = $this->get('app.ticket.counter')->countUnread($this->getUser());

        return $this->json([
            'count' => $count,
        ]);
    }

}
