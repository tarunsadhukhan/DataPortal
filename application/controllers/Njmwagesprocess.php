<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Cell\DataType;

//use Mpdf\Mpdf;
require_once(APPPATH . 'libraries/fpdf/fpdf.php');


class Njmwagesprocess extends MY_Controller {

public function __construct() {
        parent::__construct();
		$this->load->library('session');
		$this->load->library('session');
		
		$this->load->model('Njmallwagesprocess');


        ini_set('max_execution_time', 6000); //300 seconds = 5 minutes

	
}


	public function njmwagespayslip1() {
		// Create a new Spreadsheet object

		$periodfromdate= $this->input->get('periodfromdate');
		$periodtodate = $this->input->get('periodtodate');
		$att_dept = $this->input->get('att_dept');
		$att_spell = $this->input->get('att_spell');
		$holget = $this->input->get('holget');
		$att_payschm = $this->input->get('att_payschm');
echo $periodfromdate.'=='.$periodtodate.'=='.$att_dept.'=='.$att_spell.'=='.$holget;
		$comp = $this->session->userdata('companyId');
		$month = 'June 2025';
	    $tktno = $this->input->get('tktno');
		
		echo "<pre>$periodfromdate</pre>";


    $pdfFile = "D:\\pyproj\\payschmup\\Payslip_{$month}.pdf";
    $pythonPath = 'd:\\Python311\\python.exe';
    $scriptPath = 'D:\\pyproj\\payschmup\\generate_payslip.py';

$python = 'python.exe'; // full path to your Python binary
$script = 'D:\\pyproj\\payschmup\\generate_payslip.py'; // full path to your script

//$python = 'D:\\pyproj\\payschmup\\venv\\Scripts\\python.exe'; // ✅ use venv Python
//$script = 'D:\\pyproj\\payschmup\\generate_payslip.py';      // ✅ your script





//    $command = "$pythonPath \"$scriptPath\" --month=\"$month\" --tktno=\"$tktno\"";
    $cmd = "$python \"$script\" --fromdate=\"$periodfromdate\" --todate=\"$periodtodate\" --payschmid=$att_payschm --pdffile=\"$pdfFile\"";
    $output = shell_exec($cmd);
    echo "command ".$cmd."<br>";
    echo "output ".$output."<br>";
//	shell_exec($cmd);


log_message('error', '✅ njmwagespayslip() was triggered');
    echo "✅ Controller called.<br>";
    

    if (file_exists($pdfFile)) {
        header('Content-Type: application/pdf');
        header('Content-Disposition: attachment; filename="'.basename($pdfFile).'"');
        readfile($pdfFile);
    } else {
        echo "❌ PDF not found!";
    }



	}


