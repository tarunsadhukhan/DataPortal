
    <link rel="stylesheet" href="<?= base_url('public/dist-assets/css/select2.min.css'); ?>">
    <link rel="stylesheet" href="<?= base_url('public/dist-assets/css/select2-bootstrap.min.css'); ?>">
    <style>
        table {
            display: none;
        }
        .form-group {
            display: flex;
            flex-wrap: wrap;
            margin-bottom: 16px;
        }
        #holidayrecordTable thead th {
            background-color: #0f4d92  ; /* Background color for the header */
            color: #f8f8ff  ; /* Font color for the header */
            font-size: 14px; /* Font size for the header */
            word-wrap: no-worp;
            height: 10px; 
        }    
        #njmcntbankstatementTable thead th {
            background-color: #0f4d92  ; /* Background color for the header */
            color: #f8f8ff  ; /* Font color for the header */
            font-size: 14px; /* Font size for the header */
            word-wrap: no-worp;
            height: 10px; 
        }    

        #njmcntwagesTable thead th {
            background-color: #0f4d92  ; /* Background color for the header */
            color: #f8f8ff  ; /* Font color for the header */
            font-size: 14px; /* Font size for the header */
            word-wrap: no-worp;
            height: 10px; 
        }    


        #forthnightrecordTable thead th {
            background-color: #0f4d92  ; /* Background color for the header */
            color: #f8f8ff  ; /* Font color for the header */
            font-size: 14px; /* Font size for the header */
            word-wrap: no-worp;
            height: 10px; 
        }    
        #monthlyrecordTable thead th {
            background-color: #0f4d92 ; /* Background color for the header */
            color: #f8f8ff ; /* Font color for the header */
            font-size: 14px; /* Font size for the header */
            word-wrap: no-worp;
        }    
.thleft{
    padding:5px;
    border-left:1px solid #9d9d9d;
}
.thright{
    padding:5px;
    border-right:1px solid #9d9d9d;
}
.thtop{
    padding:5px;
    border-top:1px solid #9d9d9d;
}
.thbottom{
    padding:5px;
    border-bottom:1px solid #9d9d9d;
}

.checkbox-container {
            display: inline-block;
            position: relative;
            padding-left: 25px;
            margin-right: 15px;
            cursor: pointer;
        }

        .checkbox-container input {
            position: absolute;
            opacity: 0;
            cursor: pointer;
        }

        .checkmark {
            position: absolute;
            top: 0;
            left: 0;
            height: 20px;
            width: 20px;
            background-color: #eee;
            border-radius: 3px;
        }

        .checkbox-container:hover input ~ .checkmark {
            background-color: #ccc;
        }

        .checkbox-container input:checked ~ .checkmark {
            background-color: #2196F3;
        }

        .checkmark:after {
            content: "";
            position: absolute;
            display: none;
        }

        .checkbox-container input:checked ~ .checkmark:after {
            display: block;
        }

        .checkbox-container .checkmark:after {
            left: 7px;
            top: 3px;
            width: 5px;
            height: 10px;
            border: solid white;
            border-width: 0 3px 3px 0;
            transform: rotate(45deg);
        }
 


</style>
<style>
  /* 6in × 4in fixed size */
  .swal-fixed-size { width: 6in !important; height: 4in !important; }
  .swal-fixed-size .swal2-html-container { text-align: left; }
</style>
 <style>
 
.table-loader{position:fixed;inset:0;z-index:20000;background:rgba(255,255,255,.85);
  display:flex;align-items:center;justify-content:center;font-weight:600}
.loader-box{text-align:center}
.spinner-lg{width:40px;height:40px;margin:0 auto 10px;border:4px solid #ddd;border-top-color:#0d6efd;border-radius:50%;animation:spin .8s linear infinite}
#spinnerCounter{font-size:22px}
@keyframes spin{to{transform:rotate(360deg)}}


</style>    


<script src="<?= base_url('public/dist-assets/js/plugins/bootstrap.bundle.min.js'); ?>"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.min.css">
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/fixedcolumns/4.3.0/css/fixedColumns.dataTables.min.css">
<script src="https://cdn.datatables.net/fixedcolumns/4.3.0/js/dataTables.fixedColumns.min.js"></script>
<!-- SweetAlert2 CSS & JS -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="<?= base_url('assets/js/stlupload.js'); ?>"></script>
  
            <div class="reporthead"><?='Holiday/Attendance Incentive Data'?></div>

    <div class="form-group">
        <div class="col-12 col-sm-2">
            <label for="account-name">Period From Date/Holiday</label>
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
               <label for="email">Data For</label>
              <?php
                    $holget[1] = 'Holiday Data';
                    $holget[2] = 'Main Payroll FN Attendance Incentive';
                    $holget[3] = 'Forthnightly Attendance Incentive';
                    $holget[4] = 'Monthly Attendance Incentive';
                    $holget[5] = 'Attendance Data For EJM';
                    $holget[6] = 'Mill Att Data EJM';
                    $holget[7] = 'Forthnightly Attendance Incentive(T)';
                    $holget[8] = 'Monthly Attendance Incentive(T)';
                    $holget[9] = 'Attendance Data For NJM';
                    $holget[10] = 'Leave Transfer Data For NJM ';
                    $holget[11] = 'Sardar/Helper Hours Transfer  For NJM ';
                    $holget[12] = 'Beaming Production Data Transfer for NJM ';
                    $holget[13] = 'NJM wages data prepartion ';
                    $holget[14] = 'EJM Cash/Outsider Daily Payment prepartion ';
                    $holget[15] = 'EJM Std Hands Updation';
                    $holget[16] = 'T NO Other Data Updation';
                    $holget[17] = 'Non Working Date ';
                    $holget[18] = 'EJM-Main Payroll Attendance Data Transfer'; 
                    $holget[19] = 'EJM-Winding Production Data Transfer'; 
                    $holget[20] = 'EJM-Cumulative Data Transfer'; 
                    $holget[21] = 'EJM-Att Incentive Dept Summary'; 
                    $holget[22] = 'STL Data Stamement'; 
                    $holget[23] = 'Canteen Data Statement'; 
                    $holget[24] = 'Attendance Sheet';
                    $holget[25] = 'Daily Outsider Production(EJM)';
                    $holget[26] = 'Pay Roll Posting';
                    

                    echo form_dropdown('hol_get', $holget, ($hol_get ? $hol_get : "0"), 'id="hol_get"  class="myselect form-control form-control-rounded" data-placeholder="Select Holiday "  style="width:100%;"');
                ?>
              
              </div>


    </div>

    <div class="form-group">

     

    <div class="col-12 col-sm-2">
    	    <label for="purchaseDetailsPurchaseDate">Process Data <span class="text-center"></span></label>
                <button name="submit" id="hlincprocessdata"  type="submit" class="form-control btn btn-primary">Process</button>
            </div>
        <div class="col-12 col-sm-2">
    	    <label for="purchaseDetailsPurchaseDate">Data For Wages<span class="text-center"></span></label>
                <button name="submit" id="hlwagesincdata"  type="submit" class="form-control btn btn-primary">Data</button>
            </div>
            <div class="col-12 col-sm-2">
				<label for="purchaseDetailsPurchaseDate">Statements<span class="text-center"></span></label>
                <button name="submit" id="hlincstatement"  type="submit" class="form-control btn btn-danger">Report</button>
            </div>
            <div class="col-12 col-sm-2">
						    <label for="purchaseDetailsPurchaseDate">Print<span class="text-center"></span></label>
                <button name="submit" id="hlincprint"  type="submit" class="form-control btn btn-danger">Print</button>
            </div>
   
            <div class="col-12 col-sm-2">
				<label for="purchaseDetailsPurchaseDate">NJM Wages Data<span class="text-center"></span></label>
                <button name="submit" id="njmwagesdata"  type="submit" class="form-control btn btn-danger">Process</button>
            </div>
            <div class="col-12 col-sm-2">
				<label for="purchaseDetailsPurchaseDate">EJM Wages Data<span class="text-center"></span></label>
                <button name="submit" id="ejmwagesdata"  type="submit" class="form-control btn btn-danger">Process</button>
            </div>

<?php
               $company_id = $this->session->userdata('company_id');
               $company_name = $this->session->userdata('company_name');
               ?>
              <?php $this->load->view('modals/ejm_stl_modal'); ?>
              <?php $this->load->view('modals/ejm_wages_modal'); ?>

              <input type="hidden" class="input" value="<?php echo $company_id; ?>" id="companyId" />
              <input type="hidden" class="input" id="ebid" />
              <input type="hidden" class="input" id="record_id" />              
              <input type="hidden" class="input" id="prvamt" />              
              <input type="hidden" class="input" id="payschemename" />              

        </div> 


        <hr style="height:4px; background-color: #002e63  ;"></hr>
            <h4 id="heading" align="center" style="font-family:Droid Serif">Pay Register/Pay Slip</h4>

        <hr style="height:4px; background-color: #0f4d92 ;"></hr>
  
   <table id="holidayrecordTable" class="display">
        <thead>
            <tr>
                <th>Rec Id</th>
                <th>Emp Code</th>
                <th>Emp Name</th>
                <th>Holiday Date</th>
                <th>Holiday </th>
                <th>Holiday Hours</th>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>


    <table id="njmcntwgesTable" class="display">
        <thead>
            <tr>
                <th>Department</th>
                <th>EB_NO</th>
                <th>EMP Name </th>
                <th>Rate</th>
                <th>Days</th>
                <th>Amount</th>
                <th>Basic</th>
                <th>HRA</th>
                <th>Conveyance</th>
                <th>Other Allowance</th>
                <th>Uniform Allowance</th>
                <th>Medical Allowance</th>
                <th>Telephone</th>
                <th>Education</th>
                <th>Training</th>
                <th>GROSS1</th>
                <th>PF Employer</th>
                <th>ESI Employer</th>
                <th>PF Employee</th>
                <th>ESI Employee</th>
                <th>GROSS2</th>
                <th>Advance</th>
                <th>TA</th>
                <th>Plus Balance</th>
                <th>NET</th>

                </tr>
        </thead>
        <tbody>
        </tbody>
    </table>



    <table id="njmcntbankstatementTable" class="display">
        <thead>
            <tr>
                <th>SL No</th>
                <th>Emp Code</th>
                <th>Emp Name</th>
                <th>Bank Name</th>
                <th>IFSC Code </th>
                <th>Account No</th>
                <th>Net Pay</th>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>



    <table id="forthnightrecordTable" class="display">
        <thead>
            <tr>
                <th>Emp Code</th>
                <th>Emp Name</th>
                <th>Department</th>
                <th>Period From </th>
                <th>Period To</th>
                <th>Working Days</th>
                <th>Leave Days</th>
                <th>Att Inc Rate</th>
                <th>FN Attendance Incentive</th>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
    <table id="monthlyrecordTable" class="display">
        <thead>
            <tr>
                <th>Emp Code</th>
                <th>Emp Name</th>
                <th>Period From </th>
                <th>Period To</th>
                <th>Working Days</th>
                <th>Leave Days</th>
                <th>Att Inc Rate</th>
                <th>MN Attendance Incentive</th>
                </tr>
        </thead>
        <tbody>
        </tbody>
    </table>


<div id="tableLoader" class="table-loader" style="display:none;">
  <div class="loader-box">
    <div class="spinner-lg"></div>
    <div id="spinnerCounter" aria-live="polite">0</div>

    <div>Loading, please wait…</div>
  </div>
</div>

    
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
                document.getElementById("holidayrecordTable").style.display = "none";
                document.getElementById("forthnightrecordTable").style.display = "none";
                document.getElementById("monthlyrecordTable").style.display = "none";
                document.getElementById("njmcntbankstatementTable").style.display = "none";
                document.getElementById("njmcntwgesTable").style.display = "none";
                
        }

            function destroyAllTables() {
                $('#holidayrecordTable').DataTable().destroy();
                $('#forthnightrecordTable').DataTable().destroy();
                $('#monthlyrecordTable').DataTable().destroy();
                $('#njmcntbankstatementTable').DataTable().destroy();
                $('#njmcntwgesTable').DataTable().destroy();

            }



/* let spinnerTimer; // will hold our setInterval reference
let spinnerStart; // will store the start time

function showSpinner() {
  spinnerStart = Date.now();
  $('#spinnerText').text('Loading, please wait…');
  $('#tableLoader').show();

  // Update every second
  spinnerTimer = setInterval(() => {
    const elapsed = Math.floor((Date.now() - spinnerStart) / 1000);
    $('#spinnerText').text(`Loading... (${elapsed} sec)`);
  }, 1000);
}

function hideSpinner() {
  clearInterval(spinnerTimer);
  $('#tableLoader').hide();
}
 */

let spinnerTimer = null;
let spinnerCount = 0;

function showSpinnerCounter() {
  spinnerCount = 0;
  $('#spinnerCounter').text(spinnerCount);   // start at 0 (or 1 if you prefer)
  $('#tableLoader').show();

  spinnerTimer = setInterval(() => {
    spinnerCount += 1;
    $('#spinnerCounter').text(spinnerCount); // shows: 1, 2, 3, ...
  }, 1000);
}

function hideSpinnerCounter() {
  clearInterval(spinnerTimer);
  spinnerTimer = null;
  $('#tableLoader').hide();
}



