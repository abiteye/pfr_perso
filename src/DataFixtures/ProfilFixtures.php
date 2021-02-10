<?php

namespace App\DataFixtures;

use App\Entity\Profil;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class ProfilFixtures extends Fixture
{
    public const ADMIN_USER_REFERENCE = 'admin-user';
    public const FOR_USER_REFERENCE = 'for-user';
    public const APP_USER_REFERENCE = 'apprenant-user';
    public const CM_USER_REFERENCE = 'cm-user';

    public static function getReferenceKey($i){
        return sprintf('profil_%s',$i);
    }
    public function load(ObjectManager $manager)
    {
        $tabprofil=['admin-user', 'for-user', 'apprenant-user', 'cm-user'];
        $tab=['Admin', 'Formateur', 'Apprenant', 'Cm'];
        foreach($tab as $key => $value){
            
            $profil = new Profil();
            $profil->setLibelle($value);
            $profil->setArchivage(0);
            $this->addReference($tabprofil[$key], $profil);
            // if ($value=='Apprenant') {
            //     $this->addReference('apprenant-user', $profil);
            // }else{
            // $this->addReference(self::getReferenceKey($key), $profil);
            // }

            $manager->persist($profil);

            $manager->flush();
    
        }

    }
}
