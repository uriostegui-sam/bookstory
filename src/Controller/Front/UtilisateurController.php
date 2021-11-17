<?php

namespace App\Controller\Front;

use App\Entity\Utilisateur;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UtilisateurController extends AbstractController
{
    /**
     * @Route("/mon-profile", name="utilisateur_profile")
     * @IsGranted("ROLE_USER")
     */
    public function profile(): Response
    {
        return $this->render('front/utilisateur/profile.html.twig');
    }

    /**
     * @Route("/revendeur/{id}", name="revendeur_show", methods={"GET"})
     */
    public function showRevendeur(Utilisateur $revendeur): Response
    {
        if ($this->getUser() && $this->getUser()->getEmail() === $revendeur->getEmail()) {
            return $this->redirectToRoute('app_front_user_profile');
        }

        return $this->render('front/utilisateur/showRevendeur.html.twig', [
            'revendeur' => $revendeur,
        ]);
    }
}
