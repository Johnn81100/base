<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Exemple;
use App\Form\ExempleType;
use Doctrine\ORM\EntityManagerInterface;



class ExempleController extends AbstractController
{
    #[Route('/exemple', name: 'app_exemple')]
    public function index(Request $request, EntityManagerInterface $em): Response
    {
        $exemple = new Exemple();
        $form = $this->createForm(ExempleType::class,$exemple);
        $form->handleRequest($request);
        if($form->isSubmitted()) {
            //dd($exemple);
            $em->persist($exemple);
            $em->flush();
            $this->addFlash("success", "L'exemple a été ajouté en BDD");
        }
        return $this->render('exemple/index.html.twig', [
            'formulaire' => $form->createView(),
        ]);
    }
}
