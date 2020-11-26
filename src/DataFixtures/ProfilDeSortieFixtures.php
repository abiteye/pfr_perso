<?php

namespace App\DataFixtures;

use App\Entity\ProfilDeSortie;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class ProfilDeSortieFixtures extends Fixture
{
    public const APP_REFERENCE='apprenant-user';

    public static function getReferenceKey($i){

        return sprintf('profilDeSortie_%s', $i);
    }

    public function load(ObjectManager $manager)
    {
        $tabProfilDeSortie = ["Développeur front", 
                               "Développeur back",
                               "Développeur fullstack", 
                               "CMS", 
                               "Intégrateur", 
                               "Designer", 
                               "CM", 
                               "DataArtisant"
        ];
        foreach ($tabProfilDeSortie as $key => $libelle){ 

            $profilDeSortie = new ProfilDeSortie();
            $profilDeSortie->setLibelle($libelle)
                            ->setArchivage(false);

            $this->addReference(self::getReferenceKey($key), $profilDeSortie);                

            $manager->persist($profilDeSortie); 

            $manager->flush();
        }    
    }
}
