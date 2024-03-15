<?php

namespace App\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Exemple;
use App\Repository\ExempleRepository;
use Doctrine\ORM\EntityManagerInterface;


class ApiExempleController extends AbstractController{

    public function __construct(private ExempleRepository $exempleRepository, private EntityManagerInterface $em) {

    }

    #[Route('/api/exemple/all', name:'app_api_exemple_all', methods:'GET')]
    public function getAllExemple() : Response {
        //tableau liste des exemples
        $exemples = $this->exempleRepository->findAll();
        //retourner un json avec les exemples
        return $this->json($exemples,200,['Access-Control-Allow-Origin' => '*']);
    }
}