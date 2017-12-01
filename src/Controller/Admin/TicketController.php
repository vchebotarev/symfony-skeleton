<?php

namespace App\Controller\Admin;

use App\Entity\Ticket;
use App\Symfony\Controller\AbstractController;
use App\Ticket\Security\TicketVoter;
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

        return $this->render('Admin/Ticket/list.html.twig', [

        ]);
    }

    /**
     * @param Request $request
     * @param int     $id
     * @return Response
     */
    public function viewAction(Request $request, $id)
    {
        $ticket = $this->findTicket($id, TicketVoter::VIEW);

        //todo

        return $this->render('Admin/Ticket/view.html.twig', [

        ]);
    }

    /**
     * @param Request $request
     * @param int     $userId
     * @return Response
     */
    public function createAction(Request $request, $userId)
    {
        //todo

        return $this->render('Admin/Ticket/create.html.twig', [

        ]);
    }

    /**
     * @param Request $request
     * @param int     $id
     * @return JsonResponse
     */
    public function archiveAction(Request $request, $id)
    {
        if (!$request->isXmlHttpRequest()) {
            throw $this->createNotFoundException();
        }

        $ticket = $this->findTicket($id, TicketVoter::ARCHIVE);

        $this->get('app.ticket.manager')->archive($ticket);

        return $this->json([
            'success' => 1,
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

    /**
     * @param int         $id
     * @param null|string $attr
     * @return Ticket
     */
    protected function findTicket(int $id, $attr = null)
    {
        $chat = $this->get('app.ticket.manager')->findTicketById($id);
        if (!$chat) {
            throw $this->createNotFoundException();
        }
        if ($attr !== null) {
            $this->denyAccessUnlessGranted($attr, $chat);
        }
        return $chat;
    }

}
