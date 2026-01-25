<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Columns
{

    public function __construct()
    {

    }

    public function __get($var)
    {
        return get_instance()->$var;
    }

    public function getReportColumnsm($subId,$fromdt=null,$todt=null,$compid=null,$itcod=null){
        $data = array();
        if($subId==253){
                $startDate = ($fromdt);
                $endDate = ($todt);
                $compId = $compid;
                $itcode = $itcod;
              //  echo 'itecode'.$itcode.' mn '.$startDate.' en'.$endDate;
                $data = array('itemcode'=> 'Item&nbsp;Code','item_desc'=>'Item&nbsp;Description');
                $listm = $this->Store_item_monthwise_consumption_model->get_monthdata($subId,$compId,$startDate,$endDate,$itcode);
                foreach ($listm as $loc) {
                    $day=$loc->cmnyr;
                    $rangArray[] = $day;
                    $data = array_merge($data, [$day => $day]);
                }    
                $day='Total';
                $data = array_merge($data, [$day => $day]);
                $day='No of Month';
                $data = array_merge($data, [$day => $day]);
                $day='Average';
                $data = array_merge($data, [$day => $day]);


                return $data;
               
         
        }



    }    
    public function getReportColumns($subId,$fromdt=null,$todt=null,$spells=null){
        $data = array();
        if($subId==562){
            $data = array('SNo','POID','Created&nbsp;By', 
            'Created&nbsp;Date',
            'Last&nbsp;Modified&nbsp;By',
            'Last&nbsp;Modified&nbsp;Date',
            'Bill&nbsp;To&nbsp;Address',
            'Bill&nbsp;To&nbsp;State&nbsp;Name',
            'Ship&nbsp;To&nbsp;Address',
            'Ship&nbsp;To&nbsp;State&nbsp;Name',
            'Credit&nbsp;Days',
            'Po&nbsp;Date',
            'Po&nbsp;Sequence&nbsp;No',
            'Source',
            'Tax&nbsp;Payable',
            'Delivery&nbsp;Timeline',
            'Supplier&nbsp;Branch',
            'Billing&nbsp;Branch',
            'Category',
            'Net&nbsp;Amount',
            'Total&nbsp;Amount',
            'Tax&nbsp;Type',
            'Item&nbsp;Group',
            'Advance&nbsp;Type',
            'Advance&nbsp;Percentage',
            'Advance&nbsp;Amount',
            'Status',
            'Budget&nbsp;Head',
            'Indent&nbsp;Squence&nbsp;No',
            'Company&nbsp;Code',
            'Branch&nbsp;Name',
            'Branch&nbsp;Address',
            'Group&nbsp;Desc',
            'Supp&nbsp;Name',
            'Customer',
            );
            
        }
        if($subId==631){
            $data = array('SNo','POID','Created&nbsp;By', 
            'Created&nbsp;Date',
            'Last&nbsp;Modified&nbsp;By',
            'Last&nbsp;Modified&nbsp;Date',
            'Bill&nbsp;To&nbsp;Address',
            'Bill&nbsp;To&nbsp;State&nbsp;Name',
            'Ship&nbsp;To&nbsp;Address',
            'Ship&nbsp;To&nbsp;State&nbsp;Name',
            'Credit&nbsp;Days',
            'Po&nbsp;Date',
            'Po&nbsp;Sequence&nbsp;No',
            'Source',
            'Tax&nbsp;Payable',
            'Delivery&nbsp;Timeline',
            'Supplier&nbsp;Branch',
            'Billing&nbsp;Branch',
            'Category',
            'Net&nbsp;Amount',
            'Total&nbsp;Amount',
            'Tax&nbsp;Type',
            'Item&nbsp;Group',
            'Advance&nbsp;Type',
            'Advance&nbsp;Percentage',
            'Advance&nbsp;Amount',
            'Status',
            'Budget&nbsp;Head',
            'Indent&nbsp;Squence&nbsp;No',
            'Company&nbsp;Code',
            'Branch&nbsp;Name',
            'Branch&nbsp;Address',
            'Group&nbsp;Desc',
            'Supp&nbsp;Name',
            'Customer',
            );
            
        }
        if($subId==563){
            $data = array('SNo',
            'PO&nbsp;Detail&nbsp;Id',
            'Qty', 'Rate','Last&nbsp;Purchase',
            'Indent',
            'Indent&nbsp;Detail',
            'Item',
            'Tax',
            'Installation&nbsp;Rate',
            'Installation&nbsp;Amount',
            'Make',
            'Uom&nbsp;Code',
            'PO&nbsp;Sequence&nbsp;No',
            'PO&nbsp;Date',
            'Status&nbsp;Name',
            'Name',
            'Group&nbsp;Code',
            'Group&nbsp;Desc',
            'Item&nbsp;Code',
            'Item&nbsp;Description',
            'Item Wise Value',
            'Supplier',
            'Customer',
            'PO Value Without Tax',
            'PO Gross Value With Tax',
            'Source(PO/WO)',
        );


        }
        if($subId==564){
            // $data = array('SNo','ID','Sequence&nbsp;No','Date', 'Project&nbsp;Type', 'Title', 'Client', 'Category', 'Branch&nbsp;Name', 'Status&nbsp;Name', 'Value');
            $data = array('SNo','INDENT&nbsp;NO','INDENT&nbsp;SRL&nbsp;NO','Indent&nbsp;Date','Branch&nbsp;Name','Prject&nbsp;Name',
            'Indent&nbsp;Type','ItemCode','INDENT&nbsp;QTY','Item&nbsp;Desc','UOM&nbsp;CODE','Remarks','OutSt&nbsp;Qty','Cancelled&nbsp;Qty',
            'Cancelled&nbsp;Date','Indentstatus','Supp.&nbsp;Name','PO&nbsp;NUM','LINE&nbsp;ITEM&nbsp;NUM','PO&nbsp;DATE',
            'Po&nbsp;Quantity','Pending&nbsp;Qty&nbsp;PO','STORE&nbsp;RECEIVE&nbsp;NO','SR&nbsp;Date','SR&nbsp;Quantity');
            
        }

        if($subId==90){
            $data = array('SNo','Quality&nbsp;ID','Quality', 'Opening&nbsp;Bales', 'Receipt&nbsp;Bales'
                , 'Issued&nbsp;Bales'
                , 'Sold&nbsp;Bales'
                , 'Closing&nbsp;Bales'
                , 'Opening&nbsp;Drums'
                , 'Receipt&nbsp;Drums'
                , 'Issued&nbsp;Drums'
                , 'Sold&nbsp;Drums'
                , 'Closing&nbsp;Drums'
                , 'Opening&nbsp;Wt.&nbsp;(QNT)'
                , 'Receipt&nbsp;Wt.&nbsp;(QNT)'
                , 'Issued&nbsp;Wt.&nbsp;(QNT)'
                , 'Sold&nbsp;Wt.&nbsp;(QNT)'
                , 'Closing&nbsp;Wt.&nbsp;(QNT)'
                , 'Avg&nbsp;Issue&nbsp;Rate'
                , 'Avg&nbsp;Issued&nbsp;Value&nbsp;(In&nbsp;Lakhs)'
            
            );
        }

        if($subId==91){
            $data = array('SNo','J&nbsp;Code','Quality', 
            'Opening&nbsp;Weight', 
            'Receipt&nbsp;Weight',
            'Issued&nbsp;Weight',
            'Closing&nbsp;Weight',

            'Opening&nbsp;Bales', 
            'Receipt&nbsp;Bales',
            'Issued&nbsp;Bales' ,
            'Closing&nbsp;Bales',

            'Opening&nbsp;Drums', 
            'Receipt&nbsp;Drums',
            'Issued&nbsp;Drums' ,
            'Closing&nbsp;Drums'         
            
            );
        
        }
        if($subId==924){
            $data = array('SNo','Issue&nbsp;allDate','Mr&nbsp;No','Quality','Godown&nbspID',
            'Pack&nbsp;Type','Quantity','Weight','Unit', 
            'Rate', 'Issue&nbsp;Value'
                , 'MR&nbsp;Line&nbsp;No','Quality&nbsp;ID','Godown&nbsp;Name' ,'Status'            
            
            );
        
        }
        if($subId==96){
            $data = array('SNo',
            'MR&nbsp;Date',
            'Mr&nbsp;No',
            'Quality&nbsp;ID',
            'Quality',
            'MR&nbsp;ID',
            'Godown&nbsp;Name',
            'MR&nbsp;Line&nbsp;No',
            'Unit',
            'Recp&nbsp;Bales/Drums',
            'Recp&nbsp;Weight',
            'Issue&nbsp;Date',
            'Issue&nbsp;Quality',
            'Issue&nbsp;Bales/Drums', 
            'Issue&nbsp;Weight', 
            'Total&nbspIssue&nbspBales/Drums', 
            'Total&nbspIssue&nbspWeight', 
            'Balance&nbspBales/Drums' ,
            'Balance&nbspWeight' ,
            );

            
        }        

        if($subId==530){
            $data = array(
                'SNo',
                'MR&nbsp;Date',
                'MR&nbsp;No',
                'Quality&nbsp;ID',
                'Quality',
                'Godown&nbsp;ID',
                'Godown&nbsp;Name',
                'Status',
                'MR&nbspLine&nbsp;No',
                'Bales',
                'Issue&nbsp;Bales',
                'Sold&nbsp;Bales',
                'Bales&nbsp;Stock',
                'Drums',
                'Drums&nbsp;Issued',
                'Drums&nbsp;Sold',
                'Drums&nbsp;Stock',
                'Receipt&nbsp;Wt',
                'Issued&nbsp;Wt',
                'Sold&nbspWt',
                'Stock&nbspQnt'


            );
        
        }
        if($subId==93){
            $data = array(
                'SNo',
                'Godown&nbsp;ID',
                'item&nbsp;desc',
                'Quality',
                'Bales',
                'Drums',
                'Weight',
                'QNT',
                'Quality&nbsp;ID',
                'Godown&nbsp;Name'
                


            );
        
        }

        if($subId==227){
            $data = array(
                'SNo',
                'Supp&nbsp;Code',
                'Supplier&nbsp;Name',
                'Total&nbsp;MR',
                'Total&nbsp;Pass',
                'Total&nbsp;Claim',
                'Pass&nbsp;percent',
                'Claim&nbsp;percent');
        
        }     
        if($subId==228){
            $data = array(
                'SNo',
                'SUPP&nbsp;CODE',
                'SUPPLIER&nbsp;NAME',
                'MR&nbsp;NO',
                'MR&nbsp;DATE;',
                'JUTE&nbsp;TYPE',
                'QUALITY',
                'CONDITION',
                'ADVISED&nbsp;CLAIM&nbsp;KGS',
                'ACTUAL&nbsp;CLAIM&nbsp;KGS',
                'DEVIATION&nbsp;KGS'
            );
        
        }  
        if($subId==229){
            $data = array(
                'SNo',
                'Supp&nbsp;Code',
                'Supplier&nbsp;Name',
                'Mukham',
                'Avg&nbsp;Supplied&nbsp;Moisture',
                'Avg&nbsp;Mukam&nbsp;Moisture',
                'Deviation'
                
            );
        
        }    
        if($subId==477){
            $data = array(
                'Year&nbsp;Month',
                'Bales',
                'Issue&nbsp;Bales',
                'Sold&nbsp;Bales',
                'Drums',
                'Drums&nbsp;Issued',
                'Drums&nbsp;Sold',
                'Receipt&nbsp;Wt&nbsp;QNT',
                'Issued&nbsp;Wt&nbsp;QNT',
                'Sold&nbsp;Wt&nbsp;QNT',
                
            );
        
        }            
        if($subId==694){
            $data = array(
                'Quality',
                'Open&nbsp;Stock(Kgs)',
                'Receipt&nbsp;(Kgs)',
                'Issue&nbsp;(Kgs)',
                'Closing&nbsp;(Kgs)',
                 
            );
        
        }            
        if($subId==496){
            $data = array(
                'tran&nbsp;date',
                'Bales',
                'Issue&nbsp;Bales',
                'Sold&nbsp;Bales',
                'Drums',
                'Drums&nbsp;Issued',
                'Drums&nbsp;Sold',
                'Receipt&nbsp;Wt&nbsp;QNT',
                'Issued&nbsp;Wt&nbsp;QNT',
                'Sold&nbsp;Wt&nbsp;QNT',
                
            );
        

        }     
        if($subId==528){
            $data = array(
                'MR&nbsp;ID',
                'MR&nbsp;Suplier',
                'MR&nbsp;Print&nbsp;No',
                'Gate&nbsp;Ref&nbsp;No',
                'MR&nbsp;Date',
                'Invoice&nbsp;Date',
                'Invoice&nbsp;No',
                'Customer&nbsp;Name',
                'Created&nbsp;By',
                'Amount',
                'Invoice&nbsp;Status',
                'Mukam',
                'Sales&nbsp;Order&nbsp;No',
                'Bale/loose',
                
            );
        
        }          
        if($subId==261){
            $data = array(
                'S.No',
                'Category',
                'PlanName',
                'Bales',
                'Drums',
                'YarnType',
                'Qualities',
                'Percentage',
                'Actual&nbsp;Issuee',
                'Desired&nbsp;Issue',
                'Deviation',
                'Deviation&nbsp;Percentage',
                'Bales&nbsp;Or&nbsp;Drums',
                'Value',
                'Total&nbsp;Plan&nbsp;Weight',
                
            );
        
        }        
        if($subId==154){
            $data = array(
                'no' => 'S.No',
                'ID' => 'ID',
                'Sequence_No' => 'Sequence No',
                'Date' => 'Date',
                'Project_Type' => 'Project&nbsp;Type',
                'Title' => 'Title',
                'Client' => 'Client',
                'Category' => 'Category',
                'Branch_Name' => 'Branch&nbsp;Name',
                'Status_Name' => 'Status&nbsp;Name',
                'Value' => 'Value'
                
            );
        
        }   

        if($subId==493){
            $data = array(
                'indent_no' => 'Indent&nbsp;No',
	            'Indent_date' => 'Indent&nbsp;Date',
	            'prj_name' => 'Prj&nbsp;Name',
	            'indent_type' => 'Indent&nbsp;ype',
	            'indentstatus' => 'Indent&nbsp;Status',
	            'itemcode' => 'Item&nbsp;Code',
	            'item_desc' => 'Item&nbsp;Desc',
	            'uom_code' => 'Uom_&nbsp;Code',
	            'indent_qty' => 'Indent&nbsp;Qty',
	            'cancelled_qty' => 'Cancelled_&nbsp;Qty',
	            'poqty' => 'PO&nbsp;Qty',
	            'srqty' => 'SR&nbsp;Qty',
	            'qty_outstanding_for_po' => 'Qty&nbsp;Outstanding&nbsp;For&nbsp;PO',
	            'qty_outstanding_for_receive' => 'Qty&nbsp;Outstanding&nbsp;For&nbsp;Receive',
	            'outstanding_for_days' => 'Outstanding&nbsp;For&nbsp;(Days)',
                               
            );
        }
 
        if($subId==647){
            $data = array(
                'eb_no' => 'Emp&nbsp;Code',
	            'empname' => 'Employee&nbsp;Name',
	            'prod' => 'Production',
	            'whrs' => 'Working&nbsp;Hours',
	            'prod_8hrs' => 'Prod&nbsp;/&nbsp;8&nbsp;Hours',
	            'target_8hrs' => 'Target&nbsp;Prod&nbsp;/8&nbsp;Hours',
	            'diff' => 'Difference',
	            'eff' => 'Eff(%)',
	            'WND_GROUP_NAME' => 'Quality&nbsp;Type',
                                
            );
        }
        if($subId==675){
            $data = array(
                'line_number' => 'Line&nbsp;Number',
	            'mechine_name' => 'Loom&nbsp;No',
	            'proda' => 'Prod_A',
	            'prodb' => 'Prod_B',
	            'prodc' => 'Prod_C',
	            'prodtot' => 'Total&nbsp;Prod',
	            'effa' => 'Eff_A(%)',
	            'effb' => 'Eff_B(%)',
	            'effc' => 'Eff_C(%)',
	            'offf' => 'Eff_oveall(%)',
	            'peff' => 'Last&nbsp;15&nbsp;Days&nbsp;Eff(%)',
 	                            
            );
   //         var_dump($data);
        }

        if($subId==679){
            $data = array(
                'ebno' => 'Emp&nbsp;Code',
	            'wname' => 'Emp&nbsp;Name',
	            'mcnos' => 'Loom&nbsp;No',
	            'shift' => 'Shift',
	            'prod' => 'Prod',
	            'eff' => 'Eff(%)',
	            'atthrs' => 'Hrs&nbsp;Wrkd',
	            'ceff' => 'Cur&nbsp;Fne&nbsp;Eff(%)',
	            'catthrs' => 'Cur&nbsp;Fne&nbsp;Hrs',
	            'leff' => 'Last&nbsp;Fne&nbsp;Eff(%)',
	            'latthrs' => 'Last&nbsp;Fne&nbsp;Hrs',
	            'otheff' => 'Others&nbsp;Eff(%)',
  	                            
            );
   //         var_dump($data);
        }

        if($subId==680){
            $data = array(
                'ebno' => 'Emp&nbsp;Code',
	            'wname' => 'Emp&nbsp;Name',
	            'sftmc' => 'Shift',
	            'davgnodoff' => 'Std&nbsp;Avg&nbsp;Doff',
	            'dstddoffwt' => 'Std&nbsp;Doff&nbsp;wt',
	            'dactavnogdoff' => 'Avg&nbsp;Doff',
	            'dactacgdoffwt' => 'Avg&nbsp;Doff&nbsp;Wt',
	            'datthrs' => 'Wrk&nbsp;Hours',
	            'deff' => 'Eff(%)',
	            'ceff' => 'Cur&nbsp;Fne&nbsp;Eff(%)',
	            'catthrs' => 'Cur&nbsp;Fne&nbsp;Hrs',
	            'leff' => 'Last&nbsp;Fne&nbsp;Eff(%)',
	            'latthrs' => 'Last&nbsp;Fne&nbsp;Hrs',
                'tactdof' => 'Shed&nbsp;Avg&nbsp;Doff',
	            'tactdofwt' => 'Shed&nbsp;Avg&nbsp;Doff',
                'oactavnogdoff' => 'Oth&nbsp;Avg&nbsp;Doff',
	            'oactacgdoffwt' => 'Oth&nbsp;Avg&nbsp;Doff&nbsp;Wt',
	            'oeff' => 'Others&nbsp;Eff(%)',





  	                            
            );
   //         var_dump($data);
        }

        if($subId==681){
            $data = array(
                'ebno' => 'Emp&nbsp;Code',
	            'wname' => 'Emp&nbsp;Name',
                'spg_group' => 'Group&nbsp;Name',
                'mcnos' => 'Mc&nbsp;No',
	            'shift' => 'Shift',
	            'dprod' => 'Prod',
	            'davgprod' => 'Avg&nbsp;Prod',
	            'deff' => 'Eff(%)',
	            'dwhrs' => 'Hrs&nbsp;Wrkd',
	            'cavgprod' => 'Cur&nbsp;Fne&nbsp;Avg&nbsp;Prod',
	            'ceff' => 'Cur&nbsp;Fne&nbsp;Eff(%)',
	            'cwhrs' => 'Cur&nbsp;Fne&nbsp;Hrs',
	            'lavgprod' => 'Last&nbsp;Fne&nbsp;Avg&nbsp;Prod',
	            'leff' => 'Last&nbsp;Fne&nbsp;Eff(%)',
	            'lwhrs' => 'Last&nbsp;Fne&nbsp;Hrs',
	            'oavgprod' => 'Others&nbsp;Fne&nbsp;Avg&nbsp;Prod',
	            'oeff' => 'Others&nbsp;Eff(%)',
  	                            
            );
   //         var_dump($data);
        }


        if($subId==674){


            if(($fromdt) && $todt){
                $startDate = strtotime($fromdt);
                $endDate = strtotime($todt);
            //   echo 'start='.$startDate.'=';
            //   echo 'end='.$endDate.'=';

                $data = array('Loom_no' => 'Loom No');
            
                for ($currentDate = $startDate; $currentDate <= $endDate; $currentDate += (86400)) {
                    $date = date('Y-m-d', $currentDate);
                    $day = date("d",$currentDate)." ".substr((date("D",$currentDate)),0,2)." ".substr((date("M",$currentDate)),0,3); //date('d', $currentDate);
                    //2024-01-29
                    $day=substr($date,8,2).'/'.substr($date,5,2);
                  //  $day=$date;
                    $rangArray[] = $date;
                    $data = array_merge($data, [$day => $day]);
                }
                $day='Total_Days';
                $data = array_merge($data, [$day => $day]);
                $day='Avg_eff';
                $data = array_merge($data, [$day => $day]);
            
               
            }
     //    var_dump($data);

        }

        if($subId==676){


            if(($fromdt) && $todt){
                $startDate = strtotime($fromdt);
                $endDate = strtotime($todt);
            //   echo 'start='.$startDate.'=';
            //   echo 'end='.$endDate.'=';
                $data = array('ebno'=> 'EB&nbsp;No','wname'=>'Employee&nbsp;Name');
            
//                $data = array('Loom_no' => 'Loom No');
            
                for ($currentDate = $startDate; $currentDate <= $endDate; $currentDate += (86400)) {
                    $date = date('Y-m-d', $currentDate);
                    $day = date("d",$currentDate)." ".substr((date("D",$currentDate)),0,2)." ".substr((date("M",$currentDate)),0,3); //date('d', $currentDate);
                    //2024-01-29
                    $day=substr($date,8,2).'/'.substr($date,5,2);
                  //  $day=$date;
                    $rangArray[] = $date;
                    $data = array_merge($data, [$day => $day]);
                }
                $day='Total_Days';
                $data = array_merge($data, [$day => $day]);
                $day='Avg_eff';
                $data = array_merge($data, [$day => $day]);
            
               
            }
     //    var_dump($data);

        }
 
        if($subId==586){


            if(($fromdt) && $todt){
                $startDate = strtotime($fromdt);
                $endDate = strtotime($todt);
            //   echo 'start='.$startDate.'=';
            //   echo 'end='.$endDate.'=';
                $data = array('ebno'=> 'EB&nbsp;No','wname'=>'Employee&nbsp;Name');
            
//                $data = array('Loom_no' => 'Loom No');
            
                for ($currentDate = $startDate; $currentDate <= $endDate; $currentDate += (86400)) {
                    $date = date('Y-m-d', $currentDate);
                    $day = date("d",$currentDate)." ".substr((date("D",$currentDate)),0,2)." ".substr((date("M",$currentDate)),0,3); //date('d', $currentDate);
                    //2024-01-29
                    $day=substr($date,8,2).'/'.substr($date,5,2);
                  //  $day=$date;
                    $rangArray[] = $date;
                    $data = array_merge($data, [$day => $day]);
                }
                $day='Total_Days';
                $data = array_merge($data, [$day => $day]);
                $day='Average';
                $data = array_merge($data, [$day => $day]);
            
//var_dump($data); 
            }
     //    var_dump($data);

        }
 
       if($subId==695){


            if(($fromdt) && $todt){
                $startDate = strtotime($fromdt);
                $endDate = strtotime($todt);
            //   echo 'start='.$startDate.'=';
            //   echo 'end='.$endDate.'=';
                $data = array('ebno'=> 'EB&nbsp;No','wname'=>'Employee&nbsp;Name');
            
//                $data = array('Loom_no' => 'Loom No');
            
                for ($currentDate = $startDate; $currentDate <= $endDate; $currentDate += (86400)) {
                    $date = date('Y-m-d', $currentDate);
                    $day = date("d",$currentDate)." ".substr((date("D",$currentDate)),0,2)." ".substr((date("M",$currentDate)),0,3); //date('d', $currentDate);
                    //2024-01-29
                    $day=substr($date,8,2).'/'.substr($date,5,2);
                  //  $day=$date;
                    $rangArray[] = $date;
                    $data = array_merge($data, [$day => $day]);
                }
                $day='Total_Days';
                $data = array_merge($data, [$day => $day]);
                $day='Average';
                $data = array_merge($data, [$day => $day]);
            
//var_dump($data); 
            }
     //    var_dump($data);

        }
 
 

        if($subId==685){


            if(($fromdt) && $todt){
                $startDate = strtotime($fromdt);
                $endDate = strtotime($todt);
                $from_date=$fromdt;
                $to_date=$todt;
                $comp = $this->session->userdata('companyId');
                $companyId=$comp;
            //   echo 'start='.$startDate.'=';
            //   echo 'end='.$endDate.'=';
                $data = array('ebno'=> 'EB&nbsp;No','wname'=>'Employee&nbsp;Name');
            
//                $data = array('Loom_no' => 'Loom No');
                $sqlp="select date_to from EMPMILL12.tbl_report_period trp where trp.date_from 
                between '".$from_date."' and '".$to_date."' and company_id =".$companyId ." order by date_to";
                $queryp = $this->db->query($sqlp);
       
                $datap=$queryp->result();
                foreach ($datap as $recordp) {
                    $date=$recordp->date_to;
                    $day=substr($date,8,2).'/'.substr($date,5,2);
                    $rangArray[] = $date;
                    $data = array_merge($data, [$day => $day]);
                }
                $day='Total_Days';
                $dayw='No of Times';
                
                $data = array_merge($data, [$day => $dayw]);
                $day='Average';
                $data = array_merge($data, [$day => $day]);

/*
                for ($currentDate = $startDate; $currentDate <= $endDate; $currentDate += (86400)) {
                    $date = date('Y-m-d', $currentDate);
                    $day = date("d",$currentDate)." ".substr((date("D",$currentDate)),0,2)." ".substr((date("M",$currentDate)),0,3); //date('d', $currentDate);
                    //2024-01-29
                    $day=substr($date,8,2).'/'.substr($date,5,2);
                  //  $day=$date;
                    $rangArray[] = $date;
                    $data = array_merge($data, [$day => $day]);
                }
                $day='Total_Days';
                $data = array_merge($data, [$day => $day]);
                $day='Avg_eff';
                $data = array_merge($data, [$day => $day]);
 */           
//var_dump($data); 
            }
     //    var_dump($data);

        }
        if ($subId == 650) {

            if (($fromdt) && $todt) {
                $startDate = strtotime($fromdt);
                $endDate = strtotime($todt);
                $from_date = $fromdt;
                $to_date = $todt;
                $comp = $this->session->userdata('companyId');
                $companyId = $comp;
                //   echo 'start='.$startDate.'=';
                //   echo 'end='.$endDate.'=';
                $data = array('emp_code' => 'EB&nbsp;No', 'wname' => 'Employee&nbsp;Name', 'status_name' => 'Status'
                , 'dept_desc' => 'Department', 'cata_desc' => 'Catagory'
            );
          /*
                $sqlp = "select date_to from EMPMILL12.tbl_report_period trp where trp.date_from 
                between '" . $from_date . "' and '" . $to_date . "' and company_id =" . $companyId . " order by date_to";
          */
                $sqlp="WITH RECURSIVE YearMonths AS (
                SELECT DATE_FORMAT('" . $from_date . "', '%Y%m') AS yearmn, '" . $from_date . "' AS start_date
                UNION ALL
                SELECT DATE_FORMAT(DATE_ADD(start_date, INTERVAL 1 MONTH), '%Y%m'), DATE_ADD(start_date, INTERVAL 1 MONTH)
                FROM YearMonths
                WHERE start_date <= '" . $to_date . "'  ) 
                SELECT yearmn
                FROM YearMonths";
                $sqlp = "WITH RECURSIVE YearMonths AS (
        SELECT DATE_FORMAT('" . $from_date . "', '%Y%m') AS yearmn,  '" . $from_date . "' AS start_date
        UNION ALL
        SELECT DATE_FORMAT(DATE_ADD(start_date, INTERVAL 1 MONTH), '%Y%m'), DATE_ADD(start_date, INTERVAL 1 MONTH)
        FROM YearMonths
        WHERE DATE_ADD(start_date, INTERVAL 1 MONTH) <= '" . $to_date . "'
    )
    SELECT yearmn
    FROM YearMonths";

                $queryp = $this->db->query($sqlp);
                $datap = $queryp->result();
                foreach ($datap as $recordp) {
                    $date = $recordp->yearmn;
                    $day = substr($date, 0, 4) . '/' . substr($date, 4, 2);
                    $rangArray[] = $date;
                    $data = array_merge($data, [$day => $day]);
                }
                $day = 'Average';
               $dayw = 'Total Days';
               $data = array_merge($data, [$day => $dayw]);
               $day = 'Total_Days';
                $dayw = 'Avg Days';
                $data = array_merge($data, [$day => $dayw]);
//                var_dump($data);

            }

        }


        if($subId==677){


            if(($fromdt) && $todt){
                $startDate = strtotime($fromdt);
                $endDate = strtotime($todt);
            //   echo 'start='.$startDate.'=';
            //   echo 'end='.$endDate.'=';
                $data = array('ebno'=> 'EB&nbsp;No','wname'=>'Employee&nbsp;Name');
            
//                $data = array('Loom_no' => 'Loom No');
            
                for ($currentDate = $startDate; $currentDate <= $endDate; $currentDate += (86400)) {
                    $date = date('Y-m-d', $currentDate);
                    $day = date("d",$currentDate)." ".substr((date("D",$currentDate)),0,2)." ".substr((date("M",$currentDate)),0,3); //date('d', $currentDate);
                    //2024-01-29
                    $day=substr($date,8,2).'/'.substr($date,5,2);
                  //  $day=$date;
                    $rangArray[] = $date;
                    $data = array_merge($data, [$day => $day]);
                }
                $day='avgeff';
                $dday='Avg eff(%)';
                $data = array_merge($data, [$day => $dday]);
                $day='Total_Days';
                $dday='No of Days';
                $data = array_merge($data, [$day => $dday]);
                $day='inc_days';
                $dday='Inc Days';
                $data = array_merge($data, [$day => $dday]);
                $day='total_inc_amt';
                $dday='Inc Amount';
                $data = array_merge($data, [$day => $dday]);
            
               
            }
     //    var_dump($data);

        }


