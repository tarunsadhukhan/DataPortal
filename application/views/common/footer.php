</div>

<!-- The Modal -->
<div id="myModal" class="modal">

  <!-- Modal content -->
  <div class="modal-content">
    <div class="modal-header">
      <h3>Report Filter</h3>
    </div>
    <div class="modal-body">
      <div class="row">
        <?php        
              if(($submenuId!=93) & ($submenuId!=506) & ($submenuId!=505) & ($submenuId!=517) & ($submenuId!=508) 
              & ($submenuId!=609) & ($submenuId!=651)  ){
          ?>
          <div class="col-12 col-sm-6">
              <div class="form-group">
                  <label for="account-name"><?=($submenuId==530 ? 'Date' : 'From Date')?></label>
                  <input class="form-control form-control-rounded" value="<?=$from_date?>" id="fromdt" name="fromdt" type="date">
              </div>
          </div>
          <?php
          if(($submenuId!=530)  & ($submenuId!=506) & ($submenuId!=675)  & ($submenuId!=505) & ($submenuId!=517)  
          & ($submenuId!=673) & ($submenuId!=682) & ($submenuId!=684) & ($submenuId!=508) & ($submenuId!=609)
         ){
          ?>
            <div class="col-12 col-sm-6">
              <div class="form-group">
              <label for="email">To Date</label>
              <input class="form-control form-control-rounded" id="todt"  value="<?php echo $to_date;?>" name="todt" type="date" >
              </div>
          </div>

          <?php
          }
          ?>
          
          <?php
              }
          ?>
          <?php
              if($submenuId==675){
          ?>
          <div class="col-12 col-sm-6">
              <div class="form-group">
              <label for="email">Line No</label>
              <input class="form-control form-control-rounded" id="itcod"  value="<?=$itcod?>" name="itcod" type="text">
              </div>
          </div>
          <div class="col-12 col-sm-6">
              <div class="form-group">
              <label for="email">Eff Upto</label>
              <input class="form-control form-control-rounded" id="srno"  value="<?=$srno?>" name="srno" type="text">
              </div>
          </div>
          <?php
              }
          ?>

<?php
              if($submenuId==674){
          ?>
          <div class="col-12 col-sm-6">
              <div class="form-group">
              <label for="email">Loom No</label>
              <input class="form-control form-control-rounded" id="itcod"  value="<?=$itcod?>" name="itcod" type="text">
              </div>
          </div>
          <div class="col-12 col-sm-6">
              <div class="form-group">
              <label for="email">Eff Upto</label>
              <input class="form-control form-control-rounded" id="srno"  value="<?=$srno?>" name="srno" type="text">
              </div>
          </div>
          <?php
              }
          ?>

<?php
              if($submenuId==673){
          ?>
           <div class="col-12 col-sm-6">
              <div class="form-group">
              <label for="email">Departments</label>
              <?php
                    $de['0'] = 'ALL';
                    foreach ($departments as $dept) {
                        $de[$dept->dept_desc] = $dept->dept_desc;
                    }       
                    
                    echo form_dropdown('att_dept', $de, ($att_dept ? $att_dept : "0"), 'id="att_dept"  class="myselect form-control form-control-rounded" data-placeholder="Select Department"  style="width:100%;"');
                ?>
              
              </div>
          </div>
          
         <div class="col-12 col-sm-6">
              <div class="form-group">
              <label for="email">Category</label>
              <?php
                    $ca['0'] = 'ALL';
                    foreach ($categorys as $cat) {
                        $ca[$cat->cata_id] = $cat->cata_desc;
                    }       
                    
                    echo form_dropdown('att_cat', $ca, ($att_cat ? explode(',', $att_cat) : "0"), 'id="att_cat"  class="myselect form-control form-control-rounded" data-placeholder="Select Category"  style="width:100%;"');
                ?>
              
              </div>
          </div>
 
        <div class="col-12 col-sm-6">
              <div class="form-group">
              <label for="email">Emp Code</label>
              <input class="form-control form-control-rounded" id="itcod"  value="<?=$itcod?>" name="itcod" type="text">
              </div>
          </div>
          <div class="col-12 col-sm-6">
              <div class="form-group">
              <label for="email">no of days</label>
              <input class="form-control form-control-rounded" id="srno"  value="<?=$srno?>" name="srno" type="text">
              </div>
          </div>
  




          <?php
              }
          ?>



<?php
              if($submenuId==682){
          ?>
           <div class="col-12 col-sm-6">
              <div class="form-group">
              <label for="email">Departments</label>
              <?php
                    $de['0'] = 'ALL';
                    foreach ($departments as $dept) {
                        $de[$dept->dept_desc] = $dept->dept_desc;
                    }       
                    
                    echo form_dropdown('att_dept', $de, ($att_dept ? $att_dept : "0"), 'id="att_dept"  class="myselect form-control form-control-rounded" data-placeholder="Select Department"  style="width:100%;"');
                ?>
              
              </div>
          </div>
          
     
        <div class="col-12 col-sm-6">
              <div class="form-group">
              <label for="email">Emp Code</label>
              <input class="form-control form-control-rounded" id="itcod"  value="<?=$itcod?>" name="itcod" type="text">
              </div>
          </div>
      
          <div class="col-12 col-sm-6">
              <div class="form-group">
              <label for="email">Shift</label>
              <?php
                    $source['ALL'] = 'ALL';
                    $source['A'] = 'A';
                    $source['B'] = 'B';
                    $source['C'] = 'C';
                     
                    echo form_dropdown('Source', $source, ($Source ? $Source : "1"), 'id="Source"  class="myselect form-control form-control-rounded" data-placeholder="Select Source"  style="width:100%;"');
                ?>
              
              </div>
          </div>




          <?php
              }
          ?>

<?php
              if($submenuId==686){
          ?>
           <div class="col-12 col-sm-6">
              <div class="form-group">
              <label for="email">Departments</label>
              <?php
                    $de['0'] = 'ALL';
                    foreach ($departments as $dept) {
                        $de[$dept->dept_desc] = $dept->dept_desc;
                    }       
                    
                    echo form_dropdown('att_dept', $de, ($att_dept ? $att_dept : "0"), 'id="att_dept"  class="myselect form-control form-control-rounded" data-placeholder="Select Department"  style="width:100%;"');
                ?>
              
              </div>
          </div>
          
     
        <div class="col-12 col-sm-6">
              <div class="form-group">
              <label for="email">Emp Code</label>
              <input class="form-control form-control-rounded" id="itcod"  value="<?=$itcod?>" name="itcod" type="text">
              </div>
          </div>
    



          <?php
              }
          ?>

