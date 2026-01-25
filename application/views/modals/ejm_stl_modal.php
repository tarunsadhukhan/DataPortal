



<div id="stlModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title modal-title-center" id="exampleModalLabel">EJM STL Data Preparation</h4>
            <button type="button" id="closebtnsa" onclick="stlcloseModal()" class="close btn btn-primary" data-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <div class="form-group row">
                <div class="col-sm-6">
                    <label>From Date</label>
                    <input class="form-control form-control-rounded" value="<?= date('Y-m-d') ?>" id="stldetfromdt" name="stldetfromdt" type="date">
                </div>
                <div class="col-sm-6">
                    <label>To Date</label>
                    <input class="form-control form-control-rounded" value="<?= date('Y-m-d') ?>" id="stldettodt" name="stldettodt" type="date">
                </div>
            </div>

            <div class="form-group row">
                <div class="col-sm-6">
                    <label>Upload File</label>
                    <input class="form-control form-control-rounded" id="fileupload" name="fileupload" type="file">   
                </div>
                <div class="col-sm-6">
                    <label>Upload Data</label>
                    <button id="stlupload" type="submit" class="form-control btn btn-primary">Upload Data</button>
                </div>
            </div>    

            <div class="form-group row">
                <div class="col-sm-6">
                    <label>Download Data</label>
                    <button id="stldownload" type="submit" class="form-control btn btn-primary">Download</button>
                </div>
                <div class="col-sm-6">
                    <label>Download Details Data</label>
                    <button id="stldetdownload" type="submit" class="form-control btn btn-primary">Download</button>
                </div>
            </div>

            <div class="form-group row">
                <div class="col-sm-6">
                    <label>Close</label>
                    <button id="stlclose" type="submit" class="form-control btn btn-danger">Close</button>
                </div>
            </div> 
        </div>
    </div>