	public function njmcntwagespayslip() {
		// Create a new Spreadsheet object

		$periodfromdate= $this->input->get('periodfromdate');
		$periodtodate = $this->input->get('periodtodate');
		$att_dept = $this->input->get('att_dept');
		$att_spell = $this->input->get('att_spell');
		$holget = $this->input->get('holget');
		$att_payschm = $this->input->get('att_payschm');
        $att_dept = $this->input->get('att_dept');
		$comp = $this->session->userdata('companyId');
		$month = 'June 2025';
	    $tktno = $this->input->get('tktno');

        //contract
        if ($att_payschm==163) {
            $mccodes = $this->Njmallwagesprocess->njmnewbdlpayslipprint($att_payschm,$periodfromdate,$periodtodate);
            $this->njmwagespayslip163($periodfromdate,$periodtodate,$att_payschm);
        }

        //clerk
        if ($att_payschm==166) {
//            $mccodes = $this->Njmallwagesprocess->njmnewbdlpayslipprint($att_payschm,$periodfromdate,$periodtodate);
            $this->njmwagespayslip166($periodfromdate,$periodtodate,$att_payschm);
        }
        //main
        if ($att_payschm==164) {
              $mccodes = $this->Njmallwagesprocess->njmwagespayslip1641($att_payschm,$periodfromdate,$periodtodate,$att_dept);
         //     var_dump($mccodes);
              $this->njmwagespayslip1641($mccodes,$periodfromdate,$periodtodate,$att_payschm);

        }
        if ($att_payschm==167) {
              $mccodes = $this->Njmallwagesprocess->njmwagespayslip1641($att_payschm,$periodfromdate,$periodtodate,$att_dept);
              $this->njmwagespayslip1641($mccodes,$periodfromdate,$periodtodate,$att_payschm);

        }
        if ($att_payschm==169) {
              $mccodes = $this->Njmallwagesprocess->njmwagespayslip1641($att_payschm,$periodfromdate,$periodtodate,$att_dept);
              $this->njmwagespayslip1641($mccodes,$periodfromdate,$periodtodate,$att_payschm);

        }



    
    }





public function njmwagespayslip1641($mccodes,$periodfromdate,$periodtodate,$att_payschm) {
    $logMsg = "";
    $totamt = 0;
    $tvard  = 0;
    $pg     = 0;
    $department = "";
    $esino=123456789;
    $CODE = 'N0000514';
    //echo $CODE; // Output: N0000514
    $dpc='';
    $payslipDate = date("M'Y", strtotime($periodtodate));
    $printDate   = date("d,M'Y");
    $parintDate = date("10,M'Y");
   // echo $parintDate;   C_finalnetpay_

    $lnn=1;
   		$pg=0;
        $tpgn=0;    
        $totamt=0;
        $tvard=0;
        $ebid=0;

//INITIALIZE
        
        $C_wrkhours2_= 0;
        $C_PHours_= 0;
       $days =0;
        $C_nightdays_= 0;
        $C_FHours_= 0;
        $C_LayOffHrs_= 0;
        $C_eldays_= 0;
        $C_extrahrstime_= 0;
        $C_extrahourspiece_= 0;
        $C_esi_days_= 0;
        $C_timewages_= 0;
        $C_sundayadv_= 0;
        $C_loffwagesincamt_= 0;
        $C_rsdamt_= 0;
        $C_pwage_= 0;
        $C_DAAMOUNT_= 0;
        $C_loffdagenincamt_= 0;
        $C_pwagesinc_= 0;
        $C_TOTAL_DEDUCTION_ = 0;
        $C_eldwagesincamt_ = 0;
        $C_na_ = 0;
        $C_extrahourswages_ = 0;
        $C_EPF_ = 0;
        $C_netpayamount_ = 0;
        $C_finalnetpay_ = 0;
        $C_genincamt_ = 0;
        $C_elddagenincamt_ = 0;
        $C_exthrsdagincamt_ = 0;
        $C_rentded_ = 0;
        $C_NET = 0;
        $C_OTHER_ALLOW_ = 0;
        $C_eldwagesincamt_ = 0;
        $C_gross2amt_ = 0;
        $C_ESI_ = 0;
        $C_iftudedamt_ = 0;
        $C_festwagesincamt_ = 0;
        $C_festdagenincamt_ = 0;
        $C_HRA_ = 0;
        $C_exadvance_ = 0;
        $C_govtwelfare_ = 0;
        $C_finalnetpay_ = 0;
        $C_totalwagesamt_ = 0;
        $C_gross1amt_ = 0;
        $C_PTAX_ = 0;
        $C_landrentamt_ = 0;
        $C_mveplus_ = 0;
        $C_mveminus_ = 0;
        

        $C_PFG_ = 0;
        $C_otherpay_ = 0;
        $C_canteen_ = 0;
        $C_loomproducion_ = 0;
        $C_sldwagesincamt_ = 0;
        $ebid=0;
//INITIALIZE
        $bln='';

    foreach ($mccodes as $record) {

        If ($ebid<>$record->eb_id ) {
            IF ($ebid>0 ) {
                if  ($C_gross2amt_>0) {
                $totamt=$totamt+$C_NET; 
                $tvard=$tvard+$C_iftudedamt_;
             	$pg++;
                //=====================================PRINT
            $logMsg .=chr(15).' ' . ' LIMELIGHT COMM PVT LTD  ' . 
            str_repeat('', max(0, 0 - strlen($RATE))) . 
            number_format($RATE,4) . '  ' . 
            'PAY SLIP FOR:' . str_repeat(' ', max(0, 9 - strlen($payslipDate))) . $payslipDate . '  ' . 
            'DEPT: ' . str_repeat(' ', max(0, 8 - strlen($department))) . substr($department,0,12) . '    ' . 
            '#'.' PFN:' . str_repeat('  ', 5 - strlen($PFNO)) . $PFNO . '   ' . 
            'DAYS:' . str_repeat('', max(0, 3 - strlen($days))) . number_format($days,2) . '                 '. chr(14).$parintDate . "\n";

                    
                $logMsg.='   NAME  :'. str_repeat('', $padding). substr($emp_name, 0, 15).'  '.'     DESG:' . str_repeat('', $padding) . substr($designation, 0, 6) .'      '.'ESINO: '.str_repeat('', 52 - strlen($esi_no)).$esi_no.'  '.'TKT NO: '.str_repeat('', 57 - strlen($eb_no)).$eb_no.'  '.'CODE:'.str_repeat(' ', 6 - strlen($eb_no)).$eb_no.'                       '.'PAY SLIP FOR:'.str_repeat('', 14 - strlen($payslipDate)).$payslipDate."\n";
                

                if ($att_payschm==164) {    
                $logMsg.='   '.'THR1  :'.str_repeat(' ', 8 - strlen($C_wrkhrs1_)).$C_wrkhrs1_.'   '.'THR2 :'.str_repeat(' ', 8 - strlen($C_wrkhours2_)).$C_wrkhours2_.'   '.'PHRS :'.str_repeat(' ', 8 - strlen($C_PHours_)).$C_PHours_.'   '.'CPN  :'.str_repeat(' ', 8 - strlen($C_nightdays_)).$C_nightdays_.'   '.'FHRS :'.str_repeat(' ', 8 - strlen($C_FHours_)).$C_FHours_.'   '.'LHRS :'.str_repeat(' ', 8 - strlen($C_LayOffHrs_)).$C_LayOffHrs_.'            '.'DEPT:'.str_repeat('', 14 - strlen($department)).$department."\n";
        
                $logMsg.='   '.'SLD   :'.str_repeat(' ', 4 - strlen($SLD)).number_format($SLD,2).'    '.'ELD  :'.str_repeat(' ', 8 - strlen($C_eldays_)).$C_eldays_.'   '.'LODAY:'.str_repeat(' ', 5 - strlen($LODAY)).number_format($LODAY,2).'   '.'OTTHR:'.str_repeat(' ', 8 - strlen($C_extrahrstime_)).$C_extrahrstime_.'   '.'OTPHR:'.str_repeat(' ', 8 - strlen($C_extrahourspiece_)).$C_extrahourspiece_.'   '.'ESID :'.str_repeat(' ', 8 - strlen($C_esi_days_)).$C_esi_days_.'            '.''.str_repeat('', 35 - strlen($emp_name)).$emp_name."\n";
                
                $logMsg.='  '.' TWAGE :'.str_repeat(' ', 8 - strlen($C_timewages_)).$C_timewages_.'   '.'DJ.A:'.str_repeat(' ', 8 - strlen($C_mveminus_)).$C_sundayadv_.'   '.'LOWI :'.str_repeat(' ', 8 - strlen($C_loffwagesincamt_)).$C_loffwagesincamt_.'  '.'ILT&PW:'.str_repeat(' ', 5 - strlen($ILT)).number_format($ILT,2).'   '.'S.ADV:'.str_repeat(' ', 5 - strlen($PADV)).number_format($PADV,2).'   '.'RSD  :'.str_repeat(' ', 8 - strlen($C_rsdamt_)).$C_rsdamt_.'            '.'CODE: '.str_repeat('', 65 - strlen($eb_no)).$eb_no."\n";
                
                $logMsg.='  '.' P.WAGE:'.str_repeat(' ', 8 - strlen($C_pwage_)).$C_pwage_.'  '.' DA   :'.str_repeat(' ', 8 - strlen($C_DAAMOUNT_)).$C_DAAMOUNT_.'  '.' LODG :'.str_repeat(' ', 8 - strlen($C_loffdagenincamt_)).$C_loffdagenincamt_.' '.' INCENT:'.str_repeat(' ', 8 - strlen($C_pwagesinc_)).$C_pwagesinc_.'  '.' CDN  :'.str_repeat(' ', 5 - strlen($CDN)).number_format($CDN,2).'  '.' GDED :'.str_repeat(' ', 8 - strlen($C_TOTAL_DEDUCTION_)).$C_TOTAL_DEDUCTION_.'            '.'TKT:'.str_repeat('', 25 - strlen($eb_no)).$eb_no."\n";
                
                $logMsg.='  '.' NA    :'.str_repeat(' ', 8 - strlen($C_na_)).$C_na_.'  '.' EWI  :'.str_repeat(' ', 8 - strlen($C_eldwagesincamt_)).$C_eldwagesincamt_.'  '.' OTW  :'.str_repeat(' ', 8 - strlen($C_extrahourswages_)).$C_extrahourswages_.'  '.' ILIN :'.str_repeat(' ', 5 - strlen($ILINT)).number_format($ILINT,2).'  '.' PF   :'.str_repeat(' ', 8 - strlen($C_EPF_)).$C_EPF_.'  '.' NPAY :'.str_repeat(' ', 8 - strlen($C_netpayamount_)).$C_netpayamount_.'            '.chr(14).'NET:'.str_repeat('', 25 - strlen($C_finalnetpay_)).$C_finalnetpay_."\n";
                
                $logMsg.='  '.' GI    :'.str_repeat(' ', 8 - strlen($C_genincamt_)).$C_genincamt_.'  '.' EDG  :'.str_repeat(' ', 8 - strlen($C_elddagenincamt_)).$C_elddagenincamt_.'  '.' OTI  :'.str_repeat(' ', 8 - strlen($C_extrahoursincamt_)).$C_extrahoursincamt_.'  '.' ILDG :'.str_repeat(' ', 5 - strlen($ILDG)).number_format($ILDG,2).'  '.' RENT :'.str_repeat(' ', 8 - strlen($C_rentded_)).$C_rentded_.'  '.' RNPAY:'.str_repeat(' ', 8 - strlen($C_NET)).$C_NET."\n";
                
                $logMsg.='  '.' INC   :'.str_repeat(' ', 8 - strlen($C_inc_)).$C_inc_.'   '.'FDG  :'.str_repeat(' ', 8 - strlen($C_festdagenincamt_)).$C_festdagenincamt_.'  '.' ODG  :'.str_repeat(' ', 8 - strlen($C_exthrsdagincamt_)).$C_exthrsdagincamt_.'  '.' GRS2 :'.str_repeat(' ', 8 - strlen($C_gross2amt_)).$C_gross2amt_.'  '.' ESI  :'.str_repeat(' ', 8 - strlen($C_ESI_)).$C_ESI_.'  '.' VARD1:'.str_repeat(' ', 8 - strlen($C_iftudedamt_)).$C_iftudedamt_."\n";
                
                $logMsg.='  '.' FWI   :'.str_repeat(' ', 8 - strlen($C_festwagesincamt_)).$C_festwagesincamt_.'  '.' SDG  :'.str_repeat(' ', 8 - strlen($C_sldwagesincamt_)).$C_sldwagesincamt_.'  '.' HRA  :'.str_repeat(' ', 8 - strlen($C_HRA_)).$C_HRA_.' '.' ADVDED:'.str_repeat(' ', 8 - strlen($C_exadvance_)).$C_exadvance_.'  '.' G.W.F:'.str_repeat(' ', 8 - strlen($C_govtwelfare_)).$C_govtwelfare_.'  '.' NET  :'.str_repeat(' ', 8 - strlen($C_finalnetpay_)).$C_finalnetpay_."\n";
            
                $logMsg.='  '.' SWI   :'.str_repeat(' ', 5 - strlen($SWOA)).number_format($SWOA,2).' '.'  TOTAL:'.str_repeat(' ', 8 - strlen($C_totalwagesamt_)).$C_totalwagesamt_.'  '.' GRS1 :'.str_repeat(' ', 8 - strlen($C_gross1amt_)).$C_gross1amt_.'  '.' PTAX :'.str_repeat(' ', 8 - strlen($C_PTAX_)).$C_PTAX_.'  '.' LR   :'.str_repeat(' ', 8 - strlen($C_landrentamt_)).$C_landrentamt_."\n";
                $logMsg.='  '.' PVE   :'.str_repeat(' ', 8 - strlen($C_mveplus_)).$C_mveplus_.' '.'  P.F.G:'.str_repeat(' ', 8 - strlen($C_PFG_)).$C_PFG_.'  '.' OP   :'.str_repeat(' ', 8 - strlen($C_otherpay_)).$C_otherpay_.'  '.' CANT :'.str_repeat(' ', 8 - strlen($C_canteen_)).$C_canteen_.'  '.' PROD :'.str_repeat(' ', 8 - strlen($C_loomproducion_)).$C_loomproducion_."\n";
                }

                if ($att_payschm==167) { 
                $logMsg.='   '.'THR1  :'.str_repeat(' ', 8 - strlen($C_wrkhrs1_)).$C_wrkhrs1_.'   '.'THR2 :'.str_repeat(' ', 8 - strlen($C_wrkhours2_)).$C_wrkhours2_.'   '.'PHRS :'.str_repeat(' ', 8 - strlen($C_PHours_)).$C_PHours_.'   '.'CPN  :'.str_repeat(' ', 8 - strlen($C_nightdays_)).$C_nightdays_.'   '.'FHRS :'.str_repeat(' ', 8 - strlen($C_FHours_)).$C_FHours_.'   '.'LHRS :'.str_repeat(' ', 8 - strlen($C_LayOffHrs_)).$C_LayOffHrs_.'            '.'DEPT:'.str_repeat('', 14 - strlen($department)).$department."\n";
        
                $logMsg.='   '.'SLD   :'.str_repeat(' ', 4 - strlen($SLD)).number_format($SLD,2).'    '.'ELD  :'.str_repeat(' ', 8 - strlen($C_eldays_)).$C_eldays_.'   '.'LODAY:'.str_repeat(' ', 5 - strlen($LODAY)).number_format($LODAY,2).'   '.'OTTHR:'.str_repeat(' ', 8 - strlen($C_extrahrstime_)).$C_extrahrstime_.'   '.'OTPHR:'.str_repeat(' ', 8 - strlen($C_extrahourspiece_)).$C_extrahourspiece_.'   '.'ESID :'.str_repeat(' ', 8 - strlen($C_esi_days_)).$C_esi_days_.'            '.''.str_repeat('', 35 - strlen($emp_name)).$emp_name."\n";
                
                $logMsg.='  '.' TWAGE :'.str_repeat(' ', 8 - strlen($C_timewages_)).$C_timewages_.'   '.'ADJ.A:'.str_repeat(' ', 8 - strlen($C_mveminus_)).$C_sundayadv_.'   '.'LOWI :'.str_repeat(' ', 8 - strlen($C_loffwagesincamt_)).$C_loffwagesincamt_.'  '.'ILT&PW:'.str_repeat(' ', 5 - strlen($ILT)).number_format($ILT,2).'   '.'S.ADV:'.str_repeat(' ', 5 - strlen($PADV)).number_format($PADV,2).'   '.'RSD  :'.str_repeat(' ', 8 - strlen($C_rsdamt_)).$C_rsdamt_.'            '.'CODE: '.str_repeat('', 65 - strlen($eb_no)).$eb_no."\n";
                
                $logMsg.='  '.' P.WAGE:'.str_repeat(' ', 8 - strlen($C_pwage_)).$C_pwage_.'  '.' DA   :'.str_repeat(' ', 8 - strlen($C_DAAMOUNT_)).$C_DAAMOUNT_.'  '.' LODG :'.str_repeat(' ', 8 - strlen($C_loffdagenincamt_)).$C_loffdagenincamt_.' '.' INCENT:'.str_repeat(' ', 8 - strlen($C_pwagesinc_)).$C_pwagesinc_.'  '.' CDN  :'.str_repeat(' ', 5 - strlen($CDN)).number_format($CDN,2).'  '.' GDED :'.str_repeat(' ', 8 - strlen($C_TOTAL_DEDUCTION_)).$C_TOTAL_DEDUCTION_.'            '.'TKT:'.str_repeat('', 25 - strlen($eb_no)).$eb_no."\n";
                
                $logMsg.='  '.' NA    :'.str_repeat(' ', 8 - strlen($C_na_)).$C_na_.'  '.' EWI  :'.str_repeat(' ', 8 - strlen($C_eldwagesincamt_)).$C_eldwagesincamt_.'  '.' OTW  :'.str_repeat(' ', 8 - strlen($C_extrahourswages_)).$C_extrahourswages_.'  '.' ILIN :'.str_repeat(' ', 5 - strlen($ILINT)).number_format($ILINT,2).'  '.' PF   :'.str_repeat(' ', 8 - strlen($C_EPF_)).$C_EPF_.'  '.' NPAY :'.str_repeat(' ', 8 - strlen($C_netpayamount_)).$C_netpayamount_.'            '.chr(14).'NET:'.str_repeat('', 25 - strlen($C_finalnetpay_)).$C_finalnetpay_."\n";
                
                $logMsg.='  '.' GI    :'.str_repeat(' ', 8 - strlen($C_genincamt_)).$C_genincamt_.'  '.' EDG  :'.str_repeat(' ', 8 - strlen($C_elddagenincamt_)).$C_elddagenincamt_.'  '.' OTI  :'.str_repeat(' ', 8 - strlen($C_extrahoursincamt_)).$C_extrahoursincamt_.'  '.' ILDG :'.str_repeat(' ', 5 - strlen($ILDG)).number_format($ILDG,2).'  '.' RENT :'.str_repeat(' ', 8 - strlen($C_rentded_)).$C_rentded_.'  '.' RNPAY:'.str_repeat(' ', 8 - strlen($C_NET)).$C_NET."\n";
                
                $logMsg.='  '.' INC   :'.str_repeat(' ', 8 - strlen($C_inc_)).$C_inc_.'   '.'FDG  :'.str_repeat(' ', 8 - strlen($C_festdagenincamt_)).$C_festdagenincamt_.'  '.' ODG  :'.str_repeat(' ', 8 - strlen($C_exthrsdagincamt_)).$C_exthrsdagincamt_.'  '.' GRS2 :'.str_repeat(' ', 8 - strlen($C_gross2amt_)).$C_gross2amt_.'  '.' ESI  :'.str_repeat(' ', 8 - strlen($C_ESI_)).$C_ESI_.'  '.' VARD1:'.str_repeat(' ', 8 - strlen($C_iftudedamt_)).$C_iftudedamt_."\n";
                
                $logMsg.='  '.' FWI   :'.str_repeat(' ', 8 - strlen($C_festwagesincamt_)).$C_festwagesincamt_.'  '.' SDG  :'.str_repeat(' ', 8 - strlen($C_sldwagesincamt_)).$C_sldwagesincamt_.'  '.' HRA  :'.str_repeat(' ', 8 - strlen($C_HRA_)).$C_HRA_.' '.' ADVDED:'.str_repeat(' ', 8 - strlen($C_exadvance_)).$C_exadvance_.'  '.' G.W.F:'.str_repeat(' ', 8 - strlen($C_govtwelfare_)).$C_govtwelfare_.'  '.' NET  :'.str_repeat(' ', 8 - strlen($C_finalnetpay_)).$C_finalnetpay_."\n";
            
                $logMsg.='  '.' SWI   :'.str_repeat(' ', 5 - strlen($SWOA)).number_format($SWOA,2).' '.'  TOTAL:'.str_repeat(' ', 8 - strlen($C_totalwagesamt_)).$C_totalwagesamt_.'  '.' GRS1 :'.str_repeat(' ', 8 - strlen($C_gross1amt_)).$C_gross1amt_.'  '.' PTAX :'.str_repeat(' ', 8 - strlen($C_PTAX_)).$C_PTAX_.'  '.' LR   :'.str_repeat(' ', 8 - strlen($C_landrentamt_)).$C_landrentamt_."\n";
                $logMsg.='  '.' PVE   :'.str_repeat(' ', 8 - strlen($C_mveplus_)).$C_mveplus_.' '.'  P.F.G:'.str_repeat(' ', 8 - strlen($C_PFG_)).$C_PFG_.'  '.' OP   :'.str_repeat(' ', 8 - strlen($C_otherpay_)).$C_otherpay_.'  '.' CANT :'.str_repeat(' ', 8 - strlen($C_canteen_)).$C_canteen_.'  '.' PROD :'.str_repeat(' ', 8 - strlen($C_loomproducion_)).$C_loomproducion_."\n";
                }


                if ($att_payschm==169) { 
                $logMsg.='   '.'THR1  :'.str_repeat(' ', 8 - strlen($C_wrkhrs1_)).$C_wrkhrs1_.'   '.'THR2 :'.str_repeat(' ', 8 - strlen($C_wrkhours2_)).$C_wrkhours2_.'   '.'PHRS :'.str_repeat(' ', 8 - strlen($C_PHours_)).$C_PHours_.'   '.'CPN  :'.str_repeat(' ', 8 - strlen($C_nightdays_)).$C_nightdays_.'   '.'FHRS :'.str_repeat(' ', 8 - strlen($C_FHours_)).$C_FHours_.'   '.'LHRS :'.str_repeat(' ', 8 - strlen($C_LayOffHrs_)).$C_LayOffHrs_.'            '.'DEPT:'.str_repeat('', 14 - strlen($department)).$department."\n";
        
                $logMsg.='   '.'SLD   :'.str_repeat(' ', 4 - strlen($SLD)).number_format($SLD,2).'    '.'ELD  :'.str_repeat(' ', 8 - strlen($C_eldays_)).$C_eldays_.'   '.'LODAY:'.str_repeat(' ', 5 - strlen($LODAY)).number_format($LODAY,2).'   '.'OTTHR:'.str_repeat(' ', 8 - strlen($C_extrahrstime_)).$C_extrahrstime_.'   '.'OTPHR:'.str_repeat(' ', 8 - strlen($C_extrahourspiece_)).$C_extrahourspiece_.'   '.'ESID :'.str_repeat(' ', 8 - strlen($C_esi_days_)).$C_esi_days_.'            '.''.str_repeat('', 35 - strlen($emp_name)).$emp_name."\n";
                
                $logMsg.='  '.' TWAGE :'.str_repeat(' ', 8 - strlen($C_timewages_)).$C_timewages_.'   '.'ADJ.A:'.str_repeat(' ', 8 - strlen($C_mveminus_)).$C_sundayadv_.'   '.'LOWI :'.str_repeat(' ', 8 - strlen($C_loffwagesincamt_)).$C_loffwagesincamt_.'  '.'ILT&PW:'.str_repeat(' ', 5 - strlen($ILT)).number_format($ILT,2).'   '.'S.ADV:'.str_repeat(' ', 5 - strlen($PADV)).number_format($PADV,2).'   '.'RSD  :'.str_repeat(' ', 8 - strlen($C_rsdamt_)).$C_rsdamt_.'            '.'CODE: '.str_repeat('', 65 - strlen($eb_no)).$eb_no."\n";
                
                $logMsg.='  '.' P.WAGE:'.str_repeat(' ', 8 - strlen($C_pwage_)).$C_pwage_.'  '.' DA   :'.str_repeat(' ', 8 - strlen($C_DAAMOUNT_)).$C_DAAMOUNT_.'  '.' LODG :'.str_repeat(' ', 8 - strlen($C_loffdagenincamt_)).$C_loffdagenincamt_.' '.' INCENT:'.str_repeat(' ', 8 - strlen($C_pwagesinc_)).$C_pwagesinc_.'  '.' CDN  :'.str_repeat(' ', 5 - strlen($CDN)).number_format($CDN,2).'  '.' GDED :'.str_repeat(' ', 8 - strlen($C_TOTAL_DEDUCTION_)).$C_TOTAL_DEDUCTION_.'            '.'TKT:'.str_repeat('', 25 - strlen($eb_no)).$eb_no."\n";
                
                $logMsg.='  '.' NA    :'.str_repeat(' ', 8 - strlen($C_na_)).$C_na_.'  '.' EWI  :'.str_repeat(' ', 8 - strlen($C_eldwagesincamt_)).$C_eldwagesincamt_.'  '.' OTW  :'.str_repeat(' ', 8 - strlen($C_extrahourswages_)).$C_extrahourswages_.'  '.' ILIN :'.str_repeat(' ', 5 - strlen($ILINT)).number_format($ILINT,2).'  '.' PF   :'.str_repeat(' ', 8 - strlen($C_EPF_)).$C_EPF_.'  '.' NPAY :'.str_repeat(' ', 8 - strlen($C_netpayamount_)).$C_netpayamount_.'            '.chr(14).'NET:'.str_repeat('', 25 - strlen($C_finalnetpay_)).$C_finalnetpay_."\n";
                
                $logMsg.='  '.' GI    :'.str_repeat(' ', 8 - strlen($C_genincamt_)).$C_genincamt_.'  '.' EDG  :'.str_repeat(' ', 8 - strlen($C_elddagenincamt_)).$C_elddagenincamt_.'  '.' OTI  :'.str_repeat(' ', 8 - strlen($C_extrahoursincamt_)).$C_extrahoursincamt_.'  '.' ILDG :'.str_repeat(' ', 5 - strlen($ILDG)).number_format($ILDG,2).'  '.' RENT :'.str_repeat(' ', 8 - strlen($C_rentded_)).$C_rentded_.'  '.' RNPAY:'.str_repeat(' ', 8 - strlen($C_NET)).$C_NET."\n";
                
                $logMsg.='  '.' O.A   :'.str_repeat(' ', 8 - strlen($C_OTHER_ALLOW_)).$C_OTHER_ALLOW_.'   '.'FDG  :'.str_repeat(' ', 8 - strlen($C_festdagenincamt_)).$C_festdagenincamt_.'  '.' ODG  :'.str_repeat(' ', 8 - strlen($C_exthrsdagincamt_)).$C_exthrsdagincamt_.'  '.' GRS2 :'.str_repeat(' ', 8 - strlen($C_gross2amt_)).$C_gross2amt_.'  '.' ESI  :'.str_repeat(' ', 8 - strlen($C_ESI_)).$C_ESI_.'  '.' VARD1:'.str_repeat(' ', 8 - strlen($C_iftudedamt_)).$C_iftudedamt_."\n";
                
                $logMsg.='  '.' FWOA  :'.str_repeat(' ', 8 - strlen($C_festwagesincamt_)).$C_festwagesincamt_.'  '.' SDG  :'.str_repeat(' ', 8 - strlen($C_sldwagesincamt_)).$C_sldwagesincamt_.'  '.' HRA  :'.str_repeat(' ', 8 - strlen($C_HRA_)).$C_HRA_.' '.' ADVDED:'.str_repeat(' ', 8 - strlen($C_exadvance_)).$C_exadvance_.'  '.' G.W.F:'.str_repeat(' ', 8 - strlen($C_govtwelfare_)).$C_govtwelfare_.'  '.' NET  :'.str_repeat(' ', 8 - strlen($C_finalnetpay_)).$C_finalnetpay_."\n";
            
                $logMsg.='  '.' SWOA  :'.str_repeat(' ', 5 - strlen($SWOA)).number_format($SWOA,2).' '.'  TOTAL:'.str_repeat(' ', 8 - strlen($C_totalwagesamt_)).$C_totalwagesamt_.'  '.' GRS1 :'.str_repeat(' ', 8 - strlen($C_gross1amt_)).$C_gross1amt_.'  '.' PTAX :'.str_repeat(' ', 8 - strlen($C_PTAX_)).$C_PTAX_.'  '.' LR   :'.str_repeat(' ', 8 - strlen($C_landrentamt_)).$C_landrentamt_."\n";
                $logMsg.='  '.' PVE   :'.str_repeat(' ', 8 - strlen($C_mveplus_)).$C_mveplus_.' '.'       :'.str_repeat(' ', 8 - strlen($bln   )).$bln   .'  '.' OP   :'.str_repeat(' ', 8 - strlen($C_otherpay_)).$C_otherpay_.'  '.' CANT :'.str_repeat(' ', 8 - strlen($C_canteen_)).$C_canteen_.'  '.' PROD :'.str_repeat(' ', 8 - strlen($C_loomproducion_)).$C_loomproducion_."\n";
                }


                //$totamt=$totamt+$record->NET;
                //$tvard=$tvard+$record->VRD1;
            //    $totamt=$totamt+$record->C_NET; 
            //    $tvard=$tvard+$record->C_iftudedamt_;
                ///$department+$record->department;
            //	$tpgn=$tpgn+$record->pgn;
            
                    
        ///====================================PRINT

        //RESET
            $logMsg .= $line."\n";
            $lnn=$lnn++;
                $pgn++;		
                if ( $pgn>5 ) { 	
                    //$logMsg .= Chr(12);
                    $logMsg .=chr(18). Chr(12)."\n";
                $pgn=1;
            }	
            } 

        }


        if ($dpc<>$record->dept_code ) { 	
			if (strlen($dpc)>0) {
                $logMsg .= $bln."\n";
					$logMsg.=$bln."\n";
					$logMsg.=$bln."\n";
					$logMsg .='   '.' TOTAL  NO. OF PAYSLIPS :'.$pg. "\n";
					$logMsg .='  '.'  TOTAL NET AMOUNT:  '.$totamt.'         '.'  LOAN DED: '.$tvard.'            '.'  DEPARTMENT :'.$department .  Chr(12);
                    
             		$pg=0;
                    $tpgn=0;    
                    $totamt=0;
                    $tvard=0;
 				
			}	
			   $dpc=$record->dept_code;
			   $dptcode=$dpc;
			   $lnn=1;
			   $tpgn++;
			   $rnop=0;	
			   $pgn=1;     
		}	

      
    
        $esi_no=$record->esi_no;;
		$RATE='5.54211';

        $PFNO=substr($record->uan_no,0,5);
 if ($att_payschm==167) {
    $PFNO=''; 
 }   
        $SLD=0.00;
        $LODAY=0.00;
        $ILT=0.00;
        $PADV=0.00;
        $CDN=0.00;
        $ILINT=0.00;
        $ILDG=0.00;
        $SWOA=0.00;

        $ebid=$record->eb_id;
        //$ebid=$record->eb_id;
        $emp_name = $record->emp_name;
        $padding = max(0, 20 - strlen($emp_name));
        $designation= $record->designation;
        $padding = max(0, 15 - strlen($designation));
        $eb_no= $record->eb_no;
		$department=substr($record->department,0,6);
        $department= $record->department;
		$desig=substr($record->desig,0,5);
		$desig=$record->desig;
		$TKTNO=$record->TKTNO;
		$CODE=$record->CODE;
        $C_wrkhours2_= 0;
        $C_PHours_= 0;
       $days =0;
        $C_nightdays_= 0;
        $C_FHours_= 0;
        $C_LayOffHrs_= 0;
        $C_eldays_= 0;
        $C_extrahrstime_= 0;
        $C_extrahourspiece_= 0;
        $C_esi_days_= 0;
        $C_timewages_= 0;
        $C_sundayadv_= 0;
        $C_loffwagesincamt_= 0;
        $C_rsdamt_= 0;
        $C_pwage_= 0;
        $C_DAAMOUNT_= 0;
        $C_loffdagenincamt_= 0;
        $C_pwagesinc_= 0;
        $C_TOTAL_DEDUCTION_ = 0;
        $C_eldwagesincamt_ = 0;
        $C_na_ = 0;
        $C_extrahourswages_ = 0;
        $C_EPF_ = 0;
        $C_netpayamount_ = 0;
        $C_finalnetpay_ = 0;
        $C_genincamt_ = 0;
        $C_inc_=0;
        $C_elddagenincamt_ = 0;
        $C_exthrsdagincamt_ = 0;
        $C_rentded_ = 0;
        $C_NET = 0;
        $C_OTHER_ALLOW_ = 0;
        $C_eldwagesincamt_ = 0;
        $C_gross2amt_ = 0;
        $C_ESI_ = 0;
        $C_iftudedamt_ = 0;
        $C_festwagesincamt_ = 0;
        $C_festdagenincamt_ = 0;
        $C_HRA_ = 0;
        $C_exadvance_ = 0;
        $C_govtwelfare_ = 0;
        $C_finalnetpay_ = 0;
        $C_totalwagesamt_ = 0;
        $C_gross1amt_ = 0;
        $C_PTAX_ = 0;
        $C_landrentamt_ = 0;
        $C_mveplus_ = 0;
        $C_mveminus_ = 0;
        $C_PFG_ = 0;
        $C_otherpay_ = 0;
        $C_canteen_ = 0;
        $C_loomproducion_ = 0;
        $C_sldwagesincamt_ = 0;
       //RESET

    }    
 
        if ($record->CODE=='C_wrkhrs1_') {    
            $C_wrkhrs1_= $record->AMOUNT; 
     //       $logMsg.='  MYLIME work hrs  :'.$C_wrkhrs1_."\n";
        }
        if ($record->CODE=='C_wrkhours2_') {    
            $C_wrkhours2_= $record->AMOUNT; 
        }
        IF ($record->CODE== 'C_wrkhours2_') {
            $C_wrkhours2_= $record->AMOUNT;
        }
        IF ($record->CODE== 'C_PHours_') {
            $C_PHours_= $record->AMOUNT;
        }
        $days = ($C_wrkhrs1_ + $C_PHours_ + $C_wrkhours2_ )/ 8;
        IF ($record->CODE== 'C_nightdays_') {
            $C_nightdays_= $record->AMOUNT;
        }
        IF ($record->CODE== 'C_FHours_') {
            $C_FHours_= $record->AMOUNT;
        }
        IF ($record->CODE== 'C_LayOffHrs_') {
            $C_LayOffHrs_= $record->AMOUNT;
        }
        IF ($record->CODE== 'C_eldays_') {
            $C_eldays_= $record->AMOUNT;
        }
        IF ($record->CODE== 'C_extrahrstime_') {
            $C_extrahrstime_= $record->AMOUNT;
        }
        IF ($record->CODE== 'C_extrahourspiece_') {
            $C_extrahourspiece_= $record->AMOUNT;
        }
        IF ($record->CODE== 'C_esi_days_') {
            $C_esi_days_= $record->AMOUNT;
        }
        IF ($record->CODE== 'C_timewages_') {
            $C_timewages_= $record->AMOUNT;
        }
        IF ($record->CODE== 'C_sundayadv_') {
            $C_sundayadv_= $record->AMOUNT;
        }
        IF ($record->CODE== 'C_loffwagesincamt_') {
            $C_loffwagesincamt_= $record->AMOUNT;
        }
        IF ($record->CODE== 'C_rsdamt_') {
            $C_rsdamt_= $record->AMOUNT;
        }
        IF ($record->CODE== 'C_pwage_') {
            $C_pwage_= $record->AMOUNT;
        }
        IF ($record->CODE== 'C_DAAMOUNT_') {
            $C_DAAMOUNT_= $record->AMOUNT;
        }
        IF ($record->CODE== 'C_loffdagenincamt_') {
            $C_loffdagenincamt_= $record->AMOUNT;
        }
        IF ($record->CODE== 'C_pwagesinc_') {
            $C_pwagesinc_= $record->AMOUNT;
        }
        IF ($record->CODE== 'C_TOTAL_DEDUCTION_') {
            $C_TOTAL_DEDUCTION_= $record->AMOUNT;
        }
        IF ($record->CODE== 'C_eldwagesincamt_') {
            $C_eldwagesincamt_= $record->AMOUNT;
        }
        IF ($record->CODE== 'C_na_') {
            $C_na_= $record->AMOUNT;
        }
        IF ($record->CODE== 'C_extrahourswages_') {
            $C_extrahourswages_= $record->AMOUNT;
        }
        IF ($record->CODE== 'C_EPF_') {
            $C_EPF_= $record->AMOUNT;
        }
        IF ($record->CODE== 'C_netpayamount_') {
            $C_netpayamount_= $record->AMOUNT;
        }
        IF ($record->CODE== 'C_finalnetpay_') {
            $C_finalnetpay_= $record->AMOUNT;
        }
        IF ($record->CODE== 'C_genincamt_') {
            $C_genincamt_= $record->AMOUNT;
        }
        IF ($record->CODE== 'C_inc_') {
            $C_inc_= $record->AMOUNT;
        }
        IF ($record->CODE== 'C_elddagenincamt_') {
            $C_elddagenincamt_= $record->AMOUNT;
        }
        IF ($record->CODE== 'C_exthrsdagincamt_') {
            $C_exthrsdagincamt_= $record->AMOUNT;
        }
        IF ($record->CODE== 'C_rentdedamt_') {
            $C_rentded_= $record->AMOUNT;
        }
        IF ($record->CODE== 'C_NET') {
            $C_NET= $record->AMOUNT;
        }
        IF ($record->CODE== 'C_OTHER_ALLOW_') {
            $C_OTHER_ALLOW_= $record->AMOUNT;
        }
        IF ($record->CODE== 'C_eldwagesincamt_') {
            $C_eldwagesincamt_= $record->AMOUNT;
        }
        IF ($record->CODE== 'C_gross2amt_') {
            $C_gross2amt_= $record->AMOUNT;
        }
        IF ($record->CODE== 'C_ESI_') {
            $C_ESI_= $record->AMOUNT;
        }
        IF ($record->CODE== 'C_iftudedamt_') {
            $C_iftudedamt_= $record->AMOUNT;
        }
        IF ($record->CODE== 'C_festwagesincamt_') {
            $C_festwagesincamt_= $record->AMOUNT;
        }
        IF ($record->CODE== 'C_festdagenincamt_') {
            $C_festdagenincamt_= $record->AMOUNT;
        }
        IF ($record->CODE== 'C_HRA_') {
            $C_HRA_= $record->AMOUNT;
        }
        IF ($record->CODE== 'C_exadvance_') {
            $C_exadvance_= $record->AMOUNT;
        }
        IF ($record->CODE== 'C_govtwelfare_') {
            $C_govtwelfare_= $record->AMOUNT;
        }
        IF ($record->CODE== 'C_finalnetpay_') {
            $C_finalnetpay_= $record->AMOUNT;
        }
        IF ($record->CODE== 'C_totalwagesamt_') {
            $C_totalwagesamt_= $record->AMOUNT;
        }
        IF ($record->CODE== 'C_gross1amt_') {
            $C_gross1amt_= $record->AMOUNT;
        }
        IF ($record->CODE== 'C_PTAX_') {
            $C_PTAX_= $record->AMOUNT;
        }
        IF ($record->CODE== 'C_landrentamt_') {
            $C_landrentamt_= $record->AMOUNT;
        }
        IF ($record->CODE== 'C_mveplus_') {
            $C_mveplus_= $record->AMOUNT;
        }
        IF ($record->CODE== 'C_mveminusamt_') {
            $C_mveminus_= $record->AMOUNT;
//            echo 'mn amt'.$C_mveminus_;
        }

        IF ($record->CODE== 'C_PFG_') {
            $C_PFG_= $record->AMOUNT;
        }
        IF ($record->CODE== 'C_otherpay_') {
            $C_otherpay_= $record->AMOUNT;
        }
        IF ($record->CODE== 'C_canteen_') {
            $C_canteen_= $record->AMOUNT;
        }
        IF ($record->CODE== 'C_loomproducion_') {
            $C_loomproducion_= $record->AMOUNT;
        }
        IF ($record->CODE== 'C_sldwagesincamt_') {
            $C_sldwagesincamt_= $record->AMOUNT;
        }

        
    }   	
    


// MAIN LOOP END
                if  ($C_gross2amt_>0) {
                $totamt=$totamt+$C_NET; 
                $tvard=$tvard+$C_iftudedamt_;
             	$pg++;
                //=====================================PRINT
            $logMsg .=chr(15).' ' . ' LIMELIGHT COMM PVT LTD  ' . 
            str_repeat('', max(0, 0 - strlen($RATE))) . 
            number_format($RATE,4) . '  ' . 
            'PAY SLIP FOR:' . str_repeat(' ', max(0, 9 - strlen($payslipDate))) . $payslipDate . '  ' . 
            'DEPT: ' . str_repeat(' ', max(0, 8 - strlen($department))) . substr($department,0,12) . '    ' . 
            '#'.' PFN:' . str_repeat('  ', 5 - strlen($PFNO)) . $PFNO . '   ' . 
            'DAYS:' . str_repeat('', max(0, 3 - strlen($days))) . number_format($days,2) . '                 '. chr(14).$parintDate . "\n";

                    
                $logMsg.='   NAME  :'. str_repeat('', $padding). substr($emp_name, 0, 15).'  '.'     DESG:' . str_repeat('', $padding) . substr($designation, 0, 6) .'      '.'ESINO: '.str_repeat('', 52 - strlen($esi_no)).$esi_no.'  '.'TKT NO: '.str_repeat('', 57 - strlen($eb_no)).$eb_no.'  '.'CODE:'.str_repeat(' ', 6 - strlen($eb_no)).$eb_no.'                       '.'PAY SLIP FOR:'.str_repeat('', 14 - strlen($payslipDate)).$payslipDate."\n";
                

                if ($att_payschm==164) {    
                $logMsg.='   '.'THR1  :'.str_repeat(' ', 8 - strlen($C_wrkhrs1_)).$C_wrkhrs1_.'   '.'THR2 :'.str_repeat(' ', 8 - strlen($C_wrkhours2_)).$C_wrkhours2_.'   '.'PHRS :'.str_repeat(' ', 8 - strlen($C_PHours_)).$C_PHours_.'   '.'CPN  :'.str_repeat(' ', 8 - strlen($C_nightdays_)).$C_nightdays_.'   '.'FHRS :'.str_repeat(' ', 8 - strlen($C_FHours_)).$C_FHours_.'   '.'LHRS :'.str_repeat(' ', 8 - strlen($C_LayOffHrs_)).$C_LayOffHrs_.'            '.'DEPT:'.str_repeat('', 14 - strlen($department)).$department."\n";
        
                $logMsg.='   '.'SLD   :'.str_repeat(' ', 4 - strlen($SLD)).number_format($SLD,2).'    '.'ELD  :'.str_repeat(' ', 8 - strlen($C_eldays_)).$C_eldays_.'   '.'LODAY:'.str_repeat(' ', 5 - strlen($LODAY)).number_format($LODAY,2).'   '.'OTTHR:'.str_repeat(' ', 8 - strlen($C_extrahrstime_)).$C_extrahrstime_.'   '.'OTPHR:'.str_repeat(' ', 8 - strlen($C_extrahourspiece_)).$C_extrahourspiece_.'   '.'ESID :'.str_repeat(' ', 8 - strlen($C_esi_days_)).$C_esi_days_.'            '.''.str_repeat('', 35 - strlen($emp_name)).$emp_name."\n";
                
                $logMsg.='  '.' TWAGE :'.str_repeat(' ', 8 - strlen($C_timewages_)).$C_timewages_.'   '.'DJ.A:'.str_repeat(' ', 8 - strlen($C_mveminus_)).$C_sundayadv_.'   '.'LOWI :'.str_repeat(' ', 8 - strlen($C_loffwagesincamt_)).$C_loffwagesincamt_.'  '.'ILT&PW:'.str_repeat(' ', 5 - strlen($ILT)).number_format($ILT,2).'   '.'S.ADV:'.str_repeat(' ', 5 - strlen($PADV)).number_format($PADV,2).'   '.'RSD  :'.str_repeat(' ', 8 - strlen($C_rsdamt_)).$C_rsdamt_.'            '.'CODE: '.str_repeat('', 65 - strlen($eb_no)).$eb_no."\n";
                
                $logMsg.='  '.' P.WAGE:'.str_repeat(' ', 8 - strlen($C_pwage_)).$C_pwage_.'  '.' DA   :'.str_repeat(' ', 8 - strlen($C_DAAMOUNT_)).$C_DAAMOUNT_.'  '.' LODG :'.str_repeat(' ', 8 - strlen($C_loffdagenincamt_)).$C_loffdagenincamt_.' '.' INCENT:'.str_repeat(' ', 8 - strlen($C_pwagesinc_)).$C_pwagesinc_.'  '.' CDN  :'.str_repeat(' ', 5 - strlen($CDN)).number_format($CDN,2).'  '.' GDED :'.str_repeat(' ', 8 - strlen($C_TOTAL_DEDUCTION_)).$C_TOTAL_DEDUCTION_.'            '.'TKT:'.str_repeat('', 25 - strlen($eb_no)).$eb_no."\n";
                
                $logMsg.='  '.' NA    :'.str_repeat(' ', 8 - strlen($C_na_)).$C_na_.'  '.' EWI  :'.str_repeat(' ', 8 - strlen($C_eldwagesincamt_)).$C_eldwagesincamt_.'  '.' OTW  :'.str_repeat(' ', 8 - strlen($C_extrahourswages_)).$C_extrahourswages_.'  '.' ILIN :'.str_repeat(' ', 5 - strlen($ILINT)).number_format($ILINT,2).'  '.' PF   :'.str_repeat(' ', 8 - strlen($C_EPF_)).$C_EPF_.'  '.' NPAY :'.str_repeat(' ', 8 - strlen($C_netpayamount_)).$C_netpayamount_.'            '.chr(14).'NET:'.str_repeat('', 25 - strlen($C_finalnetpay_)).$C_finalnetpay_."\n";
                
                $logMsg.='  '.' GI    :'.str_repeat(' ', 8 - strlen($C_genincamt_)).$C_genincamt_.'  '.' EDG  :'.str_repeat(' ', 8 - strlen($C_elddagenincamt_)).$C_elddagenincamt_.'  '.' OTI  :'.str_repeat(' ', 8 - strlen($C_extrahoursincamt_)).$C_extrahoursincamt_.'  '.' ILDG :'.str_repeat(' ', 5 - strlen($ILDG)).number_format($ILDG,2).'  '.' RENT :'.str_repeat(' ', 8 - strlen($C_rentded_)).$C_rentded_.'  '.' RNPAY:'.str_repeat(' ', 8 - strlen($C_NET)).$C_NET."\n";
                
                $logMsg.='  '.' INC   :'.str_repeat(' ', 8 - strlen($C_inc_)).$C_inc_.'   '.'FDG  :'.str_repeat(' ', 8 - strlen($C_festdagenincamt_)).$C_festdagenincamt_.'  '.' ODG  :'.str_repeat(' ', 8 - strlen($C_exthrsdagincamt_)).$C_exthrsdagincamt_.'  '.' GRS2 :'.str_repeat(' ', 8 - strlen($C_gross2amt_)).$C_gross2amt_.'  '.' ESI  :'.str_repeat(' ', 8 - strlen($C_ESI_)).$C_ESI_.'  '.' VARD1:'.str_repeat(' ', 8 - strlen($C_iftudedamt_)).$C_iftudedamt_."\n";
                
                $logMsg.='  '.' FWI   :'.str_repeat(' ', 8 - strlen($C_festwagesincamt_)).$C_festwagesincamt_.'  '.' SDG  :'.str_repeat(' ', 8 - strlen($C_sldwagesincamt_)).$C_sldwagesincamt_.'  '.' HRA  :'.str_repeat(' ', 8 - strlen($C_HRA_)).$C_HRA_.' '.' ADVDED:'.str_repeat(' ', 8 - strlen($C_exadvance_)).$C_exadvance_.'  '.' G.W.F:'.str_repeat(' ', 8 - strlen($C_govtwelfare_)).$C_govtwelfare_.'  '.' NET  :'.str_repeat(' ', 8 - strlen($C_finalnetpay_)).$C_finalnetpay_."\n";
            
                $logMsg.='  '.' SWI   :'.str_repeat(' ', 5 - strlen($SWOA)).number_format($SWOA,2).' '.'  TOTAL:'.str_repeat(' ', 8 - strlen($C_totalwagesamt_)).$C_totalwagesamt_.'  '.' GRS1 :'.str_repeat(' ', 8 - strlen($C_gross1amt_)).$C_gross1amt_.'  '.' PTAX :'.str_repeat(' ', 8 - strlen($C_PTAX_)).$C_PTAX_.'  '.' LR   :'.str_repeat(' ', 8 - strlen($C_landrentamt_)).$C_landrentamt_."\n";
                $logMsg.='  '.' PVE   :'.str_repeat(' ', 8 - strlen($C_mveplus_)).$C_mveplus_.' '.'  P.F.G:'.str_repeat(' ', 8 - strlen($C_PFG_)).$C_PFG_.'  '.' OP   :'.str_repeat(' ', 8 - strlen($C_otherpay_)).$C_otherpay_.'  '.' CANT :'.str_repeat(' ', 8 - strlen($C_canteen_)).$C_canteen_.'  '.' PROD :'.str_repeat(' ', 8 - strlen($C_loomproducion_)).$C_loomproducion_."\n";
                }

                if ($att_payschm==167) { 
                $logMsg.='   '.'THR1  :'.str_repeat(' ', 8 - strlen($C_wrkhrs1_)).$C_wrkhrs1_.'   '.'THR2 :'.str_repeat(' ', 8 - strlen($C_wrkhours2_)).$C_wrkhours2_.'   '.'PHRS :'.str_repeat(' ', 8 - strlen($C_PHours_)).$C_PHours_.'   '.'CPN  :'.str_repeat(' ', 8 - strlen($C_nightdays_)).$C_nightdays_.'   '.'FHRS :'.str_repeat(' ', 8 - strlen($C_FHours_)).$C_FHours_.'   '.'LHRS :'.str_repeat(' ', 8 - strlen($C_LayOffHrs_)).$C_LayOffHrs_.'            '.'DEPT:'.str_repeat('', 14 - strlen($department)).$department."\n";
        
                $logMsg.='   '.'SLD   :'.str_repeat(' ', 4 - strlen($SLD)).number_format($SLD,2).'    '.'ELD  :'.str_repeat(' ', 8 - strlen($C_eldays_)).$C_eldays_.'   '.'LODAY:'.str_repeat(' ', 5 - strlen($LODAY)).number_format($LODAY,2).'   '.'OTTHR:'.str_repeat(' ', 8 - strlen($C_extrahrstime_)).$C_extrahrstime_.'   '.'OTPHR:'.str_repeat(' ', 8 - strlen($C_extrahourspiece_)).$C_extrahourspiece_.'   '.'ESID :'.str_repeat(' ', 8 - strlen($C_esi_days_)).$C_esi_days_.'            '.''.str_repeat('', 35 - strlen($emp_name)).$emp_name."\n";
                
                $logMsg.='  '.' TWAGE :'.str_repeat(' ', 8 - strlen($C_timewages_)).$C_timewages_.'   '.'ADJ.A:'.str_repeat(' ', 8 - strlen($C_mveminus_)).$C_sundayadv_.'   '.'LOWI :'.str_repeat(' ', 8 - strlen($C_loffwagesincamt_)).$C_loffwagesincamt_.'  '.'ILT&PW:'.str_repeat(' ', 5 - strlen($ILT)).number_format($ILT,2).'   '.'S.ADV:'.str_repeat(' ', 5 - strlen($PADV)).number_format($PADV,2).'   '.'RSD  :'.str_repeat(' ', 8 - strlen($C_rsdamt_)).$C_rsdamt_.'            '.'CODE: '.str_repeat('', 65 - strlen($eb_no)).$eb_no."\n";
                
                $logMsg.='  '.' P.WAGE:'.str_repeat(' ', 8 - strlen($C_pwage_)).$C_pwage_.'  '.' DA   :'.str_repeat(' ', 8 - strlen($C_DAAMOUNT_)).$C_DAAMOUNT_.'  '.' LODG :'.str_repeat(' ', 8 - strlen($C_loffdagenincamt_)).$C_loffdagenincamt_.' '.' INCENT:'.str_repeat(' ', 8 - strlen($C_pwagesinc_)).$C_pwagesinc_.'  '.' CDN  :'.str_repeat(' ', 5 - strlen($CDN)).number_format($CDN,2).'  '.' GDED :'.str_repeat(' ', 8 - strlen($C_TOTAL_DEDUCTION_)).$C_TOTAL_DEDUCTION_.'            '.'TKT:'.str_repeat('', 25 - strlen($eb_no)).$eb_no."\n";
                
                $logMsg.='  '.' NA    :'.str_repeat(' ', 8 - strlen($C_na_)).$C_na_.'  '.' EWI  :'.str_repeat(' ', 8 - strlen($C_eldwagesincamt_)).$C_eldwagesincamt_.'  '.' OTW  :'.str_repeat(' ', 8 - strlen($C_extrahourswages_)).$C_extrahourswages_.'  '.' ILIN :'.str_repeat(' ', 5 - strlen($ILINT)).number_format($ILINT,2).'  '.' PF   :'.str_repeat(' ', 8 - strlen($C_EPF_)).$C_EPF_.'  '.' NPAY :'.str_repeat(' ', 8 - strlen($C_netpayamount_)).$C_netpayamount_.'            '.chr(14).'NET:'.str_repeat('', 25 - strlen($C_finalnetpay_)).$C_finalnetpay_."\n";
                
                $logMsg.='  '.' GI    :'.str_repeat(' ', 8 - strlen($C_genincamt_)).$C_genincamt_.'  '.' EDG  :'.str_repeat(' ', 8 - strlen($C_elddagenincamt_)).$C_elddagenincamt_.'  '.' OTI  :'.str_repeat(' ', 8 - strlen($C_extrahoursincamt_)).$C_extrahoursincamt_.'  '.' ILDG :'.str_repeat(' ', 5 - strlen($ILDG)).number_format($ILDG,2).'  '.' RENT :'.str_repeat(' ', 8 - strlen($C_rentded_)).$C_rentded_.'  '.' RNPAY:'.str_repeat(' ', 8 - strlen($C_NET)).$C_NET."\n";
                
                $logMsg.='  '.' INC   :'.str_repeat(' ', 8 - strlen($C_inc_)).$C_inc_.'   '.'FDG  :'.str_repeat(' ', 8 - strlen($C_festdagenincamt_)).$C_festdagenincamt_.'  '.' ODG  :'.str_repeat(' ', 8 - strlen($C_exthrsdagincamt_)).$C_exthrsdagincamt_.'  '.' GRS2 :'.str_repeat(' ', 8 - strlen($C_gross2amt_)).$C_gross2amt_.'  '.' ESI  :'.str_repeat(' ', 8 - strlen($C_ESI_)).$C_ESI_.'  '.' VARD1:'.str_repeat(' ', 8 - strlen($C_iftudedamt_)).$C_iftudedamt_."\n";
                
                $logMsg.='  '.' FWI   :'.str_repeat(' ', 8 - strlen($C_festwagesincamt_)).$C_festwagesincamt_.'  '.' SDG  :'.str_repeat(' ', 8 - strlen($C_sldwagesincamt_)).$C_sldwagesincamt_.'  '.' HRA  :'.str_repeat(' ', 8 - strlen($C_HRA_)).$C_HRA_.' '.' ADVDED:'.str_repeat(' ', 8 - strlen($C_exadvance_)).$C_exadvance_.'  '.' G.W.F:'.str_repeat(' ', 8 - strlen($C_govtwelfare_)).$C_govtwelfare_.'  '.' NET  :'.str_repeat(' ', 8 - strlen($C_finalnetpay_)).$C_finalnetpay_."\n";
            
                $logMsg.='  '.' SWI   :'.str_repeat(' ', 5 - strlen($SWOA)).number_format($SWOA,2).' '.'  TOTAL:'.str_repeat(' ', 8 - strlen($C_totalwagesamt_)).$C_totalwagesamt_.'  '.' GRS1 :'.str_repeat(' ', 8 - strlen($C_gross1amt_)).$C_gross1amt_.'  '.' PTAX :'.str_repeat(' ', 8 - strlen($C_PTAX_)).$C_PTAX_.'  '.' LR   :'.str_repeat(' ', 8 - strlen($C_landrentamt_)).$C_landrentamt_."\n";
                $logMsg.='  '.' PVE   :'.str_repeat(' ', 8 - strlen($C_mveplus_)).$C_mveplus_.' '.'  P.F.G:'.str_repeat(' ', 8 - strlen($C_PFG_)).$C_PFG_.'  '.' OP   :'.str_repeat(' ', 8 - strlen($C_otherpay_)).$C_otherpay_.'  '.' CANT :'.str_repeat(' ', 8 - strlen($C_canteen_)).$C_canteen_.'  '.' PROD :'.str_repeat(' ', 8 - strlen($C_loomproducion_)).$C_loomproducion_."\n";
                }


                if ($att_payschm==169) { 
                $logMsg.='   '.'THR1  :'.str_repeat(' ', 8 - strlen($C_wrkhrs1_)).$C_wrkhrs1_.'   '.'THR2 :'.str_repeat(' ', 8 - strlen($C_wrkhours2_)).$C_wrkhours2_.'   '.'PHRS :'.str_repeat(' ', 8 - strlen($C_PHours_)).$C_PHours_.'   '.'CPN  :'.str_repeat(' ', 8 - strlen($C_nightdays_)).$C_nightdays_.'   '.'FHRS :'.str_repeat(' ', 8 - strlen($C_FHours_)).$C_FHours_.'   '.'LHRS :'.str_repeat(' ', 8 - strlen($C_LayOffHrs_)).$C_LayOffHrs_.'            '.'DEPT:'.str_repeat('', 14 - strlen($department)).$department."\n";
        
                $logMsg.='   '.'SLD   :'.str_repeat(' ', 4 - strlen($SLD)).number_format($SLD,2).'    '.'ELD  :'.str_repeat(' ', 8 - strlen($C_eldays_)).$C_eldays_.'   '.'LODAY:'.str_repeat(' ', 5 - strlen($LODAY)).number_format($LODAY,2).'   '.'OTTHR:'.str_repeat(' ', 8 - strlen($C_extrahrstime_)).$C_extrahrstime_.'   '.'OTPHR:'.str_repeat(' ', 8 - strlen($C_extrahourspiece_)).$C_extrahourspiece_.'   '.'ESID :'.str_repeat(' ', 8 - strlen($C_esi_days_)).$C_esi_days_.'            '.''.str_repeat('', 35 - strlen($emp_name)).$emp_name."\n";
                
                $logMsg.='  '.' TWAGE :'.str_repeat(' ', 8 - strlen($C_timewages_)).$C_timewages_.'   '.'ADJ.A:'.str_repeat(' ', 8 - strlen($C_mveminus_)).$C_sundayadv_.'   '.'LOWI :'.str_repeat(' ', 8 - strlen($C_loffwagesincamt_)).$C_loffwagesincamt_.'  '.'ILT&PW:'.str_repeat(' ', 5 - strlen($ILT)).number_format($ILT,2).'   '.'S.ADV:'.str_repeat(' ', 5 - strlen($PADV)).number_format($PADV,2).'   '.'RSD  :'.str_repeat(' ', 8 - strlen($C_rsdamt_)).$C_rsdamt_.'            '.'CODE: '.str_repeat('', 65 - strlen($eb_no)).$eb_no."\n";
                
                $logMsg.='  '.' P.WAGE:'.str_repeat(' ', 8 - strlen($C_pwage_)).$C_pwage_.'  '.' DA   :'.str_repeat(' ', 8 - strlen($C_DAAMOUNT_)).$C_DAAMOUNT_.'  '.' LODG :'.str_repeat(' ', 8 - strlen($C_loffdagenincamt_)).$C_loffdagenincamt_.' '.' INCENT:'.str_repeat(' ', 8 - strlen($C_pwagesinc_)).$C_pwagesinc_.'  '.' CDN  :'.str_repeat(' ', 5 - strlen($CDN)).number_format($CDN,2).'  '.' GDED :'.str_repeat(' ', 8 - strlen($C_TOTAL_DEDUCTION_)).$C_TOTAL_DEDUCTION_.'            '.'TKT:'.str_repeat('', 25 - strlen($eb_no)).$eb_no."\n";
                
                $logMsg.='  '.' NA    :'.str_repeat(' ', 8 - strlen($C_na_)).$C_na_.'  '.' EWI  :'.str_repeat(' ', 8 - strlen($C_eldwagesincamt_)).$C_eldwagesincamt_.'  '.' OTW  :'.str_repeat(' ', 8 - strlen($C_extrahourswages_)).$C_extrahourswages_.'  '.' ILIN :'.str_repeat(' ', 5 - strlen($ILINT)).number_format($ILINT,2).'  '.' PF   :'.str_repeat(' ', 8 - strlen($C_EPF_)).$C_EPF_.'  '.' NPAY :'.str_repeat(' ', 8 - strlen($C_netpayamount_)).$C_netpayamount_.'            '.chr(14).'NET:'.str_repeat('', 25 - strlen($C_finalnetpay_)).$C_finalnetpay_."\n";
                
                $logMsg.='  '.' GI    :'.str_repeat(' ', 8 - strlen($C_genincamt_)).$C_genincamt_.'  '.' EDG  :'.str_repeat(' ', 8 - strlen($C_elddagenincamt_)).$C_elddagenincamt_.'  '.' OTI  :'.str_repeat(' ', 8 - strlen($C_extrahoursincamt_)).$C_extrahoursincamt_.'  '.' ILDG :'.str_repeat(' ', 5 - strlen($ILDG)).number_format($ILDG,2).'  '.' RENT :'.str_repeat(' ', 8 - strlen($C_rentded_)).$C_rentded_.'  '.' RNPAY:'.str_repeat(' ', 8 - strlen($C_NET)).$C_NET."\n";
                
                $logMsg.='  '.' O.A   :'.str_repeat(' ', 8 - strlen($C_OTHER_ALLOW_)).$C_OTHER_ALLOW_.'   '.'FDG  :'.str_repeat(' ', 8 - strlen($C_festdagenincamt_)).$C_festdagenincamt_.'  '.' ODG  :'.str_repeat(' ', 8 - strlen($C_exthrsdagincamt_)).$C_exthrsdagincamt_.'  '.' GRS2 :'.str_repeat(' ', 8 - strlen($C_gross2amt_)).$C_gross2amt_.'  '.' ESI  :'.str_repeat(' ', 8 - strlen($C_ESI_)).$C_ESI_.'  '.' VARD1:'.str_repeat(' ', 8 - strlen($C_iftudedamt_)).$C_iftudedamt_."\n";
                
                $logMsg.='  '.' FWOA  :'.str_repeat(' ', 8 - strlen($C_festwagesincamt_)).$C_festwagesincamt_.'  '.' SDG  :'.str_repeat(' ', 8 - strlen($C_sldwagesincamt_)).$C_sldwagesincamt_.'  '.' HRA  :'.str_repeat(' ', 8 - strlen($C_HRA_)).$C_HRA_.' '.' ADVDED:'.str_repeat(' ', 8 - strlen($C_exadvance_)).$C_exadvance_.'  '.' G.W.F:'.str_repeat(' ', 8 - strlen($C_govtwelfare_)).$C_govtwelfare_.'  '.' NET  :'.str_repeat(' ', 8 - strlen($C_finalnetpay_)).$C_finalnetpay_."\n";
            
                $logMsg.='  '.' SWOA  :'.str_repeat(' ', 5 - strlen($SWOA)).number_format($SWOA,2).' '.'  TOTAL:'.str_repeat(' ', 8 - strlen($C_totalwagesamt_)).$C_totalwagesamt_.'  '.' GRS1 :'.str_repeat(' ', 8 - strlen($C_gross1amt_)).$C_gross1amt_.'  '.' PTAX :'.str_repeat(' ', 8 - strlen($C_PTAX_)).$C_PTAX_.'  '.' LR   :'.str_repeat(' ', 8 - strlen($C_landrentamt_)).$C_landrentamt_."\n";
                $logMsg.='  '.' PVE   :'.str_repeat(' ', 8 - strlen($C_mveplus_)).$C_mveplus_.' '.'       :'.str_repeat(' ', 8 - strlen($bln   )).$bln   .'  '.' OP   :'.str_repeat(' ', 8 - strlen($C_otherpay_)).$C_otherpay_.'  '.' CANT :'.str_repeat(' ', 8 - strlen($C_canteen_)).$C_canteen_.'  '.' PROD :'.str_repeat(' ', 8 - strlen($C_loomproducion_)).$C_loomproducion_."\n";
                }


                //$totamt=$totamt+$record->NET;
                //$tvard=$tvard+$record->VRD1;
            //    $totamt=$totamt+$record->C_NET; 
            //    $tvard=$tvard+$record->C_iftudedamt_;
                ///$department+$record->department;
            //	$tpgn=$tpgn+$record->pgn;
            
                    
        ///====================================PRINT

        //RESET
            $logMsg .= $line."\n";
            $lnn=$lnn++;
                $pgn++;		
                if ( $pgn>5 ) { 	
                    //$logMsg .= Chr(12);
                    $logMsg .=chr(18). Chr(12)."\n";
                $pgn=1;
            }	
            } 


//
        $logMsg.=$bln."\n";
		$logMsg.=$bln."\n";
		$logMsg .='   '.' TOTAL  NO. OF PAYSLIPS :'.$pg. "\n";
		$logMsg .='  '.'  TOTAL NET AMOUNT:  '.$totamt.'         '.'  LOAN DED: '.$tvard.'            '.'  DEPARTMENT :'.$department. chr(18). Chr(12)."\n";
		
		$logMsg .=chr(18). Chr(12)."\n";

    // 
    $fileContainer = FCPATH . "payslip.txt";
    file_put_contents($fileContainer, $logMsg);
   $zipname = FCPATH . "NJMpayslip.zip";
    $zip = new ZipArchive;
    if ($zip->open($zipname, ZipArchive::CREATE) === TRUE) {
    $zip->addFile($fileContainer, basename($fileContainer));
        $zip->close();
    }

    // Download 
    header('Content-Type: application/zip');
    header('Content-Disposition: attachment; filename="NJMpayslip.zip"');
    header('Content-Length: ' . filesize($zipname));
    readfile($zipname);

  
    unlink($fileContainer);
    unlink($zipname);
   }






