<?php

namespace App\User\Form\Type;

use App\Mailer\MailerTokened;
use App\Symfony\Form\AbstractType;
use App\Symfony\Validator\Constraints\Chain;
use App\Symfony\Validator\Constraints\EntityExists;
use App\Symfony\Validator\Constraints\UserPassword;
use App\User\UserManager;
use AppBundle\Entity\User;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class ChangeEmailType extends AbstractType
{
    /**
     * @var UserManager
     */
    protected $userManager;

    /**
     * @var
     */
    protected $mailer;

    /**
     * @param UserManager $userManager
     * @param             $mailer
     */
    public function __construct(UserManager $userManager, MailerTokened $mailer)
    {
        $this->userManager = $userManager;
        $this->mailer      = $mailer;
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

        $builder->add('email', EmailType::class, [
            'label'       => 'Новый e-mail',
            'constraints' => [
                new Chain([
                    new NotNull([
                        'message' => 'Введите e-mail',
                    ]),
                    new Email([
                        'message' => 'Введите корректный e-mail',
                    ]),
                    new Callback(function ($value, ExecutionContextInterface $context) use ($formType) {
                        if ($formType->userManager->getCurrentUser()->getEmail() == $value) {
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
        $newEmail = $form->getData()['email'];
        $this->mailer->sendEmailChangeConfirmation($user, $newEmail);
    }

}
