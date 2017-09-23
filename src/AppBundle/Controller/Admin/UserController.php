<?php

namespace AppBundle\Controller\Admin;

use App\Symfony\Controller\AbstractController;
use App\User\Security\UserVoter;
use AppBundle\Entity\User;
use AppBundle\Entity\UserAuthLog;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class UserController extends AbstractController
{
    /**
     * @param Request $request
     * @param int     $id
     * @return Response
     */
    public function authLogAction(Request $request, $id)
    {
        $user = $this->findUser($id, UserVoter::VIEW_AUTH_LOG);

        $logs = $this->getEm()->getRepository(UserAuthLog::class)->findByUser($user);

        return $this->render('@App/Admin/User/auth_log.html.twig', [
            'user' => $user,
            'logs' => $logs,
        ]);
    }

    /**
     * @param int   $id
     * @param mixed $attr
     * @return User|null
     */
    protected function findUser($id, $attr = null)
    {
        $user = $this->get('app.user.manager')->findUserById($id);
        if (!$user) {
            throw $this->createNotFoundException();
        }
        if ($attr) {
            $this->denyAccessUnlessGranted($attr, $user);
        }
        return $user;
    }

}
