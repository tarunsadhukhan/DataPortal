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
            if($res){
                $total=$nom=$avg=0;
                foreach($res as $row){
                    $cn = count(get_object_vars($row));
                    $total=$nom=$avg=$cnt=0;
                    $norw=0;
                    echo '<tr>';
                    foreach ($row as $value) {
                        
                        echo '<td class="thtop thleft">' . $value . '</td>';
                        if ($norw>=2) {                   
                           $total=$total+$value;
                            if ($value>0) {
                                $nom++;
                            } 
                        }
                        $norw++;

                    }
                    $avg=round($total/$nom,3);
                    echo '<td class="thtop thleft">' . $total . '</td>';
                    echo '<td class="thtop thleft">' . $nom . '</td>';
                    echo '<td class="thtop thleft">' . $avg . '</td>';
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
