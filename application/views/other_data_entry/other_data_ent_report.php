
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pay Register / Pay Slip</title>
    <style>
.modal-title-center {
    text-align: center;
    margin: 0 auto;
}
    #myDataTable tbody tr.even {
            background-color: #f5f5f5; /* Change this color to your desired even stripe color */
        }

        /* Custom stripe color for odd rows */
        #myDataTable tbody tr.odd {
            background-color: #ffffff; /* Change this color to your desired odd stripe color */
        }

        /* Hover effect for rows */
        #handcomp tbody tr:hover {
            background-color: #85C1E9  !important; /* Use !important to ensure precedence */
        }

        /* Add your custom styles for the selected row here */
        #handcomp tbody tr.selected-row {
            background-color: #a6a6a6; /* Change this color to your desired selected color */
        }        
        #handcomp tbody tr.even {
            background-color: #D6EAF8; /* Change this color to your desired even stripe color */
        }

        /* Custom stripe color for odd rows */
        #handcomp tbody tr.odd {
            background-color: #ffffff; /* Change this color to your desired odd stripe color */
        }

        
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

    #handcomp_wrapper .dataTables_scrollHead th {
            background-color: #0f4d92;
            color: #f8f8ff;
            font-size: 14px;
            word-wrap: nowrap;
            height: 10px;
}
#handcomp td {
      font-size: 14px; /* Adjust the font size as needed */
    }
    #handcompdet td {
      font-size: 12px; /* Adjust the font size as needed */
    }
    #dailypay_wrapper .dataTables_scrollHead th {
            background-color: #0f4d92;
            color: #f8f8ff;
            font-size: 14px;
            word-wrap: nowrap;
            height: 10px;
}
#musterrolltable_wrapper .dataTables_scrollHead th {
            background-color: #0f4d92;
            color: #f8f8ff;
            font-size: 14px;
            word-wrap: nowrap;
            height: 10px;
}

#workerdetails_wrapper .dataTables_scrollHead th {
            background-color: #0f4d92;
            color: #f8f8ff;
            font-size: 14px;
            word-wrap: nowrap;
            height: 10px;
}

#handcompdet_wrapper .dataTables_scrollHead th {
            background-color: #0f4d92;
            color: #f8f8ff;
            font-size: 14px;
            word-wrap: nowrap;
            height: 10px;
}
#attdetails_wrapper .dataTables_scrollHead th {
            background-color: #0f4d92;
            color: #f8f8ff;
            font-size: 10px;
            word-wrap: nowrap;
            height: 10px;
}
#attdetails td {
      font-size: 12px; /* Adjust the font size as needed */
    }


  
        .modal {
  background: linear-gradient() ;
  position: absolute;
  z-index: 1001;
  float: left;
  left: 50%;
  top: 50%;
  transform: translate(-50%, -50%);

} 
        .modal-content {
            background-color: #FFFFFF;
            margin-top: 5%;
            margin-left: 10%;
            padding: 40px;
            border: 1px solid #888;
            width: 70%; /* Set the width as needed */
            height: 60%; /* Set the width as needed */
            
     /*   height: 80%;  Set the height as needed 
        max-width: 800px; */ /* Optional: Set a maximum width */
            overflow-y: auto;  Enable vertical scrollbar if needed */
                    margin-top: 10%; /* Adjust the top margin to center vertically */
                    display: flex;
        align-items: center; /* Align items at the top */
                    
                }
                .modal-dialog {
            display: flex;
            align-items: center;
            min-height: calc(100% - (1.75rem * 2));
        }

        #attModal.modal-content {
        width: 90%; /* Adjust the width as needed */
        margin: 0 auto; /* Center the modal horizontally */
        border: 1px solid #888;
        height: 80%; /* Set the width as needed */
        
}

        
th, td { 
    white-space: nowrap;
}

</style>

<script src="<?php echo base_url()?>public/admin/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>


<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.min.css">

<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/fixedcolumns/4.3.0/css/fixedColumns.dataTables.min.css">
<script src="https://cdn.datatables.net/fixedcolumns/4.3.0/js/dataTables.fixedColumns.min.js"></script>

<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/keytable/2.11.0/css/keyTable.dataTables.min.css">
<script src="https://cdn.datatables.net/keytable/2.11.0/js/dataTables.keyTable.min.js"></script>

<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/1.7.0/css/buttons.dataTables.min.css">
    <script type="text/javascript" src="https://cdn.datatables.net/buttons/1.7.0/js/dataTables.buttons.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/buttons/1.7.0/js/buttons.html5.min.js"></script>




<!--
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
-->    

</head>
<body>
  
