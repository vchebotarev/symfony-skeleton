<?php

namespace AppBundle\Controller\Admin;

use App\Symfony\Controller\AbstractController;
use App\User\Form\Type\ChangeEmailAdminFormType;
use App\User\Form\Type\ChangeUsernameAdminFormType;
use App\User\Form\Type\CreateUserFormType;
use App\User\Form\Type\EditUserFormType;
use App\User\Security\UserVoter;
use AppBundle\Entity\User;
use AppBundle\Entity\UserAuthLog;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class UserController extends AbstractController
{
    /**
     * @param Request $request
     * @return Response
     */
    public function listAction(Request $request)
    {
        if (!$this->isGranted(UserVoter::LIST)) {
            throw $this->createAccessDeniedException();
        }

        $users = $this->getEm()->getRepository(User::class)->findAll();

        return $this->render('@App/Admin/User/list.html.twig', [
            'users' => $users,
        ]);
    }

    /**
     * @param Request $request
     * @param int     $id
     * @return Response
     */
    public function viewAction(Request $request, $id)
    {
        $user = $this->findUser($id, UserVoter::VIEW);

        //Редиректим в профиль если это текущий пользователь
        if ($user->getId() == $this->getUser()->getId()) {
            return $this->redirectToRoute('app_admin_profile_index');
        }

        return $this->render('@App/Admin/User/view.html.twig', [
            'user' => $user,
        ]);
    }

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
     * @param Request $request
     * @return Response
     */
    public function createAction(Request $request)
    {
        $this->denyAccessUnlessGranted(UserVoter::CREATE);

        $form = $this->createForm(CreateUserFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $this->get('app.user.manager')->findUserByEmail($form->getData()['email']);
            return $this->redirectToRoute('app_admin_user_view', [
                'id' => $user->getId(),
            ]);
        }

        return $this->render('@App/Admin/User/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @param Request $request
     * @param int     $id
     * @return Response|RedirectResponse
     */
    public function editAction(Request $request, $id)
    {
        $user = $this->findUser($id, UserVoter::EDIT);

        //Редиректим в редактирование профиля если это текущий пользователь
        if ($user->getId() == $this->getUser()->getId()) {
            return $this->redirectToRoute('app_admin_profile_edit');
        }

        $form = $this->createForm(EditUserFormType::class, [
            'fio_f'         => $user->getFioF(),
            'fio_i'         => $user->getFioI(),
            'fio_o'         => $user->getFioO(),
            'date_birthday' => $user->getDateBirthday(),
            'gender'        => $user->getGender(),
        ], [
            'user' => $user,
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            return $this->redirectToRoute('app_admin_user_view', [
                'id' => $user->getId(),
            ]);
        }

        return $this->render('@App/Admin/User/edit.html.twig', [
            'form' => $form->createView(),
            'user' => $user,
        ]);
    }

    /**
     * @param Request $request
     * @param int     $id
     * @return Response|RedirectResponse
     */
    public function changeEmailAction(Request $request , $id)
    {
        $user = $this->findUser($id, UserVoter::EDIT);

        $form = $this->createForm(ChangeEmailAdminFormType::class, null, [
            'user' => $user,
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            return $this->redirectToRoute('app_admin_user_view', [
                'id' => $user->getId(),
            ]);
        }

        return $this->render('@App/Admin/User/change_email.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @param Request $request
     * @param int     $id
     * @return Response|RedirectResponse
     */
    public function changeUsernameAction(Request $request, $id)
    {
        $user = $this->findUser($id, UserVoter::EDIT);

        $form = $this->createForm(ChangeUsernameAdminFormType::class, null, [
            'user' => $user,
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            return $this->redirectToRoute('app_admin_user_view', [
                'id' => $user->getId(),
            ]);
        }

        return $this->render('@App/Admin/User/change_username.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @param Request $request
     * @param int     $id
     * @return RedirectResponse
     */
    public function enableAction(Request $request, $id)
    {
        $user = $this->findUser($id, UserVoter::ENABLE);

        $this->get('app.user.manipulator')->enable($user);

        //todo is ajax

        return $this->redirectToRoute('app_admin_user_view', [
            'id' => $id,
        ]);
    }

    /**
     * @param Request $request
     * @param int     $id
     * @return RedirectResponse
     */
    public function lockAction(Request $request, $id)
    {
        $user = $this->findUser($id, UserVoter::LOCK);

        $this->get('app.user.manipulator')->lock($user);

        //todo is ajax

        return $this->redirectToRoute('app_admin_user_view', [
            'id' => $id,
        ]);
    }

    /**
     * @param Request $request
     * @param int     $id
     * @return RedirectResponse
     */
    public function unlockAction(Request $request, $id)
    {
        $user = $this->findUser($id, UserVoter::UNLOCK);

        $this->get('app.user.manipulator')->unlock($user);

        //todo is ajax

        return $this->redirectToRoute('app_admin_user_view', [
            'id' => $id,
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
