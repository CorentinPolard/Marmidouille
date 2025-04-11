<?php

namespace App\Controller;

use App\Entity\Ingredient;
use App\Form\IngredientType;
use App\Repository\IngredientRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/admin/ingredient')]
final class AdminIngredientController extends AbstractController
{
    #[Route('', name: 'app_admin_ingredient')]
    public function index(IngredientRepository $ingredientRepository): Response
    {
        $ingredients = $ingredientRepository->findAll();

        return $this->render('admin_ingredient/index.html.twig', [
            'ingredients' => $ingredients,
        ]);
    }

    #[Route('/add', name: 'app_admin_ingredient_add')]
    public function add(EntityManagerInterface $entityManager, Request $request): Response
    {
        $ingredient = new Ingredient();

        $form = $this->createForm(IngredientType::class, $ingredient);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($ingredient);
            $entityManager->flush();

            return $this->redirectToRoute('app_admin_ingredient');
        }

        return $this->render('admin_ingredient/add-ingredient.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/edit/{id}', name: 'app_admin_ingredient_edit', requirements: ['id' => '\d+'])]
    public function edit(EntityManagerInterface $entityManager, Request $request, Ingredient $ingredient): Response
    {
        $form = $this->createForm(IngredientType::class, $ingredient);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($ingredient);
            $entityManager->flush();

            return $this->redirectToRoute('app_admin_ingredient');
        }

        return $this->render('admin_ingredient/edit-ingredient.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/remove/{id}', name: 'app_admin_ingredient_delete', requirements: ['id' => '\d+'])]
    public function delete(EntityManagerInterface $entityManager, Ingredient $ingredient): Response
    {
        $entityManager->remove($ingredient);
        $entityManager->flush();

        return $this->redirectToRoute('app_admin_ingredient');
    }
}
