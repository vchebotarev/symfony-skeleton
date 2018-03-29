<?php

namespace App\Symfony\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller as BaseController;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\FormBuilderInterface;

abstract class AbstractController extends BaseController
{
    /**
     * @return User|null
     */
    public function getUser()
    {
        return $this->get('app.user.manager')->getCurrentUser();
    }

    /**
     * @param string|null $name
     * @return EntityManager
     */
    protected function getEm($name = null)
    {
        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager($name);
        return $em;
    }

    /**
     * @inheritDoc
     */
    protected function json($data, $status = 200, $headers = array(), $context = array())
    {
        $jsonResponse = parent::json($data, $status, $headers, $context);
        $jsonResponse->setEncodingOptions(JSON_UNESCAPED_UNICODE);
        return $jsonResponse;
    }

    /**
     * @param string     $name
     * @param null|mixed $data
     * @param array      $options
     * @return FormBuilderInterface
     */
    protected function createNamedFormBuilder($name = '', $data = null, array $options = array())
    {
        return $this->container->get('form.factory')->createNamedBuilder($name, FormType::class, $data, $options);
    }

    /**
     * @param int    $id
     * @param string $className
     * @param mixed  $attr
     * @return object
     */
    protected function findById(int $id, string $className, $attr = null)
    {
        $obj = $this->getEm()->getRepository($className)->find($id);
        if (!$obj) {
            throw $this->createNotFoundException();
        }
        if ($attr !== null) {
            $this->denyAccessUnlessGranted($attr, $obj);
        }
        return $obj;
    }

}
