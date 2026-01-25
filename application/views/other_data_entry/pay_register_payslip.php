
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
#daterangetable_wrapper .dataTables_scrollHead th {
            background-color: #0f4d92;
            color: #f8f8ff;
            font-size: 14px;
            word-wrap: nowrap;
            height: 10px;
}
#dtrgmainvctable_wrapper .dataTables_scrollHead th {
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
<script src="<?= base_url('assets/js/stlupload.js'); ?>"></script>



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
                    $holget[6] = 'Traineee ';
                    $holget[7] = 'Main Pay Roll Vouchers(ESI) ';
                    $holget[8] = 'Pay Image Comparative Statement'; ////////sabir change 04.03.24///////////
                    $holget[11] = 'NJM Main Pay Roll'; ////////sabir change 09.03.24///////////
                    $holget[12] = 'NJM OFB CLERK'; ////////sabir change 09.03.24///////////
                    $holget[13] = 'C.E DEDUCTION ABSTRACT SHEET'; ////////sabir change 
                    $holget[14] = 'Bank Sheet'; ////////sabir change 
                    $holget[15] = 'P.Tax Report'; ////////sabir change 
                    $holget[16] = 'Periodical Payregister For Traineee Wages'; ////////sabir change //                 
                    $holget[29] = 'Periodical Payregister For All-Learnar & C-Voucher'; ////////sabir change // 
                    $holget[30] = 'Periodical Payregister For Main Payroll & 18-PF'; ////////sabir change //
                    $holget[31] = 'Pay Roll Posting '; 
    
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
						    <label for="purchaseDetailsPurchaseDate">ESI Report<span class="text-center"></span></label>
                <button name="submit" id="esiacreport"  type="submit"  class="form-control btn btn-danger">ESI Report</button>
            </div>
   

            <div id="njmmainpayslipModal" class="modal">
            <div class="modal-content">
            <div class="modal-header">
            <h4 class="modal-title modal-title-center" id="exampleModalLabel">T NO Data Updation </h4>
            <button type="button" id="njmmainpayslipclosebtnsa" onclick="njmmainpayslipcloseModal()" class="close btn btn-primary" data-dismiss="modal" 
                aria-label="Close">
                </button>
            </div>
            <div class="modal-body">
         <tbody>

         <div class="form-group">

         <div class="col-12 col-sm-6">
            <label for="account-name">Pay Date</label>
            <input class="form-control form-control-rounded" value="<?= date('Y-m-d') ?>" id="paydt" name="paydtdt" type="date">
        </div>
  
        <div class="form-group">
 
          <div class="col-12 col-sm-12">
              <label for="email">Department</label>
              <?php
                    $de['0'] = 'Select';
                    foreach ($departments as $department) {
                        $dde[$department->dept_id] = $department->dept_desc;
                    }       
                    
                    echo form_dropdown('att_dept', $dde, ($att_dept ? $att_dept : "0"), 'id="att_dept"  class="myselect form-control form-control-rounded" data-placeholder="Select Department"  multiple="multiple" style="width:100%;"');
                ?>
              
          </div>
    
    </div> 
        <div class="form-group">
      
        <div class="col-12 col-sm-6">
        <label for="purchaseDetailsPurchaseDate">Update<span class="text-center"></span></label>
            <button name="submit" id="njmmainpayslipprint"  type="submit" class="form-control btn btn-primary">Print</button>
        </div>
        <div class="col-12 col-sm-6">
            <label for="purchaseDethyyurchaseDate">Close<span class="text-center"></span></label>
            <button name="submit" id="njmmainpayslipclose"  type="submit" class="form-control btn btn-danger">Close</button>
        </div>
        </div> 
        </tbody> 
    </table>
                </div>
                </div>
        </div>     

            <?php $this->load->view('modals/ejm_stl_modal'); ?>

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
        <br>
        <br>
        <hr style="height:4px; background-color:#0f4d92  ;"></hr>
     
            <h4 id="heading" style="font-family:Droid Serif; text-align:center;">Pay Register/Pay slip</h4>

            <hr style="height:4px; background-color: #0f4d92  ;"></hr>
                </br> 
                </br> 
                  
           
            <table id="mainpaydatatable"  class="display">
        <thead>
            <tr>
                <th>Frome Date</th>
                <th>To Date</th>
                <th>Emp Code</th>
                <th>Emp Name</th>
                <th>Department</th>
                <th>Occupation</th>
                <th>Time / Piece </th>
                <th>Basic Rate</th>
                <th>Working Hours</th>
                <th>Night Shift Hours</th>
                <th>HoliDay Hours</th>
                <th>Layoff Hrs</th>
                <th>STL Days</th>
                <th>Working Days</th>
                <th>Basic Amt </th>
                <th>Time Rated Basic</th>
                <th>Night Shift Amount</th>
                <th>Holi Day Wages</th>
                <th>Increment</th>
                <th>Layoffwages</th>
                <th>Incentive Amount</th>
                <th>Da Amount</th>
                <th>STL Wages</th>
                <th>PF Gross</th>
                <th>Gross Earnings</th>
                <th>HRA Amount</th>
                <th>Miscellaneous Earning</th>
                <th>Total Earning</th>
                <th>Balance C/F</th>
                <th>EPF Amount</th>
                <th>ESI Gross</th>
                <th>ESIC Amount</th>
                <th>L.W.F Amount</th>
                <th>P.Tax Amount</th>
                <th>Puja Advance</th>
                <th>S.T.L Advance</th>
                <th>Total Deduction</th>
                <th>Balance B/F</th>
                <th>Net Payment</th>
                <th>Cumulative Days</th>
                <th>Cumulative Bons Earn</th>
                <th>Uan Number</th>
                <th>ESIC Nmber</th>
                


            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>  <table id="voucherpaydatatable" style="background-color:"class="display">
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
    </table>  <table id="millrtdpaydatatable" style="background-color:"class="display">
        <thead>
            <tr>
            <th>Frome Date</th>
                <th>To Date</th>
                <th>Emp Code</th>
                <th>Emp Name</th>
                <th>Dept Code</th>
                <th>Department</th>
                 <th>Working Hrs</th>
                 <th>Night Hrs</th>
                 <th>Festival Hrs</th>
            <th>Rate</th>
            <th>Amount</th>
            <th>F. Wages</th>
            <th>Misc Earn</th>
            <th>Total Earnings</th>
            
            <th>ESI Amount</th>
            <th>Advance</th>
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
    <table id="daterangetable"  class="display">
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
    <table id="millrtdpaydatatablee" style="background-color:"class="display">
        <thead>
            <tr>
                <th>Emp Code</th>
                <th>Emp Name</th>
                <th>Working Hrs</th>
                <th>Festival Hrs</th>
                <th>OT Hrs</th>
                <th>Basic Amount</th>
                <th>OT Amount</th>
                <th>Advance Amount</th>
                <th>Net Payable</th>
 
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
        <table id="dtrgmainvctable"  class="display">
        <thead>
            <tr>
            <th>EB Number</th>
				<th> Name </th>
				<th> Work Hours </th>
				<th> Holi Day Hours </th>
				<th> N.S Hours </th>
				<th> STL Days </th>
                <th> Time Basic </th>
                <th> Prod Basic </th>
				<th> Basic </th>
				<th> DA Amount </th>
				<th> Holiday Amount </th>
				<th> N.S Amount </th>
				<th> HRA Amount </th>
				<th> STL Amount </th>
                <th> P.F Gross </th>
				<th> PF Amount </th>
                <th> ESI Gross </th>
				<th> ESI Amount </th>
				<th> P.Tax  </th>
				<th> Advance </th>
                <th> Total Earnings </th>
                <th> Gross Deducion </th>
				<th> Net Payment </th>
				<th> O.T Hours </th>
                <th> O.T Advance </th>
				<th> O.T Amount </th>
				<th> Attendance Incentive </th>
				<th> Total Amount </th>
                


            </tr>
        </thead>
        <tbody>
        </tbody>

    <table id="COMPARATIVE" style="background-color:"class="display"><? //// COMPARATIVE  Table sabir change 04.03.24 ?>
        <thead>
            <tr>

            <th>Frome Date</th>
            <th>To Date</th>
            <th>Department</th>
            <th>EB No</th>
            <th>EMP Name</th>
            <th>O-Gross Pay</th>
            <th>N-Gross Pay</th>
            <th>O-Deduction</th>
            <th>N-Deduction</th>
            <th>O-Net pay</th>
            <th>N-Net Pay</th>
            <th>Diff-Gross</th>
            <th>Diff-Netpay</th>
           

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
            function hideAllTables() {
            //alert ("hideallTables");
                document.getElementById("mainpaydatatable").style.display = "none";
                document.getElementById("voucherpaydatatable").style.display = "none";
                document.getElementById("contactdatatable").style.display = "none";
                document.getElementById("millrtdpaydatatable").style.display = "none";
                document.getElementById("COMPARATIVE").style.display = "none";  ///sabir change 04.03.24/////
                document.getElementById("daterangetable").style.display = "none"; ///sabir change 04.03.24/////
                document.getElementById("millrtdpaydatatablee").style.display = "none"; ///sabir change 04.03.24/////    
                document.getElementById("dtrgmainvctable").style.display = "none";


            }
            hideAllTables();

            function destroyAllTables() {
            //alert ("hideallTables");
                $('#mainpaydatatable').DataTable().destroy();
                $('#voucherpaydatatable').DataTable().destroy();
                $('#contactdatatable').DataTable().destroy();
                $('#millrtdpaydatatable').DataTable().destroy();
                $('#COMPARATIVE').DataTable().destroy();    ///sabir change 04.03.24/////
                $('#daterangetable').DataTable().destroy();    ///sabir change 04.03.24/////
                $('#millrtdpaydatatablee').DataTable().destroy();    ///sabir change 04.03.24/////
                $('#dtrgmainvctable').DataTable().destroy(); 

            }

            function initDataTable8() {
            destroyAllTables();
               table = $('#COMPARATIVE').DataTable({
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
                    { targets: [0], visible: true }, // Hide the first column (auto_id)
                    {
                    targets: [0, 1],
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

         
        
        
$('.select-on-focus').on('focus', function() {
    this.select();
});

      /////////// date rage table //////////////////////////
      function initDataTable11() {
                destroyAllTables();
                table = $('#daterangetable').DataTable({
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
     
     ////////////// end date range table////////////////////////

 
//initDataTable1();

           /* function initDataTable1() {
                $('#spgdailyrecordTable').DataTable().destroy();
             
                table = $('#spgdailyrecordTable').DataTable({
  //              "sDom": 'r<"H"lf><"datatable-scroll"t><"F"ip>',
                 "processing": true,
      //            "serverSide": true,
            //   "bFilter": false,*/

            function initDataTable1() {
                destroyAllTables();
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
            destroyAllTables();
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
                destroyAllTables();
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
        function initDataTable4() {
            destroyAllTables();
               table = $('#millrtdpaydatatable').DataTable({
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

 function initDataTable12() {
            destroyAllTables();
               table = $('#millrtdpaydatatablee').DataTable({
                "processing": true,
                ajax: {
                    url: '<?= base_url('Data_entry/getdranglcpayreg') ?>',
                         type: 'POST',
                         data: function (d) {
                             d.periodfromdate = $('#advpfromdt').val();
                             d.periodtodate = $('#advptodt').val();
                             d.att_payschm = $('#att_payschm').val();
                             d.hol_get = $('#hol_get').val();
                         }
                     }, columnDefs: [
                         { targets: [0, 1], visible: true }, // Hide the first column (auto_id)
                         {
                             targets: [2, 3],
                             render: function (data, type, row, meta) {
                                 return '<div class="column-align-right">' + data + '</div>';
                             }
                         }
                     ],
                     drawCallback: function () {
                         // Apply alignment styles to the table cells
                         $('#recordTable td.column-align-center').css('text-align', 'center');
                         $('#recordTable td.column-align-right').css('text-align', 'right');
                     },

                     fixedColumns: {
                         leftColumns: 4
                     }, scrollX: true,
                     order: [[0, 'asc']],                 // Sort by the first column (auto_id) in descending order
                     pageLength: 10 // Set the default number of rows per page to 25
                 });
             }
             ////////// End This Table///////////////////
             ////////////////// Datergmainvc Data Table //////////////////////
             function initDataTable13() {
                 destroyAllTables();
                 table = $('#dtrgmainvctable').DataTable({
                     "processing": true,
                     ajax: {
                         url: '<?= base_url('Data_entry_2/getmainvcpayrollexceldrg') ?>',
                         type: 'POST',
                         data: function (d) {
                             d.periodfromdate = $('#advpfromdt').val();
                             d.periodtodate = $('#advptodt').val();
                             d.att_payschm = $('#att_payschm').val();
                             d.hol_get = $('#hol_get').val();
                         }
                     }, columnDefs: [
                         { targets: [0, 1], visible: true }, // Hide the first column (auto_id)
                         {
                             targets: [2, 3],
                             render: function (data, type, row, meta) {
                                 return '<div class="column-align-right">' + data + '</div>';
                             }
                         }
                     ],
                     drawCallback: function () {
                         // Apply alignment styles to the table cells
                         $('#recordTable td.column-align-center').css('text-align', 'center');
                         $('#recordTable td.column-align-right').css('text-align', 'right');
                     },

                     fixedColumns: {
                         leftColumns: 4
                     }, scrollX: true,
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
                alert(holget);
                var hd1 = '';
                hd1 = 'Pay  Register for ' + payschemeName + ' Period From ' + periodfromdate + ' To ' + periodtodate;
                heading.textContent = hd1;
                if (holget==1 || holget==2 || holget==7)  {
                    hideAllTables();
                    document.getElementById("mainpaydatatable").style.display = "table";
                    initDataTable1();
                }
               if ( holget==4 || holget==5) {
                hideAllTables();
                 document.getElementById("voucherpaydatatable").style.display = "table";
                initDataTable2();
              }
             if (holget==6) {
                hideAllTables();
                document.getElementById("contactdatatable").style.display = "table";
                initDataTable3();
            }

            if (holget==3) {
                hideAllTables();
                document.getElementById("millrtdpaydatatable").style.display = "table";
                initDataTable4();
            }
   
            if (holget==8) {
                hideAllTables();
                document.getElementById("COMPARATIVE").style.display = "table";
                initDataTable8();
            }
            //////// end change////////////////////
            if (holget==16) {
                alert('16');
                hideAllTables();
                document.getElementById("daterangetable").style.display = "table";
                initDataTable11();
            }
            if (holget==29) {
                hideAllTables();
                document.getElementById("millrtdpaydatatablee").style.display = "table";
                initDataTable12();
            }
            if (holget==30) {
                hideAllTables();
                document.getElementById("dtrgmainvctable").style.display = "table";
                initDataTable13();
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
                alert(holget);
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


$("#njmmainpayslipprint").click(function(event){
      event.preventDefault(); 
	//  alert ("aaaa");
	  var opt=3;
             event.preventDefault();     
                var att_payschm =  $('#att_payschm').val();
                var holget =  $('#hol_get').val();
                periodfromdate = $('#advpfromdt').val();
                periodtodate = $('#advptodt').val();
                payschemeName = $('#payschemename').val();
                att_dept=$('#att_dept').val();
                alert(att_dept);
//                exportdbfdata
            var url = '<?php echo site_url("Data_entry/payslipprint"); ?>' +
                      '?att_payschm=' + att_payschm +
                      '&holget=' + holget+
                      '&periodfromdate=' + periodfromdate+
                      '&payschemeName=' + payschemeName+
                      '&periodtodate=' + periodtodate+
                      '&att_dept=' + att_dept 
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
 

$("#esiacreport").click(function(event){
    var companyid = $('#companyId').val();

  //  alert(companyid);

    // Construct the URL with the 'holget' parameter
    var redirectUrl = "<?php echo site_url('Data_entry/loadAnotherPage'); ?>";
    redirectUrl += "?companyid=" + encodeURIComponent(companyid);

    // Redirect to the new URL
    window.location.href = redirectUrl;
});
 //       }
        
 
 function njmmainpayslipcloseModal() {
    alert('close');
        document.getElementById('njmmainpayslipModal').style.display = 'none';
    }



   
    function njmmainpayslipopenModal() {
//    alert('open');
        hol_get = $('#hol_get').val();
        if (hol_get==11) {
            document.getElementById('njmmainpayslipModal').style.display = 'block';
        }    
        }
        function nwdopenModal() {
//    alert('open');
///        hol_get = $('#hol_get').val();
        if (hol_get==11) {
            document.getElementById('njmmainpayslipModal').style.display = 'block';
        }    
        }

function canteenopenModal() {
    alert('open');
            document.getElementById('canteenModal').style.display = 'block';
    alert('may');
        }


        $('#hol_get').change(function() {
      
            hol_get = $('#hol_get').val();
      
            if (hol_get==11) {
      //      locafill();
            njmmainpayslipopenModal();
        }    
        if (hol_get==31) {
            
            locafill();
            alert(hol_get);
            canteenopenModal();
        }
 });      

        $("#njmmainpayslipclose").click(function(event){
           //    alert('closeb');
                document.getElementById('njmmainpayslipModal').style.display = 'none';
        });

        
                $('.select2-multi').select2({
                multiple: true,
                placeholder: 'Select options',
                width: '100%',
            });


            function locafill() {
        //        alert('ana');
            event.preventDefault(); 
            var periodfromdate= $('#advpfromdt').val();
            var periodtodate= $('#advptodt').val();
            var att_payschm =  $('#att_payschm').val();
            var holget =  $('#hol_get').val();

      //      alert(periodfromdate);
      //      alert (holget);

        $.ajax({
            url: "<?php echo base_url('Data_entry/locadatafill'); ?>",
            type: "POST",
            data: {periodfromdate : periodfromdate,periodtodate : periodtodate,att_payschm: att_payschm,
                holget: holget},
                success: function(response) {
               //     alert('abc');
                        $('#sub_location').empty(); // Clear previous options
                        $('#sub_location').append('<option value="">Select Location</option>'); // Add default option

                        // Populate the dropdown with received data
                        $.each(response.locations, function(index, location) {
//                            console.log(location);
//                            console.log(location.subloca_id);
                          
                            $('#sub_location').append('<option value="' + location[0] + '">' + location[1] + '</option>');
                        });
                    },
            error: function(xhr, status, error) {
                console.error('AJAX Error: ' + error);
            }
        });
     

                
            };    


       //     });
        
    </script>
      