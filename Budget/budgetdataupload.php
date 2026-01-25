<?php
// session_start();
// require_once('calendar/classes/tc_calendar.php');


// include 'vendor/autoload.php';

// use PhpOffice\PhpSpreadsheet\Spreadsheet;
// use PhpOffice\PhpSpreadsheet\Style\Color;
// use PhpOffice\PhpSpreadsheet\Style\Conditional;
// use PhpOffice\PhpSpreadsheet\Style\Font;



// $servername = "13.232.34.218";
// $username = "Tarun";
// $password = "Tarun@1234";
// $dbname = "vowdev_sw";

// $connect = new mysqli($servername, $username, $password, $dbname);


// if(isset($_POST["export"]))
// {
//   $file = new Spreadsheet();

// $_SESSION["favanimal"]=119;

//   $cmp=$_SESSION["favanimal"];


// $cmp=$_SESSION["favanimal"];


// $sql="select max(budget_date) mxdt from budget_manpower_table where is_active=1";
//   $resulta = $connect->query($sql);
//  while($rowa = $resulta->fetch_assoc()) 
//   {
// 	$mxdt=$rowa["mxdt"];

//   }



// $query = "select bmt.branch_code,region,bmt.branch_id,branch_name,Budget_head_count,Budget_shifts,desig_id,desig from budget_manpower_table bmt,branch_master bm, designation dsg
// where is_active=1 and bmt.branch_id=bm.branch_id and bmt.desig_id=dsg.id and bmt.company_id=".$cmp."  and bmt.budget_date='".$mxdt."' order by branch_name";



// $query = "select g.* from (
// select bmt.company_id,budget_date,bmt.branch_code,region,bmt.branch_id,branch_name,Budget_head_count,Budget_shifts,desig_id,desig from budget_manpower_table bmt,branch_master bm, designation dsg
// where is_active=1 and bmt.branch_id=bm.branch_id and bmt.desig_id=dsg.id and bmt.company_id=".$cmp."  and is_active=1  
// ) g  ,
// (
// select company_id compid, branch_id brid,max(budget_date) mxdate from budget_manpower_table bm where bm.company_id=".$cmp."  and is_active=1
// group by branch_id
// ) h where h.brid=g.branch_id and h.mxdate=g.budget_date and h.compid=g.company_id
// order by branch_name";



// $result = $connect->query($query);



//   $active_sheet = $file->getActiveSheet();

   
   



//   $active_sheet->setCellValue('A1', 'Last Upload Date ');
//   $active_sheet->setCellValue('b1', 'Branch Code');
//   $active_sheet->setCellValue('c1', 'Region ');
//   $active_sheet->setCellValue('d1', 'Site Id');
//   $active_sheet->setCellValue('e1', 'Site Name');
//   $active_sheet->setCellValue('f1', 'Budget Head Count');
//   $active_sheet->setCellValue('g1', 'Budget Shifts');
//   $active_sheet->setCellValue('h1', 'Desig Id');
//   $active_sheet->setCellValue('i1', 'Designation');

//   $active_sheet->setCellValue('j1', 'Please fill Data and Upload');
//   $active_sheet->setCellValue('j2', 'If need to add any New Data Please add at the end ');

// $file->getActiveSheet()->getStyle('j1:j2')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('DFFF00');

// $file->getActiveSheet()->getStyle('a1:i1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('9FE2BF');

// $file->getActiveSheet()->setTitle('BUDGET');

  
//   $stdat=date('d-m-Y');
 

//   $count = 2;



//  while($row = $result->fetch_assoc()) 


//   {
	  
