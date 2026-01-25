<?php
require_once(APPPATH . 'libraries/fpdf/fpdf.php');

class Fpdf_lib extends FPDF
{
    public function __construct()
    {
        parent::__construct();
    }
}
