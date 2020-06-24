<?php
/**
 * Reservation entity.
 */

namespace App\Entity;

use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Reservation.
 *
 * @ORM\Table(name="reservations")
 * @ORM\Entity(repositoryClass="App\Repository\ReservationRepository")
 */
class Reservation
{
    /**
     * Primary key.
     *
     * @var int
     *
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * Comment.
     *
     * @var string
     *
     * @ORM\Column(
     *     type="text",
     *     nullable=true,
     *     length=200,
     *     )
     */
    private $comment;

    /**
     * Created at.
     *
     * @var DateTimeInterface
     *
     * @ORM\Column(type="datetime")
     *
     * @Assert\DateTime
     *
     * @Gedmo\Timestampable(on="create")
     */
    private $createdAt;

    /**
     * Author.
     *
     * @var User
     *
     * @ORM\ManyToOne(
     *     targetEntity="App\Entity\User",
     *     fetch="EXTRA_LAZY",
     *     )
     * @ORM\JoinColumn(nullable=true)
     */
    private $author;

    /**
     * Tape.
     *
     * @var Tape
     *
     * @ORM\ManyToOne(
     *     targetEntity="App\Entity\Tape",
     *     fetch="EXTRA_LAZY",
     *     )
     * @ORM\JoinColumn(nullable=true)
     */
    private $tape;

    /**
     * @ORM\Column(type="boolean", options={"default" : 0})
     */
    private $status;

    /**
     * Getter for Id.
     *
     * @return int|null Result
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Getter for Email.
     * 
     * @return string|null
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * Setter for Email.
     *
     * @param string $email
     */
    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    /**
     * Getter for Comment.
     *
     * @return string|null
     */
    public function getComment(): ?string
    {
        return $this->comment;
    }

    /**
     * Setter for Comment.
     *
     * @param string|null $comment
     */
    public function setComment(?string $comment): void
    {
        $this->comment = $comment;
    }

    /**
     * Getter for Created At.
     *
     * @return DateTimeInterface|null Created at
     */
    public function getCreatedAt(): ?DateTimeInterface
    {
        return $this->createdAt;
    }

    /**
     * Setter for Created at.
     *
     * @param DateTimeInterface $createdAt
     */
    public function setCreatedAt(DateTimeInterface $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    public function getAuthor(): ?User
    {
        return $this->author;
    }

    public function setAuthor(?User $author): self
    {
        $this->author = $author;

        return $this;
    }

    public function getTape(): ?Tape
    {
        return $this->tape;
    }

    public function setTape(?Tape $tape): void
    {
        $this->tape = $tape;
    }

    public function getStatus(): ?bool
    {
        return $this->status;
    }

    public function setStatus(bool $status): void
    {
        $this->status = $status;
    }
}
