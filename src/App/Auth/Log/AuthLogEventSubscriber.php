<?php

namespace App\Auth\Log;

use App\UserAgent\UserAgentManager;
use App\Visitor\VisitorManager;
use AppBundle\Entity\UserAuthLog;
use Doctrine\ORM\EntityManager;
use HWI\Bundle\OAuthBundle\Security\Core\Authentication\Token\OAuthToken;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Authentication\Token\RememberMeToken;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Symfony\Component\Security\Http\SecurityEvents;

class AuthLogEventSubscriber implements EventSubscriberInterface
{
    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * @var RequestStack
     */
    protected $requestStack;

    /**
     * @var VisitorManager
     */
    protected $visitorManager;

    /**
     * @var UserAgentManager
     */
    protected $userAgentManager;

    /**
     * @param EntityManager    $em
     * @param RequestStack     $requestStack
     * @param VisitorManager   $visitorManager
     * @param UserAgentManager $userAgentManager
     */
    public function __construct(EntityManager $em, RequestStack $requestStack, VisitorManager $visitorManager, UserAgentManager $userAgentManager)
    {
        $this->requestStack     = $requestStack;
        $this->em               = $em;
        $this->visitorManager   = $visitorManager;
        $this->userAgentManager = $userAgentManager;
    }

    /**
     * @inheritdoc
     */
    public static function getSubscribedEvents()
    {
        return [
            SecurityEvents::INTERACTIVE_LOGIN => 'onInteractiveLogin',
        ];
    }

    /**
     * @param InteractiveLoginEvent $event
     */
    public function onInteractiveLogin(InteractiveLoginEvent $event)
    {
        $userAuthLog = new UserAuthLog();
        $userAuthLog->setType($this->getAuthTypeByToken($event->getAuthenticationToken()));
        $userAuthLog->setUser($event->getAuthenticationToken()->getUser());
        $userAuthLog->setVisitor($this->visitorManager->getCurrentVisitor());
        $userAuthLog->setUserAgent($this->userAgentManager->getCurrentUserAgent());
        $userAuthLog->setIp($this->requestStack->getCurrentRequest()->getClientIp());

        $this->em->persist($userAuthLog);
        $this->em->flush($userAuthLog);
    }

    /**
     * @param TokenInterface $token
     * @return int
     */
    protected function getAuthTypeByToken(TokenInterface $token)
    {
        $type = UserAuthLog::TYPE_UNKNOWN;
        if ($token instanceof UserLinkToken) {
            $type = UserAuthLog::TYPE_LINK;
        } elseif ($token instanceof UsernamePasswordToken) {
            $type = UserAuthLog::TYPE_USERNAME_PASSWORD;
        } elseif($token instanceof RememberMeToken) {
            $type = UserAuthLog::TYPE_REMEMBER_ME;
        } elseif($token instanceof OAuthToken) {
            $type = UserAuthLog::TYPE_OAUTH;
            //todo ещё бы запоминать через какую соц сеть
        }
        return $type;
    }

}
