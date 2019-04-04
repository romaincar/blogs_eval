<?php

namespace App\DataFixtures;

use App\Entity\Article;
use App\Entity\Categorie;
use App\Entity\Comment;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class ArticlesFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {

        $fake = \Faker\Factory::create('fr_FR');

// Créer 3 catégories fakes
        for ($i = 1; $i <= 3; $i++) {
            $categorie = new Categorie();
            $categorie->setTitre($fake->sentence())
                ->setDescription($fake->paragraph());

            $manager->persist($categorie);


            // Créer entre 4 et 7 articles

            for ($j = 1; $j <= mt_rand(4, 6); $j++) {
                $article = new Article();


                $content = '<p>' . join($fake->paragraphs(5), '</p><p>') . '</p>';


                $article->setTitre($fake->sentence())
                    ->setContent($content)
                    ->setImage($fake->imageUrl())
                    ->setCreateAt($fake->dateTimeBetween('-6months'))
                    ->setCategorie($categorie);

                $manager->persist($article);

                // Commentaires à l'article
                for ($a = 1; $a <= mt_rand(4, 10); $a++) {
                    $comment = new Comment();

                    $content = '<p>' . join($fake->paragraphs(3), '</p><p>') . '</p>';

                    $now = new \DateTime();
                    $inter = $now->diff($article->getCreateAt());
                    $days = $inter->days;
                    $mini = '-' . $days . ' days'; // -100 days

                    $comment->setAuthor($fake->name)
                        ->setContent($content)
                        ->setCreatedAt($fake->dateTimeBetween($mini))
                        ->setArticle($article);

                    $manager->persist($comment);

                }
            }
        }
            $manager->flush();

    }
}
