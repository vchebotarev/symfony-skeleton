<?php

namespace AppBundle\Controller\Auth;

use App\Auth\Form\Type\ResetFormType;
use App\Auth\Form\Type\ResetRequestFormType;
use App\Symfony\Controller\AbstractController;
use AppBundle\Entity\UserToken;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ResetController extends AbstractController
{
    /**
     * @param Request $request
     * @return Response
     */
    public function requestAction(Request $request)
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('app_private_default_index');
        }

        $form = $this->createForm(ResetRequestFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $email = $form->getData()['email'];
            return $this->render('@App/Auth/Reset/request_success.html.twig', [
                'email' => $email,
            ]);
        }

        return $this->render('@App/Auth/Reset/request.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @param Request $request
     * @param string  $token
     * @return Response
     */
    public function resetAction(Request $request, string $token)
    {
        $tokenManager = $this->get('app.user.token_manager');
        $userToken = $tokenManager->findTokenByHashAndType($token, UserToken::TYPE_RESET_PASSWORD);
        if (!$userToken) {
            //todo защита от перебора
            $response = new Response();
            $response->setStatusCode(Response::HTTP_NOT_FOUND);
            return $this->render('@App/Auth/Reset/reset_error.html.twig', [], $response);
        }

        $formReset = $this->createForm(ResetFormType::class);
        $formReset->handleRequest($request);

        if ($formReset->isSubmitted() && $formReset->isValid()) {
            return $this->render('@App/Auth/Reset/reset_success.html.twig');
        }

        return $this->render('@App/Auth/Reset/reset.html.twig', [
            'form' => $formReset->createView(),
        ]);
    }

}
