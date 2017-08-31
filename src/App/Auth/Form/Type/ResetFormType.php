<?php

namespace App\Auth\Form\Type;

use App\Auth\LoginManager;
use App\Symfony\Form\AbstractType;
use App\Symfony\Validator\Constraints\Chain;
use App\Symfony\Validator\Constraints\Password;
use App\Symfony\Validator\Constraints\UserPassword;
use App\User\UserManipulator;
use App\User\UserTokenManager;
use AppBundle\Entity\User;
use AppBundle\Entity\UserToken;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Validator\Constraints\NotNull;

class ResetFormType extends AbstractType
{
    /**
     * @var UserManipulator
     */
    protected $userManipulator;

    /**
     * @var UserTokenManager
     */
    protected $userTokenManager;

    /**
     * @var RequestStack
     */
    protected $requestStack;

    /**
     * @var LoginManager
     */
    protected $loginManager;

    /**
     * @param UserManipulator  $userManipulator
     * @param UserTokenManager $userTokenManager
     * @param RequestStack     $requestStack
     * @param LoginManager     $loginManager
     */
    public function __construct(UserManipulator $userManipulator, UserTokenManager $userTokenManager, RequestStack $requestStack, LoginManager $loginManager)
    {
        $this->userManipulator  = $userManipulator;
        $this->userTokenManager = $userTokenManager;
        $this->requestStack     = $requestStack;
        $this->loginManager     = $loginManager;
    }

    /**
     * @inheritdoc
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $formType = $this;

        $builder->setMethod(Request::METHOD_POST)->setAction('');

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
                    new UserPassword([
                        'user'         => $this->getUserFromRequestToken(),
                        'notValidMode' => true,
                        'message'      => 'Новый пароль не должны совпадать с текущим',
                    ]),
                ]),
            ],
        ]);

        $builder->addEventListener(FormEvents::POST_SUBMIT, function(FormEvent $event) use ($formType) {
            $formType->postSubmit($event);
        });
    }

    /**
     * @return User
     */
    protected function getUserFromRequestToken()
    {
        //это хак, чтобы добыть юзера, проще было бы сделать хендлер для формы, но не хочу плодить классы
        $tokenHash = $this->requestStack->getCurrentRequest()->attributes->get('_route_params')['token'];
        $userToken = $this->userTokenManager->findTokenByHashAndType($tokenHash, UserToken::TYPE_RESET_PASSWORD);

        return $userToken->getUser();
    }

    /**
     * @param FormEvent $event
     */
    public function postSubmit(FormEvent $event)
    {
        $form = $event->getForm();
        if (!$form->isSubmitted() || !$form->isValid()) {
            return;
        }

        $user = $this->getUserFromRequestToken();

        $this->userManipulator->enable($user); //Подтверждаем юзера
        $this->userManipulator->changePassword($user, $event->getForm()->getData()['password']);

        $this->loginManager->loginUserByLink($user, 'main');
    }

}
