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
                    echo '<td class="thtop thleft">'.$row->category.'</td>';
                    echo '<td class="thtop thleft">'.$row->planName.'</td>';
                    echo '<td class="thtop thleft">'.$row->bales.'</td>';
                    echo '<td class="thtop thleft">'.$row->drums.'</td>';
                    echo '<td class="thtop thleft">'.$row->yarnType.'</td>';
                    echo '<td class="thtop thleft">'.(count($row->qualities) > 0 ? $row->qualities[0]: 0).'</td>';
                    echo '<td class="thtop thleft">'.$row->percentage.'</td>';
                    echo '<td class="thtop thleft">'.$row->actualIssue.'</td>';
                    echo '<td class="thtop thleft">'.$row->desiredIssue.'</td>';
                    echo '<td class="thtop thleft">'.$row->deviation.'</td>';
                    echo '<td class="thtop thleft">'.$row->deviationPercentage.'</td>';
                    echo '<td class="thtop thleft">'.$row->balesOrDrums.'</td>';
                    echo '<td class="thtop thleft">'.$row->value.'</td>';
                    echo '<td class="thtop thleft">'.$row->totalPlanWeight.'</td>';
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
