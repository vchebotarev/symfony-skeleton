<?php

namespace App\Auth\Form\Type;

use App\Auth\LoginManager;
use App\Symfony\Form\AbstractFormType;
use App\Symfony\Validator\Constraints\Chain;
use App\Symfony\Validator\Constraints\Password;
use App\Symfony\Validator\Constraints\UserPassword;
use App\User\UserManipulator;
use AppBundle\Entity\User;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotNull;

class ResetFormType extends AbstractFormType
{
    /**
     * @var UserManipulator
     */
    protected $userManipulator;

    /**
     * @var LoginManager
     */
    protected $loginManager;

    /**
     * @param UserManipulator  $userManipulator
     * @param LoginManager     $loginManager
     */
    public function __construct(UserManipulator $userManipulator, LoginManager $loginManager)
    {
        $this->userManipulator  = $userManipulator;
        $this->loginManager     = $loginManager;
    }

    /**
     * @inheritdoc
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $formType = $this;

        $builder->setMethod(Request::METHOD_POST)->setAction('');

        $builder->add('password', RepeatedType::class, [
            'type'            => PasswordType::class,
            'first_name'      => 'password',
            'second_name'     => 'password_confirm',
            'first_options'   => [
                'label' => 'Пароль',
            ],
            'second_options'  => [
                'label' => 'Повтор пароля',
            ],
            'invalid_message' => 'Введенные пароли не совпадают',
            'constraints'     => [
                new Chain([
                    new NotNull([
                        'message' => 'Введите пароль'
                    ]),
                    new Password(),
                    new UserPassword([
                        'user'         => $options['user'],
                        'notValidMode' => true,
                        'message'      => 'Новый пароль не должны совпадать с текущим',
                    ]),
                ]),
            ],
        ]);

        $builder->addEventListener(FormEvents::POST_SUBMIT, function(FormEvent $event) use ($formType) {
            $formType->postSubmit($event);
        });
    }

    /**
     * @inheritDoc
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->setRequired('user');
        $resolver->addAllowedTypes('user', User::class);
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

        $user = $form->getConfig()->getOption('user');

        $this->userManipulator->enable($user); //Подтверждаем юзера
        $this->userManipulator->changePassword($user, $event->getForm()->getData()['password']);

        $this->loginManager->loginUserByLink($user, 'main');
    }

}
