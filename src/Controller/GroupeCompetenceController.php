<?php

namespace App\Controller;

use App\Entity\Competence;
use App\Entity\GroupeCompetence;
use App\Repository\CompetenceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\GroupeCompetenceRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class GroupeCompetenceController extends AbstractController
{

    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(EntityManagerInterface $entityManager, SerializerInterface $serializer)
    {
        $this->em = $entityManager;
        $this->serializer=$serializer;
        
    }
    /**
     * @Route("/groupe/competence", name="groupe_competence")
     */
    public function index(): Response
    {
        return $this->render('groupe_competence/index.html.twig', [
            'controller_name' => 'GroupeCompetenceController',
        ]);
    }

    /**
     * @Route(
     *     name="edition_competence_in_grpe_competence",
     *     path="/api/admin/groupe_competences",
     *     methods={"POST"},
     * )
     */

    public function addGroupeCompetence(Request $request, GroupeCompetenceRepository $groupeCompRepo, CompetenceRepository $compRepo){

        //Recuperation de la requete 
        $gptab=json_decode($request->getContent(), true);

        foreach($gptab['competences'] as $key => $comp)
        {
            if(gettype($comp)!=="array"){
                $gptab['competences'][$key]="/api/admin/competences/".$comp;
            }
        }
        $gptab=$this->serializer->denormalize($gptab,GroupeCompetence::class, true);
        //dd($gptab);
        // if(!isset($groupeCompTab['id']) && isset($groupeCompTab['libelle']) && isset($groupeCompTab['descriptif'])){
        //     //ajout de competences
        //     $competence=new Competence();
            
        //     $competence->setLibelle($groupeCompTab['libelle'])
        //                ->setDescriptif($groupeCompTab['descriptif']);
                       
    

        //     $groupeCompTab->addCompetence($competence);
        // }
        
        $this->em->flush();

        return $this->json($groupeCompetence, Response::HTTP_OK);
    }
}
