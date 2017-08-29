<?php

namespace AppBundle\Twig\Extension;

use App\Email\EmailCheckUrlDetector;
use App\Email\EmailHelper;

class EmailExtension extends \Twig_Extension
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

    /**
     * @inheritdoc
     */
    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('email_check_url', [$this, 'emailCheckUrl']),
        ];
    }

    /**
     * @inheritdoc
     */
    public function getTests()
    {
        return [
            new \Twig_SimpleTest('email', [$this, 'isEmail']),
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
