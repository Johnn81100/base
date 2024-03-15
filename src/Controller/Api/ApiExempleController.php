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

    #[Route('/api/exemple/all', name:'app_api_exemple_all')]
    public function getAllExemple() : Response {
        return $this->json(["nom"=>"mathieu"]);
    }
}