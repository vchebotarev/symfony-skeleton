<?php

namespace App\UserAgent;

use App\Doctrine\Entity\UserAgent;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\RequestStack;

class UserAgentManager
{
    /**
     * @var UserAgent
     */
    protected $currentUserAgent;

    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * @var RequestStack
     */
    protected $requestStack;

    /**
     * @param EntityManager $em
     * @param RequestStack  $requestStack
     */
    public function __construct(EntityManager $em, RequestStack $requestStack)
    {
        $this->em           = $em;
        $this->requestStack = $requestStack;
    }

    protected function initUserAgent()
    {
        $uaString = $this->requestStack->getCurrentRequest()->headers->get('User-Agent');
        $uaHash   = $this->generateHash($uaString);
        $uaEntity = $this->em->getRepository(UserAgent::class)->findOneBy([
            'hash' => $uaHash,
        ]);
        if (!$uaEntity) {
            $uaEntity = new UserAgent();
            $uaEntity->setName($uaString);
            $uaEntity->setHash($uaHash);
            $this->em->persist($uaEntity);
            $this->em->flush($uaEntity);
        }
        $this->currentUserAgent = $uaEntity;
    }

    /**
     * @return UserAgent
     */
    public function getCurrentUserAgent()
    {
        if ($this->currentUserAgent === null) {
            $this->initUserAgent();
        }
        return $this->currentUserAgent;
    }

    /**
     * @param string $userAgentString
     * @return string
     */
    protected function generateHash($userAgentString)
    {
        return md5($userAgentString);
    }

}
