<?php

namespace App\Controller\Front;

use App\Entity\Auteur;
use App\Form\Auteur2Type;
use App\Repository\AuteurRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/front/auteur")
 */
class AuteurController extends AbstractController
{
    /**
     * @Route("/auteurs", name="front_auteur_index", methods={"GET"})
     */
    public function index(AuteurRepository $auteurRepository): Response
    {
        return $this->render('front/auteur/index.html.twig', [
            'auteurs' => $auteurRepository->findAll(),
        ]);
    }

    /**
     * @Route("/auteurs/{id}", name="front_auteur_show", methods={"GET"})
     */
    public function show(Auteur $auteur): Response
    {
        return $this->render('front/auteur/show.html.twig', [
            'auteur' => $auteur,
        ]);
    }
}
