<?php

namespace Glavnivc\UserBundle\Entity;

use DateTime;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * TODO: здесь нет имени, фамилии и данных профиля - упрощенный вариант
 *
 * @ORM\Table(name="`user`")
 * @ORM\Entity(repositoryClass="Glavnivc\UserBundle\Repository\UserRepository")
 */
class User implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\Column(type="uuid", unique=true)
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class="Ramsey\Uuid\Doctrine\UuidGenerator")
     */
    private $id;

    /**
     * Уникальный логин пользователя.
     *
     * @var string
     * @ORM\Column(type="string", unique=true, nullable=false)
     */
    private $username;

    /**
     * Email - также уникальный идентификатор пользователя.
     *
     * @var string
     * @ORM\Column(type="string", unique=true, nullable=false)
     */
    private $email;

    /**
     * Любые дополнительные данные.
     * @ORM\Column(type="json", nullable=true)
     */
    private $data = [];

    /**
     * @ORM\Column(type="json", nullable=true)
     */
    private $roles = [];

    /**
     * Хэш пароля.
     * @var string
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @var DateTime
     */
    private $lastLogin;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $isActive = true;

    /**
     * @ORM\ManyToMany(targetEntity=Role::class)
     */
    private $role;

    ///
    /// геттеры и сеттеры
    ///

    public function getId()
    {
        return $this->id;
    }

    /////////////////////////////////////////////////
    // обязательные геттеры из интерфейса

    public function getUsername(): string
    {
        return (string)$this->username;
    }

    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        // TODO: проверить, уместно ли здесь это
        $roles[] = 'ROLE_USER';
        return array_unique($roles);
    }

    public function getRole() : Collection
    {
        return $this->role;
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;
        return $this;
    }

    public function getPassword(): string
    {
        return (string)$this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;
        return $this;
    }

    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
        // TODO: нужен ли нам plainPassword?
    }

    //////////////////////////////////////
    // не интерфейсные геттеры/сеттеры

    public function setUsername(string $username): self
    {
        $this->username = $username;
        return $this;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;
        return $this;
    }

    // уместно ли это в сущности?
    public function hasRole(string $role) : bool
    {
        return in_array($role, $this->getRoles(), true);
    }

    public function getData(): ?array
    {
        return $this->data;
    }

    public function setData(?array $data): self
    {
        $this->data = $data;
        return $this;
    }

    public function isActive(): bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive)  : self
    {
        $this->isActive = $isActive;
        return $this;
    }

    public function getLastLogin(): ?DateTime
    {
        return $this->lastLogin;
    }

    public function setLastLogin(?DateTime $lastLogin) : self
    {
        $this->lastLogin = $lastLogin;
        return $this;
    }

    /////////////////////////////////////////////////
    // работа с датой и временем создания/обновления

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @var DateTime
     */
    protected $createdAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @var DateTime
     */
    protected $updatedAt;

    /**
     * Обновление даты перед созданием сущности.
     * @ORM\PrePersist
     */
    public function updateCreatedAt()
    {
        $this->createdAt = new DateTime("now");
        $this->updatedAt = new DateTime("now");
    }

    /**
     * Обновление даты при обновлении сущности.
     * @ORM\PreUpdate
     */
    public function updateUpdatedAt()
    {
        $this->updatedAt = new DateTime("now");
    }

    public function setCreatedAt(DateTime $createdAt)
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    public function setUpdatedAt(DateTime $updatedAt)
    {
        $this->updatedAt = $updatedAt;
        return $this;
    }

    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }
}
