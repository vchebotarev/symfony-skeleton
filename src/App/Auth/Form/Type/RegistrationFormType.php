<?php

namespace App\Auth\Form\Type;

use App\Symfony\Form\AbstractFormType;
use App\Symfony\Validator\Constraints\Chain;
use App\Symfony\Validator\Constraints\EntityExists;
use App\Symfony\Validator\Constraints\Password;
use App\Symfony\Validator\Constraints\Username;
use App\User\UserManipulator;
use App\Entity\User;
use EWZ\Bundle\RecaptchaBundle\Form\Type\EWZRecaptchaType;
use EWZ\Bundle\RecaptchaBundle\Validator\Constraints\IsTrue as RecaptchaIsTrue;
use Gregwar\CaptchaBundle\Type\CaptchaType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotNull;

class RegistrationFormType extends AbstractFormType
{
    /**
     * @var UserManipulator
     */
    protected $userManipulator;

    /**
     * @param UserManipulator $userManipulator
     */
    public function __construct(UserManipulator $userManipulator)
    {
        $this->userManipulator = $userManipulator;
    }

    /**
     * @inheritdoc
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $formType = $this;

        $builder->setMethod(Request::METHOD_POST)->setAction('');

        $builder->add('username', EmailType::class, [
            'label'       => 'Логин',
            'constraints' => [
                new Chain([
                    new NotNull([
                        'message' => 'Введите логин',
                    ]),
                    new Username(),
                    new EntityExists([
                        'entityClass'  => User::class,
                        'field'        => 'username',
                        'notExistMode' => true,
                        'message'      => 'Пользователь с таким логином уже зарегистрирован',
                    ]),
                ]),
            ],
        ]);
        $builder->add('email', EmailType::class, [
            'label'       => 'E-mail',
            'constraints' => [
                new Chain([
                    new NotNull([
                        'message' => 'Введите e-mail',
                    ]),
                    new Email([
                        'message' => 'Введите корректный e-mail',
                    ]),
                    new EntityExists([
                        'entityClass'  => User::class,
                        'field'        => 'email',
                        'notExistMode' => true,
                        'message'      => 'Пользователь с таким email уже зарегистрирован',
                    ]),
                ]),
            ],
        ]);
        $builder->add('password', RepeatedType::class, [
            'type'            => PasswordType::class,
            'first_name'      => 'password',
            'second_name'     => 'password_confirm',
            'first_options'   => [
                'label' => 'Пароль',
            ],
            'second_options'  => [
                'label' => 'Повтор пароля',
            ],
            'invalid_message' => 'Введенные пароли не совпадают',
            'constraints'     => [
                new Chain([
                    new NotNull([
                        'message' => 'Введите пароль'
                    ]),
                    new Password(),
                ]),
            ],
        ]);
        $builder->add('recaptcha', EWZRecaptchaType::class, [
            'constraints' => [
                new RecaptchaIsTrue([
                    'message' => 'Вы не прошли проверку "Я не робот"',
                ]),
            ],
        ]);
        $builder->add('g-recaptcha-response', HiddenType::class); //или allow extra fields потому что рекапча вставляет невидимое поле с токеном

        $builder->add('captcha', CaptchaType::class, array(
            'background_color' => array(255, 255, 255,),
            'width'            => 135,
            'height'           => 45,
            'invalid_message'  => 'Вы ввели неверный текст с картинки',
        ));

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

        $this->userManipulator->create($data['username'], $data['email'], $data['password']);
    }

}
