
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pay Register / Pay Slip</title>
    <style>
        .form-group {
            display: block;
            clear: both;
            margin-bottom: 70px;
        }
        #mainpaydatatable_wrapper .dataTables_scrollHead th {
            background-color: #0f4d92;
            color: #f8f8ff;
            font-size: 14px;
            word-wrap: nowrap;
            height: 10px;
}

    #voucherpaydatatable_wrapper .dataTables_scrollHead th {
            background-color: #0f4d92;
            color: #f8f8ff;
            font-size: 14px;
            word-wrap: nowrap;
            height: 10px;
}
#contactdatatable_wrapper .dataTables_scrollHead th {
            background-color: #0f4d92;
            color: #f8f8ff;
            font-size: 14px;
            word-wrap: nowrap;
            height: 10px;
}
  </style>
<script src="<?php echo base_url()?>public/admin/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.min.css">
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/fixedcolumns/4.3.0/css/fixedColumns.dataTables.min.css">
<script src="https://cdn.datatables.net/fixedcolumns/4.3.0/js/dataTables.fixedColumns.min.js"></script>

<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/keytable/2.11.0/css/keyTable.dataTables.min.css">
<script src="https://cdn.datatables.net/keytable/2.11.0/js/dataTables.keyTable.min.js"></script>



</head>
<body>
  
<div class="reporthead"><?='Pay Register / Pay Slip'?></div>

    <div class="form-group">
        <div class="col-12 col-sm-2">
            <label for="account-name">Period From Date</label>
            <input class="form-control form-control-rounded" value="<?= date('Y-m-d') ?>" id="advpfromdt" name="advpfromdt" type="date">
        </div>
        <div class="col-12 col-sm-2">
            <label for="account-name">Period To</label>
            <input class="form-control form-control-rounded" value="<?= date('Y-m-d') ?>" id="advptodt" name="advptodt" type="date">
       </div>
       <div class="col-12 col-sm-3">
              <label for="email">Pay Schemes</label>
              <?php
                    $de['0'] = 'Select';
                    foreach ($payschemes as $payschm) {
                        $de[$payschm->ID] = $payschm->NAME;
                    }       
                    
                    echo form_dropdown('att_payschm', $de, ($att_payschm ? $att_payschm : "0"), 'id="att_payschm"  class="myselect form-control form-control-rounded" data-placeholder="Select PayScheme"  style="width:100%;"');
                ?>
              
          </div>
          <div class="col-12 col-sm-3">
               <label for="email">Pay Ragister/ Pay Slip Type</label>
              <?php
                  // $holget[0] = 'Select';
                    $holget[1] = 'Main Pay Roll';
                    $holget[2] = 'Main Pay Roll Vouchers ';
                    $holget[3] = 'Mill Retired ';
                    $holget[4] = 'Card Holder Bank';
                    $holget[5] = 'Card Holder Cash';
                    $holget[6] = 'Contractor ';
            //        $holget[7] = 'Retired Voucher';
                    
                     
                    echo form_dropdown('hol_get', $holget, ($hol_get ? $hol_get : "0"), 'id="hol_get"  class="myselect form-control form-control-rounded" data-placeholder="Select Holiday "  style="width:100%;"');
                ?>
              
              </div>


     </div>

    <div class="form-group">

   
          <div class="col-12 col-sm-2">
    	    <label for="purchaseDetailsPurchaseDate">Pay Register<span class="text-center"></span></label>
                <button name="submit" id="payregisterdisplay"  type="submit" class="form-control btn btn-primary">Register</button>
         </div>
    <div class="col-12 col-sm-2">
    	    <label for="purchaseDetailsPurchaseDate">Pay Payslip<span class="text-center"></span></label>
                <button name="submit" id="paypayslipprint"  type="submit" class="form-control btn btn-primary">Payslip</button>
            </div>
        <div class="col-12 col-sm-2">
    	    <label for="purchaseDetailsPurchaseDate">Pay Register Print<span class="text-center"></span></label>
                <button name="submit" id="payregisterprint"  type="submit" class="form-control btn btn-primary">Print</button>
         </div>
         <div class="col-12 col-sm-2">
						    <label for="purchaseDetailsPurchaseDate">Statements<span class="text-center"></span></label>
                <button name="submit" id="paystatement"  type="submit" class="form-control btn btn-danger">Report</button>
            </div>
            <div class="col-12 col-sm-2">
						    <label for="purchaseDetailsPurchaseDate">PayReg Parameters<span class="text-center"></span></label>
                <button name="submit" id="payslipparameter"  type="submit"  class="form-control btn btn-danger">Payreg Parameter</button>
            </div>
   

