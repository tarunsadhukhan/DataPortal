<<?php 
 ini_set('memory_limit', '256M'); // Set memory limit to 256 megabytes

 ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Loan and Advance Form</title>
    <style>
        table {
            display: none;
        }
        .form-group {
            display: block;
            clear: both;
            margin-bottom: 70px;
        }
        #holidayrecordTable thead th {
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



</style>




<script src="<?php echo base_url()?>public/admin/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.min.css">
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
</head>
<body>
  
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
                    

                    echo form_dropdown('hol_get', $holget, ($hol_get ? $hol_get : "0"), 'id="hol_get"  class="myselect form-control form-control-rounded" data-placeholder="Select Holiday "  style="width:100%;"');
                ?>
              
              </div>


    </div>

    <div class="form-group">

     

    <div class="col-12 col-sm-2">
    	    <label for="purchaseDetailsPurchaseDate">Process Data For Holiady<span class="text-center"></span></label>
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
            <h4 align="center" style="font-family:Droid Serif"><?php echo $company_name; ?></h4>
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
    }

    // Show the first table on page load

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



/*
        function refreshDataTable() {
            var holget =  $('#hol_get').val();

            if (holget==1) {
                initDataTable1();
            }    
            if (holget==2) {
                initDataTable1();
            }    
            if (holget==3) {
                initDataTable3();
            }    
 
            // 
   //         table.ajax.reload(null, false); // Reload the data without resetting the current page
        
        
        }
*/
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
        if (holget==4) {
             document.getElementById("holidayrecordTable").style.display = "none";
            document.getElementById("forthnightrecordTable").style.display = "none";
            document.getElementById("monthlyrecordTable").style.display = "table";
         //   alert('3st');

            initDataTable3();
        }

            });


 
        $("#hlincprocessdata").click(function(event){
          
            event.preventDefault(); 
            var periodfromdate= $('#advpfromdt').val();
            var periodtodate= $('#advptodt').val();
            var att_payschm =  $('#att_payschm').val();
            alert(periodfromdate);
       
          $.ajax({
            url: "<?php echo base_url('Data_entry/holidayprocessdata'); ?>",
            type: "POST",
            data: {periodfromdate : periodfromdate,periodtodate : periodtodate,att_payschm: att_payschm},
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
      
        
        
        
                $('.select2-multi').select2({
                multiple: true,
                placeholder: 'Select options',
                width: '100%',
            });

            });
        
    </script>
      