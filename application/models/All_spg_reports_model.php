<?php
class All_spg_reports_model extends CI_Model
{
    // Define the main table
    private $table = 'itemmaster im';

    // Define searchable columns for datatables
    private $column_order = array(null);
    private $column_search = array('');

    // Constructor to load the database
    public function __construct()
    {
        $this->load->database();
    }

    // Function to get the datatables query
    private function _get_datatables_query($mainmenuId, $submenuId, $companyId, $from_date, $to_date)
    {
        $sql = "
            SELECT
                drange.*,
                ddate.*,
                sm.*
            FROM
                (
                    SELECT
                        sdt.Q_CODE,
                        SUM(sdt.prd_a + sdt.prd_b + sdt.prd_c)/1000 AS PRODT,
                        ROUND(SUM(sdt.prd_a + sdt.prd_b + sdt.prd_c) / SUM(sdt.tarprda + sdt.tarprdb + sdt.tarprdc) * 100, 2) AS meff,
                        ROUND(SUM(sdt.mc_a + sdt.mc_b + sdt.mc_c), 0) AS tno_of_frms,
                        ROUND(SUM(sdt.prd_a + sdt.prd_b + sdt.prd_c) / 1000, 1) AS prod_per_mtt,
                        ROUND(SUM(sdt.prd_a + sdt.prd_b + sdt.prd_c) / SUM(sdt.winder), 0) AS mprod_per_winder,
                        ROUND(SUM(sdt.prd_a + sdt.prd_b + sdt.prd_c) / SUM(sdt.mc_a + sdt.mc_b + sdt.mc_c), 0) AS mprd_per_fram,
                        ROUND((SUM(sdt.hunprod) / 1000), 1) AS tar_prod_per_mt,
                        ROUND(SUM(sdt.hunprod) / SUM(sdt.mc_a + sdt.mc_b + sdt.mc_c), 0) AS prd_frm_a_countt,
                        ROUND(SUM(sdt.prd_a + sdt.prd_b + sdt.prd_c) / SUM(sdt.mc_a + sdt.mc_b + sdt.mc_c), 0) - ROUND(SUM(sdt.hunprod) / SUM(sdt.std_count) / SUM(sdt.mc_a + sdt.mc_b + sdt.mc_c) * SUM(sdt.std_count), 0) AS mvar_prd_fram,
                        ROUND(SUM((sdt.prd_a + sdt.prd_b + sdt.prd_c) / sdt.act_count * sdt.std_count) / SUM(sdt.mc_a + sdt.mc_b + sdt.mc_c), 0) AS mcon_std_cnt_prd_fram
                    FROM
                        EMPMILL12.spining_daily_transaction sdt
                    WHERE
                        sdt.tran_date BETWEEN '$from_date' AND '$to_date'
                    GROUP BY
                        sdt.Q_CODE
                ) drange
            LEFT JOIN (
                    SELECT
                        sdt.Q_CODE,
                        CONCAT(sdt.std_count, ' LBS ', '/', sm.subgroup_type, ' J ', '/', sm.SPINDLE) AS CTYPE,
                        SUM(sdt.prd_a + sdt.prd_b + sdt.prd_c) AS PROD,
                        ROUND(SUM(sdt.prd_a + sdt.prd_b + sdt.prd_c) / SUM(sdt.tarprda + sdt.tarprdb + sdt.tarprdc) * 100, 2) AS eff,
                        ROUND(SUM(sdt.hunprod) / SUM(sdt.act_count) * SUM(sdt.std_count) / SUM(sdt.mc_a + sdt.mc_b + sdt.mc_c), 0) AS prd_frm_n_count,
                        ROUND(SUM(sdt.hunprod) / SUM(sdt.std_count) / SUM(sdt.mc_a + sdt.mc_b + sdt.mc_c) * SUM(sdt.std_count), 0) AS prd_frm_a_count,
                        ROUND(SUM(sdt.hunprod) / 1000, 1) AS prod_per_mt,
                        SUM(sdt.act_count) AS act_countt,
                        ROUND(SUM(sdt.mc_a + sdt.mc_b + sdt.mc_c), 0) AS no_of_fram,
                        ROUND(SUM(sdt.prd_a + sdt.prd_b + sdt.prd_c) / 1000, 1) AS prod_per_mtt,
                        ROUND(SUM(sdt.prd_a + sdt.prd_b + sdt.prd_c) / SUM(sdt.winder), 0) AS prod_per_winder,
                        ROUND(SUM(sdt.prd_a + sdt.prd_b + sdt.prd_c) / SUM(sdt.mc_a + sdt.mc_b + sdt.mc_c), 0) AS prd_per_fram,
                        ROUND(SUM(sdt.prd_a + sdt.prd_b + sdt.prd_c) / SUM(sdt.mc_a + sdt.mc_b + sdt.mc_c), 0) - ROUND(SUM(sdt.hunprod) / SUM(sdt.std_count) / SUM(sdt.mc_a + sdt.mc_b + sdt.mc_c) * SUM(sdt.std_count), 0) AS var_prd_fram,
                        ROUND(SUM((sdt.prd_a + sdt.prd_b + sdt.prd_c) / sdt.act_count * sdt.std_count) / SUM(sdt.mc_a + sdt.mc_b + sdt.mc_c), 0) AS con_std_cnt_prd_fram
                    FROM
                        EMPMILL12.spining_daily_transaction sdt
                    LEFT JOIN EMPMILL12.spining_master sm ON
                        sdt.Q_CODE = sm.Q_CODE
                    WHERE
                        sdt.tran_date = '$to_date'
                    GROUP BY
                        sdt.Q_CODE,
                        sdt.std_count,
                        sm.subgroup_type,
                        sm.SPINDLE
                ) ddate ON drange.Q_CODE = ddate.Q_CODE
            LEFT JOIN EMPMILL12.spining_master sm ON drange.Q_CODE = sm.Q_CODE
            ORDER BY drange.Q_CODE";
//echo $sql;
        // Add search functionality
        if ($_POST['search']['value']) {
            foreach ($this->column_search as $item) {
                if ($i === 0) {
                    $sql .= " AND ($item LIKE '%" . $_POST['search']['value'] . "%' ";
                } else {
                    $sql .= " OR $item LIKE '%" . $_POST['search']['value'] . "%' ";
                }
                $i++;
            }
            $sql .= ")";
        }

        // Add order functionality
        if (isset($_POST['order'])) {
            $sql .= " ORDER BY " . $this->column_order[$_POST['order']['0']['column']] . " " . $_POST['order']['0']['dir'];
        } else if (isset($this->order)) {
            $order = $this->order;
            $sql .= " ORDER BY " . key($order) . " " . $order[key($order)];
        }

        return $sql;
    }