<?php
               $company_id = $this->session->userdata('companyId');
               $company_name = $this->session->userdata('company_name');
               $comp = $this->session->userdata('companyId');
               //  echo $company_id;
              ?>
              <input type="hidden" class="input" value="<?php echo $company_id; ?>" id="companyId" />
              <input type="hidden" class="input" id="ebid" />
              <input type="hidden" class="input" id="record_id" />              
              <input type="hidden" class="input" id="prvamt" />              
              <input type="hidden" class="input" id="payschemename" /> 
        </div> 
     
        <hr style="height:4px; background-color:#0f4d92  ;"></hr>
     
            <h4 align="center" style="font-family:Droid Serif">Pay Register/Pay slip</h4>
            <hr style="height:4px; background-color: #0f4d92  ;"></hr>
        <table id="mainpaydatatable"  class="display">
        <thead>
            <tr>
                <th>Frome Date</th>
                <th>To Date</th>
                <th>Emp Code</th>
                <th>Emp Name</th>
                <th>Department</th>
                <th>Occupation</th>
                <th>Fix Basic Per/Hour</th>
                <th>Da Rate</th>
                <th>Working Hours</th>
                <th>Night Shift Hours</th>
                <th>Holy Day Hours</th>
                <th>Stl Days</th>
                <th>OT Hours</th>
                <th>MISC Earning</th>
                <th>HRA(%)</th>
                <th>Working Hours On Efficence</th>
                <th>Fixt Basic</th>
                <th>Night Allowance</th>
                <th>DA Amount</th>
                <th>Holy Day Wages</th>
                <th>STL Wages</th>
                <th>PF Gross Amount</th>
                <th>HRA Amount</th>
                <th>Gross Earning</th>
                <th>EPF Amount</th>
                <th>ESI Amount</th>
                <th>Company Loan Amount</th>
                <th>Puja Advance</th>
                <th>Ptax Amount</th>
                <th>Gross Deducion</th>
                <th>Net Payment Amount</th>
                <th>OT Amount</th>
                <th>Attendance Incentive</th>
                <th>Production Basic Amt</th>
                <th>Time Basic Amount</th>

            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
    <table id="voucherpaydatatable" style="background-color:"class="display">
        <thead>
            <tr>
            <th>Frome Date</th>
                <th>To Date</th>
                <th>Emp Code</th>
                <th>Emp Name</th>
                <th>Dept Code</th>
                <th>Department</th>
                 <th>Working Hrs</th>
                 <th>Festival Hrs</th>
                 <th>OT Hrs</th>
            <th>Rate</th>
            <th>ADJ Amount</th>
            <th>ADV Amount</th>
            <th>Net Payable</th>
 
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
    <table id="contactdatatable" style="background-color:"class="display">
        <thead>
            <tr>
            <th>Frome Date</th>
            <th>To Date</th>
            <th>EB Number</th>
            <th>Name</th>
            <th>Dept Code</th>
            <th>Dept Name</th>
            <th>Working Hours</th>
            <th>Festival Hrs</th>
            <th>OT Hours</th>
            <th>Rate</th>
            <th>Basic</th>
            <th>Ohers</th>
            <th>Tiffin Allowans</th>
            <th>Convance</th>
            <th>Washing Allowans</th>
            <th>Gross-2</th>
            <th>PF(12%)</th>
            <th>ESI(0.75%)</th>
            <th>Adjust Amt</th>
            <th>P.Tax</th>
            <th>Advance</th>
            <th>Total Earning</th>
            <th>Total Deduction</th>
            <th>Round Off</th>
            <th>Net Pay Amount</th>
            <th>PF (13.00%)</th>
            <th>ESI (3.25%)</th>
            <th>Uan No</th>
            <th>ESI No</th>

            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>

 
    </body>
