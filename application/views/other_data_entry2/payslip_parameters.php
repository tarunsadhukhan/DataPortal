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
  <!--
<script src="<?php echo base_url()?>public/admin/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
-->
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.min.css">
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/fixedcolumns/4.3.0/css/fixedColumns.dataTables.min.css">
<script src="https://cdn.datatables.net/fixedcolumns/4.3.0/js/dataTables.fixedColumns.min.js"></script>

<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/keytable/2.11.0/css/keyTable.dataTables.min.css">
<script src="https://cdn.datatables.net/keytable/2.11.0/js/dataTables.keyTable.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<link rel="stylesheet" type="text/css" href="<?= base_url('public/assets/css/editor.dataTables.css') ?>">
<script type="text/javascript" src="<?= base_url('public/assets/js/dataTables.editor.js') ?>"></script>

<link rel="stylesheet" type="text/css" href="<?= base_url('public/assets/css/editor.dataTables.min.css') ?>">
<script type="text/javascript" src="<?= base_url('public/assets/js/dataTables.editor.min.js') ?>"></script>


<!--
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/dataTables.editor.css">

 DataTables Editor JS 
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.5/js/dataTables.editor.js"></script>
 -->


</head>
<body>
  
<div class="reporthead"><?='Pay Register Parameters'?></div>

    <div class="form-group">


          <div class="col-12 col-sm-3">
              <label for="email">Branch</label>
              <?php
                    $bde['0'] = 'Select';
                    foreach ($branchs as $branch) {
                        $bde[$branch->branch_id] = $branch->branch_name;
                    }       
                    
                    echo form_dropdown('att_branch', $bde, ($att_branch ? $att_branch : "0"), 'id="att_branch"  class="myselect form-control form-control-rounded" data-placeholder="Select Branch"  style="width:100%;"');
                ?>
              
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
         


  
   
          <div class="col-12 col-sm-2">
    	    <label for="purchaseDetailsPurchaseDate">Update <span class="text-center"></span></label>
                <button name="submit" id="payschemeupdate"  type="submit" class="form-control btn btn-primary">Update</button>
         </div>
    <div class="col-12 col-sm-2">
    	    <label for="purchaseDetailsPurchaseDate">Preview<span class="text-center"></span></label>
                <button name="submit" id="payschmepreview"  type="submit" class="form-control btn btn-primary">Preview</button>
            </div>
   

<?php
               $company_id = $this->session->userdata('companyId');
               $company_name = $this->session->userdata('company_name');
                //  echo $company_id;
              ?>
              <input type="hidden" class="input" value="<?php echo $company_id; ?>" id="companyId" />
              <input type="hidden" class="input" id="ebid" />
              <input type="hidden" class="input" id="record_id" />              
              <input type="hidden" class="input" id="prvamt" />              
              <input type="hidden" class="input" id="payschemename" /> 
   
            </div> 
     
        <hr style="height:4px; background-color:#0f4d92  ;"></hr>
     
            <h4 align="center" style="font-family:Droid Serif">Pay slip Print Parameters</h4>
            <hr style="height:4px; background-color: #0f4d92  ;"></hr>
        <table id="mainpaydatatable"  class="display">
        <thead>
            <tr>

                <th>Para id</th>
                <th>Company id</th>
                <th>Branch id</th>
                <th>Payschme Id</th>
                <th>Payscheme Name</th>
                <th>Component Id</th>
                <th>Component Name</th>
                <th>Display Name</th>
                <th>Payslip Print Order</th>
                <th>Print in Payslip</th>
                <th>Total on Payslip</th>
                <th>pps</th>
                <th>tps</th>
                <th>Action</th>
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

    }

    hideAllTables();
            function hideAllTables() {
            //alert ("hideallTables");
//        document.getElementById("mainpaydatatable").style.display = "none";
     //   document.getElementById("voucherpaydatatable").style.display = "none";
     //   document.getElementById("contactdatatable").style.display = "none";

    }        
        