//     $active_sheet->setCellValue('A' . $count, $row["budget_date"]);
//     $active_sheet->setCellValue('B' . $count, $row["branch_code"]);
//     $active_sheet->setCellValue('C' . $count, $row["region"]);
//     $active_sheet->setCellValue('D' . $count, $row["branch_id"]);
//     $active_sheet->setCellValue('e' . $count, $row["branch_name"]);
// 	  $active_sheet->setCellValue('f' . $count, $row["Budget_head_count"]);
// 	  $active_sheet->setCellValue('g' . $count, $row["Budget_shifts"]);
// 	  $active_sheet->setCellValue('h' . $count, $row["desig_id"]);
// 	  $active_sheet->setCellValue('i' . $count, $row["desig"]);
	
	
	

//     $count = $count + 1;
//   }

//     $file->getActiveSheet()->getColumnDimension('A')->setAutoSize(TRUE);
//     $file->getActiveSheet()->getColumnDimension('B')->setAutoSize(TRUE);
//     $file->getActiveSheet()->getColumnDimension('C')->setAutoSize(TRUE);
//     $file->getActiveSheet()->getColumnDimension('D')->setAutoSize(TRUE);
//     $file->getActiveSheet()->getColumnDimension('E')->setAutoSize(TRUE);
//     $file->getActiveSheet()->getColumnDimension('F')->setAutoSize(TRUE);
//     $file->getActiveSheet()->getColumnDimension('G')->setAutoSize(TRUE);
//     $file->getActiveSheet()->getColumnDimension('H')->setAutoSize(TRUE);
//     $file->getActiveSheet()->getColumnDimension('I')->setAutoSize(TRUE);
//     $file->getActiveSheet()->getColumnDimension('j')->setAutoSize(TRUE);




// $c=$count-1;

// $file->getActiveSheet()->getProtection()->setSheet(true);
// $file->getDefaultStyle()->getProtection()->setLocked(false);
// $active_sheet->getStyle('A1:A'.$c)->getProtection()->setLocked(\PhpOffice\PhpSpreadsheet\Style\Protection::PROTECTION_PROTECTED);
// $active_sheet->getStyle('B1:B'.$c)->getProtection()->setLocked(\PhpOffice\PhpSpreadsheet\Style\Protection::PROTECTION_PROTECTED);
// $active_sheet->getStyle('C1:C'.$c)->getProtection()->setLocked(\PhpOffice\PhpSpreadsheet\Style\Protection::PROTECTION_PROTECTED);
// $active_sheet->getStyle('D1:D'.$c)->getProtection()->setLocked(\PhpOffice\PhpSpreadsheet\Style\Protection::PROTECTION_PROTECTED);
// $active_sheet->getStyle('E1:E'.$c)->getProtection()->setLocked(\PhpOffice\PhpSpreadsheet\Style\Protection::PROTECTION_PROTECTED);
// $active_sheet->getStyle('H1:H'.$c)->getProtection()->setLocked(\PhpOffice\PhpSpreadsheet\Style\Protection::PROTECTION_PROTECTED);
// $active_sheet->getStyle('I1:I'.$c)->getProtection()->setLocked(\PhpOffice\PhpSpreadsheet\Style\Protection::PROTECTION_PROTECTED);


//   $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($file, $_POST["file_type"]);

//   $file_name = 'BudgetData_'.$stdat.'.' . strtolower($_POST["file_type"]);

//   $writer->save($file_name);

//   header('Content-Type: application/x-www-form-urlencoded');

//   header('Content-Transfer-Encoding: Binary');

//   header("Content-disposition: attachment; filename=\"".$file_name."\"");

//   readfile($file_name);

//   unlink($file_name);

//   exit;

// } else

// $_SESSION["favanimal"]=2;
	
// $cmp=2;


	
// if(isset($_POST["upload"]))
// {


// $dtt=$_POST["date1"];
// if (strlen($dtt)==0) {
// //		exit;
// }	
	
	
//     $file_mimes = array('text/x-comma-separated-values', 'text/comma-separated-values', 'application/octet-stream', 'application/vnd.ms-excel', 'application/x-csv', 'text/x-csv', 'text/csv', 'application/csv', 'application/excel', 'application/vnd.msexcel', 'text/plain', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');


      
//         $arr_file = explode('.', $_FILES['file']['name']);
//         $extension = end($arr_file);
// 		$file_type="Xlsx";

