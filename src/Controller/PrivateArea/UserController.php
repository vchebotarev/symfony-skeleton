<?php

namespace App\Controller\PrivateArea;

use App\Doctrine\Entity\User;
use App\Doctrine\Entity\UserReview;
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
        /** @var User $user */
        $user = $this->findById($id, User::class, UserVoter::VIEW);

        if ($user->getId() == $this->getUser()->getId()) {
            return $this->redirectToRoute('app_private_profile_index');
        }

        return $this->render('PrivateArea/User/view.html.twig', [
            'user' => $user,
        ]);
    }

    /**
     * @param Request $request
     * @param int     $id
     * @return Response
     */
    public function viewReviewsAction(Request $request, $id)
    {
        /** @var User $user */
        $user = $this->findById($id, User::class, UserVoter::VIEW);

        $reviews = $this->getEm()->getRepository(UserReview::class)->findByUser($user);

        return $this->render('/PrivateArea/User/view_reviews.html.twig', [
            'reviews' => $reviews,
            'user'    => $user,
        ]);
    }
}
