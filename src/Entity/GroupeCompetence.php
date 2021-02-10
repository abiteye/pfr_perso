<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\GroupeCompetenceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\MaxDepth;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=GroupeCompetenceRepository::class)
 * @ApiResource(
 *          routePrefix="/admin",
 *      itemOperations={
 *          "get", "put",
 *          "GET_Comp_grComp"={
 *                 "method"="GET",
 *                 "defaults"={"id"=null},
 *                 "path"="/groupe_competences/{id}/competences",
 *                 "requirements"={"id"="\d+"},
 *          },
 *      },
 *      collectionOperations={
 *           
 *           "GET_Comp_tous_grComp"={
 *                 "method"="GET",
 *                 "defaults"={"id"=null},
 *                 "path"="/groupe_competences/competences",
 *                 "normalization_context" = {
 *                      "groups"={"groupeComp_read"}
 *                  }
 *          },
 *          "post"={
 *                 "method"="post",
 *                 "path"="/groupe_competences/competences"
 *          },
 *     },
 *      attributes = {
 *          "security" = "is_granted('ROLE_Admin')",
 *          "security_message"="Vous n'avez les autorisations requises"
 *     },
 *      normalizationContext = {
 *          "groups"={"groupe_competence_read"}
 *      },
 *       denormalizationContext = {
 *                      "groups"={"groupeComp_write"}
 *                  }
 * )
 */
class GroupeCompetence
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100)
     * @Assert\NotBlank(message="Ce champ est obligatoire")
     * @Groups({"groupe_competence_read", "groupeComp_read", "groupeComp_write", "tag_read", "referentiel_read"})
     */
    private $libelle;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Ajoutez un descriptif please")
     * @Groups({"groupe_competence_read", "groupeComp_read", "groupeComp_write", "referentiel_read"})
     */
    private $descriptif;

    /**
     * @ORM\Column(type="boolean")
     * @Groups({"groupe_competence_read"})
     */
    private $archivage;

    /**
     * @ORM\ManyToMany(targetEntity=Competence::class, inversedBy="groupeCompetences", cascade={"persist"})
     * @Assert\NotBlank(message="Il faut ajouter impérativement une compétence")
     * @Groups({"groupe_competence_read", "groupeComp_read", "groupeComp_write", "referentiel_read"})
     * @MaxDepth(4)
     */
    private $competences;

    /**
     * @ORM\ManyToMany(targetEntity=Tag::class, mappedBy="groupeCompetences")
     */
    private $tags;

    /**
     * @ORM\ManyToMany(targetEntity=Referentiel::class, mappedBy="groupeCompetences")
     */
    private $referentiels;

    public function __construct()
    {
        $this->competences = new ArrayCollection();
        $this->setArchivage(false);
        $this->tags = new ArrayCollection();
        $this->referentiels = new ArrayCollection();
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

    public function getDescriptif(): ?string
    {
        return $this->descriptif;
    }

    public function setDescriptif(string $descriptif): self
    {
        $this->descriptif = $descriptif;

        return $this;
    }

    public function getArchivage(): ?bool
    {
        return $this->archivage;
    }

    public function setArchivage(bool $archivage): self
    {
        $this->archivage = $archivage;

        return $this;
    }

    /**
     * @return Collection|Competence[]
     */
    public function getCompetences(): Collection
    {
        return $this->competences;
    }

    public function addCompetence(Competence $competence): self
    {
        if (!$this->competences->contains($competence)) {
            $this->competences[] = $competence;
        }

        return $this;
    }

    public function removeCompetence(Competence $competence): self
    {
        $this->competences->removeElement($competence);

        return $this;
    }

    /**
     * @return Collection|Tag[]
     */
    public function getTags(): Collection
    {
        return $this->tags;
    }

    public function addTag(Tag $tag): self
    {
        if (!$this->tags->contains($tag)) {
            $this->tags[] = $tag;
            $tag->addGroupeCompetence($this);
        }

        return $this;
    }

    public function removeTag(Tag $tag): self
    {
        if ($this->tags->removeElement($tag)) {
            $tag->removeGroupeCompetence($this);
        }

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
            $referentiel->addGroupeCompetence($this);
        }

        return $this;
    }

    public function removeReferentiel(Referentiel $referentiel): self
    {
        if ($this->referentiels->removeElement($referentiel)) {
            $referentiel->removeGroupeCompetence($this);
        }

        return $this;
    }

    
}
