<?php

namespace App\User\Form\Type;

use App\Symfony\Form\AbstractFormType;
use App\Symfony\Validator\Constraints\Chain;
use App\Symfony\Validator\Constraints\EntityExists;
use App\Symfony\Validator\Constraints\Password;
use App\Symfony\Validator\Constraints\Username;
use App\User\UserManipulator;
use AppBundle\Entity\User;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotNull;

class CreateUserFormType extends AbstractFormType
{
    /**
     * @var UserManipulator
     */
    protected $userManipulator;

    /**
     * @param UserManipulator $userManipulator
     */
    public function __construct(UserManipulator $userManipulator)
    {
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

        $builder->add('username', TextType::class, [
            'label'       => 'Логин',
            'constraints' => [
                new Chain([
                    new Username(),
                    new EntityExists([
                        'notExistMode' => true,
                        'message'      => 'Пользователь с таким логином уже есть в системе',
                        'entityClass'  => User::class,
                        'field'        => 'username',
                    ]),
                ]),
            ],
        ]);
        $builder->add('email', EmailType::class, [
            'label'       => 'E-mail',
            'constraints' => [
                new Chain([
                    new Email(),
                    new EntityExists([
                        'notExistMode' => true,
                        'message'      => 'Пользователь с таким e-mail уже есть в системе',
                        'entityClass'  => User::class,
                        'field'        => 'email',
                    ]),
                ]),
            ],
        ]);
        $builder->add('password', RepeatedType::class, [
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
                        'message' => 'Введите пароль',
                    ]),
                    new Password(),
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

        $username = $form->getData()['username'];
        $email    = $form->getData()['email'];
        $password = $form->getData()['password'];

        $this->userManipulator->create($username, $email, $password);
    }

}
