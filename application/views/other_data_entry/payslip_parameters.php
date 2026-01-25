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

<!-- <link rel="stylesheet" type="text/css" href="<?= base_url('public/assets/css/editor.dataTables.css') ?>">
<script type="text/javascript" src="<?= base_url('public/assets/js/dataTables.editor.js') ?>"></script>

<link rel="stylesheet" type="text/css" href="<?= base_url('public/assets/css/editor.dataTables.min.css') ?>">
<script type="text/javascript" src="<?= base_url('public/assets/js/dataTables.editor.min.js') ?>"></script>
 -->

<!--
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/dataTables.editor.css">

 DataTables Editor JS 
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.5/js/dataTables.editor.js"></script>
 -->


</head>
<body>
  
<div class="reporthead"><?='ESI Accident Report'?></div>

    <div class="form-group">


        <div class="col-12 col-sm-2">
            <label for="account-name">Period From Date</label>
            <input class="form-control form-control-rounded" value="<?= date('Y-m-d') ?>" id="advpfromdt" name="advpfromdt" type="date">
        </div>
        <div class="col-12 col-sm-2">
            <label for "email">Employee Code</label>
            <input class="form-control form-control-rounded select-on-focus" id="ebno"   name="ebno" type="text">
        </div>

 
   
          <div class="col-12 col-sm-2">
    	    <label for="purchaseDetailsPurchaseDate">Print Report <span class="text-center"></span></label>
                <button name="submit" id="payschemeupdate"  type="submit" class="form-control btn btn-primary">Print</button>
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
       
            </div> 
     
        <hr style="height:4px; background-color:#0f4d92  ;"></hr>
     
  
 
    </body>
</html>


<script>
         $(document).ready(function () {
            const heading = document.querySelector('h4');


     
         
$('.select-on-focus').on('focus', function() {
    this.select();
});

 
var editor; 
//initDataTable1();
             
 

 
    
   //     refreshDataTable();
      });

             // Enable inline editing for the "Payslip Print Order" column (column 9)


$("#payschemeupdate").click(function(event){
      event.preventDefault(); 
	  var opt=3;
             event.preventDefault();     
                periodfromdate = $('#advpfromdt').val();
                ebno = $('#ebno').val();
         //       alert(ebno);
                
//                exportdbfdata
//02-03-2024 anotherFunction

var url = '<?php echo site_url("Ejmprocessdata/esiacreport"); ?>' +
          '?periodfromdate=' + periodfromdate +
          '&ebno=' + ebno;
           //           alert(url);
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
      