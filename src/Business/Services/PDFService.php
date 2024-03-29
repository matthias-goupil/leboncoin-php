<?php
namespace TheFeed\Business\Services;

use TCPDF;
class PDFService
{
    private $pdf;

    public function __construct()
    {
        $this->pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        $this->pdf->SetCreator(PDF_CREATOR);
        $this->pdf->SetAuthor('Your Name');
        $this->pdf->SetTitle('Document Title');
        $this->pdf->SetSubject('Document Subject');
        $this->pdf->SetKeywords('Keywords');
    }

    public function generatePdf($html)
    {
        // Ajoutez le contenu HTML au PDF
        $this->pdf->AddPage();
        $this->pdf->SetPrintHeader(false); // désactiver l'en-tête
        $this->pdf->SetPrintFooter(false); // désactiver le pied de page
        $this->pdf->SetHeaderData('', 0, '', ''); // Désactiver l'en-tête
        $this->pdf->writeHTML($html, true, false, true, false, '');

        // Génère le PDF et retourne son contenu
        return $this->pdf->Output('document.pdf', 'S');
    }
}