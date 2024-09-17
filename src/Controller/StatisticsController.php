<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse; // Add this line
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\ReservationRepository;
use App\Entity\Reservations;
use Psr\Log\LoggerInterface; 
class StatisticsController extends AbstractController
{
    #[Route('/statistics', name: 'app_statistics')]
    public function index(): Response
    {
        return $this->render('statistics/index.html.twig', [
            'controller_name' => 'StatisticsController',
        ]);
    }
    #[Route('/client-statistics/{cinClient}', name: 'app_stat', methods: ['GET'])]
   
        public function clientStatisticsChart(ReservationRepository $reservationRepository): Response
    {
        // Obtenez les statistiques pour chaque client
        $data = $reservationRepository->getClientStatistics();

        return $this->render('statistics/client_statistics_chart.html.twig', [
            'chartData' => $data,
        ]);
    }
    }
