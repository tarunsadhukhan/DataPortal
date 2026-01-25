/* JUTE REPORTS ***/

/* JUTE WITH VALUE REPORT */

SELECT `values`.`Quality ID` as `Quality_ID`, `values`.`Quality Name`  as `Quality_Name`,
		SUM(`values`.`Receipt Bales`) as `Receipt_Bales`,
		SUM(`values`.`Issue Bales`) as `Issue_Bales` ,
		SUM(`values`.`Sold Bales`) as `Sold_Bales`,
		SUM(`values`.`Drums`) as `Drums` ,
		SUM(`values`.`Drums Issued`) as `Drums_Issued`,
		SUM(`values`.`Drums Sold`) as `Drums_Sold`,
		SUM(`values`.`Receipt Wt.(QNT)`) as `Receipt_Wt` ,
		SUM(`values`.`Issued Wt.(QNT)`) as `Issued_Wt`,
		SUM(`values`.`Sold Wt.(QNT)`) as `Sold_Wt`,
		SUM(`values`.`Avg. Issue Rate`) as `Avg_Issue_Rate`,
		SUM(`values`.`Issued Val (In Lakhs)`) as `Issued_Val`,
		SUM(`values`.`Opening Bales`) as `Opening_Bales`,
		SUM(`values`.`Opening Drums`) as `Opening_Drums`,
		SUM(`values`.`Opening Wt.(QNT)`) as `Opening_Wt`
		from (SELECT quality_code AS 'Quality ID',
		quality_name AS 'Quality Name',
		ROUND(SUM(bales_receipt), 2) AS 'Receipt Bales',
		ROUND(SUM(bales_issued), 2) AS 'Issue Bales',
		ROUND(SUM(bales_sold), 2) AS 'Sold Bales',
		ROUND(SUM(drums_receipt),2) AS 'Drums',
		ROUND(SUM(drums_issued),2) AS 'Drums Issued',
		ROUND(SUM(drums_sold), 2) AS 'Drums Sold',
		ROUND(SUM(accepted_weight),2) AS 'Receipt Wt.(QNT)',
		ROUND(SUM(weight_issued), 2) AS 'Issued Wt.(QNT)',
		ROUND(SUM(weight_sold), 2) AS 'Sold Wt.(QNT)',
		IFNULL(ROUND(SUM(weight_issued * rate) / SUM(weight_issued),2),0) AS 'Avg. Issue Rate',
		ROUND(SUM(weight_issued * rate) / 100000,2) AS 'Issued Val (In Lakhs)',0 AS 'Opening Bales',0 AS 'Opening Drums',0 AS 'Opening Wt.(QNT)'
		FROM view_jute_receipt_issue_sale
		WHERE company_id = 2 AND tran_date >= '2022-12-01' and tran_date<= '2022-12-31'  
		AND tran_status NOT IN (4 ,6) GROUP BY `quality_code` , `quality_name`  UNION  SELECT  quality_code AS 'Quality ID', quality_name AS 'Quality Name', 0 AS 'Receipt Bales', 0 AS 'Issue Bales', 0 AS 'Sold Bales', 0 AS 'Drums', 0 AS 'Drums Issued', 0 AS 'Drums Sold', 0 AS 'Receipt Wt.(QNT)', 0 AS 'Issued Wt.(QNT)', 0 AS 'Sold Wt.(QNT)', 0 AS 'Avg. Issue Rate', 0 AS 'Issued Val (In Lakhs)', ROUND(SUM(bales_receipt) -  SUM(bales_issued) - SUM(bales_sold), 2) AS 'Opening Bales', ROUND(SUM(drums_receipt)- SUM(drums_issued) - SUM(drums_sold), 2) AS 'Opening Drums', ROUND(SUM(accepted_weight) - SUM(weight_issued) - SUM(weight_sold), 2) AS 'Opening Wt.(QNT)'     
		FROM view_jute_receipt_issue_sale     
		WHERE  company_id = 2  and tran_date < '2022-12-01'   and tran_status not in (4,6)      
		GROUP BY `quality_code` ,`quality_name`  ) `values` GROUP BY `values`.`Quality ID`,`values`.`Quality Name` ORDER BY `values`.`Quality ID` LIMIT 0,10;




        /**** Jute Issue/Receipt Summary **/


        SELECT tran_date AS 'Issue_Date', jute_receive_no AS 'MR_No', quality_name as 'Quality', godown as 'Godown_ID', unit_conversion as 'Pack_Type', round(bales_issued+drums_issued, 2) as 'Quantity', round(weight_issued, 2) as 'Weight', 'QNT' as 'Unit', round(rate, 2) as 'Rate', round(issue_value, 2) as 'Issue_Value', mr_line_id as 'MR_Line_No', quality_code as 'Quality_ID', godown_name as 'Godown_Name', status_name as 'Status'
        FROM `view_jute_receipt_issue_sale`
        WHERE `transaction_type` = 'I'
        AND `company_id` = '2'
        AND `tran_date` >= '2022-12-01' and `tran_date` <= '2022-12-31'
        ORDER BY `tran_date` DESC
        LIMIT 10


        /**** Godown Wise Stock **/

        select a.godown as 'Godown_ID',i.item_desc,a.quality_name as 'Quality', 
		ROUND(SUM(a.bales_receipt - a.bales_sold - a.bales_issued),2) AS `Bales`, 
		ROUND(SUM(a.drums_receipt - a.drums_sold - a.drums_issued),2) AS `Drums`,    
		ROUND(SUM(a.accepted_weight - a.weight_sold - a.weight_issued),2) AS `Weight`,    'QNT',a.quality_code as 'Quality_ID',a.godown_name as 'Godown_Name' 
		from view_jute_receipt_issue_sale a    
		left join itemmaster i on i.company_id=a.company_id and i.group_code='999' and i.item_code=a.item_code 
		WHERE  a.company_id = 2 AND a.godown != '' 
		GROUP BY a.godown,a.godown_name,i.item_desc, a.quality_name,a.quality_code order by a.godown,a.godown_name ,i.item_desc, a.quality_name LIMIT 0,10;


        /**** MR Wise Report **/

        select i.invoice_date , 
		i.invoice_no_string , 
		i.customer_name , 
		u.first_name , 
		i.invoice_amount , 
		sm.status_name , 
		i.mr_id , 
		s.supp_name ,
		m.mr_print_no , 
		m.gate_entry_no , 
		m.jute_receive_dt , 
		mm.mukam_name , 
		i.sale_no , 
		i.unit_conversion 
		from 
		invoice_hdr i , 
		scm_mr_hdr m , 
		user_details u , 
		suppliermaster s , 
		status_master sm , 
		mukam mm 
		where m.jute_receive_no=i.mr_id 
		and u.user_id=i.created_by 
		and s.supp_code=m.supp_code 
		and s.company_id=m.company_id 
		and sm.status_id=i.status 
		and mm.mukam_id=m.mukam_id 
		and i.is_active=1 
		and m.company_id=2 AND i.invoice_date >= '2022-12-01' and i.invoice_date<= '2022-12-31' LIMIT 0,10;



    /*******   % Of Claims (JA01)   *****/

        SELECT supp_code AS 'Supp_Code',supp_name AS 'Supplier_Name',
			SUM(mr_count) AS `Total_MR`,
			SUM(pass_count) AS `Total_Pass`,SUM(claim_count) AS `Total_Claim`,
			ROUND(AVG(pass_per),2) AS 'Pass_percent',
			ROUND(AVG(claim_per),2) AS 'Claim_percent'
			FROM vJute_supp_claim_analysis a 
			where company_id=2  AND jute_receive_dt >= '2022-12-01' and jute_receive_dt<= '2022-12-31'; 
			GROUP BY supp_code,supp_name LIMIT 0,10;


    /*********   Claim Deviation(JA02)   ******/

    SELECT 
		supp_code AS `SUPP_CODE`,
		supp_name AS `SUPPLIER_NAME`,
		mr_print_no AS `MR_NO`,
		jute_receive_dt AS `MR_DATE`,
		item_desc AS `JUTE_TYPE`,
		jute_quality AS `QUALITY`,
		claims_condition+claim_dust AS `CONDITION`,
		actual_shortage AS `ADVISED_CLAIM_KGS`,
		shortage_kgs AS `ACTUAL_CLAIM_KGS`,
		actual_shortage-shortage_kgs AS `DEVIATION_KGS`
		FROM claim_deviation
		where company_id=2  AND jute_receive_dt >= '2022-12-01' and jute_receive_dt<= '2022-12-31'  ORDER BY jute_receive_dt LIMIT 0,10;




    /*********   Mukham Deviation(JA03)   ******/


        SELECT `mma`.`supp_code` AS `Supp_Code`, `mma`.`supp_name` AS `Supplier_Name`, `mma`.`mukam_name` AS `Mukham`, ROUND(AVG(mma.claims_condition), 2) AS `Avg_Supplied_Moisture`, `mma`.`moisture` AS `Avg_Mukam_Moisture`, round(AVG(mma.claims_condition)-mma.moisture, 2) AS `Deviation`
        FROM `mukham_moisture_analysis` `mma`
        WHERE `company_id` = '2'
        AND `jute_receive_dt` >= '2022-12-01' and `jute_receive_dt` <= '2022-12-31'
        GROUP BY `supp_code`, `supp_name`, `mukam_name`, `mma`.`moisture`
        LIMIT 10;

    /***************  Month Wise Jute Receipt and Issue *****/

    SELECT     
		extract(year_month from tran_date) as 'Year_Month',    
		round(sum(bales_receipt),2) as 'Bales',   
		round(sum(bales_issued),2) as 'Issue_Bales',  
		round(sum(bales_sold),2) as 'Sold_Bales',   
		round(sum(drums_receipt),2) as 'Drums',   
		round(sum(drums_issued),2) as 'Drums_Issued',   
		round(sum(drums_sold),2) as 'Drums_Sold',   
		round(sum(accepted_weight),2) as 'Receipt_Wt_QNT',   
		round(sum(weight_issued),2) as 'Issued_Wt_QNT',   
		round(sum(weight_sold),2) as 'Sold_Wt_QNT' 
		FROM  view_jute_receipt_issue_sale
		where company_id=2  AND tran_date >= '2022-12-01' and tran_date<= '2022-12-31' and tran_status not in (4,6)  
		GROUP BY extract(year_month from tran_date) LIMIT 0,10;


    /**************  Day Wise Jute Receipt and Issue *****/




    SELECT     
		tran_date,    
		round(sum(bales_receipt),2) as 'Bales',   
		round(sum(bales_issued),2) as 'Issue_Bales',  
		round(sum(bales_sold),2) as 'Sold_Bales',   
		round(sum(drums_receipt),2) as 'Drums',   
		round(sum(drums_issued),2) as 'Drums_Issued',   
		round(sum(drums_sold),2) as 'Drums_Sold',   
		round(sum(accepted_weight),2) as 'Receipt_Wt_QNT',   
		round(sum(weight_issued),2) as 'Issued_Wt_QNT',   
		round(sum(weight_sold),2) as 'Sold_Wt_QNT' 
		FROM  view_jute_receipt_issue_sale
		where company_id=2  AND tran_date >= '2022-12-01' and tran_date<= '2022-12-31' and tran_status not in (4,6) 
		GROUP BY tran_date LIMIT 0,10;



    /************* MR Wise Sales Report  ************/


        SELECT     
		extract(year_month from tran_date) as 'Year_Month',    
		round(sum(bales_receipt),2) as 'Bales',   
		round(sum(bales_issued),2) as 'Issue_Bales',  
		round(sum(bales_sold),2) as 'Sold_Bales',   
		round(sum(drums_receipt),2) as 'Drums',   
		round(sum(drums_issued),2) as 'Drums_Issued',   
		round(sum(drums_sold),2) as 'Drums_Sold',   
		round(sum(accepted_weight),2) as 'Receipt_Wt_QNT',   
		round(sum(weight_issued),2) as 'Issued_Wt_QNT',   
		round(sum(weight_sold),2) as 'Sold_Wt_QNT' 
		FROM  view_jute_receipt_issue_sale
		where company_id=2  AND tran_date >= '2022-12-01' and tran_date<= '2022-12-31' and tran_status not in (4,6)  
		GROUP BY extract(year_month from tran_date) LIMIT 0,10;



