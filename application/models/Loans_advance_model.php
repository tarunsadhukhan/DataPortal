<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Loans_advance_model extends CI_Model {

    public function getAllMasterDepartments() {
        // Replace this with your actual database query to fetch departments
        return $this->db->get('master_departments')->result();
    }

    public function getMCCodesByDepartment($department) {
        // Replace this with your actual database query to fetch MCCodes based on department
        $company_name = $this->session->userdata('companyname');
        $comp = $this->session->userdata('companyId');
        $this->db->select('mc_code_id, MC_OC_CODE, MC_DESCRIPTION');
        $this->db->where('DEPT_ID', $department);
        $this->db->where('company_id', $comp);
        $data=$this->db->get('EMPMILL12.MC_CODE_MASTER')->result();
     //   echo $this->db->last_query();
        
        return $data; 
            }
}