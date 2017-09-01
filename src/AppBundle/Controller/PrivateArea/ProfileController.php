<?php

namespace AppBundle\Controller\PrivateArea;

use App\Symfony\Controller\AbstractController;
use App\User\Form\Type\ChangeEmailType;
use App\User\Form\Type\ChangePasswordType;
use App\User\Form\Type\ChangeUsernameType;
use App\User\Security\UserVoter;
use AppBundle\Entity\UserToken;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ProfileController extends AbstractController
{

    /**
     * @return Response
     */
    public function indexAction()
    {
        return $this->render('@App/PrivateArea/Profile/index.html.twig');
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function changeUsernameAction(Request $request)
    {
        $form = $this->createForm(ChangeUsernameType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            return $this->render('@App/PrivateArea/Profile/change_username_success.html.twig');
        }

        return $this->render('@App/PrivateArea/Profile/change_username.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function changePasswordAction(Request $request)
    {
        $form = $this->createForm(ChangePasswordType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            return $this->render('@App/PrivateArea/Profile/change_password_success.html.twig');
        }

        return $this->render('@App/PrivateArea/Profile/change_password.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function changeEmailAction(Request $request)
    {
        if (!$this->isGranted(UserVoter::CHANGE_EMAIL)) {
            $response = new Response();
            $response->setStatusCode(Response::HTTP_FORBIDDEN);
            return $this->render('@App/PrivateArea/Profile/change_email_error.html.twig', [], $response);
        }

        $form = $this->createForm(ChangeEmailType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $email = $form->getData()['email'];
            return $this->render('@App/PrivateArea/Profile/change_email_success.html.twig', [
                'email' => $email,
            ]);
        }

        return $this->render('@App/PrivateArea/Profile/change_email.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @param string $token
     * @return Response
     */
    public function changeEmailConfirmAction($token)
    {
        $userToken = $this->get('app.user.token_manager')->findTokenByHashAndType($token, UserToken::TYPE_CHANGE_EMAIL);
        if (!$userToken) {
            $response = new Response();
            $response->setStatusCode(Response::HTTP_FORBIDDEN);
            return $this->render('@App/PrivateArea/Profile/change_email_confirm_error_token.html.twig', [], $response);
        }

        $user     = $userToken->getUser();
        $newEmail = $userToken->getData()['email'];

        //Проверяем не заняли ли уже емейл
        $userEmail = $this->get('app.user.manager')->findUserByEmail($newEmail);
        if ($userEmail) {
            $response = new Response();
            $response->setStatusCode(Response::HTTP_FORBIDDEN);
            return $this->render('@App/PrivateArea/Profile/change_email_confirm_error_email.html.twig', [], $response);
        }

        //Изменяем почту
        $this->get('app.user.manipulator')->changeEmail($user, $newEmail);

        //Авторизовываем пользователя
        $this->get('app.auth.login_manager')->loginUserByLink($user, 'main');

        return $this->render('@App/PrivateArea/Profile/change_email_confirm_success.html.twig');
    }

}