<?php
              if($submenuId==687){
          ?>
           <div class="col-12 col-sm-6">
              <div class="form-group">
              <label for="email">Departments</label>
              <?php
                    $de['0'] = 'ALL';
                    foreach ($departments as $dept) {
                        $de[$dept->dept_desc] = $dept->dept_desc;
                    }       
                    
                    echo form_dropdown('att_dept', $de, ($att_dept ? $att_dept : "0"), 'id="att_dept"  class="myselect form-control form-control-rounded" data-placeholder="Select Department"  style="width:100%;"');
                ?>
              
              </div>
          </div>
          
     
        <div class="col-12 col-sm-6">
              <div class="form-group">
              <label for="email">Emp Code</label>
              <input class="form-control form-control-rounded" id="itcod"  value="<?=$itcod?>" name="itcod" type="text">
              </div>
          </div>
    



          <?php
              }
          ?>



<?php
              if($submenuId==676){
          ?>
          <div class="col-12 col-sm-6">
              <div class="form-group">
              <label for="email">EB No</label>
              <input class="form-control form-control-rounded" id="itcod"  value="<?=$itcod?>" name="itcod" type="text">
              </div>
          </div>
          <div class="col-12 col-sm-6">
              <div class="form-group">
              <label for="email">Eff Upto</label>
              <input class="form-control form-control-rounded" id="srno"  value="<?=$srno?>" name="srno" type="text">
              </div>
          </div>
          <div class="col-12 col-sm-6">
              <div class="form-group">
              <label for="email">Loom Side</label>
              <?php
                    $source['1'] = 'Hessian';
                    $source['2'] = 'Sacking';
                    
                    echo form_dropdown('Source', $source, ($Source ? $Source : "1"), 'id="Source"  class="myselect form-control form-control-rounded" data-placeholder="Select Source"  style="width:100%;"');
                ?>
              
              </div>
          </div>

          <?php
              }
          ?>
<?php
              if($submenuId==586){
          ?>
          <div class="col-12 col-sm-6">
              <div class="form-group">
              <label for="email">EB No</label>
              <input class="form-control form-control-rounded" id="itcod"  value="<?=$itcod?>" name="itcod" type="text">
              </div>
          </div>
          <div class="col-12 col-sm-6">
              <div class="form-group">
              <label for="email">Eff Upto</label>
              <input class="form-control form-control-rounded" id="srno"  value="<?=$srno?>" name="srno" type="text">
              </div>
          </div>
          <div class="col-12 col-sm-6">
              <div class="form-group">
              <label for="email">Reports Type</label>
              <?php
                    $source['1'] = 'Efficiency';
                    $source['2'] = 'Production';
                    
                    echo form_dropdown('Source', $source, ($Source ? $Source : "1"), 'id="Source"  class="myselect form-control form-control-rounded" data-placeholder="Select Source"  style="width:100%;"');
                ?>
              
              </div>
          </div>


          <?php
              }
          ?>


<?php
              if($submenuId==695){
          ?>
          <div class="col-12 col-sm-6">
              <div class="form-group">
              <label for="email">EB No</label>
              <input class="form-control form-control-rounded" id="itcod"  value="<?=$itcod?>" name="itcod" type="text">
              </div>
          </div>
          <div class="col-12 col-sm-6">
              <div class="form-group">
              <label for="email">Eff Upto</label>
              <input class="form-control form-control-rounded" id="srno"  value="<?=$srno?>" name="srno" type="text">
              </div>
          </div>
          <div class="col-12 col-sm-6">
              <div class="form-group">
              <label for="email">Reports Type</label>
              <?php
                    $source['1'] = 'Efficiency';
                    $source['2'] = 'Production';
                    
                    echo form_dropdown('Source', $source, ($Source ? $Source : "1"), 'id="Source"  class="myselect form-control form-control-rounded" data-placeholder="Select Source"  style="width:100%;"');
                ?>
              
              </div>
          </div>


          <?php
              }
          ?>


<?php
              if($submenuId==685){
          ?>
          <div class="col-12 col-sm-6">
              <div class="form-group">
              <label for="email">EB No</label>
              <input class="form-control form-control-rounded" id="itcod"  value="<?=$itcod?>" name="itcod" type="text">
              </div>
          </div>
          <div class="col-12 col-sm-6">
              <div class="form-group">
              <label for="email">Eff Upto</label>
              <input class="form-control form-control-rounded" id="srno"  value="<?=$srno?>" name="srno" type="text">
              </div>
          </div>
          <div class="col-12 col-sm-6">
              <div class="form-group">
              <label for="email">Reports Type</label>
              <?php
                    $source['1'] = 'Efficiency';
                    $source['2'] = 'Production';
                    
                    echo form_dropdown('Source', $source, ($Source ? $Source : "1"), 'id="Source"  class="myselect form-control form-control-rounded" data-placeholder="Select Source"  style="width:100%;"');
                ?>
              
              </div>
          </div>
 

          <?php
              }
          ?>



<?php
if ($submenuId == 650) {
    ?>
    <div class="col-12 col-sm-6">
        <div class="form-group">
            <label for="email">EB No</label>
            <input class="form-control form-control-rounded" id="itcod" value="<?= $itcod ?>" name="itcod" type="text">
        </div>
    </div>
    <div class="col-12 col-sm-6">
        <div class="form-group">
            <label for="email">No of Days</label>
            <input class="form-control form-control-rounded" id="srno" value="<?= $srno ?>" name="srno" type="text">
        </div>
    </div>
    <div class="col-12 col-sm-6">
        <div class="form-group">
            <label for="email">Reports Type</label>
            <?php
            $source['1'] = 'Only Working Days';
            $source['2'] = 'Working Days with Leave';

            echo form_dropdown('Source', $source, ($Source ? $Source : "1"), 'id="Source"  class="myselect form-control form-control-rounded" data-placeholder="Select Source"  style="width:100%;"');
            ?>

        </div>
    </div>
           <div class="col-12 col-sm-6">
              <div class="form-group">
              <label for="email">Departments</label>
              <?php
                    $de['0'] = 'ALL';
                    foreach ($departments as $dept) {
                        $de[$dept->dept_desc] = $dept->dept_desc;
                    }       
                    
                    echo form_dropdown('att_dept', $de, ($att_dept ? $att_dept : "0"), 'id="att_dept"  class="myselect form-control form-control-rounded" data-placeholder="Select Department"  style="width:100%;"');
                ?>
              
              </div>
          </div>


    <?php
}
?>

