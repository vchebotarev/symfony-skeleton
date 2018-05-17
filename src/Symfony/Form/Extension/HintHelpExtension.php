<?php

namespace App\Symfony\Form\Extension;

use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

class HintHelpExtension extends AbstractTypeExtension
{
    /**
     * @inheritDoc
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['help']         = $options['help'];
        $view->vars['hint']         = $options['hint'];
        $view->vars['hint_prepend'] = $options['hint_prepend'];
        $view->vars['hint_append']  = $options['hint_append'];
    }

    /**
     * @inheritDoc
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefault('help', null);
        $resolver->setDefault('hint', null);
        $resolver->setDefault('hint_prepend', null);
        $resolver->setDefault('hint_append', null);

        //todo help_attr, hint_attr
    }

    /**
     * @inheritDoc
     */
    public function getExtendedType()
    {
        return FormType::class;
    }

}
