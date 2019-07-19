<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\AuthorRepository")
 */
class Author
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $lastname;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $firstname;

    /**
     * @ORM\Column(type="date")
     */
    private $birthdate;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $deathdate;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Book", mappedBy="author")
     */
    private $books;

    // le constructeur est la méthode appelée automatiquement
    public function __construct(){
        // je déclare ma proporiété books en tant qu'array
        // car elle peut contenir plusieurs livres
        // ArrayCollection est une méthode symfony qui crée un "super" array
        $this->books = new ArrayCollection();
    }
    public function getBooks()
    {
        return $this->books;
    }

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $bio;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $name): self
    {
        $this->lastname = $name;

        return $this;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getBirthdate(): ?\DateTimeInterface
    {
        return $this->birthdate;
    }

    public function setBirthdate(\DateTimeInterface $birthdate): self
    {
        $this->birthdate = $birthdate;

        return $this;
    }

    public function getDeathdate(): ?\DateTimeInterface
    {
        return $this->deathdate;
    }

    public function setDeathdate(?\DateTimeInterface $deathdate): self
    {
        $this->deathdate = $deathdate;

        return $this;
    }

    public function getBio(): ?string
    {
        return $this->bio;
    }

    public function setBio(?string $bio): self
    {
        $this->bio = $bio;

        return $this;
    }

    /**
     * Generates the magic method
     *
     */
   /* public function __toString(){
        // to show the name of the Author in the select
        return $this->lastname;
    }*/
}
