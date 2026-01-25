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
    </thead>
    <tbody>
        <?php
            if($res){
                $i=1;
/*                 eb_id	emp_code	emp_name	dept_code	dept_desc	desig	cata_desc	date_of_birth	
                date_of_join	date_of_join	pf_no	pf_date_of_join	pf_uan_no	bank_acc_no	ifsc_code	
                bank_name	contractor_name	status_name	NAME	isactive	last_workings
 */
                foreach($res as $row){
                    echo '<tr>';
                    echo '<td class="thtop thleft">'.$i.'</td>';
                    echo '<td class="thtop thleft">'.$row->eb_id.'</td>';
                    echo '<td class="thtop thleft">'.$row->emp_code.'</td>';
                    echo '<td class="thtop thleft">'.$row->emp_name.'</td>';
                    echo '<td class="thtop thleft">'.$row->gender.'</td>';
                    echo '<td class="thtop thleft">'.$row->cata_desc.'</td>';
                    echo '<td class="thtop thleft">'.$row->dept_code.'</td>';
                    echo '<td class="thtop thleft">'.$row->dept_desc.'</td>';
                    echo '<td class="thtop thleft">'.$row->desig.'</td>';                    
                    echo '<td class="thtop thleft">'.$row->date_of_birth.'</td>';
                    echo '<td class="thtop thleft">'.$row->date_of_join.'</td>';
                    echo '<td class="thtop thleft">'.$row->esi_no.'</td>';
                    echo '<td class="thtop thleft">'.$row->pf_no.'</td>';
                    echo '<td class="thtop thleft">'.$row->pf_date_of_join.'</td>';
echo '<td class="thtop thleft" style="mso-number-format:\'@\';">'.$row->pf_uan_no.'</td>';
echo '<td class="thtop thleft" style="mso-number-format:\'@\';">'.$row->bank_acc_no.'</td>';
                    echo '<td class="thtop thleft">'.$row->ifsc_code.'</td>';
                    echo '<td class="thtop thleft">'.$row->bank_name.'</td>';
                    echo '<td class="thtop thleft">'.$row->contractor_name.'</td>';
                    echo '<td class="thtop thleft">'.$row->status_name.'</td>';
                    echo '<td class="thtop thleft">'.$row->NAME.'</td>';
                    echo '<td class="thtop thleft">'.$row->isactive.'</td>';
                    echo '<td class="thtop thleft">'.$row->last_workings.'</td>';
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
