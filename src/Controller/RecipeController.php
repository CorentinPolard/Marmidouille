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

    #[Route('/user/{user}', name: 'app_recipe_user')]
    public function showUserRecipe(RecipeRepository $recipeRepository, User $user): Response
    {
        $recipes = $recipeRepository->findBy(['author' => $user]);

        return $this->render('recipe/user-recipe.html.twig', [
            'recipes' => $recipes,
            'user' => $user,
        ]);
    }

    #[Route('/difficulty/{difficultyName}', name: 'app_recipe_difficulty')]
    public function showDifficultyRecipe(DifficultyRepository $difficultyRepository, RecipeRepository $recipeRepository, string $difficultyName): Response
    {
        $difficulty = $difficultyRepository->findOneBy(['label' => $difficultyName]);
        $recipes = $recipeRepository->findBy(['difficulty' => $difficulty]);

        return $this->render('recipe/difficulty-recipe.html.twig', [
            'recipes' => $recipes,
            'difficulty' => $difficulty,
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
}
