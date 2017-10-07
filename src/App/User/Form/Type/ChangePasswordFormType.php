<?php

namespace App\User\Form\Type;

use App\Symfony\Form\AbstractFormType;
use App\Symfony\Validator\Constraints\Chain;
use App\Symfony\Validator\Constraints\Password;
use App\Symfony\Validator\Constraints\UserPassword;
use App\User\UserManager;
use App\User\UserManipulator;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints\NotNull;

class ChangePasswordFormType extends AbstractFormType
{
    /**
     * @var UserManager
     */
    protected $userManager;

    /**
     * @var UserManipulator
     */
    protected $userManipulator;

    /**
     * @param UserManager     $userManager
     * @param UserManipulator $userManipulator
     */
    public function __construct(UserManager $userManager, UserManipulator $userManipulator)
    {
        $this->userManager     = $userManager;
        $this->userManipulator = $userManipulator;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $formType = $this;

        $builder->setMethod(Request::METHOD_POST)->setAction('');

        $builder->add('password', PasswordType::class, [
            'label'       => 'Ваш текущий пароль',
            'constraints' => [
                new Chain([
                    new NotNull([
                        'message' => 'Введите ваш текущий пароль',
                    ]),
                    new UserPassword([
                        'message' => 'Неверный пароль',
                    ]),
                ]),
            ],
        ]);
        $builder->add('password_new', RepeatedType::class, [
            'type'            => PasswordType::class,
            'first_name'      => 'password',
            'second_name'     => 'password_confirm',
            'first_options'   => [
                'label' => 'Пароль',
            ],
            'second_options'  => [
                'label' => 'Повторите пароль',
            ],
            'invalid_message' => 'Введенные пароли не совпадают',
            'constraints'     => [
                new Chain([
                    new NotNull([
                        'message' => 'Введите новый пароль',
                    ]),
                    new Password(),
                    new UserPassword([
                        'notValidMode' => true,
                        'message'      => 'Текущий и новый пароль не должны совпадать',
                    ]),
                ]),
            ],
        ]);

        $builder->addEventListener(FormEvents::POST_SUBMIT, function (FormEvent $event) use ($formType) {
            $formType->postSubmit($event);
        });
    }

    /**
     * @param FormEvent $event
     */
    public function postSubmit(FormEvent $event)
    {
        $form = $event->getForm();

        if (!$form->isSubmitted() || !$form->isValid()) {
            return;
        }

        $user     = $this->userManager->getCurrentUser();
        $password = $form->getData()['password_new'];
        $this->userManipulator->changePassword($user, $password);
    }

}
