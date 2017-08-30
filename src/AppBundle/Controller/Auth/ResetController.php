<?php

namespace AppBundle\Controller\Auth;

use App\Symfony\Controller\AbstractController;
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
        return $this->render('');
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function resetAction(Request $request)
    {
        return $this->render('');
    }

}
