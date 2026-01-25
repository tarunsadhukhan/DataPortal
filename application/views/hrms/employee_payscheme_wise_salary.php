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
            if($res){
                $i=1;
                $array_keys = array_keys($columns);
                    


                foreach($res as $row){
                    echo '<tr>';
                    for($i=0; $i<count($array_keys); $i++){
                        $mrowname = $array_keys[$i];
                        echo '<td class="thtop thleft">'.$row->$mrowname.'</td>';
                    }
                     echo '</tr>';
                }
            }
        /*
            echo '<tr>';
                    echo '<td class="thtop thleft">'.$i.'</td>';
                    echo '<td class="thtop thleft">'.$row->Date.'</td>';
                    echo '<td class="thtop thleft">'.$row->Spell.'</td>';
                    echo '<td class="thtop thleft">'.$row->EB_No.'</td>';
                    echo '<td class="thtop thleft">'.$row->Name.'</td>';
                    echo '<td class="thtop thleft">'.$row->Department.'</td>';
                    echo '<td class="thtop thleft">'.$row->Designation.'</td>';                    
                    echo '<td class="thtop thleft">'.$row->attendance_type.'</td>';
                    echo '<td class="thtop thleft">'.$row->attendance_source.'</td>';
                    echo '<td class="thtop thleft">'.$row->Working_Hours.'</td>';
                    echo '<td class="thtop thleft">'.$row->MC_Nos.'</td>';
                    echo '<td class="thtop thleft">'.$row->Remarks.'</td>';
                     echo '</tr>';
*/        
            
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