 public function njmwagespayslip164($mccodes,$periodfromdate,$periodtodate) {
 

    $logMsg = "";
    $totamt = 0;
    $tvard  = 0;
    $pg     = 0;
    $department = "";
    $esino=123456789;
   $CODE = 'N0000514';
    //echo $CODE; // Output: N0000514
        $dpc='';
    $payslipDate = date("M'Y", strtotime($periodtodate));
    $printDate   = date("d,M'Y");
    $parintDate = date("10,M'Y");
   // echo $parintDate;   C_finalnetpay_



    $lnn=1;
   		$pg=0;
        $tpgn=0;    
        $totamt=0;
        $tvard=0;
 
    foreach ($mccodes as $record) {
       // echo $record->emp_name;
       // echo '=='.$record->dept_code;
			   if ($dpc<>$record->dept_code ) { 	
				   if (strlen($dpc)>0) {
					$logMsg .= $bln."\n";
					$logMsg.=$bln."\n";
					$logMsg.=$bln."\n";
					$logMsg .='   '.' TOTAL  NO. OF PAYSLIPS :'.$pg. "\n";
					$logMsg .='  '.'  TOTAL NET AMOUNT:  '.$totamt.'         '.'  LOAN DED: '.$tvard.'            '.'  DEPARTMENT :'.$dpc .  Chr(12);
			//		$logMsg .= Chr(12);
                   
  		$pg=0;
        $tpgn=0;    
        $totamt=0;
        $tvard=0;
 				
			   }	
			 
			  
			   $dpc=$record->dept_code;
			   $dptcode=$dpc;
			   
			   $lnn=1;
			   $tpgn++;
				 
			   $rnop=0;	
			   $pgn=0;     
			
		   }	
		   $pgn++;		
		   if ( $pgn>5 ) { 	
			//$logMsg .= Chr(12);
            $logMsg .=chr(18). Chr(12)."\n";
		  $pgn=1;
	  }	
              $totamt=$totamt+$record->C_NET; 
             $tvard=$tvard+$record->C_iftudedamt_;
			   $pg++;
	$logMsg .= $line."\n";
        $esi_no=1234567890;
		$RATE='5.54211';
        $PFNO='8892';
        $SLD=0.00;
        $LODAY=0.00;
        $ILT=0.00;
        $PADV=0.00;
        $CDN=0.00;
        $ILINT=0.00;
        $ILDG=0.00;
        $SWOA=0.00;
        $emp_name = $record->emp_name;
        $padding = max(0, 20 - strlen($emp_name));
        $designation= $record->designation;
        $padding = max(0, 15 - strlen($designation));
        $eb_no= $record->eb_no;
        $C_wrkhrs1_= $record->C_wrkhrs1_;
        $C_wrkhours2_= $record->C_wrkhours2_;
        $C_PHours_= $record->C_PHours_;
		$department=substr($record->department,0,6);
        $department= $record->department;
       $days = ($record->C_wrkhrs1_ + $record->C_PHours_ + $record->C_wrkhours2_ )/ 8;
        $C_nightdays_= $record->C_nightdays_;
        $C_FHours_= $record->C_FHours_;
        $C_LayOffHrs_= $record->C_LayOffHrs_;
        $C_eldays_= $record->C_eldays_;
        $C_extrahrstime_= $record->C_extrahrstime_;
        $C_extrahourspiece_= $record->C_extrahourspiece_;
        $C_esi_days_= $record->C_esi_days_;
        $C_timewages_= $record->C_timewages_;
        $C_sundayadv_= $record->C_sundayadv_;
        $C_loffwagesincamt_= $record->C_loffwagesincamt_;
        $C_rsdamt_= $record->C_rsdamt_;
        $C_pwage_= $record->C_pwage_;
        $C_DAAMOUNT_= $record->C_DAAMOUNT_;
        $C_loffdagenincamt_= $record->C_loffdagenincamt_;
        $C_pwagesinc_= $record->C_pwagesinc_;
        $C_TOTAL_DEDUCTION_ = $record->C_TOTAL_DEDUCTION_;
        $C_eldwagesincamt_ = $record->C_eldwagesincamt_;
        $C_na_ = $record->C_na_;
        $C_extrahourswages_ = $record->C_extrahourswages_;
        $C_EPF_ = $record->C_EPF_;
        $C_netpayamount_ = $record->C_netpayamount_;
        $C_finalnetpay_ = $record->C_finalnetpay_;
        $C_genincamt_ = $record->C_genincamt_;
        $C_elddagenincamt_ = $record->C_elddagenincamt_;
        $C_exthrsdagincamt_ = $record->C_exthrsdagincamt_;
        $C_rentded_ = $record->C_rentded_;
        $C_NET = $record->C_NET;
        $C_OTHER_ALLOW_ = $record->C_OTHER_ALLOW_;
        $C_eldwagesincamt_ = $record->C_eldwagesincamt_;
        $C_gross2amt_ = $record->C_gross2amt_;
        $C_ESI_ = $record->C_ESI_;
        $C_iftudedamt_ = $record->C_iftudedamt_;
        $C_festwagesincamt_ = $record->C_festwagesincamt_;
        $C_festdagenincamt_ = $record->C_festdagenincamt_;
        $C_HRA_ = $record->C_HRA_;
        $C_exadvance_ = $record->C_exadvance_;
        $C_govtwelfare_ = $record->C_govtwelfare_;
        $C_finalnetpay_ = $record->C_finalnetpay_;
        $C_totalwagesamt_ = $record->C_totalwagesamt_;
        $C_gross1amt_ = $record->C_gross1amt_;
        $C_PTAX_ = $record->C_PTAX_;
        $C_landrentamt_ = $record->C_landrentamt_;
        $C_mveplus_ = $record->C_mveplus_;
        $C_PFG_ = $record->C_PFG_;
        $C_otherpay_ = $record->C_otherpay_;
        $C_canteen_ = $record->C_canteen_;
        $C_loomproducion_ = $record->C_loomproducion_;
        $C_sldwagesincamt_ = $record->C_sldwagesincamt_;
		$desig=substr($record->desig,0,5);
		$desig=$record->desig;
		$TKTNO=$record->TKTNO;
		$CODE=$record->CODE;
		//$periodtodate=$record->periodtodate;
		
		/////'       '.'PFN :'.str_repeat(' ', 4 - strlen($pf_no)).$pf_no.'  '.'DAYS :'.str_repeat(' ', 20 - strlen($DAYS)).number_format($DAYS,2).
	//	$logMsg.=$bln."\n";
        	
	$logMsg .=chr(15).' ' . ' LIMELIGHT COMM PVT LTD  ' . 
    str_repeat('', max(0, 0 - strlen($RATE))) . 
    number_format($RATE,4) . '  ' . 
    'PAY SLIP FOR:' . str_repeat(' ', max(0, 9 - strlen($payslipDate))) . $payslipDate . '  ' . 
    'DEPT: ' . str_repeat(' ', max(0, 8 - strlen($department))) . substr($department,0,12) . '    ' . 
    '#'.' PFN:' . str_repeat('  ', 5 - strlen($PFNO)) . $PFNO . '   ' . 
    'DAYS:' . str_repeat('', max(0, 3 - strlen($days))) . number_format($days,2) . '                 '. chr(14).$parintDate . "\n";

	
		$logMsg.='   NAME  :'. str_repeat('', $padding). substr($emp_name, 0, 15).'  '.'     DESG:' . str_repeat('', $padding) . substr($designation, 0, 6) .'      '.'ESINO: '.str_repeat('', 52 - strlen($esi_no)).$esi_no.'  '.'TKT NO: '.str_repeat('', 57 - strlen($eb_no)).$eb_no.'  '.'CODE:'.str_repeat(' ', 6 - strlen($eb_no)).$eb_no.'                       '.'PAY SLIP FOR:'.str_repeat('', 14 - strlen($payslipDate)).$payslipDate."\n";
		  
		$logMsg.='   '.'THR1  :'.str_repeat(' ', 8 - strlen($C_wrkhrs1_)).$C_wrkhrs1_.'   '.'THR2 :'.str_repeat(' ', 8 - strlen($C_wrkhours2_)).$C_wrkhours2_.'   '.'PHRS :'.str_repeat(' ', 8 - strlen($C_PHours_)).$C_PHours_.'   '.'CPN  :'.str_repeat(' ', 8 - strlen($C_nightdays_)).$C_nightdays_.'   '.'FHRS :'.str_repeat(' ', 8 - strlen($C_FHours_)).$C_FHours_.'   '.'LHRS :'.str_repeat(' ', 8 - strlen($C_LayOffHrs_)).$C_LayOffHrs_.'            '.'DEPT:'.str_repeat('', 14 - strlen($department)).$department."\n";
	 
		$logMsg.='   '.'SLD   :'.str_repeat(' ', 4 - strlen($SLD)).number_format($SLD,2).'    '.'ELD  :'.str_repeat(' ', 8 - strlen($C_eldays_)).$C_eldays_.'   '.'LODAY:'.str_repeat(' ', 5 - strlen($LODAY)).number_format($LODAY,2).'   '.'OTTHR:'.str_repeat(' ', 8 - strlen($C_extrahrstime_)).$C_extrahrstime_.'   '.'OTPHR:'.str_repeat(' ', 8 - strlen($C_extrahourspiece_)).$C_extrahourspiece_.'   '.'ESID :'.str_repeat(' ', 8 - strlen($C_esi_days_)).$C_esi_days_.'            '.''.str_repeat('', 35 - strlen($emp_name)).$emp_name."\n";
		 
		$logMsg.='  '.' TWAGE :'.str_repeat(' ', 8 - strlen($C_timewages_)).$C_timewages_.'   '.'DJ.A:'.str_repeat(' ', 8 - strlen($C_sundayadv_)).$C_sundayadv_.'   '.'LOWI :'.str_repeat(' ', 8 - strlen($C_loffwagesincamt_)).$C_loffwagesincamt_.'  '.'ILT&PW:'.str_repeat(' ', 5 - strlen($ILT)).number_format($ILT,2).'   '.'S.ADV:'.str_repeat(' ', 5 - strlen($PADV)).number_format($PADV,2).'   '.'RSD  :'.str_repeat(' ', 8 - strlen($C_rsdamt_)).$C_rsdamt_.'            '.'CODE: '.str_repeat('', 65 - strlen($eb_no)).$eb_no."\n";
		 
		$logMsg.='  '.' P.WAGE:'.str_repeat(' ', 8 - strlen($C_pwage_)).$C_pwage_.'  '.' DA   :'.str_repeat(' ', 8 - strlen($C_DAAMOUNT_)).$C_DAAMOUNT_.'  '.' LODG :'.str_repeat(' ', 8 - strlen($C_loffdagenincamt_)).$C_loffdagenincamt_.' '.' INCENT:'.str_repeat(' ', 8 - strlen($C_pwagesinc_)).$C_pwagesinc_.'  '.' CDN  :'.str_repeat(' ', 5 - strlen($CDN)).number_format($CDN,2).'  '.' GDED :'.str_repeat(' ', 8 - strlen($C_TOTAL_DEDUCTION_)).$C_TOTAL_DEDUCTION_.'            '.'TKT:'.str_repeat('', 25 - strlen($eb_no)).$eb_no."\n";
		 
		$logMsg.='  '.' NA    :'.str_repeat(' ', 8 - strlen($C_na_)).$C_na_.'  '.' EWI  :'.str_repeat(' ', 8 - strlen($C_eldwagesincamt_)).$C_eldwagesincamt_.'  '.' OTW  :'.str_repeat(' ', 8 - strlen($C_extrahourswages_)).$C_extrahourswages_.'  '.' ILIN :'.str_repeat(' ', 5 - strlen($ILINT)).number_format($ILINT,2).'  '.' PF   :'.str_repeat(' ', 8 - strlen($C_EPF_)).$C_EPF_.'  '.' NPAY :'.str_repeat(' ', 8 - strlen($C_netpayamount_)).$C_netpayamount_.'            '.chr(14).'NET:'.str_repeat('', 25 - strlen($C_finalnetpay_)).$C_finalnetpay_."\n";
		 
		$logMsg.='  '.' GI    :'.str_repeat(' ', 8 - strlen($C_genincamt_)).$C_genincamt_.'  '.' EDG  :'.str_repeat(' ', 8 - strlen($C_elddagenincamt_)).$C_elddagenincamt_.'  '.' OTI  :'.str_repeat(' ', 8 - strlen($C_extrahoursincamt_)).$C_extrahoursincamt_.'  '.' ILDG :'.str_repeat(' ', 5 - strlen($ILDG)).number_format($ILDG,2).'  '.' RENT :'.str_repeat(' ', 8 - strlen($C_rentded_)).$C_rentded_.'  '.' RNPAY:'.str_repeat(' ', 8 - strlen($C_NET)).$C_NET."\n";
		 
		$logMsg.='  '.' INC   :'.str_repeat(' ', 8 - strlen($C_OTHER_ALLOW_)).$C_OTHER_ALLOW_.'   '.'FDG  :'.str_repeat(' ', 8 - strlen($C_festdagenincamt_)).$C_festdagenincamt_.'  '.' ODG  :'.str_repeat(' ', 8 - strlen($C_exthrsdagincamt_)).$C_exthrsdagincamt_.'  '.' GRS2 :'.str_repeat(' ', 8 - strlen($C_gross2amt_)).$C_gross2amt_.'  '.' ESI  :'.str_repeat(' ', 8 - strlen($C_ESI_)).$C_ESI_.'  '.' VARD1:'.str_repeat(' ', 8 - strlen($C_iftudedamt_)).$C_iftudedamt_."\n";
		 
		$logMsg.='  '.' FWI   :'.str_repeat(' ', 8 - strlen($C_festwagesincamt_)).$C_festwagesincamt_.'  '.' SDG  :'.str_repeat(' ', 8 - strlen($C_sldwagesincamt_)).$C_sldwagesincamt_.'  '.' HRA  :'.str_repeat(' ', 8 - strlen($C_HRA_)).$C_HRA_.' '.' ADVDED:'.str_repeat(' ', 8 - strlen($C_exadvance_)).$C_exadvance_.'  '.' G.W.F:'.str_repeat(' ', 8 - strlen($C_govtwelfare_)).$C_govtwelfare_.'  '.' NET  :'.str_repeat(' ', 8 - strlen($C_finalnetpay_)).$C_finalnetpay_."\n";
	 
		$logMsg.='  '.' SWI   :'.str_repeat(' ', 5 - strlen($SWOA)).number_format($SWOA,2).' '.'  TOTAL:'.str_repeat(' ', 8 - strlen($C_totalwagesamt_)).$C_totalwagesamt_.'  '.' GRS1 :'.str_repeat(' ', 8 - strlen($C_gross1amt_)).$C_gross1amt_.'  '.' PTAX :'.str_repeat(' ', 8 - strlen($C_PTAX_)).$C_PTAX_.'  '.' LR   :'.str_repeat(' ', 8 - strlen($C_landrentamt_)).$C_landrentamt_."\n";
		 
		$logMsg.='  '.' PVE   :'.str_repeat(' ', 8 - strlen($C_mveplus_)).$C_mveplus_.' '.'  P.F.G:'.str_repeat(' ', 8 - strlen($C_PFG_)).$C_PFG_.'  '.' OP   :'.str_repeat(' ', 8 - strlen($C_otherpay_)).$C_otherpay_.'  '.' CANT :'.str_repeat(' ', 8 - strlen($C_canteen_)).$C_canteen_.'  '.' PROD :'.str_repeat(' ', 8 - strlen($C_loomproducion_)).$C_loomproducion_."\n";
	 
		//$totamt=$totamt+$record->NET;
		//$tvard=$tvard+$record->VRD1;
          $totamt=$totamt+$record->C_NET; 
          $tvard=$tvard+$record->C_iftudedamt_;
		///$department+$record->department;
		$tpgn=$tpgn+$record->pgn;
		   $lnn=$lnn++;
		}
	//	$logMsg .= $bln."\n";
		$logMsg.=$bln."\n";
		$logMsg.=$bln."\n";
		$logMsg .='   '.' TOTAL  NO. OF PAYSLIPS :'.$pg. "\n";
		$logMsg .='  '.'  TOTAL NET AMOUNT:  '.$totamt.'         '.'  LOAN DED: '.$tvard.'            '.'  DEPARTMENT :'.$department. chr(18). Chr(12)."\n";
		
		$logMsg .=chr(18). Chr(12)."\n";

    // 
    $fileContainer = FCPATH . "NjmMainRoll.txt";
    file_put_contents($fileContainer, $logMsg);
   $zipname = FCPATH . "NJMpayslip.zip";
    $zip = new ZipArchive;
    if ($zip->open($zipname, ZipArchive::CREATE) === TRUE) {
    $zip->addFile($fileContainer, basename($fileContainer));
        $zip->close();
    }

    // Download 
    header('Content-Type: application/zip');
    header('Content-Disposition: attachment; filename="NJMpayslip.zip"');
    header('Content-Length: ' . filesize($zipname));
    readfile($zipname);

  
    unlink($fileContainer);
    unlink($zipname);
   }



