<style>
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
.numeric {
    text-align: right;
}

.string {
    text-align: left;
}
td {
  text-align: left;
}

td.center {
  text-align: center;
}

td.right {
  text-align: right;
}
.column-align-right {
    text-align: right;
}
</style>


<?php          $company_name = $this->session->userdata('companyname');
          
    ?>
<div style="padding:10px;text-align:center;"><h2><?=$company_name?></h2></div>

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
var Source = '<?=$Source?>';
var att_type = '<?=$att_type?>';
var att_status = '<?=$att_status?>';
var att_dept = '<?=$att_dept?>';
var att_desig = '<?=$att_desig?>';
var att_spells = '<?=$att_spells?>';
var eb_no = '<?=$eb_no?>';
var att_mark_hrs_att = '<?=$att_mark_hrs_att?>';
var branch_id = '<?=$branch_id?>';
var componet_id = '<?=$componet_id?>';
var itcod = '<?=$itcod?>';
var costcenter = '<?=$costcenter?>';
var itemdesc = '<?=$itemdesc?>';
const expr = submenuId;

//alert(submenuId); 
 
// alert(att_mark_hrs_att);
switch (submenuId) {
    case '185':
//185
table = $('#example').DataTable({
    "sDom": 'r<"H"lf><"datatable-scroll"t><"F"ip>',
    "processing": true,
    "serverSide": true,
    "bFilter": false,
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
            data.Source = Source;
            data.att_type = att_type;
            data.att_status = att_status;
            data.att_dept = att_dept;
            data.att_desig = att_desig;
            data.att_spells = att_spells;
            data.eb_no = eb_no;
            data.att_mark_hrs_att = att_mark_hrs_att;
            data.branch_id = branch_id;
            data.componet_id = componet_id;
            data.itcod = itcod;
            data.costcenter = costcenter;
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
        case '499':
//499
table = $('#example').DataTable({
    "sDom": 'r<"H"lf><"datatable-scroll"t><"F"ip>',
    "processing": true,
    "serverSide": true,
    "bFilter": false,
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
            data.Source = Source;
            data.att_type = att_type;
            data.att_status = att_status;
            data.att_dept = att_dept;
            data.att_desig = att_desig;
            data.att_spells = att_spells;
            data.eb_no = eb_no;
            data.att_mark_hrs_att = att_mark_hrs_att;
            data.branch_id = branch_id;
            data.componet_id = componet_id;
            data.itcod = itcod;
            data.costcenter = costcenter;
            data.itemdesc = itemdesc;


        }
    },
    "columnDefs": [
    {
        "targets": [ 0 ],
        "orderable": false,
        targets: [5, 6,7,8,9,10,11,12],
                    render: function(data, type, row, meta) {
                        return '<div class="column-align-right">' + data + '</div>';
                    }
                  
 
    },
    ],
 //   "scrollY": "400px", // Set the vertical scroll height
 //   "scrollCollapse": true, // Allow the table to collapse when the content is smaller than the container
    fixedColumns: {
                    leftColumns: 1,
                    rightColumns: 1
                },processing: true,

  
    "fnRowCallback": function(nRow, aData, iDisplayIndex, iDisplayIndexFull) {

if ( aData[11] <0)    {
   $('td', nRow).css('background-color', '#F5B7B1');
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
        case '415':
//415
table = $('#example').DataTable({
    "sDom": 'r<"H"lf><"datatable-scroll"t><"F"ip>',
    "processing": true,
    "serverSide": true,
    "bFilter": false,
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
            data.Source = Source;
            data.att_type = att_type;
            data.att_status = att_status;
            data.att_dept = att_dept;
            data.att_desig = att_desig;
            data.att_spells = att_spells;
            data.eb_no = eb_no;
            data.att_mark_hrs_att = att_mark_hrs_att;
            data.branch_id = branch_id;
            data.componet_id = componet_id;
            data.itcod = itcod;
            data.costcenter = costcenter;
            data.itemdesc = itemdesc;


        }
    },
    "columnDefs": [
    {
        "targets": [ 0 ],
        "orderable": false,
        targets: [5, 6,7,8,9,10,11,12],
                    render: function(data, type, row, meta) {
                        return '<div class="column-align-right">' + data + '</div>';
                    }
                  
 
    },
    ],
 //   "scrollY": "400px", // Set the vertical scroll height
 //   "scrollCollapse": true, // Allow the table to collapse when the content is smaller than the container
    fixedColumns: {
                    leftColumns: 1,
                    rightColumns: 1
                },processing: true,

  
    "fnRowCallback": function(nRow, aData, iDisplayIndex, iDisplayIndexFull) {

if ( aData[13] > 180 && aData[13] <= 365 )    {
    if (aData[9] >0) {
    $('td', nRow).css('background-color', '#FBF6D9');
    }     
} 
if ( aData[13] > 365 )    {
    if (aData[9] >0) {
      $('td', nRow).css('background-color', '#FAEBD7');
    }
} 
if ( aData[9] <0 || aData[10] <0 )    {
        $('td', nRow).css('background-color', '#E42217');
        
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
 
 
        case '230':
//185
table = $('#example').DataTable({
    "sDom": 'r<"H"lf><"datatable-scroll"t><"F"ip>',
    "processing": true,
    "serverSide": true,
    "bFilter": false,
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
            data.Source = Source;
            data.att_type = att_type;
            data.att_status = att_status;
            data.att_dept = att_dept;
            data.att_desig = att_desig;
            data.att_spells = att_spells;
            data.eb_no = eb_no;
            data.att_mark_hrs_att = att_mark_hrs_att;
            data.branch_id = branch_id;
            data.componet_id = componet_id;
            data.itcod = itcod;
            data.costcenter = costcenter;


        }
    },
    "columnDefs": [
    {
        "targets": [ 0 ],
        "orderable": false,
    },
    ],
    "fnRowCallback": function(nRow, aData, iDisplayIndex, iDisplayIndexFull) {

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
 
        case '233':
//185
table = $('#example').DataTable({
    "sDom": 'r<"H"lf><"datatable-scroll"t><"F"ip>',
    "processing": true,
    "serverSide": true,
    "bFilter": false,
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
            data.Source = Source;
            data.att_type = att_type;
            data.att_status = att_status;
            data.att_dept = att_dept;
            data.att_desig = att_desig;
            data.att_spells = att_spells;
            data.eb_no = eb_no;
            data.att_mark_hrs_att = att_mark_hrs_att;
            data.branch_id = branch_id;
            data.componet_id = componet_id;
            data.itcod = itcod;
            data.costcenter = costcenter;


        }
    },
    "columnDefs": [
    {
        "targets": [ 0 ],
        "orderable": false,
    },
    ],
    "fnRowCallback": function(nRow, aData, iDisplayIndex, iDisplayIndexFull) {

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
 
        case '217':
//185
table = $('#example').DataTable({
    "sDom": 'r<"H"lf><"datatable-scroll"t><"F"ip>',
    "processing": true,
    "serverSide": true,
    "bFilter": false,
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
            data.Source = Source;
            data.att_type = att_type;
            data.att_status = att_status;
            data.att_dept = att_dept;
            data.att_desig = att_desig;
            data.att_spells = att_spells;
            data.eb_no = eb_no;
            data.att_mark_hrs_att = att_mark_hrs_att;
            data.branch_id = branch_id;
            data.componet_id = componet_id;
            data.itcod = itcod;
            data.costcenter = costcenter;


        }
    },
    "columnDefs": [
    {
        "targets": [ 0 ],
        "orderable": false,
    },
    ],
    "fnRowCallback": function(nRow, aData, iDisplayIndex, iDisplayIndexFull) {

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
 
        default:

      //  alert('def');
//alert(submenuId);

    table = $('#example').DataTable({
    "sDom": 'r<"H"lf><"datatable-scroll"t><"F"ip>',
    "processing": true,
    "serverSide": true,
    "bFilter": false,
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
            data.Source = Source;
            data.att_type = att_type;
            data.att_status = att_status;
            data.att_dept = att_dept;
            data.att_desig = att_desig;
            data.att_spells = att_spells;
            data.eb_no = eb_no;
            data.att_mark_hrs_att = att_mark_hrs_att;
            data.branch_id = branch_id;
            data.componet_id = componet_id;
            data.itcod = itcod;
            data.costcenter = costcenter;


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


