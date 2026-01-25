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
               //     var_dump($columns);
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
                $m=1;
                foreach($res as $row){
                    echo '<tr>';
                    $array_keys = array_keys($columns);
                    if($array_keys){
                        for($i=0; $i<count($array_keys); $i++){
                            if($sno){
                                if($i==0){
                                    echo '<td class="thtop thleft">'.$m.'</td>';
                                }else
                                if($i==count($array_keys)){
                                    echo '<td class="thtop thleft thright">'.(isset($row->$mrowname) ? $row->$mrowname : $row[$mrowname]).'</td>';
                                }else{
                                    $mrowname = $array_keys[$i];
                                    echo '<td class="thtop thleft">'.(isset($row->$mrowname) ? $row->$mrowname : ($submenuId==601? "" : $row[$mrowname])).'</td>';
                                }
                            }else{
                                if($i==count($array_keys)){
                                    echo '<td class="thtop thleft thright">'.(isset($row->$mrowname) ? $row->$mrowname : $row[$mrowname]).'</td>';
                                }else{
                                    $mrowname = $array_keys[$i];
                                    if($submenuId==534){
                                        if($i==3){
                                            $accountnumber = (isset($row->$mrowname) ? $row->$mrowname : $row[$mrowname]);
                                            $accountnumber = "'".(string)$accountnumber."\r";
                                            echo '<td class="thtop thleft">'.$accountnumber.'</td>';
                                            
                                        }else{
                                            echo '<td class="thtop thleft">'.(isset($row->$mrowname) ? $row->$mrowname : $row[$mrowname]).'</td>';
                                        }
                                    }else{
                                        echo '<td class="thtop thleft">'.(isset($row->$mrowname) ? $row->$mrowname : $row[$mrowname]).'</td>';
                                    }
                                    
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
    <tfoot>
        <tr>
            <th colspan="<?=count($array_keys)?>" class="thtop thleft thright thbottom">&nbsp;</th>
        </tr>
    </tfoot>
</table>
<script>
window.print(); 
</script>
