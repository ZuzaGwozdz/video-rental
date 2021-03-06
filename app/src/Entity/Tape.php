<?php
/**
 * Tape entity.
 */

namespace App\Entity;

use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Tape.
 *
 * @ORM\Entity(repositoryClass="App\Repository\TapeRepository")
 * @ORM\Table(name="tapes")
 *
 * @UniqueEntity(fields={"title"})
 */
class Tape
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
     * Title.
     *
     * @ORM\Column(
     *     type="string",
     *     length=255,
     *     )
     *
     * @Assert\Type(type="string")
     * @Assert\NotBlank
     * @Assert\Length(
     *     min="3",
     *     max="255",
     *     )
     */
    private $title;

    /**
     * Category.
     *
     * @var Category Category
     *
     * @ORM\ManyToOne(
     *     targetEntity="App\Entity\Category",
     *     inversedBy="tapes",
     *     fetch="EXTRA_LAZY",
     * )
     *
     * @ORM\JoinColumn(nullable=false)
     */
    private $category;

    /**
     * Tags.
     *
     * @var array
     *
     * @ORM\ManyToMany(
     *     targetEntity="App\Entity\Tag",
     *     inversedBy="tapes",
     *     orphanRemoval=true,
     *     fetch="EXTRA_LAZY",
     *     )
     *
     * @ORM\JoinTable(name="tapes_tags")
     */
    private $tags;

    /**
     * Availability.
     *
     * @var bool
     *
     * @ORM\Column(type="boolean")
     *
     * @Assert\Type(type="boolean")
     */
    private $availability;

    /**
     * @ORM\OneToMany(
     *      targetEntity=Rating::class,
     *      mappedBy="tape",
     *      fetch="EXTRA_LAZY",
     *      orphanRemoval=true
     *      )
     */
    private $ratings;

    /**
     * @ORM\OneToOne(targetEntity=Image::class, mappedBy="tape", cascade={"persist", "remove"})
     */
    private $image;

    /**
     * Tape constructor.
     */
    public function __construct()
    {
        $this->tags = new ArrayCollection();
        $this->ratings = new ArrayCollection();
    }

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
     * Getter for Rating.
     *
     * @return int|null Result
     */
    public function getRating(): ?int
    {
        $total = 0;

        $count = count($this->getRatings());

        foreach ($this->getRatings() as $rating) {
            $total = $total + $rating->getNote();
        }

        $this->rating = $total / $count;

        return $this->rating;
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
     * @param DateTimeInterface $createdAt Created at
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
     * Getter for Title.
     *
     * @return string|null Title
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * Setter for Title.
     *
     * @param string $title Title
     */
    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    /**
     * Getter for Category.
     *
     * @return Category|null Category
     */
    public function getCategory(): ?Category
    {
        return $this->category;
    }

    /**
     * Setter for Category.
     *
     * @param Category|null $category Category
     */
    public function setCategory(?Category $category): void
    {
        $this->category = $category;
    }

    /**
     * Getter for Tags.
     *
     * @return Collection|Tag[] Tags collection
     */
    public function getTags(): Collection
    {
        return $this->tags;
    }

    /**
     * Add Tag to collection.
     *
     * @param Tag $tag Tag entity
     */
    public function addTag(Tag $tag): void
    {
        if (!$this->tags->contains($tag)) {
            $this->tags[] = $tag;
        }
    }

    /**
     * Remove Tag from collection.
     *
     * @param Tag $tag Tag entity
     */
    public function removeTag(Tag $tag): void
    {
        if ($this->tags->contains($tag)) {
            $this->tags->removeElement($tag);
        }
    }

    /**
     * Getter for Availability.
     *
     * @return bool|null Availability
     */
    public function getAvailability(): ?bool
    {
        return $this->availability;
    }

    /**
     * Setter for Availability.
     *
     * @param bool $availability
     */
    public function setAvailability(bool $availability): void
    {
        $this->availability = $availability;
    }

    /**
     * @return Collection|Rating[]
     */
    public function getRatings(): Collection
    {
        return $this->ratings;
    }

    /**
     * @param Rating $rating
     */
    public function addRating(Rating $rating): void
    {
        if (!$this->ratings->contains($rating)) {
            $this->ratings[] = $rating;
            $rating->setTape($this);
        }
    }

    /**
     * @param Rating $rating
     */
    public function removeRating(Rating $rating): void
    {
        if ($this->ratings->contains($rating)) {
            $this->ratings->removeElement($rating);
            // set the owning side to null (unless already changed)
            if ($rating->getTape() === $this) {
                $rating->setTape(null);
            }
        }
    }

    /**
     * @return Image|null
     */
    public function getImage(): ?Image
    {
        return $this->image;
    }

    /**
     * @param Image|null $image
     */
    public function setImage(?Image $image): void
    {
        $this->image = $image;

        // set (or unset) the owning side of the relation if necessary
        $newTape = null === $image ? null : $this;
        if ($image->getTape() !== $newTape) {
            $image->setTape($newTape);
        }
    }
}
