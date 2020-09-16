<?php
/**
 * Rating entity.
 */

namespace App\Entity;

use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Rating.
 *
 * @ORM\Entity(repositoryClass="App\Repository\RatingRepository")
 * @ORM\Table(name="ratings")
 */
class Rating
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
     * Note.
     *
     * @var int
     *
     * @ORM\Column(type="integer")
     *
     *  @Assert\Type(type="integer")
     */
    private $note;

    /**
     * Author.
     *
     * @var User
     *
     * @ORM\ManyToOne(
     *      targetEntity=User::class,
     *      fetch="EXTRA_LAZY",
     *      )
     * @ORM\JoinColumn(nullable=false)
     */
    private $author;

    /**
     * Tape.
     *
     * @var Tape
     *
     * @ORM\ManyToOne(
     *      targetEntity=Tape::class,
     *      fetch="EXTRA_LAZY",
     *      )
     * @ORM\JoinColumn(nullable=false)
     */
    private $tape;

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
     * Updated at.
     *
     * @var DateTimeInterface
     *
     * @ORM\Column(type="datetime")
     *
     * @Assert\DateTime
     *
     * @Gedmo\Timestampable(on="update")
     */
    private $updatedAt;

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
     * Getter for Note.
     *
     * @return int|null Note
     */
    public function getNote(): ?int
    {
        return $this->note;
    }

    /**
     * Setter for Note.
     *
     * @param Note $note Note
     */
    public function setNote(int $note): void
    {
        $this->note = $note;
    }

    /**
     * Getter for Tape.
     *
     * @return Tape|null Tapes
     */
    public function getTape(): ?Tape
    {
        return $this->tape;
    }

    /**
     * Setter for Tape.
     *
     * @param Tape $tape Tape
     */
    public function setTape(?Tape $tape): void
    {
        $this->tape = $tape;
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
     */
    public function setCreatedAt(DateTimeInterface $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    /**
     * Getter for Updated at.
     *
     * @return DateTimeInterface|null Updated at
     */
    public function getUpdatedAt(): ?DateTimeInterface
    {
        return $this->updatedAt;
    }

    /**
     * Setter for Updated at.
     *
     * @param DateTimeInterface $updatedAt Updated at
     */
    public function setUpdatedAt(DateTimeInterface $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }

    /**
     * Getter for Author.
     *
     * @return User|null User
     */
    public function getAuthor(): ?User
    {
        return $this->author;
    }

    /**
     * Setter for Author.
     *
     * @param User $author User
     */
    public function setAuthor(?User $author): void
    {
        $this->author = $author;
    }
}
