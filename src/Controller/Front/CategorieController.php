<?php

namespace App\Controller\Front;

use App\Entity\Categorie;
use App\Repository\CategorieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CategorieController extends AbstractController
{
    /**
     * @Route("/categories/", name="front_categorie_index", methods={"GET"})
     */
    public function index(CategorieRepository $categorieRepository): Response
    {
        return $this->render('front/categorie/index.html.twig', [
            'categories' => $categorieRepository->findAll(),
        ]);
    }

    /**
     * @Route("/categorie/{id}", name="front_categorie_show", methods={"GET"})
     */
    public function show(Categorie $categorie, CategorieRepository $categorieRepository): Response
    {
        return $this->render('front/categorie/show.html.twig', [
            'categories' => $categorieRepository->findAll(),
            'categorie' => $categorie,
        ]);
    }
}
