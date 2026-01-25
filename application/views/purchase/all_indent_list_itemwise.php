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
                        for($i=0; $i<count($columns); $i++){
                            if($i==0){
                                echo '<th class="thfirst">'.$columns[$i].'</th>';
                            }else if(count($columns)-1 == $i){
                                echo '<th class="thlast">'.$columns[$i].'</th>';
                            }else{
                                echo '<th>'.$columns[$i].'</th>';
                            }                            
                        }
                    }else{
                ?>
            <th class="thtop thleft">po_sequence_no</th>
            <th class="thtop thleft">Date</th>
            <th class="thtop thleft">ItemWise</th>
            <th class="thtop thleft">Office</th>
            <th class="thtop thleft">Age</th>
            <th class="thtop thleft">Start date</th>
            <th class="thtop thleft">Salary</th>
            <th class="thtop thleft">Extn.</th>
            <th class="thtop thleft thright">E-mail</th>
            <?php
                    }
            ?>
        </tr>
    </thead>
    <tbody>
        <?php
            if($res){
                $m=1;
                foreach($res as $row){
                    echo '<tr>';                   
                    echo '<td class="thtop thleft">'.$m.'</td>';
                    echo '<td class="thtop thleft">'.$row->po_detail_id.'</td>';
                    echo '<td class="thtop thleft thright">'.$row->qty.'</td>';                  
                    echo '<td class="thtop thleft thright">'.$row->rate.'</td>';                  
                    echo '<td class="thtop thleft thright">'.$row->rate_lastpurchase.'</td>';                  
                    echo '<td class="thtop thleft thright">'.$row->indent.'</td>';                  
                    echo '<td class="thtop thleft thright">'.$row->indent_detail.'</td>';                  
                    echo '<td class="thtop thleft thright">'.$row->item.'</td>';                  
                    echo '<td class="thtop thleft thright">'.$row->tax.'</td>';                  
                    echo '<td class="thtop thleft thright">'.$row->installation_rate.'</td>';                  
                    echo '<td class="thtop thleft thright">'.$row->installation_amount.'</td>';                  
                    echo '<td class="thtop thleft thright">'.$row->make.'</td>';                  
                    echo '<td class="thtop thleft thright">'.$row->uom_code.'</td>';                  
                    echo '<td class="thtop thleft thright">'.$row->po_sequence_no.'</td>';                  
                    echo '<td class="thtop thleft thright">'.$row->po_date.'</td>';                  
                    echo '<td class="thtop thleft thright">'.$row->status_name.'</td>';                  
                    echo '<td class="thtop thleft thright">'.$row->name.'</td>';                  
                    echo '<td class="thtop thleft thright">'.$row->group_code.'</td>';                  
                    echo '<td class="thtop thleft thright">'.$row->group_desc.'</td>';                  
                    echo '<td class="thtop thleft thright">'.$row->item_code.'</td>';                  
                    echo '<td class="thtop thleft thright">'.$row->item_desc.'</td>';                  
                    echo '<td class="thtop thleft thright">'.$row->item_wise_value.'</td>';                  
                    echo '<td class="thtop thleft thright">'.$row->supplier.'</td>';                  
                    echo '<td class="thtop thleft thright">'.$row->customer.'</td>';                  
                    echo '<td class="thtop thleft thright">'.$row->po_value_without_tax.'</td>';                  
                    echo '<td class="thtop thleft thright">'.$row->po_gross_value_with_tax.'</td>';                  
                    echo '<td class="thtop thleft thright">'.$row->source.'</td>';                  
                    echo '</tr>';
                $m++;
                }
                
            }
            
			
        ?>        
    </tbody>
    <!-- <tfoot>
        <tr>
            <th colspan="21" class="thtop thleft thright thbottom">&nbsp;</th>
        </tr>
    </tfoot> -->
</table>
<script>
window.print(); 
</script>
