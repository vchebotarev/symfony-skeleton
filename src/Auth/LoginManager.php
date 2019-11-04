<?php

namespace App\Auth;

use App\Auth\Log\UserLinkToken;
use App\User\UserManager;
use App\Entity\User;
use FOS\UserBundle\Model\UserInterface;
use FOS\UserBundle\Security\LoginManagerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Exception\AccountStatusException;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Symfony\Component\Security\Http\SecurityEvents;
use Symfony\Component\Security\Http\Session\SessionAuthenticationStrategyInterface;

class LoginManager implements LoginManagerInterface
{
    /**
     * @var TokenStorageInterface
     */
    protected $tokenStorage;

    /**
     * @var UserCheckerInterface
     */
    protected $userChecker;

    /**
     * @var SessionAuthenticationStrategyInterface
     */
    protected $sessionStrategy;

    /**
     * @var RequestStack
     */
    protected $requestStack;

    /**
     * @var EventDispatcherInterface
     */
    protected $eventDispatcher;

    /**
     * @var UserManager
     */
    protected $userManager;

    /**
     * @param TokenStorageInterface                  $tokenStorage
     * @param UserCheckerInterface                   $userChecker
     * @param SessionAuthenticationStrategyInterface $sessionStrategy
     * @param RequestStack                           $requestStack
     * @param EventDispatcherInterface               $eventDispatcher
     * @param UserManager                            $userManager
     */
    public function __construct(
        TokenStorageInterface $tokenStorage,
        UserCheckerInterface $userChecker,
        SessionAuthenticationStrategyInterface $sessionStrategy,
        RequestStack $requestStack,
        EventDispatcherInterface $eventDispatcher,
        UserManager $userManager
    ) {
        $this->tokenStorage      = $tokenStorage;
        $this->userChecker       = $userChecker;
        $this->sessionStrategy   = $sessionStrategy;
        $this->requestStack      = $requestStack;
        $this->eventDispatcher   = $eventDispatcher;
        $this->userManager       = $userManager;
    }

    /**
     * Из-за того что в FOS\UserBundle\Security\LoginManager все private и final - копипастим, кроме remember me (запоминать после такого не надо точно)
     */
    public function logInUser($firewallName, UserInterface $user, Response $response = null)
    {
        throw new \RuntimeException('Use other public methods. This one is too unified.');
    }

    /**
     * @param TokenInterface $token
     */
    protected function loginUserWithToken(TokenInterface $token)
    {
        try {
            /** @var User $user */
            $user = $token->getUser();

            $this->userChecker->checkPreAuth($user);
            $this->userChecker->checkPostAuth($user);

            $request = $this->requestStack->getCurrentRequest();

            if ($request) {
                $this->sessionStrategy->onAuthentication($request, $token);
            }

            $this->tokenStorage->setToken($token);

            if ($request) {
                $this->eventDispatcher->dispatch(SecurityEvents::INTERACTIVE_LOGIN, new InteractiveLoginEvent($request, $token));
            }
        } catch (AccountStatusException $e) {
            // We simply do not authenticate users which do not pass the user checker (not enabled, expired, etc.).
        }
    }

    /**
     * @param User   $user
     * @param string $firewallName
     */
    public function loginUserByPassword(User $user, $firewallName)
    {
        if ($this->userManager->getCurrentUser()) {
            return;
        }

        $token = new UsernamePasswordToken($user, null, $firewallName, $user->getRoles());

        $this->loginUserWithToken($token);
    }

    /**
     * @param User   $user
     * @param string $firewallName
     */
    public function loginUserByLink(User $user, $firewallName)
    {
        if ($this->userManager->getCurrentUser()) {
            return;
        }

        $token = new UserLinkToken($user, null, $firewallName, $user->getRoles());

        $this->loginUserWithToken($token);
    }

}
