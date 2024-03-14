<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class TestController extends AbstractController
{
    #[Route('/test/{nom}', name: 'app_test')]
    public function index($nom): Response
    {
        return $this->render('test/index.html.twig', [
            'nom' => $nom, 
        ]);
    }

    #[Route('/hello3', name: 'app_test_hello3')]
    public function hello(){
        return new Response('Hello World');
    }

    #[Route('/hello/{prenom}', name: 'app_test_hello5')]
    public function helloName($prenom){
        dd($prenom);
        return new Response('Bonjour '.$prenom);
    }

    #[Route('/calcul/{nbr1}/{nbr2}', name:'app_test_calcul')]
    public function calcul(mixed $nbr1, mixed $nbr2) : Response 
    {
        if(is_numeric($nbr1) AND is_numeric($nbr2)) {
            $resultat = "Le résultat est égal à : ". ($nbr1+$nbr2);
        }
        else { 
            $resultat = "Veuillez passer des nombres dans l'url";
        }

        return $this->render('test/calcul.html.twig',[
            'resultat' => $resultat,
            'nbr1' => $nbr1,
            'nbr2' => $nbr2,
        ]);
    }
}
