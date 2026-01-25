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
            
                foreach($res as $row){
                    echo '<tr>';
                    echo '<td class="thtop thleft">'.$row->Quality.'</td>';
                    echo '<td class="thtop thleft">'.$row->Opening_Stock.'</td>';
                    echo '<td class="thtop thleft">'.$row->recvweight.'</td>';
                    echo '<td class="thtop thleft">'.$row->issueweight.'</td>';
                    echo '<td class="thtop thleft">'.$row->closweight.'</td>';
                     echo '</tr>';
                   
                }
            }

            
        ?>        
    </tbody>
    <tfoot>
        <tr>
            <th colspan="5" class="thtop thleft thright thbottom">&nbsp;</th>
        </tr>
    </tfoot>
</table>
<script>
window.print(); 
</script>
