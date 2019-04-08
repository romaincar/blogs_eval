<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Comment;
use App\Form\CommentType;
use App\Repository\ArticleRepository;
use Doctrine\Common\Persistence\ObjectManager;
use function Sodium\add;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class BlogController extends AbstractController
{
    /**
     * @Route("/blog", name="blog")
     */
    public function index(ArticleRepository $repo)
    {
        $articles = $repo->findAll();

        return $this->render('blog/index.html.twig', [
            'controller_name' => 'BlogController',
            'articles' => $articles
        ]);
    }

    /**
     * @Route("/", name="accueil")
     */
    public function accueil()
    {
        return $this->render('blog/accueil.html.twig');
    }

    /**
     * @Route("admin/blog/new", name="blog_create")
     * @Route("admin/blog/{id}/edit", name="blog_edit")
     */
    public function createmodif(Article $article = null, Request $request, ObjectManager $manager)
    {
        if (!$article) {
            $article = new Article();
        }


        $form = $this->createFormBuilder($article)
            ->add('titre', TextType::class, [
                'attr' => ['placeholder' => "Titre de l'article",
                    'class' => 'form-control'
                ]
            ])
            ->add('content', TextareaType::class, [
                'attr' => ['placeholder' => "Texte de l'article",
                    'class' => 'form-control'
                ]
            ])
            ->add('image', TextType::class, [
                'attr' => ['placeholder' => " URL de image",
                    'class' => 'form-control'
                ]
            ])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($article->getId()) {
                $article->setCreateAt(new \DateTime());
            }
            $manager->persist($article);
            $manager->flush();

            return $this->redirectToRoute('blog_show', ['id' => $article->getId()]);
        }


        return $this->render('blog/create.html.twig', [
            'formArticle' => $form->createView(),
            'editMode' => $article->getId() !== null
        ]);
    }

    /**
     * @Route("/blog/{id}", name="blog_show")
     */
    public function show_in(ArticleRepository $repo, $id, Comment $comment, Request $request, ObjectManager $manager)
    {
        $comment = new Comment();


        $form = $this->createForm(CommentType::class, $comment);
        $article = $repo->find($id);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $comment->setCreatedAt(new \DateTime())
                ->setArticle($article);

            $manager->persist($comment);
            $manager->flush();

            return $this->redirectToRoute('blog_show', [
                'id' => $article->getId()
            ]);
        }


        return $this->render('blog/show_in.html.twig', [
            'article' => $article,
            'commentForm' => $form->createView()
        ]);
    }

    /**
     * @Route("/admin/{id}/delete", name="blog_delete")
     */
    public function Delete(Article $article = null, Comment $comment, Request $request, ObjectManager $manager)
    {



        $form = $this->createFormBuilder($article)
            ->add('titre', TextType::class, [
                'attr' => ['placeholder' => "Titre de l'article",
                    'class' => 'form-control'
                ]
            ])
            ->add('content', TextareaType::class, [
                'attr' => ['placeholder' => "Texte de l'article",
                    'class' => 'form-control'
                ]
            ])
            ->add('image', TextType::class, [
                'attr' => ['placeholder' => " URL de image",
                    'class' => 'form-control'
                ]
            ])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager->remove($article);
            $manager->flush();

            return $this->redirectToRoute('blog', ['id' => $article->getId()]);
        }


        return $this->render('blog/delete.html.twig', [
            'formArticle' => $form->createView(),
        ]);
    }


}

