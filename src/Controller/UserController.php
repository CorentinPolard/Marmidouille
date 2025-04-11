<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\RecipeRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/user')]
final class UserController extends AbstractController
{
    // #[Route('/user', name: 'app_user')]
    // public function index(): Response
    // {
    //     return $this->render('user/index.html.twig', [
    //         'controller_name' => 'UserController',
    //     ]);
    // }

    // #[Route('/{user}', name: 'app_recipe_user')]
    // public function showUserRecipe(RecipeRepository $recipeRepository, User $user): Response
    // {
    //     $recipes = $recipeRepository->findBy(['author' => $user]);

    //     return $this->render('recipe/user-recipe.html.twig', [
    //         'recipes' => $recipes,
    //         'user' => $user,
    //     ]);
    // }
}
