<?php

namespace App\User\Search\Form\Type;

use App\Search\Param;
use App\Symfony\Form\AbstractFormType;
use App\Symfony\Form\IntervalType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SearchType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SearchAdminFormType extends AbstractFormType
{
    /**
     * @inheritdoc
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->setMethod(Request::METHOD_GET)->setAction('');

        $builder->add(Param::Q, SearchType::class, [
            'required' => false,
            'attr'     => [
                'placeholder' => 'Поисковая строка',
            ],
        ]);

        $builder->add(Param::ENABLED, ChoiceType::class, [
            'label'      => 'Активирован',
            'required'   => false,
            'choices'    => array_flip([
                1 => 'Да',
                0 => 'Нет',
            ]),
            'placeholder' => 'Все',
        ]);

        $builder->add(Param::LOCKED, ChoiceType::class, [
            'label'      => 'Заблокирован',
            'required'   => false,
            'choices'    => array_flip([
                1 => 'Да',
                0 => 'Нет',
            ]),
            'placeholder' => 'Все',
        ]);

        $builder->add(Param::CREATED, IntervalType::class, [
            'label' => 'Дата регистрации',
            'type'  => DateTimeType::class,
            //todo сделать что-нибудь с говновиджетом
        ]);
        $builder->add(Param::LOGIN, IntervalType::class, [
            'label' => 'Дата входа',
            'type' => DateType::class,
        ]);

    }

    /**
     * @inheritdoc
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefault('csrf_protection', false);
    }

}
