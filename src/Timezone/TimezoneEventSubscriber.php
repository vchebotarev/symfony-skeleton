<?php

namespace App\Timezone;

use App\User\UserManager;
use Doctrine\ORM\EntityManager;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class TimezoneEventSubscriber implements EventSubscriberInterface
{
    /**
     * @var UserManager
     */
    protected $userManager;

    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * @param UserManager   $userManager
     * @param EntityManager $em
     */
    public function __construct(UserManager $userManager, EntityManager $em)
    {
        $this->userManager = $userManager;
        $this->em          = $em;

        //todo use session
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::REQUEST => 'onKernelRequest',
        ];
    }

    /**
     * @param GetResponseEvent $event
     */
    public function onKernelRequest(GetResponseEvent $event)
    {
        $user = $this->userManager->getCurrentUser();
        if (!$user) {
            return;
        }

        $timezone = $user->getTimezone();
        if (!$timezone) {
            return;
        }

        $timezone = new \DateTimeZone($timezone);

        //php
        date_default_timezone_set($timezone->getName());

        //db
        $this->em->getConnection()->executeQuery('SET @@session.time_zone = "'.(new \DateTime('now', $timezone))->format('P').'"');
    }

}
