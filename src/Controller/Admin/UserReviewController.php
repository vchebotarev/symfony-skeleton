<?php

namespace App\Controller\Admin;

use App\Doctrine\Entity\UserReview;
use App\Symfony\Controller\AbstractController;
use App\User\Review\Form\Type\UserReviewSearchFormType;
use App\User\Review\Security\UserReviewVoter;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class UserReviewController extends AbstractController
{
    /**
     * @param Request $request
     * @return Response
     */
    public function listAction(Request $request)
    {
        $form = $this->createForm(UserReviewSearchFormType::class);
        $form->handleRequest($request);

        $search = $this->get('chebur.search.manager')
            ->createBuilderAdmin()
            ->setItemsSource($this->get('app.user.review.search.source'), $form->getData())
            ->build($request);

        return $this->render('Admin/UserReview/list.html.twig', [
            'form'   => $form->createView(),
            'search' => $search,
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
