<?php

namespace App\SwiftMailer\Transport;

use Swift_Events_EventDispatcher;
use Swift_Events_EventListener;
use Swift_Events_SendEvent;
use Swift_Mime_SimpleMessage;
use Swift_Transport;
use Swift_TransportException;

/**
 * MailTransport from Swiftmailer 5 https://github.com/swiftmailer/swiftmailer/tree/5.x
 * - SimpleMailInvoker's code placed in
 * - implemented Swift_Transport::ping()
 * - fixed codestyle
 */
class MailTransport implements Swift_Transport
{
    /**
     * Additional parameters to pass to mail()
     * @var string
     */
    private $extraParams = '-f%s';

    /**
     * The event dispatcher from the plugin API
     * @var Swift_Events_EventDispatcher
     */
    private $eventDispatcher;

    /**
     * @param Swift_Events_EventDispatcher $eventDispatcher
     */
    public function __construct(Swift_Events_EventDispatcher $eventDispatcher)
    {
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * Send mail via the mail() function.
     * This method takes the same arguments as PHP mail().
     * @param string $to
     * @param string $subject
     * @param string $body
     * @param string $headers
     * @param string $extraParams
     * @return bool
     */
    private function mail($to, $subject, $body, $headers = null, $extraParams = null)
    {
        if (!ini_get('safe_mode')) {
            return @mail($to, $subject, $body, $headers, $extraParams);
        }
        return @mail($to, $subject, $body, $headers);
    }

    /**
     * Not used.
     */
    public function ping()
    {
        return true;
    }

    /**
     * Not used.
     */
    public function isStarted()
    {
        return false;
    }

    /**
     * Not used.
     */
    public function start()
    {
    }

    /**
     * Not used.
     */
    public function stop()
    {
    }

    /**
     * Set the additional parameters used on the mail() function.
     * This string is formatted for sprintf() where %s is the sender address.
     * @param string $params
     * @return $this
     */
    public function setExtraParams($params)
    {
        $this->extraParams = $params;
        return $this;
    }

    /**
     * Get the additional parameters used on the mail() function.
     * This string is formatted for sprintf() where %s is the sender address.
     * @return string
     */
    public function getExtraParams()
    {
        return $this->extraParams;
    }

    /**
     * Send the given Message.
     * Recipient/sender data will be retrieved from the Message API.
     * The return value is the number of recipients who were accepted for delivery.
     * @param Swift_Mime_SimpleMessage $message
     * @param string[]                 $failedRecipients An array of failures by-reference
     * @return int
     */
    public function send(Swift_Mime_SimpleMessage $message, &$failedRecipients = null)
    {
        $failedRecipients = (array) $failedRecipients;

        if ($evt = $this->eventDispatcher->createSendEvent($this, $message)) {
            $this->eventDispatcher->dispatchEvent($evt, 'beforeSendPerformed');
            if ($evt->bubbleCancelled()) {
                return 0;
            }
        }

        $count = (
            count((array) $message->getTo())
            + count((array) $message->getCc())
            + count((array) $message->getBcc())
        );

        $toHeader = $message->getHeaders()->get('To');
        $subjectHeader = $message->getHeaders()->get('Subject');

        if (0 === $count) {
            $this->throwException(new Swift_TransportException('Cannot send message without a recipient'));
        }
        $to = $toHeader ? $toHeader->getFieldBody() : '';
        $subject = $subjectHeader ? $subjectHeader->getFieldBody() : '';

        $reversePath = $this->getReversePath($message);

        // Remove headers that would otherwise be duplicated
        $message->getHeaders()->remove('To');
        $message->getHeaders()->remove('Subject');

        $messageStr = $message->toString();

        if ($toHeader) {
            $message->getHeaders()->set($toHeader);
        }
        $message->getHeaders()->set($subjectHeader);

        // Separate headers from body
        if (false !== $endHeaders = strpos($messageStr, "\r\n\r\n")) {
            $headers = substr($messageStr, 0, $endHeaders)."\r\n"; //Keep last EOL
            $body = substr($messageStr, $endHeaders + 4);
        } else {
            $headers = $messageStr."\r\n";
            $body = '';
        }

        unset($messageStr);

        if ("\r\n" != PHP_EOL) {
            // Non-windows (not using SMTP)
            $headers = str_replace("\r\n", PHP_EOL, $headers);
            $subject = str_replace("\r\n", PHP_EOL, $subject);
            $body = str_replace("\r\n", PHP_EOL, $body);
            $to = str_replace("\r\n", PHP_EOL, $to);
        } else {
            // Windows, using SMTP
            $headers = str_replace("\r\n.", "\r\n..", $headers);
            $subject = str_replace("\r\n.", "\r\n..", $subject);
            $body = str_replace("\r\n.", "\r\n..", $body);
            $to = str_replace("\r\n.", "\r\n..", $to);
        }

        if ($this->mail($to, $subject, $body, $headers, $this->formatExtraParams($this->extraParams, $reversePath))) {
            if ($evt) {
                $evt->setResult(Swift_Events_SendEvent::RESULT_SUCCESS);
                $evt->setFailedRecipients($failedRecipients);
                $this->eventDispatcher->dispatchEvent($evt, 'sendPerformed');
            }
        } else {
            $failedRecipients = array_merge(
                $failedRecipients,
                array_keys((array) $message->getTo()),
                array_keys((array) $message->getCc()),
                array_keys((array) $message->getBcc())
            );

            if ($evt) {
                $evt->setResult(Swift_Events_SendEvent::RESULT_FAILED);
                $evt->setFailedRecipients($failedRecipients);
                $this->eventDispatcher->dispatchEvent($evt, 'sendPerformed');
            }

            $message->generateId();

            $count = 0;
        }

        return $count;
    }

    /**
     * Register a plugin.
     *
     * @param Swift_Events_EventListener $plugin
     */
    public function registerPlugin(Swift_Events_EventListener $plugin)
    {
        $this->eventDispatcher->bindEventListener($plugin);
    }

    /**
     * Throw a TransportException, first sending it to any listeners
     * @param Swift_TransportException $e
     */
    protected function throwException(Swift_TransportException $e)
    {
        if ($evt = $this->eventDispatcher->createTransportExceptionEvent($this, $e)) {
            $this->eventDispatcher->dispatchEvent($evt, 'exceptionThrown');
            if (!$evt->bubbleCancelled()) {
                throw $e;
            }
        } else {
            throw $e;
        }
    }

    /**
     * Determine the best-use reverse path for this message
     * @param Swift_Mime_SimpleMessage $message
     * @return mixed|null|string
     */
    private function getReversePath(Swift_Mime_SimpleMessage $message)
    {
        $return = $message->getReturnPath();
        $sender = $message->getSender();
        $from = $message->getFrom();
        $path = null;
        if (!empty($return)) {
            $path = $return;
        } elseif (!empty($sender)) {
            $keys = array_keys($sender); //todo
            $path = array_shift($keys);
        } elseif (!empty($from)) {
            $keys = array_keys($from);
            $path = array_shift($keys);
        }

        return $path;
    }

    /**
     * Fix CVE-2016-10074 by disallowing potentially unsafe shell characters.
     * Note that escapeshellarg and escapeshellcmd are inadequate for our purposes, especially on Windows.
     * @param string $string The string to be validated
     * @return bool
     */
    private function isShellSafe($string)
    {
        // Future-proof
        if (escapeshellcmd($string) !== $string || !in_array(escapeshellarg($string), array("'$string'", "\"$string\""))) {
            return false;
        }

        $length = strlen($string);
        for ($i = 0; $i < $length; ++$i) {
            $c = $string[$i];
            // All other characters have a special meaning in at least one common shell, including = and +.
            // Full stop (.) has a special meaning in cmd.exe, but its impact should be negligible here.
            // Note that this does permit non-Latin alphanumeric characters based on the current locale.
            if (!ctype_alnum($c) && strpos('@_-.', $c) === false) {
                return false;
            }
        }

        return true;
    }

    /**
     * Return php mail extra params to use for invoker->mail.
     * @param string $extraParams
     * @param string $reversePath
     * @return string|null
     */
    private function formatExtraParams($extraParams, $reversePath)
    {
        if (false !== strpos($extraParams, '-f%s')) {
            if (empty($reversePath) || false === $this->isShellSafe($reversePath)) {
                $extraParams = str_replace('-f%s', '', $extraParams);
            } else {
                $extraParams = sprintf($extraParams, $reversePath);
            }
        }

        return !empty($extraParams) ? $extraParams : null;
    }
}
