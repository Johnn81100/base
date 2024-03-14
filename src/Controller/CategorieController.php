<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Categorie;
use App\Form\CategorieType;
use App\Repository\CategorieRepository;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;


class CategorieController extends AbstractController
{
    private EntityManagerInterface $em;
    private CategorieRepository $catRepo;
    private NormalizerInterface $normalizer;

    public function __construct(
        EntityManagerInterface $entityManagerInterface,
        CategorieRepository $categorieRepository,
        NormalizerInterface $normalizerInterface
    ) {
        $this->em = $entityManagerInterface;
        $this->catRepo = $categorieRepository;
        $this->normalizer = $normalizerInterface;
    }

    #[Route('/categorie/add', name: 'app_categorie_add')]
    public function addCategorie(Request $request): Response 
    {
        //créer un objet categorie
        $cat = new Categorie();
        //créer un objet CategorieType (formulaire)
        $form = $this->createForm(CategorieType::class, $cat);
        //récupére le contenu de la requête
        $form->handleRequest($request);
        //vérifier quand le formulaire est submit
        if ($form->isSubmitted()) {
            //test si le formulaire est valide
            if ($form->isValid()) {
                //test si la catégorie n'existe pas
                if (!$this->catRepo->findOneBy(["nom" => $cat->getNom()])) {
                    //mettre en cache
                    $this->em->persist($cat);
                    //enregistrer en BDD
                    $this->em->flush();
                    //message de confirmation
                    $type = "success";
                    $message = "La catégorie a été ajouté";
                }
                //test si la catégorie existe
                else {
                    //message d'erreur
                    $type = "danger";
                    $message = "La catégorie existe déja";
                }
            }
            //test le formulaire n'est pas valide
            else {
                //récupération de l'erreur
                $type = "warning";
                $message = $form->getErrors(true)[0]->getMessage();
            }
            //affichage des messages
            $this->addFlash($type, $message);
        }
        //rendu de la vue Twig
        return $this->render('categorie/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/categorie/validate', name : 'app_categorie_validate')]
    public function validCategorie(ValidatorInterface $validatorInterface) : Response 
    {
        //instancier un objet
        $cat = new Categorie();
        //setter les attributs (avec une erreur)
        $cat->setNom('t');
        //récupération des erreurs
        $errors = $validatorInterface->validate($cat);
        //test s'il y a des erreurs
        if(count($errors) > 0) {
            //récupération du premier message d'erreur
            return new Response($errors[0]->getMessage());
        }
        return new Response('La catégorie est valide');
    }
}
