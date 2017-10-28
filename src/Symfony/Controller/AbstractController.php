<?php

namespace App\Symfony\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller as BaseController;

abstract class AbstractController extends BaseController
{
    /**
     * @return User|null
     */
    public function getUser()
    {
        return $this->get('app.user.manager')->getCurrentUser();
    }

    /**
     * @param string|null $name
     * @return EntityManager
     */
    protected function getEm($name = null)
    {
        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager($name);
        return $em;
    }

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