/*     $('#getchecklist').change(function() {
        getchecklist = $('#getchecklist').val();
        alert(getchecklist);
    });
 */ 

    // Show the first table on page load

    $('#payschm').change(function() {
        payschm = $('#payschm').val();
 //       alert(payschm);

    });

    $('#ejm_payschm').change(function() {
        $('#payschm').val($('#ejm_payschm').val()).trigger('change');
    });

    $(document).on('change', '#ejm_getmenu', function() {
        if ($(this).val() == 1) {
            tareffopenModal();
        }
        if ($(this).val() == 2) {
            prodWagesModal();
        }
    });

    function checkTareffExisting() {
        var deptId = $('#tareff_dept_id').val();
        var targetType = $('#tareff_target_type').val();
        var effCodeId = $('#tareff_eff_code').val();
        var qualCode = $('#tareff_qual_code').val().trim();
        var dateFrom = $('#ejmfromdt').val();
        var dateTo = $('#ejmtodt').val();

        if (!deptId || deptId == '0' || !targetType || !dateFrom || !dateTo) {
            $('#tareff_target_id').val('');
            $('#tareff_target_save').show();
            $('#tareff_target_update').hide();
            return;
        }

        // For Efficiency: need eff_code to check
        if (targetType == 'EFF' && (!effCodeId || effCodeId == '0')) {
            $('#tareff_target_id').val('');
            $('#tareff_target_save').show();
            $('#tareff_target_update').hide();
            return;
        }

        // For Production: need qual_code to check
        if (targetType == 'PROD' && !qualCode) {
            $('#tareff_target_id').val('');
            $('#tareff_target_save').show();
            $('#tareff_target_update').hide();
            return;
        }

        $.ajax({
            url: "<?php echo base_url('Ejmprocessdata/get_fne_target_entry'); ?>",
            type: "POST",
            data: {
                dept_id: deptId,
                target_type: targetType.toString().substr(0,1), // get 'E' or 'P'
                eff_code: effCodeId,
                qual_code: qualCode,
                date_from: dateFrom,
                date_to: dateTo
            },
            dataType: "json",
            success: function(response) {
                if (response.exists) {
                    $('#tareff_target_id').val(response.all_trn_eff_id);
                    if (response.target_eff !== null && response.target_eff !== undefined) {
                        $('#tareff_target_eff').val(response.target_eff);
                    }
                    $('#tareff_target_save').hide();
                    $('#tareff_target_update').show();
                } else {
                    $('#tareff_target_id').val('');
                    $('#tareff_target_eff').val('');
                    $('#tareff_target_save').show();
                    $('#tareff_target_update').hide();
                }
            }
        });
    }

    $('#tareff_dept_id, #tareff_target_type, #tareff_eff_code, #ejmfromdt, #ejmtodt').on('change', function() {
        checkTareffExisting();
    });

    // Toggle Eff Code / Qual Code based on Target Type selection
    $('#tareff_target_type').on('change', function() {
        var targetType = $(this).val();
        if (targetType === 'EFF') {
            // Efficiency selected: enable Eff Code, disable Qual Code
            $('#tareff_eff_code').prop('disabled', false);
            $('#tareff_qual_code').prop('readonly', true).val('');
        } else if (targetType === 'PROD') {
            // Production selected: disable Eff Code, enable Qual Code
            $('#tareff_eff_code').prop('disabled', true).val('0');
            $('#tareff_qual_code').prop('readonly', false);
        } else {
            // No selection: enable both
            $('#tareff_eff_code').prop('disabled', false);
            $('#tareff_qual_code').prop('readonly', false);
        }
    });

    // Set initial state on load
    $('#tareff_target_type').trigger('change');

    $('#tareff_qual_code').on('blur change', function() {
        checkTareffExisting();
    });

    $('#tareff_target_save').click(function(event) {
        event.preventDefault();
        submitTareffEntry();
    });

    $('#tareff_target_update').click(function(event) {
        event.preventDefault();
        submitTareffEntry();
    });

    // Clone last fortnight data into current fortnight
    $('#tareff_clone').click(function(event) {
        event.preventDefault();
        var dateFrom = $('#ejmfromdt').val();
        var dateTo = $('#ejmtodt').val();

        if (!dateFrom || !dateTo) {
            alert('Please select From and To dates for the current fortnight');
            return;
        }

        if (!confirm('This will copy all target entries from the last fortnight into the current fortnight (' + dateFrom + ' to ' + dateTo + '). Continue?')) {
            return;
        }

        $.ajax({
            url: "<?php echo base_url('Ejmprocessdata/clone_last_fortnight_targets'); ?>",
            type: "POST",
            data: {
                date_from: dateFrom,
                date_to: dateTo
            },
            dataType: "json",
            success: function(response) {
                if (response.success) {
                    alert(response.message);
                } else {
                    alert(response.message || 'Clone failed');
                }
            },
            error: function() {
                alert('Error occurred while cloning data');
            }
        });
    });

    function submitTareffEntry() {
        var deptId = $('#tareff_dept_id').val();
        var targetType = $('#tareff_target_type').val();
        var effCodeId = $('#tareff_eff_code').val();
        var qualCode = $('#tareff_qual_code').val().trim();
        var targetEff = $('#tareff_target_eff').val().trim();
        var dateFrom = $('#ejmfromdt').val();
        var dateTo = $('#ejmtodt').val();
        var recordId = $('#tareff_target_id').val();

        if (!deptId || deptId == '0') {
            alert('Please Select Department');
            return;
        }

        if (!dateFrom || !dateTo) {
            alert('Please Select From and To Date');
            return;
        }

        if (targetType == 'EFF' && (!effCodeId || effCodeId == '0')) {
            alert('Please Select Eff Code');
            return;
        }

        if (targetType == 'PROD' && !qualCode) {
            alert('Please Enter Qual Code');
            return;
        }

        if (!targetEff) {
            alert('Please Enter Target Eff');
            return;
        }

        $.ajax({
            url: "<?php echo base_url('Ejmprocessdata/save_fne_target_entry'); ?>",
            type: "POST",
            data: {
                all_trn_eff_id: recordId,
                dept_id: deptId,
                target_type: targetType,
                eff_mast_code_id: effCodeId,
                qual_code: qualCode,
                target_eff: targetEff,
                date_from: dateFrom,
                date_to: dateTo
            },
            dataType: "json",
            success: function(response) {
                if (response.success) {
                    alert(response.message);
                    $('#tareff_target_id').val(response.all_trn_eff_id || recordId);
                    $('#tareff_target_save').hide();
                    $('#tareff_target_update').show();
                    loadTareffTargetTable();
                } else {
                    alert(response.message || 'Save failed');
                }
            }
        });
    }

    $('#hol_get').click(function() {
        hol_get = $('#hol_get').val();
//        alert(hol_get);
        if (hol_get==26) {
            locafill();
            paypostopenModal();
        }
    });

    $('#hol_get').change(function() {
        hol_get = $('#hol_get').val();
   //     alert(hol_get);
        if (hol_get==16) {
            locafill();
            tnoopenModal();
        }    
        if (hol_get==17) {
            locafill();
            nwdopenModal();
        }
        if (hol_get==22) {
            locafill();
            stlopenModal();
        }

        if (hol_get==23) {
            locafill();
            canteenopenModal();
                }
        if (hol_get==24) {
            locafill();
            attsheetopenModal();
        }

        if (hol_get==25) {
            locafill();
            oattprdopenModal();
        }
        if (hol_get==26) {
            locafill();
            paypostopenModal();
        }


});      





    // Add an event listener to the select element
    document.getElementById("hol_get").addEventListener("change", function () {
        hideAllTables(); // Hide all tables
        var selectedTable = this.value; // Get the selected value

        // Show the selected table
        document.getElementById(selectedTable).style.display = "table";

        var holget =  $('#hol_get').val();
//        alert(holget);
        if (holget==1) {
            document.getElementById("holidayrecordTable").style.display = "table";
            document.getElementById("forthnightrecordTable").style.display = "none";
            document.getElementById("monthlyrecordTable").style.display = "none";
            initDataTable1();
        }
        if (holget==2) {
            document.getElementById("holidayrecordTable").style.display = "none";
            document.getElementById("forthnightrecordTable").style.display = "table";
            document.getElementById("monthlyrecordTable").style.display = "none";
            initDataTable2();
        }
        if (holget==3) {
            document.getElementById("holidayrecordTable").style.display = "none";
            document.getElementById("forthnightrecordTable").style.display = "none";
            document.getElementById("monthlyrecordTable").style.display = "table";
            initDataTable3();
        }



    });

//            var fromdtInput = document.getElementById("fromdt");

// Set the min and max dates dynamically
/*
var minDate = "2023-11-01";
var maxDate = "2023-12-31";
fromdtInput.setAttribute("min", minDate);
fromdtInput.setAttribute("max", maxDate);
*/
 // Select all inputs with the "select-on-focus" class
$('.select-on-focus').on('focus', function() {
    this.select();
});
function closeModal() {
//    alert('close');
        document.getElementById('myModal').style.display = 'none';
    }
    function ejmcloseModal() {
//    alert('close');
        document.getElementById('ejmModal').style.display = 'none';
    }
    function stlcloseModal() {
//    alert('close');
        document.getElementById('stlModal').style.display = 'none';
    }


    function cntwagescloseModal() {
//    alert('close');
        document.getElementById('wagessummaryModal').style.display = 'none';
    }



    function canteencloseModal() {
//    alert('close');
        document.getElementById('canteenModal').style.display = 'none';
    }

    function wgsbrkcloseModal() {
//    alert('close');
        document.getElementById('wagesbrkModal').style.display = 'none';
    }


    function attsheetcloseModal() {
//    alert('close');
        document.getElementById('attsheetModal').style.display = 'none';
    }

    function oattprdcloseModal() {
    alert('close');
        document.getElementById('oattprdModal').style.display = 'none';
    }



    function tnocloseModal() {
//    alert('close');
        document.getElementById('TnoupdmyModal').style.display = 'none';
    }
    function nwdcloseModal() {
  //  alert('close');
        document.getElementById('nwdupdmyModal').style.display = 'none';
    }

function openModal() {
//    alert('open');
            document.getElementById('myModal').style.display = 'block';
}

function ejmopenModal() {
//    alert('open');
            document.getElementById('ejmModal').style.display = 'block';
}

function tareffopenModal() {
//    alert('open');
        document.getElementById('tareffModal').style.display = 'block';
        var dateFrom = $('#ejmfromdt').val();
        var dateTo = $('#ejmtodt').val();
        $('#tareff_date_display').text('From: ' + dateFrom + '  To: ' + dateTo);
        loadTareffTargetTable();
}

function prodWagesModal() {
//    alert('open');
        document.getElementById('prodWagesModal').style.display = 'block';
        var dateFrom = $('#ejmfromdt').val();
        var dateTo = $('#ejmtodt').val();
        $('#tareff_date_display').text('From: ' + dateFrom + '  To: ' + dateTo);
         loadProdWagesTable();
}



function loadTareffTargetTable() {
    var dateFrom = $('#ejmfromdt').val();
    var dateTo = $('#ejmtodt').val();
    if (!dateFrom || !dateTo) {
        $('#tareff_target_tbody').html('<tr><td colspan="5" class="text-center">Please select From and To dates</td></tr>');
        return;
    }
    alert('Loading FNE targets for period: ' + dateFrom + ' to ' + dateTo);

    // Destroy existing DataTable if initialized
    if ($.fn.DataTable.isDataTable('#tarrecordTable')) {
        $('#tarrecordTable').DataTable().destroy();
    }
    $('#tareff_target_tbody').html('<tr><td colspan="5" class="text-center">Loading...</td></tr>');

    $.ajax({
        url: "<?php echo base_url('Ejmprocessdata/get_all_fne_targets'); ?>",
        type: "POST",
        data: { date_from: dateFrom, date_to: dateTo },
        dataType: "json",
        success: function(response) {
            // Destroy again before reinit
            if ($.fn.DataTable.isDataTable('#tarrecordTable')) {
                $('#tarrecordTable').DataTable().destroy();
            }
            $('#tareff_target_tbody').empty();

            if (response.success && response.data && response.data.length > 0) {
                var html = '';
                $.each(response.data, function(i, row) {
                    html += '<tr>';
                    html += '<td>' + (row.dept_desc || row.dept_id) + '</td>';
                    html += '<td>' + (row.eff_mast_name || row.eff_code || '-') + '</td>';
                    html += '<td>' + (row.qual_code || '-') + '</td>';
                    html += '<td>' + (row.target_eff || '') + '</td>';
                    html += '<td><button class="btn btn-sm btn-info tareff-edit-btn" '
                         + 'data-id="' + row.all_trn_eff_id + '" '
                         + 'data-dept="' + row.dept_id + '" '
                         + 'data-type="' + row.target_type + '" '
                         + 'data-eff="' + (row.eff_code || '') + '" '
                         + 'data-qual="' + (row.qual_code || '') + '" '
                         + 'data-target="' + (row.target_eff || '') + '" '
                         + ' title="Edit" style="cursor:pointer;color:#0d6efd;background:none;border:none;font-size:14px;padding:2px 4px;">&#9998;</button> '
                         + '<button class="tareff-delete-btn" '
                         + 'data-id="' + row.all_trn_eff_id + '" '
                         + ' title="Delete" style="cursor:pointer;color:#dc3545;background:none;border:none;font-size:14px;padding:2px 4px;">&#128465;</button></td>';
                    html += '</tr>';
                });
                $('#tareff_target_tbody').html(html);
            }

            // Initialize DataTable with search, scroll
            $('#tarrecordTable').DataTable({
                searching: true,
                paging: true,
                pageLength: 10,
                scrollX: true,
                scrollY: '300px',
                scrollCollapse: true,
                ordering: true,
                info: true,
                autoWidth: false,
                language: {
                    emptyTable: 'No records found'
                }
            });
        },
        error: function() {
            $('#tareff_target_tbody').html('<tr><td colspan="5" class="text-center text-danger">Error loading data</td></tr>');
        }
    });
}

$(document).on('click', '.tareff-edit-btn', function() {
    var btn = $(this);
    $('#tareff_dept_id').val(btn.data('dept')).trigger('change');
    var ttype = btn.data('type');
    if (ttype === 'E') $('#tareff_target_type').val('EFF').trigger('change');
    else if (ttype === 'P') $('#tareff_target_type').val('PROD').trigger('change');
    $('#tareff_eff_code').val(btn.data('eff')).trigger('change');
    $('#tareff_qual_code').val(btn.data('qual'));
    $('#tareff_target_eff').val(btn.data('target'));
    $('#tareff_target_id').val(btn.data('id'));
    $('#tareff_target_save').hide();
    $('#tareff_target_update').show();
});

$(document).on('click', '.tareff-delete-btn', function() {
    var id = $(this).data('id');
    if (!confirm('Are you sure you want to delete this FNE target?')) return;
    $.ajax({
        url: "<?php echo base_url('Ejmprocessdata/delete_fne_target'); ?>",
        type: 'POST',
        data: { id: id },
        dataType: 'json',
        success: function(resp) {
            if (resp.success) {
                alert('Deleted successfully');
                loadTareffTargetTable();
            } else {
                alert(resp.message || 'Delete failed');
            }
        },
        error: function() { alert('Error deleting record'); }
    });
});

function tareffcloseModal() {
//    alert('close');
    document.getElementById('tareffModal').style.display = 'none';
}

// ========== Wages & Production Quality Link Modal ==========
function prodWagesOpenModal() {
    document.getElementById('prodWagesModal').style.display = 'block';
    loadProdWagesTable();
}

function prodWagesCloseModal() {
    document.getElementById('prodWagesModal').style.display = 'none';
}

$(document).on('click', '#pw_close_btn, #prodWagesCloseBtnX', function() {
    prodWagesCloseModal();
});

function prodWagesClearForm() {
    $('#pw_dept_id').val('0').trigger('change');
    $('#pw_prod_code').val('');
    $('#pw_wages_code').val('');
    $('#pw_code_type').val('');
    $('#pw_edit_id').val('');
    $('#pw_save_btn').show();
    $('#pw_update_btn').hide();
}

function loadProdWagesTable() {
    if ($.fn.DataTable.isDataTable('#prodWagesTable')) {
        $('#prodWagesTable').DataTable().destroy();
    }
    $('#pw_tbody').html('<tr><td colspan="5" class="text-center">Loading...</td></tr>');

    $.ajax({
        url: "<?php echo base_url('Ejmprocessdata/get_prod_wages_links'); ?>",
        type: "POST",
        dataType: "json",
        success: function(response) {
            if ($.fn.DataTable.isDataTable('#prodWagesTable')) {
                $('#prodWagesTable').DataTable().destroy();
            }
            $('#pw_tbody').empty();

            if (response.success && response.data && response.data.length > 0) {
                var html = '';
                $.each(response.data, function(i, row) {
                    html += '<tr>';
                    html += '<td>' + (row.dept_desc || row.dept_id) + '</td>';
                    html += '<td>' + row.prod_code + '</td>';
                    html += '<td>' + row.wages_code + '</td>';
                    html += '<td>' + (row.code_type || '') + '</td>';
                    html += '<td>'
                         + '<button class="btn btn-sm btn-info pw-edit-btn" '
                         + 'data-prod="' + row.prod_code + '" '
                         + 'data-wages="' + row.wages_code + '" '
                         + 'data-dept="' + row.dept_id + '" '
                         + 'data-type="' + (row.code_type || '') + '" '
                         + ' title="Edit" style="cursor:pointer;color:#0d6efd;background:none;border:none;font-size:14px;padding:2px 4px;">&#9998;</button> '
                         + '<button class="pw-delete-btn" '
                         + 'data-prod="' + row.prod_code + '" '
                         + 'data-dept="' + row.dept_id + '" '
                         + 'data-type="' + (row.code_type || '') + '" '
                         + ' title="Delete" style="cursor:pointer;color:#dc3545;background:none;border:none;font-size:14px;padding:2px 4px;">&#128465;</button>'
                         + '</td>';
                    html += '</tr>';
                });
                $('#pw_tbody').html(html);
            }

            $('#prodWagesTable').DataTable({
                searching: true,
                paging: true,
                pageLength: 10,
                scrollX: true,
                scrollY: '300px',
                scrollCollapse: true,
                ordering: true,
                info: true,
                autoWidth: false,
                language: { emptyTable: 'No records found' }
            });
        },
        error: function() {
            $('#pw_tbody').html('<tr><td colspan="5" class="text-center text-danger">Error loading data</td></tr>');
        }
    });
}

// Save
$(document).on('click', '#pw_save_btn', function() {
    var dept = $('#pw_dept_id').val();
    var prod = $('#pw_prod_code').val();
    var wages = $('#pw_wages_code').val();
    var ctype = $('#pw_code_type').val();
    if (!dept || dept == '0' || !prod || !wages) {
        alert('Please fill Department, Prod Code and Wages Code');
        return;
    }
    $.ajax({
        url: "<?php echo base_url('Ejmprocessdata/save_prod_wages_link'); ?>",
        type: "POST",
        data: { dept_id: dept, prod_code: prod, wages_code: wages, code_type: ctype },
        dataType: "json",
        success: function(response) {
            if (response.success) {
                alert('Saved successfully');
                prodWagesClearForm();
                loadProdWagesTable();
            } else {
                alert(response.message || 'Error saving');
            }
        },
        error: function() { alert('Error saving data'); }
    });
});

// Edit button click
$(document).on('click', '.pw-edit-btn', function() {
    var btn = $(this);
    $('#pw_dept_id').val(btn.data('dept')).trigger('change');
    $('#pw_prod_code').val(btn.data('prod'));
    $('#pw_wages_code').val(btn.data('wages'));
    $('#pw_code_type').val(btn.data('type'));
    $('#pw_edit_id').val(btn.data('prod') + '|' + btn.data('dept') + '|' + btn.data('type'));
    $('#pw_save_btn').hide();
    $('#pw_update_btn').show();
});

