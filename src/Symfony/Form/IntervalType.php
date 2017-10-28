<?php

namespace App\Symfony\Form;

use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class IntervalType extends RepeatedType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $formType = $this;

        // Overwrite required option for child fields
        $options['from_options']['required'] = $options['required'];
        $options['to_options']['required']   = $options['required'];

        if (!isset($options['options']['error_bubbling'])) {
            $options['options']['error_bubbling'] = $options['error_bubbling'];
        }

        $builder->add($options['from_name'], $options['type'], array_merge($options['options'], $options['from_options']));
        $builder->add($options['to_name'], $options['type'], array_merge($options['options'], $options['to_options']));

        $builder
            //todo чую можно лакониченее сделать
            ->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) use ($formType) {
                $formType->fixInterval($event);
            })
            ->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) use ($formType) {
                $formType->fixInterval($event);
            })
        ;
    }

    /**
     * @todo проверить
     * Меняет местами ОТ и ДО, если ОТ больше ДО
     * @param FormEvent $event
     */
    public static function fixInterval(FormEvent $event)
    {
        $data    = $event->getData();
        $keyFrom = $event->getForm()->getConfig()->getOption('from_name');
        $keyTo   = $event->getForm()->getConfig()->getOption('to_name');

        if (!is_array($data)) {
            return;
        }
        if (isset($data[$keyFrom]) && isset($data[$keyTo])) {
            $from = $data[$keyFrom];
            $to   = $data[$keyTo];
            if ($from && $to && $from > $to) {
                $data[$keyFrom] = $to;
                $data[$keyTo]   = $from;
            }
        }
        $event->setData($data);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'type'           => TextType::class,
            'options'        => [],
            'from_options'   => [],
            'to_options'     => [],
            'from_name'      => 'from',
            'to_name'        => 'to',
            'error_bubbling' => false,
        ]);

        $resolver->setAllowedTypes('options', 'array');
        $resolver->setAllowedTypes('from_options', 'array');
        $resolver->setAllowedTypes('to_options', 'array');
    }

    /**
     * @inheritDoc
     */
    public function getBlockPrefix()
    {
        return 'interval';
    }

}
