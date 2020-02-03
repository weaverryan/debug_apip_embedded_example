<?php

namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Core\Annotation\ApiSubresource;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\OrderFilter;

/**
 * @ApiResource(
 *     collectionOperations={"get", "post"},
 *     itemOperations={
 *         "get"={
 *             "normalization_context"={"groups"={"employee:read", "employee:item:get"}},
 *         },
 *         "put",
 *     },
 *     normalizationContext={
 *         "skip_null_values"=false,
 *     },
 *     denormalizationContext={
 *         "groups"={"employee:write"}
 *     },
 *     attributes={
 *         "pagination_items_per_page"=2
 *     }
 * )
 * @ORM\Table(name="employee")
 * @ApiFilter(SearchFilter::class, properties={"job":"exact"})
 * @ApiFilter(OrderFilter::class, properties={"id", "name"})
 * @ORM\Entity(repositoryClass="App\Repository\EmployeeRepository")
 */
class Employee
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"employee:read"})
     */
    private $id;

    /**
     * @Assert\NotBlank()
     * @Assert\Length(
     *     min=2,
     *     max=30,
     *     maxMessage="Employee name should be less than 30 chars."
     * )
     * @ORM\Column(type="string", length=30)
     * @Groups({"employee:read", "employee:write", "user:read"})
     */
    private $name;

    /**
     * @Assert\NotBlank()
     * @ORM\Column(type="datetime")
     * @Groups({"employee:read", "employee:write"})
     */
    private $hired;

    /**
     * @Assert\NotBlank()
     * @ORM\Column(type="integer")
     * @Groups({"employee:read", "employee:write"})
     */
    private $experience;

    /**
     * @Assert\NotBlank()
     * @ORM\Column(type="decimal", precision=7, scale=2)
     * @Groups({"employee:read", "employee:write"})
     */
    private $salary;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @Groups({"employee:read", "employee:write"})
     */
    private $firedDate;

    /**
     * @Assert\NotBlank()
     * @ORM\ManyToOne(targetEntity="App\Entity\EmployeeJob", fetch="EAGER")
     * @ApiSubresource
     * @Groups({"employee:read", "employee:write"})
     */
    private $job;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="employees")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"employee:read", "employee:write", "user:read"})
     */
    private $owner;

    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getHired(): ?\DateTimeInterface
    {
        return $this->hired;
    }

    public function setHired(\DateTimeInterface $hired): self
    {
        $this->hired = $hired;

        return $this;
    }

    public function getExperience(): ?int
    {
        return $this->experience;
    }

    public function setExperience(int $experience): self
    {
        $this->experience = $experience;

        return $this;
    }

    public function getSalary(): ?string
    {
        return $this->salary;
    }

    public function setSalary(string $salary): self
    {
        $this->salary = $salary;

        return $this;
    }

    public function getFiredDate(): ?\DateTimeInterface
    {
        return $this->firedDate;
    }

    public function setFiredDate(?\DateTimeInterface $firedDate): self
    {
        $this->firedDate = $firedDate;

        return $this;
    }

    public function getJob(): ?EmployeeJob
    {
        return $this->job;
    }

    public function setJob(?EmployeeJob $job): self
    {
        $this->job = $job;

        return $this;
    }

    public function getOwner(): ?User
    {
        return $this->owner;
    }

    public function setOwner(?User $owner): self
    {
        $this->owner = $owner;

        return $this;
    }
}
