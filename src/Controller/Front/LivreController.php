<?php

namespace App\Controller\Front;

use App\Entity\Livre;
use App\Form\Livre1Type;
use App\Repository\LivreRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class LivreController extends AbstractController
{
    /**
     * @Route("/livres/", name="front_livre_index", methods={"GET"})
     */
    public function index(LivreRepository $livreRepository): Response
    {
        return $this->render('front/livre/index.html.twig', [
            'livres' => $livreRepository->findAll(),
        ]);
    }

    /**
     * @Route("/livre/{id}", name="front_livre_show", methods={"GET"})
     */
    public function show(Livre $livre): Response
    {
        return $this->render('front/livre/show.html.twig', [
            'livre' => $livre,
        ]);
    }
}
