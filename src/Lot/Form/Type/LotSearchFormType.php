<?php

namespace App\Lot\Form\Type;

use App\Entity\Lot;
use App\Search\Param;
use App\Symfony\Form\AbstractFormType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SearchType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\Request;

class LotSearchFormType extends AbstractFormType
{
    /**
     * @inheritDoc
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->setAction('')->setMethod(Request::METHOD_GET);

        $builder->add(Param::USER, NumberType::class, [
            'label'    => 'Пользователь',
            'required' => false,
        ]);
        $builder->add(Param::STATUS, ChoiceType::class, [
            'label'    => 'Статус',
            'required' => false,
            'choices'  => [
                'Черновик' => Lot::STATUS_DRAFT,
                'Активный' => Lot::STATUS_ACTIVE,
                'Закрыт'   => Lot::STATUS_CLOSED,
            ],
        ]);
        $builder->add(Param::Q, SearchType::class, [
            'label'    => 'Поисковая строка',
            'required' => false,
        ]);
    }

}