   public function njmwagespayslip166($periodfromdate,$periodtodate,$att_payschm) {

        $mccodes = $this->Njmallwagesprocess->getcewagespayslip($periodfromdate,$periodtodate,$att_payschm);
        $this->load->library('fpdf_lib');
//	$pdf = new \FPDF();
        $pdf = $this->fpdf_lib; 
        $y=5;
        $pdf->AddPage('P', 'A4');
                        $mnth = date("M'Y", strtotime($periodtodate));

                        

        $y=$y+1;
        $kn=1;
        $pgn=1;
        $totamt=0;
        $tvard=0;
        $totslp=0;

        foreach ($mccodes as $row) {
            if ($pgn>5) {
                        $pdf->AddPage('P', 'A4');
                $pgn=1;
                $y=6;
            }
               $x=10;
               $pdf->SetFont('Arial', '', 8);
                $pdf->SetXY($x, $y);
            $wkd=$row->C_WORKING_DAYS_;
            $whrs=$row->C_WRK_DAY_;
            $nm=substr($row->emp_name,0,20);
         //   $dg=str_repeat(' ', 25 - strlen($row->designation)).$row->designation ;
            $dg=substr($row->designation,0,20);
            //.str_repeat(' ', 26 - strlen(substr($row->designation,0,25)));
            $dpn=substr($row->department,0,20);
            //.str_repeat(' ', 26 - strlen(substr($row->department,0,25)));
            $fhr=$row->C_FHours_ ;
            $wkrs=$row->C_WORKING_HR_;
            $eldays=$row->C_eldays_;
            $wage=$row->C_timewages_;
            $twage=$row->C_totalwagesamt_;
            $ptx=$row->C_PTAX_;
            $pfg=$row->C_PFG_;
            $gded=$row->C_TOTAL_DEDUCTION_;
            $da=$row->C_DAAMOUNT_;
            $pf=$row->C_EPF_;
            $varep=$row->C_ARRAMTPLUS_;
            $varem=$row->C_ARRAMTMINUS_;

            $mv=0.00;

            $zero=$mv;
            $mve=$mv;

            $gi=$row->C_genincamt_;
            $edgi=$mv;
            $rnt=$row->C_rentdedamt_;

            $fhw=$row->C_FHours_;
            $otw=$mv;
            $gwf=$row->C_gwfamtded_;
            $sad=$zero;
            $lren= $row->C_landrentamt_;
            $fhdgi=$row->C_festwagesincamt_;
            $otdgi=$row->C_festdagenincamt_;
            $cdn=$mv;
            $rsd=$row->C_rsdamt_;
            $npay=$row->C_netpayamount_;
            $slw=$zero;
            $inc=$row->C_incamt_;
            $adv=$row->C_exadvance_;
            $vard=$zero;
            $rnpay=$row->C_NET;
            $sldgi=$zero;
            $hra=$row->C_HRA_;
            $bkl=$zero;
            $lwd=$zero;
            $elw=$row->eldwagesincamt_;
            $exw=$zero;
            $wf=$zero;
            $advan=$zero;
            $df=$row->C_iftudedamt_;

//            $net=$str_repeat(' ', 12 - strlen($row->C_finalnetpay_)).$row->C_finalnetpay_;
            $net=$row->C_finalnetpay_;
            $eldgi=$row->C_elddagenincamt_;
            $gross=$row->C_gross1amt_;
            $esic=$row->C_ESI_;
            $cloth=$zero;
            $dpn=substr($row->department,0,25);
//            $dpn='watch and ward';
            $x=5;
//            $y=10;
//            $YY = $pdf->GetY();
               $pdf->SetFont('Arial', 'B', 7);

            $pdf->SetXY($x, $y);
            $pdf->Cell(30, 4, 'LIMELIGHT COMM PVT LTD');
            $x=$x+36;
            $pdf->SetXY($x, $y);
            $pdf->Cell(15, 4, 'DAYS BASIS:');
            $pdf->Cell(10, 4, $wkd,0,0,'R');
            $x=$x+25;
            $pdf->SetXY($x, $y);
            $pdf->Cell(18, 4, 'PAY SLIP FOR :');
            $pdf->Cell(16, 4, $mnth,0,0,'L');
            $x=$x+30    ;
            $pdf->SetXY($x, $y);
            $pdf->Cell(9, 4, 'DEPT :');
            $pdf->Cell(22, 4, $dpn, 0, 0, 'L');
            $x=$x+34;
            $pdf->SetXY($x, $y);
            $pdf->Cell(10, 4, 'DAYS :');
            $pdf->Cell(10, 4, $whrs, 0, 0, 'R');
            $x=$x+30;   
            $pdf->SetXY($x, $y);
            $pdf->Cell(30, 4, 'LIMELIGHT COMM PVT LTD');

            $y =$y+4;
            $x=5;
            $pdf->SetXY($x, $y);
            $pdf->Cell(8, 4, 'NAME : ');
            $pdf->Cell(30, 4, $nm);
            $x=$x+50;
            $pdf->SetXY($x, $y);
            $pdf->Cell(10, 4, 'DESG : ');
            $pdf->Cell(20, 4, $dg);
            $x=$x+30;
            $pdf->SetXY($x, $y);
            $pdf->Cell(12, 4, 'TKT NO: ');
            $pdf->Cell(10, 4, $row->eb_no,0,0,'R');
            $x=$x+25;
            $pdf->SetXY($x, $y);
            $pdf->Cell(8, 4, 'CODE : ');
//            $pdf->Cell(10, 4, $row->CODE,0,0,'R');
            $x=$x+20;   
            $pdf->SetXY($x, $y);
            $pdf->Cell(10, 4, 'ESID  : ');
            $pdf->Cell(10, 4, $row->C_esi_days_,0,0,'R');
            $x=$x+30;
            $pdf->SetXY($x, $y);
            $pdf->Cell(18, 4, 'PAY SLIP FOR :');
            $pdf->Cell(16, 4, $mnth,0,0,'L');
            $y =$y+4;
            $x=5;   
            $pdf->SetFont('Arial', '', 7);

            $pdf->SetXY($x, $y);
            $pdf->Cell(8, 4, 'FHR : '); 
            $pdf->Cell(10, 4, $fhr,0,0,'R');
            $x=$x+20;
            $pdf->SetXY($x, $y);
            $pdf->Cell(8, 4, 'HOURS: ');
            $pdf->Cell(14, 4, $wkrs,0,0,'R');
            $x=$x+30;
            $pdf->SetXY($x, $y);
            $pdf->Cell(8, 4, 'AHRS: ');
            $pdf->Cell(10, 4, '0.00',0,0,'R');
            $x=$x+30;
            $pdf->SetXY($x, $y);
            $pdf->Cell(8, 4, 'EXHRS: ');
            $pdf->Cell(10, 4, '0.00',0,0,'R');
            $x=$x+25;
            $pdf->SetXY($x, $y);
            $pdf->Cell(6, 4, 'SL: ');
            $pdf->Cell(10, 4, '0.00',0,0,'R');
            $x=$x+20;
            $pdf->SetXY($x, $y);
            $pdf->Cell(10, 4, 'EL: ');
            $pdf->Cell(10, 4, $eldays,0,0,'R');
            $x=$x+30;
            $pdf->SetXY($x, $y);
            $pdf->Cell(12, 4, 'TKT NO: ');
            $pdf->Cell(10, 4, $row->eb_no,0,0,'R');
            $y =$y+4;
            $x=5;
            $pdf->SetXY($x, $y);
            $pdf->Cell(8, 4, 'WAGE  : ');
            $pdf->Cell(15, 4, $wage,0,0,'R');
            $x=$x+30;
            $pdf->SetXY($x, $y);
            $pdf->Cell(8, 4, 'TOTAL : ');
            $pdf->Cell(15, 4, $twage,0,0,'R');
            $x=$x+30;
            $pdf->SetXY($x, $y);
            $pdf->Cell(8, 4, 'PTAX  : ');
            $pdf->Cell(15, 4, $ptx,0,0,'R');
            $x=$x+30;
            $pdf->SetXY($x, $y);
            $pdf->Cell(8, 4, 'P.F.G : ');
            $pdf->Cell(15, 4, $pfg,0,0,'R');
            $x=$x+30;
            $pdf->SetXY($x, $y);
            $pdf->Cell(8, 4, 'GDED  : ');
            $pdf->Cell(17, 4, $gded,0,0,'R');
            $x=$x+35;
            $pdf->SetXY($x, $y);
            $pdf->Cell(8, 4, 'NAME : ');
            $pdf->Cell(30, 4, $nm);
            $y =$y+4;
            $x=5;
            $pdf->SetXY($x, $y);
            $pdf->Cell(8, 4, 'DA : ');
            $pdf->Cell(15, 4, $da,0,0,'R');
            $x=$x+30;   
            $pdf->SetXY($x, $y);
            $pdf->Cell(8, 4, 'EXW : ');
            $pdf->Cell(15, 4, $exw,0,0,'R');
            $x=$x+30;
            $pdf->SetXY($x, $y);
            $pdf->Cell(8, 4, 'UCS :');
            $pdf->Cell(15, 4, '0.00',0,0,'R');
            $x=$x+30;
            $pdf->SetXY($x, $y);
            $pdf->Cell(8, 4, 'P.F :');
            $pdf->Cell(15, 4, $pf,0,0,'R');
            $x=$x+30;
            $pdf->SetXY($x, $y);
            $pdf->Cell(8, 4, '-VARE : ');
            $pdf->Cell(17, 4, $varem,0,0,'R');
            $x=$x+35    ;
            $pdf->SetXY($x, $y);
            $pdf->Cell(9, 4, 'DEPT :');
            $pdf->Cell(22, 4, $dpn, 0, 0, 'L');
            $y =$y+4;
            $x=5;
            $pdf->SetXY($x, $y);
            $pdf->Cell(8, 4, 'GI : ');
            $pdf->Cell(15, 4, $gi,0,0,'R');
            $x=$x+30;
            $pdf->SetXY($x, $y);
            $pdf->Cell(8, 4, 'EXDGI : ');
            $pdf->Cell(15, 4, $edgi,0,0,'R');
            $x=$x+30;
            $pdf->SetXY($x, $y);
            $pdf->Cell(8, 4, 'RENT : ');
            $pdf->Cell(15, 4, $rnt,0,0,'R');
            $x=$x+30;
            $pdf->SetXY($x, $y);
            $pdf->Cell(8, 4, 'F.R.F : ');
            $pdf->Cell(15, 4, $gwf,0,0,'R');
            $x=$x+30;
            $pdf->SetXY($x, $y);
            $pdf->Cell(8, 4, '+VARE  : ');
            $pdf->Cell(17, 4, $varep,0,0,'R');
            $x=$x+35;
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->SetXY($x, $y);
            $pdf->Cell(10, 4, 'NET  : ');
            $pdf->Cell(17, 4, $net,0,0,'R');
            $y =$y+4;
            $x=5;
            $pdf->SetFont('Arial', '', 7);
            $pdf->SetXY($x, $y);
            $pdf->Cell(8, 4, 'FHW   : ');
            $pdf->Cell(15, 4, $fhw,0,0,'R');
            $x=$x+30;
            $pdf->SetXY($x, $y);
            $pdf->Cell(8, 4, 'OTW   : ');
            $pdf->Cell(15, 4, $otw,0,0,'R');
            $x=$x+30;
            $pdf->SetXY($x, $y);
            $pdf->Cell(8, 4, 'G.W.F : ');
            $pdf->Cell(15, 4, $gwf,0,0,'R');
            $x=$x+30;
            $pdf->SetXY($x, $y);
            $pdf->Cell(8, 4, 'S.ADV : ');
            $pdf->Cell(15, 4, $sad,0,0,'R');
            $x=$x+30;
            $pdf->SetXY($x, $y);
            $pdf->Cell(8, 4, 'LRENT : ');
            $pdf->Cell(17, 4, $lren,0,0,'R');
            $y =$y+4;
            $x=5;
            $pdf->SetXY($x, $y);
            $pdf->Cell(8, 4, 'FHDGI : ');
            $pdf->Cell(15, 4, $fhdgi,0,0,'R');
            $x=$x+30;
            $pdf->SetXY($x, $y);
            $pdf->Cell(8, 4, 'OTDGI : ');
            $pdf->Cell(15, 4, $otdgi,0,0,'R');
            $x=$x+30;
            $pdf->SetXY($x, $y);
            $pdf->Cell(8, 4, 'CDN   : ');
            $pdf->Cell(15, 4, $cdn,0,0,'R');
            $x=$x+30;
            $pdf->SetXY($x, $y);
            $pdf->Cell(8, 4, 'RSD   : ');
            $pdf->Cell(15, 4, $rsd,0,0,'R');
            $x=$x+30;
            $pdf->SetXY($x, $y);
            $pdf->Cell(8, 4, 'NETPAY: ');
            $pdf->Cell(17, 4, $npay,0,0,'R');
            $y=$y+4;
            $x=5;
            $pdf->SetXY($x, $y);
            $pdf->Cell(8, 4, 'SLW   : ');
            $pdf->Cell(15, 4, $slw,0,0,'R');
            $x=$x+30;
            $pdf->SetXY($x, $y);
            $pdf->Cell(8, 4, 'INC   : ');
            $pdf->Cell(15, 4, $inc,0,0,'R');
            $x=$x+30;
            $pdf->SetXY($x, $y);
            $pdf->Cell(8, 4, 'ADV   : ');
            $pdf->Cell(15, 4, $adv,0,0,'R');
            $x=$x+30;
            $pdf->SetXY($x, $y);
            $pdf->Cell(8, 4, 'VARD  : ');
            $pdf->Cell(15, 4, $vard,0,0,'R');
            $x=$x+30;
            $pdf->SetXY($x, $y);
            $pdf->Cell(8, 4, 'RNPAY : ');
            $pdf->Cell(17, 4, $rnpay,0,0,'R');
            $y =$y+4;
            $x=5;
//                        $pdf->Cell(50, 10, 'SLDGI :'.$sldgi.str_repeat(' ', 7).'HRA   :'.$hra.str_repeat(' ', 7).'BKLN  :'.$bkl.str_repeat(' ', 7), 0, 1);

            $pdf->SetXY($x, $y);
            $pdf->Cell(8, 4, 'SLDGI : ');
            $pdf->Cell(15, 4, $eldgi,0,0,'R');
            $x=$x+30;
            $pdf->SetXY($x, $y);
            $pdf->Cell(8, 4, 'HRA    : ');
            $pdf->Cell(15, 4, $hra,0,0,'R');
            $x=$x+30;
            $pdf->SetXY($x, $y);
            $pdf->Cell(8, 4, 'BKL    : ');
            $pdf->Cell(15, 4, $bkl,0,0,'R');
            $x=$x+30;
            $pdf->SetXY($x, $y);
            $pdf->Cell(8, 4, ' : ');
            $pdf->Cell(15, 4, '',0,0,'R');
            $x=$x+30;
            $pdf->SetXY($x, $y);
            $pdf->Cell(8, 4, 'L/W  : ');
            $pdf->Cell(17, 4, $df,0,0,'R');
            $x=$x+30;
            $y =$y+4;
            $x=5;
            $pdf->SetXY($x, $y);
//            $pdf->Cell(50, 10, 'ELW   :'.$elw.str_repeat(' ', 7).'EX ALW:'.$exw.str_repeat(' ', 7).'W/F   :'.$wf.str_repeat(' ', 7).'ADVAN:'.$advan.str_repeat(' ', 7).'N ET  :'.$net, 0, 1);
            $pdf->Cell(8, 4, 'ELW   : ');
            $pdf->Cell(15, 4, $elw,0,0,'R');
            $x=$x+30;
            $pdf->SetXY($x, $y);
            $pdf->Cell(8, 4, 'EX ALW: ');
            $pdf->Cell(15, 4, $exw,0,0,'R');
            $x=$x+30;
            $pdf->SetXY($x, $y);
            $pdf->Cell(8, 4, 'W/F   : ');
            $pdf->Cell(15, 4, $wf,0,0,'R');
            $x=$x+30;
            $pdf->SetXY($x, $y);
            $pdf->Cell(8, 4, 'ADVAN: ');
            $pdf->Cell(15, 4, $advan,0,0,'R');
            $x=$x+30;
            $pdf->SetXY($x, $y);
            $pdf->Cell(8, 4, 'N ET  : ');
            $pdf->Cell(17, 4, $net,0,0,'R');
            $y =$y+4;
            $x=5;
            $pdf->SetXY($x, $y);

//            $pdf->Cell(50, 10, 'ELDGI :'.$eldgi.str_repeat(' ', 7).'GROSS :'.$gross.str_repeat(' ', 7).'ESIC  :'.$esic.str_repeat(' ', 7).'CLOTH :'.$cloth, 0, 1);

            $pdf->Cell(8, 4, 'ELDGI : ');
            $pdf->Cell(15, 4, $eldgi,0,0,'R');
            $x=$x+30;
            $pdf->SetXY($x, $y);
            $pdf->Cell(8, 4, 'GROSS : ');
            $pdf->Cell(15, 4, $gross,0,0,'R');
            $x=$x+30;
            $pdf->SetXY($x, $y);
            $pdf->Cell(8, 4, 'ESIC  : ');
            $pdf->Cell(15, 4, $esic,0,0,'R');
            $x=$x+30;
            $pdf->SetXY($x, $y);
            $pdf->Cell(8, 4, 'CLOTH : ');
            $pdf->Cell(15, 4, $cloth,0,0,'R');
            //$pgn=$pgn+1;

            $y =$y+6;
            $x=5;
            $pdf->SetXY($x, $y);
            $pdf->Line(4, $y, 200, $y);   // from (10,y) to (200,y)
            $y =$y+4;
            $x=5;
            $totslp++;
            $totamt=$totamt+$row->C_NET;
            $tvard=$tvard+$row->C_iftudedamt_;


//$pdf->Cell(width, height, text, border, ln, align)0,0;



            $pgn++;

        }
        $y =$y+4;
        $x=5;
//        $pdf->SetXY($x, $y);
  //      $pdf->Line(4, $y, 200, $y);   // from (10,y) to (200,y)
  //      $y =$y+4;
        $x=5;
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetXY($x, $y);
        $pdf->Cell(40, 4, 'TOTAL NO. OF PAYSLIPS : '.$totslp,0,0,'L');
        $x=$x+80;
        $pdf->SetXY($x, $y);
        $pdf->Cell(40, 4, 'TOTAL NET AMOUNT : '.number_format($totamt,2),0,0,'L');
        $x=$x+70;
        $pdf->SetXY($x, $y);
        $pdf->Cell(40, 4, 'L/W : '.number_format($tvard,2),0,0,'L');
        $y =$y+4;
        $x=5;

        $pdf->AddPage('P', 'A4');

        $y=10;
        $x=4;
        $pgn=1;
        $tothrs=0;
        $toamt=0;
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->SetXY($x, $y);
        $pdf->Cell(200, 10, 'LIMELIGHT COMM PVT LTD  EXTRA PAY REGISTER FOR : '.date('F Y', strtotime($periodtodate)), 0, 1, 'C');
        $y=$y+8;
        $x=4;
        $pdf->SetXY($x, $y);
        $pdf->Line(4, $y, 200, $y);   // from (10,y) to (200,y)
        $y=$y+5;
        $x=4;
        $pdf->Cell(200, 10, '    EB NO       NAME                             DEPARTMENT       Extra Hours      Extra amount ', 0, 1, 'L');
        $y=$y+5;
        $x=4;
        $pdf->SetXY($x, $y);
        $pdf->Line(4, $y, 200, $y);   // from (10,y) to (200,y)
        $y=$y+5;    
        $x=4;
        foreach ($mccodes as $row) {
            if ($pgn>5) {
//                        $pdf->AddPage('P', 'A4');
//                $pgn=1;
//                $y=6;
            }
               $x=10;
               $pdf->SetFont('Arial', '', 8);
                $pdf->SetXY($x, $y);
            $wkd=$row->c_ot_hrs_;
            $dpn=substr($row->department,0,20);
            $nm=substr($row->emp_name,0,20);
            $C_OTNETAMOUNT_=$row->C_OTNETAMOUNT_;
            $tothrs=$tothrs+$wkd;
            $toamt=$toamt+$C_OTNETAMOUNT_;
            $pdf->Cell(20, 4, $row->eb_no,0,0,'L');
            $pdf->Cell(50, 4, $nm,0,0,'L');
            $pdf->Cell(50, 4, $dpn,0,0,'L');
            $pdf->Cell(30, 4, $wkd,0,0,'    R');
            $pdf->Cell(30, 4, $C_OTNETAMOUNT_,0,0,'    R');
            $y =$y+4;       
            $pdf->Line(4, $y, 200, $y);   // from (10,y) to (200,y)
            $y=$y+5;    
            $x=4;
            $pgn++;
        }
//        $y =$y+4;
        $x=10;
        $nm='Grand Total';
        $pdf->SetFont('Arial', 'B', 12);        
        $pdf->SetXY($x, $y);
        $pdf->Cell(20, 4, '',0,0,'L');
        $pdf->Cell(50, 4, $nm,0,0,'L');
        $pdf->Cell(50, 4, '',0,0,'L');
        $pdf->Cell(30, 4, $tothrs,0,0,'    R');
        $pdf->Cell(30, 4, $toamt,0,0,'    R');
            $y =$y+4;       
            $pdf->Line(4, $y, 200, $y);   // from (10,y) to (200,y)
        $pdf->Ln();
       $this->fpdf_lib->Output('D', 'cepayslip_'.$periodfromdate.'_'.$periodtodate.'.pdf'); // 'D' = force download
 

 }
    
 

 
 
