<?php

namespace App\Entity;

use App\Repository\JoueursRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=JoueursRepository::class)
 */
class Joueurs
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\ManyToOne(targetEntity=Partie::class)
     */
    private $partie;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $score;

    public function __construct()
    {
        $this->score = 0;
        $this->nbrjoueurs = 0;


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

    public function getPartie(): ?partie
    {
        return $this->partie;
    }

    public function setPartie(?partie $partie): self
    {
        $this->partie = $partie;

        return $this;
    }

    public function getScore(): ?int
    {
        return $this->score;
    }

    public function setScore(?int $score): self
    {
        $this->score = $score;

        return $this;
    }
}
