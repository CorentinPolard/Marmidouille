<?php

namespace App\Controller;

use App\Entity\Comment;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/comment')]
final class CommentController extends AbstractController
{
    #[Route('', name: 'app_comment')]
    public function index(): Response
    {
        return $this->redirectToRoute('app_recipes');
    }

    #[Route('/comment/{id}', name: 'app_comment_delete')]
    public function removeComment(EntityManagerInterface $entityManager, Comment $comment): Response
    {
        $recipeId = $comment->getRecipe()->getId();
        if ($this->getUser() == $comment->getUser()) {
            $entityManager->remove($comment);
            $entityManager->flush();
            return $this->redirectToRoute('app_recipe_show', ['id' => $recipeId]);
        }

        return $this->redirectToRoute('app_logout');
    }
}
