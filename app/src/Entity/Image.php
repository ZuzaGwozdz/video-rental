<?php
/**
 * Image.
 */

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Image.
 *
 * @ORM\Entity(repositoryClass="App\Repository\ImageRepository")
 * @ORM\Table(
 *     name="images",
 *     uniqueConstraints={
 *          @ORM\UniqueConstraint(
 *              name="UQ_filename_1",
 *              columns={"filename"},
 *          ),
 *     },
 * )
 *
 * @UniqueEntity(
 *      fields={"filename"},
 * )
 */
class Image
{
    /**
     * Id.
     *
     * @var int
     *
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * Tape.
     *
     * @var \App\Entity\Tape
     *
     * @ORM\OneToOne(
     *     targetEntity="App\Entity\Tape",
     *     inversedBy="image",
     * )
     * @ORM\JoinColumn(nullable=false)
     *
     * @Assert\Type(type="App\Entity\Tape")
     */
    private $tape;

    /**
     * Filename.
     *
     * @var string
     *
     * @ORM\Column(
     *     type="string",
     *     length=191,
     * )
     *
     * @Assert\Type(type="string")
     */
    private $filename;

    /**
     * Getter for id.
     *
     * @return int|null Id
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Getter for tape.
     *
     * @return \App\Entity\Tape|null Tape
     */
    public function getTape(): ?Tape
    {
        return $this->tape;
    }

    /**
     * Setter for tape.
     *
     * @param \App\Entity\Tape $tape Tape
     */
    public function setTape(Tape $tape): void
    {
        $this->tape = $tape;
    }

    /**
     * Getter for filename.
     *
     * @return string Filename
     */
    public function getFilename()
    {
        return $this->filename;
    }

    /**
     * Setter for filename.
     *
     * @param string $filename Filename
     */
    public function setFilename(string $filename): void
    {
        $this->filename = $filename;
    }
}
