<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\ApprenantRepository;
use ApiPlatform\Core\Annotation\ApiResource;

/**
 * @ORM\Entity(repositoryClass=ApprenantRepository::class)
 * @ApiResource(
 *      collectionOperations={
 *          "LISTER_apprenants"={
 *                        "method"="GET",
 *                         "path"="/apprenants",
 *                         "security"="is_granted('ROLE_Formateur')",
 *                         "security_message"="Vous n'avez pas access à cette Ressource"
 *                   },
 *          "addApprenant"={
 *                        "method"="POST",
 *                        "path"="/apprenants",
 *                        "controller"=App\Controller\AddUserController::class,
 *                        "security_post_denormalize"="is_granted('ROLE_Formateur')",
 *                        "security_post_denormalize_message"="Vous n'avez pas access à cette Ressource",
 *           },
 *      },
 *      itemOperations={
 *          "GET_apprenant"={
 *                 "method"="GET",
 *                 "path"="/apprenants/{id}",
 *                 "requirements"={"id"="\d+"},
 *                 "security"="is_granted('ROLE_Formateur') or is_granted('ROLE_Apprenant') or is_granted('ROLE_Cm')",
 *                 "security_message"="Vous n'avez pas access à cette Ressource"
 *           },
 *          "PUT_apprenant"={
 *                 "method"="PUT",
 *                 "path"="/apprenants/{id}",
 *                 "requirements"={"id"="\d+"},
 *                 "security"="is_granted('ROLE_Formateur')",
 *                 "security_message"="Vous n'avez pas access à cette Ressource"
 *           },
 *    }
 * )
 */
class Apprenant extends User
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $adresse;

    /**
     * @ORM\Column(type="string", length=20)
     */
    private $statut;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $infoComplementaire;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $categorie;

    /**
     * @ORM\ManyToOne(targetEntity=ProfilDeSortie::class, inversedBy="apprenants")
     */
    private $profilDeSortie;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(string $adresse): self
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function getStatut(): ?string
    {
        return $this->statut;
    }

    public function setStatut(string $statut): self
    {
        $this->statut = $statut;

        return $this;
    }

    public function getInfoComplementaire(): ?string
    {
        return $this->infoComplementaire;
    }

    public function setInfoComplementaire(string $infoComplementaire): self
    {
        $this->infoComplementaire = $infoComplementaire;

        return $this;
    }

    public function getCategorie(): ?string
    {
        return $this->categorie;
    }

    public function setCategorie(string $categorie): self
    {
        $this->categorie = $categorie;

        return $this;
    }

    public function getProfilDeSortie(): ?ProfilDeSortie
    {
        return $this->profilDeSortie;
    }

    public function setProfilDeSortie(?ProfilDeSortie $profilDeSortie): self
    {
        $this->profilDeSortie = $profilDeSortie;

        return $this;
    }
}