 public function njmwagespayslip163($periodfromdate,$periodtodate,$att_payschm   ) {

 
        $mccodes = $this->Njmallwagesprocess->getcntwagespayslip($periodfromdate,$periodtodate,$att_payschm);


 $this->load->library('fpdf_lib');
//	$pdf = new \FPDF();
    $pdf = $this->fpdf_lib; 
$y=15;
        $pdf->AddPage();

        $y=$y+10;
        $kn=1;
        $dpc='';
        $pgslp =1;
        $dpn='';
        foreach ($mccodes as $row) {

        if ($dpc<>$row->dept_code_1)
        {
            if ($dpc<>'') 
            {
        //        if ($pgslp>0) {
                $pdf->AddPage();
          //      }
                $y=15;

            }
            $dpc=$row->dept_code_1;
            $dpn=$row->dept_desc_1;
            $pdf->SetFont('Arial', 'B', 12);
            $pdf->SetXY(10, $y);
            $pdf->Cell(190, 10, 'NJM CO LTD/NELLIMARLA   PAY SLIP FOR : '.date('F Y', strtotime($periodtodate)).'    DEPT : '.$dpn, 0, 1, 'C');
            $y=$y+20;
            $kn=1;
            $pgslp=1;
        }       

        
        $pgslp=1;
        $tkt1=$row->ticket_no_1; 
        $tkt2=$row->ticket_no_2;
        $empname1=$row->emp_name_1; 
        $empname2=$row->emp_name_2;
        $pday1=$row->regday_1+$row->otday_1;    
        $pday2=$row->regday_2+$row->otday_2;    
        $month = $row->month_1;

//        $pdf->SetFont('Arial', '', 12); // or any font and size


        $x = 10;
        $YY = $pdf->GetY();
    
    $pdf->SetFont('Arial', '', 8);
    $pdf->SetXY($x, $YY);
    $pdf->Cell(90, 4, $row->emp_name_1, "", 0, 'C');
    $x=105;
    $pdf->SetXY($x, $YY);
    $pdf->Cell(90, 4, $row->emp_name_2, "", 0, 'C');
    $x=10;        
    $pdf->Ln();
    $y = $pdf->GetY();
    $pdf->SetXY($x, $y);
    $pdf->SetFont('Arial', 'B', 7);
    $pdf->Cell(39, 4, 'TKT NO', "LTB", 0, 'L');
    $pdf->Cell(16, 4, $row->ticket_no_1, "LTBR", 0, 'L');
    $pdf->Cell(19, 4, 'Month', "LTB", 0, 'L');
    $pdf->Cell(16, 4, $row->month_1, "LTBR", 0, 'L');
    $x=105;        
    $pdf->SetXY($x, $y);
    $pdf->SetFont('Arial', 'B', 7);
    $pdf->Cell(39, 4, 'TKT NO', "LTB", 0, 'L');
    $pdf->Cell(16, 4, $row->ticket_no_2, "LTBR", 0, 'L');
    $pdf->Cell(19, 4, 'Month', "LTB", 0, 'L');
    $pdf->Cell(16, 4, $row->month_2, "LTBR", 0, 'L');
    $x=10;        
    $pdf->Ln();

    $y = $pdf->GetY();
    $pdf->SetXY($x, $y);
    $pdf->SetFont('Arial', '', 7);
    $pdf->Cell(39, 4, 'PDays', "LB", 0, 'L');
    $pdf->Cell(16, 4, '', "LB", 0, 'R');
    $pdf->Cell(19, 4, 'TDays', "LB", 0, 'L');
    $pdf->Cell(16, 4, $pday1, "LBR", 0, 'R');
//    $pdf->Cell(16, 4, '', "LBR", 0, 'R');

    $x = 105;
    $pdf->SetXY($x, $y);
    $pdf->SetFont('Arial', '', 7);
    $pdf->Cell(39, 4, 'PDays', "LB", 0, 'L');
    $pdf->Cell(16, 4, '', "LB", 0, 'R');
    $pdf->Cell(19, 4, 'TDays', "LB", 0, 'L');
    $pdf->Cell(16, 4, $pday2, "LBR", 0, 'R');
  //  $pdf->Cell(16, 4, '', "LBR", 0, 'R');

    $pdf->Ln();

    $x=10;        
    $y = $pdf->GetY();
    $y = $pdf->GetY();
    $pdf->SetXY($x, $y);
    $pdf->Cell(39, 4, 'Total Days:', "LB", 0, 'L');
    $pdf->Cell(16, 4, $pday1, "LB", 0, 'R');
    $pdf->Cell(19, 4, 'TRate: ', "LB", 0, 'L');
    $pdf->Cell(16, 4, $row->trate_1, "LBR", 0, 'R');
//    $pdf->Cell(19, 4, 'Amount:', "LB", 0, 'L');
//    $pdf->Cell(16, 4, $row->AMOUNT, "LBR", 0, 'R');
    $x = 105;
    $pdf->SetXY($x, $y);
    $pdf->Cell(39, 4, 'Total Days:', "LB", 0, 'L');
    $pdf->Cell(16, 4, $pday2, "LB", 0, 'R');
    $pdf->Cell(19, 4, 'TRate: ', "LB", 0, 'L');
    $pdf->Cell(16, 4, $row->trate_2, "LBR", 0, 'R');
            $x= 10;
    $pdf->Ln();
    $y = $pdf->GetY();
    $pdf->SetXY($x, $y);
    $row->ta_1 = ($row->ta_1 > 0) ? $row->ta_1 : '';
    $pdf->Cell(39, 4, 'Advance', "LB", 0, 'L');
    $pdf->Cell(16, 4, $row->advance_1, "LB", 0, 'R');
    $pdf->Cell(19, 4, 'TA', "LB", 0, 'L');
    $pdf->Cell(16, 4, $row->ta_1, "LBR", 0, 'R');

    $x = 105;
    $pdf->SetXY($x, $y);
    $row->ta_2 = ($row->ta_2 > 0) ? $row->ta_2 : '';
    $pdf->Cell(39, 4, 'Advance', "LB", 0, 'L');
    $pdf->Cell(16, 4, $row->advance_2, "LB", 0, 'R');
    $pdf->Cell(19, 4, 'TA', "LB", 0, 'L');
    $pdf->Cell(16, 4, $row->ta_2, "LBR", 0, 'R');
$x = 10;
    $pdf->Ln();
    $y = $pdf->GetY();
    $pdf->SetXY($x, $y);
    $pdf->Cell(39, 4, 'Plus Balance', "LB", 0, 'L');
    $pdf->Cell(16, 4, $row->plus_balance_1, "LB", 0, 'R');
    $pdf->Cell(19, 4, '', "LB", 0, 'L');
    $pdf->Cell(16, 4, '', "LBR", 0, 'R');
$x = 105;
    $pdf->SetXY($x, $y);
    $pdf->Cell(39, 4, 'Plus Balance', "LB", 0, 'L');
    $pdf->Cell(16, 4, $row->plus_balance_2, "LB", 0, 'R');
    $pdf->Cell(19, 4, '', "LB", 0, 'L');
    $pdf->Cell(16, 4, '', "LBR", 0, 'R');
    $pdf->Ln();
    $y = $pdf->GetY();
$x = 10;
    $pdf->SetXY($x, $y);
    $pdf->Cell(39, 4, 'ESI', "LB", 0, 'L');
    $pdf->Cell(16, 4, $row->esi_1, "LB", 0, 'R');
    $pdf->Cell(19, 4, 'PF', "LB", 0, 'L');
    $pdf->Cell(16, 4, $row->pf_1, "LBR", 0, 'R');
$x = 105;
    $pdf->SetXY($x, $y);
    $pdf->Cell(39, 4, 'ESI', "LB", 0, 'L');
    $pdf->Cell(16, 4, $row->esi_2, "LB", 0, 'R');
    $pdf->Cell(19, 4, 'PF', "LB", 0, 'L');
    $pdf->Cell(16, 4, $row->pf_2, "LBR", 0, 'R');
$x= 10;
    $pdf->Ln();
    $pdf->SetFont('Arial', 'B', 7);
    $y = $pdf->GetY();
    $pdf->SetXY($x, $y);
    $pdf->Cell(10, 4, 'S.no', "LB", 0, 'C');
    $pdf->Cell(29, 4, 'Quality', "LB", 0, 'C');
    //$pdf->Cell(15, 4, 'Day', "LB", 0, 'C');
    $pdf->Cell(16, 4, 'Qty', "LB", 0, 'C');
    $pdf->Cell(19, 4, 'PRate', "LB", 0, 'C');
    $pdf->Cell(16, 4, 'Amount', "LBR", 0, 'C');
$x = 105;
    $pdf->SetXY($x, $y);
    $pdf->Cell(10, 4, 'S.no', "LB", 0, 'C');
    $pdf->Cell(29, 4, 'Quality', "LB", 0, 'C');
    //$pdf->Cell(15, 4, 'Day', "LB", 0, 'C');
    $pdf->Cell(16, 4, 'Qty', "LB", 0, 'C');
    $pdf->Cell(19, 4, 'PRate', "LB", 0, 'C');
    $pdf->Cell(16, 4, 'Amount', "LBR", 0, 'C');

    $x= 10;        
    $pdf->Ln();


// production details

/*     $tkt = $row->ticket_no_1;        
    $prdcodes = $this->Njmallwagesprocess->getcntprddpayslip($periodfromdate,$periodtodate,$att_payschm,$tkt);

    $s = 1;
     $pdf->SetFont('Arial', '', 7);
     	foreach ($prdcodes as $prdrow) {
        $qulity_id = $rowd1->PROD_ID;
        $PRODUCTION = $rowd1->PRODUCTION;
        $RATES1 = $rowd1->RATES;
        $AMOUNT1 = $rowd1->AMOUNT;
        $AMOUNT1 = round($AMOUNT1);
        $y = $pdf->GetY();
        $pdf->SetXY($x, $y);
        $pdf->Cell(10, 4, $s, "L", 0, 'L');
        $pdf->Cell(29, 4, $prdrow->quality, "L", 0, 'C');
       // $pdf->Cell(15, 4, '', "L", 0, 'L');
        $pdf->Cell(16, 4, $prdrow->qty, "L", 0, 'C');
        $pdf->Cell(19, 4, $prdrow->prate, "L", 0, 'R');
        $pdf->Cell(16, 4, $prdrow->amount, "LR", 0, 'R');
        $s++;
        $pdf->Ln();
    }


    $tkt = $row->ticket_no_2;        
    $prdcodes = $this->Njmallwagesprocess->getcntprddpayslip($periodfromdate,$periodtodate,$att_payschm,$tkt);
    $x= 105;
    $s = 1;
     $pdf->SetFont('Arial', '', 7);
     	foreach ($prdcodes as $prdrow) {
        $qulity_id = $rowd1->PROD_ID;
        $PRODUCTION = $rowd1->PRODUCTION;
        $RATES1 = $rowd1->RATES;
        $AMOUNT1 = $rowd1->AMOUNT;
        $AMOUNT1 = round($AMOUNT1);
        $y = $pdf->GetY();
        $pdf->SetXY($x, $y);
        $pdf->Cell(10, 4, $s, "L", 0, 'L');
        $pdf->Cell(29, 4, $prdrow->quality, "L", 0, 'C');
       // $pdf->Cell(15, 4, '', "L", 0, 'L');
        $pdf->Cell(16, 4, $prdrow->qty, "L", 0, 'C');
        $pdf->Cell(19, 4, $prdrow->prate, "L", 0, 'R');
        $pdf->Cell(16, 4, $prdrow->amount, "LR", 0, 'R');
        $s++;
         $pdf->Ln();
    }
 */

    //production details end


$tkt1 = $row->ticket_no_1;        
$tkt2 = $row->ticket_no_2;        

$prdcodes1 = $this->Njmallwagesprocess->getcntprddpayslip($periodfromdate,$periodtodate,$att_payschm,$tkt1);
$prdcodes2 = $this->Njmallwagesprocess->getcntprddpayslip($periodfromdate,$periodtodate,$att_payschm,$tkt2);

// Convert to arrays (to allow indexing easily)
$prdcodes1 = array_values($prdcodes1);
$prdcodes2 = array_values($prdcodes2);

$maxRows = max(count($prdcodes1), count($prdcodes2));

$pdf->SetFont('Arial', '', 7);
for ($i = 0; $i < $maxRows; $i++) {
    $y = $pdf->GetY();

    // First Ticket
    $x = 10; // starting x position for left table
    $pdf->SetXY($x, $y);

    if (isset($prdcodes1[$i])) {
        $prdrow = $prdcodes1[$i];
        $pdf->Cell(10, 4, $i+1, "L", 0, 'L');
        $pdf->Cell(29, 4, $prdrow->quality, "L", 0, 'C');
        $pdf->Cell(16, 4, $prdrow->qty, "L", 0, 'C');
        $pdf->Cell(19, 4, $prdrow->prate, "L", 0, 'R');
        $pdf->Cell(16, 4, $prdrow->amount, "LR", 0, 'R');
    } else {
        // print empty cells if no data in ticket1 for this row
        $pdf->Cell(10, 4, '', "L", 0, 'L');
        $pdf->Cell(29, 4, '', "L", 0, 'C');
        $pdf->Cell(16, 4, '', "L", 0, 'C');
        $pdf->Cell(19, 4, '', "L", 0, 'R');
        $pdf->Cell(16, 4, '', "LR", 0, 'R');
    }

    // Second Ticket
    $x = 105; // starting x position for right table
    $pdf->SetXY($x, $y);

    if (isset($prdcodes2[$i])) {
        $prdrow = $prdcodes2[$i];
        $pdf->Cell(10, 4, $i+1, "L", 0, 'L');
        $pdf->Cell(29, 4, $prdrow->quality, "L", 0, 'C');
        $pdf->Cell(16, 4, $prdrow->qty, "L", 0, 'C');
        $pdf->Cell(19, 4, $prdrow->prate, "L", 0, 'R');
        $pdf->Cell(16, 4, $prdrow->amount, "LR", 0, 'R');
    } else {
        // print empty cells if no data in ticket2 for this row
        $pdf->Cell(10, 4, '', "L", 0, 'L');
        $pdf->Cell(29, 4, '', "L", 0, 'C');
        $pdf->Cell(16, 4, '', "L", 0, 'C');
        $pdf->Cell(19, 4, '', "L", 0, 'R');
        $pdf->Cell(16, 4, '', "LR", 0, 'R');
    }

    $pdf->Ln();
}


$x= 10;
    $y = $pdf->GetY();
        $pdf->SetXY($x, $y);
        $pdf->Cell(10, 4, '', "L", 0, 'L');
        $pdf->Cell(29, 4,'', "L", 0, 'C');
       // $pdf->Cell(15, 4, '', "L", 0, 'L');
        $pdf->Cell(16, 4, '', "L", 0, 'C');
        $pdf->Cell(19, 4, '', "L", 0, 'R');
        $pdf->Cell(16, 4, '', "LR", 0, 'R');
$x= 105;
        $pdf->SetXY($x, $y);
        $pdf->Cell(10, 4, '', "L", 0, 'L');
        $pdf->Cell(29, 4,'', "L", 0, 'C');
       // $pdf->Cell(15, 4, '', "L", 0, 'L');
        $pdf->Cell(16, 4, '', "L", 0, 'C');
        $pdf->Cell(19, 4, '', "L", 0, 'R');
        $pdf->Cell(16, 4, '', "LR", 0, 'R');

    $x= 10;
    $pdf->Ln();
    $pdf->SetFont('Arial', '', 7);
    $y = $pdf->GetY();
    $pdf->SetXY($x, $y);
    $pdf->SetFillColor(211, 211, 211);
    $pdf->Cell(39, 4, 'NET', "LTB", 0, 'L', true);
    $pdf->Cell(16, 4, $row->net_1, "LTB", 0, 'R', true);
    $pdf->Cell(19, 4, '', "LTB", 0, 'L');
    $pdf->Cell(16, 4, '', "LTBR", 0, 'R');
    $x = 105;
    $pdf->SetXY($x, $y);
    $pdf->SetFillColor(211, 211, 211);
    $pdf->Cell(39, 4, 'NET', "LTB", 0, 'L', true);
    $pdf->Cell(16, 4, $row->net_2, "LTB", 0, 'R', true);
    $pdf->Cell(19, 4, '', "LTB", 0, 'L');
    $pdf->Cell(16, 4, '', "LTBR", 0, 'R');

    $pdf->Ln();
    $pdf->Ln();
    $pdf->Ln(2);


    $ly = $pdf->GetY();

    $pgslp++;

    if ($kn % 5 == 0)
        $pdf->AddPage();
        $kn++;
        $pgslp=0;
    }



 
        // Force download
        $fl='NJM_payslip_'.$month.'.pdf'    ;
        $this->fpdf_lib->Output('D', $fl); // 'D' = force download
    }



public function cntexlupload() {

	$this->load->library('session');
	$this->load->helper(array('form', 'url'));
	$this->load->library('upload');
	$periodfromdate = $this->input->post('periodfromdate');
	$periodtodate = $this->input->post('periodtodate');
	$fileupload =  $this->input->post('fileupload');
 	$comp = $this->session->userdata('companyId');
	  
	 $config['upload_path'] = './uploads/';
	 $config['allowed_types'] = 'csv|xlsx';
	 $config['max_size'] = 2048;

	 $this->upload->initialize($config);

	 $file_mimes = array('text/x-comma-separated-values', 'text/comma-separated-values', 'application/octet-stream', 
	 'application/vnd.ms-excel', 'application/x-csv', 'text/x-csv', 'text/csv', 
	 'application/csv', 'application/excel', 'application/vnd.msexcel', 'text/plain', 
	 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');

//echo 'ahahah'.$fileupload;
     if (!$this->upload->do_upload('fileupload')) {
		$error = array('error' => $this->upload->display_errors());
//		echo $error['error'];
	} else {
		$data = $this->upload->data();

		// Ensure PhpSpreadsheet is included

		$file_mimes = array(
			'text/x-comma-separated-values', 'text/comma-separated-values', 'application/octet-stream',
			'application/vnd.ms-excel', 'application/x-csv', 'text/x-csv', 'text/csv',
			'application/csv', 'application/excel', 'application/vnd.msexcel', 'text/plain',
			'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
		);

		// Get the file extension
		$arr_file = explode('.', $data['file_name']);
		$extension = end($arr_file);

		// Determine the appropriate reader based on the file extension
		if ('csv' == $extension) {
			$reader = new \PhpOffice\PhpSpreadsheet\Reader\Csv();
		} else {
			$reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
		}

	//	$spreadsheet = $reader->load($_FILES['file']['tmp_name']);
		$spreadsheet = $reader->load($data['full_path']);

		//$lastRow = $spreadsheet->getHighestDataRow();
		//echo $lastRow;
		$worksheet = $spreadsheet->getActiveSheet();
		// Get the highest row and column numbers referenced in the worksheet
		$highestRow = $worksheet->getHighestRow(); // e.g. 10
		$highestColumn = $worksheet->getHighestColumn(); // e.g 'F'//$highestColumnIndex = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($highestColumn); // e.g. 5
//		echo 'hrow='.$highestRow.' hcol'.$highestColumn ;
		
//start_date	end_date	eb_no	wrk_hours_reg	wrk_hours_ot	wrk_hours_adj	rates	prod_id	
// production	canteen	advance	plus_amount	minus_amount	travel_allowance	updt_from

        $sql="update EMPMILL12.tbl_njm_cnt_wages_data_collection set is_active=0 where start_date='$periodfromdate' and end_date='$periodtodate' and updt_from='M'";
        $query = $this->db->query($sql);            

		$sheetData = $spreadsheet->getActiveSheet()->toArray();
		$allupdt=0;
		$allfn='Y';
        $ebmissing="";
		if (!empty($sheetData)) {
            for ($i=1; $i<count($sheetData); $i++) { //skipping first row
                $stdt = $periodfromdate;
				$endt=	$periodtodate;
				$ebno=	$sheetData[$i][2];
				//$wrkahrs=$sheetData[$i][3];
                $wrkahrs = !empty($sheetData[$i][3]) ? $sheetData[$i][3] : 0;
//				$wrkrt=$sheetData[$i][4];
				$wrkrt = !empty($sheetData[$i][4]) ? $sheetData[$i][4] : 0;
                $prdid=$sheetData[$i][5];
//				$prod=$sheetData[$i][6];
				$prod = !empty($sheetData[$i][6]) ? $sheetData[$i][6] : 0;
//				$canteen=$sheetData[$i][7];
				$canteen = !empty($sheetData[$i][7]) ? $sheetData[$i][7] : 0;
//				$adv=$sheetData[$i][8];
				$adv = !empty($sheetData[$i][8]) ? $sheetData[$i][8] : 0;
//			    $plusamt=$sheetData[$i][9];
				$plusamt = !empty($sheetData[$i][9]) ? $sheetData[$i][9] : 0;
//			    $minusamt=$sheetData[$i][10];
    			$minusamt = !empty($sheetData[$i][10]) ? $sheetData[$i][10] : 0;
//                $taamt=$sheetData[$i][11];
				$taamt = !empty($sheetData[$i][11]) ? $sheetData[$i][11] : 0;
                $updt=$sheetData[$i][12];
                $prdamt=0;
                if ($prod>0) {
                    $prdamt=$prod * $wrkrt;
            }    

                if (empty($stdt) || empty($endt) || empty($ebno)) {
//                    echo "Skipping row $i due to missing data.<br>";
                    continue;
                }

                // Validate date format
                if (!DateTime::createFromFormat('Y-m-d', $stdt) || !DateTime::createFromFormat('Y-m-d', $endt)) {
  //                  echo "Invalid date format in row $i. Expected format: Y-m-d.<br>";
                    continue;
                }


            $ebid=0;                
                $wsql="select theod.eb_id from vowsls.tbl_hrms_ed_official_details theod
                left join vowsls.tbl_hrms_ed_personal_details thepd on theod.eb_id=thepd.eb_id
                where emp_code='$ebno' and thepd.company_id=1 and theod.is_active=1 and theod.catagory_id=14
				and thepd.is_active =1";

//                echo $wsql;
                $query= $this->db->query($wsql);
                if ($query->num_rows() > 0) {
                    $row = $query->row();
                    $ebid = $row->eb_id;
                } else {
  //                  echo "Employee not found for code $ebno";
                    $ebmissing .= "Employee not found for code $ebno";
                    continue;
                }

//            echo "Processing row $i: $ebno,$ebid, $stdt, $endt, $ebno, $wrkahrs, $wrkrt, $prdid, $prod, 
//            $canteen, $adv, $plusamt, $minusamt, $taamt, $updt<br>";

                if ($ebid > 0) {
                    $sql = "insert into EMPMILL12.tbl_njm_cnt_wages_data_collection (
                        start_date, end_date, eb_id, wrk_hours_reg, wrk_hours_ot,
        wrk_hours_adj, rates, prod_id, production, amount_reg, amount_ot,
        amount_adj, canteen, advance, plus_amount, minus_amount, travel_allowance,
        updt_from, reg_amount, prod_amount,esi_days
) values 
					('$stdt','$endt',$ebid,0,0,
                    $wrkahrs,$wrkrt,$prdid,'$prod',0,0,
                    0,$canteen,$adv,$plusamt,$minusamt,$taamt,
                    '$updt',0,$prdamt,0)";
//                    echo $sql;
                    $query = $this->db->query($sql);
					$allupdt++;
    //                echo "Row $allupdt processed successfully.<br>";
                }	
			}		
		}		
			
          $response = array(
				'success' => true,
				'savedata'=> $allupdt,
                'ebmissing' => $ebmissing,
			);
			
				echo json_encode($response);
 


 	}





}


public function wrklinehoursupload() {

	$this->load->library('session');
	$this->load->helper(array('form', 'url'));
	$this->load->library('upload');
	$periodfromdate = $this->input->post('periodfromdate');
	$periodtodate = $this->input->post('periodtodate');
	$fileupload =  $this->input->post('fileupload');
 	$comp = $this->session->userdata('companyId');
	  
	 $config['upload_path'] = './uploads/';
	 $config['allowed_types'] = 'csv|xlsx';
	 $config['max_size'] = 2048;

	 $this->upload->initialize($config);

	 $file_mimes = array('text/x-comma-separated-values', 'text/comma-separated-values', 'application/octet-stream', 
	 'application/vnd.ms-excel', 'application/x-csv', 'text/x-csv', 'text/csv', 
	 'application/csv', 'application/excel', 'application/vnd.msexcel', 'text/plain', 
	 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');

//echo 'ahahah'.$fileupload;
     if (!$this->upload->do_upload('fileupload')) {
		$error = array('error' => $this->upload->display_errors());
		echo $error['error'];
	} else {
		$data = $this->upload->data();

		// Ensure PhpSpreadsheet is included

		$file_mimes = array(
			'text/x-comma-separated-values', 'text/comma-separated-values', 'application/octet-stream',
			'application/vnd.ms-excel', 'application/x-csv', 'text/x-csv', 'text/csv',
			'application/csv', 'application/excel', 'application/vnd.msexcel', 'text/plain',
			'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
		);

		// Get the file extension
		$arr_file = explode('.', $data['file_name']);
		$extension = end($arr_file);

		// Determine the appropriate reader based on the file extension
		if ('csv' == $extension) {
			$reader = new \PhpOffice\PhpSpreadsheet\Reader\Csv();
		} else {
			$reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
		}

	//	$spreadsheet = $reader->load($_FILES['file']['tmp_name']);
		$spreadsheet = $reader->load($data['full_path']);

		//$lastRow = $spreadsheet->getHighestDataRow();
		//echo $lastRow;
		$worksheet = $spreadsheet->getActiveSheet();
		// Get the highest row and column numbers referenced in the worksheet
		$highestRow = $worksheet->getHighestRow(); // e.g. 10
		$highestColumn = $worksheet->getHighestColumn(); // e.g 'F'//$highestColumnIndex = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($highestColumn); // e.g. 5
 
		$sheetData = $spreadsheet->getActiveSheet()->toArray();
		$allupdt=0;
		$allfn='Y';
        $ebmissing="";
        $success=true;
        if (!empty($sheetData)) {
                $stdt = $periodfromdate;
				$endt=	$periodtodate;
                $lstdt1=$sheetData[1][0];
                $lendt1=	$sheetData[1][1];
                $lstdt= date('Y-m-d', strtotime($lstdt1));
                $lendt= date('Y-m-d', strtotime($lendt1));
//                 echo "Converted start date: $lstdt<br>";
//                echo "Checking date in first data row: Expected: $stdt to $endt, Found: $lstdt to $lendt.<br>";
                if ($lstdt != $stdt || $lendt != $endt) {
                //    echo "Date mismatch in row $i. Expected: $stdt to $endt, Found: $lstdt to $lendt.<br>";
                    $allfn='N';
                    $success=false;
                    $allupdt=0;
                }   
            if ($allfn=='Y') {
             for ($i=1; $i<count($sheetData); $i++) { //skipping first row
                $stdt = $periodfromdate;
				$endt=	$periodtodate;
                $lstdt1=$sheetData[1][0];
                $lendt1=	$sheetData[1][1];
                $lstdt= date('Y-m-d', strtotime($lstdt1));
                $lendt= date('Y-m-d', strtotime($lendt1));
                if ($lstdt != $stdt || $lendt != $endt) {
                   $ebmissing.= "Date mismatch in row $i";
            //       echo "Date mismatch in row $i. Expected: $stdt to $endt, Found: $lstdt to $lendt.<br>";
                    continue;
                }   
                $lnno = !empty($sheetData[$i][2]) ? $sheetData[$i][2] : 0;
				$srdhlp = !empty($sheetData[$i][3]) ? $sheetData[$i][3] : 0;
                $rhrs = !empty($sheetData[$i][4]) ? $sheetData[$i][4] : 0;
				$lhrs = !empty($sheetData[$i][5]) ? $sheetData[$i][5] : 0;
            	$wgrp=$sheetData[$i][6];
				$nolms = !empty($sheetData[$i][7]) ? $sheetData[$i][7] : 0;
                if (empty($stdt) || empty($endt) ) {
                    echo "Skipping row $i due to missing data.<br>";
                    continue;
                }
                // Validate date format
                if (!DateTime::createFromFormat('Y-m-d', $stdt) || !DateTime::createFromFormat('Y-m-d', $endt)) {
                    echo "Invalid date format in row $i. Expected format: Y-m-d.<br>";
                    continue;
                }

                $sql="select * from EMPMILL12.tbl_run_loom_line_hours where line_no=$lnno and date_from='$stdt' 
                and date_to='$endt' and sard_helper='$srdhlp' and wgroup='$wgrp' ";
                $query= $this->db->query($sql);
                if ($query->num_rows() > 0) {
                    $row = $query->row();   
                        $sqlu="update EMPMILL12.tbl_run_loom_line_hours set running_hours=$rhrs,lost_hours=$lhrs,
                        no_of_looms=$nolms
                        where line_no=$lnno and date_from='$stdt' 
                        and date_to='$endt' and sard_helper='$srdhlp' and wgroup='$wgrp'";
//                    echo $sqlu."<br>"   ;
                        $query = $this->db->query($sqlu);
                } else {       
                     $sql = "insert into EMPMILL12.tbl_run_loom_line_hours (date_from, date_to,line_no,sard_helper,
                     running_hours,lost_hours,
                     wgroup,no_of_looms
                    ) values 
					('$stdt','$endt',$lnno,'$srdhlp',$rhrs,$lhrs,
                    '$wgrp',$nolms
                    )";
                    $query = $this->db->query($sql);
                }
//                    echo $sql;
    //                echo "Row $allupdt processed successfully.<br>";
					$allupdt++;
                }	
			}		
        }
          $response = array(
				'success' => $success,
				'savedata'=> $allupdt,
                'ebmissing' => $ebmissing,
			);
			
				echo json_encode($response);
 


 	}





}




public function njmwrkfaupload() {

	$this->load->library('session');
	$this->load->helper(array('form', 'url'));
	$this->load->library('upload');
	$periodfromdate = $this->input->post('periodfromdate');
	$periodtodate = $this->input->post('periodtodate');
//	$fileupload =  $this->input->post('wrkfaexlfileupload');
	$fileupload =  $this->input->post('fileupload');
 	$att_payschm =  $this->input->post('att_payschm');
 	$comp = $this->session->userdata('companyId');
echo 'file upload: ' . $fileupload;
//    echo $fileupload.'=='.$periodfromdate.'=='.$periodtodate.'=='.$att_payschm;

	 $config['upload_path'] = './uploads/';
	 $config['allowed_types'] = 'csv|xlsx';
	 $config['max_size'] = 2048;

	 $this->upload->initialize($config);

	 $file_mimes = array('text/x-comma-separated-values', 'text/comma-separated-values', 'application/octet-stream', 
	 'application/vnd.ms-excel', 'application/x-csv', 'text/x-csv', 'text/csv', 
	 'application/csv', 'application/excel', 'application/vnd.msexcel', 'text/plain', 
	 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');

//echo 'ahahah'.$fileupload;
     if (!$this->upload->do_upload('fileupload')) {
		$error = array('error' => $this->upload->display_errors());
		echo $error['error'];
	} else {
		$data = $this->upload->data();

		// Ensure PhpSpreadsheet is included

		$file_mimes = array(
			'text/x-comma-separated-values', 'text/comma-separated-values', 'application/octet-stream',
			'application/vnd.ms-excel', 'application/x-csv', 'text/x-csv', 'text/csv',
			'application/csv', 'application/excel', 'application/vnd.msexcel', 'text/plain',
			'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
		);

		// Get the file extension
		$arr_file = explode('.', $data['file_name']);
		$extension = end($arr_file);

		// Determine the appropriate reader based on the file extension
		if ('csv' == $extension) {
			$reader = new \PhpOffice\PhpSpreadsheet\Reader\Csv();
		} else {
			$reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
		}

	//	$spreadsheet = $reader->load($_FILES['file']['tmp_name']);
		$spreadsheet = $reader->load($data['full_path']);

		//$lastRow = $spreadsheet->getHighestDataRow();
		//echo $lastRow;
		$worksheet = $spreadsheet->getActiveSheet();
		// Get the highest row and column numbers referenced in the worksheet
		$highestRow = $worksheet->getHighestRow(); // e.g. 10
		$highestColumn = $worksheet->getHighestColumn(); // e.g 'F'//$highestColumnIndex = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($highestColumn); // e.g. 5
	//	echo 'hrow='.$highestRow.' hcol'.$highestColumn ;
		
//start_date	end_date	eb_no	wrk_hours_reg	wrk_hours_ot	wrk_hours_adj	rates	prod_id	
// production	canteen	advance	plus_amount	minus_amount	travel_allowance	updt_from

        $sql="update EMPMILL12.tbl_njm_wages_data_collection set is_active=0 
        where date_from='$periodfromdate' and date_to='$periodtodate' and update_for='FA' and is_active=1
        and payscheme_id=$att_payschm";
        $query = $this->db->query($sql);            

		$sheetData = $spreadsheet->getActiveSheet()->toArray();
        $rows  = $worksheet->toArray(null, true, true, true); // preserves columns as A,B,C...

            // 3) Convert to associative rows based on header row (row 1)
            if (count($rows) < 2) {
                return $this->json(['ok'=>false,'msg'=>'Excel has no data rows.']);
            }


/*             $header = array_map('trim', $rows[1]); // row 1 is header
            $data   = [];
            for ($i = 2; $i <= count($rows); $i++) {
                $row = [];
                foreach ($header as $colKey => $colName) {
                    if ($colName === '') continue;
                    $row[$colName] = isset($rows[$i][$colKey]) ? trim((string)$rows[$i][$colKey]) : null;
                }
                if (!empty(array_filter($row, fn($v) => $v !== null && $v !== ''))) {
                    $data[] = $row;
                }
            }

            // 4) Save JSON file
/*             $jsonName = pathinfo($fileData['file_name'], PATHINFO_FILENAME) . '.json';
            $jsonPath = $config['upload_path'] . $jsonName;
            echo $jsonName.'=='.$jsonPath;
            write_file($jsonPath, json_encode($data, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE));
 
$filename = 'data_' . date('Ymd_His') . '.json';
$uploadDir = FCPATH . 'uploads/';
if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);

$fullPath = $uploadDir . $filename; // absolute path
$jsonUrl  = base_url('uploads/' . $filename); // public URL

file_put_contents($fullPath, json_encode($data, JSON_PRETTY_PRINT));

 */

		$sheetData = $spreadsheet->getActiveSheet()->toArray();
		$allupdt=0;
		$allfn='Y';
        $ebmissing="";
		if (!empty($sheetData)) {
            for ($i=1; $i<count($sheetData); $i++) { //skipping first row
                $stdt = $periodfromdate;
				$endt=	$periodtodate;
				$ebno=	$sheetData[$i][1];
				//$wrkahrs=$sheetData[$i][3];
                $faamt = !empty($sheetData[$i][3]) ? $sheetData[$i][7] : 0;
     
                if (empty($stdt) || empty($endt) || empty($ebno)) {
                    echo "Skipping row $i due to missing data.<br>";
                    continue;
                }

                // Validate date format
                if (!DateTime::createFromFormat('Y-m-d', $stdt) || !DateTime::createFromFormat('Y-m-d', $endt)) {
                    echo "Invalid date format in row $i. Expected format: Y-m-d.<br>";
                    continue;
                }


            $ebid=0;                
                $wsql="select tpep.EMPLOYEEID eb_id from  vowsls.tbl_pay_employee_payscheme tpep 
                left join vowsls.tbl_hrms_ed_personal_details thepd on tpep.EMPLOYEEID=thepd.eb_id
                left join vowsls.tbl_hrms_ed_official_details theod on tpep.EMPLOYEEID=theod.eb_id and theod.is_active =1
                left join vowsls.tbl_hrms_ed_resign_details therd on tpep.EMPLOYEEID=therd.eb_id and therd.is_active =1
                where tpep.STATUS =1 and tpep.PAY_SCHEME_ID =164 
";

//                echo $wsql;
                $query= $this->db->query($wsql);
                if ($query->num_rows() > 0) {
                    $row = $query->row();
                    $ebid = $row->eb_id;
                } else {
  //                  echo "Employee not found for code $ebno";
                    $ebmissing .= "Employee not found for code $ebno";
                    continue;
                }

//            echo "Processing row $i: $ebno,$ebid, $stdt, $endt, $ebno, $wrkahrs, $wrkrt, $prdid, $prod, 
//            $canteen, $adv, $plusamt, $minusamt, $taamt, $updt<br>";

                if ($ebid > 0) {
                    $sql = "insert into EMPMILL12.tbl_njm_wages_data_collection (
                        date_from, date_to, eb_id,is_active,update_for, other_pay
                        ) values 
					('$stdt','$endt',$ebid,1,'FA',
                    $faamt)";
//                    echo $sql;
                    $query = $this->db->query($sql);
					$allupdt++;
    //                echo "Row $allupdt processed successfully.<br>";
                }	
			}		
		}		




 			
          $response = array(
				'success' => true,
				'savedata'=> $allupdt,
                'ebmissing' => $ebmissing,
			);
			
				echo json_encode($response);
 


 	}

}


public function njmwrkfauploadjs() {

	$this->load->library('session');
	$this->load->helper(array('form', 'url'));
	$this->load->library('upload');
	$periodfromdate = $this->input->post('periodfromdate');
	$periodtodate = $this->input->post('periodtodate');
//	$fileupload =  $this->input->post('wrkfaexlfileupload');
	$fileupload =  $this->input->post('fileupload');
 	$att_payschm =  $this->input->post('att_payschm');
 	$comp = $this->session->userdata('companyId');
//echo 'file upload: ' . $fileupload;

//    echo $fileupload.'=='.$periodfromdate.'=='.$periodtodate.'=='.$att_payschm;

	 $config['upload_path'] = './uploads/';
	 $config['allowed_types'] = 'csv|xlsx';
	 $config['max_size'] = 2048;

	 $this->upload->initialize($config);

	 $file_mimes = array('text/x-comma-separated-values', 'text/comma-separated-values', 'application/octet-stream', 
	 'application/vnd.ms-excel', 'application/x-csv', 'text/x-csv', 'text/csv', 
	 'application/csv', 'application/excel', 'application/vnd.msexcel', 'text/plain', 
	 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');

//echo 'ahahah'.$fileupload;
     if (!$this->upload->do_upload('fileupload')) {
		$error = array('error' => $this->upload->display_errors());
		echo $error['error'];
	} else {
		$data = $this->upload->data();

		// Ensure PhpSpreadsheet is included

		$file_mimes = array(
			'text/x-comma-separated-values', 'text/comma-separated-values', 'application/octet-stream',
			'application/vnd.ms-excel', 'application/x-csv', 'text/x-csv', 'text/csv',
			'application/csv', 'application/excel', 'application/vnd.msexcel', 'text/plain',
			'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
		);

		// Get the file extension
		$arr_file = explode('.', $data['file_name']);
		$extension = end($arr_file);


     $fileData  = $this->upload->data();
    $full_path = $fileData['full_path'];

		// Determine the appropriate reader based on the file extension
		if ('csv' == $extension) {
			$reader = new \PhpOffice\PhpSpreadsheet\Reader\Csv();
		} else {
			$reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
		}

	//	$spreadsheet = $reader->load($_FILES['file']['tmp_name']);
		$spreadsheet = $reader->load($data['full_path']);

		//$lastRow = $spreadsheet->getHighestDataRow();
		//echo $lastRow;
		$worksheet = $spreadsheet->getActiveSheet();
		// Get the highest row and column numbers referenced in the worksheet
		$highestRow = $worksheet->getHighestRow(); // e.g. 10
		$highestColumn = $worksheet->getHighestColumn(); // e.g 'F'//$highestColumnIndex = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($highestColumn); // e.g. 5
	//	echo 'hrow='.$highestRow.' hcol'.$highestColumn ;
		
//start_date	end_date	eb_no	wrk_hours_reg	wrk_hours_ot	wrk_hours_adj	rates	prod_id	
// production	canteen	advance	plus_amount	minus_amount	travel_allowance	updt_from

        $sql="update EMPMILL12.tbl_njm_wages_data_collection set is_active=0 
        where date_from='$periodfromdate' and date_to='$periodtodate' and update_for='FA' and is_active=1
        and payscheme_id=$att_payschm";
        $query = $this->db->query($sql);            

		$sheetData = $spreadsheet->getActiveSheet()->toArray();
        $rows  = $worksheet->toArray(null, true, true, true); // preserves columns as A,B,C...

            // 3) Convert to associative rows based on header row (row 1)
            if (count($rows) < 2) {
                return $this->json(['ok'=>false,'msg'=>'Excel has no data rows.']);
            }


            $header = array_map('trim', $rows[1]); // row 1 is header
            $data   = [];
            for ($i = 2; $i <= count($rows); $i++) {
                $row = [];
                foreach ($header as $colKey => $colName) {
                    if ($colName === '') continue;
                    $row[$colName] = isset($rows[$i][$colKey]) ? trim((string)$rows[$i][$colKey]) : null;
                }
                if (!empty(array_filter($row, fn($v) => $v !== null && $v !== ''))) {
                    $data[] = $row;
                }
            }

            // 4) Save JSON file
/*             $jsonName = pathinfo($fileData['file_name'], PATHINFO_FILENAME) . '.json';
            $jsonPath = $config['upload_path'] . $jsonName;
            echo $jsonName.'=='.$jsonPath;
            write_file($jsonPath, json_encode($data, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE));
 */
$filename = 'data_' . date('Ymd_His') . '.json';
$uploadDir = FCPATH . 'uploads/';
if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);

$fullPath = $uploadDir . $filename; // absolute path
$jsonUrl  = base_url('uploads/' . $filename); // public URL

file_put_contents($fullPath, json_encode($data, JSON_PRETTY_PRINT));


if (is_file($full_path)) { @unlink($full_path); }
$jsonData = file_get_contents($fullPath);

//echo $jsonData;
$this->db->query("SET @json := ?", [$jsonData]);

    // Step 3: Run your query using JSON_TABLE
    $sql = "
select TICKET_NO,EMPLOYEEID eb_id,AMOUNT FROM (
select  
Ticket_NO,
emp_code,
  eb_id AS EMPLOYEEID,
  169 AS PAYSCHEME_ID,
  j.AMOUNT,
  1 AS STATUS,
  CURRENT_TIMESTAMP() AS LUPDATE
FROM (
  SELECT 
    theod.eb_id,
    emp_code,
	tpep.EMPLOYEEID    
  FROM tbl_hrms_ed_official_details theod
  LEFT JOIN tbl_hrms_ed_personal_details thepd ON thepd.eb_id = theod.eb_id
  left join tbl_pay_employee_payscheme tpep on theod.eb_id=tpep.EMPLOYEEID 
  WHERE 
    theod.is_active = 1
    AND thepd.company_id = 1
    AND tpep.PAY_SCHEME_ID = $att_payschm
    AND tpep.STATUS = 1 
) mst
 RIGHT JOIN JSON_TABLE(
  @json,
  '$[*]' COLUMNS (
     Ticket_NO VARCHAR(20) PATH '$.Ticket_NO',
     AMOUNT INT PATH '$.AMOUNT'
   )
) AS j
ON mst.emp_code = j.Ticket_NO
) g where emp_code is null
    ";
//echo $sql;
    $query = $this->db->query($sql);
    $result = $query->result_array();
    $ebmissing = "";
      
    if ($query->num_rows() > 0) {
                foreach ($result as $row) {
    
                    $ebno = $row['Ticket_NO'];
                //    ECHO "Processing Employee Code: $ebno<br>";
                    $ebmissing .= "Employee not found for code $ebno<br>";
                }
    
 


                } else {
   $sql="insert into EMPMILL12.tbl_njm_wages_data_collection (eb_id,date_from ,date_to,is_active,update_for,payscheme_id,other_pay )        
select eb_id,'$periodfromdate' date_from,'$periodtodate' date_to,1 is_active,'FA' update_for,$att_payschm,AMOUNT other_pay from
(
select TICKET_NO,EMPLOYEEID eb_id,AMOUNT FROM (
select  
Ticket_NO,
emp_code,
  eb_id AS EMPLOYEEID,
  169 AS PAYSCHEME_ID,
  j.AMOUNT,
  1 AS STATUS,
  CURRENT_TIMESTAMP() AS LUPDATE
FROM (
  SELECT 
    theod.eb_id,
    emp_code,
	tpep.EMPLOYEEID    
  FROM tbl_hrms_ed_official_details theod
  LEFT JOIN tbl_hrms_ed_personal_details thepd ON thepd.eb_id = theod.eb_id
  left join tbl_pay_employee_payscheme tpep on theod.eb_id=tpep.EMPLOYEEID 
  WHERE 
    theod.is_active = 1
    AND thepd.company_id = 1
    AND tpep.PAY_SCHEME_ID = $att_payschm
    AND tpep.STATUS = 1 
) mst
 RIGHT JOIN JSON_TABLE(
  @json,
  '$[*]' COLUMNS (
     Ticket_NO VARCHAR(20) PATH '$.Ticket_NO',
     AMOUNT INT PATH '$.AMOUNT'
   )
) AS j
ON mst.emp_code = j.Ticket_NO
) g ) k";
                    
                    $query = $this->db->query($sql);
                    $ebmissing = "All Employees found and processed successfully.";
               }                    


    

//  




    $allupdt = 0;
 


// Now delete the file after use
if (is_file($fullPath)) {
//    @unlink($fullPath);
}

      $response = array(
				'success' => true,
				'savedata'=> $allupdt,
                'ebmissing' => $ebmissing,
			);
			
				echo json_encode($response);
 
 
        }
        }


public function njmwrkothadjsuploadjs() {

	$this->load->library('session');
	$this->load->helper(array('form', 'url'));
	$this->load->library('upload');
	$periodfromdate = $this->input->post('periodfromdate');
	$periodtodate = $this->input->post('periodtodate');
//	$fileupload =  $this->input->post('wrkfaexlfileupload');
	$fileupload =  $this->input->post('fileupload');
 	$att_payschm =  $this->input->post('att_payschm');
 	$comp = $this->session->userdata('companyId');

//    echo $fileupload.'=='.$periodfromdate.'=='.$periodtodate.'=='.$att_payschm;

	 $config['upload_path'] = './uploads/';
	 $config['allowed_types'] = 'csv|xlsx';
	 $config['max_size'] = 2048;

	 $this->upload->initialize($config);

	 $file_mimes = array('text/x-comma-separated-values', 'text/comma-separated-values', 'application/octet-stream', 
	 'application/vnd.ms-excel', 'application/x-csv', 'text/x-csv', 'text/csv', 
	 'application/csv', 'application/excel', 'application/vnd.msexcel', 'text/plain', 
	 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');

     if (!$this->upload->do_upload('fileupload')) {
		$error = array('error' => $this->upload->display_errors());
		echo $error['error'];
	} else {
		$data = $this->upload->data();

		// Ensure PhpSpreadsheet is included

		$file_mimes = array(
			'text/x-comma-separated-values', 'text/comma-separated-values', 'application/octet-stream',
			'application/vnd.ms-excel', 'application/x-csv', 'text/x-csv', 'text/csv',
			'application/csv', 'application/excel', 'application/vnd.msexcel', 'text/plain',
			'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
		);

		// Get the file extension
		$arr_file = explode('.', $data['file_name']);
		$extension = end($arr_file);


     $fileData  = $this->upload->data();
    $full_path = $fileData['full_path'];

		// Determine the appropriate reader based on the file extension
		if ('csv' == $extension) {
			$reader = new \PhpOffice\PhpSpreadsheet\Reader\Csv();
		} else {
			$reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
		}

	//	$spreadsheet = $reader->load($_FILES['file']['tmp_name']);
		$spreadsheet = $reader->load($data['full_path']);

		//$lastRow = $spreadsheet->getHighestDataRow();
		//echo $lastRow;
		$worksheet = $spreadsheet->getActiveSheet();
		// Get the highest row and column numbers referenced in the worksheet
		$highestRow = $worksheet->getHighestRow(); // e.g. 10
		$highestColumn = $worksheet->getHighestColumn(); // e.g 'F'//$highestColumnIndex = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($highestColumn); // e.g. 5
	//	echo 'hrow='.$highestRow.' hcol'.$highestColumn ;
		
//start_date	end_date	eb_no	wrk_hours_reg	wrk_hours_ot	wrk_hours_adj	rates	prod_id	
// production	canteen	advance	plus_amount	minus_amount	travel_allowance	updt_from

        $sql="update EMPMILL12.tbl_njm_wages_data_collection set is_active=0 
        where date_from='$periodfromdate' and date_to='$periodtodate' and update_for='OTH' and is_active=1
        and payscheme_id=$att_payschm";
        $query = $this->db->query($sql);            

		$sheetData = $spreadsheet->getActiveSheet()->toArray();
        $rows  = $worksheet->toArray(null, true, true, true); // preserves columns as A,B,C...

            // 3) Convert to associative rows based on header row (row 1)
            if (count($rows) < 2) {
                return $this->json(['ok'=>false,'msg'=>'Excel has no data rows.']);
            }


            $header = array_map('trim', $rows[1]); // row 1 is header
            $data   = [];
for ($i = 2; $i <= count($rows); $i++) {
    $row = [];
    foreach ($header as $colKey => $colName) {
        if ($colName === '') continue;

        // If the cell is set and not blank, trim it; otherwise set to 0
        $cellValue = isset($rows[$i][$colKey]) ? trim((string)$rows[$i][$colKey]) : '';
        $row[$colName] = ($cellValue === '') ? 0 : $cellValue;
    }

    // Optional: Only keep rows where at least one value is non-zero
    if (!empty(array_filter($row, fn($v) => $v !== 0 && $v !== ''))) {
        $data[] = $row;
    }
}
            // 4) Save JSON file
/*             $jsonName = pathinfo($fileData['file_name'], PATHINFO_FILENAME) . '.json';
            $jsonPath = $config['upload_path'] . $jsonName;
            echo $jsonName.'=='.$jsonPath;
            write_file($jsonPath, json_encode($data, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE));
 */
$filename = 'data_' . date('Ymd_His') . '.json';
$uploadDir = FCPATH . 'uploads/';
if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);

$fullPath = $uploadDir . $filename; // absolute path
$jsonUrl  = base_url('uploads/' . $filename); // public URL

file_put_contents($fullPath, json_encode($data, JSON_PRETTY_PRINT));


if (is_file($full_path)) { @unlink($full_path); }
$jsonData = file_get_contents($fullPath);
//echo $jsonData;
$this->db->query("SET @json := ?", [$jsonData]);

    // Step 3: Run your query using JSON_TABLE
    $sql = "
select TICKET_NO,EMPLOYEEID eb_id FROM (
select  
EB_NO TICKET_NO,
emp_code,
  eb_id AS EMPLOYEEID,
  169 AS PAYSCHEME_ID,
  j.*,
  1 AS STATUS,
  CURRENT_TIMESTAMP() AS LUPDATE
FROM (
  SELECT 
    theod.eb_id,
    emp_code,
	tpep.EMPLOYEEID    
  FROM tbl_hrms_ed_official_details theod
  LEFT JOIN tbl_hrms_ed_personal_details thepd ON thepd.eb_id = theod.eb_id
  left join tbl_pay_employee_payscheme tpep on theod.eb_id=tpep.EMPLOYEEID 
  WHERE 
    theod.is_active = 1
    AND thepd.company_id = 1
    AND tpep.PAY_SCHEME_ID = $att_payschm
    AND tpep.STATUS = 1 
) mst
 RIGHT JOIN JSON_TABLE(
  @json,
  '$[*]' COLUMNS (
     EB_NO VARCHAR(20) PATH '$.EB_NO'
   )
) AS j
ON mst.emp_code = j.EB_NO
) g where emp_code is null
    ";
//echo $sql;
    $query = $this->db->query($sql);
    $result = $query->result_array();
    $ebmissing = "";
//   var_dump ($result)  ;

    if ($query->num_rows() > 0) {
                foreach ($result as $row) {
    
                    $ebno = $row['TICKET_NO'];
                //    ECHO "Processing Employee Code: $ebno<br>";
                    $ebmissing .= "Employee not found for code $ebno<br>";
                }
    
 


                } else {
   $sql="insert into EMPMILL12.tbl_njm_wages_data_collection (eb_id,date_from ,date_to,is_active,update_for,payscheme_id,hours_wkd_1
,hours_wkd_2,esi_days,el_days,sl_days,lay_off_hrs,piece_hours,canteen,sunday_adv,
other_adv,other_pay,el_advance,installment_advance,
extra_hours_t,extra_hours_p,c_shift_days,festival_hours,arrear_plus,arrear_minus,iltime_hrs,ilpiece_hrs,act_prod_amount,
piece_wages_inc,cl_days,minus_bal,ul_days,advance
 )        
select eb_id,'$periodfromdate' date_from,'$periodtodate' date_to,1 is_active,'OTH' update_for,$att_payschm, 
C_wrkhrs1_,C_wrkhours2_,C_esi_days_,C_eldays_,C_sld_,C_LayOffHrs_,C_PHours_,C_canteen_,C_sundayadv_,
C_otheradv1_,C_otherpay_,C_eladvance_,C_instadvded_,C_extrahrstime_,C_extrahourspiece_,C_nightdays_,
C_FHours_,C_mveplus_,C_mveminus_,C_iltime_,C_ilpiece_,C_pwage_,C_pwagesinc_,C_cldays_,C_minusbalance_,C_uldays_,C_exadvance_ from
(
select EMPLOYEEID eb_id,g.* FROM (
select  
emp_code,
  eb_id AS EMPLOYEEID,
  169 AS PAYSCHEME_ID,
  j.*,
  1 AS STATUS,
  CURRENT_TIMESTAMP() AS LUPDATE
FROM (
  SELECT 
    theod.eb_id,
    emp_code,
	tpep.EMPLOYEEID    
  FROM tbl_hrms_ed_official_details theod
  LEFT JOIN tbl_hrms_ed_personal_details thepd ON thepd.eb_id = theod.eb_id
  left join tbl_pay_employee_payscheme tpep on theod.eb_id=tpep.EMPLOYEEID 
  WHERE 
    theod.is_active = 1
    AND thepd.company_id = 1
    AND tpep.PAY_SCHEME_ID = $att_payschm
    AND tpep.STATUS = 1 
) mst
 RIGHT JOIN JSON_TABLE(
  @json,
  '$[*]' COLUMNS (
     EB_NO VARCHAR(20) PATH '$.EB_NO',
C_wrkhrs1_ DECIMAL(10,2) PATH  '$.C_wrkhrs1_',
C_wrkhours2_ DECIMAL(10,2) PATH  '$.C_wrkhours2_',
C_esi_days_ DECIMAL(10,2) PATH  '$.C_esi_days_',
C_eldays_ DECIMAL(10,2) PATH  '$.C_eldays_',
C_sld_ DECIMAL(10,2) PATH  '$.C_sld_',
C_LayOffHrs_ DECIMAL(10,2) PATH  '$.C_LayOffHrs_',
C_PHours_ DECIMAL(10,2) PATH  '$.C_PHours_',
C_canteen_ DECIMAL(10,2) PATH  '$.C_canteen_',
C_sundayadv_ DECIMAL(10,2) PATH  '$.C_sundayadv_',
C_otheradv1_ DECIMAL(10,2) PATH  '$.C_otheradv1_',
C_otherpay_ DECIMAL(10,2) PATH  '$.C_otherpay_',
C_eladvance_ DECIMAL(10,2) PATH  '$.C_eladvance_',
C_instadvded_ DECIMAL(10,2) PATH  '$.C_instadvded_',
C_extrahrstime_ DECIMAL(10,2) PATH  '$.C_extrahrstime_',
C_extrahourspiece_ DECIMAL(10,2) PATH  '$.C_extrahourspiece_',
C_nightdays_ DECIMAL(10,2) PATH  '$.C_nightdays_',
C_FHours_ DECIMAL(10,2) PATH  '$.C_FHours_',
C_mveplus_ DECIMAL(10,2) PATH  '$.C_mveplus_',
C_mveminus_ DECIMAL(10,2) PATH  '$.C_mveminus_',
C_iltime_ DECIMAL(10,2) PATH  '$.C_iltime_',
C_ilpiece_ DECIMAL(10,2) PATH  '$.C_ilpiece_',
C_pwage_ DECIMAL(10,2) PATH  '$.C_pwage_',
C_pratesardhelp_ DECIMAL(10,2) PATH  '$.C_pratesardhelp_',
C_pwagesinc_ DECIMAL(10,2) PATH  '$.C_pwagesinc_',
C_cldays_ DECIMAL(10,2) PATH  '$.C_cldays_',
C_minusbalance_ DECIMAL(10,2)  PATH  '$.C_minusbalance_',
C_uldays_ DECIMAL(10,2) PATH  '$.C_uldays_',
C_exadvance_ DECIMAL(10,2) PATH  '$.C_exadvance_'
   )
) AS j
ON mst.emp_code = j.EB_NO
) g ) k";
//echo $sql;       

                    $query = $this->db->query($sql);
                    $ebmissing = "All Employees found and processed successfully.";
               }                    


    

//  




    $allupdt = 0;
 


// Now delete the file after use
if (is_file($fullPath)) {
    @unlink($fullPath);
}

      $response = array(
				'success' => true,
				'savedata'=> $allupdt,
                'ebmissing' => $ebmissing,
			);
			
				echo json_encode($response);
 
 
        }
        }





	public function njmcntwagesprocessdata() {

		
		$rec_time =  date('Y-m-d H:i:s');
		$comp = $this->session->userdata('companyId');
		$periodfromdate = $this->input->post('periodfromdate');
		$periodtodate = $this->input->post('periodtodate');
		$att_payschm = $this->input->post('att_payschm');
	    $stat=3;
		$active=1;
		$userid=$this->session->userdata('userid');

		$mcc=$this->Njmallwagesprocess->njmcntwagesprocessdata($periodfromdate,$periodtodate);


 	
	 

			$response = array(
		'success' => true,
		'savedata'=> 'saved'
	);
	


	echo json_encode($response);



//$this->exceldownload();


	}




    


