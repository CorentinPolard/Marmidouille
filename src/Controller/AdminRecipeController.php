<?php

namespace App\Controller;

use App\Entity\Step;
use App\Entity\Recipe;
use App\Entity\Comment;
use App\Form\RecipeType;
use App\Form\CommentType;
use App\Entity\IngredientQuantity;
use App\Repository\RecipeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/admin/recipe')]
final class AdminRecipeController extends AbstractController
{
    #[Route('', name: 'app_admin_recipe')]
    public function index(RecipeRepository $recipeRepository): Response
    {
        $recipes = $recipeRepository->findAll();

        return $this->render('admin_recipe/index.html.twig', [
            'recipes' => $recipes,
        ]);
    }

    #[Route('/add', name: 'app_admin_recipe_add')]
    public function add(EntityManagerInterface $entityManager, Request $request): Response
    {
        $recipe = new Recipe();
        $recipe->setAuthor($this->getUser());

        $recipe->addIngredientQuantity(new IngredientQuantity());
        $recipe->addStep(new Step());

        $form = $this->createForm(RecipeType::class, $recipe);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($recipe);
            $entityManager->flush();

            return $this->redirectToRoute('app_admin_recipe');
        }

        return $this->render('admin_recipe/add-recipe.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/show/{id}', name: 'app_admin_recipe_show', requirements: ['id' => '\d+'])]
    public function show(EntityManagerInterface $entityManager, Request $request, Recipe $recipe): Response
    {
        $comment = new Comment();

        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $comment->setRecipe($recipe);
            $comment->setUser($this->getUser());

            $entityManager->persist($comment);
            $entityManager->flush();

            return $this->redirectToRoute('app_recipe_show', ['id' => $recipe->getId()]);
        }

        return $this->render('admin_recipe/show-recipe.html.twig', [
            'recipe' => $recipe,
            'form' => $form->createView(),
        ]);
    }

    #[Route('edit/{id}', name: 'app_admin_recipe_edit', requirements: ['id' => '\d+'])]
    public function edit(EntityManagerInterface $entityManager, Request $request, Recipe $recipe): Response
    {
        $form = $this->createForm(RecipeType::class, $recipe);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($recipe);
            $entityManager->flush();

            return $this->redirectToRoute('app_admin_recipe');
        }

        return $this->render('admin_recipe/edit-recipe.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('remove/{id}', name: 'app_admin_recipe_remove', requirements: ['id' => '\d+'])]
    public function remove(EntityManagerInterface $entityManager, Recipe $recipe): Response
    {
        $entityManager->remove($recipe);
        $entityManager->flush();

        return $this->redirectToRoute('app_admin_recipe');
    }
}
