<?php

namespace App\DataPersisters;

use ApiPlatform\Core\DataPersister\ContextAwareDataPersisterInterface;
use App\Entity\Referentiel;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;

final class ReferentielDataPersister implements ContextAwareDataPersisterInterface
{
    private $em;
    
    public function __construct(EntityManagerInterface $em)
    {
        $this->em=$em;
      
    }
    
    public function supports($data, array $context = []): bool
    {
        return $data instanceof Referentiel;
    }

    public function persist($data, array $context = [])
    {
        // call your persistence layer to save $data
        $this->em->persist($data);

        $this->em->flush();
        return $data;

    }


    public function remove($data, array $context = [])
    {
        // call your persistence layer to delete $data
        // $data->setArchivage(0);
        // foreach($data->getUser() as $otherUser){

        //   $otherUser->setArchivage(0);
          
        // }
        
    }  
}