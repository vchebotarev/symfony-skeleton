<?php

namespace App\Controller\Api\PublicArea;

use App\Symfony\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends AbstractController
{
    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function indexAction(Request $request)
    {
        return $this->json(['hello' => 'world']);
    }
}
