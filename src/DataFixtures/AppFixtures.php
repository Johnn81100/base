<?php

namespace App\DataFixtures;

use Faker;
use App\Entity\User;
use App\Entity\Article;
use App\Entity\Categorie;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Faker\Factory::create('fr_FR');
        //créer un tableau 
        $utilisateurs = [];
        for ($i=0; $i < 50; $i++) { 
            //création d'un objet utilisateur
            $utilisateur = new User();
            $utilisateur->setName($faker->lastName());
            $utilisateur->setPrenom($faker->firstName('male'|'female'));
            $utilisateur->setEmail($faker->email());
            $utilisateur->setPassword($faker->md5());
            //mettre en cache l'objet
            $manager->persist($utilisateur);
            $utilisateurs[] = $utilisateur;
        }
        $categories = [];
        for ($i=0; $i <30 ; $i++) { 
            $cat = new Categorie();
            $cat->setNom($faker->word());
            $manager->persist($cat);
            $categories[]=$cat;
        }
        for ($i=0; $i < 200 ; $i++) { 
            $article = new Article();
            $article->setTitre($faker->word());
            $article->setContenu($faker->text(200));
            $article->setDateAjout(new \DateTimeImmutable($faker->date('Y-m-d')));
            $article->setUtilisateur($utilisateurs[$faker->numberBetween(0, 49)]);
            $article->addCategory($categories[$faker->numberBetween(0, 9)]);
            $article->addCategory($categories[$faker->numberBetween(10, 19)]);
            $article->addCategory($categories[$faker->numberBetween(20, 29)]);
            //mettre en cache l'objet 
            $manager->persist($article);
        }
        //ajouter en BDD
        $manager->flush();
    }
}
