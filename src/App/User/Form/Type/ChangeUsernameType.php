<?php

namespace App\User\Form\Type;

use App\Symfony\Form\AbstractType;
use App\Symfony\Validator\Constraints\Chain;
use App\Symfony\Validator\Constraints\EntityExists;
use App\Symfony\Validator\Constraints\Username;
use App\Symfony\Validator\Constraints\UserPassword;
use App\User\UserManager;
use App\User\UserManipulator;
use AppBundle\Entity\User;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class ChangeUsernameType extends AbstractType
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

        $builder->add('username', TextType::class, [
            'label'       => 'Логин',
            'constraints' => [
                new Chain([
                    new Username(),
                    new Callback(function ($value, ExecutionContextInterface $context) use ($formType) {
                        if ($formType->userManager->getCurrentUser()->getUsername() == $value) {
                            $context->buildViolation('Старый и новый логина не должны совпадать')->addViolation();
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
     * @param FormEvent $event
     */
    public function postSubmit(FormEvent $event)
    {
        $form = $event->getForm();

        if (!$form->isSubmitted() || !$form->isValid()) {
            return;
        }

        $user     = $this->userManager->getCurrentUser();
        $username = $form->getData()['username'];
        $this->userManipulator->changeUsername($user, $username);
    }

}
