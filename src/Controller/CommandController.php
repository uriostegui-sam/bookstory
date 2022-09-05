<?php

namespace App\Controller;

use App\Entity\Command;
use App\Form\CommandType;
use App\Form\PaymentType;
use App\Repository\LivreRepository;
use App\Repository\CommandRepository;
use App\Repository\CategorieRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\UtilisateurRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CommandController extends AbstractController
{
    /**
     * @Route("/command", name="command_index", methods={"GET"})
     */
    public function index(
        CategorieRepository $categorieRepository,
        CommandRepository $commandRepository
    ): Response {
        return $this->render('command/index.html.twig', [
            'categories' => $categorieRepository->findAll(),
            'commands' => $commandRepository->findAll(),
        ]);
    }

    /**
     * @Route("/command/new", name="command_new", methods={"GET", "POST"})
     */
    public function new(
        EntityManagerInterface $entityManager,
        SessionInterface $sessionInterface,
        CategorieRepository $categorieRepository,

        LivreRepository $livreRepository,
        Security $security
    ): Response {
        $command = new Command();
        $panier = $sessionInterface->get('panier');

        $user = $security->getUser();

        $command->setUtilisateur($user);
        foreach ($panier as $id => $quantite) {
            $livre = $livreRepository->findOneById($id);
            for ($i = 0; $i < $quantite; $i++) {
                $command->addProduit($livre);
            }
        }

        $entityManager->persist($command);
        $entityManager->flush();
        $sessionInterface->set('panier', []);

        return $this->redirectToRoute('utilisateur_profile', [
            'categories' => $categorieRepository->findAll(),
            'command', $command
        ], Response::HTTP_SEE_OTHER);
    }

    /**
     * @Route("/command/{id}", name="command_show", methods={"GET"})
     */
    public function show(
        Command $command,
        CategorieRepository $categorieRepository,
        CommandRepository $commandRepository,
        Security $security
    ): Response {
        $user = $security->getUser();

        $commands = $commandRepository->findAllByUser($user);

        return $this->render('command/show.html.twig', [
            'categories' => $categorieRepository->findAll(),
            'commands' => $commands,
        ]);
    }

    /**
     * @Route("/payment", name="payment")
     */
    public function payment(
        CategorieRepository $categorieRepository,
        Request $request,
        SessionInterface $sessionInterface
    ): Response {
        $totalPanier = $sessionInterface->get('total');
        $form = $this->createForm(PaymentType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {


            return $this->redirectToRoute('command_new', [
                'total' => $form->getData(),
            ]);
        }
        return $this->render(
            'panier/payment.html.twig',
            [
                'categories' => $categorieRepository->findAll(),
                'form' => $form->createView(), 'totalPanier' => $totalPanier
            ]
        );
    }
}
