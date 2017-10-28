<?php

namespace App\FOS\User;

use FOS\UserBundle\Model\UserInterface as FOSUserInterface;
//use Sonata\UserBundle\Model\UserInterface as SonataUserInterface;

/**
 * Сюда поместим всякую чушь из Fos и Sonata, чтобы этот код не мешался в классе пользователя
 * Работа с ролями чистой воды копипаст
 */
abstract class AbstractUser implements FOSUserInterface//, SonataUserInterface
{
    /**
     * @var array
     */
    protected $roles;

    /**
     * @inheritdoc
     */
    public function getRoles() : array
    {
        return $this->roles;
    }

    /**
     * @inheritdoc
     */
    public function setRoles(array $roles)
    {
        $this->roles = array();
        foreach ($roles as $role) {
            $this->addRole($role);
        }
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function hasRole($role) : bool
    {
        return in_array(strtoupper($role), $this->getRoles(), true);
    }

    /**
     * {@inheritdoc}
     */
    public function addRole($role)
    {
        $role = strtoupper($role);
        if ($role === static::ROLE_DEFAULT) {
            return $this;
        }
        if (!in_array($role, $this->roles, true)) {
            $this->roles[] = $role;
        }
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function removeRole($role)
    {
        if (false !== $key = array_search(strtoupper($role), $this->roles, true)) {
            unset($this->roles[$key]);
            $this->roles = array_values($this->roles);
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function isSuperAdmin() : bool
    {
        return $this->hasRole(static::ROLE_SUPER_ADMIN);
    }

    /**
     * {@inheritdoc}
     */
    public function setSuperAdmin($boolean)
    {
        if (true === $boolean) {
            $this->addRole(static::ROLE_SUPER_ADMIN);
        } else {
            $this->removeRole(static::ROLE_SUPER_ADMIN);
        }
        return $this;
    }

    /**
     * @deprecated мы используем bcrybt
     * @inheritdoc
     */
    public function getSalt()
    {
        return '';
    }

    /**
     * @deprecated мы используем bcrybt
     * @inheritdoc
     */
    public function setSalt($salt)
    {
        return $this;
    }

    /**
     * @deprecated Регистрации через модель не будет
     * @inheritdoc
     */
    public function getPlainPassword()
    {
        return '';
    }

    /**
     * @deprecated Регистрации через модель не будет
     * @inheritdoc
     */
    public function setPlainPassword($password)
    {
        return $this;
    }

    /**
     * @deprecated Регистрации через модель не будет
     * @inheritdoc
     */
    public function eraseCredentials()
    {
    }

    /**
     * @deprecated у нас не регистрозависимая бд
     * @inheritDoc
     */
    public function getUsernameCanonical()
    {
        return $this->getUsername();
    }

    /**
     * @deprecated у нас не регистрозависимая бд
     * @inheritDoc
     */
    public function setUsernameCanonical($usernameCanonical)
    {
        return $this;
    }

    /**
     * @deprecated у нас не регистрозависимая бд
     * @inheritDoc
     */
    public function getEmailCanonical()
    {
        return $this->getEmail();
    }

    /**
     * @deprecated у нас не регистрозависимая бд
     * @inheritDoc
     */
    public function setEmailCanonical($emailCanonical)
    {
        return $this;
    }

    /**
     * @deprecated Просрочки у нас пока не будет
     * @inheritdoc
     */
    public function isAccountNonExpired()
    {
        return true;
    }

    /**
     * @deprecated Просрочки у нас пока не будет
     * @inheritdoc
     */
    public function isCredentialsNonExpired()
    {
        return true;
    }

    /**
     * @deprecated двухэтапной авторизации не будет
     * @inheritDoc
     */
    public function getTwoStepVerificationCode()
    {
        return '';
    }

    /**
     * @deprecated двухэтапной авторизации не будет
     * @inheritDoc
     */
    public function setTwoStepVerificationCode($code)
    {
        return $this;
    }

    /**
     * @deprecated у нас отдельный лог
     * @inheritDoc
     */
    public function setLastLogin(\DateTime $time = null)
    {
        return $this;
    }

    /**
     * @deprecated токены будут в отдельной таблице
     * @inheritDoc
     */
    public function getConfirmationToken()
    {
        return '';
    }

    /**
     * @deprecated токены будут в отдельной таблице
     * @inheritDoc
     */
    public function setConfirmationToken($confirmationToken)
    {
        return $this;
    }

    /**
     * @deprecated токены будут в отдельной таблице
     * @inheritDoc
     */
    public function setPasswordRequestedAt(\DateTime $date = null)
    {
        return $this;
    }

    /**
     * @deprecated токены будут в отдельной таблице
     * @inheritDoc
     */
    public function isPasswordRequestNonExpired($ttl)
    {
        return true;
    }

}
