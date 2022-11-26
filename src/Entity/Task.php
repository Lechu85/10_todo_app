<?php

namespace App\Entity;

use App\Repository\TaskRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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
    private ?string $title = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    #[Assert\Type(\DateTime::class)]
    private ?\DateTimeInterface $dueDate = null;

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

    #[ORM\ManyToOne(inversedBy: 'tasks')]
    private ?TaskCategory $Category = null;

    #[ORM\Column(nullable: true)]
    private ?bool $wontDo = null;

    #[ORM\Column(type: Types::SMALLINT, nullable: true)]
    private ?int $type = null;

    #[ORM\Column(nullable: true)]
    private ?float $cost = null;

    #[ORM\Column(length: 20, nullable: true)]
    private ?string $estimatedTime = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $repeatRules = null;

    #[ORM\Column(length: 30, nullable: true)]
    private ?string $repeatUntil = null;

    #[ORM\OneToMany(mappedBy: 'task', targetEntity: TaskNotification::class, orphanRemoval: true)]
    private Collection $notifications;

    public function __construct()
    {
        $this->notifications = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): self
    {
        $this->title = $title;

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

    public function getType(): ?int
    {
        return $this->type;
    }

    public function setType(?int $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getCost(): ?float
    {
        return $this->cost;
    }

    public function setCost(?float $cost): self
    {
        $this->cost = $cost;

        return $this;
    }

    public function getEstimatedTime(): ?string
    {
        return $this->estimatedTime;
    }

    public function setEstimatedTime(?string $estimatedTime): self
    {
        $this->estimatedTime = $estimatedTime;

        return $this;
    }

    public function getRepeatRules(): ?string
    {
        return $this->repeatRules;
    }

    public function setRepeatRules(?string $repeatRules): self
    {
        $this->repeatRules = $repeatRules;

        return $this;
    }

    public function getRepeatUntil(): ?string
    {
        return $this->repeatUntil;
    }

    public function setRepeatUntil(?string $repeatUntil): self
    {
        $this->repeatUntil = $repeatUntil;

        return $this;
    }

    /**
     * @return Collection<int, TaskNotification>
     */
    public function getNotifications(): Collection
    {
        return $this->notifications;
    }

    public function addNotification(TaskNotification $notification): self
    {
        if (!$this->notifications->contains($notification)) {
            $this->notifications->add($notification);
            $notification->setTask($this);
        }

        return $this;
    }

    public function removeNotification(TaskNotification $notification): self
    {
        if ($this->notifications->removeElement($notification)) {
            // set the owning side to null (unless already changed)
            if ($notification->getTask() === $this) {
                $notification->setTask(null);
            }
        }

        return $this;
    }

}
