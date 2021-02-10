<?php

namespace App\DataFixtures;

use DateTime;
use Faker\Factory;
use App\Entity\Groupe;
use App\Entity\Apprenant;
use App\Entity\Promotion;
use App\Entity\Referentiel;
use App\DataFixtures\UserFixtures;
use App\DataFixtures\ProfilFixtures;
use App\Repository\ProfilRepository;
use App\Repository\ApprenantRepository;
use App\Repository\FormateurRepository;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AllDataFixtures extends Fixture implements DependentFixtureInterface
{

    private $encoder;
    private $repoProfil;
    private $repoFormateur;
    public function __construct(UserPasswordEncoderInterface $encoder, ProfilRepository $repoProfil,FormateurRepository $repoFormateur){
        $this->repoProfil = $repoProfil;
        $this->encoder = $encoder;
        $this->repoFormateur = $repoFormateur;
    }

    public function getDependencies()
    {
        return array(
            UserFixtures::class,
        );
    }
    
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create("fr_FR");
        $formateurs = $this->repoFormateur->findAll()[0];
     

        //Ajout des référentiels
        for ($j=0; $j < 3 ; $j++) {
           $referentiel = new Referentiel();
           $referentiel->setLibelle($faker->word)
                       ->setPresentation($faker->word)
                       ->setProgramme($faker->text(50))
                       ->setCritereEvaluation($faker->text)
                       ->setCompetencesVisees($faker->text)
                       ->setCritereAdmission($faker->text);
                             
            //Ajout des promos         
            for ($i=1; $i <=3 ; $i++) {
                $promo = new Promotion();
                $promo->setLangue($faker->randomElement(['français','anglais']))
                      ->setTitre($faker->word)
                      ->setLieuPromo($faker->city)
                      ->setReferenceAgate($faker->text(100))
                      ->setFabrique("Sonatel Academy")
                      ->setDateDebut(new DateTime())
                      ->setDateFin(new DateTime())
                      ->addReferentiel($referentiel);

                //Ajout des groupes  
                for ($k=1; $k <=3 ; $k++) {
                    $groupPromo = new Groupe();
                    if($k==1){
                        $groupPromo->setNom("Groupe principal");
                    }else{
                        $groupPromo->setNom($faker->word);
                    }
                    $groupPromo->setDateCreation(new DateTime())
                               ->setPromotion($promo);
                            foreach($formateurs as $f)
                            {
                                $groupPromo ->addFormateur($f);
                            }
                    $manager->persist($groupPromo);
                }

                 //Affectation des apprenants aux groupes            
                for ($l=1; $l <=10 ; $l++) {
                    $user = new Apprenant();
                    $user->setProfil($this->getReference(ProfilFixtures::APP_USER_REFERENCE))
                         ->setUsername($faker->userName)
                         ->setPassword($passwordEncoder=$this->encoder->encodePassword($user, 'password'))
                         ->setNom($faker->lastName)
                         ->setPrenom($faker->firstName)
                         ->setTelephone($faker->phoneNumber)
                         ->setGenre($faker->randomElement(['masculin', 'feminin']))
                         ->setEmail($faker->email)
                         ->setPhoto($faker->imageUrl($width = '640', $height = '480'))
                         ->setArchivage(0)
                         ->setAdresse($faker->address)
                         ->setStatut($faker->randomElement(['Actif', 'Renvoyé', 'Suspendu', 'Abandonné', 'Décédé']))
                         ->setInfoComplementaire($faker->text())
                         ->setCategorie($faker->randomElement(['BIEN', 'ABIEN', 'EXCELLENT', 'FAIBLE']))
                         ->addGroupe($groupPromo);    
                    $manager->persist($user);
                }
                $promo->addGroupe($groupPromo);
                $manager->persist($promo);
                }
                $manager->persist($referentiel);
        }
        $manager->flush();
}
}
