<?php

namespace App\Controller;

use App\Entity\Step;
use App\Entity\User;
use App\Entity\Recipe;
use App\Entity\Comment;
use App\Form\RecipeType;
use App\Form\CommentType;
use App\Entity\Ingredient;
use App\Form\QuantityType;
use App\Entity\IngredientQuantity;
use App\Repository\RecipeRepository;
use App\Repository\DifficultyRepository;
// use Dom\Comment;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/recipe')]
final class RecipeController extends AbstractController
{
    #[Route('', name: 'app_recipes')]
    public function index(RecipeRepository $recipeRepository): Response
    {
        $recipes = $recipeRepository->findAll();

        return $this->render('recipe/index.html.twig', [
            'recipes' => $recipes,
        ]);
    }

    #[Route('/show', name: 'app_show')]
    public function showRedirect(): Response
    {
        return $this->redirectToRoute('app_recipes');
    }

    #[Route('/show/{id}', name: 'app_recipe_show', requirements: ['id' => '\d+'])]
    public function showRecipe(#[MapEntity(id: 'id')] Recipe $recipe, EntityManagerInterface $entityManager, Request $request): Response
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

        return $this->render('recipe/singleRecipe.html.twig', [
            'recipe' => $recipe,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/create-recipe', name: 'app_create_recipe')]
    public function createRecipe(EntityManagerInterface $entityManager, Request $request): Response
    {
        $recipe = new Recipe();
        $recipe->setAuthor($this->getUser());

        $ingredient = new IngredientQuantity();
        $recipe->addIngredientQuantity($ingredient);

        $step = new Step();
        $recipe->addStep($step);

        $form = $this->createForm(RecipeType::class, $recipe);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager->persist($recipe);
            $entityManager->flush();

            return $this->redirectToRoute('app_create_recipe');
        }

        return $this->render('recipe/create-recipe.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/edit', name: 'app_edit')]
    public function editRedirect(): Response
    {
        return $this->redirectToRoute('app_recipes');
    }

    #[Route('/edit/{id}', name: 'app_edit_recipe')]
    public function editRecipe(EntityManagerInterface $entityManager, Recipe $recipe, Request $request): Response
    {
        if ($this->getUser() == $recipe->getAuthor()) {
            $form = $this->createForm(RecipeType::class, $recipe);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $entityManager->persist($recipe);
                $entityManager->flush();

                return $this->redirectToRoute('app_recipe_show', ['id' => $recipe->getId()]);
            }

            return $this->render('recipe/edit-recipe.html.twig', [
                'form' => $form->createView(),
            ]);
        }

        return $this->redirectToRoute('app_logout');
    }

    #[Route('/delete', name: 'app_delete')]
    public function deleteRedirect(): Response
    {
        return $this->redirectToRoute('app_recipes');
    }

    #[Route('/delete/{id}', name: 'app_delete_recipe')]
    public function deleteRecipe(EntityManagerInterface $entityManager, Recipe $recipe): Response
    {
        if ($this->getUser() == $recipe->getAuthor()) {
            $entityManager->remove($recipe);
            $entityManager->flush();
            return $this->redirectToRoute('app_recipes');
        }

        return $this->redirectToRoute('app_logout');
    }
}
