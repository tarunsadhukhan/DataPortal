<?php
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
?>

<div class="reporthead"><?=$report_title?></div>
<table class="table table-class table-striped reportTable <?=$tableBorders?>" id="example" width="100%">
    <thead>
        <tr>
            <?php
     //   var_dump($columns);
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
var payscheme_id = '<?=$payscheme_id?>';
var componet_id = '<?=$componet_id?>';
var itcod = '<?=$itcod?>';
var srno = '<?=$srno?>';
var att_cat_att = '<?=$att_cat_att?>';




 //alert(att_dept);


table = $('#example').DataTable({
 //   "sDom": 'r<"H"lf><"datatable-scroll"t><"F"ip>',
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
            data.payscheme_id = payscheme_id;
            data.att_cat_att = att_cat_att;
            data.srno = srno;
            data.itcod = itcod;
    

        }
    },
    "columnDefs": [
    {
        "targets": [ 0 ],
        "orderable": false,

    },
    ],    scrollX: true,

    "fnRowCallback": function(nRow, aData, iDisplayIndex, iDisplayIndexFull) {

if ( aData[2] == "900001")    {
//   $('td', nRow).css('background-color', 'Green');
} 
if ( aData[0] == 99999991)    {
//   $('td', nRow).css('background-color', 'Red');
}
//   else if (aData[0] == "7") {
//     $('td', nRow).css('background-color', 'Orange');
//  }
},


});
$('#btn-filter').click(function(){
    table.ajax.reload();
});
$('#btn-reset').click(function(){
    $('#form-filter')[0].reset();
    table.ajax.reload();
});

});

</script>


