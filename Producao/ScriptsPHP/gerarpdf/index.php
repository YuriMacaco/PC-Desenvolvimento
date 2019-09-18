<?php
//REFERENCIAR O ARQUIVO COM A CLASSE DE GERAÇÃO DE PDF
include 'pdf/mpdf.php';


$saida = 
        "<html>
            <body>
                <h1>MEU PRIMEIRO PDF</h1>
                <ul>
                    <li>PHP</li>
                    <li>HTML</li>
                    <li>PDF</li>
                </ul>
                <h5><i>Mais em http://www.programatche.net</h5>
            </body>
        </html>
        ";

$arquivo = "Exemplo01.pdf";

$mpdf = new mPDF();
$mpdf->WriteHTML($saida);
/*
 * F - salva o arquivo NO SERVIDOR
 * I - abre no navegador E NÃO SALVA
 * D - chama o prompt E SALVA NO CLIENTE
 */

$mpdf->Output($arquivo, 'D');

echo "PDF GERADO COM SUCESSO";


?>
