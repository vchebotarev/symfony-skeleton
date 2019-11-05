<?php

namespace App\Mailer;

use App\User\UserTokenManager;
use App\Entity\User;
use App\Entity\UserToken;
use FOS\UserBundle\Mailer\MailerInterface;
use FOS\UserBundle\Model\UserInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;

class MailerTokened implements MailerInterface
{
    /**
     * @var Mailer
     */
    protected $mailer;

    /**
     * @var RouterInterface
     */
    protected $router;

    /**
     * @var UserTokenManager
     */
    protected $userTokenManager;

    /**
     * @param Mailer           $mailer
     * @param RouterInterface  $router
     * @param UserTokenManager $userTokenManager
     */
    public function __construct(Mailer $mailer, RouterInterface $router, UserTokenManager $userTokenManager)
    {
        $this->mailer           = $mailer;
        $this->router           = $router;
        $this->userTokenManager = $userTokenManager;
    }

    public function sendConfirmationEmailMessage(UserInterface $user)
    {
        /** @var User $user*/
        $to       = $user->getEmail();
        $subject  = 'Регистрация на портале';
        $params   = [
            'user'  => $user,
            'url'   => $this->router->generate('app_auth_registration_confirm', [
                'token' => $this->userTokenManager->saveToken(UserToken::TYPE_REGISTRATION, $user),
            ], UrlGeneratorInterface::ABSOLUTE_URL),
        ];
        $template = 'mail/registration_confirm.html.twig';

        $this->mailer->sendTemplated($to, $subject, $template, $params);
    }

    public function sendResettingEmailMessage(UserInterface $user)
    {
        /** @var User $user */
        $to       = $user->getEmail();
        $subject  = 'Восстановление пароля на портале';
        $params   = [
            'user'  => $user,
            'url'   => $this->router->generate('app_auth_reset_reset', [
                'token' => $this->userTokenManager->saveToken(UserToken::TYPE_RESET_PASSWORD, $user),
            ], UrlGeneratorInterface::ABSOLUTE_URL),
        ];
        $template = 'mail/reset_confirm.html.twig';

        $this->mailer->sendTemplated($to, $subject, $template, $params);
    }

    /**
     * @param User   $user
     * @param string $newEmail
     */
    public function sendEmailChangeConfirmation(User $user, $newEmail)
    {
        $to       = $newEmail;
        $subject  = 'Изменение e-mail на портале';
        $params   = [
            'user'  => $user,
            'url'   => $this->router->generate('app_public_email_confirm', [
                'token' => $this->userTokenManager->saveToken(UserToken::TYPE_CHANGE_EMAIL, $user, [
                    'email' => $newEmail,
                ]),
            ], UrlGeneratorInterface::ABSOLUTE_URL),
        ];
        $template = 'mail/change_email_confirm.html.twig';

        $this->mailer->sendTemplated($to, $subject, $template, $params);
    }

}
