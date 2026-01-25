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
                    echo '<td class="thtop thleft">'.$row->INDENT_NO.'</td>';
                    echo '<td class="thtop thleft">'.$row->INDENT_SRL_NO.'</td>';
                    echo '<td class="thtop thleft">'.date('d-m-Y',strtotime($row->IndentDate)).'</td>';
                    echo '<td class="thtop thleft thright">'.$row->branch_name.'</td>';
                    echo '<td class="thtop thleft thright">'.$row->prj_name.'</td>';
                    echo '<td class="thtop thleft thright">'.$row->Indent_type.'</td>';
                    echo '<td class="thtop thleft thright">'.$row->itemcode.'</td>';
                    echo '<td class="thtop thleft thright">'.$row->INDENT_QTY.'</td>';
                    echo '<td class="thtop thleft thright">'.$row->item_desc.'</td>';
                    echo '<td class="thtop thleft thright">'.$row->UOM_CODE.'</td>';
                    echo '<td class="thtop thleft thright">'.$row->Remarks.'</td>';
                    echo '<td class="thtop thleft thright">'.$row->OutSt_Qty.'</td>';
                    echo '<td class="thtop thleft thright">'.$row->cancelled_qty.'</td>';
                    echo '<td class="thtop thleft thright">'.($row->cancelled_date ? date('d-m-Y',strtotime($row->cancelled_date)) : "") .'</td>';
                    echo '<td class="thtop thleft thright">'.$row->Indentstatus.'</td>';
                    echo '<td class="thtop thleft thright">'.$row->supp_name.'</td>';
                    echo '<td class="thtop thleft thright">'.$row->po_num.'</td>';
                    echo '<td class="thtop thleft thright">'.$row->LINE_ITEM_NUM.'</td>';
                    echo '<td class="thtop thleft thright">'.($row->PO_DATE ? date('d-m-Y',strtotime($row->PO_DATE)) : "").'</td>';
                    echo '<td class="thtop thleft thright">'.$row->poQuantity.'</td>';
                    echo '<td class="thtop thleft thright">'.$row->Pending_Qty_PO.'</td>';
                    echo '<td class="thtop thleft thright">'.$row->STORE_RECEIVE_NO.'</td>';
                    echo '<td class="thtop thleft thright">'.($row->SRdate ? date('d-m-Y',strtotime($row->SRdate)) : "").'</td>';
                    echo '<td class="thtop thleft thright">'.$row->srQuantity.'</td>';
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
