<?php

namespace App\Lot\Bet\Form\Type;

use App\Entity\Lot;
use App\Lot\Bet\LotBetManager;
use App\Symfony\Form\AbstractFormType;
use App\Symfony\Validator\Constraints\Chain;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\GreaterThanOrEqual;
use Symfony\Component\Validator\Constraints\LessThanOrEqual;

class LotBetCreateFormType extends AbstractFormType
{
    /**
     * @var LotBetManager
     */
    protected $lotBetManager;

    /**
     * @param LotBetManager $lotManager
     */
    public function __construct(LotBetManager $lotManager)
    {
        $this->lotBetManager = $lotManager;
    }

    /**
     * @inheritDoc
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $formType = $this;

        $builder->setAction('')->setMethod(Request::METHOD_POST);

        /** @var Lot $lot */
        $lot = $options['lot'];

        $constraints = [];
        $constraints[] = new GreaterThanOrEqual([
            'value' => $lot->getPriceStart() * 0.1 + $lot->getPriceCurrent(), //todo нужна формула посложнее
        ]);
        if ($lot->getPriceBlitz()) {
            $constraints[] = new LessThanOrEqual([
                'value' => $lot->getPriceBlitz(),
            ]);
        }

        $builder->add('price', MoneyType::class, [
            'constraints' => [
                new Chain($constraints)
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

        $this->lotBetManager->create($lot, $data['price']);
    }

    /**
     * @inheritDoc
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setRequired('lot');
        $resolver->setAllowedTypes('lot', Lot::class);

        parent::configureOptions($resolver);
    }

}
