<?php

namespace App\User\Form\Type;

use App\Symfony\Form\AbstractFormType;
use App\User\GenderedInterface;
use App\User\UserManager;
use AppBundle\Entity\User;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EditUserFormType extends AbstractFormType
{
    /**
     * @var UserManager
     */
    protected $userManager;

    /**
     * @param UserManager $userManager
     */
    public function __construct(UserManager $userManager)
    {
        $this->userManager = $userManager;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $formType = $this;

        $builder->add('fio_f', TextType::class, [
            'label' => 'Фамилия',
        ]);
        $builder->add('fio_i', TextType::class, [
            'label' => 'Имя',
        ]);
        $builder->add('fio_o', TextType::class, [
            'label' => 'Отчество',
        ]);
        $builder->add('gender', ChoiceType::class, [
            'label'   => 'Пол',
            'choices' => array_flip([
                GenderedInterface::GENDER_UNKNOWN => 'неизвестно',
                GenderedInterface::GENDER_MALE    => 'мужчина',
                GenderedInterface::GENDER_FEMALE  => 'женщина',
            ]),
        ]);
        $builder->add('date_birthday', DateType::class, [
            'label' => 'Дата рождения',
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
        $resolver->addAllowedTypes('user', User::class);
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
        $user = $form->getConfig()->getOption('user');

        $user->setFioF($data['fio_f']);
        $user->setFioI($data['fio_i']);
        $user->setFioO($data['fio_o']);
        $user->setGender($data['gender']);
        $user->setDateBirthday($data['date_birthday']);

        $this->userManager->updateUser($user);
    }

}