<div class="reporthead"><?='Other Entry/Reports'?></div>

    <div class="form-group">
        <div class="col-12 col-sm-2">
            <label for="account-name">Period From Date</label>
            <input class="form-control form-control-rounded" value="<?= date('Y-m-d') ?>" id="advpfromdt" name="advpfromdt" type="date">
        </div>
        <div class="col-12 col-sm-2">
            <label for="account-name">Period To</label>
            <input class="form-control form-control-rounded" value="<?= date('Y-m-d') ?>" id="advptodt" name="advptodt" type="date">
       </div>
       <div class="col-12 col-sm-2">
              <label for="email">Spell</label>
              <?php
                    $de['0'] = 'Select';
                    foreach ($spells as $spell) {
                        $de[$spell->spell_name] = $spell->spell_name;
                    }       
                    
                    echo form_dropdown('att_spell', $de, ($att_spell ? $att_spell : "0"), 'id="att_spell"  class="myselect form-control form-control-rounded" data-placeholder="Select Spell"  style="width:100%;"');
                ?>
              
          </div>
          <div class="col-12 col-sm-2">
              <label for="email">Department</label>
              <?php
                    $de['0'] = 'Select';
                    foreach ($departments as $department) {
                        $dde[$department->dept_id] = $department->dept_desc;
                    }       
                    
                    echo form_dropdown('att_dept', $dde, ($att_dept ? $att_dept : "0"), 'id="att_dept"  class="myselect form-control form-control-rounded" data-placeholder="Select Department"  style="width:100%;"');
                ?>
              
          </div>

          <div class="col-12 col-sm-2">
               <label for="email">Report Type</label>
              <?php
                  // $holget[0] = 'Select';
                    $holget[1] = 'Man Mechine list';
                    $holget[2] = 'Daily Hands Complement ';
                    $holget[3] = 'Main Payroll Muster Roll';
                    $holget[4] = 'Main Payroll Muster Roll(M/c)';
                    $holget[5] = 'Others Muster Roll';
                    $holget[6] = 'Winding Wages Data';
                    $holget[7] = 'Working Details';///change sabir 22.12.2023////////
                    $holget[8] = 'Daily Extra Advance Payment';///change sabir 22.12.2023////////
                    $holget[9] = 'Doff Trolly / Busket Weight Entry';///change sabir 22.12.2023////////
            
                     
                    echo form_dropdown('hol_get', $holget, ($hol_get ? $hol_get : "0"), 'id="hol_get"  class="myselect form-control form-control-rounded" data-placeholder="Select Holiday "  style="width:100%;"');
                ?>
              
              </div>


     </div>

    <div class="form-group">

   
          <div class="col-12 col-sm-2">
    	    <label for="purchaseDetailsPurchaseDate">Show<span class="text-center"></span></label>
                <button name="submit" id="otherdatashow"  type="submit" class="form-control btn btn-primary">Show</button>
         </div>
        <div class="col-12 col-sm-2">
    	    <label for="purchaseDetailsPurchaseDate">Print <span class="text-center"></span></label>
                <button name="submit" id="otherdataprint"  type="submit" class="form-control btn btn-primary">Print</button>
            </div>
        <div class="col-12 col-sm-2">
    	    <label for="purchaseDetailsPurchaseDate">Pay Register Print<span class="text-center"></span></label>
                <button name="submit" id="payregisterprint"  disabled type="submit" class="form-control btn btn-primary">Print</button>
         </div>
         <div class="col-12 col-sm-2">
						    <label for="purchaseDetailsPurchaseDate">Statements<span class="text-center"></span></label>
                <button name="submit" id="paystatement"  disabled type="submit" class="form-control btn btn-danger">Report</button>
            </div>
            <div class="col-12 col-sm-2">
						    <label for="purchaseDetailsPurchaseDate">PayReg Parameters<span class="text-center"></span></label>
                <button name="submit" id="payslipparameter"  disabled type="submit"  class="form-control btn btn-danger">Payreg Parameter</button>
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
              <input type="hidden" class="input" id="occucode" />              
              <input type="hidden" class="input" id="ocdate" />              
              <input type="hidden" class="input" id="frdate" />              
              <input type="hidden" class="input" id="todate" />              
       
              </div> 

 

        
        <div id="myModal" class="modal">
            <div class="modal-content">
      
            <div class="modal-header">
            <h4 class="modal-title modal-title-center" id="exampleModalLabel">Occupation Wise Hands Details</h4>

            </div>
            <div class="modal-body">
            <table id="handcompdet"  class="display">
        <thead>
        <tr>
            <th>SL No</th>
            <th>Emp Code</th>
            <th>Emp Name</th>
            <th>Shift</th>
            <th>Occupation</th>
            <th>Attendance Type</th>
            <th>Working Hours</th>
            <th></th>
            
         </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
         
                    <div class="form-group">
          
                
                        <div class="col-12 col-sm-2">
 <!--
                        <label for="purchaseDetailsPurchaseDate">Close <span class="text-center"></span></label>
 -->
                                <button onclick="closeModal()" name="submit" id="closebtn" style="height: 40px;" type="submit" class="form-control btn btn-primary">Back</button>
                    
                        </div>
                </div>  
                </div>

                </div>
        </div>            





<div id="attModal" class="modal">
            <div class="modal-content">
      
            <div class="modal-header">
            <h4 style="font-size: 24px; color: #17919e; text-shadow: 1px 1px #ccc; class="modal-title modal-title-center" id="attModalLabel">Occupation Wise Hands Details</h4>
            
                  </div>
 
                  <div class="modal-body">
            <table id="attdetails"  class="display">
        <thead>
        <tr>
            <th>Emp Code</th>
            <th>Emp Name</th>
            <th>Date</th>
            <th>Shift</th>
            <th>Occupation</th>
            <th>Attendance Type</th>
            <th>Working Hours</th>
            <th></th>
            
         </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
    <!--
         
                    <div class="form-group">
          
                
                        <div class="col-12 col-sm-2">
                        <label for="purchaseDetailsPurchaseDate">Close <span class="text-center"></span></label>
                                <button onclick="closeattModal()" name="submit" id="closeattbtn" style="height: 40px;" type="submit" class="form-control btn btn-danger">Back</button>
                    
                        </div>
                </div>  
                -->

                <div class="modal-footer">
        <button type="button" onclick="closeattModal()" name="submit" id="closeattbtn" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times"></i> Close</button>
      </div>

                </div>

                </div>
        </div>            





