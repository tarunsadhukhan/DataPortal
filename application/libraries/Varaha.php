<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Varaha
{

    public function __construct()
    {

    }

    public function __get($var)
    {
        return get_instance()->$var;
    }

    private function _rglobRead($source, &$array = array())
    {
        if (!$source || trim($source) == "") {
            $source = ".";
        }
        foreach ((array)glob($source . "/*/") as $key => $value) {
            $this->_rglobRead(str_replace("//", "/", $value), $array);
        }
        $hidden_files = glob($source . ".*") AND $htaccess = preg_grep('/\.htaccess$/', $hidden_files);
        $files = array_merge(glob($source . "*.*"), $htaccess);
        foreach ($files as $key => $value) {
            $array[] = str_replace("//", "/", $value);
        }
    }

    private function _zip($array, $part, $destination, $output_name = 'absolin')
    {
        $zip = new ZipArchive;
        @mkdir($destination, 0777, true);

        if ($zip->open(str_replace("//", "/", "{$destination}/{$output_name}" . ($part ? '_p' . $part : '') . ".zip"), ZipArchive::CREATE)) {
            foreach ((array)$array as $key => $value) {
                $zip->addFile($value, str_replace(array("../", "./"), NULL, $value));
            }
            $zip->close();
        }
    }

    public function formatMoney($number, $currency = '')
    {
        if($this->Settings->sac) {
            return $currency . $this->formatSAC($this->formatDecimal($number));
        }
        $decimals = $this->Settings->decimals;
        $ts = $this->Settings->thousands_sep == '0' ? ' ' : $this->Settings->thousands_sep;
        $ds = $this->Settings->decimals_sep;
        return $currency . number_format($number, $decimals, $ds, $ts);
    }

    public function formatNumber($number, $decimals = NULL)
    {
        if($this->Settings->sac) {
            return $this->formatSAC($this->formatDecimal($number));
        }
        if (!$decimals) {
            $decimals = $this->Settings->decimals;
        }
        $ts = $this->Settings->thousands_sep == '0' ? ' ' : $this->Settings->thousands_sep;
        $ds = $this->Settings->decimals_sep;
        return number_format($number, $decimals, $ds, $ts);
    }

    public function formatDecimal($number, $decimals = NULL)
    {
        if ( ! is_numeric($number)) {
            return NULL;
        }
        if (!$decimals) {
            $decimals = $this->Settings->decimals;
        }
        return number_format($number, $decimals, '.', '');
    }

    public function clear_tags($str)
    {
        return htmlentities(
            strip_tags($str,
                '<span><div><a><br><p><b><i><u><img><blockquote><small><ul><ol><li><hr><big><pre><code><strong><em><table><tr><td><th><tbody><thead><tfoot><h3><h4><h5><h6>'
            ),
            ENT_QUOTES | ENT_XHTML | ENT_HTML5,
            'UTF-8'
        );
    }

    public function decode_html($str)
    {
        return html_entity_decode($str, ENT_QUOTES | ENT_XHTML | ENT_HTML5, 'UTF-8');
    }

    public function roundMoney($num, $nearest = 0.05)
    {
        return round($num * (1 / $nearest)) * $nearest;
    }

    public function roundNumber($number, $toref = NULL)
    {
        switch($toref) {
            case 1:
                $rn = round($number * 20)/20;
                break;
            case 2:
                $rn = round($number * 2)/2;
                break;
            case 3:
                $rn = round($number);
                break;
            case 4:
                $rn = ceil($number);
                break;
            default:
                $rn = $number;
        }
        return $rn;
    }
	
	public function moneyFormatIndia($num){
	
		if($num>0){
			$num = round($num);
			 $explrestunits = "" ;
			if(strlen($num)>3){
				$lastthree = substr($num, strlen($num)-3, strlen($num));
				$restunits = substr($num, 0, strlen($num)-3); // extracts the last three digits
				$restunits = (strlen($restunits)%2 == 1)?"0".$restunits:$restunits; // explodes the remaining digits in 2's formats, adds a zero in the beginning to maintain the 2's grouping.
				$expunit = str_split($restunits, 2);
				for($i=0; $i<sizeof($expunit); $i++){
					// creates each of the 2's group and adds a comma to the end
					if($i==0)
					{
						$explrestunits .= (int)$expunit[$i].","; // if is first value , convert into integer
					}else{
						$explrestunits .= $expunit[$i].",";
					}
				}
				$thecash = $explrestunits.$lastthree;
			} else {
				$thecash = $num;
			}
			return $thecash.'.00';
		}else{
			
			return $num;
		}
		
	   
	}

    public function unset_data($ud)
    {
        if ($this->session->userdata($ud)) {
            $this->session->unset_userdata($ud);
            return true;
        }
        return FALSE;
    }

    public function hrsd($sdate)
    {
        if ($sdate) {
            return date($this->dateFormats['php_sdate'], strtotime($sdate));
        } else {
            return '0000-00-00';
        }
    }

    public function hrld($ldate)
    {
        if ($ldate) {
            return date($this->dateFormats['php_ldate'], strtotime($ldate));
        } else {
            return '0000-00-00 00:00:00';
        }
    }

    public function fsd($inv_date)
    {
        if ($inv_date) {
            $jsd = $this->dateFormats['js_sdate'];
            if ($jsd == 'dd-mm-yyyy' || $jsd == 'dd/mm/yyyy' || $jsd == 'dd.mm.yyyy') {
                $date = substr($inv_date, -4) . "-" . substr($inv_date, 3, 2) . "-" . substr($inv_date, 0, 2);
            } elseif ($jsd == 'mm-dd-yyyy' || $jsd == 'mm/dd/yyyy' || $jsd == 'mm.dd.yyyy') {
                $date = substr($inv_date, -4) . "-" . substr($inv_date, 0, 2) . "-" . substr($inv_date, 3, 2);
            } else {
                $date = $inv_date;
            }
            return $date;
        } else {
            return '0000-00-00';
        }
    }

    public function fld($ldate)
    {
        if ($ldate) {
            $date = explode(' ', $ldate);
            $jsd = $this->dateFormats['js_sdate'];
            $inv_date = $date[0];
            $time = $date[1];
            if ($jsd == 'dd-mm-yyyy' || $jsd == 'dd/mm/yyyy' || $jsd == 'dd.mm.yyyy') {
                $date = substr($inv_date, -4) . "-" . substr($inv_date, 3, 2) . "-" . substr($inv_date, 0, 2) . " " . $time;
            } elseif ($jsd == 'mm-dd-yyyy' || $jsd == 'mm/dd/yyyy' || $jsd == 'mm.dd.yyyy') {
                $date = substr($inv_date, -4) . "-" . substr($inv_date, 0, 2) . "-" . substr($inv_date, 3, 2) . " " . $time;
            } else {
                $date = $inv_date;
            }
            return $date;
        } else {
            return '0000-00-00 00:00:00';
        }
    }

    public function send_email($to, $subject, $message, $from = NULL, $from_name = NULL, $attachment = NULL, $cc = NULL, $bcc = NULL)
    {
        $this->load->library('email');
        $config['useragent'] = "AbsolinPOS";
        $config['protocol'] = $this->Settings->protocol;
        $config['mailtype'] = "html";
        $config['crlf'] = "\r\n";
        $config['newline'] = "\r\n";
        if ($this->Settings->protocol == 'sendmail') {
            $config['mailpath'] = $this->Settings->mailpath;
        } elseif ($this->Settings->protocol == 'smtp') {
            $this->load->library('encrypt');
            $config['smtp_host'] = $this->Settings->smtp_host;
            $config['smtp_user'] = $this->Settings->smtp_user;
            $config['smtp_pass'] = $this->encrypt->decode($this->Settings->smtp_pass);
            $config['smtp_port'] = $this->Settings->smtp_port;
            if (!empty($this->Settings->smtp_crypto)) {
                $config['smtp_crypto'] = $this->Settings->smtp_crypto;
            }
        }

        $this->email->initialize($config);

        if ($from && $from_name) {
            $this->email->from($from, $from_name);
        } elseif ($from) {
            $this->email->from($from, $this->Settings->site_name);
        } else {
            $this->email->from($this->Settings->default_email, $this->Settings->site_name);
        }

        $this->email->to($to);
        if ($cc) {
           // $this->email->cc($cc);
        }
        if ($bcc) {
           // $this->email->bcc($bcc);
        }
        $this->email->subject($subject);
        $this->email->message($message);
		
        if ($attachment) {
            if (is_array($attachment)) {
                foreach ($attachment as $file) {
                    $this->email->attach($file);
                }
            } else {
                $this->email->attach($attachment);
            }
        }
		
        if ($this->email->send()) {
            //echo $this->email->print_debugger(); die();
            return TRUE;
        } else {
            //echo $this->email->print_debugger(); die();
            return FALSE;
        }
    }
	
	public function checkPermission($action = NULL, $type = NULL){
		if ($this->Owner || $this->Admin) {
			return true;
		}else{
			if($type==0){
				if ($this->site->getActionPermissions($action)!=1) {
					$this->session->set_flashdata('error', lang("access_denied"));
					//redirect('welcome', 'refresh');
					redirect(isset($_SERVER["HTTP_REFERER"]) ? $_SERVER["HTTP_REFERER"] : 'welcome');
				}
			}else{
				return $this->site->getActionPermissions($action);
			}
		}
	}

    public function checkPermissions($action = NULL, $js = NULL, $module = NULL)
    {
        
		if (!$this->actionPermissions($action, $module)) {
            $this->session->set_flashdata('error', lang("access_denied"));
            if ($js) {
                die("<script type='text/javascript'>setTimeout(function(){ window.top.location.href = '" . (isset($_SERVER["HTTP_REFERER"]) ? $_SERVER["HTTP_REFERER"] : site_url('welcome')) . "'; }, 10);</script>");
            } else {
                redirect(isset($_SERVER["HTTP_REFERER"]) ? $_SERVER["HTTP_REFERER"] : 'welcome');
            }
        }
    }

    public function actionPermissions($action = NULL, $module = NULL)
    {
        if ($this->Owner || $this->Admin) {
            if ($this->Admin && stripos($action, 'delete') !== false) {
                return FALSE;
            }
            return TRUE;
        } elseif ($this->Customer || $this->Supplier) {
            return false;
        } else {
            if (!$module) {
                $module = $this->m;
            }
            if (!$action) {
                $action = $this->v;
            }
            //$gp = $this->site->checkPermissions();
            if ($this->GP[$module . '-' . $action] == 1) {
                return TRUE;
            } else {
                return FALSE;
            }
        }
    }

    public function save_barcode($text = NULL, $bcs = 'code39', $height = 56, $stext = 1, $width = 256)
    {
        $drawText = ($stext != 1) ? FALSE : TRUE;
        $this->load->library('zend');
        $this->zend->load('Zend/Barcode');
        $barcodeOptions = array('text' => $text, 'barHeight' => $height, 'drawText' => $drawText);
        $rendererOptions = array('imageType' => 'png', 'horizontalPosition' => 'center', 'verticalPosition' => 'middle'); //'width' => $width
        $image = Zend_Barcode::draw($bcs, 'image', $barcodeOptions, $rendererOptions);
        if (imagepng($image, 'assets/uploads/barcode' . $this->session->userdata('user_id') . '.png')) {
            imagedestroy($image);
            $bc = file_get_contents('assets/uploads/barcode' . $this->session->userdata('user_id') . '.png');
            $bcimage = base64_encode($bc);
            return $bcimage;
        }
        return FALSE;
    }

    public function qrcode($type = 'text', $text = 'PHP QR Code', $size = 2, $level = 'H', $file_name = NULL)
    {
        $file_name = 'assets/uploads/qrcode' . $this->session->userdata('user_id') . '.png';
        if ($type == 'link') {
            $text = urldecode($text);
        }
        $this->load->library('phpqrcode');
        $config = array('data' => $text, 'size' => $size, 'level' => $level, 'savename' => $file_name);
        $this->phpqrcode->generate($config);
        $qr = file_get_contents('assets/uploads/qrcode' . $this->session->userdata('user_id') . '.png');
        $qrimage = base64_encode($qr);
        return $qrimage;
    }

    public function generate_pdf($content, $name = 'download.pdf', $output_type = NULL, $footer = NULL, $margin_bottom = NULL, $header = NULL, $margin_top = NULL, $orientation = 'P')
    {
        if (!$output_type) {
            $output_type = 'D';
        }
        if (!$margin_bottom) {
            $margin_bottom = 10;
        }
        if (!$margin_top) {
            $margin_top = 10;
        }
        $this->load->library('pdf');
        $pdf = new mPDF('utf-8', 'A4-' . $orientation, '13', '', 10, 10, $margin_top, $margin_bottom, 9, 9);
        $pdf->debug = false;
        $pdf->autoScriptToLang = true;
        $pdf->autoLangToFont = true;
        $pdf->SetProtection(array('print')); // You pass 2nd arg for user password (open) and 3rd for owner password (edit)
        //$pdf->SetProtection(array('print', 'copy')); // Comment above line and uncomment this to allow copying of content
        $pdf->SetTitle($this->Settings->site_name);
        $pdf->SetAuthor($this->Settings->site_name);
        $pdf->SetCreator($this->Settings->site_name);
        $pdf->SetDisplayMode('fullpage');
        $stylesheet = file_get_contents('assets/bs/bootstrap.min.css');
        $pdf->WriteHTML($stylesheet, 1);
        $pdf->WriteHTML($content);
        if ($header != '') {
            $pdf->SetHTMLHeader('<p class="text-center">' . $header . '</p>', '', TRUE);
        }
        if ($footer != '') {
            $pdf->SetHTMLFooter('<p class="text-center">' . $footer . '</p>', '', TRUE);
        }
        //$pdf->SetHeader($this->Settings->site_name.'||{PAGENO}', '', TRUE); // For simple text header
        //$pdf->SetFooter($this->Settings->site_name.'||{PAGENO}', '', TRUE); // For simple text footer
        if ($output_type == 'S') {
            $file_content = $pdf->Output('', 'S');
            write_file('assets/uploads/' . $name, $file_content);
            return 'assets/uploads/' . $name;
        } else {
            $pdf->Output($name, $output_type);
        }
    }

    public function print_arrays()
    {
        $args = func_get_args();
        echo "<pre>";
        foreach ($args as $arg) {
            print_r($arg);
        }
        echo "</pre>";
        die();
    }

    public function logged_in()
    {
        return (bool)$this->session->userdata('identity');
    }

    public function in_group($check_group, $id = false)
    {
        $id || $id = $this->session->userdata('user_id');
        $group = $this->site->getUserGroup($id);
        if ($group->name === $check_group) {
            return TRUE;
        }
        return FALSE;
    }

    public function log_payment($msg, $val = NULL)
    {
        $this->load->library('logs');
        return (bool)$this->logs->write('payments', $msg, $val);
    }

    public function update_award_points($total, $customer, $user, $scope = NULL)
    {
        if ($this->Settings->each_spent && $total >= $this->Settings->each_spent) {
            $company = $this->site->getCompanyByID($customer);
            $points = floor(($total / $this->Settings->each_spent) * $this->Settings->ca_point);
            $total_points = $scope ? $company->award_points - $points : $company->award_points + $points;
            $this->db->update('companies', array('award_points' => $total_points), array('id' => $customer));
        }
        if ($this->Settings->each_sale && !$this->Customer && $total >= $this->Settings->each_sale) {
            $staff = $this->site->getUser($user);
            $points = floor(($total / $this->Settings->each_sale) * $this->Settings->sa_point);
            $total_points = $scope ? $staff->award_points - $points : $staff->award_points + $points;
            $this->db->update('users', array('award_points' => $total_points), array('id' => $user));
        }
        return TRUE;
    }

    public function zip($source = NULL, $destination = "./", $output_name = 'absolin', $limit = 5000)
    {
        if (!$destination || trim($destination) == "") {
            $destination = "./";
        }

        $this->_rglobRead($source, $input);
        $maxinput = count($input);
        $splitinto = (($maxinput / $limit) > round($maxinput / $limit, 0)) ? round($maxinput / $limit, 0) + 1 : round($maxinput / $limit, 0);

        for ($i = 0; $i < $splitinto; $i++) {
            $this->_zip(array_slice($input, ($i * $limit), $limit, true), $i, $destination, $output_name);
        }

        unset($input);
        return;
    }

    public function unzip($source, $destination = './')
    {

        // @chmod($destination, 0777);
        $zip = new ZipArchive;
        if ($zip->open(str_replace("//", "/", $source)) === true) {
            $zip->extractTo($destination);
            $zip->close();
        }
        // @chmod($destination,0755);

        return TRUE;
    }

    public function view_rights($check_id, $js = NULL)
    {
        if (!$this->Owner && !$this->Admin) {
            if ($check_id != $this->session->userdata('user_id')) {
                $this->session->set_flashdata('warning', $this->data['access_denied']);
                if ($js) {
                    die("<script type='text/javascript'>setTimeout(function(){ window.top.location.href = '" . (isset($_SERVER["HTTP_REFERER"]) ? $_SERVER["HTTP_REFERER"] : site_url('welcome')) . "'; }, 10);</script>");
                } else {
                    redirect(isset($_SERVER["HTTP_REFERER"]) ? $_SERVER["HTTP_REFERER"] : 'welcome');
                }
            }
        }
        return TRUE;
    }

    function makecomma($input) {
        if(strlen($input)<=2)
        { return $input; }
        $length=substr($input,0,strlen($input)-2);
        $formatted_input = $this->makecomma($length).",".substr($input,-2);
        return $formatted_input;
    }

    public function formatSAC($num) {
        $pos = strpos((string)$num, ".");
        if ($pos === false) { $decimalpart="00";}
        else { $decimalpart= substr($num, $pos+1, 2); $num = substr($num,0,$pos); }

        if(strlen($num)>3 & strlen($num) <= 12){
            $last3digits = substr($num, -3 );
            $numexceptlastdigits = substr($num, 0, -3 );
            $formatted = $this->makecomma($numexceptlastdigits);
            $stringtoreturn = $formatted.",".$last3digits.".".$decimalpart ;
        } elseif(strlen($num)<=3) {
            $stringtoreturn = $num.".".$decimalpart ;
        } elseif(strlen($num)>12) {
            $stringtoreturn = number_format($num, 2);
        }

        if(substr($stringtoreturn,0,2)=="-,"){$stringtoreturn = "-".substr($stringtoreturn,2 );}

        return $stringtoreturn;
    }

    public function md() {
        die("<script type='text/javascript'>setTimeout(function(){ window.top.location.href = '" . (isset($_SERVER["HTTP_REFERER"]) ? $_SERVER["HTTP_REFERER"] : site_url('welcome')) . "'; }, 10);</script>");
    }
	
	public function monthname($month){
		
		$monthNum  = $month;
		$dateObj   = DateTime::createFromFormat('!m', $monthNum);
		$monthName = $dateObj->format('F');
		return $monthName;
	}
	
	public function getIndianCurrency($number)
	{
		$decimal = round($number - ($no = floor($number)), 2) * 100;
		$hundred = null;
		$digits_length = strlen($no);
		$i = 0;
		$str = array();
		$words = array(0 => '', 1 => 'one', 2 => 'two',
			3 => 'three', 4 => 'four', 5 => 'five', 6 => 'six',
			7 => 'seven', 8 => 'eight', 9 => 'nine',
			10 => 'ten', 11 => 'eleven', 12 => 'twelve',
			13 => 'thirteen', 14 => 'fourteen', 15 => 'fifteen',
			16 => 'sixteen', 17 => 'seventeen', 18 => 'eighteen',
			19 => 'nineteen', 20 => 'twenty', 30 => 'thirty',
			40 => 'forty', 50 => 'fifty', 60 => 'sixty',
			70 => 'seventy', 80 => 'eighty', 90 => 'ninety');
		$digits = array('', 'hundred','thousand','lakh', 'crore');
		while( $i < $digits_length ) {
			$divider = ($i == 2) ? 10 : 100;
			$number = floor($no % $divider);
			$no = floor($no / $divider);
			$i += $divider == 10 ? 1 : 2;
			if ($number) {
				$plural = (($counter = count($str)) && $number > 9) ? 's' : null;
				$hundred = ($counter == 1 && $str[0]) ? ' and ' : null;
				$str [] = ($number < 21) ? $words[$number].' '. $digits[$counter]. $plural.' '.$hundred:$words[floor($number / 10) * 10].' '.$words[$number % 10]. ' '.$digits[$counter].$plural.' '.$hundred;
			} else $str[] = null;
		}
		$Rupees = implode('', array_reverse($str));
		$paise = ($decimal) ? "." . ($words[$decimal / 10] . " " . $words[$decimal % 10]) . ' Paise' : '';
		$result = ($Rupees ? $Rupees . 'Rupees ' : '') . $paise;
		
		return ucfirst ( $result );
	}

	function readNumber($num, $depth=0)
	{
		$num = (int)$num;
		$retval ="";
		if ($num < 0) // if it's any other negative, just flip it and call again
			return "negative " + readNumber(-$num, 0);
		if ($num > 99) // 100 and above
		{
			if ($num > 999) // 1000 and higher
				$retval .= readNumber($num/1000, $depth+3);

			$num %= 1000; // now we just need the last three digits
			if ($num > 99) // as long as the first digit is not zero
				$retval .= readNumber($num/100, 2)." hundred\n";
			$retval .=readNumber($num%100, 1); // our last two digits                       
		}
		else // from 0 to 99
		{
			$mod = floor($num / 10);
			if ($mod == 0) // ones place
			{
				if ($num == 1) $retval.="one";
				else if ($num == 2) $retval.="second";
				else if ($num == 3) $retval.="thard";
				else if ($num == 4) $retval.="fourth";
				else if ($num == 5) $retval.="fifth";
				else if ($num == 6) $retval.="sixth";
				else if ($num == 7) $retval.="seventh";
				else if ($num == 8) $retval.="eightth";
				else if ($num == 9) $retval.="nineth";
			}
			else if ($mod == 1) // if there's a one in the ten's place
			{
				if ($num == 10) $retval.="tenth";
				else if ($num == 11) $retval.="eleventh";
				else if ($num == 12) $retval.="twelveth";
				else if ($num == 13) $retval.="thirteenth";
				else if ($num == 14) $retval.="fourteenth";
				else if ($num == 15) $retval.="fifteenth";
				else if ($num == 16) $retval.="sixteenth";
				else if ($num == 17) $retval.="seventeenth";
				else if ($num == 18) $retval.="eighteenth";
				else if ($num == 19) $retval.="nineteenth";
			}
			else // if there's a different number in the ten's place
			{
				if ($mod == 2) $retval.="twentyth ";
				else if ($mod == 3) $retval.="thirtyth ";
				else if ($mod == 4) $retval.="fortyth ";
				else if ($mod == 5) $retval.="fiftyth ";
				else if ($mod == 6) $retval.="sixtyth ";
				else if ($mod == 7) $retval.="seventyth ";
				else if ($mod == 8) $retval.="eightyth ";
				else if ($mod == 9) $retval.="ninetyth ";
				if (($num % 10) != 0)
				{
					$retval = rtrim($retval); //get rid of space at end
					$retval .= "-";
				}
				$retval.=readNumber($num % 10, 0);
			}
		}

		if ($num != 0)
		{
			if ($depth == 3)
				$retval.=" thousand\n";
			else if ($depth == 6)
				$retval.=" million\n";
			if ($depth == 9)
				$retval.=" billion\n";
		}
		return $retval;
	}
	
	function k99_relative_time($datetime, $full = false) {
		$now = new DateTime;
		$ago = new DateTime($datetime);
		$diff = $now->diff($ago);

		$diff->w = floor($diff->d / 7);
		$diff->d -= $diff->w * 7;

		$string = array(
			'y' => 'year',
			'm' => 'month',
			'w' => 'week',
			'd' => 'day',
			'h' => 'hour',
			'i' => 'minute',
			's' => 'second',
		);
		foreach ($string as $k => &$v) {
			if ($diff->$k) {
				$v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
			} else {
				unset($string[$k]);
			}
		}

		if (!$full) $string = array_slice($string, 0, 1);
		return $string ? implode(', ', $string) . ' ago' : 'just now';
	}
	
	function square_crop($src_image, $dest_image, $thumb_size, $jpg_quality = 90) {
	 
		// Get dimensions of existing image
		$image = getimagesize($src_image);
	 
		// Check for valid dimensions
		if( $image[0] <= 0 || $image[1] <= 0 ) return false;
	 
		// Determine format from MIME-Type
		$image['format'] = strtolower(preg_replace('/^.*?\//', '', $image['mime']));
	 
		// Import image
		switch( $image['format'] ) {
			case 'jpg':
			case 'jpeg':
				$image_data = imagecreatefromjpeg($src_image);
			break;
			case 'png':
				$image_data = imagecreatefrompng($src_image);
			break;
			case 'gif':
				$image_data = imagecreatefromgif($src_image);
			break;
			default:
				// Unsupported format
				return false;
			break;
		}
	 
		// Verify import
		if( $image_data == false ) return false;
	 
		// Calculate measurements
		if( $image[0] > $image[1] ) {
			// For landscape images
			$x_offset = ($image[0] - $image[1]) / 2;
			$y_offset = 0;
			$square_size = $image[0] - ($x_offset * 2);
		} else {
			// For portrait and square images
			$x_offset = 0;
			$y_offset = ($image[1] - $image[0]) / 15;
			$square_size = $image[1] - ($y_offset * 15);
		}
	 
		// Resize and crop
		$canvas = imagecreatetruecolor($thumb_size, $thumb_size);
		if( imagecopyresampled(
			$canvas,
			$image_data,
			0,
			0,
			$x_offset,
			$y_offset,
			$thumb_size,
			$thumb_size,
			$square_size,
			$square_size
		)) {
	 
			// Create thumbnail
			switch( strtolower(preg_replace('/^.*\./', '', $dest_image)) ) {
				case 'jpg':
				case 'jpeg':
					return imagejpeg($canvas, $dest_image, $jpg_quality);
				break;
				case 'png':
					return imagepng($canvas, $dest_image);
				break;
				case 'gif':
					return imagegif($canvas, $dest_image);
				break;
				default:
					// Unsupported format
					return false;
				break;
			}
	 
		} else {
			return false;
		}
	 
	}
	
	
	
	public function send_sms($to_number, $msg = "") {
       
            $msg = urlencode($msg);
            $sms_url = 'http://www.bulksmsapps.com/apisms.aspx?user=Vidhyas554&password=Syon@123&genkey=976178321&coding=3&sender=SYONST&number='.$to_number.'&message='.$msg;
            
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_POST, false);
            curl_setopt($ch, CURLOPT_URL, $sms_url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $output = curl_exec($ch);
            curl_close($ch);
            if ($output)
                return true;
       
    }

    public function getUserGroupId($companyId){
        $companys = $this->session->userdata('companys');        
		$i=0;
		if($companys){	
			foreach ($companys as $comp) {
				if($comp['compId'] == $companyId){
                    return $comp['userGroupId'];
                }
			}
		}		
        return $i;
    }

    

    public function getTenentId(){
        $tenentId = "devapi.vowerp.com"; 
        $urlParts = parse_url($_SERVER['HTTP_HOST']);
        $urlKey = array_keys($urlParts);
        $url = $urlParts[$urlKey[0]];

        if($url=='localhost'){                  
            // $servername = "18.130.130.0";
            // $username = "console";
            // $password = "MeeraN@g10";
            // $database = "vowdev";
            // $tenentId = "dev.vowerp.com"; 
            // $serverIp = "http://devapi.vowerp.com";

            // $servername = "35.178.47.156";
            // $username = "reportsqa";
            // $password = "Reports@12345";
            // $database = "vowdev";
            // $tenentId = "qa.vowerp.com"; 
            // $serverIp = "https://qaapi.vowerp.com";

            $servername = "3.7.255.145";
            $username = "reports";
            $password = "Reports@12345";
            $database = "vowsls";
            $tenentId = "sls.vowerp.com"; 
            $serverIp = "https://tenantec2.vowerp.com";

            // $servername = "3.7.255.145";
            // $username = "sw";
            // $password = "Stpl@321";
            // $database = "vowsw_capex_live";
            // $tenentId = "smartworks.vowerp.com"; 
            // $serverIp = "https://tenant2.vowerp.com";

            // $servername = "3.7.255.145";
            // $username = "reports";
            // $password = "Reports@12345";
            // $database = "vowtalbot";
            // $tenentId = "talbot.vowerp.com"; 
            // $serverIp = "https://tenantec2.vowerp.com";

            // $servername = "3.7.255.145";
            // $username = "reports";
            // $password = "Reports@12345";
            // $database = "vowsw_capex_live";
            // $tenentId = "smartworks.vowerp.com"; 
            // $serverIp = "https://tenantec2.vowerp.com";

        }else if($url == '13.126.47.172'){
   //         echo 'my tenant http://103.154.234.215/';
            $servername = "3.7.255.145";
            $username = "reports";
            $password = "Reports@12345";
            $database = "vowsls";
            $tenentId = "sls.vowerp.com"; 
            $serverIp = "https://tenantec2.vowerp.com";
        }else if($url == '103.154.234.215'){
            //         echo 'my tenant http://103.154.234.215/';
                     $servername = "3.7.255.145";
                     $username = "reports";
                     $password = "Reports@12345";
                     $database = "vowsls";
                     $tenentId = "sls.vowerp.com"; 
                     $serverIp = "https://tenantec2.vowerp.com";
                 }else if($url == 'data.dev.vowerp.com'){
            $servername = "13.232.34.218";
            $username = "devuser";
            $password = "VowDev!123";
            $tenentId = "dev.vowerp.com"; 
            $database = "vowdev";
            $serverIp = "https://devapi.vowerp.com";
        }else if($url == 'data.qa.vowerp.com'){
            $servername = "13.232.34.218";
            $username = "devuser";
            $password = "VowDev!123";
            $database = "vowqa";
            $tenentId = "qa.vowerp.com"; 
            $serverIp = "https://qaapi.vowerp.com";
        }else if($url == 'data.sls.vowerp.com'){
            $servername = "3.7.255.145";
            $username = "reports";
            $password = "Reports@12345";
            $database = "vowsls";
            $tenentId = "sls.vowerp.com"; 
            $serverIp = "https://tenantec2.vowerp.com";
        }else if($url == 'data.sls1.2.vowerp.com'){
            $servername = "3.7.255.145";
            $username = "reports";
            $password = "Reports@12345";
            $database = "vowsls";
            $tenentId = "sls.vowerp.com"; 
            $serverIp = "https://tenantec2.vowerp.com";
        }else if($url == 'data.talbot.vowerp.com'){
            $servername = "3.7.255.145";
            $username = "reports";
            $password = "Reports@12345";
            $database = "vowtalbot";
            $tenentId = "talbot.vowerp.com"; 
            $serverIp = "https://tenantec2.vowerp.com";
        }else if($url == 'data.ajm1.2.vowerp.com'){
            $servername = "3.7.255.145";
            $username = "reports";
            $password = "Reports@12345";
            $database = "vowajm";
            $tenentId = "ajm.vowerp.com"; 
            $serverIp = "https://tenantec2.vowerp.com";
        }else if($url == 'data.ajm.vowerp.com'){
            $servername = "3.7.255.145";
            $username = "reports";
            $password = "Reports@12345";
            $database = "vowajm";
            $tenentId = "ajm.vowerp.com"; 
            $serverIp = "https://tenantec2.vowerp.com";
        // }else if($url == 'data.sw.vowerp.com'){
        //     $servername = "3.7.255.145";
        //     $username = "reports";
        //     $password = "Reports@12345";
        //     $database = "vowsworks";
        //     $tenentId = "sw.vowerp.com"; 
        //     $serverIp = "https://tenantec2.vowerp.com";        
        // }else if($url == 'data.smartworks.vowerp.com'){
        //     $servername = "3.7.255.145";
        //     $username = "reports";
        //     $password = "Reports@12345";
        //     $database = "vowsw_capex_live";
        //     $tenentId = "smartworks.vowerp.com"; 
        //     $serverIp = "https://tenantec2.vowerp.com";
        }else if($url == 'data.cloud.vowerp.com'){
            $servername = "3.7.255.145";
            $username = "reports";
            $password = "Reports@12345";
            $database = "vowcloud";
            $tenentId = "cloud.vowerp.com"; 
            $serverIp = "https://tenantec2.vowerp.com";
        }else if($url == 'data.workplace.vowerp.com'){
            $servername = "3.7.255.145";
            $username = "reports";
            $password = "Reports@12345";
            $database = "vow_workplace";
            $tenentId = "workplace.vowerp.com"; 
            $serverIp = "https://tenantec2.vowerp.com";
        }else if($url == 'data.qasls.vowerp.com'){
            $servername = "3.7.255.145";
            $username = "reports";
            $password = "Reports@12345";
            $database = "vowqa";
            $tenentId = "qasls.vowerp.com"; 
            $serverIp = "https://qaapi.vowerp.com";
        }else if($url == 'data.qasw12.vowerp.com'){
            $servername = "3.7.255.145";
            $username = "reports";
            $password = "Reports@12345";
            $database = "vowdevproc";
            $tenentId = "qasw12.vowerp.com"; 
            $serverIp = "https://qaapi.vowerp.com";
        }else if($url == 'data.capextest.vowerp.com'){//https://capextest.cloud.vowerp.com/
            $servername = "13.232.34.218";
            $username = "devuser";
            $password = "VowDev!123";
            $database = "vowsw_capex_test";
            $tenentId = "capextest.vowerp.com"; 
            $serverIp = "https://tenantec2.vowerp.com";
        }

        $data = array(
            'servername' => $servername,
            'username' => $username,
            'password' => $password,
            'database' => $database,
            'tenentId' => $tenentId,
            'serverIp' => $serverIp,
        );
        return $data;
    }
}