/*
        if($subId==674){
            $data = array(
                'line_number' => 'Line&nbsp;Number',
	            'mechine_name' => 'Loom&nbsp;No',
	            'proda' => 'Prod_A',
	            'prodb' => 'Prod_B',
	            'prodc' => 'Prod_C',
	            'prodtot' => 'Total&nbsp;Prod',
	            'effa' => 'Eff_A(%)',
	            'effb' => 'Eff_B(%)',
	            'effc' => 'Eff_C(%)',
	            'offf' => 'Eff_oveall(%)',
	            'peff' => 'Last&nbsp;15&nbsp;Days&nbsp;Eff(%)',
 	                            
            );
        }
*/
        if ($subId == 688) {
            $data = array(
                'po_no' => 'SL&nbsp;No',
                'Q_CODE' => 'Quality&nbsp;   (1)',
                'CTYPE' => 'Count&nbsp;&&nbsp;Type&nbsp;No&nbsp;of&nbsp;Spindle   (2)   ',
                'speed' => 'Speed   (3)',
                'twist_per_inch' => 'TPI   (4)',
                'tar_eff' => 'Target&nbsp;Eff%  (5)',
                'prd_frm_n_count' => 'Prd/Frm/N/Count  (6)',
                'prd_frm_a_count' => 'Prd/Frm/A/Count  (7)',
                'prod_per_mt' => 'Production&nbsp;&nbsp;MT  (8)',
                'act_countt' => 'Actual&nbsp;Count  (9)',
                'no_of_fram' => 'No.&nbsp;Of&nbsp;Frames  (10)',
                'prod_per_mtt' => 'Production&nbsp;&nbsp;MT  (11)',
                'prod_per_winder' => 'Production/Winder  (12)',
                'prd_per_fram' => 'Production/Frames  (13)',
                'var_prd_fram' => 'Var/Production/Frames (14)',
                'con_std_cnt_prd_fram' => 'Conv.on/Std/Cnt  Prd/frm  (15)',
                'act_count' => 'Actual&nbsp;Count  (16)',
                'no_of_frms' => 'No.&nbsp;Of&nbsp;Frames  (17)',
                'PRODT' => 'Production&nbsp;&nbsp;MT  (18)',
                'mprod_per_winder' => 'Production/Winder  (19)',
                'mprd_per_fram' => 'Production/Frames  (20)',
                'tar_prod_per_mt' => 'Tar&nbsp;Production&nbsp;&nbsp;MT  (21)',
                'prd_frm_a_countt' => 'Target&nbsp;Prd/Frm  (22)',
                'mvar_prd_fram' => 'Var/Production/Frames ',
                'mcon_std_cnt_prd_fram' => 'Conv.on/Std/Cnt  Prd/frm ',
                'eff' => 'Efficiency(%)     Today',
                'meff' => 'Efficiency(%)    Todate',

            );
        }

        if($subId==155){
            $data = array(
                'Sno' => 'S.No',
                'indent_no' => 'Indent&nbsp;No',
	            'Indent_date' => 'Indent&nbsp;Date',
	            'prj_name' => 'Prj&nbsp;Name',
	            'indent_type' => 'Indent&nbsp;Type',
	            'indentstatus' => 'Indent&nbsp;Status',
	            'itemcode' => 'Item&nbsp;Code',
	            'item_desc' => 'Item&nbsp;Desc',
	            'uom_code' => 'Uom_&nbsp;Code',
	            'indent_qty' => 'Indent&nbsp;Qty',
	            'cancelled_qty' => 'Cancelled_&nbsp;Qty',
	            'poqty' => 'PO&nbsp;Qty',
	            'srqty' => 'SR&nbsp;Qty',
	            'qty_outstanding_for_po' => 'Qty&nbsp;Outstanding&nbsp;For&nbsp;PO',
	            'qty_outstanding_for_receive' => 'Qty&nbsp;Outstanding&nbsp;For&nbsp;Receive',
	            'outstanding_for_days' => 'Outstanding&nbsp;For&nbsp;(Days)',
                               
            );
        }

        if($subId==532){
            $data = array(
                'Sno' => 'S.No',
                'store_receipt_no' => 'SR&nbsp;No',
	            'recpdate' => 'SR&nbsp;Date',
	            'challanno' => 'Challan&nbsp;No',
	            'challanno_date' => 'Challan&nbsp;Date',
	            'supp_name' => 'Supplier&nbsp;Name',
	            'tot_amount' => 'SR&nbsp;Amount'            );
        }


        if($subId==492){
            $data = array(
                'po_no' => 'Po&nbsp;No',
	            'poapprovedate' => 'Po&nbsp;Approve&nbsp;Date',
	            'indent_type_desc' => 'Indent&nbsp;Type&nbsp;Desc',
	            'prj_name' => 'Prj&nbsp;Name',
	            'supp_name' => 'Supp&nbsp;Name',
	            'itemcode' => 'Item&nbsp;Code',
	            'item_desc' => 'Item&nbsp;Desc',
	            'uom_code' => 'Uom&nbsp;Code',
	            'make' => 'Make',
	            'qty' => 'Quantity',
	            'cancelled_qty' => 'Cancelled&nbsp;Qty',
	            'rate' => 'Rate',
	            'tax_type_name' => 'Tax&nbsp;Type&nbsp;Name',
	            'tax_percentage' => 'Tax&nbsp;Percentage',
	            'item_value' => 'Item&nbsp;Value',
                'tax_amount' => 'Tax&nbsp;Amount',
                'total_amount'=>'Total&nbsp;Amount',
                'status_name'=>'Status&nbsp;Name',
                'remarks' =>'Remarks',
                'inwqty'=>'Inwqty',
                'qty_to_be_receive'=>'Qty&nbsp;To&nbsp;Be&nbsp;Receive',
                'outstanding_for_days'=>'Outstanding&nbsp;For&nbsp;(Days)',

                               
            );
        }
        if($subId==661){
            $data = array(
                'po_no' => 'Po&nbsp;No',
	            'poapprovedate' => 'Po&nbsp;Approve&nbsp;Date',
	            'indent_type_desc' => 'Indent&nbsp;Type&nbsp;Desc',
	            'prj_name' => 'Prj&nbsp;Name',
	            'supp_name' => 'Supp&nbsp;Name',
	            'itemcode' => 'Item&nbsp;Code',
	            'item_desc' => 'Item&nbsp;Desc',
	            'uom_code' => 'Uom&nbsp;Code',
	            'make' => 'Make',
	            'qty' => 'Quantity',
	            'cancelled_qty' => 'Cancelled&nbsp;Qty',
	            'rate' => 'Rate',
	            'tax_type_name' => 'Tax&nbsp;Type&nbsp;Name',
	            'tax_percentage' => 'Tax&nbsp;Percentage',
	            'item_value' => 'Item&nbsp;Value',
                'tax_amount' => 'Tax&nbsp;Amount',
                'total_amount'=>'Total&nbsp;Amount',
                'status_name'=>'Status&nbsp;Name',
                'remarks' =>'Remarks',
                'inwqty'=>'Inwqty',
                'qty_to_be_receive'=>'Qty&nbsp;To&nbsp;Be&nbsp;Receive',
                'outstanding_for_days'=>'Outstanding&nbsp;For&nbsp;(Days)',

                               
            );
        }
        if($subId==660){
            $data = array(
                'po_no' => 'Po&nbsp;No',
	            'poapprovedate' => 'Po&nbsp;Approve&nbsp;Date',
	            'indent_type_desc' => 'Indent&nbsp;Type&nbsp;Desc',
	            'prj_name' => 'Prj&nbsp;Name',
	            'supp_name' => 'Supp&nbsp;Name',
	            'itemcode' => 'Item&nbsp;Code',
	            'item_desc' => 'Item&nbsp;Desc',
	            'uom_code' => 'Uom&nbsp;Code',
	            'make' => 'Make',
	            'qty' => 'Quantity',
	            'cancelled_qty' => 'Cancelled&nbsp;Qty',
	            'rate' => 'Rate',
	            'tax_type_name' => 'Tax&nbsp;Type&nbsp;Name',
	            'tax_percentage' => 'Tax&nbsp;Percentage',
	            'item_value' => 'Item&nbsp;Value',
                'tax_amount' => 'Tax&nbsp;Amount',
                'total_amount'=>'Total&nbsp;Amount',
                'status_name'=>'Status&nbsp;Name',
                'remarks' =>'Remarks',
                'inwqty'=>'Inwqty',
                'qty_to_be_receive'=>'Qty&nbsp;To&nbsp;Be&nbsp;Receive',
                'outstanding_for_days'=>'Outstanding&nbsp;For&nbsp;(Days)',

                               
            );
        }
        if($subId==538){
            $data = array(
                'po_no' => 'Po&nbsp;No',
	            'poapprovedate' => 'Po&nbsp;Approve&nbsp;Date',
	            'indent_type_desc' => 'Indent&nbsp;Type&nbsp;Desc',
	            'prj_name' => 'Prj&nbsp;Name',
	            'supp_name' => 'Supp&nbsp;Name',
	            'itemcode' => 'Item&nbsp;Code',
	            'item_desc' => 'Item&nbsp;Desc',
	            'uom_code' => 'Uom&nbsp;Code',
	            'make' => 'Make',
	            'qty' => 'Quantity',
	            'cancelled_qty' => 'Cancelled&nbsp;Qty',
	            'rate' => 'Rate',
	            'tax_type_name' => 'Tax&nbsp;Type&nbsp;Name',
	            'tax_percentage' => 'Tax&nbsp;Percentage',
	            'item_value' => 'Item&nbsp;Value',
                'tax_amount' => 'Tax&nbsp;Amount',
                'total_amount'=>'Total&nbsp;Amount',
                'status_name'=>'Status&nbsp;Name',
                'remarks' =>'Remarks',
                'inwqty'=>'Inwqty',
                'qty_to_be_receive'=>'Qty&nbsp;To&nbsp;Be&nbsp;Receive',
                'outstanding_for_days'=>'Outstanding&nbsp;For&nbsp;(Days)',

                               
            );
        }
        if($subId==662){
            $data = array(
                'po_no' => 'Po&nbsp;No',
	            'poapprovedate' => 'Po&nbsp;Approve&nbsp;Date',
	            'indent_type_desc' => 'Indent&nbsp;Type&nbsp;Desc',
	            'prj_name' => 'Prj&nbsp;Name',
	            'supp_name' => 'Supp&nbsp;Name',
	            'itemcode' => 'Item&nbsp;Code',
	            'item_desc' => 'Item&nbsp;Desc',
	            'uom_code' => 'Uom&nbsp;Code',
	            'make' => 'Make',
	            'qty' => 'Quantity',
	            'cancelled_qty' => 'Cancelled&nbsp;Qty',
	            'rate' => 'Rate',
	            'tax_type_name' => 'Tax&nbsp;Type&nbsp;Name',
	            'tax_percentage' => 'Tax&nbsp;Percentage',
	            'item_value' => 'Item&nbsp;Value',
                'tax_amount' => 'Tax&nbsp;Amount',
                'total_amount'=>'Total&nbsp;Amount',
                'status_name'=>'Status&nbsp;Name',
                'remarks' =>'Remarks',
                'inwqty'=>'Inwqty',
                'qty_to_be_receive'=>'Qty&nbsp;To&nbsp;Be&nbsp;Receive',
                'outstanding_for_days'=>'Outstanding&nbsp;For&nbsp;(Days)',

                               
            );
        }
        if($subId==663){
            $data = array(
                'po_no' => 'Po&nbsp;No',
	            'poapprovedate' => 'Po&nbsp;Approve&nbsp;Date',
	            'indent_type_desc' => 'Indent&nbsp;Type&nbsp;Desc',
	            'prj_name' => 'Prj&nbsp;Name',
	            'dept_desc' => 'Department',
	            'supp_name' => 'Supp&nbsp;Name',
	            'itemcode' => 'Item&nbsp;Code',
	            'item_desc' => 'Item&nbsp;Desc',
	            'uom_code' => 'Uom&nbsp;Code',
	            'make' => 'Make',
	            'qty' => 'Quantity',
	            'cancelled_qty' => 'Cancelled&nbsp;Qty',
	            'rate' => 'Rate',
	            'tax_type_name' => 'Tax&nbsp;Type&nbsp;Name',
	            'tax_percentage' => 'Tax&nbsp;Percentage',
	            'item_value' => 'Item&nbsp;Value',
                'tax_amount' => 'Tax&nbsp;Amount',
                'total_amount'=>'Total&nbsp;Amount',
                'status_name'=>'Status&nbsp;Name',
                'remarks' =>'Remarks',
                'inwqty'=>'Inwqty',
                'qty_to_be_receive'=>'Qty&nbsp;To&nbsp;Be&nbsp;Receive',
                'outstanding_for_days'=>'Outstanding&nbsp;For&nbsp;(Days)',

                               
            );
        }
        if($subId==499){
            $data = array(
                'itemcode' => 'Item&nbsp;Code',
	            'item_name' => 'Item&nbsp;Description',
                'uom_code' => 'Unit',
                'tran_type' => 'Transaction&nbsp;Type',
	            'tran_date1' => 'Transaction&nbsp;Date',
	            'doc_no' => 'Doc&nbsp;No',
	            'open_qty' => 'Opening&nbsp;Qty',
	            'open_val' => 'Opening&nbsp;Value',
	            'tranrecv_qty' => 'Received&nbsp;Qty',
	            'tranrecv_val' => 'Received&nbsp;Value',
	            'tranissu_qty' => 'Issue&nbsp;Qty',
	            'tranissu_val' => 'Issue&nbsp;Value',
                'closing_qty' => 'Closing&nbsp;Qty',
                'closing_val' => 'Closing&nbsp;Value',

                               
            );
        }

        if($subId==533){
            $data = array(
                'indent_no' => 'Indent&nbsp;No',
	            'Indent_date' => 'Indent&nbsp;Date',
	            'prj_name' => 'Prj&nbsp;Name',
	            'indent_type' => 'Indent&nbsp;ype',
	            'indentstatus' => 'Indent&nbsp;Status',
	            'itemcode' => 'Item&nbsp;Code',
	            'item_desc' => 'Item&nbsp;Desc',
	            'uom_code' => 'Uom_&nbsp;Code',
	            'indent_qty' => 'Indent&nbsp;Qty',
	            'cancelled_qty' => 'Cancelled_&nbsp;Qty',
	            'poqty' => 'PO&nbsp;Qty',
	            'srqty' => 'SR&nbsp;Qty',
	            'qty_outstanding_for_po' => 'Qty&nbsp;Outstanding&nbsp;For&nbsp;PO',
	            'qty_outstanding_for_receive' => 'Qty&nbsp;Outstanding&nbsp;For&nbsp;Receive',
	            'outstanding_for_days' => 'Outstanding&nbsp;For&nbsp;(Days)',
                               
            );
        }

        if($subId==564){
            $data = array(
                'indent_no' => 'Indent&nbsp;No',
	            'Indent_date' => 'Indent&nbsp;Date',
	            'prj_name' => 'Prj&nbsp;Name',
	            'indent_type' => 'Indent&nbsp;ype',
	            'indentstatus' => 'Indent&nbsp;Status',
	            'itemcode' => 'Item&nbsp;Code',
	            'item_desc' => 'Item&nbsp;Desc',
	            'uom_code' => 'Uom_&nbsp;Code',
	            'indent_qty' => 'Indent&nbsp;Qty',
	            'cancelled_qty' => 'Cancelled_&nbsp;Qty',
	            'poqty' => 'PO&nbsp;Qty',
	            'srqty' => 'SR&nbsp;Qty',
	            'qty_outstanding_for_po' => 'Qty&nbsp;Outstanding&nbsp;For&nbsp;PO',
	            'qty_outstanding_for_receive' => 'Qty&nbsp;Outstanding&nbsp;For&nbsp;Receive',
	            'outstanding_for_days' => 'Outstanding&nbsp;For&nbsp;(Days)',
                               
            );
        }



        if($subId==603){
            $data = array(
                'no' => 'S.No',
                'Tran_No' => 'Tran&nbsp;No',
                'EB_No' => 'EB&nbsp;No',
                'Name' => 'Name',
                'Date' => 'Date',
                'Department' => 'Department',
                'Designation' => 'Designation',
                'Mark' => 'Mark',
                'Spell' => 'Spell',
                'Idle_Hours' => 'Idle&nbsp;Hours',
                'Spell_Hours' => 'Spell&nbsp;Hours',
                'Work_Hours' => 'Work&nbsp;Hours',
                'Source' => 'Source',
                'Type' => 'Type',
                'Status' => 'Status',
                'Remarks' => 'Remarks',                
            );
        
        }   

   


        if($subId==230){
            $data = array(
                'no' => 'S.No',
                'Item_Code' => 'Item&nbsp;Code',
                'Item_Description' => 'Item&nbsp;Description',
                'Unit' => 'Unit',
                'Min_Qty' => 'Min&nbsp;Qty',
                'Max_qty' => 'Max&nbsp;Qty',
                'Min_Order_qty' => 'Min&nbsp;Order&nbsp;Qty',
                'Stock_qty' => 'StockI&nbsp;Qty',
                'Pending_indent_qty' => 'Pending&nbsp;Indent&nbsp;Qty',
                'Pending_PO_Qty' => 'Pending&nbsp;PO&nbsp;Qty',
                'record_type' => 'Open&nbsp;Indent',
                'Qty_To_Be_Order' => 'Qty&nbsp;To_Be&nbsp;Order'
              );
        
        }   
        if($subId==233){
            $data = array(
                'no' => 'S.No',
                'dept_desc' => 'Department',
                'cost_desc' => 'Cast&nbsp;Description',
                'itemcode' => 'Item&nbsp;Code',
                'item_desc' => 'Item&nbsp;Description',
                'Production' => 'Production',
                'OVERHAULING' => 'Overhauling',
                'MAINTENANCE' => 'Maintenance',
                'CAPITAL' => 'Capital',
                '`GENERAL`' => 'General',
                'total_amt' => 'Total&nbsp;Value' 
              );
        
        }   

        if($subId==217){
            $data = array(
                'no' => 'S.No',
                'dept_desc' => 'Department',
                'cost_desc' => 'Cast&nbsp;Description',
                'Production' => 'Production',
                'OVERHAULING' => 'Overhauling',
                'MAINTENANCE' => 'Maintenance',
                'CAPITAL' => 'Capital',
                'GENERAL' => 'General',
                'total_amt' => 'Total&nbsp;Value' 
              );
        
        }   
        if($subId==503){
            $data = array(
                'group_code' => 'Group&nbsp;Code',
                'group_desc' => 'Group&nbsp;Description',
                'Production' => 'Production',
                'OVERHAULING' => 'Overhauling',
                'MAINTENANCE' => 'Maintenance',
                'CAPITAL' => 'Capital',
                'GENERAL' => 'General',
                'total_amt' => 'Total&nbsp;Value' 
              );
        
        }   
        if($subId==386){
            $data = array(
                'dept_desc' => 'Department',
                'Production' => 'Production',
                'OVERHAULING' => 'Overhauling',
                'MAINTENANCE' => 'Maintenance',
                'CAPITAL' => 'Capital',
                'GENERAL' => 'General',
                'total_amt' => 'Total&nbsp;Value' 
              );
        
        }   

        if($subId==248){
            $data = array(
                'no' => 'S.No',
                'mechine_name' => 'Mechine&nbsp;Name',
                'itemcode' => 'Item&nbsp;Code',
                'item_desc' => 'Item&nbsp;Description',
                'indent_type_desc' => 'Exp&nbsp;Type',
                'issue_qty' => 'Issue&nbsp;Qty',
                'iss_val' => 'Issue&nbsp;Value',
              );
        
        }   

        if($subId==415){
            $data = array(
                'no' => 'S.No',
                'itemcode' => 'Item&nbsp;Code',
                'item_name' => 'Item&nbsp;Description',
                'uom_code' => 'Unit',
                'open_qty' => 'Opening&nbsp;Qty',
                'open_val' => 'Opeing&nbsp;Value',
                'tranrecv_qty' => 'Received&nbsp;Qty',
                'tranrecv_val' => 'Received&nbsp;Value',
                'tranissu_qty' => 'Issued&nbsp;Qty',
                'tranissu_val' => 'Issued&nbsp;Value',
                'clos_qty' => 'Closing&nbsp;Qty',
                'clos_val' => 'Closing&nbsp;Value',
                'lrecpdate' => 'Last&nbsp;Receipt&nbsp;Date',
                'lissuedate' => 'Last&nbsp;Issued&nbsp;Date',
                'nodays' => 'Not&nbsp;Consumed&nbsp;For(No&nbsp;of&nbsp;Days)'
              );
        
        }   

        if($subId==185){
            $data = array(
                'no' => 'S.No',
                'Issue_No' => 'Issue&nbsp;Nos',
                'Issue_Date' => 'Issue&nbsp;Date',
                'Department' => 'Department',
                'Item_Code' => 'Item&nbsp;Code',
                'Item_Description' => 'Item&nbsp;Description',
                'Unit' => 'Unit',
                'Cost_Center' => 'Cost&nbsp;Center',
                'Issue_Quantity' => 'Quantity',
                'Issue_Value' => 'Value',
                'EXP_Type' => 'Exp&nbsp;Type',
                'Branch' => 'Branch',
                'SR_No' => 'SR&nbsp;No',
                'Mechine_Name' => 'Mechine&nbsp;Name'
             );
        
        }   

        if($subId==657){
            $data = array(
                'no' => 'S.No',
                'Date' => 'Attendance&nbsp;Date',
                'Spell' => 'Spell',
                'EB_No' => 'EB_No',
                'Name' => 'Name',
                'Department' => 'Department',
                'Designation' => 'Designation',
                'attendance_type' => 'Att_Type',
                'attendance_source' => 'Attendance&nbsp;Source',
                'Working_Hours' => 'Working&nbsp;Hours',
                'MC_Nos' => 'MC&nbsp;Nos',
                'remarks' => 'Remarks',                
            );
        
        }   


    
        if($subId==651){
            $data = array(
                'no' => 'S.No',
                'eb_id' => 'Emp&nbsp;Id',
                'emp_code' => 'EB&nbsp;No',
                'emp_name' => 'Name',
                'gender' => 'Gender',
                'cata_desc'=>'Catagory',
                'dept_code'=>'Dept&nbsp;Code',
                'dept_desc' => 'Department',
                'desig' => 'Designation',
                'date_of_birth' => 'Date&nbsp;of&nbsp;Birth',
                'date_of_join' => 'Date&nbsp;of&nbsp;Joining',
                'esi_no' => 'ESI&nbsp;No',
                'pf_no' => 'PF&nbsp;No',
                'pf_date_of_join' => 'Date&nbsp;of&nbsp;Join&nbsp;(PF)',
                'pf_uan_no' => 'Uan&nbsp;No',
                'bank_acc_no' => 'Bank&nbsp;Acc&nbsp;No',
                'ifsc_code' => 'IFSC&nbsp;Code',
                'bank_name' => 'Bank&nbsp;Name',
                'contractor_name' => 'Contractor&nbsp;Name',
                'status_name' => 'Status',
                'NAME' => 'Pay Schme&nbsp;Name',
                'isactive' => 'Active',
                'last_workings' => 'Last&nbsp;Worked',
            );
        
        }   

        if($subId==673){
            $data = array(
                'no' => 'S.No',
                'eb_no' => 'Emp Code',
                'empname' => 'Emp Namr',
                'dept_desc' => 'Department',
                'cata_desc' => 'Category',
                'mxdate' => 'Last&nbsp;Worked/Leave&nbsp;Date',
                'absent_for' => 'Absent&nbsp;For&nbsp;(Days)',
             );
        
        }   

        if($subId==682){
            $data = array(
                'no' => 'S.No',
                'cntloca' => 'Remarks',
                'eb_no' => 'Emp&nbsp;Code',
                'empname' => 'Emp&nbsp;Name',
                'dept_desc' => 'Department',
                'desig' => 'Designation',
                'shift' => 'Shift',
                'working_hours' => 'Working&nbsp;Hours',
                'rate' => 'Rate',
                'oth_rate' => 'Other',
                'amount' => 'Amount',
                
             );
        
        }   

        if($subId==686){
            $data = array(
                'no' => 'S.No',
                'emp_code' => 'Emp&nbsp;Code',
                'wname' => 'Emp&nbsp;Name',
                'dept_desc' => 'Department',
                'cata_desc' => 'Catagory',
                'leave_type_description' => 'Leave&nbsp;Type',
                'leave_from_date' => 'Leave&nbsp;From',
                'leave_to_date' => 'Leave&nbsp;To',
                'jdate' => 'Date&nbsp;of&nbsp;Join',
                'ovdate' => 'Overstay&nbsp;Days',
                
             );
        
        }   
        if($subId==687){
            $data = array(
                'no' => 'S.No',
                'emp_code' => 'Emp&nbsp;Code',
                'empname' => 'Emp&nbsp;Name',
                'DEPT_DESC' => 'Department',
                'attendance_date' => 'Attendance&nbsp;Date',
                'shift' => 'Shift',
                'HALFDAYS_mn' => 'HalfDays Month',
                'HALFDAYS' => 'Total Halfdays',
                
             );
        
        }   
        if($subId==684){
            $data = array(
                'no' => 'S.No',
                'HOCCU_CODE' => 'OCCU&nbsp;CODE',
                'desigd' => 'Designation',
                'DIRECT_INDIRECT' => 'D/I',
                'shift_a' => 'Shift&nbsp;A',
                'shift_b' => 'Shift&nbsp;B',
                'shift_c' => 'Shift&nbsp;C',
                'totshift' => 'Total',
                'target_a' => 'Target&nbsp;A',
                'target_b' => 'Target&nbsp;B',
                'target_c' => 'Target&nbsp;C',
                'tottarget' => 'Total',
                'excess_hands' => 'Excess&nbsp;Hands',
                'short_hands' => 'Short&nbsp;Hands',
                'tdhands' => 'Total&nbsp;Hands',
                'tdexcess' => 'MTD&nbsp;Excess&nbsp;Hands',
                'tdshort' => 'MTD&nbsp;Short&nbsp;Hands',
                
             );
        
        }  


        if($subId==544){
            $data = array(
                'no' => 'S.No',
                'Date' => 'Attendance&nbsp;Date',
                'Spell' => 'Spell',
                'EB_No' => 'EB_No',
                'Name' => 'Name',
                'Department' => 'Department',
                'Designation' => 'Designation',
                'attendance_type' => 'Att_Type',
                'attendance_source' => 'Attendance&nbsp;Source',
                'Working_Hours' => 'Working&nbsp;Hours',
                'MC_Nos' => 'MC&nbsp;Nos',
                'Remarks' => 'Remarks',                
            );
        
        }   

        
        if($subId==604){
            if(($fromdt) && $todt){
                $startDate = strtotime($fromdt);
                $endDate = strtotime($todt);
        //        echo 'start='.$startDate.'=';
        //        echo 'end='.$endDate.'=';

                $data = array('no' => 'S.No','EB_No'=> 'EB&nbsp;No','empname'=>'Employee&nbsp;Name');
            
                for ($currentDate = $startDate; $currentDate <= $endDate; $currentDate += (86400)) {
                    $date = date('Y-m-d', $currentDate);
                    $day = date("d",$currentDate)." ".substr((date("D",$currentDate)),0,2)." ".substr((date("M",$currentDate)),0,3); //date('d', $currentDate);
                    $rangArray[] = $date;
                    $data = array_merge($data, [$date => $day]);
                }
            
               
            }
         
        }


 



        if($subId==607){
            $data = array(
                'no' => 'S.No',
                'dept_desc' => 'Department',
                'desig' => 'Occupation',
                'Hands' => 'Hands',
                'Work_Hours' => 'Work&nbsp;Hours',
            );
        
        }   
