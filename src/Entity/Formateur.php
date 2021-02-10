<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\FormateurRepository;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\MaxDepth;

/**
 * @ORM\Entity(repositoryClass=FormateurRepository::class)
 * @ApiResource(
 *      itemOperations={
 *              "GET_formateur"={
 *                 "method"="GET",
 *                 "path"="/formateurs/{id}",
 *                 "requirements"={"id"="\d+"},
 *                 "security"="is_granted('ROLE_Formateur')",
 *                 "security_message"="Vous n'avez pas access à cette Ressource"
 *                  },
 *              "PUT_formateur"={
 *                 "method"="PUT",
 *                 "path"="/formateurs/{id}",
 *                 "requirements"={"id"="\d+"},
 *                 "security"="is_granted('ROLE_Formateur')",
 *                 "security_message"="Vous n'avez pas access à cette Ressource"
 *                  },
 *      },
 *      normalizationContext = {
 *          "groups"={"formateur_read", "groupe_read", "promo_read"}
 *      }
 * )
 */
class Formateur extends User 
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * @ORM\ManyToMany(targetEntity=Groupe::class, mappedBy="formateurs")
     * @Groups({"formateur_read", "groupe_read", "promo_read"})
     */
    private $groupes;

    public function __construct()
    {
        $this->groupes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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
            $groupe->addFormateur($this);
        }

        return $this;
    }

    public function removeGroupe(Groupe $groupe): self
    {
        if ($this->groupes->removeElement($groupe)) {
            $groupe->removeFormateur($this);
        }

        return $this;
    }
}
