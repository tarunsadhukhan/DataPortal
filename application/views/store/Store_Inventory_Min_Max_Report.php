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

        </tr>
    </thead>
    <tbody>
   
        <?php
            if($res){
                $n=0;
                foreach($res as $row){
                    $n++;
                    echo '<tr>';
                    echo '<td class="thtop thleft">'.$n.'</td>';
                    echo '<td class="thtop thleft">'.$row->Item_Code.'</td>';
                    echo '<td class="thtop thleft">'.$row->Item_Description.'</td>';
                    echo '<td class="thtop thleft">'.$row->Unit.'</td>';
                    echo '<td class="thtop thleft">'.$row->Min_Qty.'</td>';
                    echo '<td class="thtop thleft">'.$row->Max_qty.'</td>';
                    echo '<td class="thtop thleft">'.$row->Min_Order_qty.'</td>';
                    echo '<td class="thtop thleft">'.$row->Stock_qty.'</td>';
                    echo '<td class="thtop thleft">'.$row->Pending_indent_qty.'</td>';
                    echo '<td class="thtop thleft">'.$row->Pending_PO_Qty.'</td>';
                    echo '<td class="thtop thleft">'.$row->record_type.'</td>';
                    echo '<td class="thtop thleft">'.$row->Qty_To_Be_Order.'</td>';
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
