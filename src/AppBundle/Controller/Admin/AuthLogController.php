<?php

namespace AppBundle\Controller\Admin;

use App\Symfony\Controller\AbstractController;
use App\User\Security\UserVoter;
use AppBundle\Entity\UserAuthLog;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthLogController extends AbstractController
{
    /**
     * @param Request $request
     * @return Response
     */
    public function listAction(Request $request)
    {
        $this->denyAccessUnlessGranted(UserVoter::VIEW_AUTH_LOG);

        $logs = $this->getEm()->getRepository(UserAuthLog::class)->findAll();

        return $this->render('@App/Admin/AuthLog/list.html.twig', [
            'logs' => $logs,
        ]);
    }

}
