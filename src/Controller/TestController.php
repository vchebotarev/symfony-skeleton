<?php

namespace App\Controller;

use App\Symfony\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class TestController extends AbstractController
{
    /**
     * @param Request $request
     * @return Response
     */
    public function testAction(Request $request)
    {

        return new Response('Test');
    }

}