// Update
$(document).on('click', '#pw_update_btn', function() {
    var editId = $('#pw_edit_id').val();
    if (!editId) { alert('No record selected for update'); return; }
    var parts = editId.split('|');
    var dept = $('#pw_dept_id').val();
    var prod = $('#pw_prod_code').val();
    var wages = $('#pw_wages_code').val();
    var ctype = $('#pw_code_type').val();
    if (!dept || dept == '0' || !prod || !wages) {
        alert('Please fill Department, Prod Code and Wages Code');
        return;
    }
    $.ajax({
        url: "<?php echo base_url('Ejmprocessdata/update_prod_wages_link'); ?>",
        type: "POST",
        data: {
            old_prod_code: parts[0], old_dept_id: parts[1], old_code_type: parts[2],
            dept_id: dept, prod_code: prod, wages_code: wages, code_type: ctype
        },
        dataType: "json",
        success: function(response) {
            if (response.success) {
                alert('Updated successfully');
                prodWagesClearForm();
                loadProdWagesTable();
            } else {
                alert(response.message || 'Error updating');
            }
        },
        error: function() { alert('Error updating data'); }
    });
});

// Delete
$(document).on('click', '.pw-delete-btn', function() {
    if (!confirm('Are you sure you want to delete this record?')) return;
    var btn = $(this);
    $.ajax({
        url: "<?php echo base_url('Ejmprocessdata/delete_prod_wages_link'); ?>",
        type: "POST",
        data: { prod_code: btn.data('prod'), dept_id: btn.data('dept'), code_type: btn.data('type') },
        dataType: "json",
        success: function(response) {
            if (response.success) {
                alert('Deleted successfully');
                loadProdWagesTable();
            } else {
                alert(response.message || 'Error deleting');
            }
        },
        error: function() { alert('Error deleting data'); }
    });
});

// Clear
$(document).on('click', '#pw_clear_btn', function() {
    prodWagesClearForm();
});
// ========== End Wages & Production Quality Link Modal ==========

// ========== Attendance Preparation & Updation Modal ==========
function attPrepOpenModal() {
    document.getElementById('attPrepModal').style.display = 'block';
    var dateFrom = $('#ejmfromdt').val();
    var dateTo = $('#ejmtodt').val();
    var paySchmText = $('#ejm_payschm option:selected').map(function(){ return $(this).text(); }).get().join(', ');
    $('#attprep_date_from_display').text('From: ' + dateFrom);
    $('#attprep_date_to_display').text('To: ' + dateTo);
    $('#attprep_payschm_display').text('PayScheme: ' + paySchmText);
    loadAttPrepTable();
}

$(document).on('click', '#atp_close_btn, #attPrepCloseBtnX', function() {
    document.getElementById('attPrepModal').style.display = 'none';
});

function attPrepClearForm() {
    $('#atp_dept_code').val('0').trigger('change');
    $('#atp_eb_no').val('');
    $('#atp_emp_name').val('');
    $('#atp_occu_code').val('');
    $('#atp_atttype').val('');
    $('#atp_working_hours').val('0');
    $('#atp_ot_hours').val('0');
    $('#atp_ns_hours').val('0');
    $('#atp_edit_id').val('');
    $('#atp_save_btn').show();
    $('#atp_update_btn').hide();
}

// EB No lookup for attPrep
$(document).on('change', '#atp_eb_no', function() {
    var ebNo = $(this).val();
    if (!ebNo) { $('#atp_emp_name').val(''); return; }
    $.ajax({
        url: "<?php echo base_url('Ejmprocessdata/get_emp_name_by_eb'); ?>",
        type: "POST",
        data: { eb_no: ebNo },
        dataType: "json",
        success: function(res) {
            $('#atp_emp_name').val(res.success ? res.name : '');
        }
    });
});

function loadAttPrepTable() {
    var dateFrom = $('#ejmfromdt').val();
    var dateTo = $('#ejmtodt').val();
    var paySchm = $('#ejm_payschm').val();


    if ($.fn.DataTable.isDataTable('#attPrepTable')) {
        $('#attPrepTable').DataTable().destroy();
    }
    $('#atp_tbody').html('<tr><td colspan="12" class="text-center">Loading...</td></tr>');

    $.ajax({
        url: "<?php echo base_url('Ejmprocessdata/get_att_prep_data'); ?>",
        type: "POST",
        data: { date_from: dateFrom, date_to: dateTo, pay_scheme_id: paySchm },
        dataType: "json",
        success: function(response) {
            if ($.fn.DataTable.isDataTable('#attPrepTable')) {
                $('#attPrepTable').DataTable().destroy();
            }
            $('#atp_tbody').empty();

            if (response.success && response.data && response.data.length > 0) {
                var html = '';
                $.each(response.data, function(i, row) {
                    html += '<tr>';
                    html += '<td>' + (row.dept_code || '') + '</td>';
                    html += '<td>' + (row.dept_desc || '') + '</td>';
                    html += '<td>' + (row.eb_no || '') + '</td>';
                    html += '<td>' + (row.emp_name || '') + '</td>';
                    html += '<td>' + (row.occu_code || '') + '</td>';
                    html += '<td>' + (row.shift || '') + '</td>';
                    html += '<td>' + (row.working_hours || 0) + '</td>';
                    html += '<td>' + (row.ot_hours || 0) + '</td>';
                    html += '<td>' + (row.ns_hours || 0) + '</td>';
                    html += '<td>' + (row.pay_scheme_id || '') + '</td>';
                    html += '<td>'
                         + '<button class="btn btn-sm btn-info atp-edit-btn" '
                         + 'data-id="' + (row.att_summary_id || '') + '" '
                         + 'data-dept="' + row.dept_code + '" '
                         + 'data-deptid="' + (row.dept_id || '') + '" '
                         + 'data-ebno="' + (row.eb_no || '') + '" '
                         + 'data-empname="' + (row.emp_name || '') + '" '
                         + 'data-occu="' + (row.occu_code || '') + '" '
                         + 'data-atttype="' + (row.shift || '') + '" '
                         + 'data-wh="' + (row.working_hours || 0) + '" '
                         + 'data-ot="' + (row.ot_hours || 0) + '" '
                         + 'data-ns="' + (row.ns_hours || 0) + '" '
                         + ' title="Edit" style="cursor:pointer;color:#0d6efd;background:none;border:none;font-size:14px;padding:2px 4px;">&#9998;</button> '
                         + '<button class="atp-delete-btn" '
                         + 'data-id="' + (row.att_summary_id || '') + '" '
                         + ' title="Delete" style="cursor:pointer;color:#dc3545;background:none;border:none;font-size:14px;padding:2px 4px;">&#128465;</button>'
                         + '</td>';
                    html += '</tr>';
                });
                $('#atp_tbody').html(html);
            }

            $('#attPrepTable').DataTable({
                searching: true,
                paging: true,
                pageLength: 10,
                scrollX: true,
                scrollY: '300px',
                scrollCollapse: true,
                ordering: true,
                info: true,
                autoWidth: false,
                language: { emptyTable: 'No records found' }
            });
        },
        error: function() {
            $('#atp_tbody').html('<tr><td colspan="12" class="text-center text-danger">Error loading data</td></tr>');
        }
    });
}

// Save
$(document).on('click', '#atp_save_btn', function() {
    var dateFrom = $('#ejmfromdt').val();
    var dateTo = $('#ejmtodt').val();
    var paySchm = $('#ejm_payschm').val();
    var dept = $('#atp_dept_code').val();
    var ebno = $('#atp_eb_no').val();
    var occu = $('#atp_occu_code').val();
    var atttype = $('#atp_atttype').val();
    var wh = $('#atp_working_hours').val();
    var ot = $('#atp_ot_hours').val();
    var ns = $('#atp_ns_hours').val();
    
    if (!dept || dept == '0') { alert('Please select Department'); return; }
    $('#atp_save_btn').prop('disabled', true);
    $.ajax({
        url: "<?php echo base_url('Ejmprocessdata/save_att_prep'); ?>",
        type: "POST",
        data: { date_from: dateFrom, date_to: dateTo, dept_code: dept, eb_no: ebno, occu_code: occu, shift: atttype, working_hours: wh, ot_hours: ot, ns_hours: ns, pay_scheme_id: paySchm },
        dataType: "json",
        success: function(response) {
            if (response.success) {
                alert('Saved successfully');
                attPrepClearForm();
                loadAttPrepTable();
            } else {
                alert(response.message || 'Error saving');
            }
            $('#atp_save_btn').prop('disabled', false);
        },
        error: function() { alert('Error saving data'); $('#atp_save_btn').prop('disabled', false); }
    });
});

// Edit
$(document).on('click', '.atp-edit-btn', function() {
    var btn = $(this);
    $('#atp_dept_code').val(btn.data('deptid')).trigger('change');
    $('#atp_eb_no').val(btn.data('ebno'));
    $('#atp_emp_name').val(btn.data('empname'));
    $('#atp_occu_code').val(btn.data('occu'));
    $('#atp_atttype').val(btn.data('atttype'));
    $('#atp_working_hours').val(btn.data('wh'));
    $('#atp_ot_hours').val(btn.data('ot'));
    $('#atp_ns_hours').val(btn.data('ns'));
    $('#atp_edit_id').val(btn.data('id'));
    $('#atp_save_btn').hide();
    $('#atp_update_btn').show();
});

// Update
$(document).on('click', '#atp_update_btn', function() {
    var editId = $('#atp_edit_id').val();
    if (!editId) { alert('No record selected'); return; }
    var dateFrom = $('#ejmfromdt').val();
    var dateTo = $('#ejmtodt').val();
    var paySchm = $('#ejm_payschm').val();
    var dept = $('#atp_dept_code').val();
    var ebno = $('#atp_eb_no').val();
    var occu = $('#atp_occu_code').val();
    var atttype = $('#atp_atttype').val();
    var wh = $('#atp_working_hours').val();
    var ot = $('#atp_ot_hours').val();
    var ns = $('#atp_ns_hours').val();
    if (!dept || dept == '0') { alert('Please select Department'); return; }
    $('#atp_update_btn').prop('disabled', true);
    $.ajax({
        url: "<?php echo base_url('Ejmprocessdata/update_att_prep'); ?>",
        type: "POST",
        data: {
            att_summary_id: editId,
            date_from: dateFrom, date_to: dateTo,
            dept_code: dept, eb_no: ebno, occu_code: occu, shift: atttype,
            working_hours: wh, ot_hours: ot, ns_hours: ns, pay_scheme_id: paySchm
        },
        dataType: "json",
        success: function(response) {
            if (response.success) {
                alert('Updated successfully');
                attPrepClearForm();
                loadAttPrepTable();
            } else {
                alert(response.message || 'Error updating');
            }
            $('#atp_update_btn').prop('disabled', false);
        },
        error: function() { alert('Error updating data'); $('#atp_update_btn').prop('disabled', false); }
    });
});

// Delete
$(document).on('click', '.atp-delete-btn', function() {
    if (!confirm('Are you sure you want to delete this record?')) return;
    var btn = $(this);
    btn.prop('disabled', true);
    $.ajax({
        url: "<?php echo base_url('Ejmprocessdata/delete_att_prep'); ?>",
        type: "POST",
        data: { att_summary_id: btn.data('id') },
        dataType: "json",
        success: function(response) {
            if (response.success) {
                alert('Deleted successfully');
                loadAttPrepTable();
            } else {
                alert(response.message || 'Error deleting');
                btn.prop('disabled', false);
            }
        },
        error: function() { alert('Error deleting data'); btn.prop('disabled', false); }
    });
});

// Process - insert into tbl_ejm_wages_att_summary
$(document).on('click', '#atp_process_btn', function() {
    var dateFrom = $('#ejmfromdt').val();
    var dateTo = $('#ejmtodt').val();
    var paySchm = $('#ejm_payschm').val();
    
    var dept = $('#atp_dept_code').val();
    alert(paySchm);
    var allowedSchemes = ['151', '125', '161'];
    var paySchmArray = Array.isArray(paySchm) ? paySchm : [paySchm];
    var isValidScheme = paySchmArray.some(function(v){ return allowedSchemes.indexOf(v) !== -1; });
    if (!paySchm || paySchm == '0' || !isValidScheme) {
        alert('Please select Pay Scheme (151, 125 or 161)'); return;
    }
    if (!dateFrom || !dateTo) { alert('Please select From and To dates'); return; }
    if (!confirm('Process attendance data and insert into wages summary?')) return;
    $('#atp_process_btn').prop('disabled', true);
//    showSpinnerCounter();
    $.ajax({
        url: "<?php echo base_url('Ejmprocessdata/process_att_prep'); ?>",
        type: "POST",
        data: { date_from: dateFrom, date_to: dateTo, pay_scheme_id: paySchm, dept_code: dept },
        dataType: "json",
        success: function(response) {
//            hideSpinnerCounter();
            if (response.success) {
                alert('Processed successfully. ' + (response.count || '') + ' records inserted.');
                loadAttPrepTable();
            } else {
                alert(response.message || 'Error processing');
            }
            $('#atp_process_btn').prop('disabled', false);
        },
        error: function() { hideSpinnerCounter(); alert('Error processing data'); $('#atp_process_btn').prop('disabled', false); }
    });
});

// Reset
$(document).on('click', '#atp_reset_btn', function() {
    attPrepClearForm();
});
// ========== End Attendance Preparation & Updation Modal ==========

// ========== Advance & Other Entries Modal ==========
function advOthOpenModal() {
    document.getElementById('advOthModal').style.display = 'block';
    var dateFrom = $('#ejmfromdt').val();
    var dateTo = $('#ejmtodt').val();
    $('#advoth_date_display').text('From: ' + dateFrom + '  To: ' + dateTo);
    loadAdvOthTable();
}

$(document).on('click', '#adv_close_btn, #advOthCloseBtnX', function() {
    document.getElementById('advOthModal').style.display = 'none';
});

function advOthClearForm() {
    $('#adv_eb_no').val('');
    $('#adv_emp_name').val('');
    $('#adv_stl_days').val('0');
    $('#adv_puja_advance').val('0');
    $('#adv_ot_advance').val('0');
    $('#adv_installment_advance').val('0');
    $('#adv_stl_advance').val('0');
    $('#adv_co_loan').val('0');
    $('#adv_misc_earn').val('0');
    $('#adv_misc_ded').val('0');
    $('#adv_misc_ot_earn').val('0');
    $('#adv_misc_ot_ded').val('0');
    $('#adv_edit_id').val('');
    $('#adv_save_btn').show();
    $('#adv_update_btn').hide();
}

// Fetch employee name on EB No blur
$(document).on('blur', '#adv_eb_no', function() {
    var ebNo = $(this).val();
    if (!ebNo) { $('#adv_emp_name').val(''); return; }
    $.ajax({
        url: "<?php echo base_url('Ejmprocessdata/get_emp_name_by_eb'); ?>",
        type: "POST",
        data: { eb_no: ebNo },
        dataType: "json",
        success: function(response) {
            if (response.success && response.name) {
                $('#adv_emp_name').val(response.name);
            } else {
                $('#adv_emp_name').val('Not Found');
            }
        },
        error: function() { $('#adv_emp_name').val(''); }
    });
});

function loadAdvOthTable() {
    var dateFrom = $('#ejmfromdt').val();
    var dateTo = $('#ejmtodt').val();

    if ($.fn.DataTable.isDataTable('#advOthTable')) {
        $('#advOthTable').DataTable().destroy();
    }
    $('#adv_tbody').html('<tr><td colspan="13" class="text-center">Loading...</td></tr>');

    $.ajax({
        url: "<?php echo base_url('Ejmprocessdata/get_adv_oth_data'); ?>",
        type: "POST",
        data: { date_from: dateFrom, date_to: dateTo },
        dataType: "json",
        success: function(response) {
            if ($.fn.DataTable.isDataTable('#advOthTable')) {
                $('#advOthTable').DataTable().destroy();
            }
            $('#adv_tbody').empty();

            if (response.success && response.data && response.data.length > 0) {
                var html = '';
                $.each(response.data, function(i, row) {
                    html += '<tr>';
                    html += '<td>' + (row.eb_no || '') + '</td>';
                    html += '<td>' + (row.emp_name || '') + '</td>';
                    html += '<td>' + (row.stl_days || 0) + '</td>';
                    html += '<td>' + (row.puja_advance || 0) + '</td>';
                    html += '<td>' + (row.ot_advance || 0) + '</td>';
                    html += '<td>' + (row.installment_advance || 0) + '</td>';
                    html += '<td>' + (row.stl_advance || 0) + '</td>';
                    html += '<td>' + (row.co_loan || 0) + '</td>';
                    html += '<td>' + (row.misc_earn || 0) + '</td>';
                    html += '<td>' + (row.misc_ded || 0) + '</td>';
                    html += '<td>' + (row.misc_ot_earn || 0) + '</td>';
                    html += '<td>' + (row.misc_ot_ded || 0) + '</td>';
                    html += '<td>'
                         + '<button class="btn btn-sm btn-info adv-edit-btn" '
                         + 'data-id="' + (row.id || '') + '" '
                         + 'data-ebno="' + (row.eb_no || '') + '" '
                         + 'data-name="' + (row.emp_name || '') + '" '
                         + 'data-stldays="' + (row.stl_days || 0) + '" '
                         + 'data-puja="' + (row.puja_advance || 0) + '" '
                         + 'data-otadv="' + (row.ot_advance || 0) + '" '
                         + 'data-instadv="' + (row.installment_advance || 0) + '" '
                         + 'data-stladv="' + (row.stl_advance || 0) + '" '
                         + 'data-coloan="' + (row.co_loan || 0) + '" '
                         + 'data-miscearn="' + (row.misc_earn || 0) + '" '
                         + 'data-miscded="' + (row.misc_ded || 0) + '" '
                         + 'data-miscotearn="' + (row.misc_ot_earn || 0) + '" '
                         + 'data-miscotded="' + (row.misc_ot_ded || 0) + '" '
                         + ' title="Edit" style="cursor:pointer;color:#0d6efd;background:none;border:none;font-size:14px;padding:2px 4px;">&#9998;</button> '
                         + '<button class="adv-delete-btn" '
                         + 'data-id="' + (row.id || '') + '" '
                         + ' title="Delete" style="cursor:pointer;color:#dc3545;background:none;border:none;font-size:14px;padding:2px 4px;">&#128465;</button>'
                         + '</td>';
                    html += '</tr>';
                });
                $('#adv_tbody').html(html);
            }

            $('#advOthTable').DataTable({
                searching: true,
                paging: true,
                pageLength: 10,
                scrollX: true,
                scrollY: '300px',
                scrollCollapse: true,
                ordering: true,
                info: true,
                autoWidth: false,
                language: { emptyTable: 'No records found' }
            });
        },
        error: function() {
            $('#adv_tbody').html('<tr><td colspan="13" class="text-center text-danger">Error loading data</td></tr>');
        }
    });
}

