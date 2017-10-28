<?php

namespace AppBundle\Controller\PrivateArea;

use App\Symfony\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends AbstractController
{
    /**
     * @param Request $request
     * @return Response
     */
    public function indexAction(Request $request)
    {
        return $this->render('PrivateArea/Default/index.html.twig', [

        ]);
    }

}
