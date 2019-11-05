<?php

namespace App\Doctrine\Entity;

use App\Doctrine\Column;
use App\FOS\User\AbstractUser;
use App\User\GenderedInterface;
use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="user")
 * @ORM\Entity(repositoryClass="App\Doctrine\Repository\UserRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class User extends AbstractUser implements GenderedInterface, \JsonSerializable
{
    const ROLE_ADMIN = 'ROLE_ADMIN';

    use Column\Id;

    /**
     * @var string
     * @ORM\Column(name="username", type="string", unique=true, options={"default": ""})
     */
    private $username = '';

    /**
     * @var string
     * @ORM\Column(name="email", type="string", unique=true, options={"default": ""})
     */
    private $email = '';

    /**
     * @var bool
     * @ORM\Column(name="is_enabled", type="boolean", options={"default": false})
     */
    private $isEnabled = false;

    /**
     * @var bool
     * @ORM\Column(name="is_locked", type="boolean", options={"default": false})
     */
    private $isLocked = false;

    use Column\FIO;

    /**
     * @var int
     * @ORM\Column(name="gender", type="smallint", options={"default": 0})
     */
    private $gender = GenderedInterface::GENDER_UNKNOWN;

    /**
     * @var DateTime|null
     * @ORM\Column(name="date_birthday", type="date", nullable=true)
     */
    private $dateBirthday;

    /**
     * @var string
     * @ORM\Column(name="password", type="string", options={"default": ""})
     */
    private $password = '';

    /**
     * @var bool
     * @ORM\Column(name="is_admin", type="boolean", options={"default": false})
     */
    private $isAdmin = false;

    /**
     * @var bool
     * @ORM\Column(name="is_super_admin", type="boolean", options={"default": false})
     */
    private $isSuperAdmin = false;

    /**
     * @var array
     * @ORM\Column(name="roles", type="json")
     */
    private $roles = [];

    use Column\DateCreated;

    /**
     * @var string
     * @ORM\Column(name="timezone", type="string", options={"default": ""})
     */
    private $timezone = '';

    public function getUsername()
    {
        return $this->username;
    }

    public function setUsername($username)
    {
        $this->username = $username;
        return $this;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setEmail($email)
    {
        $this->email = $email;
        return $this;
    }

    public function isEnabled()
    {
        return $this->isEnabled;
    }

    public function setIsEnabled(bool $isEnabled)
    {
        $this->isEnabled = $isEnabled;
        return $this;
    }

    public function setEnabled($boolean)
    {
        return $this->setIsEnabled($boolean);
    }

    public function isLocked() : bool
    {
        return $this->isLocked;
    }

    public function setIsLocked(bool $isLocked)
    {
        $this->isLocked = $isLocked;
        return $this;
    }

    public function isAccountNonLocked()
    {
        return $this->isLocked() == false;
    }

    public function getGender() : int
    {
        return $this->gender;
    }

    public function setGender(int $gender)
    {
        $this->gender = $gender;
        return $this;
    }

    public function setDateBirthday(?DateTime $dateBirthday)
    {
        $this->dateBirthday = $dateBirthday;
        return $this;
    }

    public function getDateBirthday() : ?DateTime
    {
        return $this->dateBirthday;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function setPassword($password)
    {
        $this->password = $password;
        return $this;
    }

    public function isAdmin() : bool
    {
        return $this->isAdmin;
    }

    public function setIsAdmin(bool $isAdmin)
    {
        $this->isAdmin = $isAdmin;
        if (!$isAdmin) {
            $this->setIsSuperAdmin(false);
        }
        return $this;
    }

    public function isSuperAdmin()
    {
        return $this->isSuperAdmin;
    }

    public function setIsSuperAdmin(bool $isSuperAdmin)
    {
        $this->isSuperAdmin = $isSuperAdmin;
        return $this;
    }

    public function setSuperAdmin($isSuperAdmin)
    {
        return $this->setIsSuperAdmin((bool)$isSuperAdmin);
    }

    public function getRoles()
    {
        $roles = $this->roles;
        $roles[] = static::ROLE_DEFAULT;
        if ($this->isAdmin()) {
            $roles[] = static::ROLE_ADMIN;
        }
        if ($this->isSuperAdmin()) {
            $roles[] = static::ROLE_SUPER_ADMIN;
        }
        return $roles;
    }

    public function setRoles(array $roles)
    {
        $this->roles = [];
        foreach ($roles as $role) {
            $this->addRole($role);
        }
        return $this;
    }

    public function hasRole($role)
    {
        return in_array(strtoupper($role), $this->getRoles(), true);
    }

    public function addRole($role)
    {
        $role = strtoupper($role);
        if ($role === static::ROLE_DEFAULT) {
            return $this;
        } elseif ($role == static::ROLE_SUPER_ADMIN) {
            $this->setIsSuperAdmin(true);
            $this->setIsAdmin(true);
        } elseif ($role == static::ROLE_ADMIN) {
            $this->setIsAdmin(true);
        } else {
            if (!in_array($role, $this->roles, true)) {
                $this->roles[] = $role;
            }
        }
        return $this;
    }

    public function removeRole($role)
    {
        if ($role === static::ROLE_DEFAULT) {
            return $this;
        } elseif ($role == static::ROLE_SUPER_ADMIN) {
            $this->setIsSuperAdmin(false);
        } elseif ($role == static::ROLE_ADMIN) {
            $this->setIsSuperAdmin(false);
            $this->setIsAdmin(false);
        } else {
            if (false !== $key = array_search(strtoupper($role), $this->roles, true)) {
                unset($this->roles[$key]);
                $this->roles = array_values($this->roles);
            }
        }
        return $this;
    }

    public function getTimezone() : string
    {
        return $this->timezone;
    }

    public function setTimezone(string $timezone)
    {
        $this->timezone = $timezone;
        return $this;
    }

    public function serialize()
    {
        return serialize([
            $this->id,
            $this->username,
            $this->email,
            $this->password,
            $this->isEnabled,
            $this->isLocked,
        ]);
    }

    public function unserialize($serialized)
    {
        $data = unserialize($serialized);
        list(
            $this->id,
            $this->username,
            $this->email,
            $this->password,
            $this->isEnabled,
            $this->isLocked,
        ) = $data;
    }

    public function jsonSerialize()
    {
        return [
            'id'       => $this->getId(),
            'username' => $this->getUsername(),
            'email'    => $this->getEmail(),
            'roles'    => $this->getRoles(),
        ];
    }

}
