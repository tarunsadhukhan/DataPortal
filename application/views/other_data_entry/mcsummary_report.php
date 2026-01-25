
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Loan and Advance Form</title>
    <style>
        .form-group {
            display: block;
            clear: both;
            margin-bottom: 70px;
        }
        #spgdailyrecordTable thead th {
            background-color: #E6F2F4 ; /* Background color for the header */
            color: #5B56F1 ; /* Font color for the header */
            font-size: 16px; /* Font size for the header */
     /*       white-space: normal;  Allow text to wrap to the next line */
        /*    word-wrap: break-word; /* Break long words */
          word-wrap: no-worp;
        }    </style>
    <script src="<?php echo base_url()?>public/admin/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>

<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.min.css">
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<!--
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
-->
   <!-- Select2 -->
</head>
<body>
  
            <div class="reporthead"><?='Daily Mechine Running'?></div>

    <div class="form-group">
        <div class="col-12 col-sm-2">
            <label for="account-name">Loans/Advance Date</label>
            <input class="form-control form-control-rounded" value="<?= date('Y-m-d') ?>" id="fromdt" name="fromdt" type="date">
    </div>
    <div class="col-12 col-sm-2">
               <label for="email">Report Type</label>
              <?php
                  // $holget[0] = 'Select';
                    $holget[1] = 'Daily Mechine Entry';
                    $holget[2] = 'Daily Hands Entry';
                        
                     
                    echo form_dropdown('hol_get', $holget, ($hol_get ? $hol_get : "0"), 'id="hol_get"  class="myselect form-control form-control-rounded" data-placeholder="Select Holiday "  style="width:100%;"');
                ?>
              
              </div>
    <div class="col-12 col-sm-2">
        <label for="email">Select Branch</label>
        <?php
        $this->data['companyId'] = $company;
        $this->data['branchs'] = $this->varaha_model->getAllBranchs($this->data['companyId']);
        $bde['0'] = '';
        foreach ($branchs as $branch) {
            $bde[$branch->branch_id] = $branch->branch_name;
        }
        echo form_dropdown('att_branch', $bde, ($att_branch ? $att_branch : "29"), 'id="att_branch" class="myselect form-control form-control-rounded" data-placeholder="Select Branch" style="width:100%;"');
        ?>
    </div>

    <div class="col-12 col-sm-2">
        <label for="email">Select Department</label>
        <?php
        $this->data['companyId'] = $company;
        $this->data['masterdepartments'] = $this->varaha_model->getAllMasterDepartments($this->data['companyId']);
        $de['0'] = '';
        foreach ($masterdepartments as $dept) {
            $de[$dept->rec_id] = $dept->dept_desc;
        }
        echo form_dropdown('att_dept', $de, ($att_dept ? $att_dept : "0"), 'id="att_dept" class="myselect form-control form-control-rounded" data-placeholder="Select Department" style="width:100%;"');
        ?>
    </div>


    <div class="col-12 col-sm-4" id="machineDropdown" style="display: none;">

        <label for="email">Select Machine</label>
        <?php
        $this->data['companyId'] = $company;
        $this->data['mccodes'] = $this->varaha_model->getAllMccodes($this->data['companyId']);
        $mc[''] = '';
  //      echo var_dump($mccodes);
        foreach ($mccodes as $mcc) {
            $mc[$mcc->mc_code_id] = $mcc->mc_code . '-' . $mcc->Mechine_type_name;
        }
        echo form_dropdown('att_mccode', $mc, ($mc_code ? $mc_code : "0"), 'id="att_mccode" class="myselect form-control form-control-rounded" data-placeholder="Select Machine" style="width:100%;"');
        ?>
    </div>

    <div class="col-12 col-sm-4" id="occupationDropdown" style="display: none;">
        <label for="email">Select Occupation</label>
        <?php
        $this->data['companyId'] = $this->session->userdata('company_id');;
        $this->data['mccodes'] = $this->Loan_adv_model->getAllDesignationscd($this->data['companyId']);
        $desg[''] = '';
        foreach ($this->data['mccodes'] as $desig) {
            $desg[$desig->id] = $desig->cddesig;
          //  echo $desig->hocc;
        }
        echo form_dropdown('att_desig', $desg, ($att_desig ? $att_desig : "0"), 'id="att_desig" class="myselect form-control form-control-rounded" data-placeholder="Select Occupation" style="width:100%;"');
        ?>
    </div>



    </div>

    <div class="form-group">
        <div class="col-sm-1">
            <label for="email">Spell A1</label>
            <input class="form-control form-control-rounded" id="spella1" value="0" name="spella1" type="text">   
        </div>
        <div class="col-12 col-sm-1">
            <label for="email">Spell A2</label>
            <input class="form-control form-control-rounded" id="spella2" value="0" name="spella2" type="text">
        </div>
        <div class="col-12 col-sm-1">
            <label for="email">Shift A</label>
            <input class="form-control form-control-rounded" id="shifta" value="0" name="shifta" type="text">
        </div>
        <div class="col-12 col-sm-1">
            <label for="email">Spell B1</label>
            <input class="form-control form-control-rounded" id="spellb1" value="0" name="spellb1" type="text">
        </div>
        <div class="col-12 col-sm-1">
            <label for="email">Spell B2</label>
            <input class="form-control form-control-rounded" id="spellb2" value="0" name="spellb2" type="text">
        </div>
        <div class="col-12 col-sm-1">
            <label for="email">Shift B</label>
            <input class="form-control form-control-rounded" id="shiftb" value="0" name="shiftb" type="text">
        </div>
        <div class="col-12 col-sm-1">
            <label for="email">Shift C</label>
            <input class="form-control form-control-rounded" id="shiftc" value="0" name="shiftc" type="text">
        </div>
        <div class="col-12 col-sm-1">
				<label for="purchaseDetailsPurchaseDate">Save<span class="text-center"></span></label>
                <button name="submit" id="advsavedata"  type="submit" class="form-control btn btn-primary">Save</button>
                <button name="submit" id="advupdatedata"  type="submit" class="form-control btn btn-primary">Update</button>
            </div>