<?php
if ($submenuId == 96) {
    ?>

    <div class="col-12 col-sm-6">
        <div class="form-group">
            <label for="email">MR No</label>
              <input class="form-control form-control-rounded" id="mrno"  value="<?=$mrno?>" name="mrno" type="text">
        </div>

    </div>

    <div class="col-12 col-sm-6">
    	    <label for="purchaseDetailsPurchaseDate">Agent MR Data <span class="text-center"></span></label>
                <button name="submit" onclick="donwjuteReport1()"  type="submit" class="form-control btn btn-primary">Download</button>
            </div>
 
 
    <div class="col-12 col-sm-6">
    	    <label for="purchaseDetailsPurchaseDate">Sales Tally Data <span class="text-center"></span></label>
                <button name="submit" onclick="downjutetallysalesReport1()"  type="submit" class="form-control btn btn-primary">Download</button>
            </div>

    <div class="col-12 col-sm-6">
    	    <label for="purchaseDetailsPurchaseDate">Purchase Tally Data <span class="text-center"></span></label>
                <button name="submit" onclick="downjutetallyReport1()"  type="submit" class="form-control btn btn-primary">Download</button>
            </div>
 

            <!-- 
    <div class="col-12 col-sm-6">
        <div class="form-group">
              <button class="btn btn-primary" onclick="getmyReport()">DownGet Report</button>
        </div>
    </div>
 -->
    <div class="col-12">
           <hr class="my-2" style="height:2px; background-color: blue;"></hr>
</div>

           <div class="col-12 col-sm-6">
              <div class="form-group">
              <label for="email">Agents</label>
              <?php
                    $dea['0'] = 'ALL';
                    foreach ($agents as $agent) {
                        $dea[$agent->supp_name] = $agent->supp_name    ;
                    }       

                    echo form_dropdown('att_ag', $dea, ($att_dept ? $att_dept : "0"), 'id="att_ag"  class="myselect form-control form-control-rounded" data-placeholder="Select Agent"  style="width:100%;"');
                ?>
              
              </div>
          </div>
 
        <div class="col-12 col-sm-6">
            <div class="form-group">
                <label for="email">Gate Entry No</label>
              <input class="form-control form-control-rounded" id="srno"  value="<?=$srno?>" name="srno" type="text">
            </div>

        </div>
         

          <div class="col-12 col-sm-6">
    	    <label for="purchaseDetailsPurchaseDate">Open MR <span class="text-center"></span></label>
                <button name="submit" onclick="jutemropen()"  type="submit" class="form-control btn btn-primary">OPEN</button>
            </div>
          <div class="col-12 col-sm-6">
            </div>

    <div class="col-12">
           <hr class="my-2" style="height:2px; background-color: blue;"></hr>
</div>
  
          <div class="col-12 col-sm-6">
              <div class="form-group">
              <label for="email">Purchase Supplier(vow)</label>
              <input class="form-control form-control-rounded" id="psupvow"  value="<?=$mrno?>" name="mrno" type="text">
              </div>
          </div>

          <div class="col-12 col-sm-6">
              <div class="form-group">
              <label for="email">Purchase Supplier(Tally)</label>
              <input class="form-control form-control-rounded" id="psuptally"  value="<?=$mrno?>" name="mrno" type="text">
              </div>
          </div>

          <div class="col-12 col-sm-6">
              <div class="form-group">
              <label for="email">Sales Supplier(vow)</label>
              <input class="form-control form-control-rounded" id="psalevow"  value="<?=$mrno?>" name="mrno" type="text">
              </div>
          </div>

          <div class="col-12 col-sm-6">
              <div class="form-group">
              <label for="email">Sales Supplier(Tally)</label>
              <input class="form-control form-control-rounded" id="psaletally"  value="<?=$mrno?>" name="mrno" type="text">
              </div>
          </div>

           <div class="col-12 col-sm-6">
              <div class="form-group">
              <label for="email">Quality(Vow)</label>
              <?php
                    $deq['0'] = 'ALL';
                    foreach ($jutequalitys as $jutequality) {
                        $deq[$jutequality->jute_quality] = $jutequality->jute_quality    ;
                    }       

                    echo form_dropdown('att_jqc', $deq, ($att_dept ? $att_dept : "0"), 'id="att_jqc"  class="myselect form-control form-control-rounded" data-placeholder="Select Quality"  style="width:100%;"');
                ?>
              
              </div>
          </div>
 
        <div class="col-12 col-sm-6">
            <div class="form-group">
                <label for="email">Quality(Tally)</label>
              <input class="form-control form-control-rounded" id="att_jtqty"  value="<?=$att_jtqty?>" name="att_jtqty" type="text">
            </div>

        </div>
          <div class="col-12 col-sm-6">
    	    <label for="purchaseDetailsPurchaseDate">Update Link <span class="text-center"></span></label>
                <button name="submit" onclick="jutevowtally()"  type="submit" class="form-control btn btn-primary">Update</button>
            </div>
          <div class="col-12 col-sm-6">
    	    <label for="purchaseDetailsPurchaseDate">List of Links <span class="text-center"></span></label>
                <button name="submit" onclick="jutevowtallylist()"  type="submit" class="form-control btn btn-primary">List</button>
            </div>


    <?php
}
?>





<?php
if ($submenuId == 684) {
    ?>
     <div class="col-12 col-sm-6">

     <div class="form-group">
            <label for="email">Deviation Type</label>
            <?php
            $source['1'] = 'All Designations';
            $source['2'] = 'Designation with Short/Excess';
            echo form_dropdown('Source', $source, ($Source ? $Source : "1"), 'id="Source"  class="myselect form-control form-control-rounded" data-placeholder="Select Source"  style="width:100%;"');
            ?>

        </div>

    </div>

    <div class="col-12 col-sm-6">
        <div class="form-group">
            <label for="email">Departments</label>
            <?php
            $de['0'] = 'ALL';
            foreach ($mdepartments as $dept) {
                $de[$dept->dept_desc] = $dept->dept_desc;
            }

            echo form_dropdown('att_dept', $de, ($att_dept ? $att_dept : "0"), 'id="att_dept"  class="myselect form-control form-control-rounded" data-placeholder="Select Department"  style="width:100%;"');
            ?>

        </div>
    </div>


    <?php
}
?>


<?php
if ($submenuId == 679) {
    ?>
          <div class="col-12 col-sm-6">
              <div class="form-group">
              <label for="email">EB No</label>
              <input class="form-control form-control-rounded" id="eb_no"  value="<?=$eb_no?>" name="eb_no" type="text">
              </div>
          </div>
          <div class="col-12 col-sm-6">
              <div class="form-group">
              <label for="email">Loom Side</label>
              <?php
                    $source['1'] = 'Hessian';
                    $source['2'] = 'Sacking';
                    
                    echo form_dropdown('Source', $source, ($Source ? $Source : "1"), 'id="Source"  class="myselect form-control form-control-rounded" data-placeholder="Select Source"  style="width:100%;"');
                ?>
              
              </div>
          </div>
          <?php
              }
          ?>

