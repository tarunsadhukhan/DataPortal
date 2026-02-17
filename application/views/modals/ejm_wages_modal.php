
    <style>
        table {
            display: none;
        }
         #tarrecordTable, #tarrecordTable_wrapper table,
         #prodWagesTable, #prodWagesTable_wrapper table,
         #attPrepTable, #attPrepTable_wrapper table,
         #advOthTable, #advOthTable_wrapper table {
            display: table !important;
            width: auto !important;
            min-width: 100%;
            white-space: nowrap;
        }
         #tarrecordTable_wrapper,
         #prodWagesTable_wrapper,
         #attPrepTable_wrapper,
         #advOthTable_wrapper {
            display: block !important;
            overflow-x: auto;
            overflow-y: auto;
        }
         #tarrecordTable_wrapper *,
         #prodWagesTable_wrapper *,
         #attPrepTable_wrapper *,
         #advOthTable_wrapper * {
            visibility: visible;
        }
         #tarrecordTable thead th,
         #prodWagesTable thead th,
         #attPrepTable thead th,
         #advOthTable thead th {
            background-color: #0f4d92;
            color: #f8f8ff;
            font-size: 11px;
            white-space: nowrap;
            height: 10px;
            padding: 4px 6px;
        }
         #tarrecordTable tbody td,
         #prodWagesTable tbody td,
         #attPrepTable tbody td,
         #advOthTable tbody td {
            font-size: 10px;
            white-space: nowrap;
            padding: 3px 5px;
        }
         #advOthModal > .modal-content {
            width: 95% !important;
            max-width: 1000px !important;
         }
         #attPrepModal > .modal-content {
            width: 95% !important;
            max-width: 1400px !important;
         }