$('.select-on-focus').on('focus', function() {
    this.select();
s});

 
var editor; 
initDataTable1();
/*
            function initDataTable1() {

      



                table = $('#mainpaydatatable').DataTable({
                 "processing": true,
                 "paging": false, // Disable pagination
            "fixedHeader": true, // Enable fixed header
                ajax: {
                    url: '<?= base_url('Data_entry/getpayschemeparadata') ?>',
                    type: 'POST',
                    data: function(d) {
                        d.att_branch = $('#att_branch').val();
                        d.att_payschm = $('#att_payschm').val();
                        d.company = $('#companyId').val();
                     }
                  },columnDefs: [
                    { targets: [0,1,2,3,5,11,12], visible: false }, // Hide the first column (auto_id)
              
                    {
                    targets: [2, 3],
                    render: function(data, type, row, meta) {
                        return '<div class="column-align-right">' + data + '</div>';
                    }
                  },
                  {
                targets: [9,10], // Assuming the checkbox column is the 5th column (index 4)
                render: function(data, type, row, meta) {
                    // Assuming data contains the value 0 or 1
                    return data == 1 ? '<input type="checkbox" checked>' : '<input type="checkbox">';
                }
                },
            {
                targets: -1, // Assuming the icon column is the last column
                render: function(data, type, row, meta) {
            //       return '<i class="fas fa-save"></i>';
                return '<button class="delete-button" data-record-id="' + row[0] + '"><i class="fas fa-save"></i></button>';


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
                 scrollY: "460px",
    scrollCollapse: true,
  scrollX: true,
  scroller: true,
  scrolly:true,  
  autoWidth: false,   
  paging: false, 
  order: false

                                 // Sort by the first column (auto_id) in descending order
              // Set the default number of rows per page to 25
              });
        }

            
 

 
    
   //     refreshDataTable();
      });
*/
function initDataTable1() {
//    url: "<?php echo base_url('Data_entry/updatepayschemeparadata'); ?>",

var editor = new $.fn.dataTable.Editor({
                ajax: "<?php echo base_url('Data_entry/updatepayschemeparadata'); ?>", // Replace with your server-side script
                table: "#mainpaydatatable",
                fields: [
                    { label: "Para id:", name: "para_id" },
                    { label: "Company id:", name: "company_id" },
                    { label: "Branch id:", name: "branch_id" },
                    { label: "Payscheme Id:", name: "payscheme_id" },
                    { label: "Payscheme Name:", name: "payscheme_name" },
                    { label: "Component Id:", name: "component_id" },
                    { label: "Component Name:", name: "component_name" },
                    { label: "Display Name:", name: "display_name" },
                    { label: "Payslip Print Order:", name: "payslip_print_order" },
                    { label: "Print in Payslip:", name: "print_in_payslip" },
                    { label: "Total on Payslip:", name: "total_on_payslip" },
                    { label: "pps:", name: "pps" },
                    { label: "tps:", name: "tps" },
                    // Add configuration for other fields as needed
                ]
            });

   
    table = $('#mainpaydatatable').DataTable({
        processing: true,
        paging: false,
        fixedHeader: true,
        ajax: {
            url: '<?= base_url('Data_entry/getpayschemeparadata') ?>',
            type: 'POST',
            data: function(d) {
                d.att_branch = $('#att_branch').val();
                d.att_payschm = $('#att_payschm').val();
                d.company = $('#companyId').val();
            }
        },
        columnDefs: [
            { targets: [0, 1, 2, 3, 5, 11], visible: false },
            
            {
                targets: [2, 3],
                render: function(data, type, row, meta) {
                    return '<div class="column-align-right">' + data + '</div>';
                }
            },
            {
                targets: [9, 10],
                render: function(data, type, row, meta) {
                    return data == 1 ? '<input type="checkbox" checked>' : '<input type="checkbox">';
                }
            },
            {
                targets: -1,
                render: function(data, type, row, meta) {
                    return '<button class="delete-button" data-record-id="' + row[0] + '"><i class="fas fa-save"></i></button>';
                }
            },
            
            {
                targets: -2,
                render: function(data, type, row, meta) {
                    return '<button class="edit-button" data-record-id="' + row[0] + '"><i class="fas fa-edit"></i></button>';
                }
            },
        ],
        drawCallback: function() {
            $('#recordTable td.column-align-center').css('text-align', 'center');
            $('#recordTable td.column-align-right').css('text-align', 'right');
        },
        fixedColumns: {
            leftColumns: 4
        },
        scrollY: "460px",
        scrollCollapse: true,
        scrollX: true,
        scroller: true,
        scrolly: true,
        autoWidth: false,
        paging: false,
        order: false
    });

    // Enable inline editing for the specified column (column 7)
}
$('#mainpaydatatable tbody').on('click', 'td:eq(3)', function() {
    alert('gg');
    var row = table.row($(this).closest('tr'));
   alert(row);
    if (row) {
        alert('Row index:', row.index());
        alert('Editor state:', editor);
        editor.edit(row.index(), false).submit();
    } else {
        alert('Row not found!');
    }
            });

            // Add event listener for the Edit button in the last column
       
            $('#mainpaydatatable tbody').on('click', 'button.delete-button', function() {
                alert('jjj');
                var row = table.row($(this).parents('tr'));
               editor.edit(row.index(), false).submit();
            });

});
 

            // Enable inline editing for the "Payslip Print Order" column (column 9)

 


      function refreshDataTable() {
            table.ajax.reload(null, false); // Reload the data without resetting the current page
        }
      
        
      $('#att_payschm,#att_branch').on('change', function() {
               
                refreshDataTable();
            });

