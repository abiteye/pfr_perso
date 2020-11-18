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
        for($i=1; $i<=4; $i++){
            
            $tabprofil= $this->getReference(ProfilFixtures::getReferenceKey($i %4));
            
            foreach($tabprofil as $value){

                $faker = Factory::create('fr_FR');
                

                $user = new User();
                $passwordEncoder = $this->encoder->encodePassword($user, 'password');

                if($value->getLibelle()== 'admin'){
                    $user = new User();
                    $user->setPrenom($faker->firstName)
                          ->setNom($faker->lastName)
                          ->setEmail($faker->email)
                          ->setPassword($passwordEncoder)
                          ->setTelephone($faker->phonrNumber)
                          ->setGenre($faker->randomElement(['masculin', 'feminin']))
                          ->setArchivage(0)
                          ->setProfil($value);

                        

                }else if($value->getLibelle()== 'formateur'){
                    
                    $user = new Formateur();
                    
                }else if($value->getLibelle()== 'apprenant'){

                    $user = new Apprenant();
                    $user->setAdresse($faker->address)
                         ->setStatut($faker->randomElement(['Actif', 'Renvoyé', 'Suspendu', 'Abandonné', 'Décédé']))
                         ->setInfoComplementaire($faker->text())
                         ->setCategorie($faker->randomElement(['BIEN', 'ABIEN', 'EXCELLENT', 'FAIBLE'])); 
                    
                }else{
                    $user = new CM();
                    
                }
                $manager->persist($user);

                $manager->flush();
            }

            
        }
        
    }
}
