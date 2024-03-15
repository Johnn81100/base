<?php

namespace App\Service;

use App\Entity\Categorie;
use App\Repository\CategorieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class CategorieService
{
    private CategorieRepository $catRepo;

    private EntityManagerInterface $em;

    private ValidatorInterface $validator;

    private JsonEncoder $encoder;

    public function __construct(
        CategorieRepository $categorieRepository,
        EntityManagerInterface $entityManagerInterface,
        ValidatorInterface $validatorInterface,
        JsonEncoder $jsonEncoder,
    ) {
        $this->catRepo = $categorieRepository;
        $this->em = $entityManagerInterface;
        $this->validator = $validatorInterface;
        $this->encoder = $jsonEncoder;
    }

    public function create(?Categorie $categorie): string
    {
        //exécution du code si tout va bien
        try {
            //test si la catégorie est valide
            $this->categorieValidation($categorie);
            //test si la catégorie existe
            $this->categorieExist($categorie);
            //ajout de la catégorie en BDD
            $this->em->persist($categorie);
            $this->em->flush();
            //retourne le json de validation
            return $this->encoder->encode(
                ["type" => "success", "message" => "La categorie " .
                    $categorie->getNom() . " a été ajouté en BDD"],
                "json"
            );
        }
        //lever et retourner l'Exception
        catch (\Throwable $th) {
            //retourner un json d'erreur
            return $this->encoder->encode(
                ["type" => $th->getCode() == 1 ? "danger" : "warning", "message" => $th->getMessage()],
                "json"
            );
        }
    }

    public function categorieValidation(Categorie $categorie): void
    {
        //récupération de la liste des erreurs
        $errors = $this->validator->validate($categorie);
        //test si la catégorie n'est pas valide
        count($errors) > 0 ? throw new \Exception( $errors[0]->getMessage(),2) : null;
    }

    public function categorieExist(Categorie $categorie): void
    {
        //test si la catégorie existe déja
        $this->catRepo->findOneBy(["nom" => $categorie->getNom()])
            ? throw new \Exception("La categorie existe déja en BDD", 1) : null;
    }
}
