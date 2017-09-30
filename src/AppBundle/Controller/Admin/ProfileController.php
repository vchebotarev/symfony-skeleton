<?php

namespace AppBundle\Controller\Admin;

use App\Symfony\Controller\AbstractController;
use App\User\Form\Type\ChangeEmailType;
use App\User\Form\Type\ChangePasswordType;
use App\User\Form\Type\ChangeUsernameType;
use App\User\Security\UserVoter;
use AppBundle\Entity\UserAuthLog;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ProfileController extends AbstractController
{
    /**
     * @return Response
     */
    public function indexAction()
    {
        return $this->render('@App/Admin/Profile/index.html.twig');
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function editAction(Request $request)
    {
        //todo

        return $this->render('@App/Admin/Profile/edit.html.twig', [

        ]);
    }

    /**
     * @return Response
     */
    public function authLogAction()
    {
        $this->denyAccessUnlessGranted(UserVoter::VIEW_AUTH_LOG, $this->getUser());

        $logs = $this->getEm()->getRepository(UserAuthLog::class)->findByUser($this->getUser());

        return $this->render('@App/Admin/Profile/auth_log.html.twig', [
            'logs' => $logs,
        ]);
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function changeUsernameAction(Request $request)
    {
        $form = $this->createForm(ChangeUsernameType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            return $this->render('@App/Admin/Profile/change_username_success.html.twig');
        }

        return $this->render('@App/Admin/Profile/change_username.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function changePasswordAction(Request $request)
    {
        $form = $this->createForm(ChangePasswordType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            return $this->render('@App/Admin/Profile/change_password_success.html.twig');
        }

        return $this->render('@App/Admin/Profile/change_password.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function changeEmailAction(Request $request)
    {
        if (!$this->isGranted(UserVoter::CHANGE_EMAIL)) {
            $response = new Response();
            $response->setStatusCode(Response::HTTP_FORBIDDEN);
            return $this->render('@App/Admin/Profile/change_email_error.html.twig', [], $response);
        }

        $form = $this->createForm(ChangeEmailType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $email = $form->getData()['email'];
            return $this->render('@App/Admin/Profile/change_email_success.html.twig', [
                'email' => $email,
            ]);
        }

        return $this->render('@App/Admin/Profile/change_email.html.twig', [
            'form' => $form->createView(),
        ]);
    }

}
