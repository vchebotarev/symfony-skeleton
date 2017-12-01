<?php

namespace App\Controller\Admin;

use App\Symfony\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class TicketMessageController extends AbstractController
{
    /**
     * @param Request $request
     * @param int     $ticketId
     * @return JsonResponse
     */
    public function listAction(Request $request, $ticketId)
    {
        if (!$request->isXmlHttpRequest()) {
            throw $this->createNotFoundException();
        }

        //todo

        return $this->json([
            //todo
        ]);
    }

    /**
     * @param Request $request
     * @param int     $ticketId
     * @return JsonResponse
     */
    public function createAction(Request $request, $ticketId)
    {
        if (!$request->isXmlHttpRequest()) {
            throw $this->createNotFoundException();
        }

        //todo

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

        $count = $this->get('app.ticket.counter')->countUnreadMessages($this->getUser());

        return $this->json([
            'count' => $count,
        ]);
    }

}
