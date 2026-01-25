<style>
.dataTables_wrapper table.dataTable td {
    border: 1px solid #0047AB;
}

.dataTables_wrapper table.dataTable {
    font-size: 12px; /* Adjust the font size as needed */
}

.dataTables_wrapper table.dataTable thead th {
    font-size: 14px; /* Adjust the font size for table headers */
}


</style>

<div class="reporthead"><?=$report_title?></div>
<table class="table table-class table-striped reportTable <?=$tableBorders?>" id="example" width="100%">
    <thead>
        <tr>
            <?php
          
                if($columns){
                    $array_keys = array_keys($columns);
                
                    if($array_keys){
                        $i=0;
                        foreach($columns as $column){
                            if($tableBorders){
                                if($i==0){
                                    echo '<th class="thfirst">'.$column.'</th>';
                                }else if(count($columns)-1 == $i){
                                    echo '<th>'.$column.'</th>';
                                }else{
                                    if($i<2){
                                        echo '<th>'.$column.'</th>';
                                    }else{
                                        echo '<th>'.$column.'</th>';
                                    }
                                    
                                }     
                            }else{
                                if($i==0){
                                    echo '<th class="thfirst">'.$column.'</th>';
                                }else if(count($columns)-1 == $i){
                                    echo '<th class="thlast">'.$column.'</th>';
                                }else{
                                    echo '<th>'.$column.'</th>';
                                }     
                            }
                        $i++;
                        }
                    }else{
                        for($i=0; $i<count($columns); $i++){
                            if($i==0){
                                echo '<th class="thfirst">'.$columns[$i].'</th>';
                            }else if(count($columns)-1 == $i){
                                echo '<th class="thlast">'.$columns[$i].'</th>';
                            }else{
                                echo '<th>'.$columns[$i].'</th>';
                            }                            
                        }
                    }
                    
                }
            ?>
        </tr>
    </thead>
    <tbody></tbody>
</table>

    
<script>

