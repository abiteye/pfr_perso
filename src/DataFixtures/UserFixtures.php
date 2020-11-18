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
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
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
        for($i=0; $i<4; $i++){
            
            $tabprofil= $this->getReference(ProfilFixtures::getReferenceKey($i));
           // dd($tabprofil->getLibelle());
            
            for($j=0; $j<4; $j++){

                $faker = Factory::create('fr_FR');
                

                $user = new User();
                $passwordEncoder = $this->encoder->encodePassword($user, 'password');
               /* if($tabprofil->getLibelle()== 'admin'){
                    $user = new User();
                        

                }else*/
                 if($tabprofil->getLibelle()== 'formateur'){
                    
                    $user = new Formateur();
                    
                }else if($tabprofil->getLibelle()== 'apprenant'){

                    $user = new Apprenant();
                    $user->setAdresse($faker->address)
                         ->setStatut($faker->randomElement(['Actif', 'Renvoyé', 'Suspendu', 'Abandonné', 'Décédé']))
                         ->setInfoComplementaire($faker->text())
                         ->setCategorie($faker->randomElement(['BIEN', 'ABIEN', 'EXCELLENT', 'FAIBLE'])); 
                    
                }else{
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
                    ->setPhoto("default.png")
                    ->setArchivage(0)
                    ->setProfil($tabprofil);

                $manager->persist($user);

                $manager->flush();
            }

            
        }
        
    }
}
