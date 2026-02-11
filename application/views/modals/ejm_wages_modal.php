        <div id="ejmModal" class="modal">
            <div class="modal-content">
      
            <div class="modal-header">
            <h4 class="modal-title modal-title-center" id="ejmModalLabel">Ejm Wages Data Preparation</h4>
            <button type="button" id="ejmclosebtnsa" onclick="ejmcloseModal()" class="close btn btn-primary" data-dismiss="modal" 
                aria-label="Close">
                </button>
            </div>
            <div class="modal-body">
         <tbody>

         <div class="form-group">
           <div class="col-sm-6">
                <label for="email">From Date</label>
                <input class="form-control form-control-rounded" value="<?= date('Y-m-d') ?>" id="ejmfromdt" name="ejmfromdt" type="date">
            </div>
            <div class="col-sm-6">
                <label for="email">To Date</label>
                <input class="form-control form-control-rounded" value="<?= date('Y-m-d') ?>" id="ejmtodt" name="ejmtodt" type="date">
            </div>
        </div>

        <div class="form-group">
           <div class="col-sm-3">
            <label for="email">Cad Ded</label>
            <input class="form-control form-control-rounded" id="ejmcadded" value="0" name="ejmcadded" type="text">   
        </div>
        <div class="col-sm-3">
            <label for="email">Vard Ded</label>
            <input class="form-control form-control-rounded" id="ejmvardded" value="0" name="ejmvardded" type="text">   
        </div>
        <div class="col-sm-3">
            <label for="email">GWF Ded</label>
            <input class="form-control form-control-rounded" id="ejmgwfded" value="0" name="ejmgwfded" type="text">   
        </div>
            <div class="col-12 col-sm-3">
            <label for="purchaseDetailsPurchaseDate">Cad/Vard/GWF<span class="text-center"></span></label>
            <button name="submit" id="ejmwrkvardupdate"  type="submit" class="form-control btn btn-primary">Update</button>
        </div>
        </div>

        <div class="form-group">

        <div class="col-12 col-sm-12">
        <label for="email">Select Department</label>
        <?php
        $this->data['companyId'] = $company;
        $this->data['departments'] = $this->varaha_model->getAllDepartments($this->data['companyId']);
     //   var_dump($departments);
        $de['0'] = 'All Departments';
        foreach ($departments as $dept) {
            $de[$dept->dept_id] = $dept->dept_desc;
        }
        echo form_dropdown('att_dept', $de, ($att_dept ? $att_dept : "0"), 'id="ejm_att_dept" class="myselect form-control form-control-rounded" data-placeholder="Select Department" style="width:100%;"');
        ?>
    </div>