<?php
              if($submenuId==680){
          ?>
          <div class="col-12 col-sm-6">
              <div class="form-group">
              <label for="email">EB No</label>
              <input class="form-control form-control-rounded" id="eb_no"  value="<?=$eb_no?>" name="eb_no" type="text">
              </div>
          </div>
          <div class="col-12 col-sm-6">
              <div class="form-group">
              <label for="email">Fine/Coarse</label>
              <?php
                    $source['8'] = 'Fine';
                    $source['9'] = 'Coarse';
                    
                    echo form_dropdown('Source', $source, ($Source ? $Source : "8"), 'id="Source"  class="myselect form-control form-control-rounded" data-placeholder="Select Source"  style="width:100%;"');
                ?>
              
              </div>
          </div>
          <?php
              }
          ?>



        <?php
              if($submenuId==92){
          ?>
          <div class="col-12 col-sm-6">
              <div class="form-group">
              <label for="email">Jute Summary Type</label>
              <?php
                    $suma['1'] = 'Issue Summary';
                    $suma['2'] = 'Receipt Summary';
                    
                    echo form_dropdown('jutesummary', $suma, ($jutesummary ? $jutesummary : "1"), 'id="jutesummary"  class="myselect form-control form-control-rounded" data-placeholder="Select Jute Summary Type"  style="width:100%;"');
                ?>
              
              </div>
          </div>
          <div class="col-12 col-sm-6">
              <div class="form-group">
              <label for="email">Mr No.</label>
              <input class="form-control form-control-rounded" id="mrno"  value="<?=$mrno?>" name="mrno" type="text">
              </div>
          </div>
          <?php
              }
          ?>
          <?php
              if($submenuId==185){
          ?>
          <div class="col-12 col-sm-6">
              <div class="form-group">
              <label for="email">Item Code</label>
              <input class="form-control form-control-rounded" id="itcod"  value="<?=$itcod?>" name="itcod" type="text">
              </div>
          </div>

          <div class="col-12 col-sm-6">
              <div class="form-group">
              <label for="email">Cost Center</label>
              <input class="form-control form-control-rounded" id="costcenter"  value="<?=$costcenter?>" name="costcenter" type="text">
              
              </div>
          </div>

          <div class="col-12 col-sm-6">
              <div class="form-group">
              <label for="email">Item Desc</label>
              <input class="form-control form-control-rounded" id="itemdesc"  value="<?=$itemdesc?>" name="itemdesc" type="text">
              
              </div>
          </div>

        <?php
              }
          ?>
        <?php
              if($submenuId==253){
          ?>
          <div class="col-12 col-sm-6">
              <div class="form-group">
              <label for="email">Item Code</label>
              <input class="form-control form-control-rounded" id="itcod"  value="<?=$itcod?>" name="itcod" type="text" value='1'>
              </div>
          </div>

        <?php
              }
          ?>
        <?php
              if($submenuId==415){
          ?>
          <div class="col-12 col-sm-6">
              <div class="form-group">
              <label for="email">Item Code</label>
              <input class="form-control form-control-rounded" id="itcod"  value="<?=$itcod?>" name="itcod" type="text" value='1'>
              </div>
          </div>
          <div class="col-12 col-sm-6">
              <div class="form-group">
              <label for="email">Source</label>
              <?php
                    $source['0'] = 'ALL';
                    $source['1'] = 'Negative';
                    $source['2'] = 'Slow Moved';
                    $source['3'] = 'Un Moved';                   
                    
                    echo form_dropdown('Source', $source, ($Source ? $Source : "0"), 'id="Source"  class="myselect form-control form-control-rounded" data-placeholder="Select Source"  style="width:100%;"');
                ?>
              
              </div>
          </div>
  

        <?php
              }
          ?>

<?php
              if($submenuId==155){
          ?>
          <div class="col-12 col-sm-6">
              <div class="form-group">
              <label for="email">Item Code</label>
              <input class="form-control form-control-rounded" id="itcod"  value="<?=$itcod?>" name="itcod" type="text" value='1'>
              </div>
          </div>
          <div class="col-12 col-sm-6">
              <div class="form-group">
              <label for="email">Item Desc</label>
              <input class="form-control form-control-rounded" id="itemdesc"  value="<?=$itemdesc?>" name="itemdesc" type="text" value=''>
              </div>
          </div>

        <?php
              }
          ?>





        <?php
              if($submenuId==499){
          ?>
          <div class="col-12 col-sm-6">
              <div class="form-group">
              <label for="email">Item Code</label>
              <input class="form-control form-control-rounded" id="itcod"  value="<?=$itcod?>" name="itcod" type="text" value='1'>
              </div>
          </div>
          <div class="col-12 col-sm-6">
              <div class="form-group">
              <label for="email">Item Desc</label>
              <input class="form-control form-control-rounded" id="itemdesc"  value="<?=$itemdesc?>" name="itemdesc" type="text" >
              
              </div>
          </div>

        <?php
              }
          ?>
          <?php
              if($submenuId==663){
          ?>
          <div class="col-12 col-sm-6">
              <div class="form-group">
              <label for="email">Item Code</label>
              <input class="form-control form-control-rounded" id="itcod"  value="<?=$itcod?>" name="itcod" type="text">
              </div>
          </div>
          <div class="col-12 col-sm-6">
              <div class="form-group">
              <label for="email">Item Desc</label>
              <input class="form-control form-control-rounded" id="itemdesc"  value="<?=$itemdesc?>" name="itemdesc" type="text">
              </div>
           </div>
           <div class="col-12 col-sm-6">
              <div class="form-group">
              <label for="email">Supplier Name</label>
              <input class="form-control form-control-rounded" id="suppname"  value="<?=$suppname?>" name="suppname" type="text">
              
              </div>
          </div>

          
        <?php
              }
          ?>
          <?php
              if($submenuId==532){
          ?>
           <div class="col-12 col-sm-6">
              <div class="form-group">
              <label for="email">Supplier Name</label>
              <input class="form-control form-control-rounded" id="suppname"  value="<?=$suppname?>" name="suppname" type="text">
              
              </div>
          </div>
          <div class="col-12 col-sm-6">
              <div class="form-group">
              <label for="email">SR No</label>
              <input class="form-control form-control-rounded" id="srno"  value="<?=$srno?>" name="srno" type="text">
              </div>
          </div>

          
        <?php
              }
          ?>

      <?php
              if($submenuId==93){
          ?>
          <div class="col-12 col-sm-12">
              <div class="form-group">

              <label for="email">Select Godown No</label>
              <?php
                    $reemp[''] = '';
                    foreach ($godowns as $god) {
                        $reemp[$god->id] = $god->address;
                    }
                    echo form_dropdown('godownno', $reemp, ($godownno ? $godownno : ""), 'id="godownno"  class="myselect form-control form-control-rounded" data-placeholder="Select Godown No"  style="width:100%;"');
                ?>
              
              </div>
          </div>
          <?php
              }
          ?>

        <?php
              if($submenuId==544){
          ?>
          <div class="col-12 col-sm-6">
              <div class="form-group">
              <label for="email">Branch's</label>
              <?php
                   // $bra['0'] = 'ALL';
                    foreach ($branchs as $branch) {
                        $bra[$branch->branch_id] = $branch->branch_name. "(" . $branch->branch_address .")";
                    }       
                    
                    echo form_dropdown('branch_id', $bra, ($branch_id ? $branch_id : "0"), 'id="branch_id"  class="myselect form-control form-control-rounded" data-placeholder="Select Branch"  style="width:100%;"');
                ?>
              
              </div>
          </div>
          <div class="col-12 col-sm-6">
              <div class="form-group">
              <label for="email">Pay Scheme</label>
              <?php
                    foreach ($payschemes as $payscheme) {
                        $pbra[$payscheme->ID] = $payscheme->NAME;
                    }       
                    
                    echo form_dropdown('payscheme_id', $pbra, ($payscheme_id ? $payscheme_id : "0"), 'id="payscheme_id"  class="myselect form-control form-control-rounded" data-placeholder="Select PaySheme"  style="width:100%;"');
                ?>
              
              </div>
          </div>

          
        <?php
              }
          ?>

