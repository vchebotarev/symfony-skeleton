<?php

namespace App\Controller\Admin;

use App\Search\Param;
use App\Symfony\Controller\AbstractController;
use App\User\Form\Type\ChangeEmailAdminFormType;
use App\User\Form\Type\ChangeUsernameAdminFormType;
use App\User\Form\Type\CreateUserFormType;
use App\User\Form\Type\EditUserFormType;
use App\User\Search\Form\Type\SearchAdminFormType;
use App\User\Security\UserVoter;
use App\Doctrine\Entity\User;
use App\Doctrine\Entity\UserAuthLog;
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

        $form = $this->createForm(SearchAdminFormType::class);
        $form->handleRequest($request);

        $search = $this->get('chebur.search.manager')
            ->createBuilderAdmin()
            ->setItemsSource($this->get('app.user.search.admin'), $form->getData())
            ->setSorts([
                Param::CREATED  => [
                    Param::DESC => 'Регистрация свежие',
                    Param::ASC  => 'Регистрация старые',
                ],
                Param::USERNAME => [
                    Param::ASC  => 'Username A-Я',
                    Param::DESC => 'Username Я-A',
                ],
                Param::EMAIL    => [
                    Param::ASC  => 'E-mail A-Я',
                    Param::DESC => 'E-mail Я-A',
                ],
                //todo
                //Param::LOGIN    => [
                //    Param::ASC  => 'Последний вход давно',
                //    Param::DESC => 'Последний вход свежие',
                //],
            ])
            ->build($request);

        return $this->render('Admin/User/list.html.twig', [
            'form'   => $form->createView(),
            'search' => $search,
        ]);
    }

    /**
     * @param Request $request
     * @param int     $id
     * @return Response
     */
    public function viewAction(Request $request, $id)
    {
        /** @var User $user */
        $user = $this->findById($id, User::class, UserVoter::VIEW);

        //Редиректим в профиль если это текущий пользователь
        if ($user->getId() == $this->getUser()->getId()) {
            return $this->redirectToRoute('app_admin_profile_index');
        }

        return $this->render('Admin/User/view.html.twig', [
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
        /** @var User $user */
        $user = $this->findById($id, User::class, UserVoter::VIEW_AUTH_LOG);

        $logs = $this->getEm()->getRepository(UserAuthLog::class)->findByUser($user);

        return $this->render('Admin/User/auth_log.html.twig', [
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

        return $this->render('Admin/User/create.html.twig', [
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
        /** @var User $user */
        $user = $this->findById($id, User::class, UserVoter::EDIT);

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

        return $this->render('Admin/User/edit.html.twig', [
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
        /** @var User $user */
        $user = $this->findById($id, User::class, UserVoter::EDIT);

        $form = $this->createForm(ChangeEmailAdminFormType::class, null, [
            'user' => $user,
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            return $this->redirectToRoute('app_admin_user_view', [
                'id' => $user->getId(),
            ]);
        }

        return $this->render('Admin/User/change_email.html.twig', [
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
        /** @var User $user */
        $user = $this->findById($id, User::class, UserVoter::EDIT);

        $form = $this->createForm(ChangeUsernameAdminFormType::class, null, [
            'user' => $user,
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            return $this->redirectToRoute('app_admin_user_view', [
                'id' => $user->getId(),
            ]);
        }

        return $this->render('Admin/User/change_username.html.twig', [
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
        /** @var User $user */
        $user = $this->findById($id, User::class, UserVoter::ENABLE);

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
        /** @var User $user */
        $user = $this->findById($id, User::class, UserVoter::LOCK);

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
        /** @var User $user */
        $user = $this->findById($id, User::class, UserVoter::UNLOCK);

        $this->get('app.user.manipulator')->unlock($user);

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
    public function roleAddAdminAction(Request $request, $id)
    {
        /** @var User $user */
        $user = $this->findById($id, User::class, UserVoter::ROLE_ADD_ADMIN);

        $this->get('app.user.manipulator')->roleAddAdmin($user);

        return $this->redirectToRoute('app_admin_user_view', [
            'id' => $id,
        ]);
    }

    /**
     * @param Request $request
     * @param int     $id
     * @return RedirectResponse
     */
    public function roleRemoveAdminAction(Request $request, $id)
    {
        /** @var User $user */
        $user = $this->findById($id, User::class, UserVoter::ROLE_REMOVE_ADMIN);

        $this->get('app.user.manipulator')->roleRemoveAdmin($user);

        return $this->redirectToRoute('app_admin_user_view', [
            'id' => $id,
        ]);
    }
}
