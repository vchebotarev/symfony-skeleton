<?php

namespace App\Symfony\Form;

use Symfony\Component\Form\AbstractType as BaseAbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;

abstract class AbstractType extends BaseAbstractType
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
