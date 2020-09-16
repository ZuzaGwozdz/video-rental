<?php
/**
 * User Data entity.
 */

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=UserDataRepository::class)
 * @ORM\Table(name="user_data")
 */
class UserData
{
    /**
     * Primary key.
     *
     * @var int
     *
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * Name.
     *
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     *
     * @Assert\Type(type="string")
     * @Assert\NotBlank
     * @Assert\Length(
     *     min="3",
     *     max="255",
     *     )
     */
    private $name;

    /**
     * Surname.
     *
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     *
     * @Assert\Type(type="string")
     * @Assert\NotBlank
     * @Assert\Length(
     *     min="3",
     *     max="255",
     *     )
     */
    private $surname;

    /**
     * Birthday.
     *
     * @var date
     *
     * @ORM\Column(type="date")
     *
     * @Assert\Type(type="date")
     */
    private $birthday;

    /**
     * User.
     *
     * @var User
     *
     * @ORM\OneToOne(targetEntity=User::class, inversedBy="userData", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * Blocked.
     *
     * @var bool
     *
     * @ORM\Column(type="boolean")
     *
     * @Assert\Type(type="boolean")
     */
    private $blocked;

    /**
     * Getter for Id.
     *
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Getter for name.
     *
     * @return string|null Name
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * Setter for Name.
     *
     * @param string $name Name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * Getter for surname.
     *
     * @return string|null Title
     */
    public function getSurname(): ?string
    {
        return $this->surname;
    }

    /**
     * Setter for Surname.
     *
     * @param string $surname Surname
     */
    public function setSurname(string $surname): void
    {
        $this->surname = $surname;
    }

    /**
     * Setter for Birthday.
     *
     * @return DateTimeInterface|null Birthday
     */
    public function getBirthday(): ?\DateTimeInterface
    {
        return $this->birthday;
    }

    /**
     * Setter for Birthday.
     *
     * @param DateTimeInterface $birthday Birthday
     */
    public function setBirthday(?\DateTimeInterface $birthday): void
    {
        $this->birthday = $birthday;
    }

    /**
     * Getter for User.
     *
     * @return User|null User
     */
    public function getUser(): ?User
    {
        return $this->user;
    }

    /**
     * Setter for User.
     *
     * @param User $user User
     */
    public function setUser(User $user): void
    {
        $this->user = $user;
    }

    /**
     * Getter for Blocked.
     *
     * @return bool|null Blocked
     */
    public function getBlocked(): ?bool
    {
        return $this->blocked;
    }

    /**
     * Setter for Blocked.
     *
     * @param bool $blocked Blocked
     */
    public function setBlocked(bool $blocked): void
    {
        $this->blocked = $blocked;
    }
}
