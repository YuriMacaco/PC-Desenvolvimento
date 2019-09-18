<?php
use Dompdf\Dompdf;
require_once 'dompdf/autoload.inc.php';

function  Enviar1()
{
// instantiate and use the dompdf class
$dompdf = new Dompdf();
$dompdf->loadHtml($yuri);

// Render the HTML as PDF
$dompdf->render();

// Output the generated PDF to Browser
$dompdf->stream("NOME_DO_PDF.pdf",array("Attachment" => true));

}

Enviar1();
?>