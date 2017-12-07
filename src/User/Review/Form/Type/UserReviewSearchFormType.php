<?php

namespace App\User\Review\Form\Type;

use App\Search\Param;
use App\Symfony\Form\AbstractFormType;
use App\Symfony\Form\IntervalType;
use App\User\Review\UserReviewType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\Request;

class UserReviewSearchFormType extends AbstractFormType
{
    /**
     * @inheritDoc
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->setAction('')->setMethod(Request::METHOD_GET);

        $builder->add(Param::TYPE, ChoiceType::class, [
            'label'       => 'Тип',
            'required'    => false,
            'choices'     => array_flip(UserReviewType::getNames()),
            'placeholder' => 'Любой',
        ]);
        $builder->add(Param::USER, NumberType::class, [
            'label'    => 'Пользователю',
            'required' => false,
        ]);
        $builder->add('user_created', NumberType::class, [
            'label'    => 'Пользователь создатель',
            'required' => false,
        ]);
        $builder->add(Param::CREATED, IntervalType::class, [
            'label'    => 'Дата создания',
            'required' => false,
            'type'     => DateType::class,
        ]);
    }

}
