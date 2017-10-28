<?php

namespace App\Auth\Form\Type;

use App\Symfony\Validator\Constraints\Chain;
use App\Symfony\Validator\Constraints\Password;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class LoginAdminFormType extends LoginMainFormType
{
    /**
     * @inheritdoc
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $formType = $this;

        $builder->setAction($this->router->generate('app_auth_admin_security_check'));
        $builder->setMethod(Request::METHOD_POST);

        $builder->add('_username', TextType::class, [
            'constraints' => [
                new Chain([
                    new Constraints\NotBlank(),
                    new Constraints\Callback(function ($value, ExecutionContextInterface $context) use ($formType) {
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
                    new Constraints\NotBlank([
                        'message' => 'Введите пароль',
                    ]),
                    new Password(),
                ]),
            ],
            'attr'        => [
                'placeholder' => 'Пароль',
            ],
        ]);
    }

}
