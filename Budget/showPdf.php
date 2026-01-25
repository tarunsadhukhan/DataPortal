<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<?php

//Retrieving request parameters
$selectedDate = $_REQUEST['selectDate'];
$convertedDate = date('d.m.Y', strtotime($selectedDate));   //Converting to required date format based on folder exist
$searchString = $_REQUEST['searchString'];

$searchString = "mis0001.prn";
$dt2=$_GET["datp"];

$yarn1=$_GET["dept"];
$searchString=$yarn1;

$dat = new DateTime($dt2);
$dt3= $dat->format('Y-m-d');
$dt1=$dt3;
$date = DateTime::createFromFormat("Y-m-d", $dt1);
$y=$date->format("Y");
$m=$date->format("M");
$da=$date->format("d").'.'.$date->format("m").'.'.$date->format("Y");

$myFile = "reports/" . $convertedDate . "/" . $searchString . ".PRN";  //Forming path basedon selected date and search report

$myFile ="d://daily Reports//2017//aug2017/01.08.2017//MIS0001.PRN";
$fl="d://daily Reports//".$y."//".$m.$y."//".$da."//".$yarn1;
$myFile =$fl;
//ECHO $myFile;

//echo $dt2;

$n=file_exists($fl);

if ($n==0) {
echo $yarn1." For Dated ".$dt2." Not Prepared yet";
exit;

}
//Getting contents of created path
$orig = file_get_contents($myFile);
//Converting contents to html
$a = htmlentities($orig);
//Stroing HTML format output in string
$final_ouput = '<html><title>' . $searchString . '</title><body><code><pre>' . nl2br($a) . '</code></pre></body></html>';
file_put_contents("storeData.html", $final_ouput);  //Keeping files in other html page to get max length of each line
//Getting contents of newly created file
$newlyCreatedFile = file_get_contents("storeData.html");
//Lines Splitting based on break
$lines = explode('<br />', $newlyCreatedFile);
$line_max_count = 0;   //Initialize variable to store max length of each line
//Iterate all lines
for ($i = 0; $i < count($lines); $i++) {
    $line_length = strlen($lines[$i]);
    if ($line_length > $line_max_count) {
        $line_max_count = $line_length;  //Storing max length of all lines
    }
}

//Included DOMPDF to Convert HTML to PDF
require_once "./dompdf/autoload.inc.php";

use Dompdf\Dompdf;

//Stroing content in HTML format
$html = ob_get_clean();
if (get_magic_quotes_gpc())
    $html = stripslashes($html);
$dompdf = new DOMPDF();
$html = <<<ENDHTML
  $final_ouput
ENDHTML;

//Based on max line length setting Page size
if ($line_max_count > 211) {
    $dompdf->set_paper('legal', 'landscape');
    $dompdf->set_paper(array(0, 0, 1750.00, 1100.00), "legal");
} else if ($line_max_count > 120 && $line_max_count <= 210) {
    $dompdf->set_paper('legal', 'landscape');
    $dompdf->set_paper(array(0, 0, 1200.00, 1000.00), "legal");
} else {
    $dompdf->set_paper('legal', 'portrait');
    $dompdf->set_paper(array(0, 0, 700.00, 1200.00), "legal");
}

//Loading HTML content in Pdf
$dompdf->load_html($html);
$dompdf->render();

//$dompdf->stream($searchString.".pdf");

//if ($searchString == "DAILREPT") {
//    $displayValue = "Daily Summary [DLY0001]";
//} else if ($searchString == "CATRPT") {
//    $displayValue = "Hands Complement [HND/07]";
//} else if ($searchString == "MACHRPT1") {
//    $displayValue = "Daily Machine Comp. [HND0034]";
//} else if ($searchString == "HANDRPT1") {
//    $displayValue = "Daily Hands Comp. Details [HND03/1]";
//} else if ($searchString == "BEAMSUM2") {
//    $displayValue = "Beaming Production M/c Summary [BMG0005]";
//} else if ($searchString == "BEAMWEAV") {
//    $displayValue = "Daily Beaming and Weaving Cuts [BMG0006]";
//} else if ($searchString == "BEAMREPT") {
//    $displayValue = "Beaming Production [BMG/02]";
//} else if ($searchString == "HYLTREPT") {
//    $displayValue = "Daily Hy/Lt (At Bale Point) [HYLT/01]";
//} else if ($searchString == "FINIREPT") {
//    $displayValue = "Finishing [FNG0001]";
//} else if ($searchString == "WINDREPT") {
//    $displayValue = "Winders Productivity Statement";
//} else if ($searchString == "JUTEREPT") {
//    $displayValue = "Daily Jute Quality Wise [JUT/03]";
//} else if ($searchString == "JUTERPT1") {
//    $displayValue = "Daily Jute With Value [JUT/07]";
//} else if ($searchString == "SPG0001") {
//    $displayValue = "Daily Spinning Production [SPG0002]";
//} else if ($searchString == "WVG0001") {
//    $displayValue = "Weaving Production [WVG0002]";
//} else if ($searchString == "MCHRPT01") {
//    $displayValue = "Weekly Running Machine and Production/Machine/Day [MACHRPT01] ";
//} else if ($searchString == "HANDEXEC") {
//    $displayValue = "Daily Excess Hands Comp. Details [HND03/2]";
//}

$dompdf->stream($searchString . '.pdf', array('Attachment' => 0));
?>