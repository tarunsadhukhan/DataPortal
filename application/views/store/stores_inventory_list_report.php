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
<?php          
        $company_name = $this->session->userdata('companyname');
          
    ?>
<div style="padding:10px;text-align:center;"><h2><?=$company_name?></h2></div>

<div style="padding:10px;text-align:center;"><h2><?=$report_title?></h2></div>
<table cellpadding=0 cellspacing=0 width="100%">
    <thead>
        <tr>
        <?php
                if($columns){
                    $array_keys = array_keys($columns);
                    if($array_keys){
                        $i=0;
                        foreach($columns as $column){
                            if($i==0){
                                echo '<th class="thfirst">'.$column.'</th>';
                            }else if(count($columns)-1 == $i){
                                echo '<th class="thlast">'.$column.'</th>';
                            }else{
                                echo '<th>'.$column.'</th>';
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
    <tbody>
   
        <?php
   //     var_dump($res);
            if($res){
                $n=0;
                $gopval=$grcvval=$gissval=$clval=0;
                foreach($res as $row){
                    $n++;
                    $gopval=$gopval+$row->open_val;
                    $grcvval=$grcvval+$row->tranrecv_val;
                    $gissval=$gissval+$row->tranissu_val;
                    $gclval=$gclval+$row->clos_val;
                    
                    echo '<tr>';
                    echo '<td class="thtop thleft">'.$n.'</td>';
                    echo '<td class="thtop thleft">'.$row->itemcode.'</td>';
                    echo '<td class="thtop thleft">'.$row->item_name.'</td>';
                    echo '<td class="thtop thleft">'.$row->uom_code.'</td>';
                    echo '<td class="thtop thleft">'.$row->open_qty.'</td>';
                    $colval = sprintf("%.2f", $row->open_val);
                    echo '<td class="thtop thleft">'.$colval.'</td>';
                    echo '<td class="thtop thleft">'.$row->tranrecv_qty.'</td>';
                    $colval = sprintf("%.2f", $row->tranrecv_val);
                    echo '<td class="thtop thleft">'.$colval.'</td>';
                    echo '<td class="thtop thleft">'.$row->tranissu_qty.'</td>';
                    $colval = sprintf("%.2f", $row->tranissu_val);
                    echo '<td class="thtop thleft">'.$colval.'</td>';
                    echo '<td class="thtop thleft">'.$row->clos_qty.'</td>';
                    $colval = sprintf("%.2f", $row->clos_val);
                    echo '<td class="thtop thleft">'.$colval.'</td>';
                    echo '<td class="thtop thleft">'.$row->lrecpdate.'</td>';
                    echo '<td class="thtop thleft">'.$row->lissuedate.'</td>';
                    echo '<td class="thtop thleft">'.$row->nodays.'</td>';
                    echo '</tr>';
                   
                }
                    echo '<tr>';
                    echo '<td class="thtop thleft">'.''.'</td>';
                    echo '<td class="thtop thleft">'.''.'</td>';
                    echo '<td class="thtop thleft">'.'Grand Total'.'</td>';
                    echo '<td class="thtop thleft">'.''.'</td>';
                    echo '<td class="thtop thleft">'.''.'</td>';
                    $colval = sprintf("%.2f", $gopval);
                    echo '<td class="thtop thleft">'.$colval.'</td>';
                    echo '<td class="thtop thleft">'.''.'</td>';
                    $colval = sprintf("%.2f", $grcvval);
                    echo '<td class="thtop thleft">'.$colval.'</td>';
                    echo '<td class="thtop thleft">'.''.'</td>';
                    $colval = sprintf("%.2f", $gissval);
                    echo '<td class="thtop thleft">'.$colval.'</td>';
                    echo '<td class="thtop thleft">'.''.'</td>';
                    $colval = sprintf("%.2f", $gclval);
                    echo '<td class="thtop thleft">'.$colval.'</td>';
                    echo '<td class="thtop thleft">'.''.'</td>';
                    echo '<td class="thtop thleft">'.''.'</td>';
                    echo '<td class="thtop thleft">'.''.'</td>';
                    echo '</tr>';
            
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
