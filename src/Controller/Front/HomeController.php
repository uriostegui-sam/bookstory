<?php

namespace App\Controller\Front;

use App\Repository\LivreRepository;
use App\Repository\AuteurRepository;
use App\Form\SearchLivreCriteriaType;
use App\Repository\CategorieRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="front_home")
     */
    public function index(AuteurRepository $auteurRepository, CategorieRepository $categorieRepository, LivreRepository $livreRepository): Response
    {
        $dernierLivres = $livreRepository->findLast();
        $dernierCategories = $categorieRepository->findLast();
        $dernierAuteurs = $auteurRepository->findLast();

        return $this->render('front/home/index.html.twig', [
            'livres' => $dernierLivres,
            'categories' => $dernierCategories,
            'auteurs' => $dernierAuteurs,
        ]);
    }

    /**
     * @Route("/rechercher", name="rechercher")
     */
    public function rechercher(Request $request, LivreRepository $livreRepository, CategorieRepository $categorieRepository): Response
    {
        $form = $this->createForm(SearchLivreCriteriaType::class);
        $form->handleRequest($request);
        $livres = $livreRepository->findCriteria($form->getData());

        return $this->render(
            'front/livre/recherche.html.twig',
            [
                'form' => $form->createView(), 'livres' => $livres,
                'categories' => $categorieRepository->findAll(),
            ]
        );
    }
}
