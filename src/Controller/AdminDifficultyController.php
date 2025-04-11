<?php

namespace App\Controller;

use App\Entity\Difficulty;
use App\Form\DifficultyType;
use App\Repository\DifficultyRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/admin/difficulty')]
final class AdminDifficultyController extends AbstractController
{
    #[Route('', name: 'app_admin_difficulty')]
    public function index(DifficultyRepository $difficultyRepository): Response
    {
        $difficulties = $difficultyRepository->findAll();

        return $this->render('admin_difficulty/index.html.twig', [
            'difficulties' => $difficulties,
        ]);
    }

    #[Route('/add', name: 'app_admin_difficulty_add')]
    public function add(EntityManagerInterface $entityManager, Request $request): Response
    {
        $difficulty = new Difficulty();

        $form = $this->createForm(DifficultyType::class, $difficulty);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($difficulty);
            $entityManager->flush();

            return $this->redirectToRoute('app_admin_difficulty');
        }

        return $this->render('admin_difficulty/add-difficulty.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/edit/{id}', name: 'app_admin_difficulty_edit', requirements: ['id' => '\d+'])]
    public function edit(EntityManagerInterface $entityManager, Request $request, Difficulty $difficulty): Response
    {
        $form = $this->createForm(DifficultyType::class, $difficulty);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($difficulty);
            $entityManager->flush();

            return $this->redirectToRoute('app_admin_difficulty');
        }

        return $this->render('admin_difficulty/edit-difficulty.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/remove/{id}', name: 'app_admin_difficulty_remove', requirements: ['id' => '\d+'])]
    public function remove(EntityManagerInterface $entityManager, Difficulty $difficulty): Response
    {
        $entityManager->remove($difficulty);
        $entityManager->flush();

        return $this->redirectToRoute('app_admin_difficulty');
    }
}
