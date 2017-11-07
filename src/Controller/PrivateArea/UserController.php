<?php

namespace App\Controller\PrivateArea;

use App\Symfony\Controller\AbstractController;
use App\User\Security\UserVoter;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class UserController extends AbstractController
{
    /**
     * @param Request $request
     * @param int     $id
     * @return Response|RedirectResponse
     */
    public function viewAction(Request $request, $id)
    {
        $user = $this->get('app.user.manager')->findUserById($id);
        if (!$user) {
            throw $this->createNotFoundException();
        }
        if ($user->getId() == $this->getUser()->getId()) {
            return $this->redirectToRoute('app_private_profile_index');
        }
        $this->denyAccessUnlessGranted(UserVoter::VIEW, $user);

        return $this->render('PrivateArea/User/view.html.twig', [
            'user' => $user,
        ]);
    }


}