// Save
$(document).on('click', '#adv_save_btn', function() {
    var dateFrom = $('#ejmfromdt').val();
    var dateTo = $('#ejmtodt').val();
    var ebNo = $('#adv_eb_no').val();
    if (!ebNo) { alert('Please enter EB No'); return; }
    $.ajax({
        url: "<?php echo base_url('Ejmprocessdata/save_adv_oth'); ?>",
        type: "POST",
        data: {
            date_from: dateFrom, date_to: dateTo, eb_no: ebNo,
            stl_days: $('#adv_stl_days').val(), puja_advance: $('#adv_puja_advance').val(),
            ot_advance: $('#adv_ot_advance').val(), installment_advance: $('#adv_installment_advance').val(),
            stl_advance: $('#adv_stl_advance').val(), co_loan: $('#adv_co_loan').val(),
            misc_earn: $('#adv_misc_earn').val(), misc_ded: $('#adv_misc_ded').val(),
            misc_ot_earn: $('#adv_misc_ot_earn').val(), misc_ot_ded: $('#adv_misc_ot_ded').val()
        },
        dataType: "json",
        success: function(response) {
            if (response.success) {
                alert('Saved successfully');
                advOthClearForm();
                loadAdvOthTable();
            } else {
                alert(response.message || 'Error saving');
            }
        },
        error: function() { alert('Error saving data'); }
    });
});

// Edit
$(document).on('click', '.adv-edit-btn', function() {
    var btn = $(this);
    $('#adv_eb_no').val(btn.data('ebno'));
    $('#adv_emp_name').val(btn.data('name'));
    $('#adv_stl_days').val(btn.data('stldays'));
    $('#adv_puja_advance').val(btn.data('puja'));
    $('#adv_ot_advance').val(btn.data('otadv'));
    $('#adv_installment_advance').val(btn.data('instadv'));
    $('#adv_stl_advance').val(btn.data('stladv'));
    $('#adv_co_loan').val(btn.data('coloan'));
    $('#adv_misc_earn').val(btn.data('miscearn'));
    $('#adv_misc_ded').val(btn.data('miscded'));
    $('#adv_misc_ot_earn').val(btn.data('miscotearn'));
    $('#adv_misc_ot_ded').val(btn.data('miscotded'));
    $('#adv_edit_id').val(btn.data('id'));
    $('#adv_save_btn').hide();
    $('#adv_update_btn').show();
});

// Update
$(document).on('click', '#adv_update_btn', function() {
    var editId = $('#adv_edit_id').val();
    if (!editId) { alert('No record selected'); return; }
    var dateFrom = $('#ejmfromdt').val();
    var dateTo = $('#ejmtodt').val();
    $.ajax({
        url: "<?php echo base_url('Ejmprocessdata/update_adv_oth'); ?>",
        type: "POST",
        data: {
            id: editId, date_from: dateFrom, date_to: dateTo, eb_no: $('#adv_eb_no').val(),
            stl_days: $('#adv_stl_days').val(), puja_advance: $('#adv_puja_advance').val(),
            ot_advance: $('#adv_ot_advance').val(), installment_advance: $('#adv_installment_advance').val(),
            stl_advance: $('#adv_stl_advance').val(), co_loan: $('#adv_co_loan').val(),
            misc_earn: $('#adv_misc_earn').val(), misc_ded: $('#adv_misc_ded').val(),
            misc_ot_earn: $('#adv_misc_ot_earn').val(), misc_ot_ded: $('#adv_misc_ot_ded').val()
        },
        dataType: "json",
        success: function(response) {
            if (response.success) {
                alert('Updated successfully');
                advOthClearForm();
                loadAdvOthTable();
            } else {
                alert(response.message || 'Error updating');
            }
        },
        error: function() { alert('Error updating data'); }
    });
});

// Delete
$(document).on('click', '.adv-delete-btn', function() {
    if (!confirm('Are you sure you want to delete this record?')) return;
    var id = $(this).data('id');
    $.ajax({
        url: "<?php echo base_url('Ejmprocessdata/delete_adv_oth'); ?>",
        type: "POST",
        data: { id: id },
        dataType: "json",
        success: function(response) {
            if (response.success) {
                alert('Deleted successfully');
                loadAdvOthTable();
            } else {
                alert(response.message || 'Error deleting');
            }
        },
        error: function() { alert('Error deleting data'); }
    });
});

// Installment Processing
$(document).on('click', '#adv_installment_btn', function() {
    var dateFrom = $('#ejmfromdt').val();
    var dateTo = $('#ejmtodt').val();
    if (!dateFrom || !dateTo) { alert('Please select From and To dates'); return; }
    if (!confirm('Process installment advances for the selected period?')) return;
    showSpinnerCounter();
    $.ajax({
        url: "<?php echo base_url('Ejmprocessdata/process_installment_adv'); ?>",
        type: "POST",
        data: { date_from: dateFrom, date_to: dateTo },
        dataType: "json",
        success: function(response) {
            hideSpinnerCounter();
            if (response.success) {
                alert('Installment processed successfully. ' + (response.count || '') + ' records.');
                loadAdvOthTable();
            } else {
                alert(response.message || 'Error processing installments');
            }
        },
        error: function() { hideSpinnerCounter(); alert('Error processing installments'); }
    });
});
// ========== End Advance & Other Entries Modal ==========

function syncEjmToNjm() {
    $('#njmcntfromdt').val($('#ejmfromdt').val());
    $('#njmcnttodt').val($('#ejmtodt').val());
    $('#cadded').val($('#ejmcadded').val());
    $('#vardded').val($('#ejmvardded').val());
    $('#gwfded').val($('#ejmgwfded').val());
    $('#att_dept').val($('#ejm_att_dept').val());
    $('#payschm').val($('#ejm_payschm').val()).trigger('change');
    $('#getmenu').val($('#ejm_getmenu').val());
    $('#getchecklist').val($('#ejm_getchecklist').val());
}

function stlopenModal() {
//    alert('open');
            document.getElementById('stlModal').style.display = 'block';
}

function cntwagesopenModal() {
//    alert('open');
            document.getElementById('wagessummaryModal').style.display = 'block';
}



function canteenopenModal() {
//    alert('open');
            document.getElementById('canteenModal').style.display = 'block';
}

function paypostopenModal() {
//    alert('open');
            document.getElementById('paypostModal').style.display = 'block';
}
function paypostcloseModal() {
  //  alert('close');
        document.getElementById('paypostModal').style.display = 'none';
    }
 


function attsheetopenModal() {
//    alert('open');
            document.getElementById('attsheetModal').style.display = 'block';
}

function wagesbrkModal() {
 //   alert('open');
            document.getElementById('wagesbrkModal').style.display = 'block';
}



