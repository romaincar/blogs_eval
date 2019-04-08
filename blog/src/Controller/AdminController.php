<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\User;
use App\Form\CommentType;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\Persistence\ObjectManagerDecorator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
class AdminController extends AbstractController
{
    /**
     * @Route("/admin", name="admin")
     */
    public function index()
    {
        return $this->render('admin/index.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }

    /**
     * @Route("/admin/comm/{id}", name="admin_commentaire", methods={"DELETE"})
     */

    public function suppcomment(Comment $comment, Request $request): Response
    {

        if ($this->isCsrfTokenValid('delete' . $comment->getId(), $request->request->get('_token'))) {

            $entityManager = $this->getDoctrine()->getManager();

            $entityManager->remove($comment);

            $entityManager->flush();

        }
        return $this->redirectToRoute('blog');
    }
}
