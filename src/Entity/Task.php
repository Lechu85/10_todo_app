<?php

namespace App\Entity;

use App\Repository\TaskRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: TaskRepository::class)]
class Task
{

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'Podaj tresc zadania')]
    private ?string $task = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    #[Assert\Type(\DateTime::class)]
    private ?\DateTimeInterface $dueDate = null;

    #[ORM\Column(nullable: true)]
    private ?bool $important = null;

    #[ORM\ManyToOne(inversedBy: 'tasks')]
    private ?User $user = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(type: Types::SMALLINT)]
    private ?int $status = null;

	//todo z automatu wstawiac
    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $createdAt = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $updatedAt = null;

    #[ORM\Column(nullable: true)]
    private ?bool $deleted = null;

    #[ORM\Column(type: Types::SMALLINT, nullable: true)]
    private ?int $prioryty = null;

    #[ORM\Column(nullable: true)]
    private ?bool $pinned = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $doneAt = null;

    #[ORM\Column(nullable: true)]
    private ?int $doneByUser = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $remind = null;

    #[ORM\ManyToOne(inversedBy: 'tasks')]
    private ?TaskCategory $Category = null;

    #[ORM\Column(nullable: true)]
    private ?bool $wontDo = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTask(): ?string
    {
        return $this->task;
    }

    public function setTask(?string $task): self
    {
        $this->task = $task;

        return $this;
    }

    public function getDueDate(): ?\DateTimeInterface
    {
        return $this->dueDate;
    }

    public function setDueDate(?\DateTimeInterface $dueDate): self
    {
        $this->dueDate = $dueDate;

        return $this;
    }

    public function isImportant(): ?bool
    {
        return $this->important;
    }

    public function setImportant(?bool $important): self
    {
        $this->important = $important;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getStatus(): ?int
    {
        return $this->status;
    }

    public function setStatus(?int $status): self
    {
		if ($status == null) {
			$this->status = 1;
		} else {
			$this->status = $status;
		}

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
	    if ($createdAt == null) {
			$this->createdAt = new \DateTime();
	    } else {
	        $this->createdAt = $createdAt;
        }
        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function isDeleted(): ?bool
    {
        return $this->deleted;
    }

    public function setDeleted(?bool $deleted): self
    {
        $this->deleted = $deleted;

        return $this;
    }

    public function getPrioryty(): ?int
    {
        return $this->prioryty;
    }

    public function setPrioryty(?int $prioryty): self
    {
        $this->prioryty = $prioryty;

        return $this;
    }

    public function isPinned(): ?bool
    {
        return $this->pinned;
    }

    public function setPinned(?bool $pinned): self
    {
        $this->pinned = $pinned;

        return $this;
    }

    public function getDoneAt(): ?\DateTimeInterface
    {
        return $this->doneAt;
    }

    public function setDoneAt(?\DateTimeInterface $doneAt): self
    {
        $this->doneAt = $doneAt;

        return $this;
    }

    public function getDoneByUser(): ?int
    {
        return $this->doneByUser;
    }

    public function setDoneByUser(?int $doneByUser): self
    {
        $this->doneByUser = $doneByUser;

        return $this;
    }

    public function getRemind(): ?string
    {
        return $this->remind;
    }

    public function setRemind(?string $remind): self
    {
        $this->remind = $remind;

        return $this;
    }

	public function toArray()
                  	{
                  		return get_object_vars($this);
                  	}

    public function getCategory(): ?TaskCategory
    {
        return $this->Category;
    }

    public function setCategory(?TaskCategory $Category): self
    {
        $this->Category = $Category;

        return $this;
    }

    public function isWontDo(): ?bool
    {
        return $this->wontDo;
    }

    public function setWontDo(?bool $wontDo): self
    {
        $this->wontDo = $wontDo;

        return $this;
    }

}
