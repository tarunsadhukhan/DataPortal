
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
        #spgdailyrecordTable_wrapper .dataTables_scrollHead th {
            background-color: #0f4d92;
            color: #f8f8ff;
            font-size: 14px;
            word-wrap: nowrap;
            height: 10px;
}
        </style>
<script src="<?php echo base_url()?>public/admin/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!--
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.min.css">
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js
-->
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>

<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">

</head>
<body>
  
<div class="reporthead"><?='OT Register/Pay slip'?></div>

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
               <label for="email">Data For</label>
              <?php
                    $holget[1] = 'Main Pay Roll';
                    $holget[2] = 'Contractors ';
                     
                    echo form_dropdown('hol_get', $holget, ($hol_get ? $hol_get : "0"), 'id="hol_get"  class="myselect form-control form-control-rounded" data-placeholder="Select Holiday "  style="width:100%;"');
                ?>
              
              </div>


     </div>

    <div class="form-group">

   
          <div class="col-12 col-sm-2">
    	    <label for="purchaseDetailsPurchaseDate">OT Register<span class="text-center"></span></label>
                <button name="submit" id="otregisterdisplay"  type="submit" class="form-control btn btn-primary">Register</button>
         </div>
    <div class="col-12 col-sm-2">
    	    <label for="purchaseDetailsPurchaseDate">OT Payslip<span class="text-center"></span></label>
                <button name="submit" id="otpayslipprint"  type="submit" class="form-control btn btn-primary">Payslip</button>
            </div>
        <div class="col-12 col-sm-2">
    	    <label for="purchaseDetailsPurchaseDate">OT Register<span class="text-center"></span></label>
                <button name="submit" id="otregisterprint"  type="submit" class="form-control btn btn-primary">Print</button>
         </div>
   

<?php
               $company_id = $this->session->userdata('company_id');
               $company_name = $this->session->userdata('company_name');
                //  echo $company_id;
              ?>
              <input type="hidden" class="input" value="<?php echo $company_id; ?>" id="companyId" />
              <input type="hidden" class="input" id="ebid" />
              <input type="hidden" class="input" id="record_id" />              
              <input type="hidden" class="input" id="prvamt" />              
              <input type="hidden" class="input" id="payschemename" /> 
        </div> 
     
        <hr style="height:4px; background-color: #002e63  ;"></hr>
     
            <h4 align="center" style="font-family:Droid Serif">OT Register/Pay slip</h4>
            <hr style="height:4px; background-color: #002e63  ;"></hr>
        <table id="spgdailyrecordTable" class="display" width="100%">
        <thead>
            <tr>
            <th>Dept Code</th>
                <th>Department</th>
                <th>Emp Code</th>
                <th>Emp Name</th>
                <th>OT Hours</th>
                <th>Rate </th>
                <th>OT Amount</th>
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
       
        
$('.select-on-focus').on('focus', function() {
    this.select();
});

 

 
initDataTable1();

            function initDataTable1() {
                $('#spgdailyrecordTable').DataTable().destroy();
             
                table = $('#spgdailyrecordTable').DataTable({
  //              "sDom": 'r<"H"lf><"datatable-scroll"t><"F"ip>',
                 "processing": true,
      //           "serverSide": true,
            //   "bFilter": false,
 
                ajax: {
                    url: '<?= base_url('Data_entry/getotregisterdata') ?>',
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
                scrollX: true,
                order: false,                 // Sort by the first column (auto_id) in descending order
                pageLength: 10 // Set the default number of rows per page to 25
              });
        }

 
        
   
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

      
      
        $("#otregisterdisplay").click(function(event){
                event.preventDefault(); 
                var att_payschm =  $('#att_payschm').val();
                var holget =  $('#hol_get').val();
                periodfromdate = $('#advpfromdt').val();
                periodtodate = $('#advptodt').val();
                payschemeName = $('#payschemename').val();
                periodfromdate= periodfromdate.substring(8)+'-'+periodfromdate.substring(5,7)+'-'+periodfromdate.substring(0,4);
                periodtodate= periodtodate.substring(8)+'-'+periodtodate.substring(5,7)+'-'+periodtodate.substring(0,4);
                var hd1 = '';
                hd1 = 'OT Register for ' + payschemeName + ' Period From ' + periodfromdate + ' To ' + periodtodate;
                heading.textContent = hd1;
                initDataTable1();
        

 
            });


 
    
   //     refreshDataTable();
      });


  
      $("#otregisterprint").click(function(event){
      event.preventDefault(); 
	  alert ("aaaa");
	  var opt=3;
             event.preventDefault();     
                var att_payschm =  $('#att_payschm').val();
                var holget =  $('#hol_get').val();
                periodfromdate = $('#advpfromdt').val();
                periodtodate = $('#advptodt').val();
                payschemeName = $('#payschemename').val();
//                exportdbfdata
            var url = '<?php echo site_url("Data_entry/otregisterprint"); ?>' +
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

$("#otpayslipprint").click(function(event){
      event.preventDefault(); 
	  alert ("aaaa");
	  var opt=3;
             event.preventDefault();     
                var att_payschm =  $('#att_payschm').val();
                var holget =  $('#hol_get').val();
                periodfromdate = $('#advpfromdt').val();
                periodtodate = $('#advptodt').val();
                payschemeName = $('#payschemename').val();
//                exportdbfdata

//var url = '<?php echo site_url("Data_entry/exceldownload"); ?>' +
            var url = '<?php echo site_url("Data_entry/generatePdf"); ?>' +
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
        
        
        
        
                $('.select2-multi').select2({
                multiple: true,
                placeholder: 'Select options',
                width: '100%',
            });

       //     });
        
    </script>
      