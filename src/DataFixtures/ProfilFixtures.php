<?php

namespace App\DataFixtures;

use App\Entity\Profil;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class ProfilFixtures extends Fixture
{
    public const ADMIN_USER_REFERENCE = 'admin-user';

    public static function getReferenceKey($i){
        return sprintf('profil_%s',$i);
    }
    public function load(ObjectManager $manager)
    {
        $tab=[ 'formateur', 'apprenant', 'cm'];
        foreach($tab as $key => $value){
            
            $profil = new Profil();
            $profil->setLibelle($value);
            $profil->setArchivage(0);
            
            $this->addReference(self::getReferenceKey($key), $profil);

            $manager->persist($profil);

            $manager->flush();
    
        }

    }
}