//         if('csv' == $extension) {
//             $reader = new \PhpOffice\PhpSpreadsheet\Reader\Csv();
//         } else {
//             $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
//         }
 
//         $spreadsheet1 = $reader->load($_FILES['file']['tmp_name']);

// 		$worksheet = $spreadsheet1->getActiveSheet(2);
//         $sheetData = $spreadsheet1->getActiveSheet()->toArray();




// }


?>


<head>
<script language="javascript" src="calendar.js"></script>
</head>

<head>
<link rel="stylesheet" type="text/css" href="style.css">
</head> 


 
<title>Budget Data Upload</title>
   <link rel="icon" type="images/x-icon" href="favicon.ico" />
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
		

//                    <td bgcolor="#9FE2BF">'.$dn.'</td>


				?>

		<br></br>

<p></p>



<br></br>

 
 



 
       <head>
     <title>Upload Budget Data</title>
     <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />
   </head>
 <form method="post" enctype="multipart/form-data">

</html>
   <body>
     <div class="container">
      <br />
      <h3 align="center">Upload Budget Data</h3>
      <br />
        <div class="panel panel-default">
          <div class="panel-heading">
            <form method="post">
              <div class="row">
                <div class="col-md-6">Effective Date  : <input type="text"  autocomplete=off;   name="date1" id="datepicker"></div>
                <div class="col-md-4">
                  
			      <input type="file" name="file" />
                  <select name="file_type" class="form-control input-sm">
                    <option value="Xlsx">Xlsx</option>
                   </select>


                 </div>
                <div class="col-md-2">
					 <input type="submit" name="upload" value="Upload" />

                  <input type="submit" name="export" class="btn btn-primary btn-sm" value="Template" />
                </div>
              </div>
          <div class="panel-body">
          <div class="table-responsive">
           <table class="table table-striped table-bordered">
		   				

                <tr>
				
                  <th>Sl No </th>
                  <th>Download Date </th>
                  <th>Branch Code</th>
                  <th>Region</th>
                  <th>Site Id</th>
				  <th>Site Name</th>
				  <th>Budget Head Count</th>
				  <th>Budget Shifts</th>
				  <th>Desig Id</th>
				  <th>Designation</th>
				  
                </tr>
                   <?php
// 				   $cnt=1;
// 				$cnn=1;	
// 				$arr="";
//                 $arno=0;			
// 			$numberList3 = array();

// 				$marr="array(";
// 				$sqlm="insert into budget_manpower_table ( budget_date,company_id,region,branch_id,desig_id,Budget_head_count,Budget_shifts,branch_code,is_active ) values ";
// 				   $sqla="";
//   if (!empty($sheetData)) {
//             for ($i=1; $i<count($sheetData); $i++) { //skipping first row
//                 $dt = $sheetData[$i][0];
//                 $bc = $sheetData[$i][1];
// 				$rg=$sheetData[$i][2];
// 				$sid= $sheetData[$i][3];
// 				$snm=$sheetData[$i][4];
// 				$bhc=$sheetData[$i][5];
// 				$bsf=$sheetData[$i][6];
// 				$dsgid=$sheetData[$i][7];
// 				$dsg=$sheetData[$i][8];

// 				$mm=1;
// 				if (strlen($sid)==0) {
// 					$sid=0;
// 					$sql="select branch_id from branch_master where branch_name='".$snm."'";
// 					$resulta = $connect->query($sql);
// 					while($rowa = $resulta->fetch_assoc()) 
// 					{
// 						$sid=$rowa["branch_id"];
 
// 					}
					


// 				}
// 				if (strlen($dsgid)==0) {

// 					$dsgid=0;
// 					$sql="select id from designation where desig='".$dsg."'";
// 					$resulta = $connect->query($sql);
// 					while($rowa = $resulta->fetch_assoc()) 
// 					{
// 						$dsgid=$rowa["id"];
 
