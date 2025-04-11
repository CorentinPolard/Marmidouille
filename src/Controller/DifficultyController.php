<?php

namespace App\Controller;

use App\Repository\RecipeRepository;
use App\Repository\DifficultyRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/difficulty')]
final class DifficultyController extends AbstractController
{
    #[Route('', name: 'app_difficulty')]
    public function index(): Response
    {
        return $this->redirectToRoute('app_recipes');
    }

    #[Route('/{difficultyName}', name: 'app_recipe_difficulty')]
    public function showDifficultyRecipe(DifficultyRepository $difficultyRepository, RecipeRepository $recipeRepository, string $difficultyName): Response
    {
        $difficulty = $difficultyRepository->findOneBy(['label' => $difficultyName]);
        $recipes = $recipeRepository->findBy(['difficulty' => $difficulty]);

        return $this->render('difficulty/difficulty-recipe.html.twig', [
            'recipes' => $recipes,
            'difficulty' => $difficulty,
        ]);
    }
}
