<?php

namespace App\Entity;

use App\Doctrine\Column;
use App\FOS\User\AbstractUser;
use App\User\GenderedInterface;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="user")
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
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
    protected $username = '';

    /**
     * @var string
     * @ORM\Column(name="email", type="string", unique=true, options={"default": ""})
     */
    protected $email = '';

    /**
     * @var bool
     * @ORM\Column(name="is_enabled", type="boolean", options={"default": false})
     */
    protected $isEnabled = false;

    /**
     * @var bool
     * @ORM\Column(name="is_locked", type="boolean", options={"default": false})
     */
    protected $isLocked = false;

    use Column\FIO;

    /**
     * @var int
     * @ORM\Column(name="gender", type="smallint", options={"default": 0})
     */
    protected $gender = GenderedInterface::GENDER_UNKNOWN;

    /**
     * @var \DateTime
     * @ORM\Column(name="date_birthday", type="date", nullable=true)
     */
    protected $dateBirthday;

    /**
     * @var string
     * @ORM\Column(name="password", type="string", options={"default": ""})
     */
    protected $password = '';

    /**
     * @var bool
     * @ORM\Column(name="is_admin", type="boolean", options={"default": false})
     */
    protected $isAdmin = false;

    /**
     * @var bool
     * @ORM\Column(name="is_super_admin", type="boolean", options={"default": false})
     */
    protected $isSuperAdmin = false;

    /**
     * @var array
     * @ORM\Column(name="roles", type="json_array")
     */
    protected $roles = [];

    use Column\DateCreated;

    /**
     * @return string
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * @param string $username
     * @return $this
     */
    public function setUsername($username)
    {
        $this->username = $username;
        return $this;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param string $email
     * @return $this
     */
    public function setEmail($email)
    {
        $this->email = $email;
        return $this;
    }

    /**
     * @return bool
     */
    public function isEnabled(): bool
    {
        return $this->isEnabled;
    }

    /**
     * @param bool $isEnabled
     * @return $this
     */
    public function setIsEnabled(bool $isEnabled)
    {
        $this->isEnabled = $isEnabled;
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function setEnabled($boolean)
    {
        return $this->setIsEnabled($boolean);
    }

    /**
     * @return bool
     */
    public function isLocked(): bool
    {
        return $this->isLocked;
    }

    /**
     * @param bool $isLocked
     * @return $this
     */
    public function setIsLocked(bool $isLocked)
    {
        $this->isLocked = $isLocked;
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function isAccountNonLocked() : bool
    {
        return $this->isLocked() == false;
    }

    /**
     * @return int
     */
    public function getGender(): int
    {
        return $this->gender;
    }

    /**
     * @param int $gender
     * @return $this
     */
    public function setGender(int $gender)
    {
        $this->gender = $gender;
        return $this;
    }

    /**
     * @param \DateTime $dateBirthday
     * @return $this
     */
    public function setDateBirthday(\DateTime $dateBirthday)
    {
        $this->dateBirthday = $dateBirthday;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getDateBirthday()
    {
        return $this->dateBirthday;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @param string $password
     * @return $this
     */
    public function setPassword($password)
    {
        $this->password = $password;
        return $this;
    }

    /**
     * @return bool
     */
    public function isAdmin() : bool
    {
        return $this->isAdmin;
    }

    /**
     * @param bool $isAdmin
     * @return $this
     */
    public function setIsAdmin(bool $isAdmin)
    {
        $this->isAdmin = $isAdmin;
        if (!$isAdmin) {
            $this->setIsSuperAdmin(false);
        }
        return $this;
    }

    /**
     * @return bool
     */
    public function isSuperAdmin() : bool
    {
        return $this->isSuperAdmin;
    }

    /**
     * @param bool $isSuperAdmin
     * @return $this
     */
    public function setIsSuperAdmin(bool $isSuperAdmin)
    {
        $this->isSuperAdmin = $isSuperAdmin;
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function setSuperAdmin($isSuperAdmin)
    {
        return $this->setIsSuperAdmin((bool)$isSuperAdmin);
    }

    /**
     * @inheritdoc
     */
    public function getRoles() : array
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

    /**
     * {@inheritdoc}
     */
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

    /**
     * @inheritdoc
     */
    public function serialize() : string
    {
        return serialize(array(
            $this->id,
            $this->username,
            $this->email,
            $this->password,
            $this->isEnabled,
            $this->isLocked,
        ));
    }

    /**
     * @inheritdoc
     */
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

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->username;
    }

    /**
     * @return array
     */
    public function jsonSerialize() : array
    {
        return [
            'id'       => $this->getId(),
            'username' => $this->getUsername(),
            'email'    => $this->getEmail(),
            'roles'    => $this->getRoles(),
        ];
    }

}