// 					}


// 				}

// 					if ($sid==0) {
// 						$mm=0;
// 					}
// 					if ($dsgid==0) {
// 						$mm=0;
// 					}
					 
// 					// echo array_search($sid,$numberList3);
// 					 $g=array_search($sid,$numberList3);
// 					 if (strlen($g)==0) {  
// 					 $numberList3[$arno] = $sid;
// 				 	 $arno++;
// 					 }		
					 
// if ($mm>0) {
//                    echo '
//                   <tr>
//                     <td bgcolor="#9FE2BF">'.$cnn.'</td>
//                     <td bgcolor="#9FE2BF">'.$dt.'</td>
//                     <td bgcolor="#9FE2BF">'.$bc.'</td>
//                     <td bgcolor="#9FE2BF">'.$rg.'</td>
// 				    <td bgcolor="#9FE2BF">'.$sid.'</td>
// 				    <td bgcolor="#9FE2BF">'.$snm.'</td>
// 				    <td bgcolor="#9FE2BF">'.$bhc.'</td>
// 				    <td bgcolor="#9FE2BF">'.$bsf.'</td>
// 				    <td bgcolor="#9FE2BF">'.$dsgid.'</td>
// 			        <td bgcolor="#9FE2BF">'.$dsg.'</td>
			
					
//                   </tr>
//                   ';

// 				$dtt=$_POST["date1"];
// 				$date1=date("Y-m-d", strtotime($dtt) );

// 				if (strlen($bhc)==0) {
// 						$bhc=0	;
// 				}	

// 				if (strlen($bsf)==0) {
// 						$bsf=0	;
// 				}	

                 


//    if ($cnn==1) {
	     
// 	   $arr=$arr.$sid;
		

 
// 	 $sqla=$sqla." ( '".$date1."',119,'".$rg."',".$sid.",".$dsgid.",".$bhc.",".$bsf.",'".$bc."',1)"  ;
//  } else { 
	
 		
      	
//  $sqla=$sqla.", ( '".$date1."',119,'".$rg."',".$sid.",".$dsgid.",".$bhc.",".$bsf.",'".$bc."',1)"  ;
//   }	 
 
 

// }
   
// if ($mm==0) {
//                    echo '
//                   <tr>
//                     <td bgcolor="red">'.$cnn.'</td>
//                     <td bgcolor="red">'.$dt.'</td>
//                     <td bgcolor="red">'.$bc.'</td>
//                     <td bgcolor="red">'.$rg.'</td>
// 				    <td bgcolor="red">'.$sid.'</td>
// 				    <td bgcolor="red">'.$snm.'</td>
// 				    <td bgcolor="red">'.$bhc.'</td>
// 				    <td bgcolor="red">'.$bsf.'</td>
// 				    <td bgcolor="red">'.$dsgid.'</td>
// 			        <td bgcolor="red">'.$dsg.'</td>
			
					
//                   </tr>
//                   ';
// 		$cnt=0;
// }
   
//   $cnn++; 
   
// 			} 

           
 


// 		   $cnt=1;
		   
//   if ($cnt>0) {
// 	  $sqla=$sqlm.$sqla;


// if (strlen($dtt)==0) {
// echo '<script>alert("Please Enter Budget Effective Date")</script>';
 
// }	
// else {


// for($i = 0; $i < ($arno); $i++)
// {
//  		$k=($numberList3[$i]);
			
// 		$sql2="update budget_manpower_table set is_active=0 where budget_date='".$date1."' and branch_id=".$k;
// 		mysqli_query($connect, $sql2);	 

 
// }

 

	  
// mysqli_query($connect, $sqla);	  

// echo '<script>alert("Data Uploaded successfully")</script>';

// } 
//  }
 
//   }
  


  
                ?>

              </table>
          </div>
          </div>
        </div>
     </div>
      <br />
      <br />
  </body>
</html>