</style>   

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
                    $depsm['0'] = 'Selective ';
                    foreach ($payschemes as $payschm) {
                        $depsm[$payschm->ID] = $payschm->NAME;
                    }       
                    
                    echo form_dropdown('ejm_payschm', $depsm, ($ejm_payschm ? $ejm_payschm : "0"), 'id="ejm_payschm"  class="myselect form-control form-control-rounded" data-placeholder="Select PayScheme"  style="width:100%;"');
                ?>
              
          </div>
          </div>



        <div class="form-group">
          <div class="col-12 col-sm-12">
               <label for="email">Process/Report/Print For</label>
              <?php
                    $holget[0] = 'Select Report';
                    $holget[2] = 'Wages & Production Quality Link';
                    $holget[1] = 'FNE Target Entry ';
                    $holget[3] = 'Attendance Preparation & Updation';
                    $holget[4] = 'Advance & Other Entries ';
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
                    <span id="tareff_date_display" style="font-size:14px; font-weight:bold; color:#0f4d92; margin-left:15px;"></span>
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
                                $eff_options[$eff->eff_code] = $eff->eff_label;
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
                            <label for="tareff_target_eff">Target Eff/Prod</label>
                            <input class="form-control form-control-rounded" id="tareff_target_eff" name="tareff_target_eff" type="text">
                        </div>
                    </div>

                    <input type="hidden" id="tareff_target_id" name="tareff_target_id" value="">






                    <div class="form-group">
                        <div class="col-12 col-sm-4">
                            <label for="tareff_target_save">Save / Update</label>
                            <button name="submit" id="tareff_target_save" type="submit" class="form-control btn btn-primary">Save</button>
                            <button name="submit" id="tareff_target_update" type="submit" class="form-control btn btn-primary" style="display:none;">Update</button>
                        </div>
                        <div class="col-12 col-sm-4">
                            <label for="tareff_clone">Clone Last FNE</label>
                            <button name="submit" id="tareff_clone" type="button" class="form-control btn btn-warning">Clone</button>
                        </div>
                        <div class="col-12 col-sm-4">
                            <label for="tareffclose">Close</label>
                            <button name="submit" id="tareffclose" type="submit" class="form-control btn btn-danger">Close</button>
                        </div>
                    </div>

   <table id="tarrecordTable" class="display">
        <thead>
            <tr>
                <th>Department</th>
                <th>Eff Code</th>
                <th>Qual Code</th>
                <th>Target Eff</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody id="tareff_target_tbody">
        </tbody>
    </table>
            



                </div>
            </div>
        </div>



        <div id="prodWagesModal" class="modal">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title modal-title-center" id="prodWagesModalLabel">Wages & Production Quality Link</h4>
                    <button type="button" id="prodWagesCloseBtnX" class="close btn btn-primary" data-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <div class="col-12 col-sm-12">
                            <label for="pw_dept_id">Department</label>
                            <?php
                                if (!isset($departments)) {
                                    $this->data['companyId'] = $company;
                                    $departments = $this->varaha_model->getAllDepartments($this->data['companyId']);
                                }
                                $pw_dept_options = ['0' => 'Select'];
                                foreach ($departments as $dept) {
                                    $pw_dept_options[$dept->dept_id] = $dept->dept_desc;
                                }
                                echo form_dropdown('pw_dept_id', $pw_dept_options, '0', 'id="pw_dept_id" class="myselect form-control form-control-rounded" data-placeholder="Select Department" style="width:100%;"');
                            ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-12 col-sm-4">
                            <label for="pw_prod_code">Prod Q Code</label>
                            <input class="form-control form-control-rounded" id="pw_prod_code" name="pw_prod_code" type="text" maxlength="8">
                        </div>
                        <div class="col-12 col-sm-4">
                            <label for="pw_wages_code">Wages Q Code</label>
                            <input class="form-control form-control-rounded" id="pw_wages_code" name="pw_wages_code" type="number">
                        </div>
                        <div class="col-12 col-sm-4">
                            <label for="pw_code_type">Code Type</label>
                            <input class="form-control form-control-rounded" id="pw_code_type" name="pw_code_type" type="text" maxlength="10">
                        </div>
                    </div>

                    <input type="hidden" id="pw_edit_id" value="">

                    <div class="form-group">
                        <div class="col-12 col-sm-4">
                            <label>Save / Update</label>
                            <button id="pw_save_btn" type="button" class="form-control btn btn-primary">Save</button>
                            <button id="pw_update_btn" type="button" class="form-control btn btn-primary" style="display:none;">Update</button>
                        </div>
                        <div class="col-12 col-sm-4">
                            <label>Clear</label>
                            <button id="pw_clear_btn" type="button" class="form-control btn btn-warning">Clear</button>
                        </div>
                        <div class="col-12 col-sm-4">
                            <label>Close</label>
                            <button id="pw_close_btn" type="button" class="form-control btn btn-danger">Close</button>
                        </div>
                    </div>

                    <table id="prodWagesTable" class="display">
                        <thead>
                            <tr>
                                <th>Department</th>
                                <th>Prod Code</th>
                                <th>Wages Code</th>
                                <th>Code Type</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="pw_tbody">
                        </tbody>
                    </table>

                </div>
            </div>
        </div>


        <div id="attPrepModal" class="modal">
            <div class="modal-content">
                <div class="modal-header" style="display:block;">
                    <div style="display:flex; align-items:center; justify-content:space-between;">
                        <h4 class="modal-title modal-title-center" id="attPrepModalLabel">Attendance Preparation & Updation</h4>
                        <button type="button" id="attPrepCloseBtnX" class="close btn btn-primary" data-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div style="display:flex; align-items:center; gap:15px; margin-top:5px; font-size:13px; font-weight:bold; color:#0f4d92;">
                        <span id="attprep_date_from_display"></span>
                        <span id="attprep_date_to_display"></span>
                        <span id="attprep_payschm_display"></span>
                    </div>
                </div>
                <div class="modal-body">
                    <div class="form-group" style="display:flex;flex-wrap:wrap;gap:5px;">
                        <div class="col-12 col-sm-3">
                            <label for="atp_dept_code">Department</label>
                            <?php
                                if (!isset($departments)) {
                                    $this->data['companyId'] = $company;
                                    $departments = $this->varaha_model->getAllDepartments($this->data['companyId']);
                                }
                                $atp_dept_options = ['0' => 'Select'];
                                foreach ($departments as $dept) {
                                    $atp_dept_options[$dept->dept_id] = $dept->dept_desc;
                                }
                                echo form_dropdown('atp_dept_code', $atp_dept_options, '0', 'id="atp_dept_code" class="myselect form-control form-control-rounded" data-placeholder="Select Department" style="width:100%;"');
                            ?>
                        </div>
                        <div class="col-12 col-sm-2">
                            <label for="atp_occu_code">Occu Code</label>
                            <input class="form-control form-control-rounded" id="atp_occu_code" name="atp_occu_code" type="text">
                        </div>
                        <div class="col-12 col-sm-2">
                            <label for="atp_eb_no">EB No</label>
                            <input class="form-control form-control-rounded" id="atp_eb_no" name="atp_eb_no" type="text">
                        </div>
                        <div class="col-12 col-sm-4">
                            <label for="atp_emp_name">Emp Name</label>
                            <input class="form-control form-control-rounded" id="atp_emp_name" name="atp_emp_name" type="text" readonly style="background-color:#e9ecef;">
                        </div>
                    </div>
                    <div class="form-group" style="display:flex;flex-wrap:wrap;gap:5px;">
                        <div class="col-12 col-sm-2">
                            <label for="atp_atttype">Shift</label>
                            <select id="atp_atttype" name="atp_atttype" class="form-control form-control-rounded">
                                <option value="">Select</option>
                                <option value="A">A</option>
                                <option value="B">B</option>
                                <option value="C">C</option>
                            </select>
                        </div>
                        <div class="col-12 col-sm-2" style="margin-left: 20px;">
                            <label for="atp_working_hours">Working Hrs</label>
                            <input class="form-control form-control-rounded" id="atp_working_hours" name="atp_working_hours" type="number" step="0.01" value="0">
                        </div>
                        <div class="col-12 col-sm-2">
                            <label for="atp_ot_hours">OT Hours</label>
                            <input class="form-control form-control-rounded" id="atp_ot_hours" name="atp_ot_hours" type="number" step="0.01" value="0">
                        </div>
                        <div class="col-12 col-sm-2">
                            <label for="atp_ns_hours">NS Hours</label>
                            <input class="form-control form-control-rounded" id="atp_ns_hours" name="atp_ns_hours" type="number" step="0.01" value="0">
                        </div>
                    </div>

                    <input type="hidden" id="atp_edit_id" value="">

                    <div class="form-group">
                        <div class="col-12 col-sm-3">
                            <label>Save</label>
                            <button id="atp_save_btn" type="button" class="form-control btn btn-primary">Save</button>
                            <button id="atp_update_btn" type="button" class="form-control btn btn-primary" style="display:none;">Update</button>
                        </div>
                        <div class="col-12 col-sm-3">
                            <label>Reset</label>
                            <button id="atp_reset_btn" type="button" class="form-control btn btn-warning">Reset</button>
                        </div>
                        <div class="col-12 col-sm-3">
                            <label>Process</label>
                            <button id="atp_process_btn" type="button" class="form-control btn btn-success">Process</button>
                        </div>
                        <div class="col-12 col-sm-3">
                            <label>Close</label>
                            <button id="atp_close_btn" type="button" class="form-control btn btn-danger">Close</button>
                        </div>
                    </div>

                    <table id="attPrepTable" class="display">
                        <thead>
                            <tr>
                                <th>Dept Code</th>
                                <th>Department</th>
                                <th>EB No</th>
                                <th>Emp Name</th>
                                <th>Occu Code</th>
                                <th>Shift</th>
                                <th>Working Hrs</th>
                                <th>OT Hrs</th>
                                <th>NS Hrs</th>
                                <th>Pay Scheme</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="atp_tbody">
                        </tbody>
                    </table>

                </div>
            </div>
        </div>


        <div id="advOthModal" class="modal">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title modal-title-center" id="advOthModalLabel">Advance & Other Entries</h4>
                    <span id="advoth_date_display" style="font-size:13px; font-weight:bold; color:#0f4d92; margin-left:10px;"></span>
                    <button type="button" id="advOthCloseBtnX" class="close btn btn-primary" data-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <div class="col-12 col-sm-3">
                            <label for="adv_eb_no">EB No</label>
                            <input class="form-control form-control-rounded" id="adv_eb_no" name="adv_eb_no" type="text">
                        </div>
                        <div class="col-12 col-sm-5">
                            <label for="adv_emp_name">Name</label>
                            <input class="form-control form-control-rounded" id="adv_emp_name" name="adv_emp_name" type="text" readonly style="background-color:#e9ecef;">
                        </div>
                        <div class="col-12 col-sm-2">
                            <label for="adv_stl_days">STL Days</label>
                            <input class="form-control form-control-rounded" id="adv_stl_days" name="adv_stl_days" type="number" step="0.01" value="0">
                        </div>
                        <div class="col-12 col-sm-2">
                            <label for="adv_puja_advance">Puja Advance</label>
                            <input class="form-control form-control-rounded" id="adv_puja_advance" name="adv_puja_advance" type="number" step="0.01" value="0">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-12 col-sm-3">
                            <label for="adv_ot_advance">OT Advance</label>
                            <input class="form-control form-control-rounded" id="adv_ot_advance" name="adv_ot_advance" type="number" step="0.01" value="0">
                        </div>
                        <div class="col-12 col-sm-3">
                            <label for="adv_installment_advance">Installment Adv</label>
                            <input class="form-control form-control-rounded" id="adv_installment_advance" name="adv_installment_advance" type="number" step="0.01" value="0">
                        </div>
                        <div class="col-12 col-sm-3">
                            <label for="adv_stl_advance">STL Advance</label>
                            <input class="form-control form-control-rounded" id="adv_stl_advance" name="adv_stl_advance" type="number" step="0.01" value="0">
                        </div>
                        <div class="col-12 col-sm-3">
                            <label for="adv_co_loan">CO Loan</label>
                            <input class="form-control form-control-rounded" id="adv_co_loan" name="adv_co_loan" type="number" step="0.01" value="0">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-12 col-sm-3">
                            <label for="adv_misc_earn">Misc Earn</label>
                            <input class="form-control form-control-rounded" id="adv_misc_earn" name="adv_misc_earn" type="number" step="0.01" value="0">
                        </div>
                        <div class="col-12 col-sm-3">
                            <label for="adv_misc_ded">Misc Ded</label>
                            <input class="form-control form-control-rounded" id="adv_misc_ded" name="adv_misc_ded" type="number" step="0.01" value="0">
                        </div>
                        <div class="col-12 col-sm-3">
                            <label for="adv_misc_ot_earn">Misc OT Earn</label>
                            <input class="form-control form-control-rounded" id="adv_misc_ot_earn" name="adv_misc_ot_earn" type="number" step="0.01" value="0">
                        </div>
                        <div class="col-12 col-sm-3">
                            <label for="adv_misc_ot_ded">Misc OT Ded</label>
                            <input class="form-control form-control-rounded" id="adv_misc_ot_ded" name="adv_misc_ot_ded" type="number" step="0.01" value="0">
                        </div>
                    </div>

                    <input type="hidden" id="adv_edit_id" value="">

                    <div class="form-group">
                        <div class="col-12 col-sm-4">
                            <label>Save / Update</label>
                            <button id="adv_save_btn" type="button" class="form-control btn btn-primary">Save</button>
                            <button id="adv_update_btn" type="button" class="form-control btn btn-primary" style="display:none;">Update</button>
                        </div>
                        <div class="col-12 col-sm-4">
                            <label>Installment Process</label>
                            <button id="adv_installment_btn" type="button" class="form-control btn btn-success">Installment Processing</button>
                        </div>
                        <div class="col-12 col-sm-4">
                            <label>Close</label>
                            <button id="adv_close_btn" type="button" class="form-control btn btn-danger">Close</button>
                        </div>
                    </div>

                    <table id="advOthTable" class="display">
                        <thead>
                            <tr>
                                <th>EB No</th>
                                <th>Name</th>
                                <th>STL Days</th>
                                <th>Puja Adv</th>
                                <th>OT Adv</th>
                                <th>Inst Adv</th>
                                <th>STL Adv</th>
                                <th>CO Loan</th>
                                <th>Misc Earn</th>
                                <th>Misc Ded</th>
                                <th>Misc OT Earn</th>
                                <th>Misc OT Ded</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="adv_tbody">
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
