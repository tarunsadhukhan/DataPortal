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


 //alert(itemdesc);


table = $('#example').DataTable({
    "sDom": 'r<"H"lf><"datatable-scroll"t><"F"ip>',
    "processing": true,
    "serverSide": true,
    "searching": false,
    "lengthChange":false,
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

});

</script>


