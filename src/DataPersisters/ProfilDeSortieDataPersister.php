<?php

namespace App\DataPersisters;

use App\Entity\ProfilDeSortie;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ProfilDeSortieRepository;
use Symfony\Component\HttpFoundation\Request;
use ApiPlatform\Core\DataPersister\ContextAwareDataPersisterInterface;

final class ProfilDeSortieDataPersister implements ContextAwareDataPersisterInterface
{
    private $em;
    private $repo;
    
    public function __construct(EntityManagerInterface $em, ProfilDeSortieRepository $repo)
    {
        $this->em=$em;
      
    }
    
    public function supports($data, array $context = []): bool
    {
        return $data instanceof ProfilDeSortie;
    }

    public function persist($data, array $context = [])
    {
        // call your persistence layer to save $data

        return $data;
    }


    public function remove($data, array $context = [])
    {
        // call your persistence layer to delete $data
        $data->setArchivage();
        $this->em->persist($data);
        foreach($data->getApprenants() as $a)
        {
          $a->setProfilDeSortie(null);
        }
        $this->em->flush();
    }  
}