<?php

namespace App\Controller;
use App\Entity\Reservations;
use App\Entity\Facture;
use App\Form\FactureType;
use App\Repository\FactureRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Twig\Environment;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use App\Service\EmailService;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mime\Attachment;
use Symfony\Component\Mime\Part\DataPart;


use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

#[Route('/facture')]
class FactureController extends AbstractController
{
    #[Route('/', name: 'app_facture_index', methods: ['GET'])]
    public function index(FactureRepository $factureRepository, Request $request): Response
    {
        $sort = $request->query->get('sort', 'asc');

        if (!in_array($sort, ['asc', 'desc'])) {
            // Si la valeur du paramètre sort n'est pas valide, utilisez la valeur par défaut 'asc'
            $sort = 'asc';
        }

        $factures = ($sort === 'asc')
            ? $factureRepository->findAllOrderByDatePaiementAsc()
            : $factureRepository->findAllOrderByDatePaiementDesc();

        return $this->render('facture/index.html.twig', [
            'factures' => $factures,
            'sort' => $sort,
        ]);
    }

    #[Route('/new', name: 'app_facture_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $facture = new Facture();
        $form = $this->createForm(FactureType::class, $facture);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
           
            
            $entityManager->persist($facture);
            $entityManager->flush();

            return $this->redirectToRoute('app_facture_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('facture/new.html.twig', [
            'facture' => $facture,
            'form' => $form,
        ]);
    }

    #[Route('/{idFacture}', name: 'app_facture_show', methods: ['GET'])]
    public function show(Facture $facture): Response
    {
        return $this->render('facture/show.html.twig', [
            'facture' => $facture,
        ]);
    }

    #[Route('/{idFacture}/edit', name: 'app_facture_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Facture $facture, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(FactureType::class, $facture);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_facture_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('facture/edit.html.twig', [
            'facture' => $facture,
            'form' => $form,
        ]);
    }

    #[Route('/{idFacture}', name: 'app_facture_delete', methods: ['POST'])]
    public function delete(Request $request, Facture $facture, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$facture->getIdFacture(), $request->request->get('_token'))) {
            $entityManager->remove($facture);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_facture_index', [], Response::HTTP_SEE_OTHER);
    }
    
    #[Route('/facture/{idFacture}/generate-pdf', name: 'generate_pdf', methods: ['GET'])]
    public function generatePdf(Facture $facture): Response
    {
        // Get associated reservation
        $reservation = $facture->getNumres();
    
        // Create TCPDF instance
        $pdf = new \TCPDF();
    
        // Set PDF content
        $pdf->AddPage(); // Add a new page to the PDF
    
        // Add image to the PDF
        $imagePath = $this->getParameter('kernel.project_dir') . '/public/images/logo.jpg';
        $pdf->Image($imagePath, 10, 10, 50);
    
        // Center the title
        $pdf->SetTextColor(0, 0, 128);
        $pdf->SetXY(40, 10);
        $pdf->SetFont('helvetica', 'B', 30);
        $pdf->Cell(0, 10, '', 0, 1);
        $pdf->Cell(0, 30, 'Facture', 0, 1, 'C');
        $pdf->SetTextColor(0, 0, 0); // Set text color back to black
        $pdf->SetFont('helvetica', '', 14);
        $pdf->Ln(10);
    
        // Add other information
        $pdf->SetXY(10, 60);
        $pdf->Cell(0, 10, 'Numéro de Facture: ' . $facture->getNumfacture(), 0, 1);
        $pdf->Ln(5);
        $pdf->Cell(0, 10, 'Montant de la Facture: ' . $facture->getMontantFacture(), 0, 1);
        $pdf->Ln(5);
        $pdf->Cell(0, 10, 'Date de Paiement: ' . $facture->getDatePaiement(), 0, 1);
        $pdf->Ln(10);
    
        // Add reservation information if available
        if ($reservation instanceof Reservations) {
            // Add a section for reservation information
            $pdf->SetTextColor(0, 0, 128); // Set text color to blue
            $pdf->SetFont('helvetica', 'B', 16); // Set font size and style
            $pdf->Cell(0, 10, 'Informations de la Réservation:', 0, 1, 'L'); // Align left
            $pdf->Ln(5);
    
            // Set font back to the default
            $pdf->SetTextColor(0, 0, 0);
            $pdf->SetFont('helvetica', '', 14); // Set font size
    
            // Add reservation details
            $pdf->Cell(0, 10, 'Numéro de Réservation: ' . $reservation->getIdreservation(), 0, 1, 'L'); // Align left
            $pdf->Ln(5);
            $pdf->Cell(0, 10, 'Nom du Client: ' . $reservation->getNomclient(), 0, 1, 'L'); // Align left
            $pdf->Ln(5);
            $pdf->Cell(0, 10, 'Nombre de Personnes: ' . $reservation->getNombrepersonnes(), 0, 1, 'L'); // Align left
            $pdf->Ln(5);
            $pdf->Cell(0, 10, 'Date de Début: ' . $reservation->getDatedebut()->format('Y-m-d'), 0, 1, 'L'); // Align left
            $pdf->Ln(5);
            $pdf->Cell(0, 10, 'Date de Fin: ' . $reservation->getDatefin()->format('Y-m-d'), 0, 1, 'L'); // Align left
            $pdf->Ln(5);
            $pdf->Cell(0, 10, 'Mode de Paiement: ' . $reservation->getModePaiement(), 0, 1, 'L'); // Align left
            $pdf->Ln(5);
            $pdf->Cell(0, 10, 'Type d\'Hébergement: ' . $reservation->getTypehebergement(), 0, 1, 'L'); // Align left
            $pdf->Ln(5);
            $pdf->Cell(0, 10, 'Type d\'Activité: ' . $reservation->getTypeactivite(), 0, 1, 'L'); // Align left
            $pdf->Ln(5);
            $pdf->Cell(0, 10, 'Numéro de Téléphone: ' . $reservation->getNumtel(), 0, 1, 'L'); // Align left
            $pdf->Ln(10);
        }
    
        // Output the PDF content
        $lineY = $pdf->GetY() + 20; // Adjust the value as needed
    $pdf->Line(10, $lineY, 200, $lineY);
        $pdfContent = $pdf->Output('facture.pdf', 'S');
    
        // Create a Symfony Response with PDF content
        $response = new Response($pdfContent);
    
        // Set headers for PDF download
        $response->headers->set('Content-Type', 'application/pdf');
        $response->headers->set('Content-Disposition', 'attachment; filename="facture.pdf"');
    
        return $response;
    }
    
    
    #[Route('/facture/{idFacture}/send-email', name: 'send_email', methods: ['POST','GET'])]
    public function SendEmail(Facture $facture, MailerInterface $mailer, Request $request): Response
    {
        // Generate PDF content
        $pdfContent = $this->generatePdf($facture);
    
        // Save PDF to a temporary file
        $tmpFilePath = tempnam(sys_get_temp_dir(), 'facture');
        file_put_contents($tmpFilePath, $pdfContent);
    
        // Get email address from the user or use a predefined one
        $emailDestinataire = $request->request->get('email');
     try{
        // Create and send the email
        $email = (new Email())
        ->from('nawras.saied@esprit.tn') // Update with your sender email
        ->to($emailDestinataire)
        ->subject('Facture')
        ->text('Veuillez trouver ci-joint votre facture.')
        ->attachFromPath($tmpFilePath, 'facture.pdf');
    
        $mailer->send($email);
    
        // Remove the temporary file
        unlink($tmpFilePath);
    
        return new Response('Facture envoyée par e-mail avec succès.');}
    
catch (\Exception $e) {
    // Log or print the exception message
    // Example: $this->logger->error('Error sending email: ' . $e->getMessage());
    return new Response('Failed to send email: ' . $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);}
}
    
}