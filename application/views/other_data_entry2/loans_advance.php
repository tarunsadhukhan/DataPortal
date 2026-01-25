
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
  
            <div class="reporthead"><?='Loans & Advance Data Entry'?></div>

    <div class="form-group">
        <div class="col-12 col-sm-2">
            <label for="account-name">Loans/Advance Date</label>
            <input class="form-control form-control-rounded" value="<?= date('Y-m-d') ?>" id="fromdt" name="fromdt" type="date">
    </div>

        <div class="col-12 col-sm-2">
            <label for="email">Type</label>
            <?php
                $advtype['L'] = 'Loans';
                $advtype['P'] = 'Puja Advance';
                $advtype['O'] = 'Others Advance';
                
                echo form_dropdown('adv_type', $advtype, ($adv_type ? $adv_type : "0"), 'id="adv_type"  class="myselect form-control form-control-rounded" data-placeholder="Select Type"  style="width:100%;"');
            ?>
        </div>

        <div class="col-12 col-sm-2">
            <label for "email">Employee Code</label>
            <input class="form-control form-control-rounded select-on-focus" id="ebno"   name="ebno" type="text">
        </div>

        <div class="col-12 col-sm-5">
            <label for "email">Employee Name</label>
            <input class="form-control form-control-rounded select-on-focus" id="ebname" readonly  name="ebname" type="text">
        </div>
    </div>

    <div class="form-group">
        <div class="col-sm-2">
            <label for "email">Loans/Advance Amount</label>
            <input class="form-control form-control-rounded select-on-focus" id="loanadvamt" value=0 name="loanadvamt" type="number">
        </div>
        <div class="col-12 col-sm-2">
            <label for "email">Installment Amount</label>
            <input class="form-control form-control-rounded select-on-focus" id="instamt" value=1 name="instamt" type="number">
        </div>
        <div class="col-12 col-sm-2">
            <label for "email">No of Installments</label>
            <input class="form-control form-control-rounded" readonly id="noofinst" value=0 name="noofinst" type="text">
        </div>
        <div class="col-12 col-sm-2">
            <label for="account-name">Installment Start Date</label>
            <input class="form-control form-control-rounded " value="<?=$from_date?>" id="inststartdate" name="inststartdate" type="date">
          </div>
          <div class="col-12 col-sm-1">
						    <label for="purchaseDetailsPurchaseDate">Save<span class="text-center"></span></label>
                <button name="submit" id="advsavedata"  type="submit" class="form-control btn btn-primary">Save</button>
                <button name="submit" id="advupdatedata"  type="submit" class="form-control btn btn-primary">Update</button>
            </div>
            <div class="col-12 col-sm-1">
						    <label for="purchaseDetailsPurchaseDate">Delete<span class="text-center"></span></label>
                <button name="submit" id="advdeldata"  type="submit" class="form-control btn btn-danger">Delete</button>
            </div>
            <div class="col-12 col-sm-1">
						    <label for="purchaseDetailsPurchaseDate">Print<span class="text-center"></span></label>
                <button name="submit" id="advresetdata"  type="submit" class="form-control btn btn-danger">Reset</button>
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
            <h4 align="center" style="font-family:Droid Serif">Loans And Advance List</h4>
            <hr style="height:4px; background-color: brown;"></hr>
    <table id="spgdailyrecordTable" class="display">
        <thead>
            <tr>
                <th >Rec Id</th>
                <th>Loan/Adv date </th>
                <th>Adv Type</th>
                <th>Emp Code</th>
                <th>Emp Name</th>
                <th>Adv Amount</th>
                <th>Installment Amt</th>
                <th>No of Installment</th>
                <th>Installment Start From</th>
                <th>EB id</th>
 
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
            $("#advsavedata").attr('disabled',true);
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
        alert($('#fromdt').val());
            table = $('#spgdailyrecordTable').DataTable({
                ajax: {
                    url: '<?= base_url('Data_entry/getLoanadvtranData') ?>',
                    type: 'POST',
                    data: function(d) {
                        d.date = $('#fromdt').val();
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

            $('#fromdt').on('change', function() {
                $('#ebno').val('');
                $('#ebname').val('');
               
                refreshDataTable();
                datavaildation() ;   
            });

//

        function datavaildation() {
                    var datavalid=1;
                    var loanadvamt =  $('#loanadvamt').val();
                    var loanadvamt=parseFloat(loanadvamt);
                    var instamt= $('#instamt').val();
                    var instamt=parseFloat(instamt);
                    var companyId=$('#companyId').val();
                    var ebid=$('#ebid').val();
//                  
                  //  alert(datavalid);
                    if (loanadvamt<=0) {
                        var datavalid=0;
                    }
                    if (instamt<=0) {
                        var datavalid=0;
                    }
                 //   alert(datavalid);
             
                 if (loanadvamt<instamt) {
                        var datavalid=0;
                    }
                    if (ebid<=0) {
                        var datavalid=0;
                    }
                  //  alert(datavalid);
                 
                    var record_id= $('#record_id').val();  
//                    alert (datavalid);
                //    alert(record_id);
                    if (record_id>0) {
                        if (datavalid==1) {    
                          
                            $("#advupdatedata").attr('disabled',false);
                        } else {
                            $("#advupdatedata").attr('disabled',true);
                        }
                    } else {
                         
                        if (datavalid==1) {    
                            $("#advsavedata").attr('disabled',false);
                        } else { 
                            $("#advsavedata").attr('disabled',true);
                    }

        }
    }

        $("#advsavedata").click(function(event){
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
            url: "<?php echo base_url('Data_entry/saveadv_data'); ?>",
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
                   datavaildation() ;   
     
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
                    datavaildation() ;   
               
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
                   datavaildation() ;   
     
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


        
                $('#loanadvamt').on('input', function() {
                 //   alert('aaa');
                    var loanadvamt =  $('#loanadvamt').val();
                    var loanadvamt=parseFloat(loanadvamt);
                    var instamt= $('#instamt').val();
                    var instamt=parseFloat(instamt);
                    var companyId=$('#companyId').val();

//                    alert (loanadvamt);
                    if (loanadvamt<=0) {
                        alert ('Enter Loan/Adv Amount');
                        return false;
                    }
                    if (instamt<=0) {
                        alert ('Enter Installment Amount');
                        return false;
                    }
                    if (loanadvamt<instamt) {
                        alert ('Please check Installment Amount');
                        return false;
                    }
                    noofinst=(loanadvamt/instamt);
                    $('#noofinst').val(noofinst); 
                 //   alert(noofinst);                  
                    datavaildation() ;   
             
                });
       
                $('#instamt').on('input', function() {
                    var loanadvamt =  $('#loanadvamt').val();
                    var loanadvamt=parseFloat(loanadvamt);
                    var instamt= $('#instamt').val();
                    var instamt=parseFloat(instamt);
                    var companyId=$('#companyId').val();
                    if (loanadvamt<=0) {
                        alert ('Enter Loan/Adv Amount');
                        return false;
                    }
                    if (instamt<=0) {
                        alert ('Enter Installment Amount');
                        return false;
                    }
                    if (loanadvamt<instamt) {
                        alert ('Please check Installment Amount');
                        return false;
                    }
                    noofinst=(loanadvamt/instamt);
                    $('#noofinst').val(noofinst);                   
                    datavaildation() ;   
                });
       
        


            });
        
    </script>
      