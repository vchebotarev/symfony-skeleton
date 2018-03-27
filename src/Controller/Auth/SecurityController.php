<?php

namespace App\Controller\Auth;

use App\Symfony\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class SecurityController extends AbstractController
{
    /**
     * @return Response
     */
    public function loginAction()
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('app_private_default_index');
        }

        $form = $this->get('chebur.login_form.form.helper')->getLoginForm('main');

        $resourceOwners = $this->get('hwi_oauth.security.oauth_utils')->getResourceOwnersObjects();

        return $this->render('Auth/Security/login.html.twig', [
            'form'            => $form->createView(),
            'resource_owners' => $resourceOwners,
        ]);
    }

    public function checkAction()
    {
        throw new \RuntimeException();
    }

    public function checkOauthAction()
    {
        throw new \RuntimeException();
    }

    public function logoutAction()
    {
        throw new \RuntimeException();
    }
}
