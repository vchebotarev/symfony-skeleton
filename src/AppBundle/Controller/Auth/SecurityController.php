<?php

namespace AppBundle\Controller\Auth;

use App\Symfony\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityController extends AbstractController
{
    /**
     * @return Response
     */
    public function loginAction()
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('app_public_default_index');
        }

        $form = $this->get('chebur.login_form.form.helper')->getLoginForm('main');

        return $this->render('@App/Auth/Security/login.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    public function checkAction()
    {
        throw new \RuntimeException();
    }

    public function logoutAction()
    {
        throw new \RuntimeException();
    }
}
