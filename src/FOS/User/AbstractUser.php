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
     * @deprecated мы используем bcrybt
     */
    public function getSalt()
    {
        return '';
    }

    /**
     * @deprecated мы используем bcrybt
     */
    public function setSalt($salt)
    {
        return $this;
    }

    /**
     * @deprecated Регистрации через модель не будет
     */
    public function getPlainPassword()
    {
        return '';
    }

    /**
     * @deprecated Регистрации через модель не будет
     */
    public function setPlainPassword($password)
    {
        return $this;
    }

    /**
     * @deprecated Регистрации через модель не будет
     */
    public function eraseCredentials()
    {
    }

    /**
     * @deprecated у нас не регистрозависимая бд
     */
    public function getUsernameCanonical()
    {
        return $this->getUsername();
    }

    /**
     * @deprecated у нас не регистрозависимая бд
     */
    public function setUsernameCanonical($usernameCanonical)
    {
        return $this;
    }

    /**
     * @deprecated у нас не регистрозависимая бд
     */
    public function getEmailCanonical()
    {
        return $this->getEmail();
    }

    /**
     * @deprecated у нас не регистрозависимая бд
     */
    public function setEmailCanonical($emailCanonical)
    {
        return $this;
    }

    /**
     * @deprecated Просрочки у нас пока не будет
     */
    public function isAccountNonExpired()
    {
        return true;
    }

    /**
     * @deprecated Просрочки у нас пока не будет
     */
    public function isCredentialsNonExpired()
    {
        return true;
    }

    /**
     * @deprecated двухэтапной авторизации не будет
     */
    public function getTwoStepVerificationCode()
    {
        return '';
    }

    /**
     * @deprecated двухэтапной авторизации не будет
     */
    public function setTwoStepVerificationCode($code)
    {
        return $this;
    }

    /**
     * @deprecated у нас отдельный лог
     */
    public function setLastLogin(\DateTime $time = null)
    {
        return $this;
    }

    /**
     * @deprecated токены будут в отдельной таблице
     */
    public function getConfirmationToken()
    {
        return '';
    }

    /**
     * @deprecated токены будут в отдельной таблице
     */
    public function setConfirmationToken($confirmationToken)
    {
        return $this;
    }

    /**
     * @deprecated токены будут в отдельной таблице
     */
    public function setPasswordRequestedAt(\DateTime $date = null)
    {
        return $this;
    }

    /**
     * @deprecated токены будут в отдельной таблице
     */
    public function isPasswordRequestNonExpired($ttl)
    {
        return true;
    }
}
