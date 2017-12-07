<?php

namespace App\Lot\Form\Type;

use App\Entity\Lot;
use App\Lot\LotManager;
use App\Symfony\Form\AbstractFormType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class LotStartFormType extends AbstractFormType
{
    /**
     * @var LotManager
     */
    protected $lotManager;

    /**
     * @param LotManager $lotManager
     */
    public function __construct(LotManager $lotManager)
    {
        $this->lotManager = $lotManager;
    }

    /**
     * @inheritDoc
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $formType = $this;

        $builder->add('price_start', MoneyType::class, [
            'label' => 'Стартовая цена',
            'constraints' => [
                new NotBlank(),
            ],
        ]);
        $builder->add('price_blitz', MoneyType::class, [
            'label' => 'Блиц цена',
            'constraints' => [

            ],
        ]);

        $builder->addEventListener(FormEvents::POST_SUBMIT, function (FormEvent $event) use ($formType) {
            $formType->postSubmit($event);
        });
    }

    /**
     * @param FormEvent $event
     */
    public function postSubmit(FormEvent $event)
    {
        $form = $event->getForm();
        $data = $event->getData();

        if (!$form->isSubmitted() || !$form->isValid()) {
            return;
        }

        /** @var Lot $lot */
        $lot = $form->getConfig()->getOption('lot');

        $this->lotManager->start($lot, $data['price_start'], (float)$data['price_blitz']);
    }

    /**
     * @inheritDoc
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $formType = $this;

        $resolver->setRequired('lot');
        $resolver->setAllowedTypes('lot', Lot::class);

        $resolver->setDefault('constraints', [
            new Callback(function ($value, ExecutionContextInterface $context) use ($formType) {
                $formType->validateFullForm($value, $context);
            }),
        ]);

        parent::configureOptions($resolver);
    }

    /**
     * @param array                     $value
     * @param ExecutionContextInterface $context
     */
    public function validateFullForm($value, ExecutionContextInterface $context)
    {
        if ($value['price_blitz'] > 0) {
            if (isset($value['price_start']) && $value['price_start'] >= $value['price_blitz']) { //todo условие посложнее
                $context->buildViolation('Блиц цена должна быть меньше или равна стартовой')
                    ->setParameter('{{ value }}', $value['price_blitz'])
                    ->setInvalidValue($value['price_blitz'])
                    ->atPath('[price_blitz]')
                    ->addViolation();
            }
        }
    }

}
