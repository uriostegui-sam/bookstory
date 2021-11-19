<?php

namespace App\Controller\Front;

use App\Entity\Livre;
use App\Form\Livre1Type;
use App\Form\LivreARevendreType;
use App\Repository\LivreRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

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
     * @Route("/revendre-livre", name="front_livre_new", methods={"GET", "POST"})
     */
    public function revendre(Request $request, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(
            LivreARevendreType::class,
            null,
            ['utilisateur' => $this->getUser()]
        );
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($form->getData());
            $entityManager->flush();

            return $this->redirectToRoute('front_livre_show', [
                'id' => $form->getData()->getId(),
            ]);
        }

        return $this->render('front/livre/revendre.html.twig', [
            'form' => $form->createView(),
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

    /**
     * @Route("/modifier-livres/{id}", name="front_livre_edit")
     * @IsGranted("ROLE_USER")
     */
    public function edit(int $id, Request $request, LivreRepository $livreRepository, EntityManagerInterface $entityManager): Response
    {
        $livre = $livreRepository->find($id);

        if ($livre->getRevendeur()->getId() !== $this->getUser()->getId()) {
            throw new NotFoundHttpException('Le livre n\'a pas était trouvé');
        }

        $form = $this->createForm(LivreARevendreType::class, $livre, [
            'utilisateur' => $this->getUser(),
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($form->getData());
            $entityManager->flush();

            return $this->redirectToRoute('front_livre_show', [
                'id' => $form->getData()->getId(),
            ]);
        }

        return $this->render('front/livre/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/supprimer-livre/{id}", name="front_livre_delete", methods={"POST"})
     */
    public function delete(Request $request, Livre $livre, EntityManagerInterface $entityManager): Response
    {
        $entityManager->remove($livre);
        $entityManager->flush();
        if ($request->query->get('from')) {
            return $this->redirect($request->query->get('from'));
        }

        if ($request->headers->get('Referer')) {
            return $this->redirect($request->headers->get('Referer'));
        }

        return $this->redirectToRoute('utilisateur_profile');
    }
}
