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
            if($res){
                $i=1;
                foreach($res as $row){
                    echo '<tr>';
                    echo '<td class="thtop thleft">'.$i.'</td>';
                    echo '<td class="thtop thleft">'.$row->Tran_No.'</td>';
                    echo '<td class="thtop thleft">'.$row->EB_No.'</td>';
                    echo '<td class="thtop thleft">'.$row->Name.'</td>';
                    echo '<td class="thtop thleft">'.$row->Date.'</td>';
                    echo '<td class="thtop thleft">'.$row->Department.'</td>';
                    echo '<td class="thtop thleft">'.$row->Designation.'</td>';                    
                    echo '<td class="thtop thleft">'.$row->Mark.'</td>';
                    echo '<td class="thtop thleft">'.$row->Spell.'</td>';
                    echo '<td class="thtop thleft">'.$row->Idle_Hours.'</td>';
                    echo '<td class="thtop thleft">'.$row->Spell_Hours.'</td>';
                    echo '<td class="thtop thleft">'.$row->Work_Hours.'</td>';
                    echo '<td class="thtop thleft">'.$row->Source.'</td>';
                    echo '<td class="thtop thleft">'.$row->Type.'</td>';
                    echo '<td class="thtop thleft">'.$row->Status.'</td>';
                    echo '<td class="thtop thleft">'.$row->Remarks.'</td>';
                    echo '</tr>';
                $i++;  
                }
            }

            
        ?>        
    </tbody>
    <tfoot>
        <tr>
            <th colspan="07" class="thtop thleft thright thbottom">&nbsp;</th>
        </tr>
    </tfoot>
</table>
<script>
window.print(); 
</script>