public function njmstaffbanksheet() {
		// Create a new Spreadsheet object
		$periodfromdate= $this->input->get('periodfromdate');
		$periodtodate = $this->input->get('periodtodate');
		$att_payschm = $this->input->get('att_payschm');
//        $payschm=substr($att_payschm,0,2);
//        echo 'payschm='.$payschm;
  				$periodfromdate= $this->input->get('periodfromdate');
				$periodtodate= $this->input->get('periodtodate');
				$periodtodt= substr($periodtodate,8,2);
				$periodfrmdate=substr($periodfromdate,8,2).'-'.substr($periodfromdate,5,2).'-'.substr($periodfromdate,0,4); 
				$periodtdate=substr($periodtodate,8,2).'-'.substr($periodtodate,5,2).'-'.substr($periodtodate,0,4); 



				$att_payschm =  $this->input->get('att_payschm');
				$holget =  $this->input->get('holget');
				$payschemeName =  $this->input->get('payschemeName');
					 $company_name = $this->session->userdata('companyname');
					 $comp = $this->session->userdata('companyId');
				 $this->data['att_payschm'] = $att_payschm;
					 $fileContainer = "paydata.txt";
					 $filePointer = fopen($fileContainer,"w+");
						$mccodes = $this->Njmallwagesprocess->njmstaffbanksheet($periodfromdate,$periodtodate,$att_payschm,$holget);
						$spreadsheet = new Spreadsheet();
						$sheet = $spreadsheet->getActiveSheet();
						$sdate=substr($periodfromdate,8,2).'-'.substr($periodfromdate,5,2).'-'.substr($periodfromdate,0,4);
						$ldate=substr($periodtodate,8,2).'-'.substr($periodtodate,5,2).'-'.substr($periodtodate,0,4);
						
						$sheet->getPageMargins()->setTop(.25);
						$sheet->getPageMargins()->setRight(0.25);
						$sheet->getPageMargins()->setLeft(0.25);
						$sheet->getPageMargins()->setBottom(0.25);
						
						$borderStyle = [
							'borders' => [
								'allBorders' => [
									'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
								],
							],
						];
						$boldFontStyle = [
							'font' => [
								'bold' => true,
								'size' => 12,
							],
						];
//						EB.NO	NAME	ACCOUNT NO	IFSC NO	NET PAY	
                        $mnth = date('F Y', strtotime($periodtodate));
                        $hed1 = 'Staff Salary Bank Payment Sheet for  ' . $mnth;
						$sheet->setCellValue('A1', 'NELLIMARLA JUTE MILLS CO Ltd');
						$sheet->setCellValue('A2', $hed1);
						$sheet->setCellValue('A3', 'EB No');
						$sheet->setCellValue('B3', 'Name');
						$sheet->setCellValue('C3', 'ACCOUNT NO');
						$sheet->setCellValue('D3', 'IFSC CODE');
						$sheet->setCellValue('E3', 'NET PAY');
						$sheet->setCellValue('F3', 'BANK');
						$N=1;	
						$b='A'.$N.':f'.$N;
						$sheet->mergeCells('A1:f1');
						$N=2;	
						$b='A'.$N.':f'.$N;
						$sheet->mergeCells('A2:f2');
					  //$objPHPExcel->getActiveSheet($c)->mergeCells($b);
						$n=4;
						$no=1;
						$totamt=0;
						$tothrs=0;
						foreach ($mccodes as $row) {
								$cln='A'.$n;
								$rw=$row->eb_no; 
								$sheet->setCellValue($cln, $rw);
								$sheet->getStyle($cln)->applyFromArray($borderStyle);
								$cll=$cln;
								$sheet->getCell($cll)
								->setValueExplicit(
									$rw,
									\PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING
								);
								$rw=$row->wname; 
								$cln='B'.$n;
								$sheet->setCellValue($cln, $rw);
								$sheet->getStyle($cln)->applyFromArray($borderStyle);
								$cln='c'.$n;
								$rw=$row->bank_acc_no; 
								$sheet->setCellValue($cln, $rw);
								$sheet->getStyle($cln)->applyFromArray($borderStyle);
								$cll=$cln;
								$sheet->getCell($cll)
								->setValueExplicit(
									$rw,
									\PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING
								);
								$cln='d'.$n;
								$rw=$row->ifsc_code; 
								$sheet->setCellValue($cln, $rw);
								$sheet->getStyle($cln)->applyFromArray($borderStyle);
								$cln='e'.$n;
								$rw=$row->NET_PAY; 
								$sheet->setCellValue($cln, $rw);
								$sheet->getStyle($cln)->applyFromArray($borderStyle);
								$cln='f'.$n;
								$rw=substr($row->ifsc_code,0,4); 
								$sheet->setCellValue($cln, $rw);
								$sheet->getStyle($cln)->applyFromArray($borderStyle);
								$n++;
								$totamt=$totamt+$row->NET_PAY;
							}		
							$date = date('d/M/Y');
							$cln='a'.$n;
							$sheet->getStyle($cln)->applyFromArray($borderStyle);
							$cln='c'.$n;
							$sheet->setCellValue($cln, 'Total');
							$cln='d'.$n;
							$cln='e'.$n;
							$sheet->setCellValue($cln, $totamt);
							$clnr='a'.$n.':'.'f'.$n;
							$sheet->getStyle($clnr)->applyFromArray($boldFontStyle);
						
					
							$sheet->getColumnDimension('A')->setWidth(9);
							$sheet->getColumnDimension('B')->setWidth(30);
							$sheet->getColumnDimension('C')->setWidth(25);
							$sheet->getColumnDimension('d')->setWidth(25);
							$sheet->getColumnDimension('e')->setWidth(15);
							$sheet->getColumnDimension('f')->setWidth(15);
							$centerAlignment = $sheet->getStyle('A1:a2')->getAlignment();
							$centerAlignment->setHorizontal(Alignment::HORIZONTAL_CENTER);
							$centerAlignment = $sheet->getStyle('A3:f3')->getAlignment();
							$centerAlignment->setHorizontal(Alignment::HORIZONTAL_CENTER);
							
							// Apply font style to cell A1
							$sheet->getStyle('A1:a2')->applyFromArray($boldFontStyle);
							
						
							
							$sheet->getStyle('A1:f1')->applyFromArray($borderStyle);
							$sheet->getStyle('A2:f2')->applyFromArray($borderStyle);
							$sheet->getStyle('A3:a3')->applyFromArray($borderStyle);
							$sheet->getStyle('b3:b3')->applyFromArray($borderStyle);
							$sheet->getStyle('c3:c3')->applyFromArray($borderStyle);
							$sheet->getStyle('d3:d3')->applyFromArray($borderStyle);
							$sheet->getStyle('e3:e3')->applyFromArray($borderStyle);
							$sheet->getStyle('f3:f3')->applyFromArray($borderStyle);
					
						$deptname='Bank Sheet';	
						$sheet->setTitle($deptname);
					
					
						
							
							$filename="bank_".$ldate.'.xlsx';
						
						// After generating the Excel file
						$excelUrl = 'path_to_generated_excel_file.xlsx'; // Change this to the actual URL
						
						// Return the URL along with other response data
						echo json_encode(array('success' => true, 'savedata' => $savedata, 'excelUrl' => $excelUrl));
						
							// Set headers for Excel file download
						//	ob_clean();
							header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
						//	header('Content-Disposition: attachment;filename="your_excel_file.xlsx"');
						
						//		header('Content-Type: application/vnd.ms-excel');
							header('Content-Disposition: attachment;filename='.$filename);
							header('Cache-Control: max-age=0');
							ob_clean();
						
							// Save the Excel file to output stream
							$writer = new Xlsx($spreadsheet);
							$writer->save('php://output');
							// Save the Excel file to output stream
						//	$writer = new Xlsx($spreadsheet);
						//	$writer->save('php://output');
							
								// Terminate the script to prevent further output
								exit;
						
					
						
						





}



public function njmcntwagesexceldownload() {
		// Create a new Spreadsheet object
		$periodfromdate= $this->input->get('periodfromdate');
		$periodtodate = $this->input->get('periodtodate');
		$att_payschm = $this->input->get('att_payschm');

    echo $periodfromdate.'=='.$periodtodate.'=='.$att_payschm;
    $result = $this->Njmallwagesprocess->njmcntwagesexceldownload($periodfromdate,$periodtodate,$att_payschm);
//var_dump($result);
//    $query = $this->Njmallwagesprocess->getcntwagespayslip($periodfromdate,$periodtodate,$att_payschm);
    if (!$result) {
        echo "Query failed.";
        return;
    }
    
    // Check if the query returned any results
   //   
//$result=$this->Njmallwagesprocess->njmcntwagesexceldownload($periodfromdate,$periodtodate,$att_payschm);
  //  var_dump($query);

//  var_dump($result);
if (empty($result) || count($result) == 0) {
    echo "No data found.";
    return;
}
        $query = $result;
//    echo "Total Rows: " . $result->num_rows() . "<br>";

				$spreadsheet = new Spreadsheet();
	$sheet = $spreadsheet->getActiveSheet();

    $sdate=$periodtodate;
	//	$sdate='2024-01-01';

//echo $this->db->last_query();
// Fetching the result set as an array of arrays

//$results = $query->result_array();
//$columns = $query->list_fields();
//$numCols = $query->num_fields();

//$result = $query->result_array();


$columnNames = array_keys($result[0]);
echo "Column Names: " . implode(", ", $columnNames) . "<br>";
$col = 'A';
    foreach ($columnNames as $column) {
        $sheet->setCellValue($col.'1', $column);
        $col++;
    }

  /*   $rowNumber = 2;
    $colno=0;
    foreach ($result as $row) {
    $colno=0;
        $col = 'A';
        foreach ($row as $cell) {
            if ($colno<=0) {
              //  $sheet->setCellValueExplicitByColumnAndRow($colIndex, $rowIndex, $value, DataType::TYPE_STRING);
//                $sheet->setCellValue($col.$rowNumber, $cell, DataType::TYPE_STRING);
                $sheet->setCellValueExplicit($col.$rowNumber, $cell, DataType::TYPE_STRING);

            } else {    
                $sheet->setCellValue($col.$rowNumber, $cell);
            $col++;
            $colno++;
            }       
        }
        $rowNumber++;
    }
 */

$rowNumber = 2;

foreach ($result as $row) {
    $col = 'A';
    $colno = 0;

    foreach ($row as $cell) {
        if ($colno <= 0) {
            $sheet->setCellValueExplicit($col . $rowNumber, $cell, DataType::TYPE_STRING);
        } else {
            $sheet->setCellValue($col . $rowNumber, $cell);
        }

        $col++;     // move outside
        $colno++;   // move outside
    }

    $rowNumber++;
}



//$sheet = $spreadsheet->createSheet($index);

// Rename the sheet
$sheet->setTitle('Template');

$sheet = $spreadsheet->createSheet(1);
$sheet->setTitle('Pay Components Info');

	
    $filename="payroll_".$sdate.'.xlsx';

// After generating the Excel file
$excelUrl = 'path_to_generated_excel_file.xlsx'; // Change this to the actual URL

// Return the URL along with other response data
echo json_encode(array('success' => true, 'savedata' => $savedata, 'excelUrl' => $excelUrl));

	// Set headers for Excel file download
//	ob_clean();
	header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
//	header('Content-Disposition: attachment;filename="your_excel_file.xlsx"');

//		header('Content-Type: application/vnd.ms-excel');
	header('Content-Disposition: attachment;filename='.$filename);
	header('Cache-Control: max-age=0');
	ob_clean();

	// Save the Excel file to output stream
	$writer = new Xlsx($spreadsheet);
	$writer->save('php://output');
	// Save the Excel file to output stream
//	$writer = new Xlsx($spreadsheet);
//	$writer->save('php://output');
	
		// Terminate the script to prevent further output
		exit;



	}
	


public function njmcntbankexceldownload() {
		// Create a new Spreadsheet object
		$periodfromdate= $this->input->get('periodfromdate');
		$periodtodate = $this->input->get('periodtodate');
		$att_payschm = $this->input->get('att_payschm');

    echo $periodfromdate.'=='.$periodtodate.'=='.$att_payschm;
   // $result = $this->Njmallwagesprocess->njmcntwagesexceldownload($periodfromdate,$periodtodate,$att_payschm);
	$result = $this->Njmallwagesprocess->njmcntbnkexceldownload($periodfromdate,$periodtodate,$att_payschm  );

//    var_dump($result);
//    $query = $this->Njmallwagesprocess->getcntwagespayslip($periodfromdate,$periodtodate,$att_payschm);
    if (!$result) {
        echo "Query failed.";
        return;
    }
    
if (empty($result) || count($result) == 0) {
    echo "No data found.";
    return;
}
        $query = $result;
//    echo "Total Rows: " . $result->num_rows() . "<br>";

	$spreadsheet = new Spreadsheet();
	$sheet = $spreadsheet->getActiveSheet();

						$borderStyle = [
							'borders' => [
								'allBorders' => [
									'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
								],
							],
						];
						$boldFontStyle = [
							'font' => [
								'bold' => true,
								'size' => 12,
							],
						];
 

$sheet->setCellValue('A1', 'NELLIMARLA JUTE MILLS CO LTD');
$sheet->setCellValue('A2', 'CONTRACTOR WORKERS BANK STATEMENT');
$N=1;
$b='A'.$N.':f'.$N;
$sheet->mergeCells($b);
$N++;
$b='A'.$N.':f'.$N;
$sheet->mergeCells($b);


$rowNumber = 3;

$tpay=0;
$columnNames = array_keys($result[0]);
echo "Column Names: " . implode(", ", $columnNames) . "<br>";
$col = 'A';
    foreach ($columnNames as $column) {
        $sheet->setCellValue($col.'3', $column);
        $col++;
    }

 
$rowNumber = 4;

foreach ($result as $row) {
    $col = 'A';
    $colno = 0;

    foreach ($row as $cell) {
        if ($colno <= 4) {
            $sheet->setCellValueExplicit($col . $rowNumber, $cell, DataType::TYPE_STRING);
        } else {
            $sheet->setCellValue($col . $rowNumber, $cell);
            $tpay=$tpay+$row->NET_PAY;
        }

        $col++;     // move outside
        $colno++;   // move outside
    }

    $rowNumber++;
}

$rn='F'.$rowNumber;
$sheet->setCellValue($rn, $tpay);

    foreach (range('A', 'N') as $col) {
        $sheet->getColumnDimension($col)->setAutoSize(true);
    }

 
						 $sheet->getColumnDimension('A')->setWidth(9);
							$centerAlignment = $sheet->getStyle('A1:a2')->getAlignment();
							$centerAlignment->setHorizontal(Alignment::HORIZONTAL_CENTER);
							$centerAlignment = $sheet->getStyle('A3:f3')->getAlignment();
							$centerAlignment->setHorizontal(Alignment::HORIZONTAL_CENTER);
							
							// Apply font style to cell A1
							$sheet->getStyle('A1:a2')->applyFromArray($boldFontStyle);
							
						
							
							$sheet->getStyle('A1:f1')->applyFromArray($borderStyle);
							$sheet->getStyle('A2:f2')->applyFromArray($borderStyle);
	 

//$sheet = $spreadsheet->createSheet($index);

// Rename the sheet
$sheet->setTitle('Template');

$sheet = $spreadsheet->createSheet(1);
$sheet->setTitle('Pay Components Info');

	
    $filename="bank_".$sdate.'.xlsx';

// After generating the Excel file
$excelUrl = 'path_to_generated_excel_file.xlsx'; // Change this to the actual URL

// Return the URL along with other response data
echo json_encode(array('success' => true, 'savedata' => $savedata, 'excelUrl' => $excelUrl));

	// Set headers for Excel file download
//	ob_clean();
	header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
//	header('Content-Disposition: attachment;filename="your_excel_file.xlsx"');

//		header('Content-Type: application/vnd.ms-excel');
	header('Content-Disposition: attachment;filename='.$filename);
	header('Cache-Control: max-age=0');
	ob_clean();

	// Save the Excel file to output stream
	$writer = new Xlsx($spreadsheet);
	$writer->save('php://output');
	// Save the Excel file to output stream
//	$writer = new Xlsx($spreadsheet);
//	$writer->save('php://output');
	
		// Terminate the script to prevent further output
		exit;



	}
	




	public function getnjmcntbnkstatement() {
		$periodfromdate= $this->input->post('periodfromdate');
		$periodtodate = $this->input->post('periodtodate');
		$att_payschm = $this->input->post('att_payschm');
		$exlprn = $this->input->get('exlprn');
        
 	 //   echo $periodfromdate.'=='.$periodtodate.'=='.$att_payschm.'=='.$exlprn;
 
		$mccodes = $this->Njmallwagesprocess->getnjmcntbnkstatement($periodfromdate,$periodtodate,$att_payschm  );
//var_dump($mccodes);
        $data = [];
			foreach ($mccodes as $record) {
//                echo "Record: " . print_r($record, true) . "<br>";
        //        echo "tktno ". $record->TKTNO . "<br>";
                $data[] = [
							$FROM_DATE=$record->eb_id,
							$TO_DATE=$record->TKTNO,
							$eb_no=$record->NAME,
							$wname=$record->BANK_NAME,
							$wname=$record->IFSC_CODE,
							$wname=$record->ACC_NO,
							$wname=$record->NET_PAY,
        		 	
					
				];
			}
          //  var_dump($data);
			echo json_encode(['data' => $data]);
    }



public function get_pay_register_details_api() {
    $periodfromdate = $this->input->post('from_date');
    $periodtodate   = $this->input->post('to_date');
    $EB_NO          = $this->input->post('eb_no');

    $rows = $this->Njmallwagesprocess->get_pay_register_details($periodfromdate, $periodtodate, $EB_NO);

    if (!$rows) {
        return $this->output->set_content_type('application/json')
            ->set_output(json_encode(['error' => true, 'message' => 'No data found']));
    }

    $header = [
        'EB_NO'     => $EB_NO,
        'from'      => $periodfromdate,
        'to'        => $periodtodate,
        // adjust these keys to your actual column names from $rows[0]
        'department'=> $rows[0]->department ?? null,
        'emp_code'  => $rows[0]->emp_code ?? null,
    ];

    $timeWage = [];
    $production = [];
    $manual = ['advance' => 0, 'plus_balance' => 0];

    foreach ($rows as $r) {
        // Decide by a flag/column — replace with your real discriminator:
        if (($r->updt_from ?? '') === 'A') {
            $timeWage[] = [
                'occupation' => $r->occupation ?? null,
                'days'       => $r->days ?? null,
                'rate'       => $r->rate ?? null,
                'amount'     => $r->amount ?? null,
            ];
        } elseif (($r->updt_from ?? '') === 'P') {
            $production[] = [
                'quality_code' => $r->quality_code ?? null,
                'production'   => $r->production ?? null,
                'rate'         => $r->prod_rate ?? $r->rate ?? null,
                'amount'       => $r->prod_amount ?? $r->amount ?? null,
            ];
        }

        // If manual values are per row, aggregate the latest/non-null:
        if (isset($r->advance))      $manual['advance'] = $r->advance;
        if (isset($r->plus_balance)) $manual['plus_balance'] = $r->plus_balance;
    }

    $out = compact('header', 'timeWage', 'production', 'manual');

    return $this->output->set_content_type('application/json')
        ->set_output(json_encode($out));
}



