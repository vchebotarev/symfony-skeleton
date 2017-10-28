<?php

namespace AppBundle\Controller\PublicArea;

use App\Symfony\Controller\AbstractController;
use AppBundle\Entity\UserToken;
use Symfony\Component\HttpFoundation\Response;

class EmailController extends AbstractController
{
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
            return $this->render('PublicArea/Email/change_email_confirm_error_token.html.twig', [], $response);
        }

        $user     = $userToken->getUser();
        $newEmail = $userToken->getData()['email'];

        //Проверяем не заняли ли уже емейл
        $userEmail = $this->get('app.user.manager')->findUserByEmail($newEmail);
        if ($userEmail) {
            $response = new Response();
            $response->setStatusCode(Response::HTTP_FORBIDDEN);
            return $this->render('PublicArea/Email/change_email_confirm_error_email.html.twig', [], $response);
        }

        //Изменяем почту
        $this->get('app.user.manipulator')->changeEmail($user, $newEmail);

        //Авторизовываем пользователя
        $this->get('app.auth.login_manager')->loginUserByLink($user, 'main');

        return $this->render('PublicArea/Email/change_email_confirm_success.html.twig');
    }

}
