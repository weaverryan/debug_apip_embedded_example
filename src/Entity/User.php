<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\UniqueConstraint;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource(
 *     normalizationContext={
 *         "skip_null_values"=false,
 *         "groups"={"user:read"},
 *     },
 *     denormalizationContext={
 *         "groups"={"user:write"}
 *     })
 * @ApiFilter(SearchFilter::class, properties={"username": "exact", "username_canonical": "exact", "email": "exact"})
 * @ORM\Entity()
 * @ORM\Table(uniqueConstraints={@UniqueConstraint(name="username_uniq", columns={"username"})})
 */
class User
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"user:read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=15)
     * @Groups({"user:read", "user:write", "employee:item:get", "employee:write"})
     */
    private $username;

    /**
     * @ORM\Column(type="string", length=15)
     * @Groups({"user:read", "user:write"})
     */
    private $username_canonical;

    /**
     * @ORM\Column(type="string", length=60)
     * @Groups({"user:read", "user:write"})
     */
    private $email;

    /**
     * @ORM\Column(type="boolean")
     * @Groups({"user:read", "user:write"})
     */
    private $enabled;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"user:read", "user:write"})
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"user:read", "user:write"})
     */
    private $confirmation_token;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"user:read", "user:write"})
     */
    private $registration_date;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"user:read", "user:write"})
     */
    private $salt;

    /**
     * @ORM\Column(type="array", nullable=true)
     * @Groups({"user:read", "user:write"})
     */
    private $roles = [];

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @Groups({"user:read", "user:write"})
     */
    private $last_login;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Employee", mappedBy="owner")
     * @Groups({"user:read"})
     */
    private $employees;

    public function __construct()
    {
        $this->employees = new ArrayCollection();
        $this->confirmation_token = 'foo';
        $this->registration_date = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;
        $this->username_canonical = $username;

        return $this;
    }

    public function getUsernameCanonical(): ?string
    {
        return $this->username_canonical;
    }

    public function setUsernameCanonical(string $username_canonical): self
    {
        $this->username_canonical = $username_canonical;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getEnabled(): ?bool
    {
        return $this->enabled;
    }

    public function setEnabled(bool $enabled): self
    {
        $this->enabled = $enabled;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getConfirmationToken(): ?string
    {
        return $this->confirmation_token;
    }

    public function setConfirmationToken(string $confirmation_token): self
    {
        $this->confirmation_token = $confirmation_token;

        return $this;
    }

    public function getRegistrationDate(): ?\DateTimeInterface
    {
        return $this->registration_date;
    }

    public function setRegistrationDate(\DateTimeInterface $registration_date): self
    {
        $this->registration_date = $registration_date;

        return $this;
    }

    public function getSalt(): ?string
    {
        return $this->salt;
    }

    public function setSalt(?string $salt): self
    {
        $this->salt = $salt;

        return $this;
    }

    public function getRoles(): ?array
    {
        return $this->roles;
    }

    public function setRoles(?array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    public function getLastLogin(): ?\DateTimeInterface
    {
        return $this->last_login;
    }

    public function setLastLogin(?\DateTimeInterface $last_login): self
    {
        $this->last_login = $last_login;

        return $this;
    }

    /**
     * @return Collection|Employee[]
     */
    public function getEmployees(): Collection
    {
        return $this->employees;
    }

    public function addEmployee(Employee $employee): self
    {
        if (!$this->employees->contains($employee)) {
            $this->employees[] = $employee;
            $employee->setOwner($this);
        }

        return $this;
    }

    public function removeEmployee(Employee $employee): self
    {
        if ($this->employees->contains($employee)) {
            $this->employees->removeElement($employee);
            // set the owning side to null (unless already changed)
            if ($employee->getOwner() === $this) {
                $employee->setOwner(null);
            }
        }

        return $this;
    }
}
