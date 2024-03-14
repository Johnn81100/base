<?php

namespace App\Controller\Admin;

use App\Entity\Categorie;

use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class CategorieCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Categorie::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id', 'Identifiant')->onlyOnIndex(),
            TextField::new('nom', 'Nom categorie')->stripTags(),
        ];
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud

            ->setPageTitle('index', 'Liste des catégories')
            ->setPageTitle('new', 'Ajouter une catégorie')
            ->setPageTitle('detail', 'Afficher une catégorie')
            ->setPageTitle('edit', 'Modifier la catégorie')
        ;
    }
   
}
