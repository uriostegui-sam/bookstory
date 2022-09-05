<?php

namespace App\Controller\Front;

use App\Entity\Utilisateur;
use App\Form\UtilisateurType;
use App\Repository\CategorieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UtilisateurController extends AbstractController
{
    /**
     * @Route("/mon-profile", name="utilisateur_profile")
     * @IsGranted("ROLE_USER")
     */
    public function profile(
        CategorieRepository $categorieRepository
    ): Response {
        return $this->render('front/utilisateur/profile.html.twig', [
            'categories' => $categorieRepository->findAll(),
        ]);
    }

    /**
     * @Route("/revendeur/{id}", name="revendeur_show", methods={"GET"})
     */
    public function showRevendeur(
        CategorieRepository $categorieRepository,
        Utilisateur $revendeur
    ): Response {
        if ($this->getUser() && $this->getUser()->getEmail() === $revendeur->getEmail()) {
            return $this->redirectToRoute('app_front_user_profile');
        }

        return $this->render('front/utilisateur/showRevendeur.html.twig', [
            'categories' => $categorieRepository->findAll(),
            'revendeur' => $revendeur,
        ]);
    }

    /**
     * @Route("/new", name="utilisateur_new", methods={"GET", "POST"})
     */
    public function new(
        CategorieRepository $categorieRepository,
        Request $request,
        EntityManagerInterface $entityManager,
        UserPasswordHasherInterface $userPasswordHasherInterface
    ): Response {
        $utilisateur = new Utilisateur();
        $form = $this->createForm(UtilisateurType::class, $utilisateur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $utilisateur->setRoles(['ROLE_USER']);
            $plainPassword = $form->get('password')->getData();
            $hashedPassword = $userPasswordHasherInterface->hashPassword($utilisateur, $plainPassword);
            $utilisateur->setPassword($hashedPassword);
            $entityManager->persist($utilisateur);
            $entityManager->flush();

            return $this->redirectToRoute('front_home', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('front/utilisateur/new.html.twig', [
            'categories' => $categorieRepository->findAll(),
            'utilisateur' => $utilisateur,
            'form' => $form,
        ]);
    }
}