</html>


<script>
         $(document).ready(function () {
            const heading = document.querySelector('h4');


            $('#advsavedata').show();
            $('#advupdatedata').hide();
            $("#advsavedata").attr('disabled',true);
            $("#advdeldata").attr('disabled',true);
            $("#advupdatedata").attr('disabled',true);
            $('#record_id').val('0');
            hideAllTables();
            function hideAllTables() {
            //alert ("hideallTables");
        document.getElementById("mainpaydatatable").style.display = "none";
        document.getElementById("voucherpaydatatable").style.display = "none";
        document.getElementById("contactdatatable").style.display = "none";

    }

    // Show the first table on page load

    /* Add an event listener to the select element
  //  document.getElementById("hol_get").addEventListener("change", function () {
    //    hideAllTables(); // Hide all tables
      //  var selectedTable = this.value; // Get the selected value

        // Show the selected table
       // document.getElementById(selectedTable).style.display = "table";

        var holget =  $('#hol_get').val();
       alert(holget);

        
        if (holget==1) {
            document.getElementById("mainpaydatatable").style.display = "table";
            document.getElementById("voucherpaydatatable").style.display = "none";
            document.getElementById("contactdatatable").style.display = "none";
            initDataTable1();
        }
        if (holget==2) {
            document.getElementById("mainpaydatatable").style.display = "none";
            document.getElementById("voucherpaydatatable").style.display = "table";
            document.getElementById("contactdatatable").style.display = "none";
            initDataTable2();
        }
        if (holget==3) {
            document.getElementById("mainpaydatatable").style.display = "none";
            document.getElementById("voucherpaydatatable").style.display = "none";
            document.getElementById("contactdatatable").style.display = "table";
            initDataTable3();
        }



    });*/
       
        
$('.select-on-focus').on('focus', function() {
    this.select();
});

 
 
//initDataTable1();

           /* function initDataTable1() {
                $('#spgdailyrecordTable').DataTable().destroy();
             
                table = $('#spgdailyrecordTable').DataTable({
  //              "sDom": 'r<"H"lf><"datatable-scroll"t><"F"ip>',
                 "processing": true,
      //            "serverSide": true,
            //   "bFilter": false,*/

            function initDataTable1() {
                $('#mainpaydatatable').DataTable().destroy();
            $('#voucherpaydatatable').DataTable().destroy();
            $('#contactdatatable').DataTable().destroy();
            
                table = $('#mainpaydatatable').DataTable({
  //              "sDom": 'r<"H"lf><"datatable-scroll"t><"F"ip>',
                 "processing": true,
      //            "serverSide": true,
            //   "bFilter": false,
 
                ajax: {
                    url: '<?= base_url('Data_entry/getpayregisterdata') ?>',
                    type: 'POST',
                    data: function(d) {
                        d.periodfromdate = $('#advpfromdt').val();
                        d.periodtodate = $('#advptodt').val();
                        d.att_payschm = $('#att_payschm').val();
                        d.hol_get = $('#hol_get').val();
                     }
                  },columnDefs: [
                    { targets: [0], visible: true }, // Hide the first column (auto_id)
                    {
                    targets: [2, 3],
                    render: function(data, type, row, meta) {
                        return '<div class="column-align-right">' + data + '</div>';
                    }
                  }
                ],
         //       alert('newwww');
                drawCallback: function() {
                // Apply alignment styles to the table cells
                $('#recordTable td.column-align-center').css('text-align', 'center');
                $('#recordTable td.column-align-right').css('text-align', 'right');
            },
            fixedColumns: {
        leftColumns: 4
    },
            scrollX: true,
                order: [[0, 'asc']],                 // Sort by the first column (auto_id) in descending order
                pageLength: 10 // Set the default number of rows per page to 25
              });
        }

        function initDataTable2() {
            $('#mainpaydatatable').DataTable().destroy();
            $('#voucherpaydatatable').DataTable().destroy();
            $('#contactdatatable').DataTable().destroy();
               table = $('#voucherpaydatatable').DataTable({
                "processing": true,
//                  "serverSide": true,
                ajax: {
                    url: '<?= base_url('Data_entry/getpayregisterdata') ?>',
                    type: 'POST',
                    data: function(d) {
                        d.periodfromdate = $('#advpfromdt').val();
                        d.periodtodate = $('#advptodt').val();
                        d.att_payschm = $('#att_payschm').val();
                        d.hol_get = $('#hol_get').val();
                     }
                  },columnDefs: [
                    { targets: [0,1], visible: true }, // Hide the first column (auto_id)
                    {
                    targets: [2, 3],
                    render: function(data, type, row, meta) {
                        return '<div class="column-align-right">' + data + '</div>';
                    }
                  }
                ],
                drawCallback: function() {
                // Apply alignment styles to the table cells
                $('#recordTable td.column-align-center').css('text-align', 'center');
                $('#recordTable td.column-align-right').css('text-align', 'right');
            },
            scrollX: true,
                order: false,                 // Sort by the first column (auto_id) in descending order
                pageLength: 10 // Set the default number of rows per page to 25
              });
 
            }
            
            
            function initDataTable3() {
            $('#mainpaydatatable').DataTable().destroy();
            $('#voucherpaydatatable').DataTable().destroy();
            $('#contactdatatable').DataTable().destroy();
               table = $('#contactdatatable').DataTable({
                "processing": true,
                ajax: {
                    url: '<?= base_url('Data_entry/getpayregisterdata') ?>',
                    type: 'POST',
                    data: function(d) {
                        d.periodfromdate = $('#advpfromdt').val();
                        d.periodtodate = $('#advptodt').val();
                        d.att_payschm = $('#att_payschm').val();
                        d.hol_get = $('#hol_get').val();
                     }
                  },columnDefs: [
                    { targets: [0,1], visible: true }, // Hide the first column (auto_id)
                    {
                    targets: [2, 3],
                    render: function(data, type, row, meta) {
                        return '<div class="column-align-right">' + data + '</div>';
                    }
                  }
                ],
                drawCallback: function() {
                // Apply alignment styles to the table cells
                $('#recordTable td.column-align-center').css('text-align', 'center');
                $('#recordTable td.column-align-right').css('text-align', 'right');
            },
            
            fixedColumns: {
        leftColumns: 4
    },scrollX: true,
                order: [[0, 'asc']],                 // Sort by the first column (auto_id) in descending order
                pageLength: 10 // Set the default number of rows per page to 25
              });
        }

         $("#payregisterdisplay").click(function(event){
                event.preventDefault(); 
                var att_payschm =  $('#att_payschm').val();
                var holget =  $('#hol_get').val();
                periodfromdate = $('#advpfromdt').val();
                periodtodate = $('#advptodt').val();
                payschemeName = $('#payschemename').val();
                periodfromdate= periodfromdate.substring(8)+'-'+periodfromdate.substring(5,7)+'-'+periodfromdate.substring(0,4);
                periodtodate= periodtodate.substring(8)+'-'+periodtodate.substring(5,7)+'-'+periodtodate.substring(0,4);
                var hd1 = '';
                hd1 = 'Pay  Register for ' + payschemeName + ' Period From ' + periodfromdate + ' To ' + periodtodate;
                heading.textContent = hd1;
                if (holget==1 || holget==2) {
                    document.getElementById("mainpaydatatable").style.display = "table";
                    document.getElementById("voucherpaydatatable").style.display = "none";
                    document.getElementById("contactdatatable").style.display = "none";
               //   alert('2st');
                    initDataTable1();
                }
               if (holget==3 || holget==4 || holget==5) {
              //  alert('4'); 
                document.getElementById("mainpaydatatable").style.display = "none";
                 document.getElementById("voucherpaydatatable").style.display = "table";
                 document.getElementById("contactdatatable").style.display = "none";
                initDataTable2();
              }
             if (holget==6) {
                 document.getElementById("mainpaydatatable").style.display = "none";
                document.getElementById("voucherpaydatatable").style.display = "none";
                document.getElementById("contactdatatable").style.display = "table";
                initDataTable3();
            }

         

 
            });


 
    
   //     refreshDataTable();
      });


  
      $("#payregisterprint").click(function(event){
      event.preventDefault(); 
	//  alert ("aaaa");
	  var opt=3;
             event.preventDefault();     
                var att_payschm =  $('#att_payschm').val();
                var holget =  $('#hol_get').val();
                periodfromdate = $('#advpfromdt').val();
                periodtodate = $('#advptodt').val();
                payschemeName = $('#payschemename').val();
//                exportdbfdata
            var url = '<?php echo site_url("Data_entry/payregisterprint"); ?>' +
                      '?att_payschm=' + att_payschm +
                      '&holget=' + holget+
                      '&periodfromdate=' + periodfromdate+
                      '&payschemeName=' + payschemeName+
                      '&periodtodate=' + periodtodate
                       
                      ;
      //                alert(url);
			//$(location).attr('href',url);
			window.open( url, '_blank');
			
			
return false;
});