<!--  start -->

        <?php
              if($submenuId==651) {
                ?>
          <div class="col-12 col-sm-6">
              <div class="form-group">
              <label for="email">Status</label>
              <?php
                    
                    $st['0'] = 'ALL';
                    foreach ($status as $stat) {
                        $st[$stat->status_id] = $stat->status_name;
                    }       
                    
                    echo form_dropdown('att_status', $st, ($att_type ? $att_type : "0"), 'id="att_status"  class="myselect form-control form-control-rounded" data-placeholder="Select Status"  style="width:100%;"');
                ?>
              
              </div>
          </div>
          <div class="col-12 col-sm-6">
              <div class="form-group">
              <label for="email">Departments</label>
              <?php
                    $de['0'] = 'ALL';
                    foreach ($departments as $dept) {
                        $de[$dept->dept_desc] = $dept->dept_desc;
                    }       
                    
                    echo form_dropdown('att_dept', $de, ($att_dept ? $att_dept : "0"), 'id="att_dept"  class="myselect form-control form-control-rounded" data-placeholder="Select Department"  style="width:100%;"');
                ?>
              
              </div>
          </div>
          <div class="col-12 col-sm-6">
              <div class="form-group">
              <label for="email">Designation</label>
              <?php
                    $deg['0'] = 'ALL';
                    foreach ($designations as $desig) {
                        $deg[$desig->desig] = $desig->desig;
                    }       
                    
                    echo form_dropdown('att_desig', $deg, ($att_desig ? $att_desig : "0"), 'id="att_desig"  class="myselect form-control form-control-rounded" data-placeholder="Select Designation"  style="width:100%;"');
                ?>
              
              </div>
          </div>
          <div class="col-12 col-sm-6">
              <div class="form-group">
              <label for="email">EB.No</label>
              <input class="form-control form-control-rounded" id="eb_no"  value="<?=$eb_no?>" name="eb_no" type="text">
              
              </div>
          </div>
          <div class="col-12 col-sm-6">
              <div class="form-group">
              <label for="email">Category</label>
              <?php
                    $ca['0'] = 'ALL';
                    foreach ($categorys as $cat) {
                        $ca[$cat->cata_id] = $cat->cata_desc;
                    }       
                    
                    echo form_dropdown('att_cat', $ca, ($att_cat ? explode(',', $att_cat) : "0"), 'id="att_cat"  class="myselect form-control form-control-rounded" data-placeholder="Select Category"  style="width:100%;"');
                ?>
              
              </div>
          </div>
     
          <div class="col-12 col-sm-6">
              <div class="form-group">
              <label for="email">Esi No</label>
              <input class="form-control form-control-rounded" id="itcod"  value="<?=$itcod?>" name="itcod" type="text">
              </div>
          </div>
          <div class="col-12 col-sm-6">
              <div class="form-group">
              <label for="email">PF No</label>
              <input class="form-control form-control-rounded" id="srno"  value="<?=$srno?>" name="srno" type="text">
              </div>
          </div>
    <div class="col-12 col-sm-6">
        <div class="form-group">
            <label for="email">Uan No</label>
              <input class="form-control form-control-rounded" id="mrno"  value="<?=$mrno?>" name="mrno" type="text">
        </div>
    </div>
          <div class="col-12 col-sm-6">
              <div class="form-group">
              <label for="email">Bank Acc No</label>
              <input class="form-control form-control-rounded" id="itemdesc"  value="<?=$itemdesc?>" name="itemdesc" type="text">
              </div>
           </div>
          <div class="col-12 col-sm-6">
              <div class="form-group">
              <label for="email">Active/Inactive</label>
              <?php
                    $suma['1'] = 'Active';
                    $suma['2'] = 'Inactive';
                    
                    echo form_dropdown('jutesummary', $suma, ($jutesummary ? $jutesummary : "1"), 'id="jutesummary"  class="myselect form-control form-control-rounded" data-placeholder="Select Jute Summary Type"  style="width:100%;"');
                ?>
              
              </div>
          </div>
          <?php
            }
          ?>

          
