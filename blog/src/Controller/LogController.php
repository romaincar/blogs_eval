<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationType;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class LogController extends AbstractController
{
    /**
     * @Route("/inscription", name="log_inscription")
     */
    public function inscription(Request $request, ObjectManager $manager, UserPasswordEncoderInterface $encoder)
    {
        $user = new User();

        $form = $this->createForm(RegistrationType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $hash = $encoder->encodePassword($user, $user->getPassword());
            $user->setPassword($hash);

            $user->setRole('ROLE_USER');

            $manager->persist($user);
            $manager->flush();

            return $this->redirectToRoute('log_connexion');
        }

        return $this->render('log/inscription.html.twig', [
   'form' => $form->createView()

        ]);
    }

    /**
     * @Route("/connexion", name="log_connexion")
     */
    public function connexion(){
        return $this->render('log/connexion.html.twig');
    }
    /**
     * @Route("/deconnexion", name="log_logout")
     */
    public function logout(){}
}
