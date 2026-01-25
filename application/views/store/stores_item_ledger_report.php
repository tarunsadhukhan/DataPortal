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
.numeric {
    text-align: right;
}

.string {
    text-align: left;
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
 //   var_dump($res);
            if($res){
                $totprd=$totovh=$totmaint=$totcap=$totgen=$totamt=0;
                $dep='';
                $costd='';
                $n=0;
                $cstockqty=0;
                $cstockval=0;
                $ostockqty=0;
                $ostockval=0;
                $rstockqty=0;
                $rstockval=0;
                $istockqty=0;
                $istockval=0;
                foreach($res as $row){
                    echo 'op '.$row->open_qty.' opv '.$row->open_val;
                    $cstockqty=$cstockqty+$row->open_qty+$row->tranrecv_qty-$row->tranissu_qty;
                    $cstockval=$cstockval+$row->open_val+$row->tranrecv_val-$row->tranissu_val;
                    if  ($row->tran_type=='O') { 
                       $ostockqty=$row->open_qty;
                       $ostockval=$row->open_val;
                    }
                    $rstockqty=$rstockqty+$row->tranrecv_qty;
                    $rstockval=$rstockval+$row->tranrecv_val;
                    $istockqty=$istockqty+$row->tranissu_qty;
                    $istockval=$istockval+$row->tranissu_val;
                
                    echo '<tr>';
                    echo '<td class="thtop thleft">'.$row->itemcode.'</td>';
                    echo '<td class="thtop thleft">'.$row->item_name.'</td>';
                    echo '<td class="thtop thleft">'.$row->uom_code.'</td>';
                    echo '<td class="thtop thleft">'.$row->tran_type.'</td>';
                    echo '<td class="thtop thleft">'.$row->tran_date1.'</td>';
                    echo '<td class="thtop thleft">'.$row->doc_no.'</td>';
                    echo '<td class="thtop thleft">'.$row->open_qty.'</td>';
                    echo '<td class="thtop thleft">'.$row->open_val.'</td>';
                    echo '<td class="thtop thleft">'.$row->tranrecv_qty.'</td>';
                    echo '<td class="thtop thleft">'.$row->tranrecv_val.'</td>';
                    echo '<td class="thtop thleft">'.$row->tranissu_qty.'</td>';
                    echo '<td class="thtop thleft">'.$row->tranissu_val.'</td>';
                    echo '<td class="thtop thleft">'.$cstockqty.'</td>';
                    echo '<td class="thtop thleft">'.$cstockval.'</td>';
                    echo '</tr>';
                   
                }
        
            echo '<tr>';
            echo '<td class="thtop thleft">'.' '.'</td>';
            echo '<td class="thtop thleft">'.'Grand Total'.'</td>';
            echo '<td class="thtop thleft">'.' '.'</td>';
            echo '<td class="thtop thleft">'.' '.'</td>';
            echo '<td class="thtop thleft">'.' '.'</td>';
            echo '<td class="thtop thleft">'.'Closing'.'</td>';
            echo '<td class="thtop thleft">'.$ostockqty.'</td>';
            echo '<td class="thtop thleft">'.$ostockval.'</td>';
            echo '<td class="thtop thleft">'.$rstockqty.'</td>';
            echo '<td class="thtop thleft">'.$rstockval.'</td>';
            echo '<td class="thtop thleft">'.$istockqty.'</td>';
            echo '<td class="thtop thleft">'.$istockval.'</td>';
            echo '<td class="thtop thleft">'.$cstockqty.'</td>';
            echo '<td class="thtop thleft">'.$cstockval.'</td>';
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
