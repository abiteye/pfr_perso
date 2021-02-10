<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Tag;
use App\Entity\Niveau;
use App\Entity\Competence;
use App\Entity\Referentiel;
use App\Entity\GroupeCompetence;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class CompetenceFixtures extends Fixture
{
    
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');

        $tabCompetences = ["Créer une base de données",
                           "Développer les composants d'accès d'une base de donnée",
                           "Développer les composants d'ApiPlatform"
        ];
        //ajout des references
        $referentiel = new Referentiel();

        $referentiel->setLibelle("Référentiel 1")
                    ->setPresentation($faker->text)
                    ->setProgramme($faker->text)
                    ->setCritereEvaluation($faker->text)
                    ->setCompetencesVisees($faker->text)
                    ->setCritereAdmission($faker->text);

        $groupeCompetence = new GroupeCompetence();

        foreach ($tabCompetences as $libelle) {
            
            $competence = new Competence();
            $competence->setLibelle($libelle)
                       ->setDescriptif($faker->text)
                       ->setArchivage(false);

            //On ajoute les niveaux en fonctions des compétences
            for($i=1;$i<=3;$i++){
                $niveau = new Niveau();
                $niveau->setLibelle('Niveau '.$i)
                       ->setGroupeAction("Pas d'actions")
                       ->setCritereEvaluation("Pas de critères")
                       ->setArchivage(false);
                $manager->persist($niveau);
                $competence->addNiveau($niveau);
            }

            $manager->persist($competence);

            $referentiel->addGroupeCompetence($groupeCompetence);

            $manager->persist($referentiel);

            //On génère un groupe de compétence
            $groupeCompetence->setLibelle("Développement web")
                            ->setDescriptif("Pas d'infos")
                            ->addCompetence($competence)
                            ->setArchivage(false);
            $manager->persist($groupeCompetence);
        }
            //ajout des fixtures des de tags
            $tagTable = ['PHP', 'JS', 'HTML5', 'SQL', 'CSS3', 'JQUERY', 'SYMFONY', 'ApiPlatform'];
            foreach($tagTable as $tagLibelle){
                $tag = new Tag();
                $tag->setLibelle($tagLibelle)
                    ->setDescriptif("Pas d'informations")
                    ->setArchivage(false);
                
                $manager->persist($tag);
                //On ajoute les tags dans notre objet GroupeCompetence
                $groupeCompetence->addTag($tag);
                $manager->persist($groupeCompetence);
            }

        $manager->flush();
    }
}
