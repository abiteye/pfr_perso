<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\ReferentielRepository;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\MaxDepth;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=ReferentielRepository::class)
 * @ApiResource(
 *          routePrefix="/admin",
 *      collectionOperations={
 *          "get","post",
 *          "GET_gpcr"={
 *              "method"="GET",
 *                 "defaults"={"id"=null},
 *                 "path"="/referentiels/groupe_competences",
 *                 "normalization_context" = {
 *                      "groups"={"referentiel_read"},"enable_max_depth"=true
 *                  }
 *          }
 *      },
 *      itemOperations={
 *          "get"={
 *              "normalization_context" = {
 *                      "groups"={"referentiel_read"},"enable_max_depth"=true
 *               }
 *           }, 
 *          "put"={
 *               "normalization_context"= {
 *                      "groups"={"referentiel_read", "promo_read"},"enable_max_depth"=true
 *                  
 *               }
 *          },
 *          
 *          "GET_gpcr"={
 *              "method"="GET",
 *                 "defaults"={"id"=null},
 *                 "path"="/referentiels/{id}/groupe_competences/{id_g}",
 *                 "normalization_context" = {
 *                      "groups"={"referentiel_read", "enable_max_depth"=true}
 *                  }
 *          }
 * 
 *      },
 *      attributes = {
 *          "security" = "is_granted('ROLE_Admin')",
 *          "security_message"="Vous n'avez les autorisations requises"
 *     },
 *      normalizationContext = {
 *          "groups"={"referentiel_read"}
 *      }
 * )
 */
class Referentiel
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100)
     * @Assert\NotBlank(message="Ajoutez un libellé please")
     * @Groups({"referentiel_read", "promo_read"})
     */
    private $libelle;

    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank(message="La présentation est obligatoire")
     * @Groups({"referentiel_read", "promo_read"})
     */
    private $presentation;

    /**
     * @ORM\Column(type="text")
     * @Groups({"referentiel_read", "promo_read"})
     */
    private $competencesVisees;

    /**
     * @ORM\Column(type="text")
     */
    private $programme;

    /**
     * @ORM\Column(type="text")
     */
    private $critereEvaluation;

    /**
     * @ORM\Column(type="text")
     */
    private $critereAdmission;

    /**
     * @ORM\ManyToMany(targetEntity=Promotion::class, mappedBy="referentiels", cascade={"persist"})
     * @MaxDepth(4)
     */
    private $promotions;

    /**
     * @ORM\ManyToMany(targetEntity=GroupeCompetence::class, inversedBy="referentiels")
     */
    private $groupeCompetences;

    public function __construct()
    {
        $this->competences = new ArrayCollection();
        $this->promotions = new ArrayCollection();
        $this->groupeCompetences = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(string $libelle): self
    {
        $this->libelle = $libelle;

        return $this;
    }

    public function getPresentation(): ?string
    {
        return $this->presentation;
    }

    public function setPresentation(string $presentation): self
    {
        $this->presentation = $presentation;

        return $this;
    }

    public function getCompetencesVisees(): ?string
    {
        return $this->competencesVisees;
    }

    public function setCompetencesVisees(string $competencesVisees): self
    {
        $this->competencesVisees = $competencesVisees;

        return $this;
    }

    public function getProgramme(): ?string
    {
        return $this->programme;
    }

    public function setProgramme(string $programme): self
    {
        $this->programme = $programme;

        return $this;
    }

    public function getCritereEvaluation(): ?string
    {
        return $this->critereEvaluation;
    }

    public function setCritereEvaluation(string $critereEvaluation): self
    {
        $this->critereEvaluation = $critereEvaluation;

        return $this;
    }

    public function getCritereAdmission(): ?string
    {
        return $this->critereAdmission;
    }

    public function setCritereAdmission(string $critereAdmission): self
    {
        $this->critereAdmission = $critereAdmission;

        return $this;
    }

    /**
     * @return Collection|Promotion[]
     */
    public function getPromotions(): Collection
    {
        return $this->promotions;
    }

    public function addPromotion(Promotion $promotion): self
    {
        if (!$this->promotions->contains($promotion)) {
            $this->promotions[] = $promotion;
            $promotion->addReferentiel($this);
        }

        return $this;
    }

    public function removePromotion(Promotion $promotion): self
    {
        if ($this->promotions->removeElement($promotion)) {
            $promotion->removeReferentiel($this);
        }

        return $this;
    }

    /**
     * @return Collection|GroupeCompetence[]
     */
    public function getGroupeCompetences(): Collection
    {
        return $this->groupeCompetences;
    }

    public function addGroupeCompetence(GroupeCompetence $groupeCompetence): self
    {
        if (!$this->groupeCompetences->contains($groupeCompetence)) {
            $this->groupeCompetences[] = $groupeCompetence;
        }

        return $this;
    }

    public function removeGroupeCompetence(GroupeCompetence $groupeCompetence): self
    {
        $this->groupeCompetences->removeElement($groupeCompetence);

        return $this;
    }
}
