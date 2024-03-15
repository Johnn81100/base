<?php

namespace App\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Exemple;
use App\Repository\ExempleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Serializer\SerializerInterface;


class ApiExempleController extends AbstractController
{

    public function __construct(
        private ExempleRepository $exempleRepository,
        private EntityManagerInterface $em,
        private SerializerInterface $serializer
    ) {
    }

    #[Route('/api/exemple/all', name: 'app_api_exemple_all', methods: 'GET')]
    public function getAllExemple(): Response
    {
        //tableau liste des exemples
        $exemples = $this->exempleRepository->findAll();
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

    #[Route('/api/exemple/id/{id}', name: 'app_api_exemple_id', methods: 'GET')]
    public function getExempleId($id): Response
    {
        //récupérer l'enregistrement
        $exemple = $this->exempleRepository->find($id);
        //tester si l'exemple existe
        if ($exemple) {
            $data = $exemple;
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

    #[Route('/api/exemple/add', name: 'app_api_exemple_add', methods:'POST')]
    public function addExemple(Request $request) :Response 
    {   
        try {
            //récupération json
            $json = $request->getContent();
            //convertir en objet
            $data = $this->serializer->deserialize($json, Exemple::class,'json');
            //test si l'exemple existe déja
            if($this->exempleRepository->findOneBy(["name" => $data->getName(), "value" => $data->getValue()])) {
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

    #[Route('/api/exemple/update', name: 'app_api_exemple_update', methods:'PUT')]
    public function updateExemple(Request $request) : Response 
    {
        try {
            //récupérer le json
            $json = $request->getContent();
            //convertir le json en tableau
            $data = $this->serializer->decode($json, "json");
            //récupérer l'objet exemple
            $oldExemple = $this->exempleRepository->findOneBy(["name"=> $data["name"], "value" => $data["value"] ]);
            //test si l'objet exemple existe
            if($oldExemple){
                //setter les nouvelles valeurs
                $oldExemple->setName($data["newname"]);
                $oldExemple->setValue($data["newvalue"]);
                //persister les données
                $this->em->persist($oldExemple);
                //enregistrer en BDD
                $this->em->flush();
                $message = ["confirm" => "l'exemple à été mis a jour en BDD", "exemple"=> $oldExemple] ;
                $code = 200; 
            }
            //test si l'exemple n'existe pas
            else{
                $message = ["error" => "l'exemple n'existe pas"];
                $code = 400;
            }

        } catch (\Throwable $th) {
            $message = ["error" => $th->getMessage()];
            $code = 400;
        }
        //retourner un json de reponse
        return $this->json($message,$code,['Access-Control-Allow-Origin' => '*']);
    }


    #[Route('/api/exemple/updatev2', name:'app_api_exemple_updatev2', methods:'PUT')]
    public function updateExempleV2(Request $request) :Response 
    {
        try {
            //récupérer le json
            $json = $request->getContent();
            //transformer en objet
            $data = $this->serializer->decode($json, 'json');
         
            //récupérer l'objet exemple à modifier
            $exemple = $this->exempleRepository->find($data["id"]);    
            //test si exemple existe
            if($exemple) {
                //test si les données sont identiques
                if($exemple->getName() == $data["name"] and $exemple->getValue() == $data["value"]) {
                    $message = ["error" => "Les données sont identiques"];
                    $code = 400;
                }
                //les données sont différentes
                else {
                    $exemple->setName($data["name"]);
                    $exemple->setValue($data["value"]);
                    //persister les données
                    $this->em->persist($exemple);
                    //enregistrer en BDD
                    $this->em->flush();
                    $message = ["confirm" => "l'exemple à été mis a jour en BDD", "exemple"=> $exemple] ;
                    $code = 200; 
                }
            }
            //test si l'exemple n'existe pas
            else{
                $message = ["error" => "l'exemple n'existe pas"];
                $code = 400;
            }   
        } catch (\Throwable $th) {
            $message = ["error" => $th->getMessage()];
            $code = 400;
        }
        //retourner un json de reponse
        return $this->json($message,$code,['Access-Control-Allow-Origin' => '*']);
    }

    #[Route('/api/exemple/delete/{id}', name : 'app_api_exemple_delete', methods:'DELETE')]
    public function deleteExemple($id) :Response 
    {
        //récupérer l'objet
        $exemple = $this->exempleRepository->find($id);
        //tester si il existe
        if($exemple) {
            $this->em->remove($exemple);
            $this->em->flush();
            $message = ["confirm" => "l'exemple a été supprimé"] ;
            $code = 200; 
        }
        //tester si il n'existe pas
        else {
            $message = ["error" => "l'exemple n'existe pas"];
            $code = 400;
        }
        //retourner un json de reponse
        return $this->json($message,$code,['Access-Control-Allow-Origin' => '*']);
    }
}
