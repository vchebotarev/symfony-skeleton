<?php

namespace App\Twig\Extension;

use App\Email\EmailCheckUrlDetector;
use App\Email\EmailHelper;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;
use Twig\TwigTest;

class EmailExtension extends AbstractExtension
{
    /**
     * @var EmailHelper
     */
    protected $emailHelper;

    /**
     * @var EmailCheckUrlDetector
     */
    protected $emailCheckUrlDetector;

    /**
     * @param EmailHelper           $emailHelper
     * @param EmailCheckUrlDetector $emailDomainDetector
     */
    public function __construct(EmailHelper $emailHelper, EmailCheckUrlDetector $emailDomainDetector)
    {
        $this->emailHelper           = $emailHelper;
        $this->emailCheckUrlDetector = $emailDomainDetector;
    }

    public function getFunctions()
    {
        return [
            new TwigFunction('email_check_url', [$this, 'emailCheckUrl']),
        ];
    }

    public function getTests()
    {
        return [
            new TwigTest('email', [$this, 'isEmail']),
        ];
    }

    /**
     * @param string $email
     * @return string
     */
    public function emailCheckUrl($email)
    {
        return $this->emailCheckUrlDetector->getUrlByEmail($email);
    }

    /**
     * @param string $string
     * @return bool
     */
    public function isEmail($string)
    {
        return $this->emailHelper->isEmail($string);
    }

}
