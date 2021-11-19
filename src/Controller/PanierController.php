<?php

namespace App\Controller;

use App\Entity\Livre;
use App\Form\PaymentType;
use App\Repository\LivreRepository;
use App\Repository\UtilisateurRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PanierController extends AbstractController
{
    /**
     * @Route("/panier", name="panier")
     */
    public function index(SessionInterface $session, LivreRepository $livreRepository): Response
    {
        $panier = $session->get('panier', []);
        $panierWData = [];

        foreach ($panier as $id => $quantite) {
            $panierWData[] = [
                'livre' => $livreRepository->find($id),
                'quantite' => $quantite
            ];
        }

        $total = 0;

        foreach ($panierWData as $item) {
            $totalItem = $item['livre']->getPrix() * $item['quantite'];
            $total += $totalItem;
        }
        $session->set('total', $total);
        return $this->render('panier/index.html.twig', [
            'items' => $panierWData,
            'total' => $total,
        ]);
    }

    /**
     * @Route("/panier/add/{id}", name="panier_add")
     */
    public function panier(SessionInterface $session, $id)
    {
        $panier = $session->get('panier', []);
        if (!empty($panier[$id])) {
            $panier[$id]++;
        } else {
            $panier[$id] = 1;
        }
        $session->set('panier', $panier);

        return $this->redirectToRoute('panier');
    }

    /**
     * @Route("/panier/remove/{id}", name="panier_remove")
     */
    public function remove($id, SessionInterface $session)
    {
        $panier = $session->get('panier', []);

        if (!empty($panier[$id])) {
            unset($panier[$id]);
        }

        $session->set('panier', $panier);

        return $this->redirectToRoute("panier");
    }
}
