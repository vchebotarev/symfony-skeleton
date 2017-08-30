<?php

namespace AppBundle\Controller\Auth;

use App\Symfony\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityController extends AbstractController
{
    /**
     * @param Request $request
     * @return Response
     */
    public function loginAction(Request $request)
    {
        return $this->render('');
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
