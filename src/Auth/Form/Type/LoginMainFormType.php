<?php

namespace App\Auth\Form\Type;

use App\Email\EmailHelper;
use App\Symfony\Form\AbstractFormType;
use App\Symfony\Validator\Constraints\Chain;
use App\Symfony\Validator\Constraints\Password;
use App\Symfony\Validator\Constraints\Username;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class LoginMainFormType extends AbstractFormType
{
    /**
     * @var RouterInterface
     */
    protected $router;

    /**
     * @var Request
     */
    protected $request;

    /**
     * @var EmailHelper
     */
    protected $emailHelper;

    /**
     * @var ValidatorInterface
     */
    protected $validator;

    /**
     * @param RouterInterface    $router
     * @param EmailHelper        $emailHelper
     * @param ValidatorInterface $validator
     */
    public function __construct(RouterInterface $router, EmailHelper $emailHelper, ValidatorInterface $validator)
    {
        $this->router      = $router;
        $this->emailHelper = $emailHelper;
        $this->validator   = $validator;
    }

    /**
     * @inheritDoc
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $formType = $this;

        $builder->setAction($this->router->generate('app_auth_security_check')); //todo return_url
        $builder->setMethod(Request::METHOD_POST);

        $builder->add('_username', TextType::class, [
            'constraints' => [
                new Chain([
                    new NotBlank([
                        'message' => 'Введите e-mail или логин',
                    ]),
                    new Callback(function ($value, ExecutionContextInterface $context) use ($formType) {
                        $formType->validateUsername($value, $context);
                    }),
                ]),
            ],
            'attr'        => [
                'placeholder' => 'E-mail или логин',
            ],
        ]);

        $builder->add('_password', PasswordType::class, [
            'constraints' => [
                new Chain([
                    new NotBlank([
                        'message' => 'Введите пароль',
                    ]),
                    new Password(),
                ]),
            ],
            'attr'        => [
                'placeholder' => 'Пароль',
            ],
        ]);

        $builder->add('_remember_me', CheckboxType::class, [
            'label' => 'Запомнить меня',
        ]);
    }

    /**
     * @param string                    $value
     * @param ExecutionContextInterface $context
     */
    public function validateUsername($value, ExecutionContextInterface $context)
    {
        if ($this->emailHelper->isEmail($value)) {
            $constraints = [
                new Email(),
            ];
        } else {
            $constraints = [
                new Username(),
            ];
        }
        $this->validator->inContext($context)->validate($value, $constraints);
    }

}
