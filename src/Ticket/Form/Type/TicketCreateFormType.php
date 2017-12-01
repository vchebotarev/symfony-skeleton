<?php

namespace App\Ticket\Form\Type;

use App\Entity\Ticket;
use App\Entity\User;
use App\Symfony\Form\AbstractFormType;
use App\Ticket\TicketManager;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class TicketCreateFormType extends AbstractFormType
{
    /**
     * @var TicketManager
     */
    protected $ticketManager;

    /**
     * @param TicketManager $ticketManager
     */
    public function __construct(TicketManager $ticketManager)
    {
        $this->ticketManager = $ticketManager;
    }

    /**
     * @inheritDoc
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $formType = $this;

        $builder->add('type', ChoiceType::class, [
            'label' => 'Тип',
            'choices' => [
                'type 1' => Ticket::TYPE_INFO,
                'type 2' => Ticket::TYPE_QUESTION,
                'type 3' => Ticket::TYPE_ERROR,
            ],
        ]);
        $builder->add('body', TextareaType::class, [
            'constraints' => [
                new NotBlank([
                    'message' => 'Текст сообщения не должен быть пустым',
                ]),
                new Length([
                    'max'        => 2000,
                    'maxMessage' => 'Максимальная длина сообщения {{ limit }} символов',
                ]),
            ],
        ]);

        $builder->addEventListener(FormEvents::POST_SUBMIT, function (FormEvent $event) use ($formType) {
            $formType->postSubmit($event);
        });
    }

    /**
     * @inheritDoc
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->setRequired('user');
        $resolver->addAllowedTypes('user', [
            User::class,
        ]);
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

        /** @var User $user */
        $user = $form->getConfig()->getOptions()['user'];

        $ticket = $this->ticketManager->create($user, $data['type'], $data['body']);
    }

}