$("#paypayslipprint").click(function(event){
      event.preventDefault(); 
	//  alert ("aaaa");
	  var opt=3;
             event.preventDefault();     
                var att_payschm =  $('#att_payschm').val();
                var holget =  $('#hol_get').val();
                periodfromdate = $('#advpfromdt').val();
                periodtodate = $('#advptodt').val();
                payschemeName = $('#payschemename').val();
//                exportdbfdata
            var url = '<?php echo site_url("Data_entry/payslipprint"); ?>' +
                      '?att_payschm=' + att_payschm +
                      '&holget=' + holget+
                      '&periodfromdate=' + periodfromdate+
                      '&payschemeName=' + payschemeName+
                      '&periodtodate=' + periodtodate
                       
                      ;
      //                alert(url);
			//$(location).attr('href',url);
			window.open( url, '_blank');
			
			
return false;
});


$("#paystatement").click(function(event){
      event.preventDefault(); 
	//  alert ("aaaa");
	  var opt=3;
             event.preventDefault();     
                var att_payschm =  $('#att_payschm').val();
                var holget =  $('#hol_get').val();
                periodfromdate = $('#advpfromdt').val();
                periodtodate = $('#advptodt').val();
                payschemeName = $('#payschemename').val();
//                exportdbfdata
            var url = '<?php echo site_url("Data_entry/paystatement"); ?>' +
                      '?att_payschm=' + att_payschm +
                      '&holget=' + holget+
                      '&periodfromdate=' + periodfromdate+
                      '&payschemeName=' + payschemeName+
                      '&periodtodate=' + periodtodate
                       
                      ;
      //                alert(url);
			//$(location).attr('href',url);
			window.open( url, '_blank');
			
			
return false;
});
 

$("#payslipparameter").click(function(event){
    var companyid = $('#companyId').val();

  //  alert(companyid);

    // Construct the URL with the 'holget' parameter
    var redirectUrl = "<?php echo site_url('Data_entry/loadAnotherPage'); ?>";
    redirectUrl += "?companyid=" + encodeURIComponent(companyid);

    // Redirect to the new URL
    window.location.href = redirectUrl;
});
 //       }
        
        
        
                $('.select2-multi').select2({
                multiple: true,
                placeholder: 'Select options',
                width: '100%',
            });

       //     });
        
    </script>
      