<?php
               $company_id = $this->session->userdata('company_id');
               $company_name = $this->session->userdata('company_name');
                //  echo $company_id;
              ?>
              <input type="hidden" class="input" value="<?php echo $company_id; ?>" id="companyId" />
              <input type="hidden" class="input" id="ebid" />
              <input type="hidden" class="input" id="record_id" />              

        </div> 
     
      <hr style="height:4px; background-color: brown;"></hr>
            <h4 align="center" style="font-family:Droid Serif">Mechine Running  List</h4>
            <hr style="height:4px; background-color: brown;"></hr>
    <table id="spgdailyrecordTable" class="display">
        <thead>
            <tr>
                <th >Rec Id</th>
                <th>Date </th>
                <th>code</th>
                <th>Description</th>
                <th>Spell A1</th>
                <th>Spell A2</th>
                <th>Shift A</th>
                <th>Spell B1</th>
                <th>Spell B2</th>
                <th>Shift B</th>
                <th>Shift C</th>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>

 
    </body>
</html>


<script>
         $(document).ready(function () {

            $('#advsavedata').show();
            $('#advupdatedata').hide();
         //   $("#advsavedata").attr('disabled',true);
            $("#advdeldata").attr('disabled',true);
            $("#advupdatedata").attr('disabled',true);
            $('#record_id').val('0');
         


            var fromdtInput = document.getElementById("fromdt");

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


/*
                   $('#spgdailyrecordTable').DataTable({
                        "columnDefs": [
                        { "width": "10px", "targets": 0,visible: false }, // Set the width for the first column (Date)
                        { "width": "100px", "targets": 1 }, // Set the width for the second column (Q Code)
                        { "width": "70px", "targets": 2 }, // Set the width for the second column (Q Code)
                        { "width": "100px", "targets": 3 }, // Set the width for the second column (Q Code)
                    // Add widths for other columns as needed
                     ]
                    });
*/
                    initDataTable();
      function initDataTable() {
                var mcsummdate= $('#fromdt').val();
                var mcsummdeptid= $('#att_dept').val();
                var mcsummmcid= $('#att_mccode').val();
                 var spella1 =  $('#spella1').val();
                 var spella2 =  $('#spella2').val();
                 var shifta =  $('#shifta').val();
                 var spellb1 =  $('#spellb1').val();
                 var spellb2 =  $('#spellb2').val();
                 var shiftb =  $('#shiftb').val();
                 var shiftc =  $('#shiftc').val();
                 var companyId=$('#companyId').val();
                 var record_id= $('#record_id').val();
                 var att_branch= $('#att_branch').val();
                 var hol_get= $('#hol_get').val();
                 

         table = $('#spgdailyrecordTable').DataTable({
                ajax: {
                    url: '<?= base_url('Data_entry/getmcsummData') ?>',
                    type: 'POST',
                    data: function(d) {
                        d.date = $('#fromdt').val();
                        d.att_dept = $('#att_dept').val();
                        d.att_branch = $('#att_branch').val();
                        d.hol_get = $('#hol_get').val();
                        
                      }
                  },columnDefs: [
                    { targets: [0,9], visible: true }, // Hide the first column (auto_id)
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
                order: [[0, 'asc']],                 // Sort by the first column (auto_id) in descending order
                pageLength: 10 // Set the default number of rows per page to 25
              });
        }

        function refreshDataTable() {
            table.ajax.reload(null, false); // Reload the data without resetting the current page
        }

        $('#spgdailyrecordTable1 tbody').on('click', 'tr', function() {
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
           //     datavaildation() ;   
            
            });
        
            $('#recordTable tbody').on('click', 'tr', function() {
                if ($(this).hasClass('selected')) {
                    $(this).removeClass('selected');
                } else {
                    table.$('tr.selected').removeClass('selected');
                    $(this).addClass('selected');
                }
            });

            $('#fromdt').on('change', function() {
                $('#ebno').val('');
                $('#ebname').val('');
               
                refreshDataTable();
          //      datavaildation() ;   
            });

//

         $("#advsavedata").click(function(event){
                event.preventDefault();
                var mcsummdate= $('#fromdt').val();
                var mcsummdeptid= $('#att_dept').val();
                var mcsummmcid= $('#att_mccode').val();
                 var spella1 =  $('#spella1').val();
                 var spella2 =  $('#spella2').val();
                 var shifta =  $('#shifta').val();
                 var spellb1 =  $('#spellb1').val();
                 var spellb2 =  $('#spellb2').val();
                 var shiftb =  $('#shiftb').val();
                 var shiftc =  $('#shiftc').val();
                 var companyId=$('#companyId').val();
                 var record_id= $('#record_id').val();
                 var att_branch= $('#att_branch').val();
                 var att_desig= $('#att_desig').val();
                 var hol_get= $('#hol_get').val();
                 
                   
             $.ajax({
            url: "<?php echo base_url('Data_entry/savemcsumm_data'); ?>",
            type: "POST",
            data: {mcsummdate: mcsummdate,mcsummdeptid: mcsummdeptid,companyId: companyId,record_id: record_id,
                mcsummmcid: mcsummmcid,spella1: spella1,spella2: spella2,spellb1: spellb1,spellb2: spellb2,
                shifta: shifta,shiftb: shiftb,shiftc: shiftc,att_branch: att_branch,att_desig : att_desig ,hol_get: hol_get
            },
            dataType: "json",
            success: function(response) {
              var savedata=(response.savedata);
                if (response.success) {
                    alert('Record Updated Successfully');
                    $('#advupdatedata').hide();
                    $('#advsavedata').show();
                 $('#spella1').val(0);
                   $('#spella2').val(0);
                 $('#shifta').val(0);
                   $('#spellb1').val(0);
                   $('#spellb2').val(0);
                   $('#shiftb').val(0);
                   $('#shiftc').val(0);
                    $('#record_id').val(0);
                    refreshDataTable();
              //     datavaildation() ;   
     
                }
            }
        });
        refreshDataTable();
      });


      $("#advresetdata").click(function(event){
                    $('#advupdatedata').hide();
                    $('#advsavedata').show();
                    $('#ebno').val('');
                    $('#ebname').val('');
                    $('#ebid').val(0);
                    $('#record_id').val(0);
            //        datavaildation() ;   
               
      });

      $("#advupdatedata").click(function(event){
          event.preventDefault(); 
          var loanadvamt = $('#loanadvamt').val();
          var companyId=$('#companyId').val();
          var instamt= $('#instamt').val(); 
          var ebid= $('#ebid').val(); 
          var record_id= $('#record_id').val();
          var noofinst= $('#noofinst').val();
          var inststartdate= $('#inststartdate').val();
          var fromdt= $('#fromdt').val();
          var advtype=$('#adv_type').val();
            $.ajax({
            url: "<?php echo base_url('Data_entry/updateadv_data'); ?>",
            type: "POST",
            data: {loanadvamt: loanadvamt,instamt: instamt,companyId: companyId,record_id: record_id,
                ebid: ebid,noofinst: noofinst,inststartdate: inststartdate,fromdt: fromdt,advtype: advtype
            },
            dataType: "json",
            success: function(response) {
              var savedata=(response.savedata);
                if (response.success) {
                    alert('Record Updated Successfully');
                    $('#advupdatedata').hide();
                    $('#advsavedata').show();
                    $('#ebno').val('');
                    $('#ebname').val('');
                    $('#ebid').val(0);
                    $('#record_id').val(0);
                    
                   refreshDataTable();
          //         datavaildation() ;   
     
                }
            }
        });
        refreshDataTable();
      });


           $('#spella1').on('input', function() {
                 var spella1 =  $('#spella1').val();
                 var spella2 =  $('#spella2').val();
                 var companyId=$('#companyId').val();
                 var spella1=parseFloat(spella1);
                 var spella2=parseFloat(spella2);
                 var shifta=(spella1+spella2)/2;
                 shifta=shifta.toFixed(3);
                 $('#shifta').val(shifta); 
                });

                $('#spella2').on('input', function() {
                 var spella1 =  $('#spella1').val();
                 var spella2 =  $('#spella2').val();
                 var companyId=$('#companyId').val();
                 var spella1=parseFloat(spella1);
                 var spella2=parseFloat(spella2);
                 var shifta=(spella1+spella2)/2;
                 shifta=shifta.toFixed(3);
                 $('#shifta').val(shifta); 
                });

                $('#spellb1').on('input', function() {
                 var spellb1 =  $('#spellb1').val();
                 var spellb2 =  $('#spellb2').val();
                 var companyId=$('#companyId').val();
                 var spellb1=parseFloat(spellb1);
                 var spellb2=parseFloat(spellb2);
                 var shiftb=(spellb1+spellb2)/2;
                 shiftb=shiftb.toFixed(3);
                 $('#shiftb').val(shiftb); 
                });

                $('#spellb2').on('input', function() {
                 var spellb1 =  $('#spellb1').val();
                 var spellb2 =  $('#spellb2').val();
                 var companyId=$('#companyId').val();
                 var spellb1=parseFloat(spellb1);
                 var spellb2=parseFloat(spellb2);
                 var shiftb=(spellb1+spellb2)/2;
                 shiftb=shiftb.toFixed(3);
                 $('#shiftb').val(shiftb); 
                });

                
                $('#att_mccode').on('change', function () {
                    mcdesigchange();   
                });    
                $('#att_desig').on('change', function () {
                    mcdesigchange();   
                });    

              function mcdesigchange() {
      
                var mcsummdate= $('#fromdt').val();
                    var mcsummdeptid=$('#att_dept').val();
                    var mcsummmcid=$('#att_mccode').val();
                    var mcsummdate= $('#fromdt').val();
                var mcsummdeptid= $('#att_dept').val();
                var mcsummmcid= $('#att_mccode').val();
                 var spella1 =  $('#spella1').val();
                 var spella2 =  $('#spella2').val();
                 var shifta =  $('#shifta').val();
                 var spellb1 =  $('#spellb1').val();
                 var spellb2 =  $('#spellb2').val();
                 var shiftb =  $('#shiftb').val();
                 var shiftc =  $('#spellc').val();
                 var companyId=$('#companyId').val();
                 var record_id= $('#record_id').val();
                 var att_branch= $('#att_branch').val();
                 var att_desig= $('#att_desig').val();
                 var hol_get= $('#hol_get').val();
                  $.ajax({
            url: "<?php echo base_url('Data_entry/checkmcsumm_data'); ?>",
            type: "POST",
            data: {mcsummdate: mcsummdate,mcsummdeptid: mcsummdeptid,companyId: companyId,record_id: record_id,
                mcsummmcid: mcsummmcid,spella1: spella1,spella2: spella2,spellb1: spellb1,spellb2: spellb2,
                shifta: shifta,shiftb: shiftb,shiftc: shiftc ,att_branch: att_branch,att_desig: att_desig,hol_get: hol_get
            },
            dataType: "json",
            success: function(response) {
             //   alert (response.success);
              if (response.success) {
                  //  $('#trollyNo').val(response.trollyNo);
                //            alert('Mechine Alreday Entered');
                    $('#advupdatedata').hide();
                    $('#advsavedata').show();
                    $('#spella1').val(response.spella1);
                    $('#spella2').val(response.spella2);
                    $('#spellb1').val(response.spellb1);
                    $('#spellb2').val(response.spellb2);
                    $('#shifta').val(response.shifta);
                    $('#shiftb').val(response.shiftb);
                    $('#shiftc').val(response.shiftc);
                    $('#record_id').val(response.recordid);
            
                }
            }
        });
 
    }


$('#machineDropdown').show();
$('#hol_get').on('change', function () {
           var holget =  $('#hol_get').val();
         
                   var selectedValue = $(this).val();
            if (holget == 2) {
                $('#occupationDropdown').show();
                $('#machineDropdown').hide();
                refreshDataTable();
          
            } else if (holget ==1) {
                $('#occupationDropdown').hide();
                $('#machineDropdown').show();
                refreshDataTable();
               
               } else {
                $('#occupationDropdown').hide();
                $('#machineDropdown').show();
                refreshDataTable();
            }
        });

            });
        
    </script>
      