</div>



        <div id="paypostModal" class="modal">
            <div class="modal-content">
            <div class="modal-header">
            <h4 class="modal-title modal-title-center" id="exampleModalLabel">Pay Roll Posting</h4>
            <button type="button" id="closebtnsa" onclick="stlcloseModal()" class="close btn btn-primary" data-dismiss="modal" 
                aria-label="Close">
                </button>
            </div>
            <div class="modal-body">
         <tbody>

         <div class="form-group">
            <div class="col-12 col-sm-6">
                <label for="purchaseDetailsPurchaseDate">Upload File <span class="text-center"></span></label>
                <input class="form-control form-control-rounded" id="payrollexlfileupload"  
                name="payrollexlfileupload" type="file"   accept="*.csv,text/csv">

   
            </div>
             <div class="col-12 col-sm-6">
            <label for="purchaseDetailsPurchaseDate">Upload Data<span class="text-center"></span></label>
                <button name="submit" id="payrollexlupload"  type="submit" class="form-control btn btn-primary">Upload Data</button>
            </div>

 

        </div>    

 
  


        <div class="form-group">
            <div class="col-12 col-sm-6">
            <label for="purchaseDetailsPurchaseDate">Upload c Data<span class="text-center"></span></label>
                <button name="submit" id="updatesalcomp"  type="submit" class="form-control btn btn-secondary">Upload C Data</button>
            </div>
            <div class="col-12 col-sm-6">
                <label for="purchaseDetailsPurchaseDate">Close<span class="text-center"></span></label>
                <button name="submit" id="payrollclose"  type="submit" class="form-control btn btn-danger">Close</button>
            </div>
        </div> 
                </tbody>
                </div>
        </div> 
        </div> 

        <div id="wagessummaryModal" class="modal">
            <div class="modal-content">
            <div class="modal-header">
            <h4 class="modal-title modal-title-center" id="exampleModalLabel">Contractor Wages Prepartion</h4>
            <button type="button" id="closebtnsa" onclick="stlcloseModal()" class="close btn btn-primary" data-dismiss="modal" 
                aria-label="Close">
                </button>
            </div>
            <div class="modal-body">
         <tbody>

         <div class="form-group">
            <div class="col-12 col-sm-6">
                <label for="purchaseDetailsPurchaseDate">Upload File <span class="text-center"></span></label>
                <input class="form-control form-control-rounded" id="cntexlfileupload"  name="fileupload" type="file">   
            </div>
            <div class="col-12 col-sm-6">
            <label for="purchaseDetailsPurchaseDate">Upload Data<span class="text-center"></span></label>
                <button name="submit" id="cntexlupload"  type="submit" class="form-control btn btn-primary">Upload Data</button>
            </div>
        </div>    

        <div class="form-group">
            <div class="col-12 col-sm-6">
                <label for="purchaseDetailsPurchaseDate">Process Cont Wages<span class="text-center"></span></label>
                <button name="submit" id="njmcntwagesprocessdata"  type="submit" class="form-control btn btn-primary">Process</button>
            </div>
            <div class="col-12 col-sm-6">
                <label for="purchaseDetailsPurchaseDate">Cont Excel For Wages<span class="text-center"></span></label>
                <button name="submit" id="njmcntwagesexceldata"  type="submit" class="form-control btn btn-primary">Excel</button>
            </div>
        </div> 

        <div class="form-group">
            <div class="col-12 col-sm-6">
                <label for="purchaseDetailsPurchaseDate">Pay Slip for Cont<span class="text-center"></span></label>
                <button name="submit" id="njmcntwagespayslip"  type="submit" class="form-control btn btn-primary">Pay Slip</button>
            </div>
            <div class="col-12 col-sm-6">
                <label for="purchaseDetailsPurchaseDate">Pay Register for Cont<span class="text-center"></span></label>
                <button name="submit" id="njmcntpyregister"  type="submit" class="form-control btn btn-primary">Pay Register</button>
            </div>
        </div> 


        <div class="form-group">
            <div class="col-12 col-sm-6">
                <label for="purchaseDetailsPurchaseDate">Worker Bank Statement <span class="text-center"></span></label>
                <button name="submit" id="njmcntworkerbankstatement"  type="submit" class="form-control btn btn-primary">Bank Statement</button>
            </div>

            <div class="col-12 col-sm-6">
                <label for="purchaseDetailsPurchaseDate">Bank Excel DownLoad <span class="text-center"></span></label>
                <button name="submit" id="njmcntworkerbankdownload"  type="submit" class="form-control btn btn-primary">Download</button>
            </div>
        </div>            

        <div class="form-group">
            <div class="col-12 col-sm-6">
                <label for="purchaseDetailsPurchaseDate">Cont Worker Pay Register <span class="text-center"></span></label>
                <button name="submit" id="njmcntworkerpayregister"  type="submit" class="form-control btn btn-primary">Pay Register</button>
            </div>

            <div class="col-12 col-sm-6">
                <label for="purchaseDetailsPurchaseDate">Pay Register Excel<span class="text-center"></span></label>
                <button name="submit" id="njmcntworkerpaydownload"  type="submit" class="form-control btn btn-primary">Download</button>
            </div>
        </div>            



        <div class="form-group">
            <div class="col-12 col-sm-6">
                <label for="purchaseDetailsPurchaseDate">Close<span class="text-center"></span></label>
                <button name="submit" id="cntwagesclose"  type="submit" class="form-control btn btn-danger">Close</button>
            </div>
        </div> 
                </tbody>
                </div>
        </div> 
        </div> 





        <div id="myModal" class="modal">
            <div class="modal-content">
      
            <div class="modal-header">
            <h4 class="modal-title modal-title-center" id="exampleModalLabel">Njm Wages Data Preparation1</h4>
            <button type="button" id="closebtnsa" onclick="closeModal()" class="close btn btn-primary" data-dismiss="modal" 
                aria-label="Close">
                </button>
            </div>
            <div class="modal-body">
         <tbody>

         <div class="form-group">
           <div class="col-sm-6">
                <label for="email">From Date</label>
                <input class="form-control form-control-rounded" value="<?= date('Y-m-d') ?>" id="njmcntfromdt" name="stldetfromdt" type="date">
            </div>
            <div class="col-sm-6">
                <label for="email">To Date</label>
                <input class="form-control form-control-rounded" value="<?= date('Y-m-d') ?>" id="njmcnttodt" name="stldettodt" type="date">
            </div>
        </div>

        <div class="form-group">
           <div class="col-sm-3">
            <label for="email">Cad Ded</label>
            <input class="form-control form-control-rounded" id="cadded" value="0" name="cadded" type="text">   
        </div>
        <div class="col-sm-3">
            <label for="email">Vard Ded</label>
            <input class="form-control form-control-rounded" id="vardded" value="0" name="vardded" type="text">   
        </div>
        <div class="col-sm-3">
            <label for="email">GWF Ded</label>
            <input class="form-control form-control-rounded" id="gwfded" value="0" name="gwfded" type="text">   
        </div>
            <div class="col-12 col-sm-3">
            <label for="purchaseDetailsPurchaseDate">Cad/Vard/GWF<span class="text-center"></span></label>
            <button name="submit" id="njmwrkvardupdate"  type="submit" class="form-control btn btn-primary">Update</button>
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
        echo form_dropdown('att_dept', $de, ($att_dept ? $att_dept : "0"), 'id="att_dept" class="myselect form-control form-control-rounded" data-placeholder="Select Department" style="width:100%;"');
        ?>
    </div>