$(document).ready(function() {

var mainmenuId = '<?=$mainmenuId?>';
var submenuId = '<?=$submenuId?>';
var companyId = '<?=$companyId?>';
var from_date = '<?=$from_date?>';
var to_date = '<?=$to_date?>';
var controller = '<?=$controller?>';
var functions = '<?=$function?>';
var itcod = '<?=$itcod?>';
var itemdesc = '<?=$itemdesc?>';
var srno = '<?=$srno?>';
var Source = '<?=$Source?>';
var eb_no = '<?=$eb_no?>';


//alert(Source);
//alert (eb_no);
var dataTable = $('#yourDataTableId').DataTable(); 
$('#yourSearchBoxId').on('keyup', function() {
        var searchValue = $(this).val(); // This will give you the value typed in the search box
        console.log("Search value:", searchValue); // You can log it to see if it's getting the right value
        // Now you can use the 'searchValue' variable in your code as needed
    alert(searchValue);
    });
/*
table = $('#example').DataTable({
 //   "sDom": 'r<"H"lf><"datatable-scroll"t><"F"ip>',
    "processing": true,
    "serverSide": true,
    "searching": false,
//    "lengthChange":false,
    "order": [],
    "ajax": {
        // "url": "<?php echo base_url() ?>"+controller+'/'+functions+'/'+mainmenuId+'/'+submenuId+'/'+companyId+'/'+from_date+'/'+to_date,
        "url": "<?php echo base_url() ?>"+controller+'/'+functions+'/',
        "type": "POST",            
        "data": function ( data ) {
            data.mainmenuId = mainmenuId;
            data.submenuId = submenuId;
            data.companyId = companyId;
            data.from_date = from_date;
            data.to_date = to_date;
            data.itcod = itcod;
            data.itemdesc = itemdesc;
            
        }
    },
    "columnDefs": [
    {
        "targets": [ 0 ],
        "orderable": false,
    },
    ],

});
$('#btn-filter').click(function(){
    table.ajax.reload();
});
$('#btn-reset').click(function(){
    $('#form-filter')[0].reset();
    table.ajax.reload();
});
*/

switch (submenuId) {
    case '675':
//185
table = $('#example').DataTable({
 //   "sDom": 'r<"H"lf><"datatable-scroll"t><"F"ip>',
    "processing": true,
    "serverSide": true,
    "searching": false,
  //  "bFilter": false,
    "order": [],
    "ajax": {
        // "url": "<?php echo base_url() ?>"+controller+'/'+functions+'/'+mainmenuId+'/'+submenuId+'/'+companyId+'/'+from_date+'/'+to_date,
        "url": "<?php echo base_url() ?>"+controller+'/'+functions+'/',
        "type": "POST",            
        "data": function ( data ) {
            data.mainmenuId = mainmenuId;
            data.submenuId = submenuId;
            data.companyId = companyId;
            data.from_date = from_date;
            data.to_date = to_date;
            data.itcod = itcod;
            data.itemdesc = itemdesc;
            data.srno = srno;
       

        }
    },
    "columnDefs": [
    {
        "targets": [ 0 ],
        "orderable": false,
    },
    ],
    "fnRowCallback": function(nRow, aData, iDisplayIndex, iDisplayIndexFull) {
      //  alert(aData[1]);
//     alert(aData[1].substr(0, 4));
if ( aData[1] == "Line")    {
   $('td', nRow).css('background-color', 'Green');
} 

if (aData[1].substr(0, 4)==" Lin") {
//    alert(aData[1].substr(0, 4));
    $('td', nRow).css('background-color', '#D6EAF8');

}


if ( aData[0] == 99999991)    {
   $('td', nRow).css('background-color', 'Green');
}
},
});
$('#btn-filter').click(function(){
    table.ajax.reload();
});
$('#btn-reset').click(function(){
    $('#form-filter')[0].reset();
    table.ajax.reload();
});
        break;

        case '679':
//185
table = $('#example').DataTable({
 //   "sDom": 'r<"H"lf><"datatable-scroll"t><"F"ip>',
    "processing": true,
    "serverSide": true,
    "searching": false,
  //  "bFilter": false,
    "order": [],
    "ajax": {
        // "url": "<?php echo base_url() ?>"+controller+'/'+functions+'/'+mainmenuId+'/'+submenuId+'/'+companyId+'/'+from_date+'/'+to_date,
        "url": "<?php echo base_url() ?>"+controller+'/'+functions+'/',
        "type": "POST",            
        "data": function ( data ) {
            data.mainmenuId = mainmenuId;
            data.submenuId = submenuId;
            data.companyId = companyId;
            data.from_date = from_date;
            data.to_date = to_date;
            data.itcod = itcod;
            data.itemdesc = itemdesc;
            data.srno = srno;
            data.Source = Source;
            data.eb_no = eb_no;
       

        }
    },
    "columnDefs": [
    {
        "targets": [ 0 ],
        "orderable": false,
    },
    ],
    "fnRowCallback": function(nRow, aData, iDisplayIndex, iDisplayIndexFull) {
//     alert(aData[1].substr(0, 4));
if ( aData[1] == "Line")    {
   $('td', nRow).css('background-color', 'Green');
} 

if (aData[1].substr(0, 4)==" Lin") {
//    alert(aData[1].substr(0, 4));
    $('td', nRow).css('background-color', '#D6EAF8');

}


if ( aData[0] == 99999991)    {
   $('td', nRow).css('background-color', 'Green');
}
},"scrollX": true, // Enable horizontal scrolling
    "scrollY": 400, // Set a fixed height for vertical scrolling

});
$('#btn-filter').click(function(){
    table.ajax.reload();
});
$('#btn-reset').click(function(){
    $('#form-filter')[0].reset();
    table.ajax.reload();
});
        break;
        case '680':
//185
table = $('#example').DataTable({
 //   "sDom": 'r<"H"lf><"datatable-scroll"t><"F"ip>',
    "processing": true,
    "serverSide": true,
    "searching": false,
  //  "bFilter": false,
    "order": [],
    "ajax": {
        // "url": "<?php echo base_url() ?>"+controller+'/'+functions+'/'+mainmenuId+'/'+submenuId+'/'+companyId+'/'+from_date+'/'+to_date,
        "url": "<?php echo base_url() ?>"+controller+'/'+functions+'/',
        "type": "POST",            
        "data": function ( data ) {
            data.mainmenuId = mainmenuId;
            data.submenuId = submenuId;
            data.companyId = companyId;
            data.from_date = from_date;
            data.to_date = to_date;
            data.itcod = itcod;
            data.itemdesc = itemdesc;
            data.srno = srno;
            data.Source = Source;
            data.eb_no = eb_no;
       

        }
    },
    "columnDefs": [
    {
        "targets": [ 0 ],
        "orderable": false,
    },
    ],
    "fnRowCallback": function(nRow, aData, iDisplayIndex, iDisplayIndexFull) {
//     alert(aData[1].substr(0, 4));
if ( aData[1] == "Line")    {
   $('td', nRow).css('background-color', 'Green');
} 

if (aData[1].substr(0, 4)==" Lin") {
//    alert(aData[1].substr(0, 4));
    $('td', nRow).css('background-color', '#D6EAF8');

}


if ( aData[0] == 99999991)    {
   $('td', nRow).css('background-color', 'Green');
}
},"scrollX": true, // Enable horizontal scrolling
    "scrollY": 400, // Set a fixed height for vertical scrolling

});
$('#btn-filter').click(function(){
    table.ajax.reload();
});
$('#btn-reset').click(function(){
    $('#form-filter')[0].reset();
    table.ajax.reload();
});
        break;
        case '681':
//185
table = $('#example').DataTable({
 //   "sDom": 'r<"H"lf><"datatable-scroll"t><"F"ip>',
    "processing": true,
    "serverSide": true,
    "searching": false,
  //  "bFilter": false,
    "order": [],
    "ajax": {
        // "url": "<?php echo base_url() ?>"+controller+'/'+functions+'/'+mainmenuId+'/'+submenuId+'/'+companyId+'/'+from_date+'/'+to_date,
        "url": "<?php echo base_url() ?>"+controller+'/'+functions+'/',
        "type": "POST",            
        "data": function ( data ) {
            data.mainmenuId = mainmenuId;
            data.submenuId = submenuId;
            data.companyId = companyId;
            data.from_date = from_date;
            data.to_date = to_date;
            data.itcod = itcod;
            data.itemdesc = itemdesc;
            data.srno = srno;
            data.Source = Source;
            data.eb_no = eb_no;
       

        }
    },
    "columnDefs": [
    {
        "targets": [ 0 ],
        "orderable": false,
    },
    ],
    "fnRowCallback": function(nRow, aData, iDisplayIndex, iDisplayIndexFull) {
//     alert(aData[1].substr(0, 4));
if ( aData[1] == "Line")    {
   $('td', nRow).css('background-color', 'Green');
} 

if (aData[1].substr(0, 4)==" Lin") {
//    alert(aData[1].substr(0, 4));
    $('td', nRow).css('background-color', '#D6EAF8');

}


if ( aData[0] == 99999991)    {
   $('td', nRow).css('background-color', 'Green');
}
},"scrollX": true, // Enable horizontal scrolling
    "scrollY": 400, // Set a fixed height for vertical scrolling

});
$('#btn-filter').click(function(){
    table.ajax.reload();
});
$('#btn-reset').click(function(){
    $('#form-filter')[0].reset();
    table.ajax.reload();
});
        break;

        case '674':
//185
table = $('#example').DataTable({
  //  "sDom": 'r<"H"lf><"datatable-scroll"t><"F"ip>',
    "processing": true,
    "serverSide": true,
    "searching": false,
  //  "bFilter": false,
    "order": [],
    "ajax": {
        // "url": "<?php echo base_url() ?>"+controller+'/'+functions+'/'+mainmenuId+'/'+submenuId+'/'+companyId+'/'+from_date+'/'+to_date,
        "url": "<?php echo base_url() ?>"+controller+'/'+functions+'/',
        "type": "POST",            
        "data": function ( data ) {
            data.mainmenuId = mainmenuId;
            data.submenuId = submenuId;
            data.companyId = companyId;
            data.from_date = from_date;
            data.to_date = to_date;
            data.itcod = itcod;
            data.itemdesc = itemdesc;
            data.srno = srno;
       

        }
    },
    "columnDefs": [
    {
        "targets": [ 0 ],
        "orderable": false,
    },
    ],
    "fnRowCallback": function(nRow, aData, iDisplayIndex, iDisplayIndexFull) {
      //  alert(aData[1]);
//     alert(aData[1].substr(0, 4));
if ( aData[1] == "Line")    {
   $('td', nRow).css('background-color', 'Green');
} 

if (aData[1].substr(0, 4)==" Lin") {
//    alert(aData[1].substr(0, 4));
    $('td', nRow).css('background-color', '#D6EAF8');

}


if ( aData[0] == 99999991)    {
   $('td', nRow).css('background-color', 'Green');
}
},
"scrollX": true, // Enable horizontal scrolling
    "scrollY": 400, // Set a fixed height for vertical scrolling
});
$('#btn-filter').click(function(){
    table.ajax.reload();
});
$('#btn-reset').click(function(){
    $('#form-filter')[0].reset();
    table.ajax.reload();
});
        break;
        case '676':
//185
table = $('#example').DataTable({
  //  "sDom": 'r<"H"lf><"datatable-scroll"t><"F"ip>',
    "processing": true,
    "serverSide": true,
    "searching": false,
  //  "bFilter": false,
    "order": [],
    "ajax": {
        // "url": "<?php echo base_url() ?>"+controller+'/'+functions+'/'+mainmenuId+'/'+submenuId+'/'+companyId+'/'+from_date+'/'+to_date,
        "url": "<?php echo base_url() ?>"+controller+'/'+functions+'/',
        "type": "POST",            
        "data": function ( data ) {
            data.mainmenuId = mainmenuId;
            data.submenuId = submenuId;
            data.companyId = companyId;
            data.from_date = from_date;
            data.to_date = to_date;
            data.itcod = itcod;
            data.itemdesc = itemdesc;
            data.srno = srno;
            data.Source = Source;


        }
    },
    "columnDefs": [
    {
        "targets": [ 0 ],
        "orderable": false,
    },
    ],
    "fnRowCallback": function(nRow, aData, iDisplayIndex, iDisplayIndexFull) {
      //  alert(aData[1]);
//     alert(aData[1].substr(0, 4));
if ( aData[1] == "Line")    {
   $('td', nRow).css('background-color', 'Green');
} 




if ( aData[0] == 99999991)    {
   $('td', nRow).css('background-color', 'Green');
}
},
"scrollX": true, // Enable horizontal scrolling
    "scrollY": 400, // Set a fixed height for vertical scrolling
});
$('#btn-filter').click(function(){
    table.ajax.reload();
});
$('#btn-reset').click(function(){
    $('#form-filter')[0].reset();
    table.ajax.reload();
});
        break;
        case '586':
//185
table = $('#example').DataTable({
  //  "sDom": 'r<"H"lf><"datatable-scroll"t><"F"ip>',
    "processing": true,
    "serverSide": true,
    "searching": false,
  //  "bFilter": false,
    "order": [],
    "ajax": {
        // "url": "<?php echo base_url() ?>"+controller+'/'+functions+'/'+mainmenuId+'/'+submenuId+'/'+companyId+'/'+from_date+'/'+to_date,
        "url": "<?php echo base_url() ?>"+controller+'/'+functions+'/',
        "type": "POST",            
        "data": function ( data ) {
            data.mainmenuId = mainmenuId;
            data.submenuId = submenuId;
            data.companyId = companyId;
            data.from_date = from_date;
            data.to_date = to_date;
            data.itcod = itcod;
            data.itemdesc = itemdesc;
            data.srno = srno;
            data.Source = Source;


        }
    },
    "columnDefs": [
    {
        "targets": [ 0 ],
        "orderable": false,
    },
    ],
    "fnRowCallback": function(nRow, aData, iDisplayIndex, iDisplayIndexFull) {
      //  alert(aData[1]);
//     alert(aData[1].substr(0, 4));
if ( aData[1] == "Line")    {
   $('td', nRow).css('background-color', 'Green');
} 




if ( aData[0] == 99999991)    {
   $('td', nRow).css('background-color', 'Green');
}
},
"scrollX": true, // Enable horizontal scrolling
    "scrollY": 400, // Set a fixed height for vertical scrolling
});
$('#btn-filter').click(function(){
    table.ajax.reload();
});
$('#btn-reset').click(function(){
    $('#form-filter')[0].reset();
    table.ajax.reload();
});
        break;
        case '677':
//185

table = $('#example').DataTable({
  //  "sDom": 'r<"H"lf><"datatable-scroll"t><"F"ip>',
    "processing": true,
    "serverSide": true,
    "searching": false,
    "paging": false,
  //  "bFilter": false,
    "order": [],
    "ajax": {
        // "url": "<?php echo base_url() ?>"+controller+'/'+functions+'/'+mainmenuId+'/'+submenuId+'/'+companyId+'/'+from_date+'/'+to_date,
        "url": "<?php echo base_url() ?>"+controller+'/'+functions+'/',
        "type": "POST",            
        "data": function ( data ) {
            data.mainmenuId = mainmenuId;
            data.submenuId = submenuId;
            data.companyId = companyId;
            data.from_date = from_date;
            data.to_date = to_date;
            data.itcod = itcod;
            data.itemdesc = itemdesc;
            data.srno = srno;
       

        }
    },
    "columnDefs": [
    {
        "targets": [ 0 ],
        "orderable": false,
    },
    ],
    "fnRowCallback": function(nRow, aData, iDisplayIndex, iDisplayIndexFull) {
if ( aData[1] == "Line")    {
   $('td', nRow).css('background-color', 'Green');
} 

for (var i = 2; i < aData.length - 4; i++) {
        // Example: Apply green color if value is "Green"
        if ( (aData[i] >0) &  (aData[i] <40) ) {
            $('td:eq('+i+')', nRow).css('background-color', '#FF0000');
        }
        if ( (aData[i] >=60)  ) {
            $('td:eq('+i+')', nRow).css('background-color', '#7FFF00');
        }
    }



var n=aData.length - 4;
if ( aData[n] >= 60)    {
    $('td:eq('+n+')', nRow).css('background-color', '#7FFF00');
    var n=aData.length - 3;
    $('td:eq('+n+')', nRow).css('background-color', '#7FFF00');
    var n=aData.length - 2;
    $('td:eq('+n+')', nRow).css('background-color', '#7FFF00');
    var n=aData.length - 1;
    $('td:eq('+n+')', nRow).css('background-color', '#7FFF00');



//   $('td', nRow).css('background-color', '#7FFF00');
}
},



"scrollX": true, // Enable horizontal scrolling
    "scrollY": 400, // Set a fixed height for vertical scrolling
});
$('#btn-filter').click(function(){
    table.ajax.reload();
});
$('#btn-reset').click(function(){
    $('#form-filter')[0].reset();
    table.ajax.reload();
});


        break;

        case '695':
//185
table = $('#example').DataTable({
  //  "sDom": 'r<"H"lf><"datatable-scroll"t><"F"ip>',
    "processing": true,
    "serverSide": true,
    "searching": false,
  //  "bFilter": false,
    "order": [],
    "ajax": {
        // "url": "<?php echo base_url() ?>"+controller+'/'+functions+'/'+mainmenuId+'/'+submenuId+'/'+companyId+'/'+from_date+'/'+to_date,
        "url": "<?php echo base_url() ?>"+controller+'/'+functions+'/',
        "type": "POST",            
        "data": function ( data ) {
            data.mainmenuId = mainmenuId;
            data.submenuId = submenuId;
            data.companyId = companyId;
            data.from_date = from_date;
            data.to_date = to_date;
            data.itcod = itcod;
            data.itemdesc = itemdesc;
            data.srno = srno;
            data.Source = Source;


        }
    },
    "columnDefs": [
    {
        "targets": [ 0 ],
        "orderable": false,
    },
    ],
    "fnRowCallback": function(nRow, aData, iDisplayIndex, iDisplayIndexFull) {
      //  alert(aData[1]);
//     alert(aData[1].substr(0, 4));
if ( aData[1] == "Line")    {
   $('td', nRow).css('background-color', 'Green');
} 




if ( aData[0] == 99999991)    {
   $('td', nRow).css('background-color', 'Green');
}
},
"scrollX": true, // Enable horizontal scrolling
    "scrollY": 400, // Set a fixed height for vertical scrolling
});
$('#btn-filter').click(function(){
    table.ajax.reload();
});
$('#btn-reset').click(function(){
    $('#form-filter')[0].reset();
    table.ajax.reload();
});
        break;
        case '677':
//185

table = $('#example').DataTable({
  //  "sDom": 'r<"H"lf><"datatable-scroll"t><"F"ip>',
    "processing": true,
    "serverSide": true,
    "searching": false,
    "paging": false,
  //  "bFilter": false,
    "order": [],
    "ajax": {
        // "url": "<?php echo base_url() ?>"+controller+'/'+functions+'/'+mainmenuId+'/'+submenuId+'/'+companyId+'/'+from_date+'/'+to_date,
        "url": "<?php echo base_url() ?>"+controller+'/'+functions+'/',
        "type": "POST",            
        "data": function ( data ) {
            data.mainmenuId = mainmenuId;
            data.submenuId = submenuId;
            data.companyId = companyId;
            data.from_date = from_date;
            data.to_date = to_date;
            data.itcod = itcod;
            data.itemdesc = itemdesc;
            data.srno = srno;
       

        }
    },
    "columnDefs": [
    {
        "targets": [ 0 ],
        "orderable": false,
    },
    ],
    "fnRowCallback": function(nRow, aData, iDisplayIndex, iDisplayIndexFull) {
if ( aData[1] == "Line")    {
   $('td', nRow).css('background-color', 'Green');
} 

for (var i = 2; i < aData.length - 4; i++) {
        // Example: Apply green color if value is "Green"
        if ( (aData[i] >0) &  (aData[i] <40) ) {
            $('td:eq('+i+')', nRow).css('background-color', '#FF0000');
        }
        if ( (aData[i] >=60)  ) {
            $('td:eq('+i+')', nRow).css('background-color', '#7FFF00');
        }
    }



var n=aData.length - 4;
if ( aData[n] >= 60)    {
    $('td:eq('+n+')', nRow).css('background-color', '#7FFF00');
    var n=aData.length - 3;
    $('td:eq('+n+')', nRow).css('background-color', '#7FFF00');
    var n=aData.length - 2;
    $('td:eq('+n+')', nRow).css('background-color', '#7FFF00');
    var n=aData.length - 1;
    $('td:eq('+n+')', nRow).css('background-color', '#7FFF00');



//   $('td', nRow).css('background-color', '#7FFF00');
}
},



"scrollX": true, // Enable horizontal scrolling
    "scrollY": 400, // Set a fixed height for vertical scrolling
});
$('#btn-filter').click(function(){
    table.ajax.reload();
});
$('#btn-reset').click(function(){
    $('#form-filter')[0].reset();
    table.ajax.reload();
});


        break;




        default:

 
    table = $('#example').DataTable({
//    "sDom": 'r<"H"lf><"datatable-scroll"t><"F"ip>',
    "processing": true,
    "serverSide": true,
  //  "bFilter": false,
    "order": [],
    "ajax": {
        // "url": "<?php echo base_url() ?>"+controller+'/'+functions+'/'+mainmenuId+'/'+submenuId+'/'+companyId+'/'+from_date+'/'+to_date,
        "url": "<?php echo base_url() ?>"+controller+'/'+functions+'/',
        "type": "POST",            
        "data": function ( data ) {
            data.mainmenuId = mainmenuId;
            data.submenuId = submenuId;
            data.companyId = companyId;
            data.from_date = from_date;
            data.to_date = to_date;
            data.itcod = itcod;
            data.itemdesc = itemdesc;


        }
    },
    "columnDefs": [
    {
        "targets": [ 0 ],
        "orderable": false,
    },
    ],
    "fnRowCallback": function(nRow, aData, iDisplayIndex, iDisplayIndexFull) {

if ( aData[2] == "900001")    {
   $('td', nRow).css('background-color', 'Green');
} 
if ( aData[0] == 9999999991)    {
   $('td', nRow).css('background-color', 'Red');
}
},
});
$('#btn-filter').click(function(){
    table.ajax.reload();
});
$('#btn-reset').click(function(){
    $('#form-filter')[0].reset();
    table.ajax.reload();
});
        break;
}




});

</script>


