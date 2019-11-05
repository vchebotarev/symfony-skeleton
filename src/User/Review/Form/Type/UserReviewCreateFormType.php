<?php

namespace App\User\Review\Form\Type;

use App\Doctrine\Entity\User;
use App\Doctrine\Entity\UserReview;
use App\Symfony\Form\AbstractFormType;
use App\User\Review\UserReviewManager;
use App\User\Review\UserReviewType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class UserReviewCreateFormType extends AbstractFormType
{
    /**
     * @var UserReviewManager
     */
    protected $userReviewManager;

    /**
     * @param UserReviewManager $userReviewManager
     */
    public function __construct(UserReviewManager $userReviewManager)
    {
        $this->userReviewManager = $userReviewManager;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $formType = $this;

        $builder->setAction('')->setMethod(Request::METHOD_POST);

        $builder->add('type', ChoiceType::class, [
            'label'    => 'Тип',
            'required' => true,
            'choices'  => array_flip(UserReviewType::getNames()),
            'data' => UserReview::TYPE_NEUTRAL,
        ]);
        $builder->add('body', TextareaType::class, [
            'label'       => 'Текст отзыва',
            'required'    => true,
            'constraints' => [
                new NotBlank(),
                new Length([
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

        /** @var User $user */
        $user = $form->getConfig()->getOption('user');

        $this->userReviewManager->create($user, $data['type'], $data['body']);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setRequired('user');
        $resolver->setAllowedTypes('user', User::class);
    }
}
