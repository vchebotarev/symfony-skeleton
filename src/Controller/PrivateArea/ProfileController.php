<?php

namespace App\Controller\PrivateArea;

use App\Doctrine\Entity\UserAuthLog;
use App\Doctrine\Entity\UserSocial;
use App\Symfony\Controller\AbstractController;
use App\User\Form\Type\ChangeEmailFormType;
use App\User\Form\Type\ChangePasswordFormType;
use App\User\Form\Type\ChangeTimezoneFormType;
use App\User\Form\Type\ChangeUsernameFormType;
use App\User\Security\UserVoter;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ProfileController extends AbstractController
{

    /**
     * @return Response
     */
    public function indexAction()
    {
        return $this->render('PrivateArea/Profile/index.html.twig');
    }

    /**
     * @return Response
     */
    public function oauthAction()
    {
        $userSocials    = $this->getEm()->getRepository(UserSocial::class)->findByUser($this->getUser());
        $resourceOwners = $this->get('hwi_oauth.security.oauth_utils')->getResourceOwnersObjects();

        return $this->render('/PrivateArea/Profile/oauth.html.twig', [
            'resource_owners' => $resourceOwners,
            'user_socials'    => $userSocials,
        ]);
    }

    /**
     * @param Request $request
     * @param string  $service
     * @return RedirectResponse
     */
    public function oauthDisconnectAction(Request $request, $service)
    {
        //todo csrf
        $resourceOwner = $this->get('hwi_oauth.security.oauth_utils')->getResourceOwnerObjectByName($service);
        if (!$resourceOwner) {
            throw $this->createNotFoundException();
        }
        $this->get('app.oauth.connector')->disconnect($this->getUser(), $resourceOwner);

        return $this->redirectToRoute('app_private_profile_oauth');
    }

    /**
     * @return Response
     */
    public function authLogAction()
    {
        $this->denyAccessUnlessGranted(UserVoter::VIEW_AUTH_LOG, $this->getUser());

        $logs = $this->getEm()->getRepository(UserAuthLog::class)->findByUser($this->getUser());

        return $this->render('PrivateArea/Profile/auth_log.html.twig', [
            'logs' => $logs,
        ]);
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function changeUsernameAction(Request $request)
    {
        $form = $this->createForm(ChangeUsernameFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            return $this->render('PrivateArea/Profile/change_username_success.html.twig');
        }

        return $this->render('PrivateArea/Profile/change_username.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function changePasswordAction(Request $request)
    {
        $form = $this->createForm(ChangePasswordFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            return $this->render('PrivateArea/Profile/change_password_success.html.twig');
        }

        return $this->render('PrivateArea/Profile/change_password.html.twig', [
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
            return $this->render('PrivateArea/Profile/change_email_error.html.twig', [], $response);
        }

        $form = $this->createForm(ChangeEmailFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $email = $form->getData()['email'];
            return $this->render('PrivateArea/Profile/change_email_success.html.twig', [
                'email' => $email,
            ]);
        }

        return $this->render('PrivateArea/Profile/change_email.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function changeTimezoneAction(Request $request)
    {
        $form = $this->createForm(ChangeTimezoneFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            return $this->render('PrivateArea/Profile/change_timezone_success.html.twig');
        }

        return $this->render('PrivateArea/Profile/change_timezone.html.twig', [
            'form' => $form->createView(),
        ]);
    }

}
