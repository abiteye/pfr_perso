<?php

namespace App\DataFixtures;

use App\Entity\CM;
use Faker\Factory;
use App\Entity\User;
use App\Entity\Apprenant;
use App\Entity\Formateur;
use App\DataFixtures\ProfilFixtures;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use App\DataFixtures\ProfilDeSortieFixtures;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture implements DependentFixtureInterface
{
    private $encoder;
    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }
    

    public function getDependencies()
    {
        return array(
            ProfilFixtures::class,
        );
    }
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');
        $tabprofil=['Admin',"Formateur","Cm"];
        foreach($tabprofil as $profil){
            
           // dd($tabprofil->getLibelle());
            
            for($j=0; $j<8; $j++){

                

                $user = new User();
                $passwordEncoder = $this->encoder->encodePassword($user, 'password');
                if($profil== 'Admin'){
                    $tabprofil= $this->getReference(ProfilFixtures::ADMIN_USER_REFERENCE);
                    $user = new User();
                        

                }else if($profil== 'Formateur'){
                    
                     $tabprofil= $this->getReference(ProfilFixtures::FOR_USER_REFERENCE);
                     
                     $user = new Formateur();

                    
                }
                //else if($tabprofil->getLibelle()== 'Apprenant'){

                //     $key=$faker->numberBetween(0,7);

                //     $user = new Apprenant();
                //     $user->setAdresse($faker->address)
                //          ->setStatut($faker->randomElement(['Actif', 'Renvoyé', 'Suspendu', 'Abandonné', 'Décédé']))
                //          ->setInfoComplementaire($faker->text())
                //          ->setCategorie($faker->randomElement(['BIEN', 'ABIEN', 'EXCELLENT', 'FAIBLE']))
                //          ->setProfilDeSortie($this->getReference(ProfilDeSortieFixtures::getReferenceKey($key))); 
                    
                // }
                else{
                    $tabprofil= $this->getReference(ProfilFixtures::CM_USER_REFERENCE);
                    $user = new CM();
                    
                }
                $user
                    ->setUsername($faker->userName)
                    ->setPassword($passwordEncoder)
                    ->setNom($faker->lastName)
                    ->setPrenom($faker->firstName)
                    ->setTelephone($faker->phoneNumber)
                    ->setGenre($faker->randomElement(['masculin', 'feminin']))
                    ->setEmail($faker->email)
                    ->setPhoto($faker->imageUrl($width = '640', $height = '480'))
                    ->setArchivage(0)
                    ->setProfil($tabprofil);

                $manager->persist($user);

                $manager->flush();
            }

            
        }
        
    }
}