    // Function to get datatables data
    public function get_datatables($mainmenuId, $submenuId, $companyId, $from_date, $to_date)
    {
        $sql = $this->_get_datatables_query($mainmenuId, $submenuId, $companyId, $from_date, $to_date);
        if ($_POST['length'] != -1) {
            $sql .= " LIMIT " . $_POST['start'] . ", " . $_POST['length'];
        }
        $query = $this->db->query($sql);
        return $query->result();
    }

    // Function to count filtered results
    public function count_filtered($mainmenuId, $submenuId, $companyId, $from_date, $to_date)
    {
        $sql = $this->_get_datatables_query($mainmenuId, $submenuId, $companyId, $from_date, $to_date);
        $query = $this->db->query($sql);
        return $query->num_rows();
    }

    // Function to count all results
    public function count_all($mainmenuId, $submenuId, $companyId, $from_date, $to_date)
    {
        $this->db->from($this->table);
        return $this->db->count_all_results();
    }

    // Function for direct report generation
    public function directReport($pers)
    {
        $sql = "
            SELECT
    drange.*,
    ddate.*,
    sm.*,
    sg.*
FROM
    (
        SELECT
            sdt.Q_CODE,
            sg.GNAME,
            ROUND(SUM(sdt.prd_a + sdt.prd_b + sdt.prd_c) / 1000, 3) AS PRODT,
            ROUND(SUM(sdt.prd_a + sdt.prd_b + sdt.prd_c) / SUM(sdt.tarprda + sdt.tarprdb + sdt.tarprdc) * 100, 2) AS meff,
            ROUND(SUM(sdt.mc_a + sdt.mc_b + sdt.mc_c), 0) AS tno_of_frms,
            ROUND(SUM(sdt.prd_a + sdt.prd_b + sdt.prd_c) / 1000, 1) AS tprod_per_mtt,
            ROUND(SUM(sdt.prd_a + sdt.prd_b + sdt.prd_c) / SUM(sdt.winder), 0) AS mprod_per_winder,
            ROUND(SUM(sdt.prd_a + sdt.prd_b + sdt.prd_c) / SUM(sdt.mc_a + sdt.mc_b + sdt.mc_c), 0) AS mprd_per_fram,
            ROUND(SUM(sdt.hunprod) / 1000, 1) AS tar_prod_per_mt,
            ROUND(SUM(sdt.hunprod) / SUM(sdt.mc_a + sdt.mc_b + sdt.mc_c), 0) AS prd_frm_a_countt,
            ROUND(SUM(sdt.prd_a + sdt.prd_b + sdt.prd_c) / SUM(sdt.mc_a + sdt.mc_b + sdt.mc_c), 0) - ROUND(SUM(sdt.hunprod) / SUM(sdt.std_count) / SUM(sdt.mc_a + sdt.mc_b + sdt.mc_c) * SUM(sdt.std_count), 0) AS mvar_prd_fram,
            ROUND(SUM((sdt.prd_a + sdt.prd_b + sdt.prd_c) / sdt.act_count * sdt.std_count) / SUM(sdt.mc_a + sdt.mc_b + sdt.mc_c), 0) AS mcon_std_cnt_prd_fram
        FROM
            EMPMILL12.spining_daily_transaction sdt
        LEFT JOIN EMPMILL12.SPINGROP sg ON
            sdt.Q_CODE = sg.GCODE
        WHERE
            sdt.tran_date BETWEEN '".$pers['from_date']."' AND '".$pers['to_date']."'
        GROUP BY
            sdt.Q_CODE,
            sg.GNAME
    ) drange
LEFT JOIN (
    SELECT
        sdt.Q_CODE,
        sg.GNAME,
        CONCAT(sdt.std_count, 'LBS', '/', sm.subgroup_type, 'J', '/', sm.SPINDLE) AS CTYPE,
        SUM(sdt.prd_a + sdt.prd_b + sdt.prd_c) AS PROD,
        ROUND(SUM(sdt.prd_a + sdt.prd_b + sdt.prd_c) / SUM(sdt.tarprda + sdt.tarprdb + sdt.tarprdc) * 100, 2) AS eff,
        ROUND(SUM(sdt.hunprod) / SUM(sdt.act_count) * SUM(sdt.std_count) / SUM(sdt.mc_a + sdt.mc_b + sdt.mc_c), 0) AS prd_frm_n_count,
        ROUND(SUM(sdt.hunprod) / SUM(sdt.std_count) / SUM(sdt.mc_a + sdt.mc_b + sdt.mc_c) * SUM(sdt.std_count), 0) AS prd_frm_a_count,
        ROUND(SUM(sdt.hunprod) / 1000, 1) AS prod_per_mt,
        SUM(sdt.act_count) AS act_countt,
        ROUND(SUM(sdt.mc_a + sdt.mc_b + sdt.mc_c), 0) AS no_of_fram,
        ROUND(SUM(sdt.prd_a + sdt.prd_b + sdt.prd_c) / 1000, 1) AS prod_per_mtt,
        ROUND(SUM(sdt.prd_a + sdt.prd_b + sdt.prd_c) / SUM(sdt.winder), 0) AS prod_per_winder,
        ROUND(SUM(sdt.prd_a + sdt.prd_b + sdt.prd_c) / SUM(sdt.mc_a + sdt.mc_b + sdt.mc_c), 0) AS prd_per_fram,
        ROUND(SUM(sdt.prd_a + sdt.prd_b + sdt.prd_c) / SUM(sdt.mc_a + sdt.mc_b + sdt.mc_c), 0) - ROUND(SUM(sdt.hunprod) / SUM(sdt.std_count) / SUM(sdt.mc_a + sdt.mc_b + sdt.mc_c) * SUM(sdt.std_count), 0) AS var_prd_fram,
        ROUND(SUM((sdt.prd_a + sdt.prd_b + sdt.prd_c) / sdt.act_count * sdt.std_count) / SUM(sdt.mc_a + sdt.mc_b + sdt.mc_c), 0) AS con_std_cnt_prd_fram
    FROM
        EMPMILL12.spining_daily_transaction sdt
    LEFT JOIN EMPMILL12.spining_master sm ON
        sdt.Q_CODE = sm.Q_CODE
    LEFT JOIN EMPMILL12.SPINGROP sg ON
        sdt.Q_CODE = sg.GCODE
    WHERE
        sdt.tran_date = '".$pers['to_date']."'
    GROUP BY
        sdt.Q_CODE,
        sdt.std_count,
        sm.subgroup_type,
        sm.SPINDLE,
        sg.GNAME
) ddate ON
    drange.Q_CODE = ddate.Q_CODE
LEFT JOIN EMPMILL12.spining_master sm ON
    drange.Q_CODE = sm.Q_CODE
LEFT JOIN EMPMILL12.SPINGROP sg ON
    SUBSTR(drange.Q_CODE, 1, LENGTH(sg.GCODE)) = sg.GCODE
ORDER BY
    drange.Q_CODE";
//echo $sql;
        $query = $this->db->query($sql);
        if ($query->num_rows() > 0) {
            return $query->result();
        }
        return false;
    }

