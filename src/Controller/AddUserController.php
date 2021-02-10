<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use ApiPlatform\Core\Validator\ValidatorInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AddUserController extends AbstractController
{
    private $serializer;
    private $encoder;
    private $validator;
    private $em;
    private $repo;
    
    public function __construct(SerializerInterface $serializer, UserPasswordEncoderInterface $encoder, ValidatorInterface $validator, EntityManagerInterface $em,
    UserRepository $repo)
    {
        $this->encoder=$encoder;
        $this->serializer=$serializer;
        $this->validator=$validator;
        $this->em=$em;
        $this->repo=$repo;
    }

    /**
     * * @Route(
     *     name="addUser",
     *     path="/api/admin/users",
     *     methods={"POST"},
     *
     * ),
     *   * @Route(
     *     name="addApprenant",
     *     path="/api/apprenants",
     *     methods={"POST"},
     *
     * ),* @Route(
     *     name="addFormateur",
     *     path="/api/formateurs",
     *     methods={"POST"},
     *
     * )
     */
    public function add(Request $request)
    {
        //recupéré tout les données de la requete
        $user = $request->request->all();
        //dd($user);
        $photo = $request->files->get("photo");

        if($user['profil']==="api/admin/profils/3"){

            $user = $this->serializer->denormalize($user,"App\Entity\CM",true);
        }
        elseif($user['profil']==="api/admin/profils/1"){
            $user = $this->serializer->denormalize($user,"App\Entity\User",true);
        }
        elseif($user['profil']==="api/admin/profils/2"){
            $user = $this->serializer->denormalize($user,"App\Entity\Formateur",true);
        }
        else{

            $user = $this->serializer->denormalize($user,"App\Entity\Apprenant",true);
        }



        if(!$photo){
        

            return new JsonResponse("veuillez mettre une image",Response::HTTP_BAD_REQUEST,[],true);
        }
        //$base64 = base64_decode($imagedata);
        $photoBlob = fopen($photo->getRealPath(),"rb");


        $user->setPhoto($photoBlob);

        /*$errors = $this->validator->validate($user);
        if (count($errors)){
            $errors = $this->serializer->serialize($errors,"json");
            return new JsonResponse($errors,Response::HTTP_BAD_REQUEST,[],true);
        }*/

        /*
        $user=new User();
        $user->setPassword($this->encoder->encodePassword($user,$password));
        */
        $password = $user->getPassword();

        // User class based on symfony security User class
        $user->setPassword($this->encoder->encodePassword($user, $password));
        $user->setArchivage(0);
        if($this->encoder->encodePassword($user, $password)){

            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
            return  $this->json('Authenticated',200);

        }else{

            return  $this->json(' username or password not work',400);
        }

    }

    /**
     * * @Route(
     *     name="modifyUser",
     *     path="/api/admin/users/{id}",
     *     methods={"PUT"},
     * 
     * ),* @Route(
     *     name="modifyApprenant",
     *     path="/api/apprenants/{id}",
     *     methods={"PUT"},
     * 
     * ),* @Route(
     *     name="modifyFormateur",
     *     path="/api/formateurs/{id}",
     *     methods={"PUT"},
     * ),
     */

    public function updateUser(Request $request, int $id){

        $user=$this->repo->find($id);
        $repo=$request->request->all();
        //dd($user);

        foreach($repo as $key => $value){
            if($key != '_method' || !$value){

                $user->{"set".ucfirst($key)}($value);
            }
        }

        $photo = $request->files->get("photo");

        $photoBlob = fopen($photo->getRealPath(),"rb");

        $user->setPhoto($photoBlob);
        $this->em->persist($user);
        $this->em->flush();

        return $this->json("success", 200);

    }
}
