<?php

namespace App\Controller\PrivateArea;

use App\Chat\Message\Form\Type\ChatMessageCreateFormType;
use App\Chat\Security\ChatVoter;
use App\Doctrine\Entity\Chat;
use App\Doctrine\Entity\ChatMessage;
use App\Doctrine\Entity\User;
use App\Search\Param;
use App\Symfony\Controller\AbstractController;
use Symfony\Component\Form\FormErrorIterator;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ChatMessageController extends AbstractController
{
    /**
     * @param Request $request
     * @param int     $chatId
     * @return JsonResponse
     */
    public function listAction(Request $request, $chatId)
    {
        if (!$request->isXmlHttpRequest()) {
            throw $this->createNotFoundException();
        }

        /** @var ChatVoter $chat */
        $chat = $this->findById($chatId, Chat::class, ChatVoter::VIEW);

        $search = $this->get('chebur.search.manager')
            ->createBuilder()
            ->setItemsSource($this->get('app.chat.message.search'), [
                'chat'         => $chat,
                Param::TO => $request->query->get(Param::TO),
            ])
            ->setLimit(10)
            ->build();

        return $this->json([
            'count'       => count($search->getItems()),
            'total_count' => $search->getTotalCount(),
            'html'        => $this->renderView('PrivateArea/ChatMessage/_list.html.twig', [
                'chat'   => $chat,
                'search' => $search,
            ])
        ]);
    }

    /**
     * @param Request $request
     * @param int     $chatId
     * @return JsonResponse
     */
    public function createAction(Request $request, $chatId)
    {
        if (!$request->isXmlHttpRequest()) {
            throw $this->createNotFoundException();
        }

        /** @var Chat $chat */
        $chat = $this->findById($chatId, Chat::class, ChatVoter::SEND);

        $form = $this->createForm(ChatMessageCreateFormType::class, null, [
            'chat' => $chat,
        ]);
        $form->handleRequest($request);

        $success = $form->isSubmitted() && $form->isValid();
        if ($success) {
            $message = $form->getData();
            $message = $this->get('app.chat.message.search.transformer')->transform([$message])[$message->getId()];
            $messageHtml = $this->renderView('PrivateArea/ChatMessage/_list_item.html.twig', [
                'message' => $message,
            ]);
        } else {
            $messageHtml = '';
        }

        $flattenErrors = function(FormErrorIterator $errors){
            $result = [];
            foreach ($errors as $error) {
                $result[] = $error->getMessage();
            }
            return $result;
        };

        return $this->json([
            'success' => $success,
            'message' => $messageHtml,
            'error'   => $flattenErrors($form->getErrors(true)),
        ]);
    }

    /**
     * @param Request $request
     * @param int     $userId
     * @return Response
     */
    public function createByUserAction(Request $request, $userId)
    {
        /** @var User $user */
        $user = $this->findById($userId, User::class, ChatVoter::SEND);

        $form = $this->createForm(ChatMessageCreateFormType::class, null, [
            'user' => $user,
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $chat = $this->get('app.chat.manager')->findChatByUser($user);
            return $this->render('PrivateArea/ChatMessage/create_success.html.twig', [
                'user' => $user,
                'chat' => $chat,
            ]);
        }

        $chat = $this->get('app.chat.manager')->findChatByUser($user);

        return $this->render('PrivateArea/ChatMessage/create.html.twig', [
            'form' => $form->createView(),
            'user' => $user,
            'chat' => $chat,
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

        /** @var ChatMessage $chatMessage */
        $chatMessage = $this->findById($id, ChatMessage::class, ChatVoter::DELETE);

        $this->get('app.chat.manager')->deleteMessage($chatMessage);

        return $this->json([
            'success' => 1,
        ]);
    }

    /**
     * @todo
     * @param Request $request
     * @return JsonResponse
     */
    public function readAction(Request $request)
    {
        if (!$request->isXmlHttpRequest()) {
            throw $this->createNotFoundException();
        }
        if (!$request->isMethod(Request::METHOD_POST)) {
            throw $this->createNotFoundException();
        }
        $ids = $request->request->get('id');
        if (!is_array($ids) || empty($ids)) {
            throw $this->createNotFoundException();
        }
        $chatManager = $this->get('app.chat.manager');

        $messages = $chatManager->findChatMessagesByIds($ids);
        foreach ($messages as $message) {
            if (!$this->isGranted(ChatVoter::READ, $message)) {
                continue;
            }
            $chatManager->read($message);
        }

        return $this->json([
            'success' => 1,
        ]);
    }

    /**
     * @todo
     * @param Request $request
     * @return JsonResponse
     */
    public function isReadAction(Request $request)
    {
        if (!$request->isXmlHttpRequest()) {
            throw $this->createNotFoundException();
        }
        $ids = $request->query->get('id');
        if (!is_array($ids) || empty($ids)) {
            throw $this->createNotFoundException();
        }
        $chatManager = $this->get('app.chat.manager');

        $messages = $chatManager->findChatMessagesByIds($ids);
        $result = [];
        foreach ($messages as $message) {
            $result[$message->getId()] = $chatManager->isRead($message);
        }

        return $this->json($result);
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

        $count = $this->get('app.chat.counter')->countUnreadMessages($this->getUser());

        return $this->json([
            'count' => $count,
        ]);
    }

}
