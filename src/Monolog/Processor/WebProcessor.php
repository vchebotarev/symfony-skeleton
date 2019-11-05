<?php

namespace App\Monolog\Processor;

use Symfony\Bridge\Monolog\Processor\WebProcessor as BaseWebProcessor;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;

class WebProcessor extends BaseWebProcessor
{
    /**
     * @var array
     */
    protected $requestData = [];

    public function __construct(array $extraFields = null)
    {
        $this->addExtraField('user_agent', 'HTTP_USER_AGENT');
        $this->addExtraField('language', 'HTTP_ACCEPT_LANGUAGE');
        $this->addExtraField('scheme', 'REQUEST_SCHEME');

        parent::__construct($extraFields);
    }

    /**
     * @param array $record
     * @return array
     */
    public function __invoke(array $record)
    {
        $record = parent::__invoke($record);

        $record['request'] = $this->requestData;
        return $record;
    }

    /**
     * @param GetResponseEvent $event
     */
    public function onKernelRequest(GetResponseEvent $event)
    {
        parent::onKernelRequest($event);

        if ($event->isMasterRequest()) {
            $request           = $event->getRequest();
            $this->requestData = [
                'attributes' => $request->attributes->all(),
                'get'        => $request->query->all(),
                'post'       => $request->request->all(),
                'cookies'    => $request->cookies->all(),
                'session'    => $request->getSession()->all(),
            ];
        }
    }
}