</div>
        
        
        <div class="form-group">
            <div class="col-12 col-sm-6">
                <label for="purchaseDetailsPurchaseDate">Contractor Upload File <span class="text-center"></span></label>
                <input class="form-control form-control-rounded" id="cntexlfileuploads"  name="fileupload" type="file">   
            </div>

            <div class="col-12 col-sm-6">
                <label for="purchaseDetailsPurchaseDate">Upload FA File <span class="text-center"></span></label>
                <input class="form-control form-control-rounded" id="wrkfaexlfileupload"  name="wrkfaexlfileupload" type="file">   
            </div>
            <div class="col-12 col-sm-6">
                <label for="purchaseDetailsPurchaseDate">Upload Other/Adjs File <span class="text-center"></span></label>
                <input class="form-control form-control-rounded" id="wrkothexlfileupload"  name="wrkothexlfileupload" type="file">   
            </div>
            <div class="col-12 col-sm-6">
                <label for="purchaseDetailsPurchaseDate">Upload Line Hours <span class="text-center"></span></label>
                <input class="form-control form-control-rounded" id="wrklinehoursupload"  name="wrklinehoursupload" type="file">   
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
                    
                    echo form_dropdown('att_payschm', $de, ($att_payschm ? $att_payschm : "0"), 'id="payschm"  class="myselect form-control form-control-rounded" data-placeholder="Select PayScheme"  style="width:100%;" multiple="multiple"');
                ?>
              
          </div>
          </div>



        <div class="form-group">
          <div class="col-12 col-sm-12">
               <label for="email">Process/Report/Print For</label>
              <?php
                    $holget[0] = 'Select Report';
                    $holget[1] = 'Contractor Wages Process';
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
                     

                    echo form_dropdown('hol_get', $holget, ($hol_get ? $hol_get : "0"), 'id="getmenu"  class="myselect form-control form-control-rounded" data-placeholder="Select Report "  style="width:100%;"');
                ?>
              
              </div>


        </div>

        <div class="form-group">
            <div class="col-12 col-sm-6">
                <label for="purchaseDetailsPurchaseDate">Submit<span class="text-center"></span></label>
            <button name="submit" id="njmmenuclick"  type="submit" class="form-control btn btn-primary">Action</button>
        </div>

        <div class="col-12 col-sm-6">
            <label for="purchaseDetailsPurchaseDate">Close<span class="text-center"></span></label>
            <button name="submit" id="njmwagesclose"  type="submit" class="form-control btn btn-danger">Close</button>
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


                    echo form_dropdown('hol_get1', $holget1, ($hol_get1 ? $hol_get1 : "0"), 'id="getchecklist"  
                    class="myselect form-control form-control-rounded" data-placeholder="Select Checklist "  
                    style="width:100%;"');
                ?>
              
              </div>


        </div>

        <div class="form-group">
            <div class="col-12 col-sm-6">
                <label for="purchaseDetailsPurchaseDate">Wages Brk Statement <span class="text-center"></span></label>
                <input class="form-control form-control-rounded" id="cntexlfileuploads"  name="fileupload" type="file">   
            </div>
                </div>            



        </tbody>
    </table>
                </div>
                </div>
        </div>     

        <div id="TnoupdmyModal" class="modal">
            <div class="modal-content">
            <div class="modal-header">
            <h4 class="modal-title modal-title-center" id="exampleModalLabel">T NO Data Updation </h4>
            <button type="button" id="tnoclosebtnsa" onclick="tnocloseModal()" class="close btn btn-primary" data-dismiss="modal" 
                aria-label="Close">
                </button>
            </div>
            <div class="modal-body">
         <tbody>

         <div class="form-group">

         <div class="col-sm-4">
            <label for="email">Emp Code</label>
            <input class="form-control form-control-rounded" id="tnocode" value="T" name="tnocode" type="text">   
        </div>
        <div class="col-sm-8">
            <label for="email">Name</label>
            <input class="form-control form-control-rounded" id="tnoname" value=""  name="tnoname" type="text" disabled>   
        </div>
        </div>
  
        <div class="form-group">
          <div class="col-12 col-sm-6">
          <label for="email">Payment Period</label>
            <label for="email" class="form-control form-control-rounded">Daily Payment
            <input type="checkbox" id="pay_due_daily" name="pay_due" class="mycheckbox">
      
            </label>
    </div>
    <div class="col-12 col-sm-6">
              <label for="email">LOcation</label>
              <?php
                    $lde['0'] = 'Select';
                    foreach ($locations as $location) {
                        $lde[$location->subloca_id] = $location->sub_location;
                    }       
                    
                    echo form_dropdown('sub_location', $lde, ($sub_location ? $sub_location : "1"), 'id="sub_location"  class="myselect form-control form-control-rounded" data-placeholder="Select Location"  style="width:100%;"');
                ?>
              
          </div>
    
    </div> 
        <div class="form-group">
        <div class="col-sm-6">
            <label for="email">Rate</label>
            <input class="form-control form-control-rounded" id="tnorate" value="0" name="tnorate" type="text">   
        </div>

        <div class="col-12 col-sm-6">
        <label for="purchaseDetailsPurchaseDate">Update<span class="text-center"></span></label>
            <button name="submit" id="tnodataupdate"  type="submit" class="form-control btn btn-primary">Update</button>
        </div>
        </div> 
        <div class="form-group">
        <div class="col-12 col-sm-6">
            <label for="purchaseDetailsPurchaseDate">Close<span class="text-center"></span></label>
            <button name="submit" id="tnoclose"  type="submit" class="form-control btn btn-danger">Close</button>
        </div>
        </div> 
        </tbody>
    </table>
                </div>
                </div>
        </div>     

        <div id="nwdupdmyModal" class="modal">
            <div class="modal-content">
            <div class="modal-header">
            <h4 class="modal-title modal-title-center" id="exampleModalLabel">Non Working Date </h4>
            <button type="button" id="nwdclosebtnsa" onclick="nwdcloseModal()" class="close btn btn-primary" data-dismiss="modal" 
                aria-label="Close">
                </button>
            </div>
            <div class="modal-body">
         <tbody>

         <div class="form-group">
             <div class="col-12 col-sm-6">
                <label for="account-name">Non Working Date</label>
                <input class="form-control form-control-rounded" value="<?= date('Y-m-d') ?>" id="nwdfromdt" name="nwdfromdt" type="date">
            </div>
            <div class="col-12 col-sm-6">
              <label for="email">Off Day/Holday</label>
              <?php
                    $sourceoff['1'] = 'Off Day';
                    $sourceoff['2'] = 'Holidays';
                    echo form_dropdown('Sourceoff', $sourceoff, ($Sourceoff ? $Sourceoff : "1"), 'id="Sourceoff"  class="myselect form-control form-control-rounded" data-placeholder="Select "  style="width:100%;"');
                ?>
            </div>
        </div>
  
        <div class="form-group">
        <div class="col-12 col-sm-6">
          <label for="email">Active</label>
            <label for="email" class="form-control form-control-rounded">Active
            <input type="checkbox" id="pay_active" name="pay_active" value=1 class="mycheckbox">
      
            </label>
    </div>
    <div class="col-12 col-sm-6">
        <label for="purchaseDetailsPurchaseDate">Update<span class="text-center"></span></label>
            <button name="submit" id="nwddataupdate"  type="submit" class="form-control btn btn-primary">Update</button>
        </div>
     

        </div> 
    
        <div class="form-group">
        <div class="col-12 col-sm-6">
            <label for="purchaseDetailsPurchaseDate">Close<span class="text-center"></span></label>
            <button name="submit" id="nwdclose"  type="submit" class="form-control btn btn-danger">Close</button>
        </div>
        </div> 
        </tbody>
    </table>
                </div>
                </div>
        </div>     


<div class="modal" id="rowDetailsModal" tabindex="-1"
     data-bs-backdrop="static" data-bs-keyboard="false" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content custom-modal-size">
      <div class="modal-header">
        <h5 class="modal-title">Row Details</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <!-- Your dynamic content here -->
      </div>
    </div>
  </div>
</div>




    <div id="canteenModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
            <h4 class="modal-title modal-title-center" id="exampleModalLabel">EJM STL Data Prepartion</h4>
            <button type="button" id="closecanteenbtnsa" onclick="canteencloseModal()" class="close btn btn-primary" data-dismiss="modal" 
                aria-label="Close">
                </button>
            </div>

            <div class="modal-body">
                 <tbody>
                    <div class="form-group">
                        <div class="col-sm-6">
                            <label for="email">From Date</label>
                            <input class="form-control form-control-rounded" value="<?= date('Y-m-d') ?>" id="stlfromdt" name="stlfromdt" type="date">
                        </div>
                        <div class="col-sm-6">
                            <label for="email">To Date</label>
                            <input class="form-control form-control-rounded" value="<?= date('Y-m-d') ?>" id="stltodt" name="stltodt" type="date">
                        </div>
                    </div>
  
 
                    <div class="form-group">
                        <div class="col-12 col-sm-6">
                            <label for="purchaseDetailsPurchaseDate">Download Data <span class="text-center"></span></label>
                            <button name="submit" id="canteendetdownload"  type="submit" class="form-control btn btn-primary">Download</button>
                        </div>

                        <div class="col-12 col-sm-6">
                            <label for="purchaseDetailsPurchaseDate">Close<span class="text-center"></span></label>
                            <button name="submit" id="canteenclose"  type="submit" class="form-control btn btn-danger">Close</button>
                        </div>
                </tbody>
            </div>
                </div>
        </div> 
        </div> 


    <div id="attsheetModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
            <h4 class="modal-title modal-title-center" id="exampleModalLabel">EJM STL Data Prepartion</h4>
            <button type="button" id="closecanteenbtnsa" onclick="attsheetcloseModal()" class="close btn btn-primary" data-dismiss="modal" 
                aria-label="Close">
                </button>
            </div>

            <div class="modal-body">
                 <tbody>
                    <div class="form-group">
                        <div class="col-sm-6">
                            <label for="email">From Date</label>
                            <input class="form-control form-control-rounded" value="<?= date('Y-m-d') ?>" id="attfromdt" name="attfromdt" type="date">
                        </div>
                        <div class="col-sm-6">
                            <label for="email">To Date</label>
                            <input class="form-control form-control-rounded" value="<?= date('Y-m-d') ?>" id="atttodt" name="atttodt" type="date">
                        </div>
                    </div>
    <div class="col-12 col-sm-6">
              <div class="form-group">
              <label for="email">Departments</label>
              <?php
                    $dep['0'] = 'ALL';
                    foreach ($masterdepartments as $dept) {
                        $dep[$dept->dept_desc] = $dept->dept_desc;
                    }       
                    
                    echo form_dropdown('att_dept', $dep, ($att_dept ? $att_dept : "0"), 'id="att_dept"  class="myselect form-control form-control-rounded" data-placeholder="Select Department"  style="width:100%;"');
                ?>
              
              </div>
          </div>
       
                    
 
                    <div class="form-group">
                        <div class="col-12 col-sm-6">
                            <label for="purchaseDetailsPurchaseDate">Download Data <span class="text-center"></span></label>
                            <button name="submit" id="attsheetdownload"  type="submit" class="form-control btn btn-primary">Download</button>
                        </div>

                        <div class="col-12 col-sm-6">
                            <label for="purchaseDetailsPurchaseDate">Close<span class="text-center"></span></label>
                            <button name="submit" id="attsheetclose"  type="submit" class="form-control btn btn-danger">Close</button>
                        </div>
                </tbody>
            </div>
                </div>
        </div> 
        </div> 


 <div id="oattprdModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
            <h4 class="modal-title modal-title-center" id="exampleModalLabel">Daily Outsider Productiosssn</h4>
            <button type="button" id="closecanteenbtnsa" onclick="oattprdcloseModal()" class="close btn btn-primary" data-dismiss="modal" 
                aria-label="Close">
                </button>
            </div>

            <div class="modal-body">
                 <tbody>
                    <div class="form-group">
                        <div class="col-sm-6">
                            <label for="email">From Date</label>
                            <input class="form-control form-control-rounded" value="<?= date('Y-m-d') ?>" id="oattfromdt" name="oattfromdt" type="date">
                        </div>

                        
             <div class="col-12 col-sm-6">
              <label for="email">Departments</label>
              <?php
                    $dep['0'] = 'ALL';
                    foreach ($masterdepartments as $dept) {
                        $dep[$dept->dept_desc] = $dept->dept_desc;
                    }       
                    
                    echo form_dropdown('att_dept', $dep, ($att_dept ? $att_dept : "0"), 'id="att_dept"  class="myselect form-control form-control-rounded" data-placeholder="Select Department"  style="width:100%;"');
                ?>
              
              </div>
          </div>

                    <div class="form-group">
                        <div class="col-12 col-sm-6">
                            <label for="purchaseDetailsPurchaseDate">Process Data <span class="text-center"></span></label>
                            <button name="submit" id="oattprdprocess"  type="submit" class="form-control btn btn-primary">Process</button>
                        </div>
                    <div class="col-12 col-sm-6">
                            <label for="purchaseDetailsPurchaseDate">Download Data <span class="text-center"></span></label>
                            <button name="submit" id="oattprddownload"  type="submit" class="form-control btn btn-primary">Download</button>
                        </div>

                </div>

                                    <div class="form-group">

                        <div class="col-sm-3">
                            <label for="email">EB No</label>
                            <input class="form-control form-control-rounded" id="ebnos" value="T" name="ebnos" type="text">   
                        </div>

            <div class="col-12 col-sm-3">
              <label for="email">Shift</label>
              <?php
                    $Shiftoff['A'] = 'A';
                    $Shiftoff['B'] = 'B';
                    $Shiftoff['C'] = 'C';
                    
                    echo form_dropdown('Shiftoff', $Shiftoff, ($Shiftoff ? $Shifteoff : "1"), 'id="shiftoff"  class="myselect form-control form-control-rounded" data-placeholder="Select "  style="width:100%;"');
                ?>
            </div>
                        <div class="col-sm-3">
                            <label for="email"> Rate</label>
                            <input class="form-control form-control-rounded" id="updtrate" value="0" name="updtrate" type="text">   
                        </div>

                      <div class="col-12 col-sm-3">
                            <label for="purchaseDetailsPurchaseDate">Update<span class="text-center"></span></label>
                            <button name="submit" id="updtrates"  type="submit" class="form-control btn btn-danger">Update</button>
                        </div>
           

                </div>
                     <div class="form-group">
                   



                        <div class="col-12 col-sm-6">
                            <label for="purchaseDetailsPurchaseDate">Close<span class="text-center"></span></label>
                            <button name="submit" id="oattprdclose"  type="submit" class="form-control btn btn-danger">Close</button>
                        </div>
                </tbody>
            </div>
                </div>
        </div> 
        </div> 



        <div id="wagesbrkModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
            <h4 class="modal-title modal-title-center" id="exampleModalLabel">Wages BreakUp Statement</h4>
            <button type="button" id="closecanteenbtnsa" onclick="wagesbrkcloseModal()" class="close btn btn-primary" data-dismiss="modal" 
                aria-label="Close">
                </button>
            </div>

            <div class="modal-body">
                 <tbody>
                    <div class="form-group">
                        <div class="col-sm-6">
                            <label for="email">Month End Date</label>
                            <input class="form-control form-control-rounded" value="<?= date('Y-m-d') ?>" id="wgbrkfromdt" name="wgbrkfromdt" type="date">
                        </div>
                        
                <div class="col-sm-6">
                    <label>Upload File</label>
                    <input class="form-control form-control-rounded" id="fileuploadwgbrk" name="fileupload" type="file">   
                </div>

                    </div>

 

 
   
                      <div class="form-group">
                        <div class="col-12 col-sm-6">
                            <label for="purchaseDetailsPurchaseDate">Update<span class="text-center"></span></label>
                            <button name="submit" id="wgbrksubmit"  type="submit" class="form-control btn btn-danger">Submit</button>
                        </div>
    
                        <div class="col-12 col-sm-6">
                            <label for="purchaseDetailsPurchaseDate">Close<span class="text-center"></span></label>
                            <button name="submit" id="wgbrkclose"  type="submit" class="form-control btn btn-danger">Close</button>
                        </div>
                </tbody>
            </div>
                </div>
        </div> 
        </div> 



    <div id="Modal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
            <h4 class="modal-title modal-title-center" id="exampleModalLabel">EJM STL Data Prepartion</h4>
            <button type="button" id="closecanteenbtnsa" onclick="canteencloseModal()" class="close btn btn-primary" data-dismiss="modal" 
                aria-label="Close">
                </button>
            </div>

            <div class="modal-body">
                 <tbody>
                    <div class="form-group">
                        <div class="col-sm-6">
                            <label for="email">From Date</label>
                            <input class="form-control form-control-rounded" value="<?= date('Y-m-d') ?>" id="stlfromdt" name="stlfromdt" type="date">
                        </div>
                        <div class="col-sm-6">
                            <label for="email">To Date</label>
                            <input class="form-control form-control-rounded" value="<?= date('Y-m-d') ?>" id="stltodt" name="stltodt" type="date">
                        </div>
                    </div>
  
 
                    <div class="form-group">
                        <div class="col-12 col-sm-6">
                            <label for="purchaseDetailsPurchaseDate">Download Data <span class="text-center"></span></label>
                            <button name="submit" id="canteendetdownload"  type="submit" class="form-control btn btn-primary">Download</button>
                        </div>

                        <div class="col-12 col-sm-6">
                            <label for="purchaseDetailsPurchaseDate">Close<span class="text-center"></span></label>
                            <button name="submit" id="canteenclose"  type="submit" class="form-control btn btn-danger">Close</button>
                        </div>
                </tbody>
            </div>
                </div>
        </div> 
        </div> 
