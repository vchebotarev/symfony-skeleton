<?php

namespace AppBundle\Controller\Auth;

use App\Auth\Form\Type\RegistrationFormType;
use App\Symfony\Controller\AbstractController;
use AppBundle\Entity\UserToken;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class RegistrationController extends AbstractController
{
    /**
     * @param Request $request
     * @return Response
     */
    public function registerAction(Request $request)
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('app_private_default_index');
        }

        $form = $this->createForm(RegistrationFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $email = $form->getData()['email'];
            return $this->render('Auth/Registration/register_success.html.twig', [
                'email' => $email,
            ]);

        }

        return $this->render('Auth/Registration/register.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @param string $token
     * @return Response
     */
    public function confirmAction(string $token)
    {
        $userToken = $this->get('app.user.token_manager')->findTokenByHashAndType($token, UserToken::TYPE_REGISTRATION);
        if (!$userToken) {
            //todo защита от перебора
            $response = new Response();
            $response->setStatusCode(Response::HTTP_NOT_FOUND);
            return $this->render('Auth/Registration/confirm_error.html.twig', [], $response);
        }

        $user = $userToken->getUser();

        //Обновляем пользователя
        $this->get('app.user.manipulator')->enable($user);

        //Авторизовываем пользователя
        $this->get('app.auth.login_manager')->loginUserByLink($user, 'main');

        return $this->render('Auth/Registration/confirm_success.html.twig');
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function resendAction(Request $request)
    {
        return $this->render('');
    }

}