<!--  end -->



        <?php
              if(($submenuId==603) || ($submenuId==604) || ($submenuId==657)){
                ?>

          <div class="col-12 col-sm-6">
              <div class="form-group">
              <label for="email">Source</label>
              <?php
                    $source['0'] = 'ALL';
                    $source['1'] = 'Manual';
                    $source['2'] = 'Facial';
                    $source['3'] = 'Logs';                   
                    
                    echo form_dropdown('Source', $source, ($Source ? $Source : "0"), 'id="Source"  class="myselect form-control form-control-rounded" data-placeholder="Select Source"  style="width:100%;"');
                ?>
              
              </div>
          </div>
          
          <div class="col-12 col-sm-6">
              <div class="form-group">
              <label for="email">Type</label>
              <?php
                    $type['0'] = 'ALL';
                    $type['R'] = 'Regular';
                    $type['O'] = 'OT';
                    $type['C'] = 'Cash';                   
                    
                    echo form_dropdown('att_type', $type, ($att_type ? $att_type : "0"), 'id="att_type"  class="myselect form-control form-control-rounded" data-placeholder="Select Type"  style="width:100%;"');
                ?>
              
              </div>
          </div>
          <?php
                if(($submenuId==603)  || ($submenuId==657)){ 
                  ?>
          <div class="col-12 col-sm-6">
              <div class="form-group">
              <label for="email">Status</label>
              <?php
                    
                    $st['0'] = 'ALL';
                    foreach ($status as $stat) {
                        $st[$stat->status_id] = $stat->status_name;
                    }       
                    
                    echo form_dropdown('att_status', $st, ($att_type ? $att_type : "0"), 'id="att_status"  class="myselect form-control form-control-rounded" data-placeholder="Select Status"  style="width:100%;"');
                ?>
              
              </div>
          </div>
          <?php
            }
          ?>
          <div class="col-12 col-sm-6">
              <div class="form-group">
              <label for="email">Departments</label>
              <?php
                    $de['0'] = 'ALL';
                    foreach ($departments as $dept) {
                        $de[$dept->dept_desc] = $dept->dept_desc;
                    }       
                    
                    echo form_dropdown('att_dept', $de, ($att_dept ? $att_dept : "0"), 'id="att_dept"  class="myselect form-control form-control-rounded" data-placeholder="Select Department"  style="width:100%;"');
                ?>
              
              </div>
          </div>
          <div class="col-12 col-sm-6">
              <div class="form-group">
              <label for="email">Designation</label>
              <?php
                    $deg['0'] = 'ALL';
                    foreach ($designations as $desig) {
                        $deg[$desig->desig] = $desig->desig;
                    }       
                    
                    echo form_dropdown('att_desig', $deg, ($att_desig ? $att_desig : "0"), 'id="att_desig"  class="myselect form-control form-control-rounded" data-placeholder="Select Designation"  style="width:100%;"');
                ?>
              
              </div>
          </div>
          
          <div class="col-12 col-sm-6">
              <div class="form-group">
              <label for="email">Spell</label>
              <?php
                    $sp['0'] = 'ALL';
                    foreach ($spells as $spel) {
                        $sp[$spel->spell_name] = $spel->spell_name;
                    }       
                    
                    echo form_dropdown('att_spells', $sp, ($att_spells ? $att_spells : "0"), 'id="att_spells"  class="myselect form-control form-control-rounded" data-placeholder="Select Spell"  style="width:100%;"');
                ?>
              
              </div>
          </div>

          <?php
            if($submenuId==604){
          ?>
          <div class="col-12 col-sm-6">
              <div class="form-group">
              <label for="email">Mark/Hours</label>
              <?php
                    
                    $st['1'] = 'Mark';
                    $st['2'] = 'Hours';                       
                    
                    echo form_dropdown('att_mark_hrs', $st, ($att_type ? $att_type : "0"), 'id="att_mark_hrs"  class="myselect form-control form-control-rounded" data-placeholder="Select Marks/Hrs"  style="width:100%;"');
                ?>
              
              </div>
          </div>
          <?php
            }
          ?>
          
          <div class="col-12 col-sm-6">
              <div class="form-group">
              <label for="email">EB.No</label>
              <input class="form-control form-control-rounded" id="eb_no"  value="<?=$eb_no?>" name="eb_no" type="text">
              
              </div>
          </div>
          <?php
              }
          ?>

          <?php
            if(($submenuId==607)){
          ?>
          <div class="col-12 col-sm-6">
              <div class="form-group">
              <label for="email">Departments</label>
              <?php
                    $de['0'] = 'ALL';
                    foreach ($departments as $dept) {
                        $de[$dept->dept_id] = $dept->dept_desc;
                    }       
                    
                    echo form_dropdown('att_dept[]', $de, ($att_dept ? explode(',', $att_dept) : "0"), 'id="att_dept"  class="myselect form-control form-control-rounded" multiple data-placeholder="Select Department"  style="width:100%;"');
                ?>
              
              </div>
          </div>
          <div class="col-12 col-sm-6">
              <div class="form-group">
              <label for="email">Spell</label>
              <?php
                    $sp['0'] = 'ALL';
                    foreach ($spells as $spel) {
                        $sp[$spel->spell_name] = $spel->spell_name;
                    }       
                    
                    echo form_dropdown('att_spells', $sp, ($att_spells ? $att_spells : "0"), 'id="att_spells"  class="myselect form-control form-control-rounded" data-placeholder="Select Spell"  style="width:100%;"');
                ?>
              
              </div>
          </div>
          <?php
              }
          ?>

          <?php
            if(($submenuId==601) || ($submenuId==559)){
          ?>
          <div class="col-12 col-sm-6">
              <div class="form-group">
              <label for="email">Departments</label>
              <?php
                    $de['0'] = 'ALL';
                    foreach ($departments as $dept) {
                        $de[$dept->dept_id] = $dept->dept_desc;
                    }       
                    
                    echo form_dropdown('att_dept[]', $de, ($att_dept ? explode(',', $att_dept) : "0"), 'id="att_dept"  class="myselect form-control form-control-rounded" multiple data-placeholder="Select Department"  style="width:100%;"');
                ?>
              
              </div>
          </div>
          <div class="col-12 col-sm-6">
              <div class="form-group">
              <label for="email">Occupation</label>
              <?php
                    $deg['0'] = 'ALL';
                    foreach ($designations as $desig) {
                        $deg[$desig->id] = $desig->desig;
                    }       
                    
                    echo form_dropdown('att_desig[]', $deg, ($att_desig ? explode(',', $att_desig) : "0"), 'id="att_desig"  class="myselect form-control form-control-rounded" multiple data-placeholder="Select Designation"  style="width:100%;"');
                ?>
              
              </div>
          </div>
          
          <?php
          if(($submenuId==601) ){
            ?>
          <div class="col-12 col-sm-6">
              <div class="form-group">
              <label for="email">Spell</label>
              <?php
                    $sp['0'] = 'ALL';
                    foreach ($spells as $spel) {
                        $sp[$spel->spell_name] = $spel->spell_name;
                    }       
                    
                    echo form_dropdown('att_spells', $sp, ($att_spells ? $att_spells : "0"), 'id="att_spells"  class="myselect form-control form-control-rounded" data-placeholder="Select Spell"  style="width:100%;"');
                ?>
              
              </div>
          </div>
          <?php
            }
              }
          ?>
          <?php
//sabir
        if($submenuId==610){
          ?>
          <div class="col-12 col-sm-6">
              <div class="form-group">
              <label for="email">EB.No</label>
              <input class="form-control form-control-rounded" id="eb_no"  value="<?=$eb_no?>" name="eb_no" type="text">
              
              </div>
          </div>
           
          <div class="col-12 col-sm-6">
              <div class="form-group">
              <label for="email">Departments</label>
              <?php
                    $de['0'] = 'ALL';
                    foreach ($departments as $dept) {
                        $de[$dept->dept_id] = $dept->dept_desc;
                    }       
                    
                    echo form_dropdown('att_dept', $de, ($att_dept ? explode(',', $att_dept) : "0"), 'id="att_dept"  class="myselect form-control form-control-rounded" data-placeholder="Select Department"  style="width:100%;"');
                ?>
              
              </div>
          </div>
          <div class="col-12 col-sm-6">
              <div class="form-group">
              <label for="email">Occupation</label>
              <?php
                    $deg['0'] = 'ALL';
                    foreach ($designations as $desig) {
                        $deg[$desig->id] = $desig->desig;
                    }       
                    
                    echo form_dropdown('att_desig', $deg, ($att_desig ? explode(',', $att_desig) : "0"), 'id="att_desig"  class="myselect form-control form-control-rounded" data-placeholder="Select Designation"  style="width:100%;"');
                ?>
              
              </div>
          </div>
          <div class="col-12 col-sm-6">
              <div class="form-group">
              <label for="email">Category</label>
              <?php
                    $ca['0'] = 'ALL';
                    foreach ($categorys as $cat) {
                        $ca[$cat->cata_id] = $cat->cata_desc;
                    }       
                    
                    echo form_dropdown('att_cat', $ca, ($att_cat ? explode(',', $att_cat) : "0"), 'id="att_cat"  class="myselect form-control form-control-rounded" data-placeholder="Select Category"  style="width:100%;"');
                ?>
              
              </div>
          </div>
          <div class="col-12 col-sm-6">
              <div class="form-group">
              <label for="email">Spell</label>
              <?php
                    $sp['0'] = 'ALL';
                    foreach ($spells as $spel) {
                        $sp[$spel->spell_name] = $spel->spell_name;
                    }       
                    
                    echo form_dropdown('att_spells', $sp, ($att_spells ? $att_spells : "0"), 'id="att_spells"  class="myselect form-control form-control-rounded" data-placeholder="Select Spell"  style="width:100%;"');
                ?>
              
              </div>
          </div>
          <div class="col-12 col-sm-6">
              <div class="form-group">
              <label for="email">Continue/Page Braak </label>
              <?php
                    $type['B'] = 'Shift Break';
                 //   $type['D'] = 'Department Break';
                    $type['C'] = 'Continuous';
                     
                    echo form_dropdown('att_type', $type, ($att_type ? $att_type : "0"), 'id="att_type"  class="myselect form-control form-control-rounded" data-placeholder="Select Type"  style="width:100%;"');
                ?>
              
              </div>
          </div>

         <?php
            }
