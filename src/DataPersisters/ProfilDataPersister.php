<?php

namespace App\DataPersisters;

use ApiPlatform\Core\DataPersister\ContextAwareDataPersisterInterface;
use App\Entity\Profil;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;

final class ProfilDataPersister implements ContextAwareDataPersisterInterface
{
    private $em;
    
    public function __construct(EntityManagerInterface $em)
    {
        $this->em=$em;
      
    }
    
    public function supports($data, array $context = []): bool
    {
        return $data instanceof Profil;
    }

    public function persist($data, array $context = [])
    {
        // call your persistence layer to save $data
        return $data;
    }


    public function remove($data, array $context = [])
    {
        // call your persistence layer to delete $data
        $data->setArchivage(0);
        $this->em->persist($data);
        foreach($data->getUser() as $otherUser){

          $otherUser->setArchivage(0);
          
        }
        $this->em->flush();
    }  
}