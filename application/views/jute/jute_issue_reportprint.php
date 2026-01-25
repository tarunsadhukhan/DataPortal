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
                    echo '<td class="thtop thleft">'.$row->Issue_Date.'</td>';
                    echo '<td class="thtop thleft">'.$row->MR_No.'</td>';
                    echo '<td class="thtop thleft">'.$row->Quality.'</td>';
                    echo '<td class="thtop thleft">'.$row->Godown_ID.'</td>';
                    echo '<td class="thtop thleft">'.$row->Pack_Type.'</td>';
                    echo '<td class="thtop thleft">'.$row->Quantity.'</td>';
                    echo '<td class="thtop thleft">'.$row->Weight.'</td>';
                    echo '<td class="thtop thleft">'.$row->Unit.'</td>';
                    echo '<td class="thtop thleft">'.$row->Rate.'</td>';
                    echo '<td class="thtop thleft">'.$row->Issue_Value.'</td>';
                    echo '<td class="thtop thleft">'.$row->MR_Line_No.'</td>';
                    echo '<td class="thtop thleft">'.$row->Quality_ID.'</td>';
                    echo '<td class="thtop thleft">'.$row->Godown_Name.'</td>';
                    echo '<td class="thtop thleft">'.$row->Status.'</td>';
                    echo '</tr>';
                   
                }
            }

            
        ?>        
    </tbody>
    <tfoot>
        <tr>
            <th colspan="14" class="thtop thleft thright thbottom">&nbsp;</th>
        </tr>
    </tfoot>
</table>
<script>
window.print(); 
</script>
