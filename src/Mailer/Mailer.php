<?php

namespace App\Mailer;

use Symfony\Bundle\TwigBundle\TwigEngine;

class Mailer
{
    /**
     * @var TwigEngine
     */
    protected $twig;

    /**
     * @var \Swift_Mailer
     */
    protected $swiftMailer;

    /**
     * @todo move to config
     * @var string
     */
    protected $emailFrom = 'no-reply@ss.dev';

    /**
     * @todo move to config
     * @var string
     */
    protected $nameFrom = 'Chebur Symfony Skeleton';

    /**
     * @param \Swift_Mailer $swiftMailer
     * @param TwigEngine    $twig
     */
    public function __construct(\Swift_Mailer $swiftMailer, TwigEngine $twig)
    {
        $this->twig        = $twig;
        $this->swiftMailer = $swiftMailer;
    }

    /**
     * @param string|array $to        @see \Swift_Mime_SimpleMessage::setTo
     * @param string       $subject
     * @param string       $template
     * @param array        $params
     * @param array        $from
     * @param mixed|null   $failedRecipients
     * @return int
     */
    public function sendTemplated($to, string $subject, string $template, array $params = [], array $from = [], &$failedRecipients = null): int
    {
        if (empty($from)) {
            $from = [
                $this->emailFrom => $this->nameFrom,
            ];
        }

        $message = (new \Swift_Message())
            ->setSubject($subject)
            ->setFrom($from)
            ->setTo($to)
            ->setContentType('text/html')
            ->setBody($this->twig->render($template, $params));

        return $this->swiftMailer->send($message);
    }

    /**
     * @param string|array $to
     * @param string       $subject
     * @param string       $body
     * @param array        $from
     * @param mixed|null   $failedRecipients
     * @return int
     */
    public function sendSimple($to, string $subject, string $body, array $from = [], &$failedRecipients = null): int
    {
        if (empty($from)) {
            $from = [
                $this->emailFrom => $this->nameFrom,
            ];
        }

        $message = (new \Swift_Message())
            ->setSubject($subject)
            ->setFrom($from)
            ->setTo($to)
            ->setContentType('text/plain')
            ->setBody($body);

        return $this->swiftMailer->send($message, $failedRecipients);
    }

}
