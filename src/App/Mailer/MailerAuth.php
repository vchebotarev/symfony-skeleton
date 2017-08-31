<?php

namespace App\Mailer;

use App\User\UserTokenManager;
use AppBundle\Entity\User;
use AppBundle\Entity\UserToken;
use FOS\UserBundle\Mailer\MailerInterface;
use FOS\UserBundle\Model\UserInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;

class MailerAuth implements MailerInterface
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

    /**
     * @inheritDoc
     */
    public function sendConfirmationEmailMessage(UserInterface $user)
    {
        /** @var User $user*/
        $to       = $user->getEmail();
        $subject  = 'Регистрация на портале';
        $params   = array(
            'user'  => $user,
            'url'   => $this->router->generate('app_auth_registration_confirm', array(
                'token' => $this->userTokenManager->saveToken(UserToken::TYPE_REGISTRATION, $user),
            ), UrlGeneratorInterface::ABSOLUTE_URL),
        );
        $template = '@App/mail/registration_confirm.html.twig';

        $this->mailer->sendTemplated($to, $subject, $template, $params);
    }

    /**
     * @inheritDoc
     */
    public function sendResettingEmailMessage(UserInterface $user)
    {
        // TODO: Implement sendResettingEmailMessage() method.
    }

}
