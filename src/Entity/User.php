<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\UserRepository;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @ORM\InheritanceType("JOINED")
 * @ORM\DiscriminatorColumn(name="profil", type="string")
 * @ORM\DiscriminatorMap({"admin" = "User", "apprenant" = "Apprenant", "formateur" = "Formateur", "cm" = "CM"})
 * @UniqueEntity("email",message="Cet email existe déja")
 * @UniqueEntity("username",message="Ce username existe déjà")
 * @UniqueEntity("telephone",message="Ce numéro de téléphone existe déjà")
 * @ApiResource(
 *      attributes={
 *             "pagination_enabled"=true,
 *              "pagination_items_per_page"=2,
 *              },
 *   itemOperations={
 *           "GET_user"={
 *                 "method"="GET",
 *                 "path"="/admin/users/{id}",
 *                  "requirements"={"id"="\d+"},
 *                  "security"="is_granted('ROLE_Administrateur')",
 *                  "security_message"="Vous n'avez pas access à cette Ressource"
 *                  },
 *             "ARCHIVER_user"={
 *                      "method"="PUT",
 *                      "path"="/admin/users/{id}Archivage",
 *                      "controller"="App\Controller\API\ArchivageAdminController",
 *                      "security"="is_granted('ROLE_Administrateur')",
 *                      "security_message"="Vous n'avez pas access à cette Ressource"
 *                    },
 *               "EDITER_user"={
 *                       "method"="PUT",
 *                       "path"="/admin/users/{id}",
 *                        "requirements"={"id"="\d+"},
 *                        "security"="is_granted('ROLE_Administrateur')",
 *                        "security_message"="Vous n'avez pas access à cette Ressource"
 *                       },
 *                    },
 *         collectionOperations={
 *                 "AJOUTER_user"={
 *                        "method"="POST",
 *                        "path"="/admin/users",
 *                        "security_post_denormalize"="is_granted('ROLE_Administrateur')",
 *                        "security_post_denormalize_message"="Vous n'avez pas access à cette Ressource",
 *                        },
 *                 "LISTER_users"={
 *                        "method"="GET",
 *                         "path"="/admin/users",
 *                         "security"="is_granted('ROLE_Administrateur')",
 *                         "security_message"="Vous n'avez pas access à cette Ressource"
 *                             },
 *            },
 *  normalizationContext={
 *      "groups"={"users_read"}
 *  },
 * )
 */
class User implements UserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"users_read"})
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     * @Assert\NotBlank(message="Ce champ est obligatoire")
     * @Groups({"users_read"})
     */
    private $username;


    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     * @Assert\NotBlank(message="Ce champ est obligatoire")
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=50)
     * @Assert\NotBlank(message="Veuillez renseigner le nom de l'utilisateur")
     * @Groups({"users_read"})
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=50)
     * @Assert\NotBlank(message="Veuillez renseigner le prénom de l'utilisateur")
     * @Groups({"users_read"})
     */
    private $prenom;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $telephone;

    /**
     * @ORM\Column(type="string", length=15)
     * @Assert\NotBlank(message="Veuillez renseigner le sexe de l'utilisateur")
     */
    private $genre;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=20)
     */
    private $photo;

    /**
     * @ORM\Column(type="integer")
     */
    private $archivage;

    /**
     * @ORM\ManyToOne(targetEntity=Profil::class, inversedBy="user")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\NotBlank(message="Veuillez renseigner le profil de l'utilisateur")
     * @Groups({"user_read"})
     */
    private $profil;

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_'.$this->profil->getLibelle();

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(string $telephone): self
    {
        $this->telephone = $telephone;

        return $this;
    }

    public function getGenre(): ?string
    {
        return $this->genre;
    }

    public function setGenre(string $genre): self
    {
        $this->genre = $genre;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getPhoto(): ?string
    {
        return $this->photo;
    }

    public function setPhoto(string $photo): self
    {
        $this->photo = $photo;

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

    public function getProfil(): ?Profil
    {
        return $this->profil;
    }

    public function setProfil(?Profil $profil): self
    {
        $this->profil = $profil;

        return $this;
    }
}
