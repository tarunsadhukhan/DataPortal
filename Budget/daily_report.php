<form action="showpdf.php" method="POST">

<?php
require_once './functions.php';
require_once './header.php';
$startingDate = getStartingDate();
$endingDate = getEndingDate();
$static_request = $_REQUEST['reportDate'];
$errorReport='';
if ($static_request != '' && !(isset($searchSubmit))) {
    if (DateTime::createFromFormat('d-M-Y', $static_request) !== false) {
        $searchDate = $static_request;
    } else {
        $errorReport =  "Please enter report Date with format like 01-JAN-1990";
    }
}
if (isset($searchSubmit) || $searchDate != '') {
    $folder_exist = '';
    $convertedDate = date('d.m.Y', strtotime($searchDate));
    $path = 'd://daily Reports//2017//' . $convertedDate;
    if (is_dir($path)) {
        $folder_exist = 1;
    } else {
        $folder_exist = 2;
    }
} else {

    $searchDate = getEndingDate();
    if ($searchDate != '') {
        $folder_exist = '';
        $convertedDate = date('d.m.Y', strtotime($searchDate));
        $path = 'd://daily Reports//2017//' . $convertedDate;
        if (is_dir($path)) {
            $folder_exist = 1;
        } else {
            $folder_exist = 2;
        }
    }
}
?>
<script type="text/javascript">
    function downloadPdf(val) {
        //alert(val);
        //alert("button");
        var selectedDate = $("#searchDate").val();
        var selectedVal = val;
        //alert(selectedDate+"--"+selectedVal);
        if (selectedDate != "" && selectedVal != "") {
            var url = 'showPdf.php?selectDate=' + selectedDate + '&searchString=' + selectedVal;
            window.open(url, '_blank');
        } else {
            alert('Please select Date and Report');
        }
    }
    window.onload = function () {
        new JsDatePick({
            useMode: 2,
            target: "searchDate",
            dateFormat: "%d-%M-%Y"
        });
    }

//          $('#calendar-demo').dcalendar();
//$('input').dcalendarpicker();
//$('input').dcalendarpicker({
//
//  format: 'dd-mm-yyyy'
//
//});

    $(document).ready(function () {
        $("#filesPdfSubmit").click(function () {

        });




    });




</script>
<body>
    <div class="bs-example">
        <?php
        require_once './menus.php';
        ?>

    </div>
    <div class="container-fluid">
        
        <div id="title" style="padding-left: 30px">
            <?php
            if($errorReport!=''){
                ?>
            <div style="color: red"><?=$errorReport?></div>
        <?php
            }
        ?>
            <h1>Daily Report</h1>
            <?php
            if ($startingDate != '' && $endingDate != '' && $startingDate == $endingDate) {
                $reportsExistStatus = "Reports available for $startingDate";
            } else if ($startingDate != '' && $endingDate != '') {
                $reportsExistStatus = "Reports available from $startingDate to $endingDate";
            } else if (($startingDate == '' && $endingDate != '') || ($startingDate != '' && $endingDate == '')) {
                $reportsExistStatus = "Reports available for $startingDate $endingDate";
            } else {
                $reportsExistStatus = "No reports available";
            }
            ?>
            <div style="color: darkslategray"><?= $reportsExistStatus ?></div>
        </div>
        <form method="POST" action="">
            <div id="content" style="padding-left: 30px;padding-top: 20px;color: darkslategray">Select Date</div>
            <div id="content" style="padding-left: 30px">
                <?php
                if ($searchDate != '') {
                    $serachDateValue = $searchDate;
                } else {
                    $serachDateValue = '';
                }
                ?>

<!--<input class="form-control" id="searchDate" name="searchDate" type="text"></div>-->
                <input type="text"   name="searchDate" id="searchDate" value="<?= $serachDateValue ?>" readonly/>
                <?php
                $searchDate = '';
                ?>
                <input type="submit" name="searchSubmit" id="searchSubmit" value="Show Reports"/>
            </div>

            <?php
            if ($folder_exist != '') {

                if ($folder_exist == 1) {
                    ?>
                    <div style="padding-top: 20px;padding-left: 30px;font-size: 15px;color: darkgreen;">
                        <b>Reports for the date of <?= $serachDateValue ?></b>
                    </div>
                    <div style="padding-left: 10px;padding-top: 5px;"  class="col-lg-8">
<?php
				}
			}
?>			



<div

   
   style="
      top: 230;
      left: 360;
      position: absolute;
      z-index: 1;
	  color: red;
      visibility: show;">
	
			<select name="dept">

			<option value="jut0001.prn">Jute Stock Report</option>"
			<option value="jut0002.prn">Batch Report</option>"
 			<option value="dof0001.prn">Doffing Report</option>"
 			<option value="drg0001.prn">Drawing Report</option>"
			<option value="spg0001.prn">Spinning Report</option>"
 			<option value="wvg0001.prn">Weaving Report</option>"
 			<option value="mis0001.prn">MIS Report</option>"


 	 
	
</div>


        </form>


    </div>
</body>
            <?php
            require_once './footer.php';
            ?>
<!--<script>
$('#searchDate').dcalendarpicker();
</script>-->
