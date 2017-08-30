<?php

namespace App\Visitor\Event;

use App\Visitor\VisitorManager;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class VisitorSubscriber implements EventSubscriberInterface
{
    /**
     * @var VisitorManager
     */
    protected $visitorManager;

    /**
     * @param VisitorManager $visitorManager
     */
    public function __construct(VisitorManager $visitorManager)
    {
        $this->visitorManager = $visitorManager;
    }

    /**
     * @inheritDoc
     */
    public static function getSubscribedEvents()
    {
        return array(
            KernelEvents::RESPONSE => array('onKernelResponse'),
        );
    }

    /**
     * @param FilterResponseEvent $event
     */
    public function onKernelResponse(FilterResponseEvent $event)
    {
        $this->visitorManager->saveVisitor($event->getResponse());
    }

}
