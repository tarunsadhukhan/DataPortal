$(document).ready(function(){
////////////////////////////////////////////
higlightRow = function(row) {
	$(row).css({'background-color':'#EBF8A4','font-weight': 'Bold','color':''});
  setTimeout(function() {
    $(row).css({'background-color':'','font-weight': ''});
  }, 200);

}
///////////////
 $( ".date" ).datepicker({
        	format: "dd-M-yyyy",
			autoclose: true
			
    	});
			
////////////////////////////////
/* $("input[value='']").on('blur', function(e){
$("input:text[value='']").css({"background":"","font-weight":""});
$("input:text[value !='']").css({"background":"#FFCFBF","font-weight":"bold"});
});	
 */
 $(".number_input").live('keypress', function(e) {

    var KeyID = (window.event) ? event.keyCode : e.which;
	//alert(KeyID);
    if ((KeyID >= 66 && KeyID <= 90) || (KeyID >= 97 && KeyID <= 122) || (KeyID >= 33 && KeyID <= 47 && KeyID != 46 && KeyID != 45 ) ||
	   (KeyID >= 58 && KeyID <= 64) || (KeyID >= 91 && KeyID <= 96) || (KeyID >= 123 && KeyID <= 126) || (KeyID == 32)) {
        return false;
    }
    return true;

	});	
	$('#lot_no').focus();	
////////////////////////////////////////////RESET///////////////////////////////////////
$("#resettab").click(function(event){
$('#ltno').val('').focus().css("border-color", "");
$('#station').val('');
$('#quality').select2('val','');
$('#party').select2('val','');
$('#nobales').val('');
$('#tran_id').val('');
$('#nwtml').val('');
$('#truckno').val('');
	 $('#prnof').val('');
	 $('#prnto').val('');
	 $('#nobales').val(0);
	  
 $('#grwtml').val('');
	 $('#nwtml').val('');
	 $('#invno').val('');
 $('#tarewt').val('');
	 $('#shrwt').val('');
	 $('#rate').val('');
	 $('#gramt').val('');
	 $('#taxamt').val('');
	 $('#froth').val('');
	 $('#namt').val('');
	 $('#lrno').val('');
	 $('#grwtinv').val('');
	 $('#nwtinv').val('');
	 
 
	




$('#savetab').show();
$('#jute_entry_edit').hide();
$("#fileData td").css({"background-color": "",'color':'','font-weight' : ""});

return false;
});

///////////////////////////////////////////RESET END//////////////////////////////////////
$('#nwtml').on('change',function(){
alert();
alert("Please Enter Nok of Bales !");

return false;
});

$('#quality').on('change',function(){
var qlty_id=$('#quality option:selected').val();

var datString='qlty_id='+qlty_id;
$.ajax({
      type: "POST",
      url: "cotton_transaction_op_cl_stock.php",
      data: datString,
      success: function(data) {    
	  var x=eval('('+data+')');
	  $("#op_stock").val(x.op_bales);
	  $("#op_stock_kgs").val(x.op_kgs);
	  
	  }
	  });
return false;
});

$('#no_bales').bind('input',function(){
var trn_typ=$("#transaction_type option:selected").val();
var no_bales=$("#no_bales").val();
var op_stock=$("#op_stock").val();
	no_bales=parseInt(no_bales);
	if(isNaN(no_bales))
		{
			no_bales=0;
		}
op_stock=parseInt(op_stock);
if(isNaN(op_stock))
		{
			op_stock=0;
		}
if(trn_typ=='R')
{

var cl_stock=(op_stock+no_bales);
}
if(trn_typ=='I')
{

var cl_stock=(op_stock-no_bales);
}
if(trn_typ=='A')
{

var cl_stock=(op_stock+no_bales);
}
$("#cl_stock").val(cl_stock);
});


$('#transaction_type').on('change',function(){
$('#no_bales').trigger('input');
$('#qnty_kgs').trigger('input');
return false;
});


$('#qnty_kgs').bind('input',function(){
var trn_typ=$("#transaction_type option:selected").val();
var qnty_kgs=$("#qnty_kgs").val();
var op_stock_kgs=$("#op_stock_kgs").val();
	qnty_kgs=parseFloat(qnty_kgs);
	if(isNaN(qnty_kgs))
		{
			qnty_kgs=0;
		}
op_stock_kgs=parseFloat(op_stock_kgs);
if(isNaN(op_stock_kgs))
		{
			op_stock_kgs=0;
		}
if(trn_typ=='R')
{

var cl_stock_kgs=(op_stock_kgs+qnty_kgs);
}
if(trn_typ=='I')
{

var cl_stock_kgs=(op_stock_kgs-qnty_kgs);
}
if(trn_typ=='A')
{

var cl_stock_kgs=(op_stock_kgs+qnty_kgs);
}
$("#cl_stock_kgs").val(cl_stock_kgs);
});

/////////////////////////rate and amt
$('#rate').bind('input',function(){
var rate=$("#rate").val();
var netwtml=$("#nwtml").val();
	rate=parseFloat(rate);
netwtml=parseFloat(netwtml);

	
	if(isNaN(rate))
		{
			rate=0;
		}
	if(isNaN(netwtml))
		{
			netwtml=0;
		}
 
var gramt=(rate*netwtml);

$("#gramt").val(gramt);
});



/////////////////////////rate and amt





$('#nwtml').bind('input',function(){
	annana
var rate=$("#rate").val();
alert("Please Enter Nok of Bales !");
var netwtml=$("#nwtml").val();
	rate=parseFloat(rate);
netwtml=parseFloat(netwtml);

	
	if(isNaN(rate))
		{
			rate=0;
		}
	if(isNaN(netwtml))
		{
			netwtml=0;
		}
 
var gramt=(rate*netwtml);

$("#gramt").val(gramt);
});


$('#prnof').bind('input',function(){
var rate=$("#prnof").val();
var netwtml=$("#prnto").val();
rate=parseFloat(rate);
netwtml=parseFloat(netwtml);

	
	if(isNaN(rate))
		{
			rate=0;
		}
	if(isNaN(netwtml))
		{
			netwtml=0;
		}
 
var gramt=(netwtml-rate)+1;

$("#nobales").val(gramt);
});


$('#prnto').bind('input',function(){
var rate=$("#prnof").val();
var netwtml=$("#prnto").val();
rate=parseFloat(rate);
netwtml=parseFloat(netwtml);

	
	if(isNaN(rate))
		{
			rate=0;
		}
	if(isNaN(netwtml))
		{
			netwtml=0;
		}
 
var gramt=(netwtml-rate)+1;

$("#nobales").val(gramt);
});






////////////////////





 ///////////////////////////////////////////save data////////////////////////////////////g
$("#savetab").click(function(event){

var issue_date=$('#tran_date').val();
var lot_no=$('#ltno').val();

 if(lot_no=='')
	{
		alert("Please Enter Lot No !");
		$('#ltno').focus().css("border-color", "red");
		return false;
	}
	var station=$('#station').val();

var  quality= $('#quality option:selected').val();
if(quality=='')
	{
		alert("Please Select a Quality !");
		$('#s2id_autogen2').focus().css("border-color", "red");
		return false;
	}

var  party= $('#party option:selected').val();
if(party=='')
	{
		alert("Please Select Supplier !");
		$('#s2id_autogen2').focus().css("border-color", "red");
		return false;
	}


	
	var no_bales=$('#nobales').val();
if(no_bales=='')
	{
		alert("Please Enter Nok of Bales !");
		$('#nobales').focus().css("border-color", "red");
		return false;
	}
	
	var nwtml=$('#nwtml').val();
 
	var station=$('#station').val();

	var truckno=$('#truckno').val();
	var prnof=$('#prnof').val();
	var prnto=$('#prnto').val();
	var nobales=$('#nobales').val();
	  
	var grwtml=$('#grwtml').val();
	var nwtml=$('#nwtml').val();
	var invno=$('#invno').val();
	var tarewt=$('#tarewt').val();
	var shrwt=$('#shrwt').val();
	var rate=$('#rate').val();
	var gramt=$('#gramt').val();
	var taxamt=$('#taxamt').val();
	var froth=$('#froth').val();
	var namt=$('#namt').val();
	var lrno=$('#lrno').val();
	var grwtinv=$('#grwtinv').val();
	var nwtinv=$('#nwtinv').val();
	 
 
	
 
	

var datString='issue_date='+encodeURIComponent(issue_date)+'&quality='+encodeURIComponent(quality)+'&lot_no='+encodeURIComponent(lot_no)+'&station='+encodeURIComponent(station)+'&truckno='+encodeURIComponent(truckno)
+'&prnof='+encodeURIComponent(prnof)+'&prnto='+encodeURIComponent(prnto)+'&nobales='+encodeURIComponent(nobales)+'&party='+encodeURIComponent(party)+
'&lrno='+encodeURIComponent(lrno)+'&grwtinv='+encodeURIComponent(grwtinv)+'&nwtinv='+encodeURIComponent(nwtinv)+'&grwtml='+encodeURIComponent(grwtml)
+'&nwtml='+encodeURIComponent(nwtml)+'&invno='+encodeURIComponent(invno)+'&tarewt='+encodeURIComponent(tarewt)+'&shrwt='+encodeURIComponent(shrwt)
+'&rate='+encodeURIComponent(rate)+'&gramt='+encodeURIComponent(gramt)+'&taxamt='+encodeURIComponent(taxamt)+'&froth='+encodeURIComponent(froth)
+'&namt='+encodeURIComponent(namt);



	
	$.ajax({
      type: "POST",
      url: "jute_transaction_save.php",
      data: datString,
      success: function(data) {        

	$("#fileData").dataTable().fnDraw();
		alert(data);
		var str2 = "Record Saved Successfully !";
		
		if(data.indexOf(str2) != -1){
  		$(".dataTables_empty").parent('tr').remove();
		$('#resettab').click();
		 higlightRow("#fileData tr:nth-child(1) td");
		}
		$('#lot_no').focus();
			},
		error: function() {
		alert("TimeOut error !");
       var url_r = "index.php";    
window.location.href = url_r;
   }
			});
			return false;
			});

///////////////////////////////////////////save end//////////////////////////////////////////	
///////////////////////////////////////////edit start/////////////////////////////////////////
$("#jute_entry_edit").click(function(event){
	
var tran_id=$('#tran_id').val();	

if(tran_id =='')
{
alert("Please Select a Record !");
return false;
}
else
{

var issue_date=$('#tran_date').val();
var lot_no=$('#ltno').val();

 if(lot_no=='')
	{
		alert("Please Enter Lot No !");
		$('#ltno').focus().css("border-color", "red");
		return false;
	}
	var station=$('#station').val();

var  quality= $('#quality option:selected').val();
if(quality=='')
	{
		alert("Please Select a Quality !");
		$('#s2id_autogen2').focus().css("border-color", "red");
		return false;
	}

var  party= $('#party option:selected').val();
if(party=='')
	{
		alert("Please Select Supplier !");
		$('#s2id_autogen2').focus().css("border-color", "red");
		return false;
	}


	
	var nobales=$('#nobales').val();
if(nobales=='')
	{
		alert("Please Enter Nos of Bales !");
		$('#nobales').focus().css("border-color", "red");
		return false;
	}
	
	var nwtml=$('#nwtml').val();
 
	var station=$('#station').val();

	var truckno=$('#truckno').val();
	var prnof=$('#prnof').val();
	var prnto=$('#prnto').val();
	var nobales=$('#nobales').val();
	  
	var grwtml=$('#grwtml').val();
	var nwtml=$('#nwtml').val();
	var invno=$('#invno').val();
	var tarewt=$('#tarewt').val();
	var shrwt=$('#shrwt').val();
	var rate=$('#rate').val();
	var gramt=$('#gramt').val();
	var taxamt=$('#taxamt').val();
	var froth=$('#froth').val();
	var namt=$('#namt').val();
	var lrno=$('#lrno').val();
	var grwtinv=$('#grwtinv').val();
	var nwtinv=$('#nwtinv').val();
	 
 
	
 
	

var datString='issue_date='+encodeURIComponent(issue_date)+'&quality='+encodeURIComponent(quality)+'&lot_no='+encodeURIComponent(lot_no)+'&station='+encodeURIComponent(station)+'&truckno='+encodeURIComponent(truckno)
+'&prnof='+encodeURIComponent(prnof)+'&prnto='+encodeURIComponent(prnto)+'&nobales='+encodeURIComponent(nobales)+'&party='+encodeURIComponent(party)+
'&lrno='+encodeURIComponent(lrno)+'&grwtinv='+encodeURIComponent(grwtinv)+'&nwtinv='+encodeURIComponent(nwtinv)+'&grwtml='+encodeURIComponent(grwtml)
+'&nwtml='+encodeURIComponent(nwtml)+'&invno='+encodeURIComponent(invno)+'&tarewt='+encodeURIComponent(tarewt)+'&shrwt='+encodeURIComponent(shrwt)
+'&rate='+encodeURIComponent(rate)+'&gramt='+encodeURIComponent(gramt)+'&taxamt='+encodeURIComponent(taxamt)+'&froth='+encodeURIComponent(froth)
+'&namt='+encodeURIComponent(namt)+'&tran_id='+tran_id;

/* 
	var datString='issue_date='+encodeURIComponent(issue_date)+'&transaction_type='+encodeURIComponent(transaction_type)+'&lot_no='+encodeURIComponent(lot_no)+'&station='+encodeURIComponent(station)+'&quality='+encodeURIComponent(quality)+'&no_bales='+encodeURIComponent(no_bales)+'&qnty_kgs='+encodeURIComponent(qnty_kgs)+'&remarks='+encodeURIComponent(remarks)+'&rec_id='+rec_id;
	 */
	$.ajax({
      type: "POST",
      url: "jute_transaction_edit.php",
      data: datString,
      success: function(data) {        
$('#ltno').val('').focus().css("border-color", "");
	$("#fileData").dataTable().fnDraw();
		alert(data);
		var str2 = "Record Successfully Updated !";
		if (data.indexOf(str2) != -1){
  		$(".dataTables_empty").parent('tr').remove();
		$('#resettab').click();
		$("#ltno").focus();
		}
			},
		error: function() {
		alert("TimeOut error !");
       var url_r = "index.php";    
window.location.href = url_r;
   }
			});
			}
			return false;
			});

///////////////////////////////////////////edit end//////////////////////////////////////////
//////////search start///////////////////////////////////////////////
 $("#fileData td").live("click",function(event){

	$("#fileData td").css({"background-color": "",'font-weight' : ""});
$(this).closest('tr').children('td').css({ 'background-color' : '#CD5C5C','font-weight' : 'bolder' });   //change select tr background color
	
	oTable =  $('#fileData').dataTable( );
	var aPos   = oTable.fnGetPosition(this);
    var aData = oTable.fnGetData(aPos[0]);        
      
	  
	
	$("#tran_id").val(aData[0]);
	$("#tran_date").val(aData[1]);	
	
	//alert(tran_typ);
	
	$("#ltno").val(aData[2]);		

	$("#truckno").val(aData[4]);	
$("#lrno").val(aData[5]);	
	$("#prnof").val(aData[6]);
	$("#prnto").val(aData[7]);
	$("#nobales").val(aData[8]);	
			
	$("#grwtinv").val(aData[10]);
			$("#nwtinv").val(aData[11]);
		$("#grwtml").val(aData[12]);
			$("#nwtml").val(aData[13]);
		$("#invno").val(aData[14]);
			$("#tarewt").val(aData[15]);
				$("#shrwt").val(aData[16]);
		$("#station").val(aData[17]);
	
		$("#rate").val(aData[20]);
		$("#gramt").val(aData[21]);
		$("#taxamt").val(aData[22]);
		$("#froth").val(aData[23]);
		$("#namt").val(aData[24]);
	
	$("#quality").select2('val',(aData[18]));	
	$("#party").select2('val',(aData[19]));	
 


	
	//$("#curent_stock").val(aData[9]);	
				
	$("#savetab").hide();		
    $("#jute_entry_edit").show();	

	});
/////////////////////////////////////////////////search end////////////////////////////////////////////////
//////////////////////////////////////////delete//////////////////////////////////////////////
$("#deltab").click(function(event){

var tran_id=$("#tran_id").val();	
if(tran_id =='')
{
alert("Invalid Record ID !");
return false;
}
else
{
	
if(confirm('Are you sure you want to delete ?')){
var datString='tran_id='+tran_id;

$.ajax({
      type: "POST",
      url: "daily_jute_transaction_delete.php",
      data: datString,
      success: function(data) {
    
		$("#fileData").dataTable().fnDraw();
		alert(data);
		var str2 = "Record Successfully Deleted !";
		
		if(data.indexOf(str2) != -1){
  		$(".dataTables_empty").parent('tr').remove();
		$('#resettab').click();
		
		}

},
		error: function() {
		alert("TimeOut error !");
       var url_r = "index.php";    
window.location.href = url_r;
   }
});
}
}
return false;
});

////////////////////////////////////////delete end///////////////////////////////////////////////
/* $("#print_tab").live("click",function(event){

	var issue_date = $('#issue_date').val();
	
				var datString = 'issue_date='+issue_date;
	var url = "spining_daily_production_report.php?"+datString;    
			//$(location).attr('href',url);
			window.open( url, '_blank');
			
			
return false;
}); */
 
 //////////////////////////////////////////////////////
$('#lot_no').keydown( function(e) {
        var key = e.charCode ? e.charCode : e.keyCode ? e.keyCode : 0;
        if(key == 13) {
            e.preventDefault();			
            $("#station").focus();
			
        }
    });
	$('#station').keydown( function(e) {
        var key = e.charCode ? e.charCode : e.keyCode ? e.keyCode : 0;
        if(key == 13) {
            e.preventDefault();			
            $("#s2id_autogen2").focus();
			
        }
    });
	$('#s2id_autogen2').keydown( function(e) {
        var key = e.charCode ? e.charCode : e.keyCode ? e.keyCode : 0;
        if(key == 13) {
            e.preventDefault();			
            $("#no_bales").focus();
			
        }
    });
	$('#no_bales').keydown( function(e) {
        var key = e.charCode ? e.charCode : e.keyCode ? e.keyCode : 0;
        if(key == 13) {
            e.preventDefault();			
            $("#qnty_kgs").focus();
			
        }
    });
	$('#qnty_kgs').keydown( function(e) {
        var key = e.charCode ? e.charCode : e.keyCode ? e.keyCode : 0;
        if(key == 13) {
            e.preventDefault();			
            $("#remarks").focus();
			
        }
    });
	
	$('#remarks').keydown( function(e) {
        var key = e.charCode ? e.charCode : e.keyCode ? e.keyCode : 0;
        if(key == 13) {
            e.preventDefault();
			var rec_id=$('#rec_id').val();
           if(rec_id==''){
            $("#savetab").focus();
			}
			else
			{
			$('#jute_entry_edit').focus();
			}
        }
    });
 });//total doc ready