function oattprdopenModal() {
//    alert('open');
            document.getElementById('oattprdModal').style.display = 'block';
}



    function tnoopenModal() {
//    alert('open');
        hol_get = $('#hol_get').val();
        if (hol_get==16) {
            document.getElementById('TnoupdmyModal').style.display = 'block';
        }    
        }
        function nwdopenModal() {
//    alert('open');
        hol_get = $('#hol_get').val();
        if (hol_get==17) {
            document.getElementById('nwdupdmyModal').style.display = 'block';
        }    
        }

 
            

            function initDataTable1() {
                $('#holidayrecordTable').DataTable().destroy();
            $('#forthnightrecordTable').DataTable().destroy();
            $('#monthlyrecordTable').DataTable().destroy();
            
                table = $('#holidayrecordTable').DataTable({
  //              "sDom": 'r<"H"lf><"datatable-scroll"t><"F"ip>',
                 "processing": true,
      //            "serverSide": true,
            //   "bFilter": false,
 
                ajax: {
                    url: '<?= base_url('Data_entry/getholiday_data') ?>',
                    type: 'POST',
                    data: function(d) {
                        d.periodfromdate = $('#advpfromdt').val();
                        d.periodtodate = $('#advptodt').val();
                        d.att_payschm = $('#att_payschm').val();
                        d.hol_get = $('#hol_get').val();
                     }
                  },columnDefs: [
                    { targets: [0], visible: false }, // Hide the first column (auto_id)
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
            order: false, 
                           pageLength: 10 // Set the default number of rows per page to 25
              });
        }



      function initDataTablebank4() {
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
     




           function initDataTablecntbank() {
                destroyAllTables();
                        var periodfromdate = $('#njmcntfromdt').val();
                     alert(periodfromdate);
                        var periodtodate = $('#njmcnttodt').val();
                table = $('#njmcntbankstatementTable').DataTable({
  //              "sDom": 'r<"H"lf><"datatable-scroll"t><"F"ip>',
                 "processing": true,
      //            "serverSide": true,
            //   "bFilter": false,
 
                ajax: {
                    url: '<?= base_url('Njmwagesprocess/getnjmcntbnkstatement') ?>',
                    type: 'POST',
                    data: function(d) {
                        d.periodfromdate = $('#njmcntfromdt').val();
                        d.periodtodate = $('#njmcnttodt').val();
                        d.att_payschm = $('#att_payschm').val();
                        d.hol_get = $('#hol_get').val();
                        d.exlprn=101    
                     }
                  },columnDefs: [
                    { targets: [0], visible: false }, // Hide the first column (auto_id)
                    {
                    targets: [0],
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
            order: false, 
                           pageLength: 10 // Set the default number of rows per page to 25
              });
              alert(url)
        }



        

        function initDataTable2() {
            $('#holidayrecordTable').DataTable().destroy();
            $('#forthnightrecordTable').DataTable().destroy();
            $('#monthlyrecordTable').DataTable().destroy();
               table = $('#forthnightrecordTable').DataTable({
                "processing": true,
//                  "serverSide": true,
                ajax: {
                    url: '<?= base_url('Data_entry/getFNattincentiveData') ?>',
                    type: 'POST',
                    data: function(d) {
                        d.periodfromdate = $('#advpfromdt').val();
                        d.periodtodate = $('#advptodt').val();
                        d.att_payschm = $('#att_payschm').val();
                        d.holget = $('#hol_get').val();
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
            order: false,
                            pageLength: 10 // Set the default number of rows per page to 25
              });
        }


        function initDataTable3() {
            $('#holidayrecordTable').DataTable().destroy();
            $('#forthnightrecordTable').DataTable().destroy();
            $('#monthlyrecordTable').DataTable().destroy();
            table = $('#monthlyrecordTable').DataTable({
                "processing": true,
                ajax: {
                    url: '<?= base_url('Data_entry/getMNattincentiveData') ?>',
                    type: 'POST',
                    data: function(d) {
                        d.periodfromdate = $('#advpfromdt').val();
                        d.periodtodate = $('#advptodt').val();
                        d.att_payschm = $('#att_payschm').val();
                        d.holget = $('#hol_get').val();
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
            order: false,               // Sort by the first column (auto_id) in descending order
                pageLength: 10 // Set the default number of rows per page to 25
              });
        }



        $('#spgdailyrecordTable tbody').on('click', 'tr', function() {
                var rowData = table.row(this).data();
                $('#record_id').val(rowData[0]);
                var frdt=rowData[1];
                nfrdt = frdt.substring(6, 10) + '-' + frdt.substring(3, 5) + '-' + frdt.substring(0, 2); 
                var frdt=rowData[8];
                ifrdt = frdt.substring(6, 10) + '-' + frdt.substring(3, 5) + '-' + frdt.substring(0, 2); 
                $('#fromdt').val(nfrdt);
                $avtp=rowData[2];
                $('#adv_type').val($avtp);

                $('#ebno').val(rowData[3]);
                $('#ebname').val(rowData[4]);
                $('#loanadvamt').val(rowData[5]);
                $('#instamt').val(rowData[6]);
                $('#noofinst').val(rowData[7]);
                $('#ebid').val(rowData[9]);
                $('#inststartdate').val(ifrdt);
                $("#adv_type").trigger('change');
                $('#advsavedata').hide();
                $('#advupdatedata').show();
                datavaildation() ;   
            
            });
        
            $('#recordTable tbody').on('click', 'tr', function() {
                if ($(this).hasClass('selected')) {
                    $(this).removeClass('selected');
                } else {
                    table.$('tr.selected').removeClass('selected');
                    $(this).addClass('selected');
                }
            });
   
        $('#att_payschm').on('change', function() {
            var att_payschm =  $('#att_payschm').val();
            
            
            $.ajax({
                url: '<?= base_url('Data_entry/getpayscheme') ?>',
                type: "POST",
                data: {ebno: att_payschm},
                dataType: "json",
                success: function(response) {
                        $('#payschemename').val(response.empname);
                        var tw=$('#ebid').val();
                }
                });

        });
        $("#njmwagesclose").click(function(event){
//                alert('closeb');
                document.getElementById('myModal').style.display = 'none';
        });
        $("#ejm_wagesclose").click(function(event){
    //                alert('closeb');
            document.getElementById('ejmModal').style.display = 'none';
        });
        $("#stlclose").click(function(event){
//                alert('closeb');
                document.getElementById('stlModal').style.display = 'none';
        });
        $("#cntwagesclose").click(function(event){
//                alert('closeb');
                document.getElementById('wagessummaryModal').style.display = 'none';
        });

        $("#canteenclose").click(function(event){
//                alert('closeb');
                document.getElementById('canteenModal').style.display = 'none';
        });
        $("#wgbrkclose").click(function(event){
//                alert('closeb');
                document.getElementById('wagesbrkModal').style.display = 'none';
        });
        $("#payrollclose").click(function(event){
//                alert('closeb');
                document.getElementById('paypostModal').style.display = 'none';
        });

        $("#attsheetclose").click(function(event){
              //  alert('closeb');
                document.getElementById('attsheetModal').style.display = 'none';
        });
        $("#oattprdclose").click(function(event){
              //  alert('closeb');
                document.getElementById('oattprdModal').style.display = 'none';
        });

        $("#tareffclose").click(function(event){
              //  alert('closeb');
            document.getElementById('tareffModal').style.display = 'none';
        });


        $("#tnoclose").click(function(event){
           //    alert('closeb');
                document.getElementById('TnoupdmyModal').style.display = 'none';
        });
        $("#nwdclose").click(function(event){
           //    alert('closeb');
                document.getElementById('nwdupdmyModal').style.display = 'none';
        });

        $("#njmcntworkerbankstatement").click(function(event){
               event.preventDefault();
               hideAllTables();  
               alert('Bank Statement');   
                var att_payschm =  $('#att_payschm').val();
                var holget =  $('#hol_get').val();
                periodfromdate = $('#advpfromdt').val();
                periodtodate = $('#advptodt').val();
                payschemeName = $('#payschemename').val();
                periodfromdate= periodfromdate.substring(8)+'-'+periodfromdate.substring(5,7)+'-'+periodfromdate.substring(0,4);
                periodtodate= periodtodate.substring(8)+'-'+periodtodate.substring(5,7)+'-'+periodtodate.substring(0,4);
                cntwagescloseModal();
                var hd1 = '';
                hd1 = 'Contractor Bank Statement for  Period From ' + periodfromdate + ' To ' + periodtodate;
                document.getElementById('heading').textContent = hd1;
                //heading.textContent = hd1;
                document.getElementById("njmcntbankstatementTable").style.display = "table";
                initDataTablecntbank();

        }); 


        $("#njmcntworkerpayregister").click(function(event){
               event.preventDefault();
//               $('#tableLoader').show();
//                showSpinner();
                showSpinnerCounter();

const $btn = $(this).prop('disabled', true);

               hideAllTables();  
//               alert('pay regiserStatement');   
                var att_payschm =  $('#att_payschm').val();
                var holget =  $('#hol_get').val();
                periodfromdate = $('#njmcntfromdt').val();
                periodtodate = $('#njmcnttodt').val();
                payschemeName = $('#payschemename').val();
                periodfromdate= periodfromdate.substring(8)+'-'+periodfromdate.substring(5,7)+'-'+periodfromdate.substring(0,4);
                periodtodate= periodtodate.substring(8)+'-'+periodtodate.substring(5,7)+'-'+periodtodate.substring(0,4);
                cntwagescloseModal();
                var hd1 = '';
                hd1 = 'Contractor Pay Register for  Period From ' + periodfromdate + ' To ' + periodtodate;
                document.getElementById('heading').textContent = hd1;
                //heading.textContent = hd1;
//                document.getElementById("njmcntwgesTable").style.display = "table";
                initDataTablecntpayregister();
                

        }); 



$(document).on('click', '#njmcntwgesTable tbody tr', function (e) {
  e.preventDefault();

  let tr = $(this).closest('tr');
  if (tr.hasClass('child')) tr = tr.prev();
alert('Row clicked');
  const rowData = table.row(tr).data();
  console.log(rowData);


});

// Row click handler for DataTable
// assumes you already have a DataTable instance in `table`
//$('#njmcntwgesTablex tbody').on('click', 'tr', function (e) {
$(document).on('click', '#njmcntwgesTablea tbody tr', function (e) {

//    e.preventDefault();

  let tr = $(this).closest('tr');
  if (tr.hasClass('child')) tr = tr.prev();

  const row = table.row(tr).data();
  if (!row) return;

  const EB_NO  = row.EB_NO || row[1];
  const fromDt = $('#njmcntfromdt').val();
  const toDt   = $('#njmcnttodt').val();

  Swal.fire({
    title: 'Pay Register Details',
    html: 'Loading…',
    didOpen: () => Swal.showLoading(),
    allowOutsideClick: false,
    allowEscapeKey: false,
    showConfirmButton: false,
    width: '6in',
    heightAuto: false,
    customClass: { popup: 'swal-fixed-size' }
  });

  $.ajax({
    url: '<?= base_url('Njmwagesprocess/get_pay_register_details_api') ?>',
    type: 'POST',
    dataType: 'json',
    data: { eb_no: EB_NO, from_date: fromDt, to_date: toDt }
  })
  .done(function (res) {
    if (!res || res.error) {
      return Swal.fire({
        icon: 'error',
        title: 'Unable to load',
        text: res?.message || 'No data returned.',
        confirmButtonText: 'Close',
        width: '6in',
        heightAuto: false,
        customClass: { popup: 'swal-fixed-size' }
      });
    }

    const h = res.header || {};
    const timeWages   = res.timeWage || [];     // now expecting an array
    const productions = res.production || [];  // now expecting an array
    const mn = res.manual || {};

    // Build Time Wage table rows
    let timeWageHTML = '';
    timeWages.forEach(tw => {
      timeWageHTML += `
        <tr>
          <td>${tw.occupation || ''}</td>
          <td>${tw.days ?? ''}</td>
          <td>${tw.rate ?? ''}</td>
          <td>${tw.amount ?? ''}</td>
        </tr>
      `;
    });

    // Build Production table rows
    let productionHTML = '';
    productions.forEach(pd => {
      productionHTML += `
        <tr>
          <td>${pd.quality_code || ''}</td>
          <td>${pd.production ?? ''}</td>
          <td>${pd.rate ?? ''}</td>
          <td>${pd.amount ?? ''}</td>
        </tr>
      `;
    });

    // Final HTML for SweetAlert
    const html = `
      <div style="font-size:14px">
        <div><b>EB No:</b> ${h.EB_NO || ''} &nbsp;&nbsp;
             <b>From Date:</b> ${h.from || fromDt} &nbsp;&nbsp;
             <b>To Date:</b> ${h.to || toDt}</div>

        <h5 style="margin-top:12px;">Time Wage Details</h5>
        <table style="width:100%;border-collapse:collapse" border="1" cellpadding="5">
          <thead>
            <tr><th>Occupation</th><th>Days</th><th>Rate</th><th>Amount</th></tr>
          </thead>
          <tbody>${timeWageHTML}</tbody>
        </table>

        <h5 style="margin-top:12px;">Production Details</h5>
        <table style="width:100%;border-collapse:collapse" border="1" cellpadding="5">
          <thead>
            <tr><th>Quality Code</th><th>Production</th><th>Rate</th><th>Amount</th></tr>
          </thead>
          <tbody>${productionHTML}</tbody>
        </table>

        <h5 style="margin-top:12px;">Manual Entry Details</h5>
        <div class="row" style="display:flex;gap:60px;margin-left:10px;">
          <div><b>Advance:</b> ${mn.advance ?? ''}</div>
          <div><b>Plus Balance:</b> ${mn.plus_balance ?? ''}</div>
        </div>
      </div>
    `;

    Swal.fire({




        title: 'Pay Register Details',
      html,
      icon: 'info',
      confirmButtonText: 'Close',
      width: '6in',
      heightAuto: false,
      customClass: { popup: 'swal-fixed-size' }
    });
  })
  .fail(function (xhr) {
    Swal.fire({
      icon: 'error',
      title: 'Server error',
      text: xhr?.responseText || 'Please try again.',
      confirmButtonText: 'Close',
      width: '6in',
      heightAuto: false,
      customClass: { popup: 'swal-fixed-size' }
    });
  });
});

 
function initDataTablecntpayregister() {
  destroyAllTables();

  $.ajax({
    url: '<?= base_url('Njmwagesprocess/njmcontpayregisdisp') ?>',
    type: 'POST',
    dataType: 'json',
    data: {
      periodfromdate: $('#njmcntfromdt').val(),
      periodtodate:   $('#njmcnttodt').val(),
      contractorName: $('#contractorName').val(),
      reportType:     $('#reportType').val()
    }
  }).done(function(res) {
    const cols = (res.columns || []).map(c => ({
      data: c.data,
      title: c.title,
      defaultContent: ''     // <-- avoids “unknown parameter” if any field missing
    }));
    const data = res.data || [];

    // Rebuild thead to match dynamic columns
    const $thead = $('#njmcntwgesTable thead').empty().append('<tr></tr>');
    cols.forEach(c => $thead.find('tr').append(`<th>${c.title}</th>`));

                document.getElementById("njmcntwgesTable").style.display = "table";

//    alert('Data loaded successfully. Initializing DataTable...');
    // Init DataTable AFTER we have columns
    $('#njmcntwgesTable').DataTable({
      destroy: true,
      processing: true,
      data: data,
      columns: cols,
      scrollX: true,
      fixedColumns: { leftColumns: 4 },
      order: [[0, 'asc']],
      pageLength: 10
    });
               hideSpinnerCounter();


  }).fail(function(xhr){
    console.error('Load failed:', xhr.responseText);
    alert('Failed to load data.');
  });
}






                function initDataTablecntpayregister2() {
                destroyAllTables();
                table = $('#njmcntwgesTable').DataTable({
                 "processing": true,
 
                ajax: {
                    url: '<?= base_url('Njmwagesprocess/njmcontpayregisdisp') ?>',
                    type: 'POST',
                    data: function(d) {
                        d.periodfromdate = $('#njmcntfromdt').val();
                        d.periodtodate = $('#njmcnttodt').val();
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


        $("#hlincstatement").click(function(event){
               event.preventDefault();     
                var att_payschm =  $('#att_payschm').val();
                var holget =  $('#hol_get').val();
                periodfromdate = $('#advpfromdt').val();
                periodtodate = $('#advptodt').val();
                payschemeName = $('#payschemename').val();
                periodfromdate= periodfromdate.substring(8)+'-'+periodfromdate.substring(5,7)+'-'+periodfromdate.substring(0,4);
                periodtodate= periodtodate.substring(8)+'-'+periodtodate.substring(5,7)+'-'+periodtodate.substring(0,4);

                var hd1 = '';
                if (holget == 1) {
                    hd1 = 'Holiday List for ' + payschemeName + ' Period From ' + periodfromdate + ' To ' + periodtodate;
                }
                if (holget==1) {
                    var hd1 = 'Holiday List for '+payschemeName+' Period From '+periodfromdate+' To '+periodtodate ;
                }
                if (holget==2) {
                    var hd1 = 'Main Payroll FN Attendance Incentive for '+payschemeName+' Period From '+periodfromdate+' To '+periodtodate ;
                }
                if (holget==3 || holget==7 ) {
                    var hd1 = 'Others Forthnightly Attendance Incentive for '+payschemeName+' Period From '+periodfromdate+' To '+periodtodate ;
                }
                if (holget==4) {
                    var hd1 = 'Others Monthly Attendance Incentive for '+payschemeName+' Period From '+periodfromdate+' To '+periodtodate ;
                }     
                heading.textContent = hd1;
            if (holget==1) {
                document.getElementById("holidayrecordTable").style.display = "table";
                document.getElementById("forthnightrecordTable").style.display = "none";
                document.getElementById("monthlyrecordTable").style.display = "none";
               //   alert('2st');
            initDataTable1();
        

        }
        if (holget==2) {
             document.getElementById("holidayrecordTable").style.display = "none";
            document.getElementById("forthnightrecordTable").style.display = "table";
            document.getElementById("monthlyrecordTable").style.display = "none";
        //    alert('2st');
            initDataTable2();
        }
        if (holget==3 || holget==7 )  {
             document.getElementById("holidayrecordTable").style.display = "none";
            document.getElementById("forthnightrecordTable").style.display = "table";
            document.getElementById("monthlyrecordTable").style.display = "none";
        //    alert('2st');
            initDataTable2();
        }
        if (holget==4 || holget==8) {
             document.getElementById("holidayrecordTable").style.display = "none";
            document.getElementById("forthnightrecordTable").style.display = "none";
            document.getElementById("monthlyrecordTable").style.display = "table";
         //   alert('3st');

            initDataTable3();
        }

            });


            $("#hlincprocessdata").click(function(event){
               event.preventDefault();     
                var att_payschm =  $('#att_payschm').val();
                var holget =  $('#hol_get').val();
                periodfromdate = $('#advpfromdt').val();
                periodtodate = $('#advptodt').val();
                payschemeName = $('#payschemename').val();
                periodfromdate= periodfromdate.substring(8)+'-'+periodfromdate.substring(5,7)+'-'+periodfromdate.substring(0,4);
                periodtodate= periodtodate.substring(8)+'-'+periodtodate.substring(5,7)+'-'+periodtodate.substring(0,4);

                var hd1 = '';
                if (holget==1) {
                     initDataTable30(); 

                }
                if (holget==14) {
                     initDataTable30(); 
                }
                if (holget==15) {
                     initDataTable31(); 
                }
      
      
            });


            function initDataTable3l() {
            $('#holidayrecordTable').DataTable().destroy();
            $('#forthnightrecordTable').DataTable().destroy();
            $('#monthlyrecordTable').DataTable().destroy();
            table = $('#monthlyrecordTable').DataTable({
                "processing": true,
                ajax: {
                    url: '<?= base_url('Data_entry/getMNattincentiveData') ?>',
                    type: 'POST',
                    data: function(d) {
                        d.periodfromdate = $('#advpfromdt').val();
                        d.periodtodate = $('#advptodt').val();
                        d.att_payschm = $('#att_payschm').val();
                        d.holget = $('#hol_get').val();
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
            order: false,               // Sort by the first column (auto_id) in descending order
                pageLength: 10 // Set the default number of rows per page to 25
              });
        }

            function locafill() {

            event.preventDefault(); 
            var periodfromdate= $('#advpfromdt').val();
            var periodtodate= $('#advptodt').val();
            var att_payschm =  $('#att_payschm').val();
            var holget =  $('#hol_get').val();

        //    alert('periodfromdate');
        //    alert (holget);

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


        $("#tnodataupdate").click(function(event){
              event.preventDefault(); 
                var tnocode =  $('#tnocode').val();
                var tnorate= $('#tnorate').val();
                var pay_due_daily= $('#pay_due_daily').val();
                var sub_location= $('#sub_location').val();
 
                var pay_due_daily = $('#pay_due_daily').prop('checked');
                if (pay_due_daily) {
                    paydue='T';
                } else {
                   paydue=' ';
                }
                
            $.ajax({
            url: "<?php echo base_url('Data_entry_2/tnodataupdate'); ?>",
            type: "POST",
            data: {tnocode: tnocode,tnorate: tnorate,pay_due_daily: pay_due_daily,paydue :paydue,
                sub_location: sub_location 
            },
            dataType: "json",
            success: function(response) {
              var savedata=(response.savedata);
                if (response.success) {
                    alert('Record Updated Successfully');
                     
      
                }
            }
        });
      });

      $("#nwddataupdate").click(function(event){
              event.preventDefault();
                var nwddate =  $('#nwdfromdt').val();
                var Sourceoff= $('#Sourceoff').val();
                var pay_active = $('#pay_active').prop('checked');
                if (pay_active) {
                    payact=1;
                } else {
                   payact=0;
                }
            $.ajax({
            url: "<?php echo base_url('Data_entry_2/nwddataupdate'); ?>",
            type: "POST",
            data: {nwddate: nwddate,Sourceoff: Sourceoff,payact: payact 
            },
            dataType: "json",
            success: function(response) {
              var savedata=(response.savedata);
                if (response.success) {
                    alert('Record Updated Successfully');
                     
      
                }
            }
        });
      });


            function initDataTable30() {
          
            event.preventDefault(); 
            var periodfromdate= $('#advpfromdt').val();
            var periodtodate= $('#advptodt').val();
            var att_payschm =  $('#att_payschm').val();
            var holget =  $('#hol_get').val();
                showSpinnerCounter();

//            alert(periodfromdate);
        //    alert (holget);
          $.ajax({
            url: "<?php echo base_url('Data_entry/holidayprocessdata'); ?>",
            type: "POST",
            data: {periodfromdate : periodfromdate,periodtodate : periodtodate,att_payschm: att_payschm,
                holget: holget},
            dataType: "json",
            success: function(response) {
           
              var savedata=(response.savedata);
                if (response.success) {
                    alert('Record Updated Successfully');
                     $('#record_id').val(0);
                        hideSpinnerCounter();
                    } else {
                    alert('No Data');
                    hideSpinnerCounter();

                }
            }
        });


   //     refreshDataTable();
      };

      function initDataTable31() {
          
          event.preventDefault(); 
          var periodfromdate= $('#advpfromdt').val();
          var periodtodate= $('#advptodt').val();
          var att_payschm =  $('#att_payschm').val();
          var holget =  $('#hol_get').val();

          document.getElementById("hlincprocessdata").disabled = true;
             document.getElementById("hlincprocessdata").style.backgroundColor = "#6BFF33"; // Set to red color as an example
             document.getElementById("hlincprocessdata").innerText = "Processing"; // Set to "New Text" as an example

          alert(periodfromdate);
      //    alert (holget);
        $.ajax({
          url: "<?php echo base_url('Data_entry_2/stdhandsprocessdata'); ?>",
          type: "POST",
          data: {periodfromdate : periodfromdate,periodtodate : periodtodate,att_payschm: att_payschm,
              holget: holget},
          dataType: "json",
          success: function(response) {
         
    var savedata = (response.savedata);
    if (response.success) {
        alert(savedata);
        document.getElementById("hlincprocessdata").disabled = false;
        document.getElementById("hlincprocessdata").style.backgroundColor = "#33A5FF"; // Set to blue color as an example
        document.getElementById("hlincprocessdata").innerText = "Process"; // Set to "Process" as an example
        $('#record_id').val(0);
    } else {
        alert('No Data');
    }
          }
      });


 //     refreshDataTable();
    };



      


      $("#njmwagesdata").click(function(event){
        openModal();

      }); 

            $("#ejmwagesdata").click(function(event){
                ejmopenModal();

            }); 

      $("#njmcntwagesdisplay").click(function(event){
//        openModal();
            closeModal();
            locafill();
//            stlopenModal();
           cntwagesopenModal()

      }); 



      $("#njmstaffbanksheet").click(function(event){
      event.preventDefault(); 
	  alert ("acaaaa");
	  var opt=3;
             event.preventDefault();     
                var att_payschm =  $('#att_payschm').val();
                var holget =  $('#hol_get').val();
                periodfromdate = $('#njmcntfromdt').val();
                periodtodate = $('#njmcnttodt').val();
                payschemeName = $('#payschemename').val();
//                exportdbfdata
//02-03-2024 anotherFunction

var url = '<?php echo site_url("Njmwagesprocess/njmstaffbanksheet"); ?>' +
                      '?att_payschm=' + att_payschm +
                      '&holget=' + holget+
                      '&periodfromdate=' + periodfromdate+
                      '&payschemeName=' + payschemeName+
                      '&periodtodate=' + periodtodate
                       
                      ;
                      alert(url);
			//$(location).attr('href',url);
			window.open( url, '_blank');
			
			
return false;
});








      $("#njmcntworkerpaydownload").click(function(event){
      event.preventDefault(); 
//	  alert ("cntbank");
	  var opt=3;
             event.preventDefault();     
                var att_payschm =  $('#att_payschm').val();
                var holget =  $('#hol_get').val();
                periodfromdate = $('#njmcntfromdt').val();
                periodtodate = $('#njmcnttodt').val();
                payschemeName = $('#payschemename').val();
//                exportdbfdata
//02-03-2024 anotherFunction

var url = '<?php echo site_url("Njmwagesprocess/njmcntpayexceldownload"); ?>' +
                      '?att_payschm=' + att_payschm +
                      '&holget=' + holget+
                      '&periodfromdate=' + periodfromdate+
                      '&payschemeName=' + payschemeName+
                      '&periodtodate=' + periodtodate
                       
                      ;
                  //    alert(url);
			//$(location).attr('href',url);
			window.open( url, '_blank');
			
			
return false;
});


      $("#njmcntworkerbankdownload").click(function(event){
      event.preventDefault(); 
	  alert ("cntbank");
	  var opt=3;
             event.preventDefault();     
                var att_payschm =  $('#att_payschm').val();
                var holget =  $('#hol_get').val();
                periodfromdate = $('#njmcntfromdt').val();
                periodtodate = $('#njmcnttodt').val();
                payschemeName = $('#payschemename').val();
//                exportdbfdata
//02-03-2024 anotherFunction

//var url = '<?php echo site_url("Data_entry/anotherFunction"); ?>' +
var url = '<?php echo site_url("Njmwagesprocess/njmcntbankexceldownload"); ?>' +
//            var url = '<?php echo site_url("Data_entry/otpayslipprint"); ?>' +
                      '?att_payschm=' + att_payschm +
                      '&holget=' + holget+
                      '&periodfromdate=' + periodfromdate+
                      '&payschemeName=' + payschemeName+
                      '&periodtodate=' + periodtodate
                       
                      ;
                      alert(url);
			//$(location).attr('href',url);
			window.open( url, '_blank');
			
			
return false;
});


// Move this function outside of $(document).ready and properly close it
function cntwagespayslipprint() {
  event.preventDefault();   
  var att_payschm = $('#att_payschm').val();
  var holget = $('#hol_get').val();
  var periodfromdate = $('#njmcntfromdt').val();
  var periodtodate = $('#njmcnttodt').val();
  var payschemeName = $('#payschemename').val();
  var url = '<?php echo site_url("Njmwagesprocess/njmcntwagespayslip"); ?>' +
    '?att_payschm=' + att_payschm +
    '&holget=' + holget +
    '&periodfromdate=' + periodfromdate +
    '&payschemeName=' + payschemeName +
    '&periodtodate=' + periodtodate;
  alert(url);
  window.open(url, '_blank');
  return false;
}






      $("#oattprdprocess").click(function(event){
          event.preventDefault(); 
          var periodfromdate= $('#oattfromdt').val();
          alert(periodfromdate);
          showSpinnerCounter();
        $.ajax({
          url: "<?php echo base_url('Ejmprocessdata/oattprdprocess'); ?>",
          type: "POST",
          data: {periodfromdate : periodfromdate},
          dataType: "json",
          success: function(response) {
           

            var savedata=(response.savedata);
              if (response.success) {
                  alert('Record Updated Successfully');
                   $('#record_id').val(0);
                   hideSpinnerCounter();
 
            } else {
                  alert('No Data');
                   hideSpinnerCounter();

      
              }
          alert(url);
            }
      });
 //     refreshDataTable();
    });






      $("#njmwagesprocessdata").click(function(event){
          event.preventDefault(); 
          var periodfromdate= $('#njmcntfromdt').val();
          var periodtodate= $('#njmcnttodt').val();
          var att_payschm =  $('#att_payschm').val();
          if (att_payschm == 0) {
              alert('Please Select Pay Scheme');
              return;
          } 
          showSpinnerCounter();
        $.ajax({
          url: "<?php echo base_url('Njmwagesprocess/njmwagesprocessdata'); ?>",
          type: "POST",
          data: {periodfromdate : periodfromdate,periodtodate : periodtodate,att_payschm: att_payschm},
          dataType: "json",
          success: function(response) {
           

            var savedata=(response.savedata);
              if (response.success) {
                  alert('Record Updated Successfully');
                   $('#record_id').val(0);
                   hideSpinnerCounter();
 
            } else {
                  alert('No Data');
                 
      
              }
          }
      });
 //     refreshDataTable();
    });




    
 
 $("#njmcntfromdt").on("change", function () {
    var periodfromdate = $('#njmcntfromdt').val(); // format: YYYY-MM-DD
    if (!periodfromdate) return;

    // Split date
    var parts = periodfromdate.split("-");
    var year = parseInt(parts[0], 10);
    var month = parseInt(parts[1], 10);

    // Get last day of the month
    var lastDay = new Date(year, month, 0).getDate(); // month is 1-based here

    // Build periodtodate in YYYY-MM-DD format
    var periodtodate = year + '-' + String(month).padStart(2, '0') + '-' + lastDay;

    // Set value
    $('#njmcnttodt').val(periodtodate);
});

$("#ejmfromdt").on("change", function () {
    var periodfromdate = $('#ejmfromdt').val(); // format: YYYY-MM-DD
    if (!periodfromdate) return;

    // Split date
    var parts = periodfromdate.split("-");
    var year = parseInt(parts[0], 10);
    var month = parseInt(parts[1], 10);

    // Get last day of the month
    var lastDay = new Date(year, month, 0).getDate(); // month is 1-based here

    // Build periodtodate in YYYY-MM-DD format
    var periodtodate = year + '-' + String(month).padStart(2, '0') + '-' + lastDay;

    // Set value
    $('#ejmtodt').val(periodtodate);
});

      $("#njmwrkvardupdate").click(function(event){
          event.preventDefault(); 
          var periodfromdate= $('#njmcntfromdt').val();
          var periodtodate= $('#njmcnttodt').val();
          var att_payschm =  $('#att_payschm').val();
          if (att_payschm == 0) {
              alert('Please Select Pay Scheme');
              return;
          }  
        if (periodfromdate == '' || periodtodate == '') {
              alert('Please Select From and To Date');
              return;
          }  
 
 
          document.getElementById("njmwrkvardupdate").disabled = true;
             document.getElementById("njmwrkvardupdate").style.backgroundColor = "#6BFF33"; // Set to red color as an example
             document.getElementById("njmwrkvardupdate").innerText = "Processing"; // Set to "New Text" as an example
        $.ajax({
          url: "<?php echo base_url('Njmwagesprocess/njmwrkvardupdate'); ?>",
          type: "POST",
          data: {periodfromdate : periodfromdate,periodtodate : periodtodate,att_payschm: att_payschm},
          dataType: "json",
          success: function(response) {
           

            var savedata=(response.savedata);
              if (response.success) {
                  alert('Record Updated Successfully');
                   $('#record_id').val(0);
              document.getElementById("njmwrkvardupdate").disabled = false;
             document.getElementById("njmwrkvardupdate").style.backgroundColor = "#6BFF33"; // Set to red color as an example
             document.getElementById("njmwrkvardupdate").innerText = "Update"; // Set to "New Text" as an example
 
            } else {
                  alert('No Data');
                 
      
              }
          }
      });
 //     refreshDataTable();
    });

      $("#ejmwrkvardupdate").click(function(event){
          event.preventDefault();
          syncEjmToNjm();
          $("#njmwrkvardupdate").trigger('click');
      });



    //  $("#njmcntwagesprocessdata").click(function(event){



  
      $("#hlwagesincdata").click(function(event){
      event.preventDefault(); 
	//  alert ("aaaa");
	  var opt=3;
             event.preventDefault();     
                var att_payschm =  $('#att_payschm').val();
                var holget =  $('#hol_get').val();
                periodfromdate = $('#advpfromdt').val();
                periodtodate = $('#advptodt').val();
                payschemeName = $('#payschemename').val();
              
            var url = '<?php echo site_url("Data_entry/exportdbfdata"); ?>' +
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


$("#stldownload").click(function(event){
      event.preventDefault(); 
             event.preventDefault();     
             var att_payschm =  $('#att_payschm').val();
                var holget =  $('#hol_get').val();
                periodfromdate = $('#stlfromdt').val();
                periodtodate = $('#stltodt').val();
                payschemeName = $('#payschemename').val();
         
              var url = '<?php echo site_url("Data_entry_2/stldownloaddata"); ?>' +
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


$("#stldetdownload").click(function(event){
      event.preventDefault(); 
             event.preventDefault();     
             var att_payschm =  $('#att_payschm').val();
                var holget =  $('#hol_get').val();
                periodfromdate = $('#stldetfromdt').val();
                periodtodate = $('#stldettodt').val();
                payschemeName = $('#payschemename').val();
         
              var url = '<?php echo site_url("Data_entry_2/stldetdownloaddata"); ?>' +
                      '?att_payschm=' + att_payschm +
                      '&holget=' + holget+
                      '&periodfromdate=' + periodfromdate+
                      '&payschemeName=' + payschemeName+
                      '&periodtodate=' + periodtodate
                       
                      ;
                     alert(url);
			//$(location).attr('href',url);
			window.open( url, '_blank');
			
			
return false;
});


$("#canteendetdownload").click(function(event){
      event.preventDefault(); 
             event.preventDefault();     
             var att_payschm =  $('#att_payschm').val();
                var holget =  $('#hol_get').val();
                periodfromdate = $('#stlfromdt').val();
                periodtodate = $('#stltodt').val();
                payschemeName = $('#payschemename').val();
         
              var url = '<?php echo site_url("Data_entry_2/canteendetdownloaddata"); ?>' +
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


$("#attsheetdownload").click(function(event){
      event.preventDefault(); 
             event.preventDefault();     
             var att_payschm =  $('#att_payschm').val();
                var holget =  $('#hol_get').val();
                periodfromdate = $('#attfromdt').val();
                periodtodate = $('#atttodt').val();
                payschemeName = $('#payschemename').val();
                att_dept= $('#att_dept').val();
    //     alert (periodfromdate);
              var url = '<?php echo site_url("Data_entry_2/attsheetdownloaddata"); ?>' +
                      '?att_payschm=' + att_payschm +
                      '&att_dept=' + att_dept+
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

////
$("#wrkfaexlfileupload").change(function(event){
    event.preventDefault();
    alert('File Selected');
    var formData = new FormData();
    formData.append('periodfromdate', $('#njmcntfromdt').val());
    formData.append('periodtodate', $('#njmcnttodt').val());
    formData.append('fileupload', $('#wrkfaexlfileupload')[0].files[0]);


    formData.append('att_payschm', $('#att_payschm').val());
    var att_payschm =  $('#att_payschm').val();

              if (att_payschm == 0) {
              alert('Please Select Pay Scheme');
              $('#wrkfaexlfileupload').val('');

              return;
          }  
                 showSpinnerCounter();

    $.ajax({
        url: "<?php echo base_url('Njmwagesprocess/njmwrkfauploadjs'); ?>",
        type: "POST",
        data: formData,
        processData: false,  // Prevent jQuery from automatically transforming the data into a query string
        contentType: false,  // Ensure the request is sent as multipart/form-data
        dataType: "json",
        success: function(response) {
            if (response.success) {
                var savedata = (response.savedata);
                var ebmissing = (response.ebmissing);
//                alert(savedata);

                alert(ebmissing);
                alert('Record Updated Successfully');
                hideSpinnerCounter();
              $('#wrkfaexlfileupload').val('');
              $('#record_id').val(0);
            } else {
                alert('No Data');
            }
        }
    });


});

$("#ejm_wrkfaexlfileupload").change(function(event){
    event.preventDefault();
    alert('File Selected');
    var formData = new FormData();
    formData.append('periodfromdate', $('#ejmfromdt').val());
    formData.append('periodtodate', $('#ejmtodt').val());
    formData.append('fileupload', $('#ejm_wrkfaexlfileupload')[0].files[0]);


    formData.append('att_payschm', $('#att_payschm').val());
    var att_payschm =  $('#att_payschm').val();

              if (att_payschm == 0) {
              alert('Please Select Pay Scheme');
              $('#ejm_wrkfaexlfileupload').val('');

              return;
          }  
                 showSpinnerCounter();

    $.ajax({
        url: "<?php echo base_url('Njmwagesprocess/njmwrkfauploadjs'); ?>",
        type: "POST",
        data: formData,
        processData: false,  // Prevent jQuery from automatically transforming the data into a query string
        contentType: false,  // Ensure the request is sent as multipart/form-data
        dataType: "json",
        success: function(response) {
            if (response.success) {
                var savedata = (response.savedata);
                var ebmissing = (response.ebmissing);
//                alert(savedata);

                alert(ebmissing);
                alert('Record Updated Successfully');
                hideSpinnerCounter();
              $('#ejm_wrkfaexlfileupload').val('');
              $('#record_id').val(0);
            } else {
                alert('No Data');
            }
        }
    });


});

 

$("#cntexlfileuploads").change(function(event){
    event.preventDefault();
    alert('File Selected----');
    var formData = new FormData();
    formData.append('periodfromdate', $('#njmcntfromdt').val());
    formData.append('periodtodate', $('#njmcnttodt').val());
    formData.append('fileupload', $('#cntexlfileuploads')[0].files[0]);
    alert(formData.get('fileupload'));
    formData.append('att_payschm', $('#att_payschm').val());
    var att_payschm =  $('#att_payschm').val();

              if (att_payschm == 0) {
              alert('Please Select Pay Scheme');
              $('#cntexlfileuploads').val('');

              return;
          }  
                 showSpinnerCounter();
    $.ajax({
        url: "<?php echo base_url('Njmwagesprocess/cntexlupload'); ?>",
        type: "POST",
        data: formData,
        processData: false,  // Prevent jQuery from automatically transforming the data into a query string
        contentType: false,  // Ensure the request is sent as multipart/form-data
        dataType: "json",
        success: function(response) {
            alert(response);
            if (response.success) {
                var savedata = (response.savedata);
                var ebmissing = (response.ebmissing);
//                alert(savedata);
                alert(ebmissing);
                
                alert('Record Updated Successfully');
                $('#cntexlfileuploads').val('');
                hideSpinnerCounter();
                $('#record_id').val(0);
            } else {
                alert('No Data');
            }
        }
    });
});

$("#ejm_cntexlfileuploads").change(function(event){
    event.preventDefault();
    alert('File Selected----');
    var formData = new FormData();
    formData.append('periodfromdate', $('#ejmfromdt').val());
    formData.append('periodtodate', $('#ejmtodt').val());
    formData.append('fileupload', $('#ejm_cntexlfileuploads')[0].files[0]);
    alert(formData.get('fileupload'));
    formData.append('att_payschm', $('#att_payschm').val());
    var att_payschm =  $('#att_payschm').val();

              if (att_payschm == 0) {
              alert('Please Select Pay Scheme');
              $('#ejm_cntexlfileuploads').val('');

              return;
          }  
                 showSpinnerCounter();
    $.ajax({
        url: "<?php echo base_url('Njmwagesprocess/cntexlupload'); ?>",
        type: "POST",
        data: formData,
        processData: false,  // Prevent jQuery from automatically transforming the data into a query string
        contentType: false,  // Ensure the request is sent as multipart/form-data
        dataType: "json",
        success: function(response) {
            alert(response);
            if (response.success) {
                var savedata = (response.savedata);
                var ebmissing = (response.ebmissing);
//                alert(savedata);
                alert(ebmissing);
                
                alert('Record Updated Successfully');
                $('#ejm_cntexlfileuploads').val('');
                hideSpinnerCounter();
                $('#record_id').val(0);
            } else {
                alert('No Data');
            }
        }
    });
});

////
$("#payrollexlupload").click(function(event){
    event.preventDefault();
//    alert('File Selected');
    var formData = new FormData();
    formData.append('periodfromdate', $('#advpfromdt').val());
    formData.append('periodtodate', $('#advptodt').val());
    formData.append('fileupload', $('#payrollexlfileupload')[0].files[0]);
//    alert(formData.get('fileupload'));
//    formData.append('expfilename', expfilename);    
    formData.append('att_payschm', $('#att_payschm').val());
    var att_payschm =  $('#att_payschm').val();

              if (att_payschm == 0) {
              alert('Please Select Pay Scheme');
              $('#cntexlfileuploads').val('');

              return;
          }  
    
          if (!formData.get('fileupload')) {
              alert('Please Select File to Upload');
              return;
          }
    

    if (att_payschm==151) { var expfilename='VOWPAYMN.CSV'; }
    if (att_payschm==125) { var expfilename='VOWPAYVW.CSV'; }
    if (att_payschm==161) { var expfilename='VOWPAYRW.CSV'; }
//    var selfilename=upper(formData.get('fileupload').name);    
    var selfilename = formData.get('fileupload').name.toUpperCase();

    var fileObj = $('#payrollexlfileupload')[0].files[0];
    var lastModifiedMs   = fileObj.lastModified;            // number
    var lastModifiedDate = new Date(lastModifiedMs);        

      alert('Selected File Name: ' + selfilename + '\n' +
          'Expected File Name: ' + expfilename + '\n' +
          'Last Modified Date: ' + lastModifiedDate.toLocaleString());
    if (selfilename !== expfilename) {
              alert('Please Select Correct File: ' + expfilename+'. You Selected: ' + selfilename  );
              return;
          }

       const cutoff = new Date();
cutoff.setDate(cutoff.getDate() - 2);

if (lastModifiedDate < cutoff) {
  alert(
    "Please select an updated file.\n" +
    "File Last Modified: " + lastModifiedDate.toLocaleString() + "\n" +
    "Allowed: within last 2 days (after " + cutoff.toLocaleString() + ")"
  );
  $('#payrollexlfileupload').val(''); // optional: clear file input
  return;
}

          showSpinnerCounter();
//alert(formData);
//process_csv
//cntexlupload
    $.ajax({
        url: "<?php echo base_url('Ejmprocessdata/cntexlupload_py'); ?>",
        type: "POST",
        data: formData,
        processData: false,  // Prevent jQuery from automatically transforming the data into a query string
        contentType: false,  // Ensure the request is sent as multipart/form-data
        dataType: "json",
        success: function(response) {
//            alert(response.success);
//            alert(response.ebmissing);
//            alert(response.savedata);
            

            if (response.success) {
                var savedata = (response.savedata);
                var ebmissing = ('no of emp='+response.totalEmployees);  
//                alert(savedata);
                alert(ebmissing);
//                alert('total='+response.totalEmployees)
                
                alert('Record Updated Successfully');
                $('#cntexlfileuploads').val('');
                hideSpinnerCounter();
                $('#record_id').val(0);
            } else {
                alert('failed '+response.savedata);
                alert('reason '+response.payschms);
                hideSpinnerCounter();
            }
        }
    });
});


////

$("#wrklinehoursupload").change(function(event){
    event.preventDefault();
    alert('File Selected');
    var formData = new FormData();
    formData.append('periodfromdate', $('#njmcntfromdt').val());
    formData.append('periodtodate', $('#njmcnttodt').val());
    formData.append('fileupload', $('#wrklinehoursupload')[0].files[0]);
    alert(formData.get('fileupload'));
    formData.append('att_payschm', $('#att_payschm').val());
    var att_payschm =  $('#att_payschm').val();

    showSpinnerCounter();
    $.ajax({
        url: "<?php echo base_url('Njmwagesprocess/wrklinehoursupload'); ?>",
        type: "POST",
        data: formData,
        processData: false,  // Prevent jQuery from automatically transforming the data into a query string
        contentType: false,  // Ensure the request is sent as multipart/form-data
        dataType: "json",
        success: function(response) {
//            alert(response);
            if (response.success) {
                var savedata = (response.savedata);
                var ebmissing = (response.ebmissing);
                alert('No of rows updated: ' + savedata);
                alert('Missing rows: ' + ebmissing);
                alert('Record Updated Successfully');
                $('#wrklinehoursupload').val('');
                hideSpinnerCounter();
                $('#record_id').val(0);
            } else {
                alert('No Data/date mismatch');
                $('#wrklinehoursupload').val('');
                hideSpinnerCounter();
            }
        }
    });
});

$("#ejm_wrklinehoursupload").change(function(event){
    event.preventDefault();
    alert('File Selected');
    var formData = new FormData();
    formData.append('periodfromdate', $('#ejmfromdt').val());
    formData.append('periodtodate', $('#ejmtodt').val());
    formData.append('fileupload', $('#ejm_wrklinehoursupload')[0].files[0]);
    alert(formData.get('fileupload'));
    formData.append('att_payschm', $('#att_payschm').val());
    var att_payschm =  $('#att_payschm').val();

    showSpinnerCounter();
    $.ajax({
        url: "<?php echo base_url('Njmwagesprocess/wrklinehoursupload'); ?>",
        type: "POST",
        data: formData,
        processData: false,  // Prevent jQuery from automatically transforming the data into a query string
        contentType: false,  // Ensure the request is sent as multipart/form-data
        dataType: "json",
        success: function(response) {
//            alert(response);
            if (response.success) {
                var savedata = (response.savedata);
                var ebmissing = (response.ebmissing);
                alert('No of rows updated: ' + savedata);
                alert('Missing rows: ' + ebmissing);
                alert('Record Updated Successfully');
                $('#ejm_wrklinehoursupload').val('');
                hideSpinnerCounter();
                $('#record_id').val(0);
            } else {
                alert('No Data/date mismatch');
                $('#ejm_wrklinehoursupload').val('');
                hideSpinnerCounter();
            }
        }
    });
});




 


$("#njmwrkfaupload").click(function(event) {
    event.preventDefault();

    var formData = new FormData();
    formData.append('periodfromdate', $('#njmcntfromdt').val());
    formData.append('periodtodate', $('#njmcnttodt').val());
//    formData.append('wrkfaexlfileupload', $('#wrkfaexlfileupload')[0].files[0]);
    formData.append('fileupload', $('#wrkfaexlfileupload')[0].files[0]);

    //    formData.append('fileupload', $('#cntexlfileupload')[0].files[0]);

    formData.append('att_payschm', $('#att_payschm').val());
    var att_payschm =  $('#att_payschm').val();

              if (att_payschm == 0) {
              alert('Please Select Pay Scheme');
              return;
          }  
 
    //       alert(att_payschm);
  //  alert(formData.get('wrkfaexlfileupload'));
  //  alert(formData.get('att_payschm'));
  //  alert(formData);
    $.ajax({
        url: "<?php echo base_url('Njmwagesprocess/njmwrkfauploadjs'); ?>",
        type: "POST",
        data: formData,
        processData: false,  // Prevent jQuery from automatically transforming the data into a query string
        contentType: false,  // Ensure the request is sent as multipart/form-data
        dataType: "json",
        success: function(response) {
            if (response.success) {
                var savedata = (response.savedata);
                var ebmissing = (response.ebmissing);
//                alert(savedata);
                alert(ebmissing);
                alert('Record Updated Successfully');
                $('#record_id').val(0);
            } else {
                alert('No Data');
            }
        }
    });
});


$("#wrkothexlfileupload").change(function(event){
    event.preventDefault();

    var formData = new FormData();
    formData.append('periodfromdate', $('#njmcntfromdt').val());
    formData.append('periodtodate', $('#njmcnttodt').val());
//    formData.append('wrkfaexlfileupload', $('#wrkfaexlfileupload')[0].files[0]);
    formData.append('fileupload', $('#wrkothexlfileupload')[0].files[0]);

    //    formData.append('fileupload', $('#cntexlfileupload')[0].files[0]);

    formData.append('att_payschm', $('#att_payschm').val());
    var att_payschm =  $('#att_payschm').val();
          if (att_payschm == 0) {
              alert('Please Select Pay Scheme');
              return;
          }  
     //       alert(att_payschm);
  //  alert(formData.get('wrkfaexlfileupload'));
  //  alert(formData.get('att_payschm'));
  //  alert(formData);
   showSpinnerCounter();
    
  $.ajax({
        url: "<?php echo base_url('Njmwagesprocess/njmwrkothadjsuploadjs'); ?>",
        type: "POST",
        data: formData,
        processData: false,  // Prevent jQuery from automatically transforming the data into a query string
        contentType: false,  // Ensure the request is sent as multipart/form-data
        dataType: "json",
        success: function(response) {
            if (response.success) {
                var savedata = (response.savedata);
                var ebmissing = (response.ebmissing);
//                alert(savedata);
                alert(ebmissing);
                alert('Record Updated Successfully');
                $('#wrkothexlfileupload').val('');
                hideSpinnerCounter();
                $('#record_id').val(0);
            } else {
                alert('No Data');
            }
        }
    });
});

$("#ejm_wrkothexlfileupload").change(function(event){
    event.preventDefault();

    var formData = new FormData();
    formData.append('periodfromdate', $('#ejmfromdt').val());
    formData.append('periodtodate', $('#ejmtodt').val());
    formData.append('fileupload', $('#ejm_wrkothexlfileupload')[0].files[0]);

    formData.append('att_payschm', $('#att_payschm').val());
    var att_payschm =  $('#att_payschm').val();
          if (att_payschm == 0) {
              alert('Please Select Pay Scheme');
              return;
          }  
   showSpinnerCounter();
    
  $.ajax({
        url: "<?php echo base_url('Njmwagesprocess/njmwrkothadjsuploadjs'); ?>",
        type: "POST",
        data: formData,
        processData: false,  // Prevent jQuery from automatically transforming the data into a query string
        contentType: false,  // Ensure the request is sent as multipart/form-data
        dataType: "json",
        success: function(response) {
            if (response.success) {
                var savedata = (response.savedata);
                var ebmissing = (response.ebmissing);
//                alert(savedata);
                alert(ebmissing);
                alert('Record Updated Successfully');
                $('#ejm_wrkothexlfileupload').val('');
                hideSpinnerCounter();
                $('#record_id').val(0);
            } else {
                alert('No Data');
            }
        }
    });
});






$("#stluploadx").click(function(event){
          event.preventDefault(); 
          var periodfromdate= $('#stlfromdt').val();
          var periodtodate= $('#stltodt').val();
          var fileupload =  $('#fileupload')[0].files[0];
        $.ajax({
          url: "<?php echo base_url('Data_entry_2/stlupload'); ?>",
          type: "POST",
          data: {periodfromdate : periodfromdate,periodtodate : periodtodate,fileupload: fileupload},
          dataType: "json",
          success: function(response) {
            var savedata=(response.savedata);
              if (response.success) {
                  alert('Record Updated Successfully');
                   $('#record_id').val(0);
 
            } else {
                  alert('No Data');
                 
      
              }
          }
      });
 //     refreshDataTable();
    });




$("#hlincprint").click(function(event){
      event.preventDefault(); 
//	  alert ("aaaa");
	  var opt=3;
             event.preventDefault();     
                var att_payschm =  $('#att_payschm').val();
                var holget =  $('#hol_get').val();
                periodfromdate = $('#advpfromdt').val();
                periodtodate = $('#advptodt').val();
                payschemeName = $('#payschemename').val();
   alert(holget);           
            var url = '<?php echo site_url("Data_entry/hlincprint"); ?>' +
                      '?att_payschm=' + att_payschm +
                      '&holget=' + holget+
                      '&periodfromdate=' + periodfromdate+
                      '&payschemeName=' + payschemeName+
                      '&periodtodate=' + periodtodate
                       
                      ;
                      alert(url);
			//$(location).attr('href',url);
			window.open( url, '_blank');
			
			
return false;
});


      $("#oattprddownload").click(function(event){
      event.preventDefault(); 
	  var opt=3;
             event.preventDefault();     
                periodfromdate = $('#oattfromdt').val();
//                exportdbfdata
//02-03-2024 anotherFunction

var url = '<?php echo site_url("Ejmprocessdata/oattprddownload"); ?>' +
                      '?periodfromdate=' + periodfromdate
                      ;
                      alert(url);
			//$(location).attr('href',url);
			window.open( url, '_blank');
			
			
return false;
});


      
 //   $('#tnocode').on('input', function() {
        $('#tnocode').on('blur', function() {
          var tnocode =  $('#tnocode').val();
      
        $.ajax({
            url: "<?php echo base_url('Data_entry_2/tnofetch_data'); ?>",
            type: "POST",
            data: {tnocode: tnocode },
            dataType: "json",
            success: function(response) {
              if (response.success) {
                    $('#tnoname').val(response.tnoname);
                    $("#sub_location").val(response.subloca).trigger("change");
                    if (response.paydue=='T') {
                        pay_due_daily.checked = true;
                    } else {
                        pay_due_daily.checked = false;
                    }
                    $('#tnorate').val(response.tnorate);
                     
                    
                } else {
  
                }
            }
        });
//        refreshDataTable();

      });
        
      $('#nwdfromdt').on('blur', function() {
          var nwdfromdt =  $('#nwdfromdt').val();
      
        $.ajax({
            url: "<?php echo base_url('Data_entry_2/nwdfetch_data'); ?>",
            type: "POST",
            data: {nwdfromdt: nwdfromdt },
            dataType: "json",
            success: function(response) {
              if (response.success) {
                    $('#tnoname').val(response.tnoname);
                    $("#Sourceoff").val(response.offday).trigger("change");
                    if (response.payact==1) {
                        pay_active.checked = true;
                    } else {
                        pay_active.checked = false;
                    }
                  
                     
                    
                } else {
  
                }
            }
        });
//        refreshDataTable();

      });
        

        
                $('.select2-multi').select2({
                multiple: true,
                placeholder: 'Select options',
                width: '100%',
            });


    $("#njmmenuclick").click(function(event){
               event.preventDefault();     
                var att_payschm =  $('#att_payschm').val();
                var getmenu =  $('#getmenu').val();
                periodfromdate = $('#advpfromdt').val();
                periodtodate = $('#advptodt').val();
                payschemeName = $('#payschemename').val();
                periodfromdate= periodfromdate.substring(8)+'-'+periodfromdate.substring(5,7)+'-'+periodfromdate.substring(0,4);
                periodtodate= periodtodate.substring(8)+'-'+periodtodate.substring(5,7)+'-'+periodtodate.substring(0,4);
  //              alert('new js');
//                alert(getmenu);
                var hd1 = '';
                 if (getmenu == 1) {
                     njmcntwagesprocessdata(event);
                    }
                 if (getmenu == 2) {
                     //njmcntwagesprocessdata(event);
                    alert("Under Development");
                    }
                 if (getmenu == 3) {
             //   alert('excel cnt');
                     njmcntwagesexceldata(event);
                    }
                 if (getmenu == 4) {
                     njmcntpayexceldownload(event);
                }
                 if (getmenu == 9) {
                     njmwagespayslip(event);
                }
                 if (getmenu == 10) {
                     njmstaffbanksheet(event);
                }
                 if (getmenu == 5) {
                     njmwagesprocessdata(event);
                }
                 if (getmenu == 6) {
                     //njmcntwagesprocessdata(event);
                    alert("Under Development");
                    }
                 if (getmenu == 7) {
                     njmwagesexceldata(event);
                }
                if (getmenu == 8) {
                     njmcntpayexceldownload(event);
               //     alert("Under Development");
                }
                if (getmenu == 11) {
            locafill();
            wagesbrkModal();
//                     njmwgsbrksummexceldownload(event);
//                    alert("Under Development");
                }
                if (getmenu == 12) {
                     //njmcntwagesprocessdata(event);
                    alert("Under Development");
                }
                if (getmenu == 13) {
                     //njmcntwagesprocessdata(event);
                    alert("Under Development");
                }
  

                });

    $("#ejm_menuclick").click(function(event){
               event.preventDefault();
               syncEjmToNjm();
               if ($('#ejm_getmenu').val() == 1) {
                   tareffopenModal();
                   return;
               }
               if ($('#ejm_getmenu').val() == 2) {
                   prodWagesOpenModal();
                   return;
               }
               if ($('#ejm_getmenu').val() == 3) {
                   attPrepOpenModal();
                   return;
               }
               if ($('#ejm_getmenu').val() == 4) {
                   advOthOpenModal();
                   return;
               }
               $("#njmmenuclick").trigger('click');
    });


function njmcntwagesprocessdata() {
  event.preventDefault(); 
  var periodfromdate= $('#njmcntfromdt').val();
  var periodtodate= $('#njmcnttodt').val();
  var att_payschm =  $('#att_payschm').val();
//alert(att_payschm);
  // Calculate difference in days
  var fromDate = new Date(periodfromdate);
  var toDate = new Date(periodtodate);
  var timeDiff = toDate.getTime() - fromDate.getTime();
  var diffDays = Math.ceil(timeDiff / (1000 * 3600 * 24)) + 1; // number of full days

  // Check desired difference
  var desiredDiff = 28;  // Change this as per your requirement
  if (diffDays < desiredDiff) {
      alert("The difference between From and To dates must be exactly " + diffDays + '---' + desiredDiff + " days.");
      return; // Exit and do not proceed further
  }
   showSpinnerCounter();

  $.ajax({
      url: "<?php echo base_url('Njmwagesprocess/njmcntwagesprocessdata'); ?>",
      type: "POST",
      data: {periodfromdate : periodfromdate, periodtodate : periodtodate, att_payschm: att_payschm},
      dataType: "json",
      success: function(response) {
          var savedata = (response.savedata);
          if (response.success) {
                hideSpinnerCounter();

            alert('Record Updated Successfully');
              $('#record_id').val(0);


          } else {
              alert('No Data');
          }
      }
  });
}


//      $("#njmcntwagesexceldata").click(function(event){
function njmcntwagesexceldata() {
      event.preventDefault(); 
	  alert ("cntacaaaa");
	  var opt=3;
             event.preventDefault();     
                var att_payschm =  $('#att_payschm').val();
                var holget =  $('#hol_get').val();
                periodfromdate = $('#njmcntfromdt').val();
                periodtodate = $('#njmcnttodt').val();
                payschemeName = $('#payschemename').val();
alert(payschemeName);

//                exportdbfdata
//02-03-2024 anotherFunction

var url = '<?php echo site_url("Njmwagesprocess/njmcntwagesexceldownload"); ?>' +
                      '?att_payschm=' + att_payschm +
                      '&holget=' + holget+
                      '&periodfromdate=' + periodfromdate+
                      '&payschemeName=' + payschemeName+
                      '&periodtodate=' + periodtodate
                       
                      ;
                      alert(url);
			//$(location).attr('href',url);
			window.open( url, '_blank');
			
			
return false;

}


 function njmcntpayexceldownload() {
      event.preventDefault(); 

      var opt=3;
             event.preventDefault();     
                var att_payschm =  $('#att_payschm').val();
                var holget =  $('#hol_get').val();
                periodfromdate = $('#njmcntfromdt').val();
                periodtodate = $('#njmcnttodt').val();
                payschemeName = $('#payschemename').val();
alert(payschemeName);
        if (att_payschm == 0) {
              alert('Please Select Pay Scheme');
              return;
          }

//                exportdbfdata
var url = '<?php echo site_url("Njmwagesprocess/njmcntpayexceldownload"); ?>' +
                      '?att_payschm=' + att_payschm +
                      '&holget=' + holget+
                      '&periodfromdate=' + periodfromdate+
                      '&payschemeName=' + payschemeName+
                      '&periodtodate=' + periodtodate
                       
                      ;
			window.open( url, '_blank');
			
			
return false;
         };

         
 function njmwgsbrksummexceldownload() {
      event.preventDefault(); 

      var opt=3;
             event.preventDefault();     
                var att_payschm =  $('#att_payschm').val();
                var holget =  $('#hol_get').val();
                periodfromdate = $('#njmcntfromdt').val();
                periodtodate = $('#njmcnttodt').val();
                payschemeName = $('#payschemename').val();
            alert(payschemeName);
        if (att_payschm == 0) {
              alert('Please Select Pay Scheme');
              return;
          }

//                exportdbfdata
var url = '<?php echo site_url("Njmwagesprocess/njmwgsbrksummexceldownload"); ?>' +
                      '?att_payschm=' + att_payschm +
                      '&holget=' + holget+
                      '&periodfromdate=' + periodfromdate+
                      '&payschemeName=' + payschemeName+
                      '&periodtodate=' + periodtodate
                       
                      ;
			window.open( url, '_blank');
			
			
return false;
         };
 



      function njmwagespayslip(){
      event.preventDefault(); 
//	  alert ("acaaaa");
	  var opt=3;
             event.preventDefault();     
                var att_payschm =  $('#att_payschm').val();
                var holget =  $('#hol_get').val();
                periodfromdate = $('#njmcntfromdt').val();
                periodtodate = $('#njmcnttodt').val();
                payschemeName = $('#payschemename').val();
                att_dept= $('#att_dept').val();

//                exportdbfdata
//02-03-2024 anotherFunction
            if (att_payschm == 0) {
              alert('Please Select Pay Scheme');
              return;
            }

var url = '<?php echo site_url("Njmwagesprocess/njmcntwagespayslip"); ?>' +
                      '?att_payschm=' + att_payschm +
                      '&holget=' + holget+
                      '&periodfromdate=' + periodfromdate+
                      '&payschemeName=' + payschemeName+
                      '&periodtodate=' + periodtodate+
                      '&att_dept=' + att_dept
                       
                      ;
//                      alert(url);
			//$(location).attr('href',url);
			window.open( url, '_blank');
			
			
return false;
      }

function njmstaffbanksheet() {
      event.preventDefault(); 
	  alert ("acaaaa");
	  var opt=3;
             event.preventDefault();     
                var att_payschm =  $('#payschm').val();
                var holget =  $('#hol_get').val();
                periodfromdate = $('#njmcntfromdt').val();
                periodtodate = $('#njmcnttodt').val();
                payschemeName = $('#payschemename').val();

                alert(att_payschm);
                 //                exportdbfdata
//02-03-2024 anotherFunction

var url = '<?php echo site_url("Njmwagesprocess/njmstaffbanksheet"); ?>' +
                      '?att_payschm=' + att_payschm +
                      '&holget=' + holget+
                      '&periodfromdate=' + periodfromdate+
                      '&payschemeName=' + payschemeName+
                      '&periodtodate=' + periodtodate
                       
                      ;
                      alert(url);
			//$(location).attr('href',url);
			window.open( url, '_blank');
			
			
return false;
};


      function njmwagesprocessdata(event){
          event.preventDefault(); 
          var periodfromdate= $('#njmcntfromdt').val();
          var periodtodate= $('#njmcnttodt').val();
          var att_payschm =  $('#att_payschm').val();
          if (att_payschm == 0) {
              alert('Please Select Pay Scheme');
              return;
          } 
          showSpinnerCounter();
        $.ajax({
          url: "<?php echo base_url('Njmwagesprocess/njmwagesprocessdata'); ?>",
          type: "POST",
          data: {periodfromdate : periodfromdate,periodtodate : periodtodate,att_payschm: att_payschm},
          dataType: "json",
          success: function(response) {
           

            var savedata=(response.savedata);
              if (response.success) {
                  alert('Record Updated Successfully');
                   $('#record_id').val(0);
                   hideSpinnerCounter();
 
            } else {
                  alert('No Data');
                 
      
              }
          }
      });
 //     refreshDataTable();
    };



function njmwagesexceldata(event){
      event.preventDefault(); 
	  alert ("acaaaa");
	  var opt=3;
             event.preventDefault();     
                var att_payschm =  $('#att_payschm').val();
                var holget =  $('#hol_get').val();
                periodfromdate = $('#njmcntfromdt').val();
                periodtodate = $('#njmcnttodt').val();
                payschemeName = $('#payschemename').val();
//                exportdbfdata
//02-03-2024 anotherFunction

//var url = '<?php echo site_url("Data_entry/anotherFunction"); ?>' +
var url = '<?php echo site_url("Data_entry/njmwagesexceldownload"); ?>' +
//            var url = '<?php echo site_url("Data_entry/otpayslipprint"); ?>' +
                      '?att_payschm=' + att_payschm +
                      '&holget=' + holget+
                      '&periodfromdate=' + periodfromdate+
                      '&payschemeName=' + payschemeName+
                      '&periodtodate=' + periodtodate
                       
                      ;
                      alert(url);
			//$(location).attr('href',url);
			window.open( url, '_blank');
			
			
return false;
};

  
    $('#getchecklist').change(function() {
        getchecklist = $('#getchecklist').val();
        if (getchecklist == 1) {
            njmattwithpayschmexceldata(event);

        }
        if (getchecklist == 3) {
            njmlinehrschecklist(event);

        }
        if (getchecklist == 4) {
            njmproductionchecklist(event);

        }



      
    });

function njmattwithpayschmexceldata(event){
      event.preventDefault(); 
	  var opt=3;
             event.preventDefault();     
                var att_payschm =  $('#att_payschm').val();
                var holget =  $('#hol_get').val();
                periodfromdate = $('#njmcntfromdt').val();
                periodtodate = $('#njmcnttodt').val();
                payschemeName = $('#payschemename').val();

var url = '<?php echo site_url("Njmwagesprocess/njmattwithpayschmexceldata"); ?>' +
                      '?att_payschm=' + att_payschm +
                      '&holget=' + holget+
                      '&periodfromdate=' + periodfromdate+
                      '&payschemeName=' + payschemeName+
                      '&periodtodate=' + periodtodate
                       
                      ;
                      alert(url);
			//$(location).attr('href',url);
			window.open( url, '_blank');
			
			
return false;
};

function njmlinehrschecklist(event){
      event.preventDefault(); 
	  var opt=3;
             event.preventDefault();     
                var att_payschm =  $('#att_payschm').val();
                var holget =  $('#hol_get').val();
                periodfromdate = $('#njmcntfromdt').val();
                periodtodate = $('#njmcnttodt').val();
                payschemeName = $('#payschemename').val();

var url = '<?php echo site_url("Njmwagesprocess/njmlinehrschecklist"); ?>' +
                      '?att_payschm=' + att_payschm +
                      '&holget=' + holget+
                      '&periodfromdate=' + periodfromdate+
                      '&payschemeName=' + payschemeName+
                      '&periodtodate=' + periodtodate
                       
                      ;
                      alert(url);
			//$(location).attr('href',url);
			window.open( url, '_blank');
			
			
return false;
};

function njmproductionchecklist(event){
      event.preventDefault(); 
	  var opt=3;
             event.preventDefault();     
                var att_payschm =  $('#att_payschm').val();
                var holget =  $('#hol_get').val();
                periodfromdate = $('#njmcntfromdt').val();
                periodtodate = $('#njmcnttodt').val();
                payschemeName = $('#payschemename').val();

var url = '<?php echo site_url("Njmwagesprocess/njmproductionchecklist"); ?>' +
                      '?att_payschm=' + att_payschm +
                      '&holget=' + holget+
                      '&periodfromdate=' + periodfromdate+
                      '&payschemeName=' + payschemeName+
                      '&periodtodate=' + periodtodate
                       
                      ;
                      alert(url);
			//$(location).attr('href',url);
			window.open( url, '_blank');
			
			
return false;
};






      $("#updtrates").click(function(event){
          event.preventDefault(); 
          var periodfromdate= $('#oattfromdt').val();
          var ebnos=$('#ebnos').val();
          var updtrate=$('#updtrate').val();
          var shiftoff  =$('#shiftoff').val();
          if (updtrate == 0) {
              alert('Please Enter Rate');
              return;
         }
              showSpinnerCounter();
        $.ajax({
          url: "<?php echo base_url('Njmwagesprocess/updtrates'); ?>",
          type: "POST",
          data: {periodfromdate : periodfromdate,ebnos : ebnos,updtrate: updtrate,shiftoff: shiftoff},
          dataType: "json",
          success: function(response) {
           

            var savedata=(response.savedata);
              if (response.success) {
                  alert(savedata);
                   $('#record_id').val(0);
                   hideSpinnerCounter();
 
            } else {
                   hideSpinnerCounter();
                   alert('No Data');
                 
      
              }
          }
      });
 //     refreshDataTable();
    });


//function wgbrksubmit(event){
$("#wgbrksubmit").click(function (event) {
    event.preventDefault();

    var formData = new FormData();
    formData.append('att_payschm',   $('#att_payschm').val());
    formData.append('holget',        $('#hol_get').val());
    formData.append('periodfromdate',$('#wgbrkfromdt').val());
    formData.append('periodtodate',  $('#njmcnttodt').val());
    formData.append('payschemeName', $('#payschemename').val());

    // 👇 REAL file goes here
    var fileObj = $('#fileuploadwgbrk')[0].files[0];
    formData.append('fileupload', fileObj);   // name MUST match do_upload('fileupload')
   // alert('aaaa');
//    alert('wait for excel download');
              showSpinnerCounter();
    $.ajax({
        url: "<?php echo site_url('Njmwagesprocess/njmwgsbrksummexceldownload'); ?>",
        type: "POST",
        data: formData,
        processData: false,        // important for FormData
        contentType: false,        // important for FormData
        xhrFields: {
            responseType: 'blob'   // because server returns a file (zip/excel)
        },
        success: function (blob, status, xhr) {
            // Trigger download in browser
            var filename = "download.zip"; // default name

            var dispo = xhr.getResponseHeader('Content-Disposition');
            if (dispo && dispo.indexOf('filename=') !== -1) {
                var matches = /filename[^;=\n]*=((['"]).*?\2|[^;\n]*)/.exec(dispo);
                if (matches && matches[1]) {
                    filename = matches[1].replace(/['"]/g, '');
                }
            }

            var url = window.URL.createObjectURL(blob);
            var a = document.createElement('a');
            a.href = url;
            a.download = filename;
            document.body.appendChild(a);
            a.click();
            a.remove();
            window.URL.revokeObjectURL(url);
                   hideSpinnerCounter();
        },
        error: function (xhr, status, error) {
            console.log('Error:', xhr.responseText || error);
        }
    });
});






$("#wgbrksubmit1").change(function(event){
    event.preventDefault();
    alert('File Selected for ');
    var formData = new FormData();
    formData.append('periodfromdate', $('#njmcntfromdt').val());
    formData.append('periodtodate', $('#njmcnttodt').val());
    formData.append('fileupload', $('#fileuploadwgbrk')[0].files[0]);

    alert($('#njmcntfromdt').val());

    formData.append('att_payschm', $('#att_payschm').val());
    var att_payschm =  $('#att_payschm').val();

        //         showSpinnerCounter();

    $.ajax({
        url: "<?php echo base_url('Njmwagesprocess/njmwgsbrksummexceldownload'); ?>",
        type: "POST",
        data: formData,
        processData: false,  // Prevent jQuery from automatically transforming the data into a query string
        contentType: false,  // Ensure the request is sent as multipart/form-data
        dataType: "json",
        success: function(response) {
            if (response.success) {
                var savedata = (response.savedata);
                var ebmissing = (response.ebmissing);
                alert('Record Updated Successfully');
                hideSpinnerCounter();
              $('#wrkfaexlfileupload').val('');
              $('#record_id').val(0);
            } else {
                alert('No Data');
            }
        }
    });


});



      $("#brkexlfileuploads").click(function(event){
      event.preventDefault(); 
	  var opt=3;
             event.preventDefault();     
                periodfromdate = $('#oattfromdt').val();
                periodtodate = $('#oattfromdt').val();
                
//                exportdbfdata
//02-03-2024 anotherFunction

var url = '<?php echo site_url("Ejmprocessdata/oattprddownload"); ?>' +
                      '?periodfromdate=' + periodfromdate
                      ;
                      alert(url);
			//$(location).attr('href',url);
			window.open( url, '_blank');
			
			
return false;
});



      $("#updtrates").click(function(event){
          event.preventDefault(); 
          var periodfromdate= $('#oattfromdt').val();
          var ebnos=$('#ebnos').val();
          var updtrate=$('#updtrate').val();
          var shiftoff  =$('#shiftoff').val();
          if (updtrate == 0) {
              alert('Please Enter Rate');
              return;
         }
              showSpinnerCounter();
        $.ajax({
          url: "<?php echo base_url('Njmwagesprocess/updtrates'); ?>",
          type: "POST",
          data: {periodfromdate : periodfromdate,ebnos : ebnos,updtrate: updtrate,shiftoff: shiftoff},
          dataType: "json",
          success: function(response) {
           

            var savedata=(response.savedata);
              if (response.success) {
                  alert(savedata);
                   $('#record_id').val(0);
                   hideSpinnerCounter();
 
            } else {
                   hideSpinnerCounter();
                   alert('No Data');
                 
      
              }
          }
      });
 //     refreshDataTable();
    });


 



////
$("#updatesalcomp").click(function(event){
    event.preventDefault();
//    alert('File Selected');
    var formData = new FormData();
    formData.append('periodfromdate', $('#advpfromdt').val());
    formData.append('periodtodate', $('#advptodt').val());
    formData.append('fileupload', $('#payrollexlfileupload')[0].files[0]);
//    alert(formData.get('fileupload'));
    formData.append('att_payschm', $('#att_payschm').val());
    var att_payschm =  $('#att_payschm').val();

              if (att_payschm == 0) {
              alert('Please Select Pay Scheme');
              $('#cntexlfileuploads').val('');

              return;
          }  
                 showSpinnerCounter();
//alert(formData);
//process_csv
//cntexlupload
    $.ajax({
        url: "<?php echo base_url('Ejmprocessdata/updatesalcomp'); ?>",
        type: "POST",
        data: formData,
        processData: false,  // Prevent jQuery from automatically transforming the data into a query string
        contentType: false,  // Ensure the request is sent as multipart/form-data
        dataType: "json",
        success: function(response) {
//            alert(response.success);
//            alert(response.ebmissing);
//            alert(response.savedata);
            

            if (response.success) {
                var savedata = (response.savedata);
                var ebmissing = ('no of emp='+response.ebmissing);  
//                alert(savedata);
                alert(ebmissing);
                alert('total='+response.allupdt)
                
                alert('Record Updated Successfully');
                $('#cntexlfileuploads').val('');
                hideSpinnerCounter();
                $('#record_id').val(0);
            } else {
                alert('failed '+response.savedata);
                alert('reason '+response.payschms);
                hideSpinnerCounter();
            }
        }
    });
});


////







            });

            

            
    </script>
      