
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
  
            <div class="reporthead"><?='Holiday & Attendance Eligibility Data Entry'?></div>

    <div class="form-group">

        <div class="col-12 col-sm-2">
            <label for "email">Employee Code</label>
            <input class="form-control form-control-rounded select-on-focus" id="ebno"   name="ebno" type="text">
        </div>

        <div class="col-12 col-sm-4">
            <label for "email">Employee Name</label>
            <input class="form-control form-control-rounded select-on-focus" id="ebname" readonly  name="ebname" type="text">
        </div>
 
        <div class="col-12 col-sm-2">
               <label for="email">Holiday Eligibility</label>
              <?php
                    $holget['N'] = 'No';
                    $holget['Y'] = 'Yes';
                     
                    echo form_dropdown('hol_get', $holget, ($hol_get ? $hol_get : "0"), 'id="hol_get"  class="myselect form-control form-control-rounded" data-placeholder="Select Holiday "  style="width:100%;"');
                ?>
              
              </div>
              <div class="col-12 col-sm-3">
              <label for="email">Attendance Incentive</label>
              <?php
                    $aincget['N'] = 'No';
                    $aincget['Y'] = 'Yes';
              
              //      foreach ($payschemes as $payschm) {
              //          $de[$payschm->ID] = $payschm->NAME;
              //      }       
                    
                    echo form_dropdown('ainc_get', $aincget, ($ainc_get ? $ainc_get : "0"), 'id="ainc_get"  class="myselect form-control form-control-rounded" data-placeholder="Select Attendance Incentive"  style="width:100%;"');
                ?>
              
          </div>
            </div>

        <div class="form-group clearfix">                  
        <div class="col-sm-2">
            <label for "email">FNE Incentive Amount</label>
            <input class="form-control form-control-rounded select-on-focus" id="fnincamt" value=0 name="fnincamt" type="number">
        </div>
        <div class="col-12 col-sm-2">
            <label for "email">Monthly Incentive Amount</label>
            <input class="form-control form-control-rounded select-on-focus" id="mnincamt" value=0 name="mnincamt" type="number">
        </div>
           <div class="col-12 col-sm-1">
			    <label for="purchaseDetailsPurchaseDate">Save<span class="text-center"></span></label>
                <button name="submit" id="elegsavedata"  type="submit" class="form-control btn btn-primary">Save</button>
                <button name="submit" id="elegupdatedata"  type="submit" class="form-control btn btn-primary">Update</button>
            </div>
            <div class="col-12 col-sm-1">
						    <label for="purchaseDetailsPurchaseDate">Delete<span class="text-center"></span></label>
                <button name="submit" id="elegdeldata"  type="submit" class="form-control btn btn-danger">Delete</button>
            </div>
            <div class="col-12 col-sm-1">
						    <label for="purchaseDetailsPurchaseDate">Reset<span class="text-center"></span></label>
                <button name="submit" id="elegresetdata"  type="submit" class="form-control btn btn-danger">Reset</button>
            </div>

        <?php
            //   $company_id = $this->session->userdata('company_id');
               $company_name = $this->session->userdata('company_name');
               $company_id = $this->session->userdata('companyId');                //  echo $company_id;
              ?>
              <input type="hidden" class="input" value="<?php echo $company_id; ?>" id="companyId" />
              <input type="hidden" class="input" id="ebid" />
              <input type="hidden" class="input" id="record_id" />              

        </div> 
     
      <hr style="height:4px; background-color: brown;"></hr>
            <h4 align="center" style="font-family:Droid Serif">Holiday & Attendance Eligibility List</h4>
            <hr style="height:4px; background-color: brown;"></hr>
    <table id="spgdailyrecordTable" class="display">
        <thead>
            <tr>
                <th >Rec Id</th>
                <th>Emp Code</th>
                <th>Emp Name</th>
                <th>Holiday</th>
                <th>Attendance Incentive</th>
                <th>Fothnightly Incentive Rate</th>
                <th>Monthly Incentive Rate</th>
                <th>EB id</th>
                <th>Category</th>
  
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>

 
    </body>
</html>