    public function directReportt($pers)
    {
  //      echo 'hhhh'.'===='.$pers['dpc'];
        $dp=$pers['dpc'];
  //      echo 'dpc value==='.$dp.'  praa';
    //    var_dump($pers);
        $sql = "
            SELECT
    AB.GCODE,
    AB.GNAME,
    AB.PROD AS PROD_A,
    AB.eff AS EFF_A,
    AB.prd_frm_n_count AS PRD_FRM_N_COUNT_A,
    AB.prd_frm_a_count AS PRD_FRM_A_COUNT_A,
    AB.prod_per_mt AS PROD_PER_MT_A,
    AB.act_countt AS ACT_COUNT_A,
    AB.no_of_fram AS NO_OF_FRAM_A,
    AB.prod_per_mtt AS PROD_PER_MTT_A,
    AB.prod_per_winder AS PROD_PER_WINDER_A,
    AB.prd_per_fram AS PRD_PER_FRAM_A,
    AB.var_prd_fram AS VAR_PRD_FRAM_A,
    AB.con_std_cnt_prd_fram AS CON_STD_CNT_PRD_FRAM_A,
    AC.PROD AS PROD_B,
    AC.eff AS EFF_B,
    AC.prd_frm_n_count AS PRD_FRM_N_COUNT_B,
    AC.prd_frm_a_count AS PRD_FRM_A_COUNT_B,
    AC.prod_per_mt AS PROD_PER_MT_B,
    AC.act_countt AS ACT_COUNT_B,
    AC.no_of_fram AS NO_OF_FRAM_B,
    AC.prod_per_mtt AS PROD_PER_MTT_B,
    AC.prod_per_winder AS PROD_PER_WINDER_B,
    AC.prd_per_fram AS PRD_PER_FRAM_B,
    AC.var_prd_fram AS VAR_PRD_FRAM_B,
    AC.con_std_cnt_prd_fram AS CON_STD_CNT_PRD_FRAM_B
FROM
    (
        SELECT
            sgp.GCODE,
            sgp.GNAME,
            SUM(sdt.prd_a + sdt.prd_b + sdt.prd_c) AS PROD,
            ROUND(SUM(sdt.prd_a + sdt.prd_b + sdt.prd_c) / SUM(sdt.tarprda + sdt.tarprdb + sdt.tarprdc) * 100, 2) AS eff,
            ROUND(SUM(sdt.hunprod) / SUM(sdt.act_count) * SUM(sdt.std_count) / SUM(sdt.mc_a + sdt.mc_b + sdt.mc_c), 0) AS prd_frm_n_count,
            ROUND(SUM(sdt.hunprod) / SUM(sdt.std_count) / SUM(sdt.mc_a + sdt.mc_b + sdt.mc_c) * SUM(sdt.std_count), 0) AS prd_frm_a_count,
            ROUND(SUM(sdt.hunprod) / 1000, 1) AS prod_per_mt,
            SUM(sdt.act_count) AS act_countt,
            ROUND(SUM(sdt.mc_a + sdt.mc_b + sdt.mc_c), 0) AS no_of_fram,
            ROUND(SUM(sdt.prd_a + sdt.prd_b + sdt.prd_c) / 1000, 1) AS prod_per_mtt,
            ROUND(SUM(sdt.prd_a + sdt.prd_b + sdt.prd_c) / SUM(sdt.winder), 0) AS prod_per_winder,
            ROUND(SUM(sdt.prd_a + sdt.prd_b + sdt.prd_c) / SUM(sdt.mc_a + sdt.mc_b + sdt.mc_c), 0) AS prd_per_fram,
            ROUND(SUM(sdt.prd_a + sdt.prd_b + sdt.prd_c) / SUM(sdt.mc_a + sdt.mc_b + sdt.mc_c), 0) - ROUND(SUM(sdt.hunprod) / SUM(sdt.std_count) / SUM(sdt.mc_a + sdt.mc_b + sdt.mc_c) * SUM(sdt.std_count), 0) AS var_prd_fram,
            ROUND(SUM((sdt.prd_a + sdt.prd_b + sdt.prd_c) / sdt.act_count * sdt.std_count) / SUM(sdt.mc_a + sdt.mc_b + sdt.mc_c), 0) AS con_std_cnt_prd_fram
        FROM
            EMPMILL12.spining_daily_transaction sdt
        LEFT JOIN
            EMPMILL12.spining_master sm ON sdt.Q_CODE = sm.Q_CODE
        LEFT JOIN
            EMPMILL12.SPINGROP sgp ON SUBSTR(sdt.Q_CODE, 1, LENGTH(sgp.GCODE)) = sgp.GCODE
        WHERE
            sdt.tran_date BETWEEN '".$pers['from_date']."' AND '".$pers['to_date']."'
        GROUP BY
            sgp.GCODE,
            sgp.GNAME
    ) AB
LEFT JOIN
    (
        SELECT
            sgp.GCODE,
            sgp.GNAME,
            SUM(sdt.prd_a + sdt.prd_b + sdt.prd_c) AS PROD,
            ROUND(SUM(sdt.prd_a + sdt.prd_b + sdt.prd_c) / SUM(sdt.tarprda + sdt.tarprdb + sdt.tarprdc) * 100, 2) AS eff,
            ROUND(SUM(sdt.hunprod) / SUM(sdt.act_count) * SUM(sdt.std_count) / SUM(sdt.mc_a + sdt.mc_b + sdt.mc_c), 0) AS prd_frm_n_count,
            ROUND(SUM(sdt.hunprod) / SUM(sdt.std_count) / SUM(sdt.mc_a + sdt.mc_b + sdt.mc_c) * SUM(sdt.std_count), 0) AS prd_frm_a_count,
            ROUND(SUM(sdt.hunprod) / 1000, 1) AS prod_per_mt,
            SUM(sdt.act_count) AS act_countt,
            ROUND(SUM(sdt.mc_a + sdt.mc_b + sdt.mc_c), 0) AS no_of_fram,
            ROUND(SUM(sdt.prd_a + sdt.prd_b + sdt.prd_c) / 1000, 1) AS prod_per_mtt,
            ROUND(SUM(sdt.prd_a + sdt.prd_b + sdt.prd_c) / SUM(sdt.winder), 0) AS prod_per_winder,
            ROUND(SUM(sdt.prd_a + sdt.prd_b + sdt.prd_c) / SUM(sdt.mc_a + sdt.mc_b + sdt.mc_c), 0) AS prd_per_fram,
            ROUND(SUM(sdt.prd_a + sdt.prd_b + sdt.prd_c) / SUM(sdt.mc_a + sdt.mc_b + sdt.mc_c), 0) - ROUND(SUM(sdt.hunprod) / SUM(sdt.std_count) / SUM(sdt.mc_a + sdt.mc_b + sdt.mc_c) * SUM(sdt.std_count), 0) AS var_prd_fram,
            ROUND(SUM((sdt.prd_a + sdt.prd_b + sdt.prd_c) / sdt.act_count * sdt.std_count) / SUM(sdt.mc_a + sdt.mc_b + sdt.mc_c), 0) AS con_std_cnt_prd_fram
        FROM
            EMPMILL12.spining_daily_transaction sdt
        LEFT JOIN
            EMPMILL12.spining_master sm ON sdt.Q_CODE = sm.Q_CODE
        LEFT JOIN
            EMPMILL12.SPINGROP sgp ON SUBSTR(sdt.Q_CODE, 1, LENGTH(sgp.GCODE)) = sgp.GCODE
        WHERE
            sdt.tran_date = '".$pers['to_date']."'
        GROUP BY
            sgp.GCODE,
            sgp.GNAME
    ) AC ON AB.GCODE = AC.GCODE
                where  
            AB.GCODE= '".$dp."'
            order by AB.GCODE";


            ///  AB.GCODE='".$dp."' order by AB.GCODE

//echo $dp;
//echo $sql;
        $query = $this->db->query($sql);
        if ($query->num_rows() > 0) {
            return $query->result();
        }
        return false;
    }



}
?>

