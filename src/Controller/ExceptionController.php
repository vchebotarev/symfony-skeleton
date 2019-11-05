<?php

namespace App\Controller;

use Symfony\Bundle\TwigBundle\Controller\ExceptionController as BaseExceptionController;
use Symfony\Component\HttpFoundation\Request;

class ExceptionController extends BaseExceptionController
{
    /**
     * Можно было бы просто подложить вьюшки в app/Resources/Twig/Exception , но мне так нагляднее
     * @see http://symfony.com/doc/current/controller/error_pages.html
     */
    protected function findTemplate(Request $request, $format, $code, $showException)
    {
        if (!$showException) {
            $template = sprintf('Exception/error%s.%s.twig', $code, $format);
            if ($this->templateExists($template)) {
                return $template;
            }
            $template = sprintf('@Twig/Exception/error.%s.twig', $format);
            if ($this->templateExists($template)) {
                return $template;
            }
        }
        return parent::findTemplate($request, $format, $code, $showException);
    }
}
