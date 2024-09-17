<?php

namespace App\Controller;

use App\Entity\Reservations;
use App\Form\AddReservationType;
use App\Repository\ReservationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;





#[Route('/reservationsajout')]
class ReservationsajoutController extends AbstractController
{
    #[Route('/', name: 'app_reservationsajout_index', methods: ['GET'])]
    public function index(ReservationRepository $reservationRepository, Request $request): Response
    {
        $sort = $request->query->get('sort');
    $reservations = $reservationRepository->findAllSortedByDate($sort);

    return $this->render('reservationsajout/index.html.twig', [
        'reservations' => $reservations,
    ]);
    }

    // ...

#[Route('/new', name: 'app_reservationsajout_new', methods: ['GET', 'POST'])]
public function new(Request $request, EntityManagerInterface $entityManager, ReservationRepository $reservationRepository): Response
{
    $reservation = new Reservations();
    $form = $this->createForm(AddReservationType::class, $reservation);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        // Vérifiez la disponibilité du type d'activité pour la saison actuelle
        $typeActivite = $reservation->getTypeactivite();
        $isTypeActiviteAvailable = $reservationRepository->isTypeActiviteAvailableForCurrentSeason($typeActivite);

        if (!$isTypeActiviteAvailable) {
            // Ajoutez un message flash d'erreur et redirigez vers la page du formulaire
            $this->addFlash('error', 'Le type d\'activité choisi n\'est pas disponible pour la saison actuelle. Veuillez choisir un autre type d\'activité.');
            
            return $this->redirectToRoute('app_reservationsajout_new');
        }

        // Si le type d'activité est disponible, persistez la réservation
        $entityManager->persist($reservation);
        $entityManager->flush();

        // Redirection vers la page d'historique des réservations avec le cinClient attribué
        return $this->redirectToRoute('app_reservationsajout_historique', [
            'cinClient' => $reservation->getCinclient(),
        ], Response::HTTP_SEE_OTHER);
    }

    return $this->renderForm('reservationsajout/new.html.twig', [
        'reservation' => $reservation,
        'form' => $form,
    ]);

}

// ...

    

    #[Route('/{idreservation}', name: 'app_reservationsajout_show', methods: ['GET'])]
    public function show(Reservations $reservation): Response
    {
        return $this->render('reservationsajout/show.html.twig', [
            'reservation' => $reservation,
        ]);
    }

    #[Route('/{idreservation}/edit', name: 'app_reservationsajout_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Reservations $reservation, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(AddReservationType::class, $reservation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_reservationsajout_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('reservationsajout/edit.html.twig', [
            'reservation' => $reservation,
            'form' => $form,
        ]);
    }

    #[Route('/{idreservation}', name: 'app_reservationsajout_delete', methods: ['POST'])]
    public function delete(Request $request, Reservations $reservation, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$reservation->getIdreservation(), $request->request->get('_token'))) {
            $entityManager->remove($reservation);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_reservationsajout_index', [], Response::HTTP_SEE_OTHER);
    }
    #[Route('/historique/{cinClient}', name: 'app_reservationsajout_historique', methods: ['GET'])]
     public function historique($cinClient, ReservationRepository $reservationRepository): Response
    {
    // Vous pouvez utiliser $cinClient pour filtrer les réservations par client
    $reservations = $reservationRepository->findBy(['cinclient' => $cinClient]);

    // Ajoutez un message flash pour indiquer que la location a été enregistrée
    $this->addFlash('success', 'Votre réservation a était effectué avec succés !');
    return $this->render('reservationsajout/historique.html.twig', [
        'reservations' => $reservations,
    ]);
    }
    #[Route('/search', name: 'app_reservationsajout_search', methods: ['GET'])]
public function search(Request $request, ReservationRepository $reservationRepository): JsonResponse
{
  
        $searchTerm = $request->query->get('search');
        
        // Use $searchTerm to filter your data and fetch the results
        $reservations = $this->getDoctrine()->getRepository(YourEntity::class)->findBySearchTerm($searchTerm);

        return $this->render('reservationsajout/_table_rows.html.twig', ['reservations' => $reservations]);
    }
    
  



}
