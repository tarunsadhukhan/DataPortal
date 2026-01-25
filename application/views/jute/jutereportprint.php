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
                $sno=1;
                foreach($res as $row){
                    echo '<tr>';
                    echo '<td class="thtop thleft">'.$sno.'</td>';
                    echo '<td class="thtop thleft">'.$row->Quality_ID.'</td>';
                    echo '<td class="thtop thleft">'.$row->Quality_Name.'</td>';
                    echo '<td class="thtop thleft">'.$row->Opening_Bales.'</td>';
                    echo '<td class="thtop thleft">'.$row->Receipt_Bales.'</td>';
                    echo '<td class="thtop thleft">'.$row->Issue_Bales.'</td>';
                    echo '<td class="thtop thleft">'.$row->Sold_Bales.'</td>';
                    $close_bales = ($row->Opening_Bales + $row->Receipt_Bales) - ($row->Sold_Bales + $row->Issue_Bales);
                    echo '<td class="thtop thleft">'.round($close_bales,2).'</td>';

                    echo '<td class="thtop thleft">'.$row->Opening_Drums.'</td>';
                    echo '<td class="thtop thleft">'.$row->Drums.'</td>';
                    echo '<td class="thtop thleft">'.$row->Drums_Issued.'</td>';
                    echo '<td class="thtop thleft">'.$row->Drums_Sold.'</td>';
                    $close_drums = ($row->Opening_Drums + $row->Drums) - ($row->Drums_Sold + $row->Drums_Issued);
                    echo '<td class="thtop thleft">'.round($close_drums,2).'</td>';

                    echo '<td class="thtop thleft">'.$row->Opening_Wt.'</td>';
                    echo '<td class="thtop thleft">'.$row->Receipt_Wt.'</td>';
                    echo '<td class="thtop thleft">'.$row->Issued_Wt.'</td>';
                    echo '<td class="thtop thleft">'.$row->Sold_Wt.'</td>';
                    $colse_wt = ($row->Opening_Wt + $row->Receipt_Wt) - ($row->Issued_Wt + $row->Sold_Wt);
                    echo '<td class="thtop thleft">'.round($close_drums,2).'</td>';

                    echo '<td class="thtop thleft">'.$row->Avg_Issue_Rate.'</td>';
                    echo '<td class="thtop thleft">'.$row->Issued_Val.'</td>';
                    echo '</tr>';

                   
                   $sno++;
                }
            }
        ?>        
    </tbody>
    <tfoot>
        <tr>
            <th colspan="16" class="thtop thleft thright thbottom">&nbsp;</th>
        </tr>
    </tfoot>
</table>
<script>
window.print(); 
</script>
