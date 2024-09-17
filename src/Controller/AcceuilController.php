<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AcceuilController extends AbstractController
{
    #[Route('/acc', name: 'app')]
    public function index(): Response
    {
        return $this->render('AcceuilRes.html.twig', [
            'controller_name' => 'AcceuilController',
        ]);
    }
    #[Route('/admin', name: 'app_acceuil')]
    public function admin(): Response
    {
        return $this->render('baseback.html.twig', [
            'controller_name' => 'AcceuilController',
        ]);
    }
}