/************* MR in Stock Date 13-12-2022 Report  ************/


        SELECT
	a.tran_date AS 'MR_Date',
	a.jute_receive_no AS 'MR_No',
	a.quality_code as 'Quality_ID',
	a.quality_name as 'Quality',
	a.godown as 'Godown_ID',
	a.godown_name as 'Godown_Name',
	a.status_name as 'Status',
   b.MRLineNo as 'MR_Line_No',b.Bales, b.`Issue Bales` as 'Issue_Bales',
   b.`Sold Bales`as 'Sold_Bales',b.`Bales Stock`as'Bales_Stock',b.Drums,b.`Drums Issued`as 'Drums_Issued',
   b.`Drums Sold`as'Drums_Sold',b.`Drums Stock`as 'Drums_Stock',b.`Receipt Wt.(QNT)`as 'Receipt_Wt',
   b.`Issued Wt.(QNT)`as 'Issued_Wt',b.`Sold Wt.(QNT)`as'Sold_Wt',b.`Stock (QNT)`as'Stock_Qnt'
FROM
 view_jute_receipt_issue_sale a
left join
	 (select
	 mr_line_id as 'MRLineNo',
	 round(sum(bales_receipt),
			 2) as 'Bales',
	 round(sum(bales_issued),
			 2) as 'Issue Bales',
	 round(sum(bales_sold),
			 2) as 'Sold Bales',
	 round(sum(bales_receipt)-sum(bales_issued)-sum(bales_sold),
			 2) as 'Bales Stock',
	 round(sum(drums_receipt),
			 2) as 'Drums',
	 round(sum(drums_issued),
			 2) as 'Drums Issued',
	 round(sum(drums_sold),
			 2) as 'Drums Sold',
	 round(sum(drums_receipt)-sum(drums_issued)-sum(drums_sold),
			 2) as 'Drums Stock',
	 round(sum(accepted_weight),
			 2) as 'Receipt Wt.(QNT)',
	 round(sum(weight_issued),
			 2) as 'Issued Wt.(QNT)',
	 round(sum(weight_sold),
			 2) as 'Sold Wt.(QNT)',
	 round(sum(accepted_weight)-sum(weight_issued)-sum(weight_sold),
			 2) as 'Stock (QNT)'
 from
	 view_jute_receipt_issue_sale
 where
	 tran_date <='2022-12-13' 
 group by
	 mr_line_id
	 ) b
		 on
 b.MRLineNo = a.mr_line_id
where
 a.transaction_type = 'R'
 and a.tran_status not in  (4,6)
 and a.company_id='2'
 and (round(b.`Bales Stock`, 0) !=0
	 or round(b.`Drums Stock`, 0) !=0)
 and a.tran_date <='2022-12-13' order by a.tran_date desc LIMIT 0,10;



 /**************************JUTE REPORTS END ***********************/