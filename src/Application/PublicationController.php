<?php

namespace TheFeed\Application;

use Framework\Application\Controller;
use TheFeed\Business\Services\PDFService;
use Symfony\Component\HttpFoundation\Request;
use TheFeed\Business\Exception\ServiceException;

class PublicationController extends Controller
{

    private PDFService $pdfGenerator;

    /**
     * @param PDFService $pdfGenerator
     */
    public function __construct(PDFService $pdfGenerator)
    {
        $this->pdfGenerator = $pdfGenerator;
    }

    public function feed() {
        $service = $this->container->get('publication_service');
        $publications = $service->getAllPublications();
        return $this->render('Publications/feed.html.twig', ["publications" => $publications]);
    }

    public function submitFeedy(Request $request) {
        $userService = $this->container->get('utilisateur_service');
            $userId = $userService->getUserId();
            $message = $request->get('message');
            $service = $this->container->get('publication_service');
            try {
                $service->createNewPublication($userId, $message);
            } catch (ServiceException $e) {
                $this->addFlash('error', $e->getMessage());
            }
            return $this->redirectToRoute('feed');
    }

    public function feedPDF() {
        // Génère le contenu HTML pour le PDF
        $html = $this->feed();

        // Génère le PDF et retourne la réponse
        return new Response(
            $this->pdfGenerator->generatePdf($html),
            200,
            array(
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="document.pdf"'
            )
        );
    }
}