//sabir 13.11.23
        if($subId==610){
            $data = array(
               'no' => 'S.No',
                'eb_no' => 'Emp&nbsp;Number',
                'name' => 'Worker&nbsp;Name',
                'spell' => 'Spell',
                'dept_desc' => 'Department',
                'desig' => 'Occupation',
                'cash_rate' => 'Cash&nbsp;Rate',
                'hrs' => 'Working&nbsp;Hours',
                'amt' => 'Amount',                
            );
        }
//sabir 13.11.23

        if($subId==601){
            $data = array(
                'no' => 'S.No',
                'eb_id' => 'EBID',
                'eb_no' => 'EBNO',
                'emp_name' => 'EMP Name',
                'attendance_date' => 'Date',
                'attendance_source' => 'Source',
                'attendance_type' => 'Type',
                'created_by' => 'Created&nbsp;By',
                // 'actual_dept_id' => 'Actual&nbsp;Dept&nbspId',
                'actual_dept' => 'Actual&nbsp;Department',
                'advised_dept' => 'Advised&nbsp;Department',
                'actual_occu' => 'Actual&nbsp;Occupation',
                'advised_occu' => 'Advised&nbsp;Occupation',
                'spell_hours' => 'Spell&nbsp;Hours',
                'working_hours' => 'Working&nbsp;Hours',
                'idle_hours' => 'Idle&nbsp;Hours',
                'Work_Hours' => 'Working&nbsp;Hours',
            );

            
        
        }   
        if($subId==559){
            $data = array(
                'no' => 'S.No',
                'dept_desc' => 'Department',
                'desig' => 'Occupation',
            );

            if($spells){
                foreach($spells as $spell){
                    $data = array_merge($data, [$spell->spell_name => $spell->spell_name]);
                }
            }
        }   
        if($subId==506){
            $data = array(
                'cata_desc' => 'Category',
                'No_of_Emp' => 'No&nbsp;Of&nbsp;Emp'
            );
        }

        if($subId==508){
            $data = array(
                'master_department' => 'Department',
                'sub_department' => 'Sub&nbsp;Department',
                'No_of_Emp' => 'No&nbsp;Of&nbsp;Emp'
            );
        }

        if($subId==505){
            $data = array(
                'master_department' => 'Department',
                'No_of_Emp' => 'No&nbsp;Of&nbsp;Emp'
            );
        }
        if($subId==517){
            $data = array(
                'master_department' => 'Department',
                'sub_department' => 'Sub&nbsp;Department',
                'desig' => 'Designation',
                'No_of_Emp' => 'No&nbsp;Of&nbsp;Emp'
            );
        }
        if($subId==509){
            $data = array(
                'master_department' => 'Department',
                'sub_department' => 'Sub&nbsp;Department',
                'permenant' => 'Permenant',
                'budli' => 'Budli',
                'new_budli' => 'New&nbsp;Budli',
                'contract' => 'Contract',
                'retired' => 'Retired',
                'other' => 'Others',
                'total' => 'Total'
            );
        }
        if($subId==609){
            $data = array(
                'group_code' => 'Group&nbsp;Code',
                'item_code' => 'Item&nbsp;Code',
                'item_desc' => 'Item&nbsp;Desc',
                'stock_qty' => 'Stock&nbsp;Qty',
                'Reserved' => 'Reserved',
                // 'Expected_Before_01092022' => 'Expected&nbsp;Before&nbsp;01092022',
                'Expected_by_07092022' => 'Expected&nbsp;by<br>07-09-2022',
                'Expected_by_14092022' => 'Expected&nbsp;by<br>14-09-2022',
                'Expected_by_22092022' => 'Expected&nbsp;by<br>22-09-2022',
                'Expected_by_30092022' => 'Expected&nbsp;by<br>30-09-2022',
                'Expected_after_30092022' => 'Expected&nbsp;after<br>30-09-2022',
                'Total_SQM' => 'Total&nbsp;SQM'
            );
        }
  /*      
        if($subId==610){
            $data = array(
                'serialNumber' => 'S&nbsp;No',
                'ebNo' => 'EB&nbsp;No',
                'workerName' => 'Worker&nbsp;Name',
                'spell' => 'Spell',
                'department' => 'Department',
                // 'Expected_Before_01092022' => 'Expected&nbsp;Before&nbsp;01092022',
                'occupation' => 'Occupation',
                'cashRate' => 'Cash&nbsp;Rate',
                'workingHours' => 'Working&nbsp;Hrs.',
                'amount' => 'Amount'
            );
        }
*/
        if($subId==246){
            $data = array(
                'billpass_number' => 'Bill&nbsp;Pass&nbsp;No',
                'billpass_date' => 'Bill&nbsp;Pass&nbsp;Date',
                'store_receipt_no' => 'Sr&nbsp;Receipt&nbsp;No',
                'po_sequence_no' => 'Purchase&nbsp;Orders',
                'supp_name' => 'Supplier&nbsp;Name',
                'status_name' => 'Status',
                'branch_name' => 'Project',
                'invoice_number' => 'Invoice&nbsp;No',
                'invoice_date' => 'Invoice&nbsp;Date',
                'invoice_amount' => 'Invoice&nbsp;Amount'
            );
        }

        if($subId==611){
            $data = array(
                'inward_sequence_no' => 'Inward&nbsp;No',
                'inward_date' => 'Inward&nbsp;Date',
                'po_sequence_no' => 'Purchase&nbsp;Orders',
                'supp_name' => 'Suppilier Name',
                'invoice_number' => 'Invoice No.',
                'invoice_date' => 'Invoice&nbsp;date',
                'store_receipt_no' => 'GRN&nbsp;No.',
                'store_receipt_date' => 'GRN&nbsp;Date',
                'dispatch_entry_no' => 'Dispatch&nbsp;Entry&nbsp;No',
                'status_name' => 'Status',
                'branch_name' => 'Branch',
                'project_name' => 'Project',
            );
        }

        if($subId==534){
            $data = array(
                'Employee_Code' => 'Employee&nbsp;Code',
                'Employee_Name' => 'Employee&nbsp;Name',
                'Bank_Name' => 'Bank&nbsp;Name',
                'Account_No' => 'Account&nbsp;No',
                'IFSC_Code' => 'IFSC&nbsp;Code',
                'Net_Pay' => 'Net&nbsp;Pay',
                
            );
        }

        if($subId==92){
            $data = array('SNo','Issue&nbsp;Date','Mr&nbsp;No','Quality','Godown&nbspID',
            'Pack&nbsp;Type','Quantity','Weight','Unit', 
            'Rate', 'Issue&nbsp;Value'
                , 'MR&nbsp;Line&nbsp;No','Quality&nbsp;ID','Godown&nbsp;Name' ,'Status'            
            
            );
        
        }

        if($subId==487){
            $data = array('Item&nbsp;Code','Item&nbsp;Description','Opening&nbsp;Qty',
            'Opening&nbsp;Value','Received&nbsp;Qty','Received&nbsp;Value',
            'Issur&nbsp;Qty','Issue&nbsp;Value','Closing&nbsp;Qty' ,'Closing&nbsp;Value',
             'Last&nbsp;Received&nbsp;Date','Last&nbsp;Issue&nbsp;Date','Not&nbsp;Issued&nbsp;For(Days)'
            );
        
        }


        if($subId==2166){
            $data = array(
                'Issue_No' => 'Issue&nbsp;No',
                'Department' => 'Department&nbsp;Name',
                'Issue_Date' => 'Issue&nbsp;Date',
                'Item_Code' => 'Item&nbsp;Code',
                'Item_Description' => 'Item&nbsp;Description',
                'Unit' => 'Unit&nbsp;',
                'Cost_Center' => 'Cost&nbsp;Center',
                'Issue_Quantity' => 'Issue&nbsp;Quantity;',
                'Issue_Value' => 'Issue&nbsp;Value',
                'EXP_Type' => 'EXP&nbsp;Type;',
                'Branch' => 'Branch&nbsp;',
                'SR_No' => 'SR&nbsp;No;',
                'Mechine_Name' => 'Mechine&nbsp;Name;'
                 
            );
        }

        if($subId==10000){
            $data = array(
                'itemcode' => 'Item&nbsp;Code',
                'item_name' => 'Item&nbsp;Name',
                'open_qty' => 'Open&nbsp;Qty',
                'open_val' => 'Open&nbsp;Val',
                'tranrecv_qty' => 'Tranrecv&nbsp;Qty',
                'tranrecv_val' => 'Tranrecv&nbsp;Val',
                'clos_qty' => 'Clos&nbsp;Qty',
                'clos_val' => 'Clos&nbsp;Val',                
            );
        }
                   
        return $data;
        
    }


}
