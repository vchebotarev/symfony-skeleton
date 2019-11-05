<?php

namespace App\Symfony\Form;

use Symfony\Component\Form\AbstractType;

/**
 * Базовый класс для создания форм, но не контролов
 */
abstract class AbstractFormType extends AbstractType
{
    public function getBlockPrefix()
    {
        return '';
    }
}
