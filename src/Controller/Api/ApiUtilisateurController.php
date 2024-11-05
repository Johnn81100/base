<?php

namespace App\Controller\Api;

use App\Entity\Utilisateur;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\UtilisateurRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ApiUtilisateurController extends AbstractController
{
    public function __construct(
        private UtilisateurRepository $utilisateurRepository,
        private EntityManagerInterface $em,
        private SerializerInterface $serializer,
    ) { }

    #[Route('/api/utilisateur/all', name: 'app_api_api_utilisateur', methods: 'GET')]
    public function allUsers(): Response
    {
         //tableau liste des exemples
         $exemples = $this->utilisateurRepository->findAll();
         //test si il existe des exemples en BDD
         if ($exemples) {
             $data = $exemples;
             $code = 200;
         }
         //test si il n'y a pas d'exemples en BB
         else {
             $data = ["error" => "Il n'y à pas d'exemples en BDD"];
             $code = 206;
         }
         //retourner un json avec les exemples
         return $this->json($data, $code, ['Access-Control-Allow-Origin' => '*']);
    }
    #[Route('/api/utilisateur/{id}', name: 'app_api_exemple_id', methods: 'GET')]
    public function getUserId($id): Response
    {
        //récupérer l'enregistrement
    
        $userExist = $this->utilisateurRepository->find($id);
        //tester si l'exemple existe
        if ($userExist) {
            $data = $userExist;
            $code = 200;
        }
        //tester si il n'existe pas
        else {
            $data = ["error" => "L'exemple n'existe pas"];
            $code = 206;
        }
        //retourner un json avec l'exemple
        return $this->json($data, $code, ['Access-Control-Allow-Origin' => '*']);
    }
    #[Route('/api/utilisateur/add', name: 'app_api_utilisateur_add', methods: 'POST')]
    public function addUser(Request $request): Response
    {
        try {
            //récupération json
            $json = $request->getContent();
            //convertir en objet
            $data = $this->serializer->deserialize($json, Utilisateur::class,'json');
            //test si l'exemple existe déja
            if($this->utilisateurRepository->findOneBy(["nom" => $data->getNom(),"prenom" => $data->getPrenom(),"email" => $data->getEmail()])) {
                $message = ["error" => "L'exemple existe déja"];
                $code = 400;
            }
            //test si l'exemple n'existe pas
            else{
                $message = ["confirm" => "l'exemple à été ajouté en BDD", "exemple"=> $data] ;
                $code = 200; 
                //persister
                $this->em->persist($data);
                //ajouter en BDD
                $this->em->flush();
            }
        } catch (\Throwable $th) {
            $message = ["error" => $th->getMessage()];
            $code = 400;
        }
        
        //retourner un json de reponse
        return $this->json($message,$code,['Access-Control-Allow-Origin' => '*']);
    }
    #[Route('/api/utilisateur/update', name: 'app_api_utilisateur_update', methods: 'PUT')]
    public function updateUser(Request $request): Response
    {
        try {
            //récupération json
            $json = $request->getContent();
            //convertir en objet
            $data = $this->serializer->decode($json, 'json');
            
            //test si l'exemple existe déja
            $userExist = $this->utilisateurRepository->findOneBy(["nom" => $data->getNom(),"prenom" => $data->getPrenom(),"email" => $data->getEmail()]);
            if(!$userExist) {
                $message = ["error" => "l'utilisateur n'existe pas"];
                $code = 400;
            }
            //test si l'exemple n'existe pas
            else{
                $message = ["confirm" => "l'utilsateur à été modifié  en BDD", "exemple"=> $data] ;
                $code = 200; 
                //persister
                $this->em->persist($data);
                //ajouter en BDD
                $this->em->flush();
            }
        } catch (\Throwable $th) {
            $message = ["error" => $th->getMessage()];
            $code = 400;
        }
        
        //retourner un json de reponse
        return $this->json($message,$code,['Access-Control-Allow-Origin' => '*']);
    }

}