$('#mainpaydatatable tbody').on('click', 'button.delete-button', function () {
//    var row = table.row($(this).parents('tr'));
  //              editor.edit(row.index(), false).submit();
              var closestRow = $(this).closest('tr');
    
    // Get the data associated with the table row
    var rowData = table.row(closestRow).data();
    var checkbox1Value = $(this).closest('tr').find('td:eq(4) input').prop('checked') ? 1 : 0;
    var checkbox2Value = $(this).closest('tr').find('td:eq(5) input').prop('checked') ? 1 : 0;
//         alert (rowData[8]);
    if (rowData) {
        var recordId = rowData[0];
               
 
 
     //   updateRecord(recordId);
        $.ajax({
    url: "<?php echo base_url('Data_entry/updatepayschemeparadata'); ?>",
            type: "POST",
            data: {recordId: recordId,checkbox1Value:checkbox1Value,checkbox2Value:checkbox2Value },
            dataType: "json",
            success: function(response) {
              var savedata=(response.savedata);
                 if (response.success) {
                    
                  alert('Record Updated Successfully');
                  refreshDataTable();

                  }
         
                }
      
              });
        // Assuming the data you want is in the first column (index 0)
    } else {
        alert("Row data not found.");
    }
              
                var recordId = $(this).data('record-id');
               var rcvqty=rowData[7];
            });


$('#payschemeupdate').on('click', function() {
    var table = $('#mainpaydatatable').DataTable();

    table.rows().every(function(rowIdx, tableLoop, rowLoop) {
        var data = this.data();
        var updatedData = [];
      
        // Assuming you want to update columns 2, 3, and 5 (change these to your actual indices)
        for (var i = 0; i < data.length; i++) {
            // Modify the values or perform any updates here
//            var checkboxState = this.nodes().to$().find('td:eq(8) input[type="checkbox"]').prop('checked');

            updatedData.push(data[i]);
            var row = data[i];
            var rowData = table.row(rowIdx).data();
 //           var rowData = table.row(closestRow).data();
            if (i==8) {
                var checkboxValue8 = rowData[8].prop('checked') ? 1 : 0;
                // === 1 ? 1 : 0;

                //                var checkboxValue8 = $(this).closest('tr').find('td:eq(8) input').prop('checked') ? 1 : 0;
            }    
             
            

        }

 

        var checkboxColumnIndex = 0;
        var checkboxValues0 = data[checkboxColumnIndex];


        var checkboxColumnIndex = 8;
// Assuming the checkbox value is stored in an array within the data
// Replace 'checkboxValues' with the actual array name containing checkbox values


var checkboxValues8 = data[checkboxColumnIndex];
// Modify the values in the updatedData array based on the checked state
if (checkboxValues0==96) {
            alert (checkboxValues0);
            alert (checkboxValues8);
            alert (checkboxValue8);
            

        }

updatedData[checkboxColumnIndex] = checkboxValue8 === 1 ? 0 : 1;


console.log(checkboxValues);
var checkboxColumnIndex = 9;
// Assuming the checkbox value is stored in an array within the data
// Replace 'checkboxValues' with the actual array name containing checkbox values
var checkboxValues = data[checkboxColumnIndex];
// Modify the values in the updatedData array based on the checked state
updatedData[checkboxColumnIndex] = checkboxValues === 1 ? 0 : 1;

var checkboxColumnIndex = 8;
// Assuming the checkbox value is stored in an array within the data
// Replace 'checkboxValues' with the actual array name containing checkbox values
var checkboxValues = data[checkboxColumnIndex];
 
//alert(updatedData); 
        // Update the row data
 //       this.data(updatedData);

        // Example: Disable input fields in columns 2, 3, and 5
        var columnsToDisable = [2, 3, 5];
        columnsToDisable.forEach(function(columnIndex) {
            this.nodes().to$().find('td:eq(' + columnIndex + ') input, td:eq(' + columnIndex + ') select').prop('disabled', true);
        }.bind(this));

        // Redraw the row to apply changes
        this.invalidate();
  
    });

    // Redraw the table to apply changes
    table.draw();
});


$('#mainpaydatatable').on('change', 'td:eq(8) input[type="checkbox"]', function() {
    alert('change');

    var table = $('#mainpaydatatable').DataTable();
    var closestRow = $(this).closest('tr');
    var rowIndex = table.row(closestRow).index();

    // Get the current data of the row
    var rowData = table.row(rowIndex).data();

    // Get the new checkbox value
    var newCheckboxValue = $(this).prop('checked') ? 1 : 0;

    // Update another column (column 9 in this example) based on the checkbox value
    rowData[10] = newCheckboxValue;

    // Redraw the row to apply changes
    table.row(rowIndex).invalidate().draw();
});



        
                $('.select2-multi').select2({
                multiple: true,
                placeholder: 'Select options',
                width: '100%',
            });

       //     });
        
    </script>
      