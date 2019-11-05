<?php

namespace App\Symfony\Security\Authentication;

use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Authentication\DefaultAuthenticationFailureHandler;
use Symfony\Component\Security\Http\HttpUtils;
use Symfony\Component\Translation\TranslatorInterface;

class AjaxAuthenticationFailureHandler extends DefaultAuthenticationFailureHandler
{
    /**
     * @var TranslatorInterface
     */
    protected $translator;

    public function __construct(HttpKernelInterface $httpKernel, HttpUtils $httpUtils, array $options = [], LoggerInterface $logger = null, TranslatorInterface $translator)
    {
        parent::__construct($httpKernel, $httpUtils, $options, $logger);

        $this->translator = $translator;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        $parentReturn = parent::onAuthenticationFailure($request, $exception);
        if ($request->isXmlHttpRequest()) {
            return new JsonResponse([
                'success' => false,
                'error'   => $this->translator->trans($exception->getMessageKey(), $exception->getMessageData()),
                'url'     => $this->options['failure_path'],
            ]);
        }
        return $parentReturn;
    }
}
