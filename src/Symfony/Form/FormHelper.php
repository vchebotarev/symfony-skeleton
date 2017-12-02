<?php

namespace App\Symfony\Form;

use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormInterface;

class FormHelper
{
    /**
     * супер-мега хак, потому что я не люблю работать в формах с моделями, а вернуть объект очень хочется
     * @todo @noinspection
     * @param FormInterface|Form $form
     * @param mixed              $data
     * @return FormInterface
     */
    public function setDataForce(Form $form, $data)
    {
        //супер-мега хак, потому что я не люблю работать в формах с моделями
        $closure = \Closure::bind(function (Form $form, $data) {
            $form->modelData = $data;
        }, null, Form::class);
        $closure($form, $data);

        return $form;
    }

}
