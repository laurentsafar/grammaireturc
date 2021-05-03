<?php

namespace App\Entity;

use App\Repository\MotsRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=MotsRepository::class)
 */
class Mots
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
    private $turc;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $francais;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $adjectif;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTurc(): ?string
    {
        return $this->turc;
    }

    public function setTurc(string $turc): self
    {
        $this->turc = $turc;

        return $this;
    }

    public function getFrancais(): ?string
    {
        return $this->francais;
    }

    public function setFrancais(string $francais): self
    {
        $this->francais = $francais;

        return $this;
    }

    public function getAdjectif(): ?bool
    {
        return $this->adjectif;
    }

    public function setAdjectif(?bool $adjectif): self
    {
        $this->adjectif = $adjectif;

        return $this;
    }
}
