<?php

namespace App\Controller;

use App\Entity\Profil;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ArchivageProfilController extends AbstractController
{
    /**
     * Undocumented variable
     *
     * @var EntityManagerInterface
     */

    private $manager;

    public function __construct(EntityManagerInterface $manager)
    {
        $this->manager->$manager;
        
    }

    //Fonction qui permet de modifier l'archivage du user
    public function __invoke(Profil $data)
    {
        $data->setArchivage(0);
        $this->manager->flush();

        return $data;
        
    }
}
