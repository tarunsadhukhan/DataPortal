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
            <th class="thtop thleft">First name</th>
            <th class="thtop thleft">Last name</th>
            <th class="thtop thleft">Position</th>
            <th class="thtop thleft">Office</th>
            <th class="thtop thleft">Age</th>
            <th class="thtop thleft">Start date</th>
            <th class="thtop thleft">Salary</th>
            <th class="thtop thleft">Extn.</th>
            <th class="thtop thleft thright">E-mail</th>
        </tr>
    </thead>
    <tbody>
        <?php
            if($res){
                foreach($res as $row){
                    echo '<tr>';
                    echo '<td class="thtop thleft">'.$row->company_code.'</td>';
                    echo '<td class="thtop thleft">'.$row->company_name.'</td>';
                    echo '<td class="thtop thleft">'.$row->company_code.'</td>';
                    echo '<td class="thtop thleft">'.$row->company_name.'</td>';
                    echo '<td class="thtop thleft">'.$row->company_code.'</td>';
                    echo '<td class="thtop thleft">'.$row->company_name.'</td>';
                    echo '<td class="thtop thleft">'.$row->company_code.'</td>';
                    echo '<td class="thtop thleft">'.$row->company_name.'</td>';
                    echo '<td class="thtop thleft thright">'.$row->company_code.'</td>';
                    echo '</tr>';
                    
                   
                }
            }
        ?>        
    </tbody>
    <tfoot>
        <tr>
            <th colspan="9" class="thtop thleft thright thbottom">&nbsp;</th>
        </tr>
    </tfoot>
</table>
<script>
window.print(); 
</script>