<div id="attModal1" class="modal" >
            <div class="modal-content">
      
            <div class="modal-header">
            <h4 class="modal-title modal-title-center" id="attModalLabel">Employee Wise Attendance Details</h4>
          <button type="button" id="closeattbtns" onclick="closeattModal()" class="close btn-danger" data-dismiss="modal" 
                aria-label="Close">
                </button>
            </div>
            <div class="modal-body">
            <table id="attdetails" width="100%" class="table table-bordered table-striped">
          <thead class="btn-primary">
        <thead>
        <tr>
            <th>Emp Code</th>
            <th>Emp Name</th>
            <th>Date</th>
            <th>Shift</th>
            <th>Occupation</th>
            <th>Attendance Type</th>
            <th>Working Hours</th>
            <th></th>
            
         </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
         
                    <div class="form-group">
          
                
                        <div class="col-12 col-sm-2">
 <!--
                        <label for="purchaseDetailsPurchaseDate">Close <span class="text-center"></span></label>
                        class="btn btn-danger"
 -->
                                <button onclick="closeattModal()" name="submit" id="closeattbtn" style="height: 40px;" type="submit" class="form-control  btn btn-danger">Back</button>
                    
                        </div>
                </div>  
                </div>

                </div>
        </div>            

//
<div id="njmmainpayslipModal" class="modal">
            <div class="modal-content">
            <div class="modal-header">
            <h4 class="modal-title modal-title-center" id="exampleModalLabel">Doff TRolly/Busket Weight Entry </h4>
            <button type="button" id="njmmainpayslipclosebtnsa" onclick="njmmainpayslipcloseModal()" class="close btn btn-primary" data-dismiss="modal" 
                aria-label="Close">
                </button>
            </div>
            <div class="modal-body">
         <tbody>

         <div class="form-group">
    
          <div class="col-12 col-sm-4">
              <label for="email">Trolly Details</label>
              <?php
                    $lde['0'] = 'Select';
                    foreach ($locations as $location) {
                        $lde[$location->trollyid] = $location->trollyno;
                    }       
                    
                    echo form_dropdown('sub_location', $lde, ($sub_location ? $sub_location : "1"), 'id="sub_location"  class="myselect form-control form-control-rounded" data-placeholder="Select Location"  style="width:100%;"');
                ?>
              
          </div>
          <div class="col-sm-4">
            <label for="email">Busket Wt</label>
            <input class="form-control form-control-rounded" id="busketwt" value="0" name="busketwt" type="text">   
        </div>
        <div class="col-sm-4">
            <label for="email">Trolly Wt</label>
            <input class="form-control form-control-rounded" id="trollywt" value="0" name="trollywt" type="text">   
        </div>
  

          </div>

        <div class="form-group">
      
        <div class="col-12 col-sm-6">
        <label for="purchaseDetailsPurchaseDate">Update<span class="text-center"></span></label>
            <button name="submit" id="njmmainpayslipprint"  type="submit" class="form-control btn btn-primary">Update</button>
        </div>
        <div class="col-12 col-sm-6">
            <label for="purchaseDetailsPurchaseDate">Close<span class="text-center"></span></label>
            <button name="submit" id="njmmainpayslipclose"  type="submit" class="form-control btn btn-danger">Close</button>
        </div>
        </div> 
        </tbody>
    </table>
                </div>
                </div>
        </div>     

//

        
        <hr style="height:4px; background-color:#0f4d92  ;"></hr>
            <h4 align="center" id="payTitle" style="font-family:Droid Serif">Pay Register/Pay slip</h4>
            <hr style="height:4px; background-color: #0f4d92  ;"></hr>

            <table id="mainpaydatatable"  class="display">
        <thead>
            <tr>
                <th>Attendaance Date</th>
                <th>Spell</th>
                <th>Emp Code</th>
                <th>Emp Name</th>
                <th>Occupation</th>
                <th>Mechine Code</th>
                <th>Mechine Name</th>
                <th>Working Hours</th>
                <th>no Mechine</th>
                
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
    <button id="Excelprn">Excel</button>
    <table id="dailypay"  class="display">
        <thead>
            <tr>
                <th>EB Number</th>
                <th>Work Name</th>
                <th>Shift</th>
                <th>Dept Name</th>
                <th>Occupatin</th>
                <th>Working Hours</th>
                <th>Adv Amount</th>
               
             
                
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
   
    <table id="handcomp" style="background-color:"class="display">
        <thead>
            <tr>
            <th>Attendance Date</th>
            <th>Dept Code</th>
            <th>Dept Desc</th>
            <th>Hoccu Code</th>
                <th>Description</th>
                <th>SHIFT A</th>
                <th>SHIFT B</th>
                <th>SHIFT C</th>
                <th>SHIFT TOTAL</th>
                <th>OT A</th>
                <th>OT B</th>
                <th>OT C</th>
                <th>OT TOTAL</th>
               
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
    <table id="musterrolltable" style="background-color:"class="display">
        <thead>
            <tr>
            <th>From Date</th>
            <th>To Date</th>
            <th>EB Number</th>
            <th>Name</th>
            <th>Shift</th>
            <th>Dept Code</th>
            <th>Dept Name</th>
            <th>Occu Code</th>
            <th>Occu Name</th>
            <th>Working Hours</th>
            <th>N.S Hours</th>
            <th>Festival Hrs</th>
            <th>OT Hours</th>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
