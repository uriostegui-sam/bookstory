<?php

namespace App\Controller\Admin;

use App\Repository\LivreRepository;
use App\Repository\AuteurRepository;
use App\Repository\CategorieRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminController extends AbstractController
{
    /**
     * @Route("/admin/", name="app_admin_admin")
     */
    public function index(
        AuteurRepository $auteurRepository,
        CategorieRepository $categorieRepository,
        LivreRepository $livreRepository
    ): Response {

        $countLivres = count($livreRepository->findAll());
        $countCategories = count($categorieRepository->findAll());
        $countAuteurs = count($auteurRepository->findAll());

        return $this->render('admin/admin/index.html.twig', [
            'livres' => $countLivres,
            'categories' => $countCategories,
            'auteurs' => $countAuteurs,
        ]);
    }
}
