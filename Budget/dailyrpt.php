<?php
//include("demo.php");
?>

<form action="showpdf.php" method="get">
</head> 
<html>
<head><title>View Products</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />

</head>
<body bgcolor="pink">
<marquee hspace=333 scrolldelay=100><font size=5 color="red">Welcome To Web Portal</font></marquee>
</body>
</html>
<html lang="en">
<form action="rdtxt.php" method="get">

<head>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
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
</head>
<body>
 
</head>
<body>

<div id='my_page2' style="z-index: 2; size=500 position: center; top: 10px; background-color: #cccc33; width: 600px; padding: 200px; color: blue; border: #0000cc 2px solid; ">   </div>


<?php
	//	$vv=$_GET["value"];
		



				?>

		<br></br>

<p></p>



<br></br>



<div

   
   style="
      top: 180;
      left: 300;
      position: absolute;
      z-index: 1;
      visibility: show;">
	
<p>Select Date  : <input type="text" name="datp" id="datepicker"></p>

	
</div>

<p></p>
<p></p>

<p></p>



<br>


 <p></p>
 


 <div

   
   style="
      top: 230;
      left: 300;
      position: absolute;
      z-index: 3;
	  color: red;
      visibility: show;">
	
	Reports

	
</div>

<div

   
   style="
      top: 230;
      left: 380;
      position: absolute;
      z-index: 1;
	  color: red;
      visibility: show;">
	
						<select name="dept">

			<option value="JUT0001.prn">Jute Report</option>"
			<option value="JUT0002.prn">Batch Report</option>"
			<option value="DRG0001.prn">Drawing Report</option>"
 			<option value="spg0001.prn">Spinning Report</option>"
			<option value="dof0001.prn">Doff Report</option>"
			<option value="a.txt">Doff Analysis Report</option>"
			
			<option value="wvg0001.prn">Weaving Report</option>"
 			<option value="hyl0001.prn">Hy Lt Report</option>"
 			<option value="fng0001.prn">Finishing Report</option>"
			
			<option value="hnd001b.prn">Hands Report</option>"
 			<option value="mis0001.prn">MIS Report</option>"



 	 <input type="submit" value="Submit" />

	
</div>
<marquee hspace=333 scrolldelay=100><font size=5 color="red">Developed & Supported By Snehadeep Goli</font></marquee>

</form> 