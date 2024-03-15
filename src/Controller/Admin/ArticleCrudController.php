<?php

namespace App\Controller\Admin;

use App\Entity\Article;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ArticleCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Article::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id', 'Identifiant')->onlyOnIndex(),
            TextField::new('titre', 'Titre')->stripTags(),
            TextareaField::new('contenu', 'Contenu')->setNumOfRows(10)->stripTags(),
            DateField::new('dateAjout', 'Date d\'ajout')->renderAsNativeWidget(false),
            AssociationField::new('utilisateur', 'Auteur')->autocomplete(),
            AssociationField::new('categories', 'Catégories')->autocomplete(),
            ImageField::new('urlImage', 'Image')->setUploadDir('public/asset/images/')->setBasePath('asset/images/')
            ->setUploadedFileNamePattern(
                fn (UploadedFile $file): string => sprintf('upload_%d_%s.%s', random_int(1, 999), $file->getFilename(), $file->guessExtension()))
            ,
        ];
    }
   
}
