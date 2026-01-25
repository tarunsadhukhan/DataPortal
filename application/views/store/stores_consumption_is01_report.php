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
<?php          $company_name = $this->session->userdata('companyname');
          
    ?>
<div style="padding:10px;text-align:center;"><h2><?=$company_name?></h2></div>

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
                $totprd=$totovh=$totmaint=$totcap=$totgen=$totamt=0;
                $dep='';
                $costd='';
                $n=0;
                foreach($res as $row){

        
                    echo '<tr>';
                    echo '<td class="thtop thleft">'.$row->dept_desc.'</td>';
                    echo '<td class="thtop thleft">'.$row->cost_desc.'</td>';
                    echo '<td class="thtop thleft">'.$row->itemcode.'</td>';
                    echo '<td class="thtop thleft">'.$row->item_desc.'</td>';
                    echo '<td class="thtop thleft">'.$row->Production.'</td>';
                    echo '<td class="thtop thleft">'.$row->OVERHAULING.'</td>';
                    echo '<td class="thtop thleft">'.$row->MAINTENANCE.'</td>';
                    echo '<td class="thtop thleft">'.$row->CAPITAL.'</td>';
                    echo '<td class="thtop thleft">'.$row->GENERAL.'</td>';
                    echo '<td class="thtop thleft">'.$row->total_amt.'</td>';
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
