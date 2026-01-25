
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

<div style="padding:10px;text-align:center;"><h2><?=$company_name?></h2></div>
<div style="padding:10px;text-align:center;"><h3><?=$report_title?></h3></div>
<table cellpadding=0 cellspacing=0 width="100%">
    <thead>
        <tr>
        <?php
                if($columns){
                    $array_keys = array_keys($columns);
                $cnt=count($array_keys);    
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
                                echo '<th class="thfirst">'.'kk'.$no.'</th>';
                            }    
                            if ($i>0 && $i<count($columns)  ){
                                echo '<th class="thlast">'.'p'.$columns[$i].'</th>';
                            } 
                            if (count($columns) == $i){
                                echo '<th class="thlast">'.'bb'.$columns[$i].'</th>';
                                $totsramt=$totsramt+$columns[$i];
                            }                             
                        }
                    }
                    $n++;
                    
                }

        ?>
       </tr>
    </thead>
    <tbody>
   
        <?php
            if($res){
                $n=0;
                $totsramt=0;

            foreach ($res as $row) {
                    $n++;
                    echo '<tr>';
                    echo '<td class="thtop thleft">' . $n . '</td>';
                    
                    
                    // Dynamically generate table cells for each property of $row
                    $i=0;
                    foreach ($row as $cell) {
                        echo '<td class="thtop thleft">' . $cell . '</td>';
                    if ($i==5) {
                        $totsramt=$totsramt+ (int)$cell;;
                       // echo $totsramt.'--'.$cell;
                    }
                    $i++;
                    }
                    
                    echo '</tr>';
                }

                    echo '<tr>';
                for($i=0; $i<$cnt; $i++){
                    if ($i<5) {
                        echo '<td class="thtop thleft">' . ' ' . '</td>';
                    }
                
                        if ($i==5) {
                            echo '<td class="thtop thleft">' . 'Grand Total' . '</td>';
                        }
                        if ($i==6) {
                            echo '<td class="thtop thleft">' . $totsramt . '</td>';
    
                        }
                    }
                echo '</tr>';
                
            }

            
        ?>        
    </tbody>
    <tfoot>
        <tr>
            <th colspan="4" class="thtop thleft thright">&nbsp;</th>
        </tr>
    </tfoot>
</table>
<script>
window.print(); 
</script>