public function njmcntpayexceldownloadp() {
		// Create a new Spreadsheet object
		$periodfromdate= $this->input->get('periodfromdate');
		$periodtodate = $this->input->get('periodtodate');
		$att_payschm = $this->input->get('att_payschm');

        $startdate= substr($periodfromdate,8,2).'-'.substr($periodfromdate,5,2).'-'.substr($periodfromdate,0,4);
        $enddate= substr($periodtodate,8,2).'-'.substr($periodtodate,5,2).'-'.substr($periodtodate,0,4);

    echo $periodfromdate.'=='.$periodtodate.'=='.$att_payschm;
   // $result = $this->Njmallwagesprocess->njmcntwagesexceldownload($periodfromdate,$periodtodate,$att_payschm);
//	$result = $this->Njmallwagesprocess->njmcntpayexceldownload($periodfromdate,$periodtodate,$att_payschm  );
	$result = $this->Njmallwagesprocess->njmnallpayregister($att_payschm ,$periodfromdate,$periodtodate );
}


 
public function njmcntpayexceldownload() {
		// Create a new Spreadsheet object
		$periodfromdate= $this->input->get('periodfromdate');
		$periodtodate = $this->input->get('periodtodate');
		$att_payschm = $this->input->get('att_payschm');

        $startdate= substr($periodfromdate,8,2).'-'.substr($periodfromdate,5,2).'-'.substr($periodfromdate,0,4);
        $enddate= substr($periodtodate,8,2).'-'.substr($periodtodate,5,2).'-'.substr($periodtodate,0,4);

    echo $periodfromdate.'=='.$periodtodate.'=='.$att_payschm;
   // $result = $this->Njmallwagesprocess->njmcntwagesexceldownload($periodfromdate,$periodtodate,$att_payschm);
//	$result = $this->Njmallwagesprocess->njmcntpayexceldownload($periodfromdate,$periodtodate,$att_payschm  );
	$result = $this->Njmallwagesprocess->njmnallpayregister($att_payschm ,$periodfromdate,$periodtodate );


//public function njmnewbdlpayslipprint($att_payschm,$periodfromdate,$periodtodate) {


//    var_dump($result);
//    $query = $this->Njmallwagesprocess->getcntwagespayslip($periodfromdate,$periodtodate,$att_payschm);
    if (!$result) {
        echo "Query failed.";
        return;
    }
    
if (empty($result) || count($result) == 0) {
    echo "No data found.";
    return;
}
        $query = $result;
//    echo "Total Rows: " . $result->num_rows() . "<br>";

	$spreadsheet = new Spreadsheet();
	$sheet = $spreadsheet->getActiveSheet();

						$borderStyle = [
							'borders' => [
								'allBorders' => [
									'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
								],
							],
						];
						$boldFontStyle = [
							'font' => [
								'bold' => true,
								'size' => 12,
							],
						];
$cmphd='';
$hdline='';
if ($att_payschm==163) {
    $cmphd='CONTRACTOR WORKERS';
    $hdline='CONTRACTOR WORKERS PAY REGISTER FOR THE PERIOD FROM '.$startdate.' TO '.$enddate;
}
if ($att_payschm==164) {
    $cmphd='LIMELIGHT COMM PVT LTD';
    $hdline='MAIN PAY ROLL PAY REGISTER FOR THE PERIOD FROM '.$startdate.' TO '.$enddate;
}
if ($att_payschm==166) {
    $cmphd='LIMELIGHT COMM PVT LTD';
    $hdline='CLERK,MEDICAL.MOTOR & OFB PAY REGISTER FOR THE PERIOD FROM '.$startdate.' TO '.$enddate;
}
if ($att_payschm==167) {
    $cmphd='LIMELIGHT COMM PVT LTD';
    $hdline='RETIRED PAY REGISTER FOR THE PERIOD FROM '.$startdate.' TO '.$enddate;
}
if ($att_payschm==169) {
    $cmphd='LIMELIGHT COMM PVT LTD';
    $hdline='NEW BUDLI PAY REGISTER FOR THE PERIOD FROM '.$startdate.' TO '.$enddate;
}



    $sheet->setCellValue('A1', $cmphd);
    $sheet->setCellValue('A2', $hdline);


    

$N=1;
$b='A'.$N.':m'.$N;
$sheet->mergeCells($b);
$N++;
$b='A'.$N.':m'.$N;
$sheet->mergeCells($b);


$rowNumber = 3;

$tpay=0;
$columnNames = array_keys($result[0]);
echo "Column Names: " . implode(", ", $columnNames) . "<br>";
$col = 'A';
    foreach ($columnNames as $column) {
        $sheet->setCellValue($col.'3', $column);
        $col++;
    }

$colstot= $col;
$rowNumber = 4;

foreach ($result as $row) {
    $col = 'A';
    $colno = 0;

    foreach ($row as $cell) {
        if ($colno <= 7) {
            $sheet->setCellValueExplicit($col . $rowNumber, $cell, DataType::TYPE_STRING);
        } else {
            $sheet->setCellValue($col . $rowNumber, $cell);
            $tpay=$tpay+$row->NET_PAY;
        }

        $col++;     // move outside
        $colno++;   // move outside
    }

    $rowNumber++;
}

$rn='F'.$rowNumber;
$sheet->setCellValue($rn, $tpay);

    foreach (range('A', 'N') as $col) {
        $sheet->getColumnDimension($col)->setAutoSize(true);
    }

 
						 $sheet->getColumnDimension('A')->setWidth(9);
							$centerAlignment = $sheet->getStyle('A1:a2')->getAlignment();
							$centerAlignment->setHorizontal(Alignment::HORIZONTAL_CENTER);
							$centerAlignment = $sheet->getStyle('A3:f3')->getAlignment();
							$centerAlignment->setHorizontal(Alignment::HORIZONTAL_CENTER);
							
							// Apply font style to cell A1
							$sheet->getStyle('A1:a2')->applyFromArray($boldFontStyle);
							
						
							
							$sheet->getStyle('A1:f1')->applyFromArray($borderStyle);
							$sheet->getStyle('A2:f2')->applyFromArray($borderStyle);
	 

//$sheet = $spreadsheet->createSheet($index);

// Rename the sheet
$sheet->setTitle('Pay Register');

//$sheet = $spreadsheet->createSheet(1);
//$sheet->setTitle('Pay Components Info');

	
    $filename="Payregister_".$att_payschm.'_'.$enddate.'.xlsx';

// After generating the Excel file
$excelUrl = 'path_to_generated_excel_file.xlsx'; // Change this to the actual URL

// Return the URL along with other response data
echo json_encode(array('success' => true, 'savedata' => $savedata, 'excelUrl' => $excelUrl));

	// Set headers for Excel file download
//	ob_clean();
	header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
//	header('Content-Disposition: attachment;filename="your_excel_file.xlsx"');

//		header('Content-Type: application/vnd.ms-excel');
	header('Content-Disposition: attachment;filename='.$filename);
	header('Cache-Control: max-age=0');
	ob_clean();

	// Save the Excel file to output stream
	$writer = new Xlsx($spreadsheet);
	$writer->save('php://output');
	// Save the Excel file to output stream
//	$writer = new Xlsx($spreadsheet);
//	$writer->save('php://output');
	
		// Terminate the script to prevent further output
		exit;



	}
	

/// for pf & esi report
public function njmpfesi() {
		// Create a new Spreadsheet object
		$periodfromdate= $this->input->get('periodfromdate');
		$periodtodate = $this->input->get('periodtodate');
		$att_payschm = $this->input->get('att_payschm');

        $startdate= substr($periodfromdate,8,2).'-'.substr($periodfromdate,5,2).'-'.substr($periodfromdate,0,4);
        $enddate= substr($periodtodate,8,2).'-'.substr($periodtodate,5,2).'-'.substr($periodtodate,0,4);

    echo $periodfromdate.'=='.$periodtodate.'=='.$att_payschm;
   // $result = $this->Njmallwagesprocess->njmcntwagesexceldownload($periodfromdate,$periodtodate,$att_payschm);
//	$result = $this->Njmallwagesprocess->njmcntpayexceldownload($periodfromdate,$periodtodate,$att_payschm  );
	$result = $this->Njmallwagesprocess->njmnallpayregister($att_payschm ,$periodfromdate,$periodtodate );


//public function njmnewbdlpayslipprint($att_payschm,$periodfromdate,$periodtodate) {


//    var_dump($result);
//    $query = $this->Njmallwagesprocess->getcntwagespayslip($periodfromdate,$periodtodate,$att_payschm);
    if (!$result) {
        echo "Query failed.";
        return;
    }
    
if (empty($result) || count($result) == 0) {
    echo "No data found.";
    return;
}
        $query = $result;
//    echo "Total Rows: " . $result->num_rows() . "<br>";

	$spreadsheet = new Spreadsheet();
	$sheet = $spreadsheet->getActiveSheet();

						$borderStyle = [
							'borders' => [
								'allBorders' => [
									'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
								],
							],
						];
						$boldFontStyle = [
							'font' => [
								'bold' => true,
								'size' => 12,
							],
						];
$cmphd='';
$hdline='';
if ($att_payschm==163) {
    $cmphd='CONTRACTOR WORKERS';
    $hdline='CONTRACTOR WORKERS PAY REGISTER FOR THE PERIOD FROM '.$startdate.' TO '.$enddate;
}
if ($att_payschm==164) {
    $cmphd='LIMELIGHT COMM PVT LTD';
    $hdline='MAIN PAY ROLL PAY REGISTER FOR THE PERIOD FROM '.$startdate.' TO '.$enddate;
}
if ($att_payschm==166) {
    $cmphd='LIMELIGHT COMM PVT LTD';
    $hdline='CLERK,MEDICAL.MOTOR & OFB PAY REGISTER FOR THE PERIOD FROM '.$startdate.' TO '.$enddate;
}
if ($att_payschm==167) {
    $cmphd='LIMELIGHT COMM PVT LTD';
    $hdline='RETIRED PAY REGISTER FOR THE PERIOD FROM '.$startdate.' TO '.$enddate;
}
if ($att_payschm==169) {
    $cmphd='LIMELIGHT COMM PVT LTD';
    $hdline='NEW BUDLI PAY REGISTER FOR THE PERIOD FROM '.$startdate.' TO '.$enddate;
}



    $sheet->setCellValue('A1', $cmphd);
    $sheet->setCellValue('A2', $hdline);


    

$N=1;
$b='A'.$N.':m'.$N;
$sheet->mergeCells($b);
$N++;
$b='A'.$N.':m'.$N;
$sheet->mergeCells($b);


$rowNumber = 3;

$tpay=0;
$columnNames = array_keys($result[0]);
echo "Column Names: " . implode(", ", $columnNames) . "<br>";
$col = 'A';
    foreach ($columnNames as $column) {
        $sheet->setCellValue($col.'3', $column);
        $col++;
    }

$colstot= $col;
$rowNumber = 4;

foreach ($result as $row) {
    $col = 'A';
    $colno = 0;

    foreach ($row as $cell) {
        if ($colno <= 7) {
            $sheet->setCellValueExplicit($col . $rowNumber, $cell, DataType::TYPE_STRING);
        } else {
            $sheet->setCellValue($col . $rowNumber, $cell);
            $tpay=$tpay+$row->NET_PAY;
        }

        $col++;     // move outside
        $colno++;   // move outside
    }

    $rowNumber++;
}

$rn='F'.$rowNumber;
$sheet->setCellValue($rn, $tpay);

    foreach (range('A', 'N') as $col) {
        $sheet->getColumnDimension($col)->setAutoSize(true);
    }

 
						 $sheet->getColumnDimension('A')->setWidth(9);
							$centerAlignment = $sheet->getStyle('A1:a2')->getAlignment();
							$centerAlignment->setHorizontal(Alignment::HORIZONTAL_CENTER);
							$centerAlignment = $sheet->getStyle('A3:f3')->getAlignment();
							$centerAlignment->setHorizontal(Alignment::HORIZONTAL_CENTER);
							
							// Apply font style to cell A1
							$sheet->getStyle('A1:a2')->applyFromArray($boldFontStyle);
							
						
							
							$sheet->getStyle('A1:f1')->applyFromArray($borderStyle);
							$sheet->getStyle('A2:f2')->applyFromArray($borderStyle);
	 

//$sheet = $spreadsheet->createSheet($index);

// Rename the sheet
$sheet->setTitle('Pay Register');

//$sheet = $spreadsheet->createSheet(1);
//$sheet->setTitle('Pay Components Info');

	
    $filename="Payregister_".$att_payschm.'_'.$enddate.'.xlsx';

// After generating the Excel file
$excelUrl = 'path_to_generated_excel_file.xlsx'; // Change this to the actual URL

// Return the URL along with other response data
echo json_encode(array('success' => true, 'savedata' => $savedata, 'excelUrl' => $excelUrl));

	// Set headers for Excel file download
//	ob_clean();
	header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
//	header('Content-Disposition: attachment;filename="your_excel_file.xlsx"');

//		header('Content-Type: application/vnd.ms-excel');
	header('Content-Disposition: attachment;filename='.$filename);
	header('Cache-Control: max-age=0');
	ob_clean();

	// Save the Excel file to output stream
	$writer = new Xlsx($spreadsheet);
	$writer->save('php://output');
	// Save the Excel file to output stream
//	$writer = new Xlsx($spreadsheet);
//	$writer->save('php://output');
	
		// Terminate the script to prevent further output
		exit;



	}
	






public function njmcontpayregisdisp1() {
//    $this->load->model('Loan_adv_model');

    $fromDate = $this->input->post('fromDate');
    $toDate = $this->input->post('toDate');
	$periodfromdate = $this->input->post('periodfromdate');
    $periodtodate = $this->input->post('periodtodate');
    $contractorName = $this->input->post('contractorName');
    $reportType = $this->input->post('reportType');

    $mccodes = $this->Njmallwagesprocess->njmcontpayregisdisp($periodfromdate,$periodtodate, $contractorName, $reportType);

    $data = [];
    foreach ($mccodes as $record) {
        $data[] = [
          			$Department=$record->Department,
					$EB_NO=$record->EB_NO,	//1
					$wname=$record->wname, //2
					$Rate=$record->Rate, //3
					$Days=$record->Days,	//4
					$Amount=$record->Amount, //5
					$Basic=$record->Basic, //6
					$HRA=$record->HRA, //7
					$Conveyance=$record->Conveyance, //8
					$Other_Allowance=$record->Other_Allowance, //9
					$Uniform_Allowance=$record->Uniform_Allowance, //10
					$Medical_Allowance=$record->Medical_Allowance, //11	
					$Telephone=$record->Telephone, //12
					$Education=	$record->Education, // 13
					$Training=$record->Training, //14
					$GROSS1=$record->GROSS1, //15
					$PF_Employer=$record->PF_Employer, //16
					$ESI_Employer=$record->ESI_Employer, //17
					$PF_Employee=$record->PF_Employee, //18
					$ESI_Employee=$record->ESI_Employee, //19
					$GROSS2=$record->GROSS2,//20
					$Advance=$record->Advance, //22
					$TA=$record->TA, //23
					$Plus_Balance=$record->Plus_Balance, //24
					$NET=$record->NET //25*
        ];
    }

    echo json_encode(['data' => $data]);
	
if (json_last_error() !== JSON_ERROR_NONE) {
        echo json_last_error_msg();
    }

	// return;
}

public function njmcontpayregisdisp2()
{
    $periodfromdate = $this->input->post('periodfromdate');
    $periodtodate   = $this->input->post('periodtodate');
    $contractorName = $this->input->post('contractorName');
    $reportType     = $this->input->post('reportType');

    $rows = $this->Njmallwagesprocess->njmcontpayregisdisp(
        $periodfromdate, $periodtodate, $contractorName, $reportType
    );

    $cols = [
        'Department','EB_NO','wname','Rate','Days','Amount','Basic','HRA','Conveyance',
        'Other_Allowance','Uniform_Allowance','Medical_Allowance','Telephone','Education',
        'Training','GROSS1','PF_Employer','ESI_Employer','PF_Employee','ESI_Employee',
        'GROSS2','Advance','TA','Plus_Balance','NET'
    ];

    $data = array_map(function($r) use ($cols) {
        return array_map(function($c) use ($r) {
            return isset($r->$c) ? $r->$c : null;
        }, $cols);
    }, $rows);

    $this->output
         ->set_content_type('application/json')
         ->set_output(json_encode(['data' => $data]));
}


public function njmcontpayregisdisp()
{
    $periodfromdate = $this->input->post('periodfromdate');
    $periodtodate   = $this->input->post('periodtodate');
    $contractorName = $this->input->post('contractorName');
    $reportType     = $this->input->post('reportType');

    $rows = $this->Njmallwagesprocess->njmcontpayregisdisp(
        $periodfromdate, $periodtodate, $contractorName, $reportType
    );

    // Convert result objects -> associative arrays
    $rowsArr = array_map('get_object_vars', $rows);

    // Build dynamic columns from the first row's keys
    $columns = [];
    if (!empty($rowsArr)) {
        foreach (array_keys($rowsArr[0]) as $key) {
            $columns[] = [
                'data'  => $key,                                   // field to read from each row
                'title' => ucwords(str_replace('_',' ', $key)),    // header text
            ];
        }
    }

    $this->output
        ->set_content_type('application/json')
        ->set_output(json_encode([
            'columns' => $columns,
            'data'    => $rowsArr,   // keep rows as associative arrays
        ]));
}




	public function njmwagesprocessdata() {

		
		$rec_time =  date('Y-m-d H:i:s');
		$comp = $this->session->userdata('companyId');
		$periodfromdate = $this->input->post('periodfromdate');
		$periodtodate = $this->input->post('periodtodate');
		$att_payschm = $this->input->post('att_payschm');
	    $stat=3;
		$active=1;
		$userid=$this->session->userdata('userid');

		$mcc=$this->Njmallwagesprocess->njmwagesprocessdata($periodfromdate,$periodtodate,$att_payschm);


 	
	 

			$response = array(
		'success' => true,
		'savedata'=> 'saved'
	);
	


	echo json_encode($response);



//$this->exceldownload();


	}



	public function njmwrkvardupdate() {

		
		$rec_time =  date('Y-m-d H:i:s');
		$comp = $this->session->userdata('companyId');
		$periodfromdate = $this->input->post('periodfromdate');
		$periodtodate = $this->input->post('periodtodate');
		$att_payschm = $this->input->post('att_payschm');
	    $stat=3;
		$active=1;
		$userid=$this->session->userdata('userid');

		$mcc=$this->Njmallwagesprocess->njmwrkvardupdate($periodfromdate,$periodtodate,$att_payschm);


 	
	 

			$response = array(
		'success' => true,
		'savedata'=> 'saved'
	);
	


	echo json_encode($response);



//$this->exceldownload();


	}


public function njmattwithpayschmexceldata() {
		// Create a new Spreadsheet object
		$periodfromdate= $this->input->get('periodfromdate');
		$periodtodate = $this->input->get('periodtodate');
		$att_payschm = $this->input->get('att_payschm');

    echo $periodfromdate.'=='.$periodtodate.'=='.$att_payschm;
    $result = $this->Njmallwagesprocess->njmattwithpayschmexceldata($periodfromdate,$periodtodate,$att_payschm);
//var_dump($result);
//    $query = $this->Njmallwagesprocess->getcntwagespayslip($periodfromdate,$periodtodate,$att_payschm);
    if (!$result) {
        echo "Query failed.";
        return;
    }
    
    // Check if the query returned any results
   //   
//$result=$this->Njmallwagesprocess->njmcntwagesexceldownload($periodfromdate,$periodtodate,$att_payschm);
  //  var_dump($query);

//  var_dump($result);
if (empty($result) || count($result) == 0) {
    echo "No data found.";
    return;
}
        $query = $result;
//    echo "Total Rows: " . $result->num_rows() . "<br>";

				$spreadsheet = new Spreadsheet();
	$sheet = $spreadsheet->getActiveSheet();

    $sdate=$periodtodate;
	//	$sdate='2024-01-01';

//echo $this->db->last_query();
// Fetching the result set as an array of arrays

//$results = $query->result_array();
//$columns = $query->list_fields();
//$numCols = $query->num_fields();

//$result = $query->result_array();


$columnNames = array_keys($result[0]);
echo "Column Names: " . implode(", ", $columnNames) . "<br>";
$col = 'A';
    foreach ($columnNames as $column) {
        $sheet->setCellValue($col.'1', $column);
        $col++;
    }

  /*   $rowNumber = 2;
    $colno=0;
    foreach ($result as $row) {
    $colno=0;
        $col = 'A';
        foreach ($row as $cell) {
            if ($colno<=0) {
              //  $sheet->setCellValueExplicitByColumnAndRow($colIndex, $rowIndex, $value, DataType::TYPE_STRING);
//                $sheet->setCellValue($col.$rowNumber, $cell, DataType::TYPE_STRING);
                $sheet->setCellValueExplicit($col.$rowNumber, $cell, DataType::TYPE_STRING);

            } else {    
                $sheet->setCellValue($col.$rowNumber, $cell);
            $col++;
            $colno++;
            }       
        }
        $rowNumber++;
    }
 */

$rowNumber = 2;

foreach ($result as $row) {
    $col = 'A';
    $colno = 0;

    foreach ($row as $cell) {
        if ($colno <= 0) {
            $sheet->setCellValueExplicit($col . $rowNumber, $cell, DataType::TYPE_STRING);
        } else {
            $sheet->setCellValue($col . $rowNumber, $cell);
        }

        $col++;     // move outside
        $colno++;   // move outside
    }

    $rowNumber++;
}



//$sheet = $spreadsheet->createSheet($index);

// Rename the sheet
$sheet->setTitle('Checklist');

$sheet = $spreadsheet->createSheet(1);
$sheet->setTitle('Pay Components Info');

    $filename="ATTwithpayscheme_".$sdate.'.xlsx';

// After generating the Excel file
$excelUrl = 'path_to_generated_excel_file.xlsx'; // Change this to the actual URL

// Return the URL along with other response data
echo json_encode(array('success' => true, 'savedata' => $savedata, 'excelUrl' => $excelUrl));

	// Set headers for Excel file download
//	ob_clean();
	header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
//	header('Content-Disposition: attachment;filename="your_excel_file.xlsx"');

//		header('Content-Type: application/vnd.ms-excel');
	header('Content-Disposition: attachment;filename='.$filename);
	header('Cache-Control: max-age=0');
	ob_clean();

	// Save the Excel file to output stream
	$writer = new Xlsx($spreadsheet);
	$writer->save('php://output');
	// Save the Excel file to output stream
//	$writer = new Xlsx($spreadsheet);
//	$writer->save('php://output');
	
		// Terminate the script to prevent further output
		exit;



	}
	

public function njmproductionchecklist() {
		// Create a new Spreadsheet object
		$periodfromdate= $this->input->get('periodfromdate');
		$periodtodate = $this->input->get('periodtodate');
		$att_payschm = $this->input->get('att_payschm');

    echo $periodfromdate.'=='.$periodtodate.'=='.$att_payschm;
    $result = $this->Njmallwagesprocess->njmproductionchecklist($periodfromdate,$periodtodate,$att_payschm);
//var_dump($result);
//    $query = $this->Njmallwagesprocess->getcntwagespayslip($periodfromdate,$periodtodate,$att_payschm);
    if (!$result) {
        echo "Query failed.";
        return;
    }
    
    // Check if the query returned any results
   //   
//$result=$this->Njmallwagesprocess->njmcntwagesexceldownload($periodfromdate,$periodtodate,$att_payschm);
  //  var_dump($query);

//  var_dump($result);
if (empty($result) || count($result) == 0) {
    echo "No data found.";
    return;
}
        $query = $result;
//    echo "Total Rows: " . $result->num_rows() . "<br>";

				$spreadsheet = new Spreadsheet();
	$sheet = $spreadsheet->getActiveSheet();

    $sdate=$periodtodate;
	//	$sdate='2024-01-01';

//echo $this->db->last_query();
// Fetching the result set as an array of arrays

//$results = $query->result_array();
//$columns = $query->list_fields();
//$numCols = $query->num_fields();

//$result = $query->result_array();





$columnNames = array_keys($result[0]);
echo "Column Names: " . implode(", ", $columnNames) . "<br>";
$col = 'A';
    foreach ($columnNames as $column) {
        $sheet->setCellValue($col.'1', $column);
        $col++;
    }

  /*   $rowNumber = 2;
    $colno=0;
    foreach ($result as $row) {
    $colno=0;
        $col = 'A';
        foreach ($row as $cell) {
            if ($colno<=0) {
              //  $sheet->setCellValueExplicitByColumnAndRow($colIndex, $rowIndex, $value, DataType::TYPE_STRING);
//                $sheet->setCellValue($col.$rowNumber, $cell, DataType::TYPE_STRING);
                $sheet->setCellValueExplicit($col.$rowNumber, $cell, DataType::TYPE_STRING);

            } else {    
                $sheet->setCellValue($col.$rowNumber, $cell);
            $col++;
            $colno++;
            }       
        }
        $rowNumber++;
    }
 */

$rowNumber = 2;

foreach ($result as $row) {
    $col = 'A';
    $colno = 0;

    foreach ($row as $cell) {
        if ($colno <= 0) {
            $sheet->setCellValueExplicit($col . $rowNumber, $cell, DataType::TYPE_STRING);
        } else {
            $sheet->setCellValue($col . $rowNumber, $cell);
        }

        $col++;     // move outside
        $colno++;   // move outside
    }

    $rowNumber++;
}



//$sheet = $spreadsheet->createSheet($index);

// Rename the sheet
$sheet->setTitle('Checklist');

$sheet = $spreadsheet->createSheet(1);
$sheet->setTitle('Pay Components Info');

    $filename="prod_checklist_".$sdate.'.xlsx';

// After generating the Excel file
$excelUrl = 'path_to_generated_excel_file.xlsx'; // Change this to the actual URL

// Return the URL along with other response data
echo json_encode(array('success' => true, 'savedata' => $savedata, 'excelUrl' => $excelUrl));

	// Set headers for Excel file download
//	ob_clean();
	header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
//	header('Content-Disposition: attachment;filename="your_excel_file.xlsx"');

//		header('Content-Type: application/vnd.ms-excel');
	header('Content-Disposition: attachment;filename='.$filename);
	header('Cache-Control: max-age=0');
	ob_clean();

	// Save the Excel file to output stream
	$writer = new Xlsx($spreadsheet);
	$writer->save('php://output');
	// Save the Excel file to output stream
//	$writer = new Xlsx($spreadsheet);
//	$writer->save('php://output');
	
		// Terminate the script to prevent further output
		exit;



	}



       public function njmlinehrschecklist() {
        	$periodfromdate= $this->input->get('periodfromdate');
		$periodtodate = $this->input->get('periodtodate');
		$att_payschm = $this->input->get('att_payschm');

        $mccodes = $this->Njmallwagesprocess->njmlinehrschecklist($periodfromdate,$periodtodate);
        $this->load->library('fpdf_lib');
//	$pdf = new \FPDF();
//var_dump($mccodes);
        $pdf = $this->fpdf_lib; 
        $y=5;
        $pdf->AddPage('P', 'A4');
                        $mnth = date("M'Y", strtotime($periodtodate));

                        

        $y=$y+1;
        $kn=1;
        $pgn=1;
        $totamt=0;
        $tvard=0;
        $totslp=0;
        $frdt=substr($periodfromdate,8,2).'-'.substr($periodfromdate,5,2).'-'.substr($periodfromdate,0,4);
        $todt=substr($periodtodate,8,2).'-'.substr($periodtodate,5,2).'-'.substr($periodtodate,0,4);

            $pdf->SetFont('Arial', 'B', 8);
            $x=5;
            $pdf->SetXY($x, $y);
            $pdf->SetXY($x, $y);
            $pdf->Cell(100, 4, 'LIMELIGHT COMM PVT LTD');
            $y=$y+5;
            $pdf->SetXY($x, $y);
            $x=5;
            $pdf->Cell(15, 4, 'LINE HOURS STATEMENT FOR THE PERIOD FROM :'.$frdt.' TO '.$todt);
            $y =$y+6;
            $x=5;
            $pdf->SetXY($x, $y);
            $pdf->Line(4, $y, 200, $y);   // from (10,y) to (200,y)
            $y=$y+5;
            $x=5;
            $pdf->SetXY($x, $y);
            $pdf->Cell(20, 4, 'LINE NO/GROUP');
            $x=$x+25;
            $pdf->SetXY($x, $y);        
            $pdf->Cell(15, 4, 'SARD HELP');
            $x=$x+20;
            $pdf->SetXY($x, $y);
            $pdf->Cell(20, 4, 'RUNNING HRS');
            $x=$x+25;
            $pdf->SetXY($x, $y);
            $pdf->Cell(20, 4, 'LOST HOURS');
            $x=$x+25;
            $pdf->SetXY($x, $y);
            $pdf->Cell(20, 4, 'NO OF LOOMS');
            $x=$x+25;
            $pdf->SetXY($x, $y);
            $pdf->Cell(18, 4, 'PRODUCTION');
            $x=$x+25;
            $pdf->SetXY($x, $y);
            $pdf->Cell(18, 4, 'AMOUNT');
            $x=$x+25;
            $pdf->SetXY($x, $y);
            $pdf->Cell(18, 4, 'RATE');
            $y =$y+6;
            $x=5;
            $pdf->SetXY($x, $y);
            $pdf->Line(4, $y, 200, $y);   // from (10,y) to (200,y)
            $y =$y+4;
        foreach ($mccodes as $row) {
            $ln=$row['line_no']. '  |  '.$row['wgroup'];
            $sh=$row['sard_helper'];
            $rh=$row['running_hours'];
            $lh=$row['lost_hours'];
            $nl=$row['no_of_looms'];
            $pr=$row['quantity'];
            $am=$row['pamount'];
            $rt=0;
//            echo $am;
            if ($am>0 and $rh>0 and $lh>0 and $nl>0 ) {
                  $rt=round($am/((($rh-$lh)*$nl)/2),6);
                if ($sh=='S')
                {
                    $rt=round($rt*1.3,6);
                }
            }
                $x=5;
            $pdf->SetXY($x, $y);
            $pdf->Cell(20, 4, $ln);
            $x=$x+25;
            $pdf->SetXY($x, $y);        
            $pdf->Cell(20, 4, $sh);
            $x=$x+25;
            $pdf->SetXY($x, $y);
            $pdf->Cell(20, 4, $rh);
            $x=$x+25;
            $pdf->SetXY($x, $y);
            $pdf->Cell(20, 4, $lh);
            $x=$x+25;
            $pdf->SetXY($x, $y);
            $pdf->Cell(20, 4, $nl);
            $x=$x+25;
            $pdf->SetXY($x, $y);
            $pdf->Cell(18, 4, $pr);
            $x=$x+25;
            $pdf->SetXY($x, $y);
            $pdf->Cell(18, 4, $am);
            $x=$x+25;
            $pdf->SetXY($x, $y);
            $pdf->Cell(18, 4, $rt);
            $y =$y+6;
            $x=5;
            $pdf->SetXY($x, $y);
            $pdf->Line(4, $y, 200, $y);   // from (10,y) to (200,y)
            $y =$y+4;
            if ($y>270) {
                $pdf->AddPage('P', 'A4');
                $y=5;
                   $pdf->SetFont('Arial', 'B', 8);
            $x=5;
            $pdf->SetXY($x, $y);
            $pdf->SetXY($x, $y);
            $pdf->Cell(100, 4, 'LIMELIGHT COMM PVT LTD');
            $y=$y+5;
            $pdf->SetXY($x, $y);
            $x=5;
            $pdf->Cell(15, 4, 'LINE HOURS STATEMENT FOR THE PERIOD FROM :'.$frdt.' TO '.$todt);
            $y =$y+6;
            $x=5;
            $pdf->SetXY($x, $y);
            $pdf->Line(4, $y, 200, $y);   // from (10,y) to (200,y)
            $y=$y+5;
            $x=5;
            $pdf->SetXY($x, $y);
            $pdf->Cell(20, 4, 'LINE NO/GROUP');
            $x=$x+25;
            $pdf->SetXY($x, $y);        
            $pdf->Cell(15, 4, 'SARD HELP');
            $x=$x+20;
            $pdf->SetXY($x, $y);
            $pdf->Cell(20, 4, 'RUNNING HRS');
            $x=$x+25;
            $pdf->SetXY($x, $y);
            $pdf->Cell(20, 4, 'LOST HOURS');
            $x=$x+25;
            $pdf->SetXY($x, $y);
            $pdf->Cell(20, 4, 'NO OF LOOMS');
            $x=$x+25;
            $pdf->SetXY($x, $y);
            $pdf->Cell(18, 4, 'PRODUCTION');
            $x=$x+25;
            $pdf->SetXY($x, $y);
            $pdf->Cell(18, 4, 'AMOUNT');
            $x=$x+25;
            $pdf->SetXY($x, $y);
            $pdf->Cell(18, 4, 'RATE');
            $y =$y+6;
            $x=5;
            $pdf->SetXY($x, $y);
            $pdf->Line(4, $y, 200, $y);   // from (10,y) to (200,y)
            $y =$y+4;
     }


        }    

 

        $pdf->Ln();
       $this->fpdf_lib->Output('D', 'linehours_'.$periodfromdate.'_'.$periodtodate.'.pdf'); // 'D' = force download



       }     

	public function updtrates() {

		$rec_time =  date('Y-m-d H:i:s');
		$comp = $this->session->userdata('companyId');
		$periodfromdate = $this->input->post('periodfromdate');
		$ebnos = $this->input->post('ebnos');
		$updtrate = $this->input->post('updtrate');
        $shiftoff = $this->input->post('shiftoff');
        $ebid=0;
        $nm='';
        
        $sql="select eb_id,concat(wm.worker_name,' ',ifnull(middle_name,''),' ',ifnull(last_name,'')) worker_name 
        from worker_master wm where eb_no='$ebnos'";
        $query = $this->db->query($sql);
        if ($query->num_rows() > 0) {
            $row = $query->row();   // get first row as object
            $ebid = $row->eb_id;
            $nm=$row->worker_name;

        }
        $prvrate=0;
        $sql="select * from EMPMILL12.tbl_daily_cash_outsider_payment_production tdcopp where prod_date='$periodfromdate'
        and prod_shift='$shiftoff' and eb_id=$ebid";
        $query = $this->db->query($sql);
        if ($query->num_rows() > 0) {
            $row = $query->row();   // get first row as object
            $prvrate = $row->prod_rate;
        }
        
        if ($ebid>0 and $prvrate>0) {
            
            $sql="update EMPMILL12.outsider_rate_approve set is_active=0 where app_date='$periodfromdate' and app_shift='$shiftoff' 
            and eb_id=$ebid";
            $this->db->query($sql);
         
            $sql="insert into EMPMILL12.outsider_rate_approve (eb_id,rate_approve,is_active,app_date,app_shift) values ( 
            $ebid,$updtrate,1,'$periodfromdate','$shiftoff')"; 
            
            $this->db->query($sql);
         }    
        
        $success=false;
        $saved='Not Saved';
        if ($ebid>0 and $prvrate>0) {
            $success=true;
            $saved='Saved data for '.$ebnos.'  '.$nm;
        } else {
            if ($ebid<=0 ) {
                $saved="No Master Data for ".$ebnos;
            }
            if ($prvrate<=0 and $ebid>0) {
                $saved="No Production Data for ".$ebnos;
            }
                
        }

		$response = array(
		'success' => $success,
		'savedata'=> $saved
	);
	


	echo json_encode($response);




        }    