</div>
        
        
        <div class="form-group">
            <div class="col-12 col-sm-6">
                <label for="purchaseDetailsPurchaseDate">Contractor Upload File <span class="text-center"></span></label>
                <input class="form-control form-control-rounded" id="ejm_cntexlfileuploads"  name="fileupload" type="file">   
            </div>

            <div class="col-12 col-sm-6">
                <label for="purchaseDetailsPurchaseDate">Upload FA File <span class="text-center"></span></label>
                <input class="form-control form-control-rounded" id="ejm_wrkfaexlfileupload"  name="ejm_wrkfaexlfileupload" type="file">   
            </div>
            <div class="col-12 col-sm-6">
                <label for="purchaseDetailsPurchaseDate">Upload Other/Adjs File <span class="text-center"></span></label>
                <input class="form-control form-control-rounded" id="ejm_wrkothexlfileupload"  name="ejm_wrkothexlfileupload" type="file">   
            </div>
            <div class="col-12 col-sm-6">
                <label for="purchaseDetailsPurchaseDate">Upload Line Hours <span class="text-center"></span></label>
                <input class="form-control form-control-rounded" id="ejm_wrklinehoursupload"  name="ejm_wrklinehoursupload" type="file">   
            </div>


    </div>
        <div class="form-group">

 

           <div class="col-12 col-sm-12">
              <label for="email">Pay Schemes</label>
              <?php
                    $de['0'] = 'Select';
                    foreach ($payschemes as $payschm) {
                        $de[$payschm->ID] = $payschm->NAME;
                    }       
                    
                    echo form_dropdown('att_payschm', $de, ($att_payschm ? $att_payschm : "0"), 'id="ejm_payschm"  class="myselect form-control form-control-rounded" data-placeholder="Select PayScheme"  style="width:100%;" multiple="multiple"');
                ?>
              
          </div>
          </div>



        <div class="form-group">
          <div class="col-12 col-sm-12">
               <label for="email">Process/Report/Print For</label>
              <?php
                    $holget[0] = 'Select Report';
                    $holget[1] = 'FNE Target Entry ';
                    $holget[2] = 'Contractor Wages PayRoll Posting';
                    $holget[3] = 'Contractor Wages Excel for PayRoll';
                    $holget[4] = 'Contractor Wages Pay Register';
                    $holget[5] = 'Main Wages Process ';
                    $holget[6] = 'Main Wages PayRoll Posting    ';
                    $holget[7] = 'Main Wages Excel for PayRoll';
                    $holget[8] = 'Main Wages Pay Register';
                    $holget[9] = 'Pay Slip Printing';
                    $holget[10] = 'Bank Statements';
                    $holget[11] = 'All Wages Break Up Summary';
                     

                    echo form_dropdown('hol_get', $holget, ($hol_get ? $hol_get : "0"), 'id="ejm_getmenu"  class="myselect form-control form-control-rounded" data-placeholder="Select Report "  style="width:100%;"');
                ?>
              
              </div>


        </div>

        <div class="form-group">
            <div class="col-12 col-sm-6">
                <label for="purchaseDetailsPurchaseDate">Submit<span class="text-center"></span></label>
            <button name="submit" id="ejm_menuclick"  type="submit" class="form-control btn btn-primary">Action</button>
        </div>

        <div class="col-12 col-sm-6">
            <label for="purchaseDetailsPurchaseDate">Close<span class="text-center"></span></label>
            <button name="submit" id="ejm_wagesclose"  type="submit" class="form-control btn btn-danger">Close</button>
        </div>
    </div>

        <div class="form-group">
          <div class="col-12 col-sm-12">
               <label for="email">Checklist  For</label>
              <?php
                    $holget1[0] = 'Select Checklist';
                    $holget1[1] = 'Attendance With PaySchemes';
                    $holget1[2] = 'Wages Checklist';
                    $holget1[3] = 'Line Hours Checklist';
                    $holget1[4] = 'Production Checklist';
                    $holget1[5] = 'No Production Checklist';
                    $holget1[6] = 'Leave Not Approved';


                    echo form_dropdown('hol_get1', $holget1, ($hol_get1 ? $hol_get1 : "0"), 'id="ejm_getchecklist"  
                    class="myselect form-control form-control-rounded" data-placeholder="Select Checklist "  
                    style="width:100%;"');
                ?>
              
              </div>


        </div>

        <div class="form-group">
            <div class="col-12 col-sm-6">
                <label for="purchaseDetailsPurchaseDate">Wages Brk Statement <span class="text-center"></span></label>
                <input class="form-control form-control-rounded" id="ejm_wagesbrkfile"  name="ejm_wagesbrkfile" type="file">   
            </div>
                </div>            



        </tbody>
    </table>
                </div>
                </div>
        </div>


        


        <div id="tareffModal" class="modal">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title modal-title-center" id="tareffModalLabel">FNE Target Entry</h4>
                    <button type="button" id="tareffclosebtn" onclick="tareffcloseModal()" class="close btn btn-primary" data-dismiss="modal" aria-label="Close">
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <div class="col-12 col-sm-12">
                            <label for="tareff_dept_id">Department</label>
                            <?php
            //echo "Company dept ID: " . $this->data['companyId'].'=='.$company . "<br>";

                    if (!isset($departments)) {
                                $this->data['companyId'] = $company;
                                $departments = $this->varaha_model->getAllDepartments($this->data['companyId']);
                            }
                            $dept_options = ['0' => 'Select'];
                            foreach ($departments as $dept) {
                                $dept_options[$dept->dept_id] = $dept->dept_desc;
                            }
                            echo form_dropdown('tareff_dept_id', $dept_options, '0', 'id="tareff_dept_id" class="myselect form-control form-control-rounded" data-placeholder="Select Department" style="width:100%;"');
                            ?>
                        </div>
                    </div>

<!-- !--//============


//
 -->

                    <div class="form-group">
                        <div class="col-12 col-sm-6">
                            <label for="tareff_target_type">Target Type</label>
                            <select id="tareff_target_type" class="form-control form-control-rounded" style="width:100%;">
                                <option value="">Select</option>
                                <option value="EFF">Efficiency</option>
                                <option value="PROD">Production</option>
                            </select>
                        </div>



                      <div class="col-12 col-sm-6">
                            <label for="tareff_eff_code">Eff Code</label>
                            <?php
                                $this->data['companyId'] = $company;
                                //echo "Company ID: " . $this->data['companyId'].'=='.$company . "<br>";
                                //                                $departments = $this->varaha_model->getAllDepartments($this->data['companyId']);
                                $eff_masters = $this->varaha_model->getAlleffM($this->data['companyId']);

                                $eff_options = ['0' => 'Select'];
                                foreach ($eff_masters as $eff) {
                                $eff_options[$eff->eff_code] = $eff->eff_mast_name;
                            }
                             echo form_dropdown('tareff_eff_code', $eff_options, '0', 'id="tareff_eff_code" class="myselect form-control form-control-rounded" data-placeholder="Select Eff Code" style="width:100%;"');
                            ?>
                        </div>




          
                    </div>




                    <div class="form-group">
                        <div class="col-12 col-sm-6">
                            <label for="tareff_qual_code">Qual Code</label>
                            <input class="form-control form-control-rounded" id="tareff_qual_code" name="tareff_qual_code" type="text">
                        </div>
                        <div class="col-12 col-sm-6">
                            <label for="tareff_target_eff">Target Eff</label>
                            <input class="form-control form-control-rounded" id="tareff_target_eff" name="tareff_target_eff" type="text">
                        </div>
                    </div>

                    <input type="hidden" id="tareff_target_id" name="tareff_target_id" value="">






                    <div class="form-group">
                        <div class="col-12 col-sm-4">
                            <label for="tareff_target_save">Save</label>
                            <button name="submit" id="tareff_target_save" type="submit" class="form-control btn btn-primary">Save</button>
                        </div>
                        <div class="col-12 col-sm-4">
                            <label for="tareff_target_update">Update</label>
                            <button name="submit" id="tareff_target_update" type="submit" class="form-control btn btn-primary" style="display:none;">Update</button>
                        </div>
                        <div class="col-12 col-sm-4">
                            <label for="tareff_clone">Clone Last FNE</label>
                            <button name="submit" id="tareff_clone" type="button" class="form-control btn btn-warning">Clone</button>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-12 col-sm-12">
                            <label for="tareffclose">Close</label>
                            <button name="submit" id="tareffclose" type="submit" class="form-control btn btn-danger">Close</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>




