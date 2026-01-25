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
                    echo '<td class="thtop thleft">'.$row->SUPP_CODE.'</td>';
                    echo '<td class="thtop thleft">'.$row->SUPPLIER_NAME.'</td>';
                    echo '<td class="thtop thleft">'.$row->MR_NO.'</td>';
                    echo '<td class="thtop thleft">'.$row->MR_DATE.'</td>';
                    echo '<td class="thtop thleft">'.$row->JUTE_TYPE.'</td>';
                    echo '<td class="thtop thleft">'.$row->QUALITY.'</td>';
                    echo '<td class="thtop thleft">'.$row->CONDITION.'</td>';
                    echo '<td class="thtop thleft">'.$row->ADVISED_CLAIM_KGS.'</td>';
                    echo '<td class="thtop thleft">'.$row->ACTUAL_CLAIM_KGS.'</td>';
                    echo '<td class="thtop thleft">'.$row->DEVIATION_KGS.'</td>';
                    echo '</tr>';
                   
                }
            }

            
        ?>        
    </tbody>
    <tfoot>
        <tr>
            <th colspan="10" class="thtop thleft thright thbottom">&nbsp;</th>
        </tr>
    </tfoot>
</table>
<script>
window.print(); 
</script>
