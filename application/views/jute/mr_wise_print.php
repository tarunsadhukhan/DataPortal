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

</style>
<div style="padding:10px;text-align:center;"><h2><?=$report_title?></h2></div>
<table cellpadding=0 cellspacing=0 width="100%">
    <thead>
        <tr>
        <?php
                    if($columns){
                        for($i=1; $i<count($columns); $i++){
                            if($i==0){
                                echo '<th class="thfirst">'.$columns[$i].'</th>';
                            }else if(count($columns)-1 == $i){
                                echo '<th class="thlast">'.$columns[$i].'</th>';
                            }else{
                                echo '<th>'.$columns[$i].'</th>';
                            }                            
                        }
                    }
                ?>
        </tr>
    </thead>
    <tbody>
        <?php

            if($res){
                foreach($res as $row){

                    echo '<tr>';
                    echo '<td class="thtop thleft">'.$row->jute_received_dt.'</td>';
                    echo '<td class="thtop thleft">'.$row->mr_print_no.'</td>';
                    echo '<td class="thtop thleft">'.$row->actual_quality.'</td>';
                    echo '<td class="thtop thleft">'.$row->jute_quality.'</td>';
                    echo '<td class="thtop thleft">'.$row->jute_receive_no.'</td>';                    
                    echo '<td class="thtop thleft">'.$row->gdname.'</td>';
                    echo '<td class="thtop thleft">'.$row->jute_line_item_no.'</td>';
                    echo '<td class="thtop thleft">'.$row->unit.'</td>';
                    echo '<td class="thtop thleft">'.$row->noofbales.'</td>';
                    echo '<td class="thtop thleft">'.$row->actual_weight.'</td>';
                    echo '<td class="thtop thleft">'.$row->issue_date.'</td>';
                    echo '<td class="thtop thleft">'.$row->issue_quality.'</td>';
                    echo '<td class="thtop thleft">'.$row->quantity.'</td>';
                    echo '<td class="thtop thleft">'.$row->total_weight.'</td>';
                    echo '<td class="thtop thleft">'.$row->qty.'</td>';
                    echo '<td class="thtop thleft">'.$row->twt.'</td>';
                    echo '<td class="thtop thleft">'.$row->bal_qty.'</td>';
                    echo '<td class="thtop thleft">'.$row->bal_weight.'</td>';
                    echo '</tr>';
                   
                    
                }
            }

            
        ?>        
    </tbody>
    <tfoot>
        <tr>
            <th colspan="9" class="thtop thleft thright thbottom">&nbsp;</th>
        </tr>
    </tfoot>
</table>
<script>
window.print(); 
</script>
