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

    public function __construct(private ExempleRepository $exempleRepository, private EntityManagerInterface $em, private SerializerInterface $serilizer) {

    }

    #[Route('/api/exemple/all', name:'app_api_exemple_all', methods:'GET')]
    public function getAllExemple() : Response {
        //tableau liste des exemples
        $exemples = $this->exempleRepository->findAll();
        //test si il existe des exemples en BDD
        if($exemples) {
            $data = $exemples;
            $code = 200;
        }
        //test si il n'y a pas d'exemples en BB
        else {
            $data = ["error"=> "Il n'y à pas d'exemples en BDD"];
            $code = 206;
        }
        //retourner un json avec les exemples
        return $this->json($data,$code,['Access-Control-Allow-Origin' => '*']);
    }

    #[Route('/api/exemple/id/{id}', name: 'app_api_exemple_id', methods: 'GET')]
    public function getExempleId($id) :Response 
    {
        //récupérer l'enregistrement
        $exemple = $this->exempleRepository->find($id);
        //tester si l'exemple existe
        if($exemple) {
            $data = $exemple;
            $code = 200;
        }
        //tester si il n'existe pas
        else {
            $data = ["error" => "L'exemple n'existe pas"];
            $code = 206;
        }
        //retourner un json avec l'exemple
        return $this->json($data,$code,['Access-Control-Allow-Origin' => '*']);
    }
    #[Route('/api/exemple/add', name: 'app_api_exemple_id', methods: 'POST')]
    public function addExemple(Request $request) : Response
    {
        try{
            //récupérer les donnée du formulaire
            $data = $request->getContent();
            $data= $this->serilizer->deserialize($data,Exemple::class,'json');
            $objetExist= $this->exempleRepository->findOneBy(["name"=>$data->getName(),"value"=>$data->getValue()]);
            if($objetExist) {
                $message = ["error" => "L'exemple existe déjà"];
                $code = 400;
            }
            else {
                $message = ["success" => "L'exemple a été ajouté"];
                $this->em->persist($data);
                $this->em->flush();
                $code = 200;
            }
        }
        catch(\Exception $e) {
            $message = ["error" => $e->getMessage()];
            $code = 400;
        }
         
         return $this->json($message,$code,['Access-Control-Allow-Origin' => '*']);
    }
    #[Route('/api/exemple/updateV2', name: 'app_api_exemple_id', methods: 'PUT')]
    public function updateExempleV2(Request $request) : Response{
        $data = $request->getContent();
        $data= $this->serilizer->deserialize($data,Exemple::class,'json');
        dd($data);
    }

}

