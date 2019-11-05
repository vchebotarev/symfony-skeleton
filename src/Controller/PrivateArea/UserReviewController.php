<?php

namespace App\Controller\PrivateArea;

use App\Doctrine\Entity\User;
use App\Doctrine\Entity\UserReview;
use App\Symfony\Controller\AbstractController;
use App\User\Review\Form\Type\UserReviewCreateFormType;
use App\User\Review\Security\UserReviewVoter;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class UserReviewController extends AbstractController
{
    /**
     * @param Request $request
     * @param int     $userId
     * @return RedirectResponse|Response
     */
    public function createAction(Request $request, $userId)
    {
        /** @var User $user */
        $user = $this->findById($userId, User::class, UserReviewVoter::CREATE);

        $form = $this->createForm(UserReviewCreateFormType::class, null, [
            'user' => $user,
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            return $this->redirectToRoute('app_private_user_view_reviews', [
                'id' => $user->getId(),
            ]);
        }

        return $this->render('PrivateArea/UserReview/create.html.twig', [
            'form' => $form->createView(),
            'user' => $user,
        ]);
    }

    /**
     * @param Request $request
     * @param int     $id
     * @return JsonResponse
     */
    public function deleteAction(Request $request, $id)
    {
        if (!$request->isXmlHttpRequest()) {
            throw $this->createNotFoundException();
        }

        /** @var UserReview $review */
        $review = $this->findById($id, UserReview::class, UserReviewVoter::DELETE);

        $this->get('app.user.review.manager')->delete($review);

        return $this->json([
            'success' => 1,
        ]);
    }
}
