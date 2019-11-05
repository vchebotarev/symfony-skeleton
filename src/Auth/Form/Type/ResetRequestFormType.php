<?php

namespace App\Auth\Form\Type;

use App\Mailer\MailerTokened;
use App\Symfony\Form\AbstractFormType;
use App\Symfony\Validator\Constraints\Chain;
use App\Symfony\Validator\Constraints\EntityExists;
use App\User\UserManager;
use App\User\UserTokenManager;
use App\Doctrine\Entity\User;
use App\Doctrine\Entity\UserToken;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class ResetRequestFormType extends AbstractFormType
{
    /**
     * @var UserManager
     */
    protected $userManager;

    /**
     * @var UserTokenManager
     */
    protected $userTokenManager;

    /**
     * @var MailerTokened
     */
    protected $mailer;

    /**
     * @param UserManager      $userManager
     * @param UserTokenManager $userTokenManager
     * @param MailerTokened    $mailer
     */
    public function __construct(UserManager $userManager, UserTokenManager $userTokenManager, MailerTokened $mailer)
    {
        $this->userManager      = $userManager;
        $this->userTokenManager = $userTokenManager;
        $this->mailer           = $mailer;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $formType = $this;

        $builder->setMethod(Request::METHOD_POST)->setAction('');

        $builder->add('email', TextType::class, [
            'label'       => 'E-mail',
            'constraints' => new Chain([
                new NotNull([
                    'message' => 'Введите e-mail',
                ]),
                new Email([
                    'message' => 'Введите корректный e-mail',
                ]),
                new EntityExists([
                    'message'     => 'Пользователь с таким e-mail не зарегистрирован на Портале',
                    'entityClass' => User::class,
                    'field'       => 'email',
                ]),
                new Callback(function ($value, ExecutionContextInterface $context) use ($formType) {
                    $formType->validateEmail($value, $context);
                }),
            ]),
        ]);

        $builder->addEventListener(FormEvents::POST_SUBMIT, function(FormEvent $event) use ($formType) {
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

        $user = $this->userManager->findUserByEmail($event->getData()['email']);

        $this->mailer->sendResettingEmailMessage($user);
    }

    /**
     * @param string                    $email
     * @param ExecutionContextInterface $context
     */
    public function validateEmail($email, ExecutionContextInterface $context)
    {
        $user      = $this->userManager->findUserByEmail($email);
        $userToken = $this->userTokenManager->findTokenByUserAndType($user, UserToken::TYPE_RESET_PASSWORD);
        if ($userToken) {
            $context->buildViolation('Пароль для данного пользователя уже запрашивался за последний час.')
                ->setParameter('{{ value }}', $email)
                ->setInvalidValue($email)
                ->addViolation()
            ;
        }
    }
}
