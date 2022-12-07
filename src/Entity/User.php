<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]
#[ORM\Table('`user`')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
	use TimestampableEntity;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    private ?string $email = null;

	#[ORM\Column(type: Types::JSON)]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

	/**
	 * The plain non-persisted password
	 */
	private ?string $plainPassword;

	#[ORM\Column]
	private bool $enabled = true;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $name = null;

    #[ORM\Column(type: 'boolean')]
    private $isVerified = false;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Task::class)]
    private Collection $tasks;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $agreedTermsAt = null;

	#[ORM\Column(nullable: true)]
	private ?string $avatar;

	#[ORM\OneToMany('askedBy', Question::class)]
	private Collection $questions;

	#[ORM\OneToMany('answeredBy', Answer::class)]
	private Collection $answers;

    public function __construct()
    {
        $this->tasks = new ArrayCollection();
	    $this->questions = new ArrayCollection();
	    $this->answers = new ArrayCollection();
    }

	public function __toString(): string
	{
		return $this->getFullName();
	}

    public function getId(): ?int
    {
        return $this->id;
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

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

	public function getPlainPassword(): string
	{
		return $this->plainPassword;
	}

	public function setPlainPassword(string $plainPassword): void
	{
		$this->plainPassword = $plainPassword;
	}

	/**
	 * Returning a salt is only needed, if you are not using a modern
	 * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
	 *
	 * @see UserInterface
	 */
	public function getSalt(): ?string
	{
		return null;
	}

	/**
	 * @see UserInterface
	 */
	public function eraseCredentials()
	{
		// If you store any temporary, sensitive data on the user, clear it here
		$this->plainPassword = null;
	}

	public function isEnabled(): bool
	{
		return $this->enabled;
	}

	public function setEnabled(bool $enabled): void
	{
		$this->enabled = $enabled;
	}

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

	public function getFullName(): ?string
	{
		return $this->name;
		//return $this->firstName.' '.$this->lastName;
	}

    public function isVerified(): bool
    {
        return $this->isVerified;
    }

    public function setIsVerified(bool $isVerified): self
    {
        $this->isVerified = $isVerified;

        return $this;
    }

    /**
     * @return Collection<int, Task>
     */
    public function getTasks(): Collection
    {
        return $this->tasks;
    }

    public function addTask(Task $task): self
    {
        if (!$this->tasks->contains($task)) {
            $this->tasks->add($task);
            $task->setUser($this);
        }

        return $this;
    }

    public function removeTask(Task $task): self
    {
        if ($this->tasks->removeElement($task)) {
            // set the owning side to null (unless already changed)
            if ($task->getUser() === $this) {
                $task->setUser(null);
            }
        }

        return $this;
    }

    public function getAgreedTermsAt(): ?\DateTimeInterface
    {
        return $this->agreedTermsAt;
    }

	//?\DateTimeInterface $agreedTermsAt
    public function agreeToTerms(): self
    {
	    $this->agreedTermsAt = new \DateTime();
        return $this;
    }


	public function getAvatar(): ?string
	{
		return $this->avatar;
	}

	public function getAvatarUrl(): ?string
	{
		if (!$this->avatar) {
			return null;
		}

		if (strpos($this->avatar, '/') !== false) {
			return $this->avatar;
		}

		return sprintf('/uploads/avatars/%s', $this->avatar);
	}

	public function setAvatar(?string $avatar): void
	{
		$this->avatar = $avatar;
	}

	/**
	 * @return Collection|Question[]
	 */
	public function getQuestions(): Collection
	{
		return $this->questions;
	}

	public function addQuestion(Question $question): self
	{
		if (!$this->questions->contains($question)) {
			$this->questions[] = $question;
			$question->setAskedBy($this);
		}

		return $this;
	}

	public function removeQuestion(Question $question): self
	{
		if ($this->questions->removeElement($question)) {
			// set the owning side to null (unless already changed)
			if ($question->getAskedBy() === $this) {
				$question->setAskedBy(null);
			}
		}

		return $this;
	}

	/**
	 * @return Collection|Answer[]
	 */
	public function getAnswers(): Collection
	{
		return $this->answers;
	}

	public function addAnswer(Answer $answer): self
	{
		if (!$this->answers->contains($answer)) {
			$this->answers[] = $answer;
			$answer->setAnsweredBy($this);
		}

		return $this;
	}

	public function removeAnswer(Answer $answer): self
	{
		if ($this->answers->removeElement($answer)) {
			// set the owning side to null (unless already changed)
			if ($answer->getAnsweredBy() === $this) {
				$answer->setAnsweredBy(null);
			}
		}

		return $this;
	}
}
