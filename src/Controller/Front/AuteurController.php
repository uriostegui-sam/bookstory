<?php

namespace App\Controller\Front;

use App\Entity\Auteur;
use App\Repository\AuteurRepository;
use App\Repository\CategorieRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class AuteurController extends AbstractController
{
    /**
     * @Route("/auteurs", name="front_auteur_index", methods={"GET"})
     */
    public function index(
        AuteurRepository $auteurRepository,
        CategorieRepository $categorieRepository,
        PaginatorInterface $paginator,
        Request $request
    ): Response {

        $data = $auteurRepository->findAll();
        $auteurs = $paginator->paginate(
            $data,
            $request->query->getInt('page', 1),
            8
        );


        return $this->render('front/auteur/index.html.twig', [
            'categories' => $categorieRepository->findAll(),
            'auteurs' => $auteurs,
        ]);
    }

    /**
     * @Route("/auteur/{id}", name="front_auteur_show", methods={"GET"})
     */
    public function show(Auteur $auteur, CategorieRepository $categorieRepository): Response
    {
        return $this->render('front/auteur/show.html.twig', [
            'categories' => $categorieRepository->findAll(),
            'auteur' => $auteur,
        ]);
    }
}
