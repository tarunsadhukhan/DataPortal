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

.class1 {
    background-color: red; /* Set background color for class1 */
}

.class2 {
    background-color: blue; /* Set background color for class2 */
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
    //    $n=count($res);
//        echo $n;
            if($res){
                $m=1;
              //  var_dump($res);
                foreach($res as $row){
                    echo '<tr>';
                    $array_keys = array_keys($columns);
                    $p=count($array_keys);
                    if($array_keys){
                        $class = 'class1'; 
                        for($i=0; $i<count($array_keys); $i++){
                            if ($m==79) {
                                $mrowname = $array_keys[$p];
                                $cellValue = $row->$mrowname;
        //                    echo 'this us'.$cellValue ;
                            }
      //  echo $sno;
                            if($sno){
                                if($i==0){
                                    echo '<td class="thtop thleft">'.$m.'</td>';
                                }else
                                if($i==count($array_keys)){
                                    echo '<td class="thtop thleft thright">'.$row->$mrowname.'</td>';
                                }else{
                                    $mrowname = $array_keys[$i];
                                    $cellValue = $row->$mrowname;
                                    echo '<td class="thtop thleft">'.$row->$mrowname.'</td>';
                             //        echo "<td class='thtop thleft $class'>$cellValue</td>";
                                }
                            }else{
                                if($i==count($array_keys)){
                                    echo '<td class="thtop thleft thright">'.$row->$mrowname.'</td>';
                                }else{
                                    $mrowname = $array_keys[$i];
                                    echo '<td class="thtop thleft">'.$row->$mrowname.'</td>';
                                    $cellValue = $row->$mrowname;
                                    //      echo '<td class="thtop thleft">'.$row->$mrowname.'</td>';
                                 //          echo "<td class='thtop thleft $class'>$cellValue</td>";
                                }
                            }
                           
                            
                        }
                    }
                   
                    
                    echo '</tr>';
                    $m++;
                }
            }
        ?>        
    </tbody>
    <!-- <tfoot>
        <tr>
            <th colspan="<?=count($array_keys)?>" class="thtop thleft thright thbottom">&nbsp;</th>
        </tr>
    </tfoot> -->
</table>
<script>
window.print(); 
</script>
