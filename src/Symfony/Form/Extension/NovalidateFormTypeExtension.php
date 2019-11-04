<?php

namespace App\Symfony\Form\Extension;

use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;

class NovalidateFormTypeExtension extends AbstractTypeExtension
{
    public function getExtendedType()
    {
        return FormType::class;
    }

    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        if ($form->isRoot()) { //только для корневой формы, т.е. <form>

            //todo сделать возможность отключения этого расширения

            $view->vars['attr'] = array_merge($view->vars['attr'], [
                'novalidate' => 'novalidate',
            ]);
        }
    }

}
