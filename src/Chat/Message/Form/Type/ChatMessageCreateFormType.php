<?php

namespace App\Chat\Message\Form\Type;

use App\Chat\ChatManager;
use App\Entity\Chat;
use App\Entity\User;
use App\Symfony\Form\AbstractFormType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\OptionsResolver\Exception\MissingOptionsException;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class ChatMessageCreateFormType extends AbstractFormType
{
    /**
     * @var ChatManager
     */
    protected $chatManager;

    /**
     * @param ChatManager $chatManager
     */
    public function __construct(ChatManager $chatManager)
    {
        $this->chatManager = $chatManager;
    }

    /**
     * @inheritdoc
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $formType = $this;

        $builder->setMethod(Request::METHOD_POST);

        if (!isset($options['user']) && !isset($options['chat'])) {
            throw new MissingOptionsException('Option "chat" or "user" must be set'); //хз как засунуть в configureOptions
        }

        $builder->add('body', TextareaType::class, [
            'label'       => 'Текст сообщения',
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
     * @inheritdoc
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->setDefined('user');
        $resolver->addAllowedTypes('user', [
            User::class,
        ]);

        $resolver->setDefined('chat');
        $resolver->addAllowedTypes('chat', [
            Chat::class,
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

        $options = $form->getConfig()->getOptions();
        /** @var Chat|null $chat */
        $chat = isset($options['chat']) ? $options['chat'] : null;
        /** @var User|null $user */
        $user = isset($options['user']) ? $options['user'] : null;

        $body = $data['body'];

        if ($chat) {
            $message = $this->chatManager->createMessage($chat, $body);
        } else {
            $message = $this->chatManager->createMessageByUser($user, $body);
        }

        //супер-мега хак, потому что я не люблю работать в формах с моделями
        $closure = \Closure::bind(function (Form $form, $data){
            $form->modelData = $data;
        }, null, Form::class);
        $closure($form, $message);
    }

}
