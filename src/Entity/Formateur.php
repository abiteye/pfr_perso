<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\FormateurRepository;
use ApiPlatform\Core\Annotation\ApiResource;

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

    public function getId(): ?int
    {
        return $this->id;
    }
}