//sabir
    ?>



          <?php
            if($submenuId==534){
          ?>
          <div class="col-12 col-sm-6">
              <div class="form-group">
              <label for="email">Branch's</label>
              <?php
                    $bra['0'] = 'ALL';
                    foreach ($branchs as $branch) {
                        $bra[$branch->branch_id] = $branch->branch_name. "(" . $branch->branch_address .")";
                    }       
                    
                    echo form_dropdown('branch_id', $bra, ($branch_id ? $branch_id : "0"), 'id="branch_id"  class="myselect form-control form-control-rounded" data-placeholder="Select Branch"  style="width:100%;"');
                ?>
              
              </div>
          </div>
          <div class="col-12 col-sm-6">
              <div class="form-group">
              <label for="email">Component's</label>
              <?php
                    $compo['0'] = 'ALL';
                    foreach ($componets as $componet) {
                        $compo[$componet->ID] = $componet->NAME. "(" . $componet->CODE .")";
                    }       
                    
                    echo form_dropdown('componet_id', $compo, ($componet_id ? $componet_id : "21"), 'id="componet_id"  class="myselect form-control form-control-rounded" data-placeholder="Select Branch"  style="width:100%;"');
                ?>
              
              </div>
          </div>
          <?php
            }
          ?>
          
          
          
      </div>
    </div>
    <div class="modal-footer">
      <button class="btn btn-danger" onclick="close_pop()">Close</button>
      <button class="btn btn-primary" onclick="getReportType(4,'<?=$mainmenuId?>')">Get Report</button>
    </div>
  </div>

</div>


  </body>
  
 <script>
  
     $(document).ready(function() {
      $('.myselect').select2({minimumResultsForSearch: 5});
    // var table = $('#example').DataTable( {
    //     responsive: true
    // } );
 
    // new $.fn.dataTable.FixedHeader( table );

    // Get the modal
    var modal = document.getElementById("myModal");

    // Get the button that opens the modal
    var btn = document.getElementById("myBtn");

    // Get the <span> element that closes the modal
    var span = document.getElementsByClassName("close")[0];

    // When the user clicks the button, open the modal 
    btn.onclick = function() {
    modal.style.display = "block";
    }

    // When the user clicks on <span> (x), close the modal
    span.onclick = function() {
    modal.style.display = "none";
    }

    // When the user clicks anywhere outside of the modal, close it
    window.onclick = function(event) {
        if (event.target == modal) {
            modal.style.display = "none";
        }
    }
    } );
    
   function close_pop(){
    var modal = document.getElementById("myModal");
    modal.style.display = "none";
   }
    

    function getReport(mainmenuId, submenuId, type){
      // alert(mainmenuId);
      var controller = '<?=$controller?>';
      var company = $('#company').val();
      if(!company){
        // alert("Sorry! Select Company");'reports_jute/report/')
        return false;
      }
      
      window.location.href = '<?=base_url()?>'+controller+'/report/'+mainmenuId+'/'+submenuId+'/'+company;

    }

    function getChangeCompany(compId){

      var menuId = $('#mainmenu').val();
     
      
      $.ajax({
            url : "<?php echo base_url('welcome/ChangeCompany'); ?>",
            data : {compId:compId, menuId: menuId},
            type: "GET",
            dataType: "html",
            success: function(data)
            {
              window.location.href = '<?=base_url()?>';                 
            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                alert('Database Error : ' + errorThrown);
            }
        });
    }

    function getChangeMenu(menuId){

        var compId = $('#company').val();      
        
        $.ajax({
              url : "<?php echo base_url('welcome/ChangeCompany'); ?>",
              data : {compId:compId, menuId: menuId},
              type: "GET",
              dataType: "html",
              success: function(data)
              {
                window.location.href = '<?=base_url()?>';                 
              },
              error: function (jqXHR, textStatus, errorThrown)
              {
                  alert('Database Error : ' + errorThrown);
              }
          });
        }

    // function getReportType(type, mainmenuId){
    //   var company = $('#company').val();
    //   var submenuId = $('#report').val();
    //   if(!company){
    //     alert("Sorry! Select Company");
    //     return false;
    //   }
    //   if(!submenuId){
    //     alert("Sorry! Select Report");
    //     return false;
    //   }

    //   // window.location.href = '<?=base_url('reports_jute/reporttype/')?>'+mainmenuId+'/'+submenuId+'/'+company+'/'+type;





    // }

    function getReportType(type, mainmenuId){
      var company = $('#company').val();
      var submenuId = ($('#report').val() ? $('#report').val() :10000);
      var fromdt = $('#fromdt').val();
      var todt = $('#todt').val();
      var jutesummary = $('#jutesummary').val();
      var mrno = $('#mrno').val();
      var godownno = $('#godownno').val();
      var Source = $('#Source').val();
      var att_type = $('#att_type').val();
      var att_status = $('#att_status').val();
      var att_dept = $('#att_dept').val();
      var att_desig = $('#att_desig').val();
      var att_spells = $('#att_spells').val();
      var att_mark_hrs = $('#att_mark_hrs').val();
      var branch_id = $('#branch_id').val();
      var payscheme_id = $('#payscheme_id').val();
      var att_worktype = $('#att_worktype').val();
      var att_cat = $('#att_cat').val();
      var componet_id = $('#componet_id').val();      
      var eb_no = $('#eb_no').val();
      var itcod = $('#itcod').val();
      var costcenter = $('#costcenter').val();
      var itemdesc = $('#itemdesc').val();
      var suppname = $('#suppname').val();
      var srno = $('#srno').val();
      
      var controller = '<?=$controller?>';
 //     console.log('line ' + $('#itcod').val());
//console.log('eff ' + srno);
//      echo fromdt.'-'.$todt;
//alert(Source);
      if(!company){
        alert("Sorry! Select Company");
        return false;
      }
      if(!submenuId){
        alert("Sorry! Select Report");
        return false;
      }
//      var fromdt = '2023-10-03';
      
      // if(!fromdt){
      //   alert("Sorry! Select From Date");
      //   return false;
      // }

      // if(!todt){
      //   alert("Sorry! Select To Date");
      //   return false;
      // }

      

 
      $('#type').val(type);
      $('#companyId').val(company);
      $('#mainmenu').val(mainmenuId);
      $('#submenu').val(submenuId);
      $('#from_date').val(fromdt);
      $('#to_date').val(todt);
      $('#jute_summary').val(jutesummary);
      $('#mr_no').val(mrno);
      $('#godown_no').val(godownno);
      $('#Source_att').val(Source);
      $('#Source').val(Source);
      $('#att_type_att').val(att_type);
      $('#att_status_att').val(att_status);
      $('#att_dept_att').val(att_dept);
      $('#att_desig_att').val(att_desig);
      $('#att_spells_att').val(att_spells);
      $('#eb_no_att').val(eb_no);
      $('#att_mark_hrs_att').val(att_mark_hrs);
      $('#att_worktype_att').val(att_worktype);
      $('#att_cat_att').val(att_cat);
      $('#att_branch_id').val(branch_id);
      $('#payscheme_chk').val(payscheme_id);
      $('#att_componet_id').val(componet_id);
      $('#costcenter_chk').val(costcenter);
      $('#itcode_chk').val(itcod);
      $('#itemdesc_chk').val(itemdesc);
      $('#suppname_chk').val(suppname);
      $('#srno_chk').val(srno);
      var icode = $('#payscheme_chk').val();
//      echo 'eff up '.$('#costcenter_chk').val();
//echo srno;
      $('#form_report').submit();

      
      
      



    }


    function donwjuteReport1(){
        //    alert('hi');
              event.preventDefault(); 
	          var opt=3;
                 event.preventDefault();     
                periodfromdate = $('#fromdt').val();
                periodtodate = $('#todt').val();
//              var fromdt = $('#fromdt').val();
//                var todt = $('#todt').val();
        
//                exportdbfdata
//02-03-2024 anotherFunction

var url = '<?php echo site_url("Ejmprocessdata/donwjuteReport1"); ?>' +
                      '?periodfromdate=' + periodfromdate
                      + '&periodtodate=' + periodtodate;
               //       alert(url);
			//$(location).attr('href',url);
			window.open( url, '_blank');
			
			
return false;


    }    



