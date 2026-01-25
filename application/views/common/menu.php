<div>
      <div class="leftNavigation">        
        <a href="<?=base_url()?>">
          <img src="<?=base_url('public')?>/assets/images/logo.png" alt="" />
        </a>
        <div class="leftNavigationContent">
          <?php
            if($menus){
              $i=0;
              foreach($menus as $menu){
                if($menu->menu_id!=560){
                  if($this->session->userdata('menuId')){
                    if($this->session->userdata('menuId')==$menu->menu_id){
                      echo '<a class="active" href="'.base_url($menu->menu_path.'/dashboard/'.$menu->menu_id).'"  onclick="getChangeMenu('.$menu->menu_id.')">';
                    }else{
                      echo '<a class="" href="'.base_url($menu->menu_path.'/dashboard/'.$menu->menu_id).'"  onclick="getChangeMenu('.$menu->menu_id.')">';
                    }                    
                  }else{
                    if($i==0){
                      echo '<a class="active" href="'.base_url($menu->menu_path.'/dashboard/'.$menu->menu_id).'"  onclick="getChangeMenu('.$menu->menu_id.')">';
                    }else{
                      echo '<a class="" href="'.base_url($menu->menu_path.'/dashboard/'.$menu->menu_id).'"  onclick="getChangeMenu('.$menu->menu_id.')">';
                    }
                  }
                  
                  echo '<img src="'.$menu->menu_icon_name.'" alt="" style="color:#fff"/>';
                  // echo '<img src="'.base_url('public').'/assets/images/jute.png" alt="" />';
                  echo '<br />'.$menu->menu;
                  echo '</a>';
                }
                $i++;
              }
            }
          ?>          
        </div>
      </div>
      <div class="mainContent">
      <div class="header d-flex">
      <div class="d-flex align-items-center" style="width: 100%;">
            <!-- <h1><?=($menuName ? ($menuName->menu_icon_name ? $menuName->menu_icon_name : '<img src="'.base_url('public').'/assets/images/jute.png" alt="" />').'&nbsp;'.str_replace(" ","&nbsp;",$menuName->menu) : "")?></h1> -->
            <h1><?=str_replace(" ","&nbsp;",$menuName)?></h1>            
            <?php	
                $scat[""] = "Select Company";              
                if($companys){	
                    foreach ($companys as $comp) {
                        $scat[$comp['compId']] = $comp['name'].' - '.$comp['label'];
                    }
                }	
                echo form_dropdown('company', $scat, ($this->session->userdata('companyId') ? $this->session->userdata('companyId') : ""), 'class="form-control myselect dropdown" id="company" data-placeholder="Select Company" style="width:100%" onchange="getChangeCompany(this.value)"')
            ?>&nbsp;&nbsp;
         
            <?php	
            // $submenuId = 0;
                $me[""] = "Select Report";
                if($submenu){	
                    $i=1;
                    // $submenuId = 0;
                    foreach ($submenu as $menu) {
                        $me[$menu->menu_id] = $menu->menu;
                        if($i==1){
                          // $submenuId = $menu->menu_id;
                        }
                        $i++;
                    }
                }	
                echo form_dropdown('report', $me, ($submenuId ? $submenuId : ""), 'class="form-control myselect dropdown" id="report" data-placeholder="Select Report" style="width:100%" onChange="getReport('.$mainmenuId.', this.value)"')
                
            ?>
          </div>
          
          <div class="d-flex align-items-center justify-content-end" style="width: 50%" >         
          <?php
                $attrib = array('data-toggle' => 'validator', 'role' => 'form', 'id' => 'form_report', 'enctype'=>"multipart/form-data");
                echo form_open_multipart($controller."/reporttype", $attrib)
            ?>    
                    <?php
                      if($submenuId!=609){
                    ?>
                    <a id="myBtn" class="ml-1" style="cursor:pointer;">
                      <img src="<?=base_url('public')?>/assets/images/filterIcon.png" alt="" />
                    </a>
                    <?php
                      }
                    ?>
                    <a href="javascript:getReportType(3, '<?=$mainmenuId?>')">
                      <img src="<?=base_url('public')?>/assets/images/printIcon.png" alt="" />
                    </a>
                    <?php
                       if($submenuId==610){
                    ?>
                      <a href="javascript:getReportType(1, '<?=$mainmenuId?>')">
                        <img src="<?=base_url('public')?>/assets/images/ic_pdf.png" alt="" />                
                      </a>
                    <?php
                        }
                    ?>
                    <a href="javascript:getReportType(2, '<?=$mainmenuId?>')">
                      <img src="<?=base_url('public')?>/assets/images/ic_excel.png" alt="" />
                    </a>
                    
            
                  <input type="hidden" id="type" name="type" />
                  <input type="hidden" id="companyId" name="companyId" value="" />
                  <input type="hidden" id="mainmenu" name="mainmenu" value="<?=$mainmenuId?>" />
                  <input type="hidden" id="submenu" name="submenu" />
                  <input type="hidden" id="from_date" name="from_date" />
                  <input type="hidden" id="to_date" name="to_date" />
                  <input type="hidden" id="jute_summary" name="jute_summary" />
                  <input type="hidden" id="mr_no" name="mr_no" value="" />
                  <input type="hidden" id="godown_no" name="godown_no" value="" />
                  <input type="hidden" id="Source_att" name="Source_att" value="" />
                  <input type="hidden" id="att_type_att" name="att_type_att" value="" />
                  <input type="hidden" id="att_status_att" name="att_status_att" value="" />
                  <input type="hidden" id="att_dept_att" name="att_dept_att" value="" />
                  <input type="hidden" id="att_mdept_att" name="att_mdept_att" value="" />
                  <input type="hidden" id="att_desig_att" name="att_desig_att" value="" />
                  <input type="hidden" id="att_spells_att" name="att_spells_att" value="" />
                  <input type="hidden" id="eb_no_att" name="eb_no_att" value="" />
                  <input type="hidden" id="att_mark_hrs_att" name="att_mark_hrs_att" value="1" />
                  <input type="hidden" id="att_worktype_att" name="att_worktype_att" value="" />
                  <input type="hidden" id="att_cat_att" name="att_cat_att" value="" />
                  <input type="hidden" id="att_branch_id" name="att_branch_id" value="" />
                  <input type="hidden" id="att_componet_id" name="att_componet_id" value="" />
                  <input type="hidden" id="itcode_chk" name="itcode_chk" value="" />
                  <input type="hidden" id="costcenter_chk" name="costcenter_chk" value="" />
                  <input type="hidden" id="itemdesc_chk" name="itemdesc_chk" value="" />
                  <input type="hidden" id="suppname_chk" name="suppname_chk" value="" />
                  <input type="hidden" id="srno_chk" name="srno_chk" value="" />
                  <input type="hidden" id="payscheme_chk" name="payscheme_chk" value="" />

                  
              <?php echo form_close(); ?>
                  <div class="dropdown">
                    <a href="" class="profileBlock dropdown-toggle" data-bs-toggle="dropdown">
                      <img src="<?=base_url('public')?>/assets/images/profileIcon.png" alt="" />
                    </a>
                    
                    <div class="dropdown-menu">
                      <a class="dropdown-item" href="<?=base_url()?>">Hi, <?=$this->session->userdata('userName')?></a>
                      <div class="dropdown-divider"></div>
                      <a class="dropdown-item" href="<?=base_url('welcome/logout')?>">Log&nbsp;Out</a>
                    </div>
                  </div>
              </div>
        </div>
        <div class="content">