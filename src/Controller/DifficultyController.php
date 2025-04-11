<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class DifficultyController extends AbstractController
{
    #[Route('/difficulty', name: 'app_difficulty')]
    public function index(): Response
    {
        return $this->render('difficulty/index.html.twig', [
            'controller_name' => 'DifficultyController',
        ]);
    }
}
