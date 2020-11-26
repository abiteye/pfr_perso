<?php

namespace App\Entity;

use App\Entity\Apprenant;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\ProfilDeSortieRepository;
use ApiPlatform\Core\Annotation\ApiSubresource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=ProfilDeSortieRepository::class)
 * @ApiResource(
 *          routePrefix="/admin",
 *      attributes = {
 *          "security" = "is_granted('ROLE_Admin')",
 *          "security_message"="Vous n'avez les autorisations requises"
 *     },
 *      normalizationContext = {
 *          "groups"={"profil_de_sorties_read"}
 *      }
 * )
 */
class ProfilDeSortie
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100)
     * @Assert\NotBlank(message="Veuillez renseigner le libellé")
     * @Groups({"profil_de_sorties_read"})
     */
    private $libelle;

    /**
     * @ORM\Column(type="boolean")
     * @Assert\NotBlank(message="Archiver le profil de sortie please")
     * @Groups({"profil_de_sorties_read"})
     */
    private $archivage;

    /**
     * @ORM\OneToMany(targetEntity=Apprenant::class, mappedBy="profilDeSortie")
     * @ApiSubresource()
     */
    private $apprenants;

    public function __construct()
    {
        $this->apprenants = new ArrayCollection();
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
     * @return Collection|Apprenant[]
     */
    public function getApprenants(): Collection
    {
        return $this->apprenants;
    }

    public function addApprenant(Apprenant $apprenant): self
    {
        if (!$this->apprenants->contains($apprenant)) {
            $this->apprenants[] = $apprenant;
            $apprenant->setProfilDeSortie($this);
        }

        return $this;
    }

    public function removeApprenant(Apprenant $apprenant): self
    {
        if ($this->apprenants->removeElement($apprenant)) {
            // set the owning side to null (unless already changed)
            if ($apprenant->getProfilDeSortie() === $this) {
                $apprenant->setProfilDeSortie(null);
            }
        }

        return $this;
    }
}
