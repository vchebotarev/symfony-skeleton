<?php

namespace App\Symfony\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller as BaseController;

abstract class AbstractController extends BaseController
{

    /**
     * @inheritDoc
     */
    protected function json($data, $status = 200, $headers = array(), $context = array())
    {
        $jsonResponse = parent::json($data, $status, $headers, $context);
        $jsonResponse->setEncodingOptions(JSON_UNESCAPED_UNICODE);
        return $jsonResponse;
    }

}
