<?php

namespace App\User\Form\Type;

use App\Symfony\Form\AbstractFormType;
use App\Timezone\TimezoneManager;
use App\User\UserManager;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\HttpFoundation\Request;

class ChangeTimezoneFormType extends AbstractFormType
{
    /**
     * @var UserManager
     */
    protected $userManager;

    /**
     * @var TimezoneManager
     */
    protected $timezoneManager;

    /**
     * @param UserManager     $userManager
     * @param TimezoneManager $timezoneManager
     */
    public function __construct(UserManager $userManager, TimezoneManager $timezoneManager)
    {
        $this->userManager     = $userManager;
        $this->timezoneManager = $timezoneManager;
    }

    /**
     * @inheritdoc
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $formType = $this;

        $builder->setMethod(Request::METHOD_POST)->setAction('');

        $builder->add('timezone', ChoiceType::class, [
            'label'   => 'Часовой пояс',
            'choices' => array_flip(array_map(function (\DateTime $dt) {
                return $dt->getTimezone()->getName().' '.substr_replace($dt->format('O'), ':', 3, 0);
            }, $this->timezoneManager->getList())),
            'data'    => $this->timezoneManager->getTimezoneByUser($this->userManager->getCurrentUser()),
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

        $user = $this->userManager->getCurrentUser();

        $user->setTimezone($data['timezone']);

        //todo event

        $this->userManager->updateUser($user);
    }

}
