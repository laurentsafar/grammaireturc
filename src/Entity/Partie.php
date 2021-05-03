<?php

namespace App\Entity;

use App\Repository\PartieRepository;
use Symfony\Component\Validator\Constraints as Assert;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PartieRepository::class)
 */
class Partie
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $nbrjoueurs;

    /**
     * @ORM\Column(type="date")
     */
    protected $date;

    /**
     * @ORM\Column(type="boolean")
     */
    protected $passe;

     /**
     * @ORM\Column(type="boolean")
     */
    protected $present;

     /**
     * @ORM\Column(type="boolean")
     */
    protected $futur;

     /**
     * @ORM\Column(type="boolean")
     */
    protected $je;

     /**
     * @ORM\Column(type="boolean")
     */
    protected $tu;

     /**
     * @ORM\Column(type="boolean")
     */
    protected $il;

     /**
     * @ORM\Column(type="boolean")
     */
    protected $nous;

     /**
     * @ORM\Column(type="boolean")
     */
    protected $vous;

     /**
     * @ORM\Column(type="boolean")
     */
    protected $ils;

     /**
     * @ORM\Column(type="boolean")
     */
    protected $affirmation;

     /**
     * @ORM\Column(type="boolean")
     */
    protected $question;

     /**
     * @ORM\Column(type="boolean")
     */
    protected $negation;

     /**
     * @ORM\Column(type="integer")
     */
    protected $tour;

    /**
     * @ORM\Column(type="integer")
     */
    protected $cycletour;

    /**
     * @ORM\Column(type="array",nullable=true)
     */
    protected $ordre;

    /**
     * @ORM\Column(type="integer",nullable=true)
     */
    protected $lastmot;

    /**
     * @ORM\Column(type="integer",nullable=true)
     */
    protected $lasttemps;

    /**
     * @ORM\Column(type="integer",nullable=true)
     */
    protected $lasttype;

    /**
     * @ORM\Column(type="integer",nullable=true)
     */
    protected $lastpersonne;


    public function __construct()
    {
        $this->date = new \DateTime();
        $this->nbrjoueurs = 0;
        $this->tour =0;
        $this->passe=true;
        $this->present=true;
        $this->futur=true;
        $this->je=true;
        $this->tu=true;
        $this->il=true;
        $this->nous=true;
        $this->vous=true;
        $this->ils=true;
        $this->affirmation=true;
        $this->question=true;
        $this->negation=true;
        $this->cycletour=0;
        $this->lastmot=null;
        $this->lastpersonne=null;
        $this->lasttype=null;
        $this->lasttemps=null;

    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNbrjoueurs(): ?int
    {
        return $this->nbrjoueurs;
    }

    public function setNbrjoueurs(int $nbrjoueurs): self
    {
        $this->nbrjoueurs = $nbrjoueurs;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getPasse()
    {
        return $this->passe;
    }

    public function setPasse($passe): self
    {
        $this->passe = $passe;

        return $this;
    }

    public function getPresent()
    {
        return $this->present;
    }

    public function setPresent($present): self
    {
        $this->present = $present;

        return $this;
    }

    public function getFutur()
    {
        return $this->futur;
    }

    public function setFutur($futur): self
    {
        $this->futur = $futur;

        return $this;
    }

    public function getJe()
    {
        return $this->je;
    }

    public function setJe($je): self
    {
        $this->je = $je;

        return $this;
    }

    public function getTu()
    {
        return $this->tu;
    }

    public function setTu($tu): self
    {
        $this->tu = $tu;

        return $this;
    }

    public function getIl()
    {
        return $this->il;
    }

    public function setIl($il): self
    {
        $this->il = $il;

        return $this;
    }

    public function getNous()
    {
        return $this->nous;
    }

    public function setNous($nous): self
    {
        $this->nous = $nous;

        return $this;
    }

    public function getVous()
    {
        return $this->vous;
    }

    public function setVous($vous): self
    {
        $this->vous = $vous;

        return $this;
    }

    public function getIls()
    {
        return $this->ils;
    }

    public function setIls($ils): self
    {
        $this->ils = $ils;

        return $this;
    }

    public function getAffirmation()
    {
        return $this->affirmation;
    }

    public function setAffirmation($affirmation): self
    {
        $this->affirmation = $affirmation;

        return $this;
    }

    public function getQuestion()
    {
        return $this->question;
    }

    public function setQuestion($question): self
    {
        $this->question = $question;

        return $this;
    }

    public function getNegation()
    {
        return $this->negation;
    }

    public function setNegation($negation): self
    {
        $this->negation = $negation;

        return $this;
    }

    public function getTour()
    {
        return $this->tour;
    }

    public function setTour($tour): self
    {
        $this->tour = $tour;

        return $this;
    }

    public function getOrdre(): ?array
    {
        return $this->ordre;
    }

    public function setOrdre(array $ordre): self
    {
        $this->ordre = $ordre;

        return $this;
    }

    public function getCycletour(): ?int
    {
        return $this->cycletour;
    }

    public function setCycletour(int $cycletour): self
    {
        $this->cycletour = $cycletour;

        return $this;
    }

    public function getLastmot(): ?int
    {
        return $this->lastmot;
    }

    public function setLastmot(int $lastmot): self
    {
        $this->lastmot = $lastmot;

        return $this;
    }

    public function getLasttemps(): ?int
    {
        return $this->lasttemps;
    }

    public function setLasttemps(int $lasttemps): self
    {
        $this->lasttemps = $lasttemps;

        return $this;
    }

    public function getLasttype(): ?int
    {
        return $this->lasttype;
    }

    public function setLasttype(int $lasttype): self
    {
        $this->lasttype = $lasttype;

        return $this;
    }

    public function getLastpersonne(): ?int
    {
        return $this->lastpersonne;
    }

    public function setLastpersonne(int $lastpersonne): self
    {
        $this->lastpersonne = $lastpersonne;

        return $this;
    }




   



    

    
}
