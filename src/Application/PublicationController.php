<?php

namespace TheFeed\Application;

use Framework\Application\Controller;

use http\Env\Response;

use TheFeed\Business\Services\PDFService;

use Symfony\Component\HttpFoundation\Request;
use TheFeed\Business\Entity\User;
use TheFeed\Business\Exception\ServiceException;
use Symfony\Component\HttpFoundation\Response;

class PublicationController extends Controller
{

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


    public function test() {
        $userService = $this->container->get('utilisateur_service');
        var_dump($userService->test());
        return new \Symfony\Component\HttpFoundation\Response("teest");
    }

    public function feedPDF() {
        // Génère le contenu HTML pour le PDF
        $servicePDF = $this->container->get('pdf_generator');
        $html =  $this->feed()->getContent();
        $html = str_replace('<script', '<!-- script', $html); // masque l'ouverture de la balise script
        $html = str_replace('</script>', '</script -->', $html); // masque la fermeture de la balise script
        $html = preg_replace('#<!--\s*script\s*(.*?)\s*-->\s*#is', '', $html); // supprime toutes les balises script

        // Génère le PDF et retourne la réponse
        return new Response(
            $servicePDF->generatePDF($html),
            200,
            array(
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="document.pdf"'
            )
        );
    }
}