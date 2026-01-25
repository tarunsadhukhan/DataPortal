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
                }
            ?>
        </tr>
    </thead>
    <tbody>
        <?php
            if($res){
                $i=1;
                foreach($res as $row){
                    echo '<tr>';
                    echo '<td class="thtop thleft">'.$i.'</td>';
                    echo '<td class="thtop thleft">'.$row->po_id.'</td>';
                    echo '<td class="thtop thleft thright">'.$row->created_by.'</td>';
                    echo '<td class="thtop thleft thright">'.$row->created_date.'</td>';
                    echo '<td class="thtop thleft thright">'.$row->last_modified_by.'</td>';
                    echo '<td class="thtop thleft thright">'.$row->last_modified_date.'</td>';
                    echo '<td class="thtop thleft thright">'.$row->bill_to_address.'</td>';
                    echo '<td class="thtop thleft thright">'.$row->bill_to_state_name.'</td>';
                    echo '<td class="thtop thleft thright">'.$row->ship_to_address.'</td>';
                    echo '<td class="thtop thleft thright">'.$row->ship_to_state_name.'</td>';
                    echo '<td class="thtop thleft thright">'.$row->credit_days.'</td>';
                    echo '<td class="thtop thleft thright">'.$row->po_date.'</td>';
                    echo '<td class="thtop thleft thright">'.$row->po_sequence_no.'</td>';
                    echo '<td class="thtop thleft thright">'.$row->source.'</td>';
                    echo '<td class="thtop thleft thright">'.$row->tax_payable.'</td>';
                    echo '<td class="thtop thleft thright">'.$row->delivery_timeline.'</td>';
                    echo '<td class="thtop thleft thright">'.$row->supplier_branch.'</td>';
                    echo '<td class="thtop thleft thright">'.$row->billing_branch.'</td>';
                    echo '<td class="thtop thleft thright">'.$row->category.'</td>';
                    echo '<td class="thtop thleft thright">'.$row->net_amount.'</td>';
                    echo '<td class="thtop thleft thright">'.$row->total_amount.'</td>';
                    echo '<td class="thtop thleft thright">'.$row->tax_type.'</td>';
                    echo '<td class="thtop thleft thright">'.$row->item_group.'</td>';
                    echo '<td class="thtop thleft thright">'.$row->advance_type.'</td>';
                    echo '<td class="thtop thleft thright">'.$row->advance_percentage.'</td>';
                    echo '<td class="thtop thleft thright">'.$row->advance_amount.'</td>';
                    echo '<td class="thtop thleft thright">'.$row->Status.'</td>';
                    echo '<td class="thtop thleft thright">'.$row->Budget_Head.'</td>';
                    echo '<td class="thtop thleft thright">'.$row->indent_squence_no.'</td>';
                    echo '<td class="thtop thleft thright">'.$row->company_code.'</td>';
                    echo '<td class="thtop thleft thright">'.$row->branch_name.'</td>';
                    echo '<td class="thtop thleft thright">'.$row->branch_address.'</td>';
                    echo '<td class="thtop thleft thright">'.$row->group_desc.'</td>';
                    echo '<td class="thtop thleft thright">'.$row->supp_name.'</td>';
                    echo '<td class="thtop thleft thright">'.$row->customer.'</td>';
                    echo '</tr>';
                $i++;
                }

            }
        ?>        
    </tbody>
    <!-- <tfoot>
        <tr>
            <th colspan="9" class="thtop thleft thright thbottom">&nbsp;</th>
        </tr>
    </tfoot> -->
</table>
<script>
window.print(); 
</script>
