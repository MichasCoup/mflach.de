<?php

session_name("blog");
session_start();

if ( !isset( $_SESSION["usr_id"] ) ) {
    header("Location: login.php");
}


/*********************************************************************************************************************************************/
/* Configuartion *****************************************************************************************************************************/
/*********************************************************************************************************************************************/

require_once("include/config.inc.php");
require_once("include/db.inc.php");
require_once('include/tcpdf/tcpdf.php');

$pdo = dbConnect();

/*********************************************************************************************************************************************/
/* Datenbank auselesen ***********************************************************************************************************************/
/*********************************************************************************************************************************************/

$sql = "SELECT blog_id, blog_headline, blog_image, blog_size, blog_content, blog_company, cat_name
        FROM blogs 
        INNER JOIN categories USING(cat_id) ";

$params = NULL;

$sql .= " ORDER BY categories.cat_name DESC";

$statement = $pdo->prepare($sql);

$statement->execute($params) OR DIE($statement-errorInfo()[2]);

$blogList = $statement->fetchAll();


/*********************************************************************************************************************************************/
/* PDF vorbereiten ***********************************************************************************************************************/
/*********************************************************************************************************************************************/


$pdfName = "Portfolio_Michael-Flach.pdf";

$header = '
Porfolio
Michael Flach
www.mflach.de';


//$footer = "Fußzeile";




//////////////////////////// Inhalt des PDFs als HTML-Code \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\


// Erstellung des HTML-Codes. Dieser HTML-Code definiert das Aussehen eures PDFs.
// tcpdf unterstützt recht viele HTML-Befehle. Die Nutzung von CSS ist allerdings
// stark eingeschränkt.

$html = '
<table cellpadding="5" cellspacing="0" style="width: 100%; ">
 <tr>
 <td>'.nl2br(trim($header)).'</td>
 </tr>
 
</table>
<br><br><br>';


$html .= "<table style='width:100%; border: 1px solid black; border-collapse: collapse;'>";

foreach($blogList AS $key => $value) {
    $html .= "
                <tr>
                    <td><img src='".$value['blog_image']."'></td>
                    <td>
                        <h2>".$value['blog_headline']."</h2>
                        <h4>".$value['cat_name']." | ".$value['blog_company']."</h4>
                        <p>".$value['blog_content']."</p>
                    </td>
                </tr>
";
}
$html .= "</table>";


//$html .= nl2br($footer);

/*********************************************************************************************************************************************/
/* Erstellung des PDF Dokuments ***********************************************************************************************************************/
/*********************************************************************************************************************************************/

$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// Dokumenteninformationen
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor("Michael Flach");
$pdf->SetTitle('Portfolio');
$pdf->SetSubject('Portfolio');


// Header und Footer Informationen
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// Auswahl des Font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// Auswahl der MArgins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

// Automatisches Autobreak der Seiten
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// Image Scale
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// Schriftart
$pdf->SetFont('helvetica', '', 10);

// Neue Seite
$pdf->AddPage();

// Fügt den HTML Code in das PDF Dokument ein
$pdf->writeHTML($html, true, false, true, false, '');

// Ausgabe der PDF
$pdf->Output($pdfName, 'I');

// PDF im Verzeichnis abspeichern:
//$pdf->Output(dirname(__FILE__).'/'.$pdfName, 'F');
//echo 'PDF herunterladen: <a href="'.$pdfName.'">'.$pdfName.'</a>';

?>