function downjutetallyReport1(){
        //    alert('hi');
              event.preventDefault(); 
	          var opt=3;
                 event.preventDefault();     
                periodfromdate = $('#fromdt').val();
                periodtodate = $('#todt').val();
//              var fromdt = $('#fromdt').val();
//                var todt = $('#todt').val();
        
//                exportdbfdata
//02-03-2024 anotherFunction

var url = '<?php echo site_url("Ejmprocessdata/downjutetallyReport1"); ?>' +
                      '?periodfromdate=' + periodfromdate
                      + '&periodtodate=' + periodtodate;
               //       alert(url);
			//$(location).attr('href',url);
			window.open( url, '_blank');
			
			
return false;


    }    

function downjutetallysalesReport1(){
        //    alert('hi');
              event.preventDefault(); 
	          var opt=3;
                 event.preventDefault();     
                periodfromdate = $('#fromdt').val();
                periodtodate = $('#todt').val();
//              var fromdt = $('#fromdt').val();
//                var todt = $('#todt').val();
        
//                exportdbfdata
//02-03-2024 anotherFunction

var url = '<?php echo site_url("Ejmprocessdata/downjutetallysalesReport1py"); ?>' +
                      '?periodfromdate=' + periodfromdate
                      + '&periodtodate=' + periodtodate;
               //       alert(url);
			//$(location).attr('href',url);
			window.open( url, '_blank');
			
			
return false;


    }  
    
    

function jutevowtally(){
          event.preventDefault(); 
          var pupdno=1;
          var supdno=1;
          var qupdno=1;

          var psupvow= $('#psupvow').val();
          var psuptally=$('#psuptally').val();  
//          alert(psupvow);

        if ((psupvow.trim().length == 0) || (psuptally.trim().length == 0)) {
            pupdno=0;
        }

     // alert ('purchase' + pupdno);
          var psalevow= $('#psalevow').val();
          var psaletally=$('#psaletally').val();
           if ((psalevow.trim().length == 0) || (psaletally.trim().length == 0)) {
            supdno=0;             
          }
      //   alert ('sales' + supdno);
          
          var att_jcqty= $('#att_jqc').val();
          var att_jtqty=$('#att_jtqty').val();

        if (att_jcqty.trim().length == 0 || att_jtqty.trim().length == 0) {
            qupdno=0;
        }
    //    alert('qty' + qupdno+'='+att_jcqty+'-'+att_jtqty);
        if (pupdno+supdno+qupdno==0) {
            alert('Please Enter any Vow/Tally Details');
            return;
        }

        $.ajax({
          url: "<?php echo base_url('Njmwagesprocess/jutevowtally'); ?>",
          type: "POST",
          data: {psupvow : psupvow,psuptally: psuptally,psalevow: psalevow,psaletally: psaletally,att_jcqty: att_jcqty,att_jtqty: att_jtqty},
          dataType: "json",
          success: function(response) {
           

            var savedata=(response.savedata);
              if (response.success) {
                  alert(savedata);
                   $('#record_id').val(0);
  //                 hideSpinnerCounter();
 
            } else {
    //               hideSpinnerCounter();
                   alert('No Data');
                 
      
              }
          }
      });




    }

function jutemropen(){
          event.preventDefault(); 
          var gateentryno= $('#srno').val();
          var agent=$('#att_ag').val();  
 //     refreshDataTable();

          alert(agent);  
          if (agent == 0) {
              alert('Please Select Agent');
              return;
         }

          if (parseInt(agent == 0)) {
              alert('Please Select Gate Entry No');
              return;
         }


//              showSpinnerCounter();
        $.ajax({
          url: "<?php echo base_url('Njmwagesprocess/jutemropen'); ?>",
          type: "POST",
          data: {gateentryno : gateentryno,agent: agent},
          dataType: "json",
          success: function(response) {
           

            var savedata=(response.savedata);
              if (response.success) {
                  alert(savedata);
                   $('#record_id').val(0);
  //                 hideSpinnerCounter();
 
            } else {
    //               hideSpinnerCounter();
                   alert('No Data');
                 
      
              }
          }
      });
    }


function jutevowtallylist(){
        //    alert('hi');
              event.preventDefault(); 
	          var opt=3;
                 event.preventDefault();     
                periodfromdate = $('#fromdt').val();
                periodtodate = $('#todt').val();
 
var url = '<?php echo site_url("Ejmprocessdata/jutevowtallylist"); ?>' +
                      '?periodfromdate=' + periodfromdate
                      + '&periodtodate=' + periodtodate;
               //       alert(url);
			//$(location).attr('href',url);
			window.open( url, '_blank');
			
			
return false;


    }  
    






</script>





</html>