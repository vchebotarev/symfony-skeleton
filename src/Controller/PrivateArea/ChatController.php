<?php

namespace App\Controller\PrivateArea;

use App\Chat\Message\Form\Type\ChatMessageCreateFormType;
use App\Chat\Security\ChatVoter;
use App\Entity\Chat;
use App\Symfony\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ChatController extends AbstractController
{
    /**
     * @param Request $request
     * @return Response
     */
    public function listAction(Request $request)
    {
        if ($request->isXmlHttpRequest()) {
            return $this->listAjaxAction($request);
        }
        $search = $this->get('chebur.search.manager')
            ->createBuilder()
            ->setItemsSource($this->get('app.chat.search.chats'))
            ->setLimit(10)
            ->build();

        return $this->render('PrivateArea/Chat/list.html.twig', [
            'search' => $search,
        ]);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    protected function listAjaxAction(Request $request)
    {
        $search = $this->get('chebur.search.manager')
            ->createBuilder()
            ->setItemsSource($this->get('app.chat.search.chats'))
            ->setParamNamePage('page') //todo фильтрация по странице не подходит если будет иметь место подгрузка диалогов - надо делать по дате, учитывая "односекундность"
            ->setLimit(10)
            ->build($request);

        return $this->json([
            'count'       => count($search->getItems()),
            'total_count' => $search->getTotalCount(),
            'html'        => $this->renderView('PrivateArea/Chat/_list.html.twig', [
                'search' => $search,
            ]),
        ]);
    }

    /**
     * @param Request $request
     * @param int     $id
     * @return Response
     */
    public function viewAction(Request $request, $id)
    {
        $chat = $this->findChat($id, ChatVoter::VIEW);

        $search = $this->get('chebur.search.manager')
            ->createBuilder()
            ->setItemsSource($this->get('app.chat.message.search'), [
                'chat' => $chat,
            ])
            ->setLimit(10)
            ->build();

        $form = $this->createForm(ChatMessageCreateFormType::class, null, [
            'action' => $this->generateUrl('app_private_chat_message_create', ['chatId' => $chat->getId()]),
            'chat'   => $chat,
        ]);

        return $this->render('PrivateArea/Chat/view.html.twig', [
            'search' => $search,
            'chat'   => $chat,
            'form'   => $form->createView(),
        ]);
    }

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
        $chat = $this->findChat($id, ChatVoter::DELETE);

        $this->get('app.chat.manager')->deleteChat($chat);

        return $this->json([
            'url' => $this->generateUrl('app_private_chat_list'),
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

        $count = $this->get('app.chat.counter')->countUnreadChats($this->getUser());

        return $this->json([
            'count' => $count,
        ]);
    }

    /**
     * @param int         $id
     * @param null|string $attr
     * @return Chat
     */
    protected function findChat(int $id, $attr = null)
    {
        $chat = $this->get('app.chat.manager')->findChatById($id);
        if (!$chat) {
            throw $this->createNotFoundException();
        }
        if ($attr !== null) {
            $this->denyAccessUnlessGranted($attr, $chat);
        }
        return $chat;
    }

}
