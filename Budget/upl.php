<?php
session_start();
//include("db.php");
$servername = "13.232.34.218";
$username = "Tarun";
$password = "Tarun@1234";
$dbname = "vowdev_sw";
$conn = new mysqli($servername, $username, $password, $dbname);

require 'vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

// Create new Spreadsheet object

?>
<?php
require_once('calendar/classes/tc_calendar.php');




if(isset($_POST["template"]))

{

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();
$sheet->setCellValue('A1', 'OGP Created')
      ->setCellValue('B1','Un Verified Meters')
      ->setCellValue('C1','Verified Meters')
      ->setCellValue('D1','Sub Div Code')
      ->setCellValue('E1','Sub Div Name');
/*
foreach($results as $key => $result_data) {
    $x = $key + 2;
    $spreadsheet->setActiveSheetIndex(0)
        ->setCellValue("A$x", $result_data['OGP_Created'])
        ->setCellValue("B$x", $result_data['Un_Verified_Meters'])
        ->setCellValue("C$x", $result_data['Verified_Meters'])
        ->setCellValue("D$x", $result_data['Sub_Div_Code'])
        ->setCellValue("E$x", $result_data['Sub_Div_Name']);
}
*/
$filename = 'Output.xlsx'; //save our workbook as this file name
// Redirect output to a client’s web browser (Xlsx)
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="'.$filename.'"');
header('Cache-Control: max-age=0');
// If you're serving to IE 9, then the following may be needed
header('Cache-Control: max-age=1');

$writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
$writer->save('php://output');



}
if(isset($_POST["export"]))

{

 
}
?>


<head>
<script language="javascript" src="calendar.js"></script>
</head>

<head>
<link rel="stylesheet" type="text/css" href="style.css">
</head> 


 
 <title>jQuery UI Datepicker - Dates in other months</title>
  <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
  <link rel="stylesheet" href="/resources/demos/style.css">
  <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
  <script>
  $( function() {
    $( "#datepicker" ).datepicker({
      
	  showOtherMonths: true,
      selectOtherMonths: true
	  
	  
    });
	
	      $( "#datepicker" ).datepicker( "option", "dateFormat", "dd-mm-yy" );

		      $( "#anim" ).on( "change", function() {
      $( "#datepicker" ).datepicker( "option", "showAnim", "fadeIn" );
    });
		  
  } );
  </script>

<?php
	//	$vv=$_GET["value"];
		



				?>

		<br></br>

<p></p>



<br></br>

 
 



 
       <head>
     <title>Export Data From API/SQL Server to MYsql using PHP</title>
     <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />
   </head>

</html>
   <body>
     <div class="container">
      <br />
      <h3 align="center">Export Data From Mysql to Excel using PHPSpreadsheet</h3>
      <br />
        <div class="panel panel-default">
          <div class="panel-heading">
            <form method="post">
              <div class="row">
                <div class="col-md-6">From Date  : <input type="text"  autocomplete=off;   name="date1" id="datepicker"></div>
                <div class="col-md-4">
                  
				      <input type="file" name="file" />

                 </div>
                <div class="col-md-2">
					 <input type="submit" name="template" value="Template" />

                  <input type="submit" name="export" class="btn btn-primary btn-sm" value="Submit" />
                </div>
              </div>
          <div class="panel-body">
          <div class="table-responsive">
           <table class="table table-striped table-bordered">
                <tr>
                  <th>Item Code</th>
                  <th>Item Desc</th>
                  <th>Uom Code</th>
                  <th>Location</th>
                </tr>
   
              </table>
          </div>
          </div>
        </div>
     </div>
      <br />
      <br />
  </body>
</html>
