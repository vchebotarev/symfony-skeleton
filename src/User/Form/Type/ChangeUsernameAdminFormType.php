<?php

namespace App\User\Form\Type;

use App\Symfony\Form\AbstractFormType;
use App\Symfony\Validator\Constraints\Chain;
use App\Symfony\Validator\Constraints\EntityExists;
use App\Symfony\Validator\Constraints\Username;
use App\User\UserManipulator;
use App\Entity\User;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class ChangeUsernameAdminFormType extends AbstractFormType
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
     * @inheritDoc
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $formType = $this;

        $builder->setMethod(Request::METHOD_POST)->setAction('');

        $builder->add('username', RepeatedType::class, [
            'type'            => TextType::class,
            'first_name'      => 'username',
            'second_name'     => 'username_confirm',
            'first_options'   => [
                'label' => 'Логин',
            ],
            'second_options'  => [
                'label' => 'Повторите логин',
            ],
            'invalid_message' => 'Введенные логины не совпадают',
            'constraints'     => [
                new Chain([
                    new NotNull([
                        'message' => 'Введите новый логин',
                    ]),
                    new Username(),
                    new Callback(function ($value, ExecutionContextInterface $context) use ($options) {
                        /** @var User $user */
                        $user = $options['user'];
                        if ($user->getUsername() == $value) {
                            $context->buildViolation('Старый и новый логин не должны совпадать')->addViolation();
                        }
                    }),
                    new EntityExists([
                        'notExistMode' => true,
                        'message'      => 'Пользователь с таким логином уже есть в системе',
                        'entityClass'  => User::class,
                        'field'        => 'username',
                    ]),
                ]),
            ],
        ]);

        $builder->addEventListener(FormEvents::POST_SUBMIT, function (FormEvent $event) use ($formType) {
            $formType->postSubmit($event);
        });
    }

    /**
     * @inheritDoc
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setRequired('user');
        $resolver->addAllowedTypes('user', User::class);
    }

    /**
     * @param FormEvent $event
     */
    public function postSubmit(FormEvent $event)
    {
        $form = $event->getForm();
        $data = $event->getData();

        if (!$form->isSubmitted() || !$form->isValid()) {
            return;
        }

        /** @var User $user */
        $user = $form->getConfig()->getOption('user');

        $this->userManipulator->changeUsername($user, $data['username']);
    }
}
