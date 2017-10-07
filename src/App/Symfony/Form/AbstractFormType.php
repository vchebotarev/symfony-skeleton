<?php

namespace App\Symfony\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Базовый класс для создания форм, но не контролов
 */
abstract class AbstractFormType extends AbstractType
{
    /**
     * @inheritdoc
     */
    public function getBlockPrefix()
    {
        return '';
    }

    /**
     * @inheritdoc
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefault('attr', [
            'novalidate' => 'novalidate',
        ]);
    }

}
