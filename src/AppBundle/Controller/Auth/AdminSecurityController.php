<?php

namespace AppBundle\Controller\Auth;

use App\Symfony\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminSecurityController extends AbstractController
{
    /**
     * @return Response
     */
    public function loginAction()
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('app_public_default_index');
        }

        $form = $this->get('chebur.login_form.form.helper')->getLoginForm('admin');

        return $this->render('@App/Auth/AdminSecurity/login.html.twig', [
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
