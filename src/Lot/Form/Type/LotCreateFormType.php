<?php

namespace App\Lot\Form\Type;

use App\Entity\Lot;
use App\Symfony\Form\AbstractFormType;
use App\Symfony\Form\FormHelper;
use App\User\UserManager;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class LotCreateFormType extends AbstractFormType
{
    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * @var UserManager
     */
    protected $userManager;

    /**
     * @var FormHelper
     */
    protected $formHelper;

    /**
     * @param EntityManager $em
     * @param UserManager   $userManager
     * @param FormHelper    $formHelper
     */
    public function __construct(EntityManager $em, UserManager $userManager, FormHelper $formHelper)
    {
        $this->em          = $em;
        $this->userManager = $userManager;
        $this->formHelper  = $formHelper;
    }

    /**
     * @inheritDoc
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $formType = $this;

        $builder->add('name', TextType::class, [
            'label'       => 'Имя',
            'constraints' => [
                new NotBlank(),
                new Length([
                    'min' => 3,
                    'max' => 255,
                ]),
            ],
        ]);
        $builder->add('body', TextareaType::class, [
            'label'       => 'Описание',
            'constraints' => [
                new NotBlank(),
                new Length([
                    'min' => 3,
                    'max' => 2000,
                ]),
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

        $lot = new Lot();
        $lot->setUser($this->userManager->getCurrentUser());
        $lot->setName($data['name']);
        $lot->setBody($data['body']);

        $this->em->persist($lot);
        $this->em->flush($lot);

        $this->formHelper->setDataForce($form, $lot);
    }

}
