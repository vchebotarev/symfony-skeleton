<?php

namespace App\User\Form\Type;

use App\Symfony\Form\AbstractFormType;
use App\Symfony\Validator\Constraints\Chain;
use App\Symfony\Validator\Constraints\EntityExists;
use App\User\UserManipulator;
use App\Doctrine\Entity\User;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class ChangeEmailAdminFormType extends AbstractFormType
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

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $formType = $this;

        $builder->setMethod(Request::METHOD_POST)->setAction('');

        $builder->add('email', RepeatedType::class, [
            'type'            => EmailType::class,
            'first_name'      => 'email',
            'second_name'     => 'email_confirm',
            'first_options'   => [
                'label' => 'E-mail',
            ],
            'second_options'  => [
                'label' => 'Повторите e-mail',
            ],
            'invalid_message' => 'Введенные e-mail не совпадают',
            'constraints'     => [
                new Chain([
                    new NotNull([
                        'message' => 'Введите новый e-mail',
                    ]),
                    new Email([
                        'message' => 'Введите корректный e-mail',
                    ]),
                    new Callback(function ($value, ExecutionContextInterface $context) use ($options) {
                        /** @var User $user */
                        $user = $options['user'];
                        if ($user->getEmail() == $value) {
                            $context->buildViolation('Старый и новый e-mail не должны совпадать')->addViolation();
                        }
                    }),
                    new EntityExists([
                        'notExistMode' => true,
                        'message'      => 'Пользователь с таким e-mail уже есть в системе',
                        'entityClass'  => User::class,
                        'field'        => 'email',
                    ]),
                ]),
            ],
        ]);

        $builder->addEventListener(FormEvents::POST_SUBMIT, function (FormEvent $event) use ($formType) {
            $formType->postSubmit($event);
        });
    }

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

        $this->userManipulator->changeEmail($user, $data['email']);
    }

}
