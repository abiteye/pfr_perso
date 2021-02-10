<?php

namespace App\Entity;

use App\Entity\Groupe;
use App\Entity\Referentiel;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\PromotionRepository;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\MaxDepth;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=PromotionRepository::class)
 * @ApiResource(
 *          routePrefix="/admin",
 *      attributes = {
 *          "security" = "is_granted('ROLE_Admin')",
 *          "security_message"="Vous n'avez les autorisations requises"
 *     },
 *      itemOperations={
 *          "get","put"
 * },
 *      normalizationContext = {
 *          "groups"={"promo_read"}
 *      }
 * )
 */
class Promotion
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100)
     * @Assert\NotBlank(message="ce champ est obligatoire")
     * @Groups({"promo_read"})
     */
    private $langue;

    /**
     * @ORM\Column(type="string", length=100)
     * @Assert\NotBlank(message="ce champ est obligatoire")
     * @Groups({"promo_read"})
     */
    private $titre;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $lieuPromo;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="ce champ est obligatoire")
     */
    private $referenceAgate;

    /**
     * @ORM\Column(type="string", length=100)
     * @Assert\NotBlank(message="ce champ est obligatoire")
     * @Groups({"promo_read"})
     */
    private $fabrique;

    /**
     * @ORM\Column(type="date")
     * @Assert\NotBlank(message="ce champ est obligatoire")
     */
    private $dateDebut;

    /**
     * @ORM\Column(type="date")
     * @Assert\NotBlank(message="ce champ est obligatoire")
     */
    private $dateFin;

    /**
     * @ORM\ManyToMany(targetEntity=Referentiel::class, inversedBy="promotions", cascade={"persist"})
     * @Assert\NotBlank(message="ce champ est obligatoire")
     * @Groups({"promo_read"})
     */
    private $referentiels;

    /**
     * @ORM\OneToMany(targetEntity=Groupe::class, mappedBy="promotion")
     * @Groups({"promo_read"})
     * 
     */
    private $groupes;

    public function __construct()
    {
        $this->referentiels = new ArrayCollection();
        $this->groupes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLangue(): ?string
    {
        return $this->langue;
    }

    public function setLangue(string $langue): self
    {
        $this->langue = $langue;

        return $this;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): self
    {
        $this->titre = $titre;

        return $this;
    }

    public function getLieuPromo(): ?string
    {
        return $this->lieuPromo;
    }

    public function setLieuPromo(string $lieuPromo): self
    {
        $this->lieuPromo = $lieuPromo;

        return $this;
    }

    public function getReferenceAgate(): ?string
    {
        return $this->referenceAgate;
    }

    public function setReferenceAgate(string $referenceAgate): self
    {
        $this->referenceAgate = $referenceAgate;

        return $this;
    }

    public function getFabrique(): ?string
    {
        return $this->fabrique;
    }

    public function setFabrique(string $fabrique): self
    {
        $this->fabrique = $fabrique;

        return $this;
    }

    public function getDateDebut(): ?\DateTimeInterface
    {
        return $this->dateDebut;
    }

    public function setDateDebut(\DateTimeInterface $dateDebut): self
    {
        $this->dateDebut = $dateDebut;

        return $this;
    }

    public function getDateFin(): ?\DateTimeInterface
    {
        return $this->dateFin;
    }

    public function setDateFin(\DateTimeInterface $dateFin): self
    {
        $this->dateFin = $dateFin;

        return $this;
    }

    /**
     * @return Collection|Referentiel[]
     */
    public function getReferentiels(): Collection
    {
        return $this->referentiels;
    }

    public function addReferentiel(Referentiel $referentiel): self
    {
        if (!$this->referentiels->contains($referentiel)) {
            $this->referentiels[] = $referentiel;
        }

        return $this;
    }

    public function removeReferentiel(Referentiel $referentiel): self
    {
        $this->referentiels->removeElement($referentiel);

        return $this;
    }

    /**
     * @return Collection|Groupe[]
     */
    public function getGroupes(): Collection
    {
        return $this->groupes;
    }

    public function addGroupe(Groupe $groupe): self
    {
        if (!$this->groupes->contains($groupe)) {
            $this->groupes[] = $groupe;
            $groupe->setPromotion($this);
        }

        return $this;
    }

    public function removeGroupe(Groupe $groupe): self
    {
        if ($this->groupes->removeElement($groupe)) {
            // set the owning side to null (unless already changed)
            if ($groupe->getPromotion() === $this) {
                $groupe->setPromotion(null);
            }
        }

        return $this;
    }
}