<script>
         $(document).ready(function () {

            $('#elegsavedata').show();
            $('#elegupdatedata').hide();
            $("#elegsavedata").attr('disabled',true);
            $("#elegdeldata").attr('disabled',true);
            $("#elegupdatedata").attr('disabled',true);
            $('#record_id').val('0');
         
            $('#hol_get').val('N');
            $('#ainc_get').val('N');
         


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

            table = $('#spgdailyrecordTable').DataTable({
                ajax: {
                    url: '<?= base_url('Data_entry/gethlaincelegData') ?>',
                    type: 'POST',
                    data: function(d) {
                        d.date = $('#ebid').val();
                     }
                  },columnDefs: [
                    { targets: [0,7], visible: false }, // Hide the first column (auto_id)
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

        $('#spgdailyrecordTable tbody').on('click', 'tr', function() {
                var rowData = table.row(this).data();
                $('#record_id').val(rowData[0]);
                $('#ebno').val(rowData[1]);
                $('#ebname').val(rowData[2]);
               
//                $avtp=rowData[3].text.substr(1, 1); 
                $avtp = rowData[3].charAt(0);
         //       alert($avtp);
                $('#hol_get').val($avtp);
                $("#hol_get").trigger('change');
                $avtp = rowData[4].charAt(0);
                $('#ainc_get').val($avtp);
                $("#ainc_get").trigger('change');
                $('#fnincamt').val(rowData[5]);
                $('#mnincamt').val(rowData[6]);
                $('#ebid').val(rowData[7]);
                $('#elegsavedata').hide();
                $('#elegupdatedata').show();
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

            $('#hol_get').on('change', function() {
               
                refreshDataTable();
                datavaildation() ;
              
            });

            $('#ainc_get').on('change', function() {
               
               refreshDataTable();
               datavaildation() ;   
           });
//
$('#fnincamt').on('input', function() {
    datavaildation() ;   
});
$('#mnincamt').on('input', function() {
    datavaildation() ;   
});

        function datavaildation() {
                    var datavalid=1;
                    var holget =  $('#hol_get').val();
                    var aincget= $('#ainc_get').val();
                    var fnincamt= parseFloat($('#fnincamt').val());
                    var mnincamt= parseFloat($('#mnincamt').val());
                    var tinc=fnincamt+mnincamt;
                     var ebid=$('#ebid').val();
                    if (fnincamt<0) {
                        $('#fnincamt').val(0);
                    }
                    if (mnincamt<0) {
                        $('#mnincamt').val(0);
                    }
                    if (aincget === 'Y' ) {
                        if (fnincamt + mnincamt <= 0) {
                              var datavalid = 0;
                    }
                    }
                    if (aincget === 'N' ) {
                        if (fnincamt + mnincamt > 0) {
                      var datavalid = 0;
                    }
                    }


                    if (ebid<=0) {
                        var datavalid=0;
                    }
                 
                    var record_id= $('#record_id').val();  
                    if (record_id>0) {
                        if (datavalid==1) {    
                          
                            $("#elegupdatedata").attr('disabled',false);
                        } else {
                            $("#elegupdatedata").attr('disabled',true);
                        }
                    } else {
                         
                        if (datavalid==1) {    
                            $("#elegsavedata").attr('disabled',false);
                        } else { 
                            $("#elegsavedata").attr('disabled',true);
                    }

        }
    }

        $("#elegsavedata").click(function(event){
          event.preventDefault(); 
                var holget =  $('#hol_get').val();
                var aincget= $('#ainc_get').val();
                var fnaincamt= $('#fnincamt').val();
                var mnaincamt= $('#mnincamt').val();
                var companyId=$('#companyId').val();
                var ebid=$('#ebid').val();
                var record_id= $('#record_id').val();
                alert(fnaincamt);
            $.ajax({
            url: "<?php echo base_url('Data_entry/elegsavedata'); ?>",
            type: "POST",
            data: {holget: holget,aincget: aincget,companyId: companyId,record_id: record_id,
                ebid: ebid,fnaincamt: fnaincamt,mnaincamt: mnaincamt 
            },
            dataType: "json",
            success: function(response) {
              var savedata=(response.savedata);
                if (response.success) {
                    alert('Record Updated Successfully');
                    $('#elegupdatedata').hide();
                    $('#elegsavedata').show();
                    $('#ebno').val('');
                    $('#ebname').val('');
                    $('#ebid').val(0);
                    $('#record_id').val(0);
                    
                   refreshDataTable();
                   datavaildation() ;   
     
                }
            }
        });
        refreshDataTable();
      });


      $("#elegresetdata").click(function(event){
                    $('#elegupdatedata').hide();
                    $('#elegsavedata').show();
                    $('#ebno').val('');
                    $('#ebname').val('');
                    $('#ebid').val(0);
                    $('#record_id').val(0);
                    datavaildation() ;   
               
      });

      $("#elegupdatedata").click(function(event){
          event.preventDefault(); 
          var holget =  $('#hol_get').val();
                var aincget= $('#ainc_get').val();
                var fnaincamt= $('#fnincamt').val();
                var mnaincamt= $('#mnincamt').val();
                var companyId=$('#companyId').val();
                var ebid=$('#ebid').val();
                var record_id= $('#record_id').val();
         //       alert(mnaincamt);
            $.ajax({
            url: "<?php echo base_url('Data_entry/updateeleg_data'); ?>",
            type: "POST",
            data: {holget: holget,aincget: aincget,companyId: companyId,record_id: record_id,
                ebid: ebid,fnaincamt: fnaincamt,mnaincamt: mnaincamt 
            },
            dataType: "json",
            success: function(response) {
              var savedata=(response.savedata);
                if (response.success) {
                    alert('Record Updated Successfully');
                    $('#elegupdatedata').hide();
                    $('elegsavedata').show();
                    $('#ebno').val('');
                    $('#ebname').val('');
                    $('#ebid').val(0);
                    $('#record_id').val(0);
                    
                   refreshDataTable();
     
                }
            }
        });
        refreshDataTable();
      });
      
           $('#ebno').on('input', function() {
                 var ebno =  $('#ebno').val();
                 var companyId=$('#companyId').val();
                  var tw=0;
                 $.ajax({
                url: '<?= base_url('Data_entry/getEbMaster') ?>',
                type: "POST",
                data: {ebno: ebno},
                dataType: "json",
                success: function(response) {
                        $('#ebname').val(response.empname);
                        $('#ebid').val(response.eb_id);
                        var tw=$('#ebid').val();
                        datavaildation() ;   
                        $('#ebname').css({'border-color': 'green','background-color': 'white'
                  });
            
                        $('#ebid').css({'border-color': 'green','background-color': 'white'
                    });
                    
                    if (tw==0) {
                 //       alert($('#ebid').val())
                 //       alert('eb');
                        $('#ebname').css({'border-color': 'red','background-color': 'yellow'
                    });
                  }
                }
                });


                });


        
        


            });
        
    </script>
      