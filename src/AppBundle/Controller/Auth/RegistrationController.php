<?php

namespace AppBundle\Controller\Auth;

use App\Symfony\Controller\AbstractController;
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
        return $this->render('');
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function confirmAction(Request $request)
    {
        return $this->render('');
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