<?php
?>
    
    <table id="workerdetails" style="background-color: blue;"class="display"><?php ////// sabir change 22.12.23 ?>
        <thead>
            <tr>
            <th>ebid</th>
            <th>From Date</th>
            <th>To Date</th>
            <th>EB No</th>
            <th>Name</th>
            <th>Catagory</th>
            <th>Department</th>
            <th>Occupation</th>
            <th>Regular Hours </th>
            <th>OT Hours</th>
            <th>Night Hours</th>
            <th>Workind Days</th>
            <th>OT Days</th>
            <th>Leave Days</th>
            <th>Holidays</th>
            <th>Mill Working Days</th>
            <th>Absent Days</th>
            <th>Last Working Date</th>
            </tr>
        </thead><?php ////// sabir change end  22.12.23 ?>
        <tbody>
        </tbody>
    </table>
  



    <table id="mcmusterrolltable" style="background-color:"class="display">
        <thead>
            <tr>
            <th>From Date</th>
            <th>To Date</th>
            <th>EB Number</th>
            <th>Name</th>
            <th>Mc Nos</th>
            <th>Dept Code</th>
            <th>Dept Name</th>
            <th>Occu Code</th>
            <th>Occu Name</th>
            <th>Working Hours</th>
            <th>N.S Hours</th>
            <th>Festival Hrs</th>
            <th>OT Hours</th>

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
        document.getElementById("handcomp").style.display = "none";
        document.getElementById("musterrolltable").style.display = "none";
        document.getElementById("mcmusterrolltable").style.display = "none";
        document.getElementById("workerdetails").style.display = "none";
        document.getElementById("dailypay").style.display = "none";    
    }
    function destroyAllTables() {

            $('#mainpaydatatable').DataTable().destroy();
            $('#handcomp').DataTable().destroy();
            $('#musterrolltable').DataTable().destroy();
            $('#mcmusterrolltable').DataTable().destroy();
            $('#workerdetails').DataTable().destroy();    //////// sabir added 22.12.23/////////        
            $('#handcompdet').DataTable().destroy();    //////// sabir added 22.12.23/////////        
            $('#dailypay').DataTable().destroy(); 
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

$('#workerdetails tbody').on('dblclick', 'tr', function() {
    var dataTableId = 'workerdetails';
    var table = $('#' + dataTableId).DataTable();
        var rowIdx = table.row(this).index();
        var colIdx = $(this).find('td').index(event.target);
        var cellData = table.cell(rowIdx, colIdx).data();
        console.log('Row: ' + rowIdx + ', Column: ' + colIdx + ', Value: ' + cellData);
        var row = table.row(this);
        var rowData = row.data();
        console.log('Row Data:', rowData);
    var occud='';
    var ocdt='';
    var occudes='';
    for (var i = 0; i < rowData.length; i++) {
        console.log('Column ' + i + ': ' + rowData[i]);
        if (i==0) { 
            $('#ebid').val(rowData[i]);
            var occud=rowData[i];
        }
        if (i==1) { 
             $('#frdate').val(rowData[i]);
             var frdt=rowData[i];
        }
        if (i==2) { 
             $('#todate').val(rowData[i]);
             var todt=rowData[i];
        }
    }
    var modalTitle = document.getElementById('exampleModalLabel');
        modalTitle.textContent = 'Employee Attendance Details for the period from '+frdt+' To '+todt;
                    document.getElementById("workerdetails").style.display = "table";
                    initDataTable9();
                    openattModal();


    }); 


$('#handcomp tbody').on('dblclick', 'tr', function() {
    var dataTableId = 'handcomp';
    var table = $('#' + dataTableId).DataTable();
        var rowIdx = table.row(this).index();
        var colIdx = $(this).find('td').index(event.target);
        var cellData = table.cell(rowIdx, colIdx).data();
        console.log('Row: ' + rowIdx + ', Column: ' + colIdx + ', Value: ' + cellData);
        var row = table.row(this);
        var rowData = row.data();
        console.log('Row Data:', rowData);
    var occud='';
    var ocdt='';
    var occudes='';
    for (var i = 0; i < rowData.length; i++) {
        console.log('Column ' + i + ': ' + rowData[i]);
        if (i==3) { 
            $('#occucode').val(rowData[i]);
            var occud=rowData[i];
        }
        if (i==4) { 
             var occudes=rowData[i];
        }
        if (i==0) { 
             $('#ocdate').val(rowData[i]);
             var ocdt=rowData[i];
        }
    }
    var modalTitle = document.getElementById('exampleModalLabel');
modalTitle.textContent = 'Occupation Wise Hands Details for Dated '+ocdt+' '+occud+'-'+occudes;
                    document.getElementById("workerdetails").style.display = "table";
                    initDataTable7();
                     openModal();


    }); 
  
//initDataTable1();
//initDataTable7();
           /* function initDataTable1() {
                $('#spgdailyrecordTable').DataTable().destroy();
             
                table = $('#spgdailyrecordTable').DataTable({
  //              "sDom": 'r<"H"lf><"datatable-scroll"t><"F"ip>',
                 "processing": true,
      //            "serverSide": true,
            //   "bFilter": false,*/

            function initDataTable1() {
            destroyAllTables()
            var att_sepll = $('#att_spell').val();
       //     alert(att_sepll);
            table = $('#mainpaydatatable').DataTable({
  //              "sDom": 'r<"H"lf><"datatable-scroll"t><"F"ip>',
                 "processing": true,
      //            "serverSide": true,
            //   "bFilter": false,
 
                ajax: {
                    url: '<?= base_url('Data_entry/getebmcdata') ?>',
                    type: 'POST',
                    data: function(d) {
                        d.periodfromdate = $('#advpfromdt').val();
                        d.periodtodate = $('#advptodt').val();
                        d.att_spell = $('#att_spell').val();
                        d.hol_get = $('#hol_get').val();
                        d.att_dept = $('#att_dept').val();
                     }
                  },columnDefs: [
                    { targets: [8], visible: true }, // Hide the first column (auto_id)
                    {
                    targets: [2, 3],
                    render: function(data, type, row, meta) {
                        return '<div class="column-align-right">' + data + '</div>';
                    }
                  }
                ],    
                "fnRowCallback": function(nRow, aData, iDisplayIndex, iDisplayIndexFull) {

                    if (aData[8] ==1) {
                        $('td', nRow).css('background-color', '#FBF6D9');
                    }     
                    if (aData[8] >1) {
                        $('td', nRow).css('background-color', '#E42217');

                    }     
    

},

         //       alert('newwww');
                drawCallback: function() {
                // Apply alignment styles to the table cells
                $('#recordTable td.column-align-center').css('text-align', 'center');
                $('#recordTable td.column-align-right').css('text-align', 'right');
            },
            fixedColumns: {
        leftColumns: 0 ,
                           "header": true

    },
    paging: false,
    scrollCollapse: true,
    scrollX: true,
    scrollY: 300// Set the default number of rows per page to 25
              });
        }

        function initDataTable2() {
            destroyAllTables()
            var att_sepll = $('#att_spell').val();
        //    alert(att_sepll);
            table = $('#handcomp').DataTable({
  //              "sDom": 'r<"H"lf><"datatable-scroll"t><"F"ip>',
                 "processing": true,
      //            "serverSide": true,
            //   "bFilter": false,
                    "order": false,
                ajax: {
                    url: '<?= base_url('Data_entry/getebmcdata') ?>',
                    type: 'POST',
                    data: function(d) {
                        d.periodfromdate = $('#advpfromdt').val();
                        d.periodtodate = $('#advptodt').val();
                        d.att_spell = $('#att_spell').val();
                        d.hol_get = $('#hol_get').val();
                        d.att_dept = $('#att_dept').val();
                     }
                  },columnDefs: [
                    { targets: [8], visible: true }, // Hide the first column (auto_id)
                    {
                    targets: [2, 3,4],
                    render: function(data, type, row, meta) {
                        return '<div class="column-align-center">' + data + '</div>';
                    }
                  },

                    {
                    targets: [5, 6,7,8,9,10,11,12],
                    render: function(data, type, row, meta) {
                        return '<div class="column-align-right">' + data + '</div>';
                    }
                  }
                ],    
                "fnRowCallback": function(nRow, aData, iDisplayIndex, iDisplayIndexFull) {
                   // alert(aData[2]);
                    if (aData[2] =='Total') {
                        $('td', nRow).css('font-weight', 'bold');
                        $('td', nRow).css('background-color', '#3498DB');
                    }     
                    if (aData[8] >1) {
                   //     $('td', nRow).css('background-color', '#E42217');

                    }     
    

},

         //       alert('newwww');
                drawCallback: function() {
                // Apply alignment styles to the table cells
                $('#handcomp td.column-align-center').css('text-align', 'center');
                $('#handcomp td.column-align-right').css('text-align', 'right');
            },
            fixedColumns: {
        leftColumns: 0 ,
                           "header": true

    },
    paging: false,
    scrollCollapse: true,
    scrollX: true,
    scrollY: 300// Set the default number of rows per page to 25
              });
        }



        function initDataTable2a() {
            destroyAllTables()
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
            
            

            function initDataTable3a() {
                destroyAllTables()
            var att_sepll = $('#att_spell').val();
      //      alert(att_sepll);
            table = $('#mcmusterrolltable').DataTable({
  //              "sDom": 'r<"H"lf><"datatable-scroll"t><"F"ip>',
                 "processing": true,
      //            "serverSide": true,
            //   "bFilter": false,
 
                ajax: {
                    url: '<?= base_url('Data_entry/getebmcdata') ?>',
                    type: 'POST',
                    data: function(d) {
                        d.periodfromdate = $('#advpfromdt').val();
                        d.periodtodate = $('#advptodt').val();
                        d.att_spell = $('#att_spell').val();
                        d.hol_get = $('#hol_get').val();
                        d.att_dept = $('#att_dept').val();
                     }
                  },columnDefs: [
                    { targets: [8], visible: true }, // Hide the first column (auto_id)
                    {
                    targets: [2, 3],
                    render: function(data, type, row, meta) {
                        return '<div class="column-align-right">' + data + '</div>';
                    }
                  }
                ],    
                "fnRowCallback": function(nRow, aData, iDisplayIndex, iDisplayIndexFull) {

                    if (aData[8] ==1) {
                        $('td', nRow).css('background-color', '#FBF6D9');
                    }     
                    if (aData[8] >1) {
                        $('td', nRow).css('background-color', '#E42217');

                    }     
    

},

         //       alert('newwww');
                drawCallback: function() {
                // Apply alignment styles to the table cells
                $('#recordTable td.column-align-center').css('text-align', 'center');
                $('#recordTable td.column-align-right').css('text-align', 'right');
            },

            fixedColumns: {
        leftColumns: 0 ,
                           "header": true

    },
    "responsive": true ,
        paging: false,
    scrollCollapse: true,
    scrollX: true,
    scrollY: 300// Set the default number of rows per page to 25
              });
        }

        $("#Excelprn").click(function(event){
      event.preventDefault(); 
	  alert ("acaaaa");
	  var opt=3;
             event.preventDefault();     
                var holget =  $('#hol_get').val();
                periodfromdate = $('#advpfromdt').val();
                periodtodate = $('#advptodt').val();
                var att_dept= $('#att_dept').val();
                var att_spell=$('#att_spell').val();
                var att_spell = ($('#att_spell').val()).substr(0,1);
//                alert(att_spell);
                if (holget==7) {
                    var url = '<?php echo site_url("Data_entry/workingdaydetailsexcel"); ?>' +
 
                      '?holget=' + holget+
                      '&periodfromdate=' + periodfromdate+
                      '&periodtodate=' + periodtodate+
                      '&att_dept=' + att_dept+
                      '&att_spell=' + att_spell
                        
                      ;
                }   
                if (holget==8) {
                    var url = '<?php echo site_url("Data_entry/extraadvancepayexcel"); ?>' +
 
                      '?holget=' + holget+
                      '&periodfromdate=' + periodfromdate+
                      '&periodtodate=' + periodtodate+
                      '&att_dept=' + att_dept+
                      '&att_spell=' + att_spell
                        
                      ;
                }   

                      alert(url);
			//$(location).attr('href',url);
			window.open( url, '_blank');
			
			
return false;
});
         
        function initDataTable10() {
            destroyAllTables()
            var att_sepll = ($('#att_spell').val()).substr(0,1);
    //        alert(att_sepll);
            table = $('#dailypay').DataTable({
        
                 "processing": true,
      //            "serverSide": true,
            //   "bFilter": false,
 
                ajax: {
                    url: '<?= base_url('Data_entry/getebmcdata') ?>',
                    type: 'POST',
                    data: function(d) {
                        d.periodfromdate = $('#advpfromdt').val();
                        d.periodtodate = $('#advptodt').val();
                        d.att_spell = att_sepll;
                        d.hol_get = $('#hol_get').val();
                        d.att_dept = $('#att_dept').val();
                     }
                  },columnDefs: [
                    { targets: [6], visible: true }, // Hide the first column (auto_id)
                    {
                    targets: [2, 3],
                    render: function(data, type, row, meta) {
                        return '<div class="column-align-right">' + data + '</div>';
                    }
                  }
                ],    
                "fnRowCallback": function(nRow, aData, iDisplayIndex, iDisplayIndexFull) {

                    if (aData[8] ==1) {
                        $('td', nRow).css('background-color', '#FBF6D9');
                    }     
                    if (aData[8] >1) {
                        $('td', nRow).css('background-color', '#E42217');

                    }     
    

},

         //       alert('newwww');
                drawCallback: function() {
                // Apply alignment styles to the table cells
                $('#recordTable td.column-align-center').css('text-align', 'center');
                $('#recordTable td.column-align-right').css('text-align', 'right');
            },
            fixedColumns: {
        leftColumns: 0 ,
                           "header": true

    },
    paging: false,
    scrollCollapse: true,
    scrollX: true,
    scrollY: 300// Set the default number of rows per page to 25
              });
        }


        
        function initDataTable3() {
            destroyAllTables()
            var att_sepll = $('#att_spell').val();
      //      alert(att_sepll);
            table = $('#musterrolltable').DataTable({
  //              "sDom": 'r<"H"lf><"datatable-scroll"t><"F"ip>',
                 "processing": true,
      //            "serverSide": true,
            //   "bFilter": false,
 
                ajax: {
                    url: '<?= base_url('Data_entry/getebmcdata') ?>',
                    type: 'POST',
                    data: function(d) {
                        d.periodfromdate = $('#advpfromdt').val();
                        d.periodtodate = $('#advptodt').val();
                        d.att_spell = $('#att_spell').val();
                        d.hol_get = $('#hol_get').val();
                        d.att_dept = $('#att_dept').val();
                     }
                  },columnDefs: [
                    { targets: [8], visible: true }, // Hide the first column (auto_id)
                    {
                    targets: [2, 3],
                    render: function(data, type, row, meta) {
                        return '<div class="column-align-right">' + data + '</div>';
                    }
                  }
                ],    
                "fnRowCallback": function(nRow, aData, iDisplayIndex, iDisplayIndexFull) {

                    if (aData[8] ==1) {
                        $('td', nRow).css('background-color', '#FBF6D9');
                    }     
                    if (aData[8] >1) {
                        $('td', nRow).css('background-color', '#E42217');

                    }     
    

},

         //       alert('newwww');
                drawCallback: function() {
                // Apply alignment styles to the table cells
                $('#recordTable td.column-align-center').css('text-align', 'center');
                $('#recordTable td.column-align-right').css('text-align', 'right');
            },
            fixedColumns: {
        leftColumns: 0 ,
                           "header": true

    },
    "responsive": true ,
        paging: false,
    scrollCollapse: true,
    scrollX: true,
    scrollY: 300// Set the default number of rows per page to 25
              });
        }

        
        function initDataTable8() {
            destroyAllTables()
            $('#workerdetails').DataTable().destroy();      
            var att_sepll = $('#occucode').val();
       //     alert(att_sepll);
            table = $('#workerdetails').DataTable({
                 "processing": true,
                    "order": false,
                ajax: {
                    url: '<?= base_url('Data_entry/getebmcdata') ?>',
                    type: 'POST',
                    data: function(d) {
                        d.periodfromdate = $('#advpfromdt').val();
                        d.periodtodate = $('#advptodt').val();
                        d.att_spell = $('#att_spell').val();
                        d.hol_get = $('#hol_get').val();
                        d.att_dept = $('#att_dept').val();
                        d.occucode = $('#occucode').val();
                    }
                  },columnDefs: [
                    { targets: [0], visible: false }, // Hide the first column (auto_id)
                    {
                    targets: [5, 6,7,8,9],
                    render: function(data, type, row, meta) {
                        return '<div class="column-align-right">' + data + '</div>';
                    }
                  }
                ],    
                "fnRowCallback": function(nRow, aData, iDisplayIndex, iDisplayIndexFull) {
                   // alert(aData[2]);
                    if (aData[2] =='Total') {
                       
                        $('td', nRow).css('background-color', '#3498DB');
                    }     
                    if (aData[8] >1) {
                   //     $('td', nRow).css('background-color', '#E42217');

                    }     
    

},

                drawCallback: function() {
                // Apply alignment styles to the table cells
                $('#workerdetails td.column-align-center').css('text-align', 'center');
                $('#workerdetails td.column-align-right').css('text-align', 'right');
            },
            fixedColumns: {
        leftColumns: 4 ,
                           "header": true

    },
    paging: false,
    scrollCollapse: true,
    scrollX: true,
    scrollY: 300// Set the default number of rows per page to 25
              });
        }


        function initDataTable7() {
            if ($.fn.DataTable.isDataTable('#handcompdet')) {
            $('#handcompdet').DataTable().destroy();
    }      
    
        table = $('#handcompdet').DataTable({
    ajax: {
        url: '<?= base_url('Data_entry/getworkerdetails') ?>',
                    type: 'POST',
                    data: function(d) {
                        d.periodfromdate = $('#ocdate').val();
                        d.periodtodate = $('#advptodt').val();
                        d.att_spell = $('#att_spell').val();
                        d.hol_get = $('#hol_get').val();
                        d.att_dept = $('#att_dept').val();
                        d.occucode = $('#occucode').val();
                    }
                 
        },columnDefs: [
        { targets: [0], visible: true }, // Hide the first column (auto_id)
        {
        targets: [5, 6],
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
    order:false,                 // Sort by the first column (auto_id) in descending order
    paging: false // Set the default number of rows per page to 25
  });
}


function initDataTable9() {
            if ($.fn.DataTable.isDataTable('#attdetails')) {
            $('#attdetails').DataTable().destroy();
    }      
    
        table = $('#attdetails').DataTable({
    ajax: {
        url: '<?= base_url('Data_entry/getworkerattdetails') ?>',
                    type: 'POST',
                    data: function(d) {
                        d.periodfromdate = $('#frdate').val();
                        d.periodtodate = $('#todate').val();
                        d.att_spell = $('#att_spell').val();
                        d.hol_get = $('#hol_get').val();
                        d.att_dept = $('#att_dept').val();
                        d.ebid = $('#ebid').val();
                    }
                 
        },columnDefs: [
        { targets: [0], visible: true }, // Hide the first column (auto_id)
        {
        targets: [5, 6],
        render: function(data, type, row, meta) {
            return '<div class="column-align-right">' + data + '</div>';
        }
      }
    ],
    drawCallback: function() {
                // Apply alignment styles to the table cells
                $('#attdetails td.column-align-center').css('text-align', 'center');
                $('#attdetails td.column-align-right').css('text-align', 'right');
            },
    paging: false,
    scrollCollapse: true,
    scrollX: true,
    scrollY: 300// Set the default number of rows per page to 25
  });
}


        $("#otherdatashow").click(function(event){
                event.preventDefault(); 

                var att_spell =  $('#att_spell').val();
                var holget =  $('#hol_get').val();
                periodfromdate = $('#advpfromdt').val();
                periodtodate = $('#advptodt').val();
                payschemeName = $('#payschemename').val();
                periodfromdate= periodfromdate.substring(8)+'-'+periodfromdate.substring(5,7)+'-'+periodfromdate.substring(0,4);
                periodtodate= periodtodate.substring(8)+'-'+periodtodate.substring(5,7)+'-'+periodtodate.substring(0,4);
                var hd1 = '';
                hideAllTables();
             //   alert(holget);
                if (holget==1) {
                    hd1 = 'Man Mechine List for ' + payschemeName + ' Period From ' + periodfromdate + ' To ' + periodtodate;
                    heading.textContent = hd1;
                    document.getElementById("payTitle").innerText = hd1;
                    document.getElementById("mainpaydatatable").style.display = "table";
                    initDataTable1();
                }
                if (holget==2) {
                    hd1 = 'Hands Completemt for '  + periodfromdate  ;
                    heading.textContent = hd1;
                    document.getElementById("payTitle").innerText = hd1;
                    document.getElementById("handcomp").style.display = "table";
                    initDataTable2();
                }
                if (holget==3) {
                    hd1 = 'Main Payroll Muster Roll List for ' + payschemeName + ' Period From ' + periodfromdate + ' To ' + periodtodate;
                    heading.textContent = hd1;
                    document.getElementById("payTitle").innerText = hd1;
                    document.getElementById("musterrolltable").style.display = "table";
                    initDataTable3();
                }
                if (holget==4) {
                    hd1 = 'Main Payroll Muuster Roll(M/C) for ' + payschemeName + ' Period From ' + periodfromdate + ' To ' + periodtodate;
                    heading.textContent = hd1;
                    document.getElementById("payTitle").innerText = hd1;
                    document.getElementById("mcmusterrolltable").style.display = "table";
                    initDataTable3a();
                }
 
                if (holget==7) {
                    hd1 = 'Employees Attendance Details for the Period From ' + periodfromdate + ' To ' + periodtodate;
                    heading.textContent = hd1;
                    document.getElementById("payTitle").innerText = hd1;
                    document.getElementById("workerdetails").style.display = "table";
                    initDataTable8();
                }
                if (holget==8) {
                    hd1 = 'Daily Extra Advance Payment Date '  + periodtodate;
                    heading.textContent = hd1;
                    document.getElementById("payTitle").innerText = hd1;
                    document.getElementById("dailypay").style.display = "table";
                    initDataTable10();
                }         

 
            });


 
    
   //     refreshDataTable();
      });

     

  
      $("#otherdataprint").click(function(event){
      event.preventDefault(); 
	//  alert ("aaaa");
	  var opt=3;
             event.preventDefault();     
                var att_payschm =  $('#att_payschm').val();
                var holget =  $('#hol_get').val();
                periodfromdate = $('#advpfromdt').val();
                periodtodate = $('#advptodt').val();
                payschemeName = $('#payschemename').val();
                att_spell = $('#att_spell').val();
                 att_dept = $('#att_dept').val();
      

                //                exportdbfdata
            var url = '<?php echo site_url("Data_entry/otherdataprint"); ?>' +
                      '?att_spell=' + att_spell +
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


      $('#handcompdet').DataTable();
    $('#handcomp').DataTable({
            stripeClasses: ['odd', 'even']
        });
    
   function openModal() {
            document.getElementById('myModal').style.display = 'block';
    }

    function closeModal() {
        document.getElementById('myModal').style.display = 'none';
    }

    function openattModal() {
            document.getElementById('attModal').style.display = 'block';
    }

    function closeattModal() {
        document.getElementById('attModal').style.display = 'none';
    }


    function njmmainpayslipcloseModal() {
    alert('close');
        document.getElementById('njmmainpayslipModal').style.display = 'none';
    }


    $("#njmmainpayslipclose").click(function(event){
           //    alert('closeb');
                document.getElementById('njmmainpayslipModal').style.display = 'none';
        });


      


        $('#hol_get').change(function() {
        hol_get = $('#hol_get').val();
        if (hol_get==9) {
            locafill();
            njmmainpayslipopenModal();
        }    
        if (hol_get==17) {
//            locafill();
//            nwdopenModal();
        }    
});     




   
    function njmmainpayslipopenModal() {
//    alert('open');
        hol_get = $('#hol_get').val();
        if (hol_get==9) {
            document.getElementById('njmmainpayslipModal').style.display = 'block';
        }    
        }

        $("#njmmainpayslipprint").click(function(event){
//          event.preventDefault(); 
            frm = $('#sub_location').val();
            twt=$('#trollywt').val();
            bwt=$('#busketwt').val();
            //    alert (frm);
            $.ajax({
            url: "<?php echo base_url('Data_entry_2/updatetrollydata'); ?>", 
            type: "POST",
            data: {frameid: frm,bwt:bwt,twt:twt },
            dataType: "json",
            success: function(response) {
               if (response.success) {
                    alert('REcord Updated Succssfully');
            } else {
                    $('#trollywt').val(0);
                    $('#busketywt').val(0);
                    alert('No data found');


             }
            }  
            });
 

        });     

        $('#sub_location').change(function() {
//          event.preventDefault(); 
            frm = $('#sub_location').val();
         
        
            $.ajax({
            url: "<?php echo base_url('Data_entry_2/gettrollydata'); ?>", 
            type: "POST",
            data: {frameid: frm },
            dataType: "json",
            success: function(response) {
               if (response.success) {
                var data = response.data[0]; // Assuming only one record is returned
                console.log(data); // Debug: Log the data to console
                
                // Ensure the selectors are correct and explicitly set the values
                $('#trollywt').val(data.trolly_weight);
                $('#busketwt').val(data.basket_weight);
                
               //     alert('REcord Updated Succssfully');
            } else {
                    $('#trollywt').val(0);
                    $('#busketywt').val(0);
                    alert('No data found');


             }
            }  
            });
 

        });     



        function locafill() {

event.preventDefault(); 
var periodfromdate= $('#advpfromdt').val();
var periodtodate= $('#advptodt').val();
var att_payschm =  $('#att_payschm').val();
var holget =  $('#hol_get').val();

//    alert('periodfromdate');
 //   alert (holget);

$.ajax({
url: "<?php echo base_url('Data_entry_2/trollydatafill'); ?>",
type: "POST",
data: {periodfromdate : periodfromdate,periodtodate : periodtodate,att_payschm: att_payschm,
    holget: holget},
    success: function(response) {
   //     alert('abc');
            $('#sub_location').empty(); // Clear previous options
            $('#sub_location').append('<option value="">Select Location</option>'); // Add default option

            // Populate the dropdown with received data
            $.each(response.locations, function(index, location) {
 //                          console.log(location);
 //                           console.log(location.trollyid);
              
                $('#sub_location').append('<option value="' + location[0] + '">' + location[1] + '</option>');
            });
        },
error: function(xhr, status, error) {
    console.error('AJAX Error: ' + error);
}
});


    
};    




  </script>
      