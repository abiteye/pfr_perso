<?php

namespace App\Entity;


use Doctrine\ORM\Mapping as ORM;
use App\Repository\ProfilRepository;
use ApiPlatform\Core\Annotation\ApiFilter;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\NumericFilter;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass=ProfilRepository::class)
 * @UniqueEntity("libelle",message="Ce libellé existe déjà")
 * @ApiFilter(NumericFilter::class, properties={"archivage"})
 * @ApiResource(
 *      routePrefix="/admin",
 *   itemOperations = {"GET","PUT","DELETE":{
 *      "path"="/profils/{id}",
 *      "swagger_context"={
 *          "summary"="Permet de faire la suppression d'un profil",
 *          "description"="En réalité cela gère l'archivage"
 *       }
 *     }
 *    },
 *   collectionOperations={
 *       "Lister_profils"={
 *              "method"="GET",
 *              "path"="/profils"       
 *        },
 *     
 *       "Ajouter_profil"={
 *              "method"="POST",
 *              "path"="/profils"
 *        }
 *      
 *   },
 *   normalizationContext={
 *      "groups"=
 *          {"profil_read"}
 *   }
 *   
 * )
 */
class Profil
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"profil_read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=30)
     * @Assert\NotBlank(message="Veuillez renseigner le libellé du profil")
     * @Assert\Length(
     *      min=2,
     *      max=30,
     *      minMessage="Le Libellé doit être supérieur ou égal à 2 caractères",
     *      maxMessage="Le libellé doit être inférieur ou égal à 30 caractères"
     *)
     * @Groups({"profil_read"})
     */
    private $libelle;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank(message="L'archivage n'a pas été défini")
     * @Assert\Regex(
     *      pattern = "/^(1|0)$/",
     *      message = "L'archivage doit être soit 1 ou 0"
     *)
     * @Groups({"profil_read"})
     */
    private $archivage;

    /**
     * @ORM\OneToMany(targetEntity=User::class, mappedBy="profil")
     * @Groups({"profil_read"})
     * @ApiSubresource()
     */
    private $user;

    public function __construct()
    {
        $this->user = new ArrayCollection();
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

    public function getArchivage(): ?int
    {
        return $this->archivage;
    }

    public function setArchivage(int $archivage): self
    {
        $this->archivage = $archivage;

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getUser(): Collection
    {
        return $this->user;
    }

    public function addUser(User $user): self
    {
        if (!$this->user->contains($user)) {
            $this->user[] = $user;
            $user->setProfil($this);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->user->removeElement($user)) {
            // set the owning side to null (unless already changed)
            if ($user->getProfil() === $this) {
                $user->setProfil(null);
            }
        }

        return $this;
    }
}