public function njmwgsbrkexlwrite($result,$periodfromdate,$periodtodate,$sheetname,$typ) {

						$spreadsheet = new Spreadsheet();
						$sheet = $spreadsheet->getActiveSheet();
						$sdate=substr($periodfromdate,8,2).'-'.substr($periodfromdate,5,2).'-'.substr($periodfromdate,0,4);
						$ldate=substr($periodtodate,8,2).'-'.substr($periodtodate,5,2).'-'.substr($periodtodate,0,4);
                        $mccodes = $result;
                          $centerAlignment = $sheet->getStyle('A1:f2')->getAlignment();
						$sheet->getPageMargins()->setTop(.25);
						$sheet->getPageMargins()->setRight(0.25);
						$sheet->getPageMargins()->setLeft(0.25);
						$sheet->getPageMargins()->setBottom(0.25);
						
						$borderStyle = [
							'borders' => [
								'allBorders' => [
									'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
								],
							],
						];
						$boldFontStyle = [
							'font' => [
								'bold' => true,
								'size' => 12,
							],
						];
//						EB.NO	NAME	ACCOUNT NO	IFSC NO	NET PAY	
                        $mnth = date('F Y', strtotime($periodtodate));
                        if ($typ==0) {
                        $hed1='WAGES BREAKUP SUMMARY FOR THE MONTH OF '.$mnth;
                        $sheet->setCellValue('A1', 'NELLIMARLA JUTE MILLS CO Ltd');
                        $sheet->setCellValue('A2', $hed1);
                        $sheet->setCellValue('A3', 'EB No');
						$sheet->setCellValue('B3', 'Name');
						$sheet->setCellValue('C3', 'ACCOUNT NO');
						$sheet->setCellValue('D3', 'IFSC CODE');
						$sheet->setCellValue('E3', 'NET PAY');
						$sheet->setCellValue('F3', 'BANK');
						$N=1;	
						$b='A'.$N.':f'.$N;
						$sheet->mergeCells('A1:f1');
						$N=2;	
						$b='A'.$N.':f'.$N;
						$sheet->mergeCells('A2:f2');
					  //$objPHPExcel->getActiveSheet($c)->mergeCells($b);
						$n=4;
						$no=1;
						$totamt=0;
						$tothrs=0;
						foreach ($mccodes as $row) {
								$cln='A'.$n;
								$rw=$row->eb_no; 
								$sheet->setCellValue($cln, $rw);
								$sheet->getStyle($cln)->applyFromArray($borderStyle);
								$cll=$cln;
								$sheet->getCell($cll)
								->setValueExplicit(
									$rw,
									\PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING
								);
								$rw=$row->wname; 
								$cln='B'.$n;
								$sheet->setCellValue($cln, $rw);
								$sheet->getStyle($cln)->applyFromArray($borderStyle);
								$cln='c'.$n;
								$rw=$row->bank_acc_no; 
								$sheet->setCellValue($cln, $rw);
								$sheet->getStyle($cln)->applyFromArray($borderStyle);
								$cll=$cln;
								$sheet->getCell($cll)
								->setValueExplicit(
									$rw,
									\PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING
								);
								$cln='d'.$n;
								$rw=$row->ifsc_code; 
								$sheet->setCellValue($cln, $rw);
								$sheet->getStyle($cln)->applyFromArray($borderStyle);
								$cln='e'.$n;
								$rw=$row->NET_PAY; 
								$sheet->setCellValue($cln, $rw);
								$sheet->getStyle($cln)->applyFromArray($borderStyle);
								$cln='f'.$n;
								$rw=substr($row->ifsc_code,0,4); 
								$sheet->setCellValue($cln, $rw);
								$sheet->getStyle($cln)->applyFromArray($borderStyle);
								$n++;
								$totamt=$totamt+$row->NET_PAY;
							}		
							$date = date('d/M/Y');
							$cln='a'.$n;
							$sheet->getStyle($cln)->applyFromArray($borderStyle);
							$cln='c'.$n;
							$sheet->setCellValue($cln, 'Total');
							$cln='d'.$n;
							$cln='e'.$n;
							$sheet->setCellValue($cln, $totamt);
							$clnr='a'.$n.':'.'f'.$n;
							$sheet->getStyle($clnr)->applyFromArray($boldFontStyle);
						
					
							$sheet->getColumnDimension('A')->setWidth(9);
							$sheet->getColumnDimension('B')->setWidth(30);
							$sheet->getColumnDimension('C')->setWidth(25);
							$sheet->getColumnDimension('d')->setWidth(25);
							$sheet->getColumnDimension('e')->setWidth(15);
							$sheet->getColumnDimension('f')->setWidth(15);
							$centerAlignment = $sheet->getStyle('A1:a2')->getAlignment();
							$centerAlignment->setHorizontal(Alignment::HORIZONTAL_CENTER);
							$centerAlignment = $sheet->getStyle('A3:f3')->getAlignment();
							$centerAlignment->setHorizontal(Alignment::HORIZONTAL_CENTER);
							
							// Apply font style to cell A1
							$sheet->getStyle('A1:a2')->applyFromArray($boldFontStyle);
							
						
							
							$sheet->getStyle('A1:f1')->applyFromArray($borderStyle);
							$sheet->getStyle('A2:f2')->applyFromArray($borderStyle);
							$sheet->getStyle('A3:a3')->applyFromArray($borderStyle);
							$sheet->getStyle('b3:b3')->applyFromArray($borderStyle);
							$sheet->getStyle('c3:c3')->applyFromArray($borderStyle);
							$sheet->getStyle('d3:d3')->applyFromArray($borderStyle);
							$sheet->getStyle('e3:e3')->applyFromArray($borderStyle);
							$sheet->getStyle('f3:f3')->applyFromArray($borderStyle);
					
						$deptname='Bank Sheet';	
						$sheet->setTitle($deptname);
                        }    
					
						
							
						
                        }					


 
// --- Configuration ---
// (Ensure these paths are correct)

 
public function njmwgsbrksummexceldownload()
{
    $this->load->library('session');
    $this->load->helper(array('form', 'url'));
    $this->load->library('upload');

    $periodfromdate = $this->input->post('periodfromdate');
    $periodtodate   = $this->input->post('periodtodate');
    $comp           = $this->session->userdata('companyId');
    $fileupload     =$this->input->post('fileupload');

	$periodtodate=$periodfromdate; 
	$periodfromdate=substr($periodfromdate,0,4).'-'.substr($periodfromdate,5,2).'-01'; 
	//2025-11-30
//echo $periodfromdate.'==='.$periodtodate.'=='.$periodfromdate;

    // Name must match your <input type="file" name="...">
    $fileFieldName = 'fileupload';  // or 'fileupload' – make sure it's the same as in your form

    $config['upload_path']   = './uploads/';
    $config['allowed_types'] = 'csv|xlsx';
    $config['max_size']      = 2048;

    $this->upload->initialize($config);

    if (!$this->upload->do_upload($fileFieldName)) {
        // TEMP: show error (for testing only, remove once OK)
        show_error($this->upload->display_errors());
        return;
    }

    $uploadData = $this->upload->data();   // 👈 this you must use (you used $data and $uploadData mixed)

    // Decide reader by extension
    $arr_file  = explode('.', $uploadData['file_name']);
    $extension = strtolower(end($arr_file));

    if ($extension == 'csv') {
        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Csv();
    } else {
        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
    }




    $spreadsheet = $reader->load($uploadData['full_path']);
    $worksheet   = $spreadsheet->getActiveSheet();

    if (file_exists($uploadData['full_path'])) {
        @unlink($uploadData['full_path']);  // @ to suppress warning if already deleted
    }


    


//new budli

    $mccodes = $this->Njmallwagesprocess->nbdlwgsbrksummary($att_payschm,$periodfromdate,$periodtodate);
    $result=$mccodes;
    $sheetName = 'New Budli';   // change to your sheet name
    $tsheetName = 'New Budli';   // change to your sheet name
    $tsheetName='Final Sheet';
    $sheet = $spreadsheet->getSheetByName($sheetName);
    $targetsheet = $spreadsheet->getSheetByName($tsheetName);

     $tsumCell   = 'A2'; // write total in next row
     $mnyr= date('M Y', strtotime($periodfromdate));
     $formula='ALL MANPOWER PAYMENT SHEET FOR THE MONTH OF '.$mnyr;
     $targetsheet->setCellValue($tsumCell, $formula);

    $highestRow    = $sheet->getHighestRow();
    $highestColumn = $sheet->getHighestColumn();
    $rowNumber = 2;
    $range = "A2:{$highestColumn}{$highestRow}";
    $sheet->removeRow(2, $highestRow-1);  // removes all rows with content
    foreach ($result as $row) {
    $col = 'A';
    $colno = 0;
    foreach ($row as $cell) {
        if ($colno == 20) {
            $sheet->setCellValueExplicit($col . $rowNumber, $cell, DataType::TYPE_STRING);
        } else {
            $sheet->setCellValue($col . $rowNumber, $cell);
        }

        $col++;     // move outside
        $colno++;   // move outside
    }

    $rowNumber++;
}
    $highestRow    = $sheet->getHighestRow();
    $lastRow  = $sheet->getHighestRow();

for ($col = 3; $col <= 20; $col++) {
    $colLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col);
    $sumCell   = $colLetter . ($lastRow + 1); // write total in next row

    // Write the SUM formula
    $sheet->setCellValue($sumCell, "=SUM({$colLetter}2:{$colLetter}{$lastRow})");

}

$lastRow++;
$finalrow=6;
$startrow=1;

for ($col = 3; $col <= 20; $col++) {
    $colLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col);
    $sumCell   = $colLetter . ($lastRow); // write total in next row
    $cl=$col-1;
    $tcolLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($cl);
    $tsumCell   = $tcolLetter . ($finalrow); // write total in next row


    $srcRow = $col+$lastRow ;  // 240, 241, 242...

    $formula = "='New Budli'!{$sumCell}";
    
    echo $formula;
    echo $tsumcell;
    $targetsheet->setCellValue($tsumCell, $formula);


 
}


//main

    $mccodes = $this->Njmallwagesprocess->mainwgsbrksummary($att_payschm,$periodfromdate,$periodtodate);
    $result=$mccodes;
    $sheetName = 'Main Payroll Wages';   // change to your sheet name
    $tsheetName='Final Sheet';
    $sheet = $spreadsheet->getSheetByName($sheetName);
    $targetsheet = $spreadsheet->getSheetByName($tsheetName);
    $highestRow    = $sheet->getHighestRow();
    $highestColumn = $sheet->getHighestColumn();
    $rowNumber = 2;
    $range = "A2:{$highestColumn}{$highestRow}";
    $sheet->removeRow(2, $highestRow-1);  // removes all rows with content
    foreach ($result as $row) {
    $col = 'A';
    $colno = 0;
    foreach ($row as $cell) {
        if ($colno == 20) {
            $sheet->setCellValueExplicit($col . $rowNumber, $cell, DataType::TYPE_STRING);
        } else {
            $sheet->setCellValue($col . $rowNumber, $cell);
        }

        $col++;     // move outside
        $colno++;   // move outside
    }

    $rowNumber++;
}
    $highestRow    = $sheet->getHighestRow();
    $lastRow  = $sheet->getHighestRow();

for ($col = 3; $col <= 20; $col++) {
    $colLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col);
    $sumCell   = $colLetter . ($lastRow + 1); // write total in next row

    // Write the SUM formula
    $sheet->setCellValue($sumCell, "=SUM({$colLetter}2:{$colLetter}{$lastRow})");

}

$lastRow++;
$finalrow=4;
$startrow=1;

for ($col = 3; $col <= 20; $col++) {
    $colLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col);
    $sumCell   = $colLetter . ($lastRow); // write total in next row
    $cl=$col-1;
    $tcolLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($cl);
    $tsumCell   = $tcolLetter . ($finalrow); // write total in next row


    $srcRow = $col+$lastRow ;  // 240, 241, 242...

    $formula = "='".$sheetName."'!{$sumCell}";
    
    echo $formula;
    echo $tsumcell;
    $targetsheet->setCellValue($tsumCell, $formula);


 
}


//retired

    $mccodes = $this->Njmallwagesprocess->rtdwgsbrksummary($att_payschm,$periodfromdate,$periodtodate);
    $result=$mccodes;
    $sheetName = 'Retired';   // change to your sheet name
    $tsheetName='Final Sheet';
    $sheet = $spreadsheet->getSheetByName($sheetName);
    $targetsheet = $spreadsheet->getSheetByName($tsheetName);
    $highestRow    = $sheet->getHighestRow();
    $highestColumn = $sheet->getHighestColumn();
    $rowNumber = 2;
    $range = "A2:{$highestColumn}{$highestRow}";
    $sheet->removeRow(2, $highestRow-1);  // removes all rows with content
    foreach ($result as $row) {
    $col = 'A';
    $colno = 0;
    foreach ($row as $cell) {
        if ($colno == 20) {
            $sheet->setCellValueExplicit($col . $rowNumber, $cell, DataType::TYPE_STRING);
        } else {
            $sheet->setCellValue($col . $rowNumber, $cell);
        }

        $col++;     // move outside
        $colno++;   // move outside
    }

    $rowNumber++;
}
    $highestRow    = $sheet->getHighestRow();
    $lastRow  = $sheet->getHighestRow();

for ($col = 3; $col <= 20; $col++) {
    $colLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col);
    $sumCell   = $colLetter . ($lastRow + 1); // write total in next row

    // Write the SUM formula
    $sheet->setCellValue($sumCell, "=SUM({$colLetter}2:{$colLetter}{$lastRow})");

}

$lastRow++;
$finalrow=5;
$startrow=1;

for ($col = 3; $col <= 20; $col++) {
    $colLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col);
    $sumCell   = $colLetter . ($lastRow); // write total in next row
    $cl=$col-1;
    $tcolLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($cl);
    $tsumCell   = $tcolLetter . ($finalrow); // write total in next row


    $srcRow = $col+$lastRow ;  // 240, 241, 242...

    $formula = "='".$sheetName."'!{$sumCell}";
    
    echo $formula;
    echo $tsumcell;
    $targetsheet->setCellValue($tsumCell, $formula);


 
}


//ofb

    $mccodes = $this->Njmallwagesprocess->ofbwgsbrksummary($att_payschm,$periodfromdate,$periodtodate);
    $result=$mccodes;
    
    $sheetName = 'Clerical Staff & Office Boys,Dr';   // change to your sheet name
    $tsheetName='Final Sheet';
    $sheet = $spreadsheet->getSheetByName($sheetName);
    $targetsheet = $spreadsheet->getSheetByName($tsheetName);
    $highestRow    = $sheet->getHighestRow();
    $highestColumn = $sheet->getHighestColumn();
    $rowNumber = 2;
    $range = "A2:{$highestColumn}{$highestRow}";
    $sheet->removeRow(2, $highestRow-1);  // removes all rows with content
    foreach ($result as $row) {
    $col = 'A';
    $colno = 0;
    foreach ($row as $cell) {
        if ($colno == 20) {
            $sheet->setCellValueExplicit($col . $rowNumber, $cell, DataType::TYPE_STRING);
        } else {
            $sheet->setCellValue($col . $rowNumber, $cell);
        }

        $col++;     // move outside
        $colno++;   // move outside
    }

    $rowNumber++;
}
    $highestRow    = $sheet->getHighestRow();
    $lastRow  = $sheet->getHighestRow();

for ($col = 3; $col <= 20; $col++) {
    $colLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col);
    $sumCell   = $colLetter . ($lastRow + 1); // write total in next row

    // Write the SUM formula
    $sheet->setCellValue($sumCell, "=SUM({$colLetter}2:{$colLetter}{$lastRow})");

}

$lastRow++;
$finalrow=7;
$startrow=1;

for ($col = 3; $col <= 20; $col++) {
    $colLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col);
    $sumCell   = $colLetter . ($lastRow); // write total in next row
    $cl=$col-1;
    $tcolLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($cl);
    $tsumCell   = $tcolLetter . ($finalrow); // write total in next row


    $srcRow = $col+$lastRow ;  // 240, 241, 242...

    $formula = "='".$sheetName."'!{$sumCell}";
    
    echo $formula;
    echo $tsumcell;
    $targetsheet->setCellValue($tsumCell, $formula);


 
}




//staff

    $mccodes = $this->Njmallwagesprocess->staffwgsbrksummary($att_payschm,$periodfromdate,$periodtodate);
    $result=$mccodes;
    $sheetName = 'Staffs';   // change to your sheet name
    $tsheetName='Final Sheet';
    $sheet = $spreadsheet->getSheetByName($sheetName);
    $targetsheet = $spreadsheet->getSheetByName($tsheetName);
    $highestRow    = $sheet->getHighestRow();
    $highestColumn = $sheet->getHighestColumn();
    $rowNumber = 2;
    $range = "A2:{$highestColumn}{$highestRow}";
    $sheet->removeRow(2, $highestRow-1);  // removes all rows with content
    foreach ($result as $row) {
    $col = 'A';
    $colno = 0;
    foreach ($row as $cell) {
        if ($colno == 20) {
            $sheet->setCellValueExplicit($col . $rowNumber, $cell, DataType::TYPE_STRING);
        } else {
            $sheet->setCellValue($col . $rowNumber, $cell);
        }

        $col++;     // move outside
        $colno++;   // move outside
    }

    $rowNumber++;
}
    $highestRow    = $sheet->getHighestRow();
    $lastRow  = $sheet->getHighestRow();

for ($col = 3; $col <= 20; $col++) {
    $colLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col);
    $sumCell   = $colLetter . ($lastRow + 1); // write total in next row

    // Write the SUM formula
    $sheet->setCellValue($sumCell, "=SUM({$colLetter}2:{$colLetter}{$lastRow})");

}

$lastRow++;
$finalrow=8;
$startrow=1;

for ($col = 3; $col <= 20; $col++) {
    $colLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col);
    $sumCell   = $colLetter . ($lastRow); // write total in next row
    $cl=$col-1;
    $tcolLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($cl);
    $tsumCell   = $tcolLetter . ($finalrow); // write total in next row


    $srcRow = $col+$lastRow ;  // 240, 241, 242...

    $formula = "='".$sheetName."'!{$sumCell}";
    
    echo $formula;
    echo $tsumcell;
    $targetsheet->setCellValue($tsumCell, $formula);


 
}


//blue

    $mccodes = $this->Njmallwagesprocess->bluewgsbrksummary($att_payschm,$periodfromdate,$periodtodate);
    $result=$mccodes;
    $sheetName = 'Blueforce';   // change to your sheet name
    $tsheetName='Final Sheet';
    $sheet = $spreadsheet->getSheetByName($sheetName);
    $targetsheet = $spreadsheet->getSheetByName($tsheetName);
    $highestRow    = $sheet->getHighestRow();
    $highestColumn = $sheet->getHighestColumn();
    $rowNumber = 2;
    $range = "A2:{$highestColumn}{$highestRow}";
    $sheet->removeRow(2, $highestRow-1);  // removes all rows with content
    foreach ($result as $row) {
    $col = 'A';
    $colno = 0;
    foreach ($row as $cell) {
        if ($colno == 20) {
            $sheet->setCellValueExplicit($col . $rowNumber, $cell, DataType::TYPE_STRING);
        } else {
            $sheet->setCellValue($col . $rowNumber, $cell);
        }

        $col++;     // move outside
        $colno++;   // move outside
    }

    $rowNumber++;
}
    $highestRow    = $sheet->getHighestRow();
    $lastRow  = $sheet->getHighestRow();

for ($col = 3; $col <= 20; $col++) {
    $colLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col);
    $sumCell   = $colLetter . ($lastRow + 1); // write total in next row

    // Write the SUM formula
    $sheet->setCellValue($sumCell, "=SUM({$colLetter}2:{$colLetter}{$lastRow})");

}

$lastRow++;
$finalrow=9;
$startrow=1;

for ($col = 3; $col <= 20; $col++) {
    $colLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col);
    $sumCell   = $colLetter . ($lastRow); // write total in next row
    $cl=$col-1;
    $tcolLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($cl);
    $tsumCell   = $tcolLetter . ($finalrow); // write total in next row


    $srcRow = $col+$lastRow ;  // 240, 241, 242...

    $formula = "='".$sheetName."'!{$sumCell}";
    
    echo $formula;
    echo $tsumcell;
    $targetsheet->setCellValue($tsumCell, $formula);


 
}


//contractor

    $mccodes = $this->Njmallwagesprocess->contwgsbrksummary($att_payschm,$periodfromdate,$periodtodate);
    $result=$mccodes;
    $sheetName = 'Contractors';   // change to your sheet name
    $tsheetName='Final Sheet';
    $sheet = $spreadsheet->getSheetByName($sheetName);
    $targetsheet = $spreadsheet->getSheetByName($tsheetName);
    $highestRow    = $sheet->getHighestRow();
    $highestColumn = $sheet->getHighestColumn();
    $rowNumber = 2;
    $range = "A2:{$highestColumn}{$highestRow}";
    $sheet->removeRow(2, $highestRow-1);  // removes all rows with content
    foreach ($result as $row) {
    $col = 'A';
    $colno = 0;
    foreach ($row as $cell) {
        if ($colno == 20) {
            $sheet->setCellValueExplicit($col . $rowNumber, $cell, DataType::TYPE_STRING);
        } else {
            $sheet->setCellValue($col . $rowNumber, $cell);
        }

        $col++;     // move outside
        $colno++;   // move outside
    }

    $rowNumber++;
}
    $highestRow    = $sheet->getHighestRow();
    $lastRow  = $sheet->getHighestRow();

for ($col = 3; $col <= 20; $col++) {
    $colLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col);
    $sumCell   = $colLetter . ($lastRow + 1); // write total in next row

    // Write the SUM formula
    $sheet->setCellValue($sumCell, "=SUM({$colLetter}2:{$colLetter}{$lastRow})");

}

$lastRow++;
$finalrow=10;
$startrow=1;

for ($col = 3; $col <= 20; $col++) {
    $colLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col);
    $sumCell   = $colLetter . ($lastRow); // write total in next row
    $cl=$col-1;
    $tcolLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($cl);
    $tsumCell   = $tcolLetter . ($finalrow); // write total in next row


    $srcRow = $col+$lastRow ;  // 240, 241, 242...

    $formula = "='".$sheetName."'!{$sumCell}";
    
    echo $formula;
    echo $tsumcell;
    $targetsheet->setCellValue($tsumCell, $formula);


 
}



//cash

    $mccodes = $this->Njmallwagesprocess->cashwgsbrksummary($att_payschm,$periodfromdate,$periodtodate);
    $result=$mccodes;
    $sheetName = 'Cash Hand Payments';   // change to your sheet name
    $tsheetName='Final Sheet';
    $sheet = $spreadsheet->getSheetByName($sheetName);
    $targetsheet = $spreadsheet->getSheetByName($tsheetName);
    $highestRow    = $sheet->getHighestRow();
    $highestColumn = $sheet->getHighestColumn();
    $rowNumber = 2;
    $range = "A2:{$highestColumn}{$highestRow}";
    $sheet->removeRow(2, $highestRow-1);  // removes all rows with content
    foreach ($result as $row) {
    $col = 'A';
    $colno = 0;
    foreach ($row as $cell) {
        if ($colno == 20) {
            $sheet->setCellValueExplicit($col . $rowNumber, $cell, DataType::TYPE_STRING);
        } else {
            $sheet->setCellValue($col . $rowNumber, $cell);
        }

        $col++;     // move outside
        $colno++;   // move outside
    }

    $rowNumber++;
}
    $highestRow    = $sheet->getHighestRow();
    $lastRow  = $sheet->getHighestRow();

for ($col = 3; $col <= 20; $col++) {
    $colLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col);
    $sumCell   = $colLetter . ($lastRow + 1); // write total in next row

    // Write the SUM formula
    $sheet->setCellValue($sumCell, "=SUM({$colLetter}2:{$colLetter}{$lastRow})");

}

$lastRow++;
$finalrow=12;
$startrow=1;

for ($col = 3; $col <= 20; $col++) {
    $colLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col);
    $sumCell   = $colLetter . ($lastRow); // write total in next row
    $cl=$col-1;
    $tcolLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($cl);
    $tsumCell   = $tcolLetter . ($finalrow); // write total in next row


    $srcRow = $col+$lastRow ;  // 240, 241, 242...

    $formula = "='".$sheetName."'!{$sumCell}";
    
    echo $formula;
    echo $tsumcell;
    $targetsheet->setCellValue($tsumCell, $formula);


 
}




    // 👉 At this point upload + read is OK.
    // You can now modify the spreadsheet, add new sheet from MySQL, etc.


    $newFileName = 'updated_Wages_break_up_Summary ' . $mnyr;

    // Clean (very important – avoid "headers already sent")
    ob_end_clean(); // if any buffer is open

    // Set download headers
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="' . $newFileName . '"');
    header('Cache-Control: max-age=0');

    // Write file to output
    $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
    $writer->save('php://output');
    exit; // stop further output



  

}


public function jutemropen() {

	$agent = $this->input->post('agent');
	$gateentryno = $this->input->post('gateentryno');
    $comp = $this->session->userdata('companyId');
	$mcc=$this->Njmallwagesprocess->jutemropen($agent,$gateentryno,$comp);

    



 	
	 

			$response = array(
		'success' => true,
		'savedata'=> 'saved'
	);
	


	echo json_encode($response);




}

public function jutevowtally() {

	$agent = $this->input->post('agent');
	$psupvow = $this->input->post('psupvow');
    $psuptally = $this->input->post('psuptally');
    $psalevow = $this->input->post('psalevow');
    $psaletally = $this->input->post('psaletally');
    $att_jcqty = $this->input->post('att_jcqty');
    $att_jtqty = $this->input->post('att_jtqty');


    $comp = $this->session->userdata('companyId');
	$mcc=$this->Njmallwagesprocess->jutevowtally($psupvow,$psuptally,$psalevow,$psaletally,$att_jcqty,$att_jtqty,$comp);
//  	var_dump($mcc); 
//    echo 'mjjjd ' . $mcc[0]['msg'];
	$response = array(
		'success' => true,
		'savedata'=> $mcc[0]['msg']

	);
	


	echo json_encode($response);




}






    }



