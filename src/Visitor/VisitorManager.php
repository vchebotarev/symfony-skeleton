<?php

namespace App\Visitor;

use App\Doctrine\Entity\Visitor;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;

class VisitorManager
{
    const COOKIE_KEY = 'visy';
    const COOKIE_TTL = 60 * 60 * 24 * 31 * 12; //12 месяцев

    /**
     * @var Visitor
     */
    protected $currentVisitor;

    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * @var Request
     */
    protected $request;

    /**
     * @param EntityManager $entityManager
     * @param RequestStack  $requestStack
     */
    public function __construct(EntityManager $entityManager, RequestStack $requestStack)
    {
        $this->em      = $entityManager;
        $this->request = $requestStack->getCurrentRequest();
    }

    protected function initVisitor()
    {
        //Если запуск не по http
        $request = $this->request;
        if (!$request) {
            return;
        }

        $cookies = $request->cookies;
        $session = $request->getSession();

        if ($session->has(self::COOKIE_KEY)) { //в сессии хранится id
            /** @var Visitor $visitor */
            $visitor = $this->em->getRepository(Visitor::class)->find($session->get(self::COOKIE_KEY));
        } elseif ($cookies->has(self::COOKIE_KEY)){ //в куках хранится hash
            $visitor = $this->em->getRepository(Visitor::class)->findOneBy([
                'hash' => $cookies->get(self::COOKIE_KEY),
            ]);
        }

        if (isset($visitor) && $visitor) {
            $this->currentVisitor = $visitor;
            return;
        }

        $visitor = new Visitor();
        $visitor->setHash($this->generateHash());
        $this->em->persist($visitor);
        $this->em->flush($visitor);

        $this->currentVisitor = $visitor;
    }

    /**
     * @return Visitor
     */
    public function getCurrentVisitor()
    {
        if ($this->currentVisitor === null) {
            $this->initVisitor();
        }
        return $this->currentVisitor;
    }

    /**
     * @return string
     */
    protected function generateHash() : string
    {
        return md5(microtime().rand(0, 1000));
    }

    /**
     * @param Response $response
     */
    public function saveVisitor(Response $response)
    {
        if (!$this->currentVisitor) { //Если никто не требовал посетителя - нечего и сохранять
            return;
        }

        //Чтобы не дергать куку постоянно
        if ($this->request->getSession()->has(self::COOKIE_KEY)) {
            return;
        }

        $expire = time() + self::COOKIE_TTL;
        $response->headers->setCookie(new Cookie(self::COOKIE_KEY, $this->getCurrentVisitor()->getHash(), $expire)); // в куки hash

        $this->request->getSession()->set(self::COOKIE_KEY, $this->currentVisitor->getId()); // в сессии id
    }

}
