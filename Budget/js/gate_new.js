
$( function() {
    $( ".datepickersh" ).datepicker({ dateFormat: 'dd-mm-yy' });
  } );

$(document).ready(function(){
	$( ".up_date_dtl" ).parent('td').siblings('td').children().prop( "disabled", true );

	
	$(document).on('click', '.up_date_dtl', function(e) {
		if($(this).is(":checked")){
			$( this ).parent('td').siblings('td').children().prop( "disabled", false );
			//$( this ).parent('td').siblings('td').children('select').prop( "disabled", false );
			
		}else{
			
			$( this ).parent('td').siblings('td').children().prop( "disabled", true );
		}
		
	});	
	
	$('#store_quantity,#weight,#quantity,#net_weight,#gross_weight,.weight,.quantity,#finish_quantity,#finish_weight,#phone_no').keyup(function(){
		var a=$(this).val();
		if(isNaN(a)){
			alert('Please Enter Number');
			$(this).val('');
			return false;
			
		}else{
			if(a<0){
				$(this).val('');
				
			}else{}
			
		}
		
	});
	
	$('#cls_tbl').click(function(){
		$('#tablepagesh').css('display','none');
		$('#toppagesh').css('display','block');
		
		
	});
	
	
	

	$(document).on('click', '.add_row', function(e) {	
	var id=$(this).parent().siblings('.hdr_id').text();
	//alert(id);
	
		$('.add_jute_entry_row_'+id).after('<tr><td colspan="11" style="padding:0;"><table cellpadding="1" cellspacing="1" class="table-two table-striped"><tbody><tr style="display: table-row;" class="trstyleone">'+
  
  '<td>'+

  '<table class="table-new-style">'+
  '<tbody>'+
     '<tr><td style="display:none" class="dtl_id"></td></tr>'+
   '<tr>'+
    '<td>Marka</td>'+
   '<td><input id="" value="" type="text" class="sh-inputstyle marka" placeholder="Enter your marka"></td>'+
   '</tr>'+
  '<tr>'+
   '<td>Quality</td>'+
   '<td><input id="" value="" type="text" class="sh-inputstyle quality" placeholder="Enter quality"></td>'+
   '</tr>'+

   '<tr>'+
   '<td>Quantity</td>'+
   '<td><input id="" type="text" value="11" class="sh-inputstyle col-sm-6 quantity" placeholder="quantity">'+
   '<select name="uom" id="" class="dist sh-select col-sm-6 uom">'+
            '<option value="BALES">BALES</option> <option value="HALF BALES">HALF BALES</option> <option value="PAKA BALES">PAKA BALES</option> <option value="DRUMS">DRUMS</option>'+
            '<option value="LOOSE">LOOSE</option> </select>'+
   '</td></tr></tbody></table></td><td>'+
  '<table class="table-new-style">'+
  '<tbody><tr>'+
    '<td>Weight</td>'+
   '<td><input id="" value="" type="text" class="sh-inputstyle weight" placeholder="Enter weight"></td>'+
   '</tr>'+
   '<tr>'+
   '<td>Remarks</td>'+
   '<td><textarea id="" class="sh-inputstyle remarks" placeholder="Enter remarks">ersert</textarea></td>'+
   '</tr>'+
      '<tr>'+
   '<td></td>'+
   '<td id="add_in_jute" align="right">'+
   '<button  class="sh-input tablebt btn add_to_jute">Save In</button>'+
   '</td>'+
   '</tr>'+

   



  '</tbody></table>'+


  '</td>'+
  
  '</tr></tbody></table></td></tr>');
		
		
	});
	
	
	$(document).on('click', '.add_to_finish', function(e) {	
	var id=$(this).parent().siblings('.hdr_id').text();
	//alert(id);
	
	$('.add_finish_row_'+id).after(
	'<tr><td colspan="8" style="padding:0;"><table cellpadding="1" cellspacing="1" class="table-two table-striped"><tbody><tr style="display: table-row;" class="trstyleone">'+
			
   
  '<td>'+
  '<table class="table-new-style">'+
  '<tbody>'+
   '<tr style="display:none"><td class="hrd_no">'+id+'</td></tr>'+
  '<tr>'+
   '<td>Lorry Pass No</td>'+
   '<td><input id="" value="" type="text" class="sh-inputstyle lorry_pass_no" placeholder=""></td>'+
  '</tr>'+
  
   '<tr>'+
   '<td>Quality</td>'+
   '<td><input id="" type="text" value="" class="sh-inputstyle cloth_quality" placeholder=""></td>'+
   '</tr>'+
   
  
   
 
   
   
  
   '<tr>'+
   '<td>Weight</td>'+
   '<td><input id="" type="text" value="" class="sh-inputstyle finish_weight" placeholder=""></td>'+
   '</tr>'+
   
  
  
   
   
   
  
  

  '</tbody>'+
  '</table>'+


  '</td>'+
    '<td>'+
  '<table class="table-new-style">'+
  '<tbody>'+
  '<tr>'+
   '<td>Quality Description</td>'+
    '<td><input id="" type="text" value="" class="sh-inputstyle goods_desc" placeholder=""></td>'+
   '</tr>'+
   '<tr>'+
   '<td>Quantity</td>'+
   '<td><input id="" type="text" value="" class="sh-inputstyle finish_quantity" placeholder=""></td>'+
   '</tr>'+
   
   '<td>  <select name="uom" id="" class="dist sh-select finish_uom"><option value="0">PAKA BALES</option>'+ 
      '<option value="BALES">BALES</option> <option value="HALF BALES">HALF BALES</option><option value="PAKA BALES">PAKA BALES</option> <option value="DRUMS">DRUMS</option><option value="LOOSE">LOOSE</option>'+               
                    
                     
                   
                     
                  '</select></td>'+
   
   '<tr>'+
   '<td></td>'+
   '<td id="add_in_jute" align="right">'+
   '<button id="add_new_finish" class="sh-input tablebt btn">Save In</button>'+
   '</td>'+
   
   '</tr>'+

   



  '</tbody></table>'+


  '</td>'+
  
  '</tr></tbody></table></td></tr>');
	
	});
	
	
	
	
	
	$(document).on('click', '#add_new_finish', function(e) {
		
		var data=[];
		
		
		
		var id=$(this).parent().parent().parent().parent().parent().parent();
		//var hrd_id=$(id).parent().parent().parent().parent().siblings('tr').children('.hdr_id').text();
		//alert(hrd_id);
		//$(id).siblings().css('border','10px solid red');
		
		
		$(id).parent().parent().parent().parent().closest('.hdr_id').text();
		var hrd_id=$(id).closest('td').find('.hrd_no').text();
		var lorry_pass_no=$(id).closest('td').find('.lorry_pass_no').val();
		//alert(lorry_pass_no);
	if(lorry_pass_no=='')
	{
		alert("Please Enter lorry  pass no!");
		$("#lorry_pass_no").focus();
		return false;
	}
	
	var cloth_quality=$(id).closest('td').find('.cloth_quality').val();
	if(cloth_quality=='')
	{
		alert("Please Enter cloth quality!");
		$("#cloth_quality").focus();
		return false;
	}
	
	
	var finish_quantity=$(id).closest('td').find('.finish_quantity').val();
	if(finish_quantity==''){}else{
	if(isNaN(finish_quantity)){
			alert('Please Enter Number');
			return false;
			
		}else{}
	}	
	if(finish_quantity=='')
	{
		alert("Please Enter cloth quality!");
		$("#finish_quantity").focus();
		return false;
	}
	
	
	var finish_weight=$(id).closest('td').find('.finish_weight').val();
	
	
	if(finish_weight=='')
	{
		//alert("Please Enter finish weight!");
		//$("#finish_weight").focus();
		//return false;
	}else{
		if(isNaN(finish_weight)){
			alert('Please Enter Number');
			return false;
			
		}else{}
		
	}
	var goods_desc=$(id).closest('td').find('.goods_desc').val();
	var goods_desc=$(id).closest('td').find('.goods_desc').val();
	if(goods_desc=='')
	{
		alert("Please Enter goods desc!");
		$("#goods_desc").focus();
		return false;
	}
	
	var finish_uom=$(id).closest('td').find('.finish_uom').val();
	
	var b =new Array(lorry_pass_no,cloth_quality,finish_quantity,finish_weight,goods_desc,finish_uom,hrd_id);
      data.push(b);
		
	


var arr=JSON.stringify(data);

console.log(data);


		//var dtl =new Array(store_vehicle_no,store_supp_name,store_driver_name,remarks,rec_id,item_name,store_quantity,store_dept,store_uom);
      



		var datString = 'hrd_id='+hrd_id+'&jsondata='+arr;
		
		
		//var datString = 'hrd_id='+hrd_id+'&jsondata='+arr;
		
		
$.ajax({
      type: "POST",
      url: "finishing_dispatch_savein_single.php",
	  data: datString,
      success: function(res) {
		 //alert(res);
		 alert('Record Saved Successfully');

	  
      },
		error: function() {
		alert("TimeOut error !");
       var url_r = "index.php";    
window.location.href = url_r;
   }
})

return false;
	
		
		
		
	});
	
	
	$(document).on('click', '.add_to_jute', function(e) {
		
		var data=[];
		
		
		
		var id=$(this).parent().parent().parent().parent().parent().parent();
		var hrd_id=$(id).parent().parent().parent().parent().siblings('tr').children('.hdr_id').text();
		
		$(id).parent().parent().parent().parent().closest('.hdr_id').text();
		//alert(hrd_id);
		//$(id).siblings().css('border','1px solid red');
	 var marka=((($(id).closest('td').find('.marka').val()).replace(/~\|~/gi, "~!~")).replace(/\|\|/gi, "!|")); 
	    //alert(marka);
	if(marka=='')
		{
		alert("Please Enter markas!");
		return false;
		}
		
		
		
		
		/*------end---------*/
		
		
		//alert($(this).closest('td').find('.quality').val());
		var quality=((($(id).closest('td').find('.quality').val()).replace(/~\|~/gi, "~!~")).replace(/\|\|/gi, "!|")); 
	if(quality=='')
		{
		alert("Please Enter quality!");
		return false;
		}
	//alert($(this).closest('td').find('.quantity').val());
	
	var quantity=((($(id).closest('td').find('.quantity').val()).replace(/~\|~/gi, "~!~")).replace(/\|\|/gi, "!|")); 
	
	if(quantity=='')
		{
		//alert("Please Enter quantity!");
		//return false;
		}else{
			
			if(isNaN(quantity)){
			alert('Please Enter Number');
			return false;
			
		    }else{}
			
			
		}
	
	//alert($(this).closest('td').find('.uom').val());
	var uom=((($(id).closest('td').find('.uom').val()).replace(/~\|~/gi, "~!~")).replace(/\|\|/gi, "!|")); 
	if(uom=='')
		{
		alert("Please Enter uom!");
		return false;
		}
	
	
	var weight=((($(id).closest('td').find('.weight').val()).replace(/~\|~/gi, "~!~")).replace(/\|\|/gi, "!|")); 
	
	
	if(weight=='')
		{
		//alert("Please Enter weight!");
		//return false;
		}else{
			if(isNaN(weight)){
			alert('Please Enter Number');
			return false;
			
		   }else{}
			
		}
		
		var remarks=((($(id).closest('td').find('.remarks').val()).replace(/~\|~/gi, "~!~")).replace(/\|\|/gi, "!|")); 
	if(remarks=='')
		{
		alert("Please Enter remarks!");
		return false;
		}
		
		var b =new Array(marka,quality,quantity,uom,weight,remarks);
      data.push(b);
		
	


var arr=JSON.stringify(data);

console.log(data);


		//var dtl =new Array(store_vehicle_no,store_supp_name,store_driver_name,remarks,rec_id,item_name,store_quantity,store_dept,store_uom);
      



		var datString = 'hrd_id='+hrd_id+'&jsondata='+arr;
		
		
$.ajax({
      type: "POST",
      url: "jute_entry_register_data_save_single.php",
	  data: datString,
      success: function(res) {
		 //alert(res);
		 
$(".marka").val('');
	
	
$(".quality").val('');
	


$(".quantity").val('');

$(".weight").val('');
$(".remarks").text('');

$(".uom").val('');
	  //$("#load").hide();	
		
	//$("#reset_jute_entry").click();
	//$("#jute_entry_search_button").click();
	alert(data);
      },
		error: function() {
		alert("TimeOut error !");
       var url_r = "index.php";    
window.location.href = url_r;
   }
});

return false;
});
    
	
  $(document).on('click', '.update,.update_finish', function(e) {	
	$(".update,.update_finish").each(function(){
        
      var rec_id=$(this).parent().siblings(".hdr_id").text();

       if($(this).is(':checked')){
		   
		   //$(this).parent().siblings().children('input').removeAttr("disabled");
	
	
	
	$('.dtl_'+rec_id).css('display','table-row');
	
	   }else{
		   $('.dtl_'+rec_id).css('display','none');
		   
	   }
	
	});
	
	});
	
	var elapsed_seconds = 0;
setInterval(function() {
  $("#entry_time").datepicker('setDate',new Date());
   var now = new Date();
   var outStr = ((now.getHours()<10?'0':'') + now.getHours() )+':'+((now.getMinutes()<10?'0':'') + now.getMinutes() )+':'+((now.getSeconds()<10?'0':'') + now.getSeconds() );
   $('#entry_time').val(outStr);
}, 1000);

var d = new Date();
var strDate =d.getDate().toString().replace(/(^.$)/,"0$1")+ "-" + (d.getMonth() + 1).toString().replace(/(^.$)/,"0$1") +"-"+ d.getFullYear();
////////////////////////////////////////////////////////////////////////////////////////////////////////////
			

	


    $('#entry_type').bind('change', function()
	{
		var entry_type=$('#entry_type option:selected').val();
		//alert(entry_type);
		var datString='entry_type='+entry_type;	
		
		
		   if (entry_type=='finishing_dispatch_register') {
			   window.location = "finishingsaleregister.php";
			
			} else if (entry_type=='visitor_register') {
				window.location = "visitorregistor.php";
	        } else if (entry_type=='jute_entry_register') {
                   window.location = "jute_entry_register.php";
            } else if (entry_type=='store_entry_register') {			
			      window.location = "store_entry_register.php";
			} else {
			       
			}

    });
	
	
	$('#reset_visitor').click(function(){
		
		$('#visitor_name').val('');
		$('#whom_to_meet').val('');
		$('#purpose').val('');
		$('#address').val('');
		$('#phone_no').val('');
		$('#results').css('display','none');
		return false;
		
	});
	
	$(document).on('click', '#shw_present_visitor', function(e) {	

	
	//$('#shw_present_visitor').click(function(){
		
		//alert('ok');
		
		/*var form_date = $("#visitorform_date").val();	
        var to_date = $("#visitorto_date").val();
	
		//var datString = 'form_date='+ form_date + '&to_date=' + to_date;
		
		$.ajax({
      type: "POST",
      url: "fetch_visitor_datewise.php",
      //data: datString,
      success: function(data) {  */      
        $('#myModaltwo').modal('show');
		
	   /*document.getElementById("shw_visitor").innerHTML = data;
	   
			//alert(data);
			},
		error: function() {
		alert("TimeOut error !");
       var url_r = "index.php";    
window.location.href = url_r;
   }
			});*/
		
		
	});
	
	
	//$("").click(function(event){
	$(document).on('click', '#finish_savein', function(e) {
		
		//alert('ok');
	var entry_da=$('.entry_date').val();
	var entry_time=$('#entry_time').val();	
	
	
	
	var finish_challan_no=$('#finish_challan_no').val();
	/*if(finish_challan_no=='')
	{
		alert("Please Enter challan no!");
		$("#finish_challan_no").focus();
		return false;
	}*/
	
	var finish_challan_date=$('#finish_challan_date').val();
	/*if(finish_challan_date=='')
	{
		alert("Please Enter challan date!");
		$("#finish_challan_date").focus();
		return false;
	}*/
	
	var whom_to_dispatch=$('#whom_to_dispatch').val();
	/*if(whom_to_dispatch=='')
	{
		alert("Please Enter whom to dispatch!");
		$("#finish_challan_date").focus();
		return false;
	}*/
	
	var finish_vehicle_no=$('#finish_vehicle_no').val();
	if(finish_vehicle_no=='')
	{
		alert("Please Enter Vehicle No!");
		$("#finish_vehicle_no").focus();
		return false;
	}
	var driver_name=$('#finish_driver_name').val();
	if(driver_name=='')
	{
		alert("Please Enter Driver Name!");
		$("#driver_name").focus();
		return false;
	}
	var transporter=$('#transporter').val();
	if(transporter=='')
	{
		alert("Please Enter Transporter!");
		$("#transporter").focus();
		return false;
	}
	
	var net_weight=$('#net_weight').val();
	
	if(net_weight=='')
	{
		//alert("Please Enter net weight!");
		//$("#net_weight").focus();
		//return false;
	}else{
		if(isNaN(net_weight)){
			alert('Please Enter Number');
			return false;
			
		}else{}
		
	}
	
	
	
	
	//lorry_pass_no,cloth_quality,finish_quantity,finish_uom,finish_weight,goods_descfinish_challan_no,finish_challan_date,whom_to_dispatch
	var datString='entry_date='+entry_da+'&entry_time='+entry_time+'&finish_vehicle_no='+finish_vehicle_no+'&transporter='+transporter+'&driver_name='+driver_name+'&finish_challan_no='+finish_challan_no+'&finish_challan_date='+finish_challan_date+'&whom_to_dispatch='+whom_to_dispatch+'&net_weight='+net_weight;
	//alert(datString);
	$.ajax({
      type: "POST",
      url: "finishing_dispatch_savein.php",
      data: datString,
      success: function(data) { 

           $('#finish_challan_no').val('');	
           $('#finish_challan_date').val('');	
           $('#finish_vehicle_no').val('');	
           $('#finish_driver_name').val('');	
           $('#transporter').val('');	       		   
		   $('#whom_to_dispatch').val('');	
            $('#net_weight').val('');		   
          
	
			alert('Record Saved Successfully');
			},
		error: function() {
		alert("TimeOut error !");
       var url_r = "index.php";    
window.location.href = url_r;
   }
			});
			return false;
			});




$(document).on('click', '#finish_search_button', function(e) {
 var form_date = $("#form_date").val();	
 var to_date = $("#to_date").val();
	
		var datString = 'form_date='+ form_date + '&to_date=' + to_date;
		
$.ajax({
      type: "POST",
      url: "finishing_dispatch_datewise.php",
      data: datString,
      success: function(data) {
		
		
		$('#myModal').modal('hide');
		
		$('#toppagesh').css('display','none');
		document.getElementById("shwidth").innerHTML = data;
		 
		$('#tablepagesh').css('display','block');
		
		
		
		//$('#dtl').css('display','none');
		
		//$("#show").append(data);
		
	 					 
	  
      //url: "finishing_dispatch_datewise_onselect.php",
      
		
		
		
		//alert(selected_hdr_id);
	$("#lorry_pass_no").val('');
	$("#cloth_quality").val('');
	$("#finish_quantity").val('');
	$("#finish_uom").val('');	
	$("#finish_weight").val('');
	$("#goods_desc").val('');	
	$("#fdtl_id").val('');
	$("#fhdr_id").val('');
	$("#fperm_id").val('');
		
	
	
	$("#finish_challan_no").attr('disabled', false);	
	$("#finish_challan_date").attr('disabled', false);	
	$("#cloth_quality").attr('disabled', false);	
	$("#whom_to_dispatch").attr('disabled', false);	
	$("#goods_desc").attr('disabled', false);	
	$("#finish_quantity").attr('disabled', false);


		
		},
		error: function() {
		alert("TimeOut error !");
       var url_r = "index.php";    
window.location.href = url_r;
   }
		});
		return false;
		});	

/*end*/

$(document).on('click', '#savet', function(e) {	
	 var data=[];
   
   var dtl_arr=[];
   var dtl_arr_new=[];

	   var rec_id=$("#hrd_id").val();
	   //alert(rec_id);
	  
	   
	   var finish_vehicle_no=((($('#finish_vehicle_no').val()).replace(/~\|~/gi, "~!~")).replace(/\|\|/gi, "!|")); 
	if(finish_vehicle_no=='')
		{
		alert("Please Enter  vehicle no!");
		return false;
		}
       //alert(finish_vehicle_no);
	
	
	var finish_driver_name=((($('#finish_driver_name').val()).replace(/~\|~/gi, "~!~")).replace(/\|\|/gi, "!|")); 
	if(finish_driver_name=='')
		{
		alert("Please Enter driver name!");
		return false;
		}

	
	var transporter=((($('#transporter').val()).replace(/~\|~/gi, "~!~")).replace(/\|\|/gi, "!|")); 
	if(transporter=='')
		{
		alert("Please Enter Transporter!");
		return false;
		}

		
		var whom_to_dispatch=((($('#whom_to_dispatch').val()).replace(/~\|~/gi, "~!~")).replace(/\|\|/gi, "!|")); 
	if(whom_to_dispatch=='')
		{
		alert("Please Enter whom to dispatch!");
		return false;
		}

		
		var gross_weight=((($('#gross_weight').val()).replace(/~\|~/gi, "~!~")).replace(/\|\|/gi, "!|")); 
	    var actual_weight=((($('#actual_weight').val()).replace(/~\|~/gi, "~!~")).replace(/\|\|/gi, "!|")); 

		//alert(gross_weight);
	
		
		
	  var dtl =new Array(rec_id,finish_vehicle_no,finish_driver_name,transporter,whom_to_dispatch,gross_weight,actual_weight);
      data.push(dtl);
	  
	  console.log(data);
	  $(".up_date_dtl_new").each(function(){
	    if($(this).is(':checked')){
		
			  //alert('ok');
        
         //alert(dtl_id_in);
        
				 
						
	

     
	var cloth_quality=((($(this).parent().siblings('td').children('.cloth_quality').val()).replace(/~\|~/gi, "~!~")).replace(/\|\|/gi, "!|"));
     //alert(cloth_quality);	
	if(cloth_quality=='')
	{
		//alert("Please Enter Quality !");
		$('.cloth_quality').focus();
		return false;
	}	
	var finish_quantity=((($(this).parent().siblings('td').children('.finish_quantity').val()).replace(/~\|~/gi, "~!~")).replace(/\|\|/gi, "!|")); 
	
	
	
	if(finish_quantity==''){
		
	}else{
		if(isNaN(finish_quantity)){
			alert('Please Enter Number');
			return false;
			
		}else{}
		
	}
	var finish_weight=((($(this).parent().siblings('td').children('.finish_weight').val()).replace(/~\|~/gi, "~!~")).replace(/\|\|/gi, "!|"));
    
	if(finish_weight==''){}else{
		if(isNaN(finish_weight)){
				alert('Please Enter Number');
				return false;
				
			}else{}	
		
    }		
	var goods_desc=((($(this).parent().siblings('td').children('.goods_desc').val()).replace(/~\|~/gi, "~!~")).replace(/\|\|/gi, "!|"));		
	var uom=((($(this).parent().siblings('td').children('.uom').val()).replace(/~\|~/gi, "~!~")).replace(/\|\|/gi, "!|"));
		var c =new Array(rec_id,cloth_quality,finish_quantity,finish_weight,goods_desc,uom);
        dtl_arr_new.push(c);
		}else{}
		
		});	
	
			
			 $(".up_date_dtl").each(function(){
	       if($(this).is(':checked')){
		
			  //alert('ok');
        
         //alert(dtl_id_in);
         var dtl_id=$(this).parent().parent().attr('class');
				 
			//alert(dtl_id);				
	

     
	var cloth_quality=((($(this).parent().siblings('td').children('.cloth_quality').val()).replace(/~\|~/gi, "~!~")).replace(/\|\|/gi, "!|"));
     //alert(cloth_quality);	
	if(cloth_quality=='')
	{
		//alert("Please Enter Quality !");
		$('.cloth_quality').focus();
		return false;
	}	
	var finish_quantity=((($(this).parent().siblings('td').children('.finish_quantity').val()).replace(/~\|~/gi, "~!~")).replace(/\|\|/gi, "!|")); 
	   
	  if(finish_quantity==''){}else{
	  if(isNaN(finish_quantity)){
			alert('Please Enter Number');
			return false;
			
		}else{}
	}
	
	
	var finish_weight=((($(this).parent().siblings('td').children('.finish_weight').val()).replace(/~\|~/gi, "~!~")).replace(/\|\|/gi, "!|"));
	 
	 if(finish_weight==''){}else{
	 if(isNaN(finish_weight)){
			alert('Please Enter Number');
			return false;
			
		}else{}
	}
	var goods_desc=((($(this).parent().siblings('td').children('.goods_desc').val()).replace(/~\|~/gi, "~!~")).replace(/\|\|/gi, "!|"));		
	var uom=((($(this).parent().siblings('td').children('.uom').val()).replace(/~\|~/gi, "~!~")).replace(/\|\|/gi, "!|"));		
		var b =new Array(rec_id,cloth_quality,finish_quantity,finish_weight,goods_desc,dtl_id,uom);
        dtl_arr.push(b);
		}else{}
		
		});		
			
		//	
	//});	
		
		console.log( dtl_arr);	
	
	  

		if(data.length > 0){ 
		
		 var all =JSON.stringify(data);
	     var sub=JSON.stringify(dtl_arr);
		 var sub_new=JSON.stringify(dtl_arr_new);
		$.ajax({
      type: "POST",
      url: "finishing_dispatch_edit.php",
	  data:'hrd='+all+'&dtl='+sub+'&dtl_new='+sub_new ,
 
      success: function(res) {
		  
		  //$('#toppagesh').css('display','none');
		  for(var i=0;i<data.length;i++){
				var id=data[i][0];
				$('.dtl_'+id).css('display','none');
				
			}
			
			//alert(res);
	
            alert('Record Successfully Updated');
			window.location.href='finishingsaleregister.php';
         },
		error: function() {
				alert("TimeOut error !");
			   var url_r = "index.php";    
		    window.location.href = url_r;
		   }
});

		}else{
			alert('Please select row');
		}
	
return false;
});
	
/*---end-----*/
$("#cap_imag").click(function(event){
	
	take_snapshot();
	alert('Image Saved');
	return false;
	
});



$("#savein").click(function(event){
	//take_snapshot();
	var entry_date=$('.entry_date').val();
	var entry_time=$('#entry_time').val();	
	
	if(entry_date=='')
	{
		alert("Please Entery Date!");
		return false;
	}
	
	var department=$('#deparment').val();
	if(department=='')
	{
		alert("Please Enter Department Name!");
		return false;
	}
	var visitor_name=$('#visitor_name').val();
	if(visitor_name=='')
	{
		alert("Please Enter Visitor Name!");
		return false;
	}
	var whom_to_meet=$('#whom_to_meet').val();
	if(whom_to_meet=='')
	{
		alert("Please Enter Whom To Meet!");
		return false;
	}
	var purpose=$('#purpose').val();
	if(purpose=='')
	{
		alert("Please Enter Purpose!");
		return false;
	}
	var address=$('#address').val();
	if(address=='')
	{
		alert("Please Enter Address!");
		return false;
	}
	var phone_no=$('#phone_no').val();
		if(phone_no.length<10)
		{
		alert("Invalid Phone No!");
		return false;
		}
		
		
		
	var datString='entry_date='+entry_date+'&entry_time='+entry_time+'&visitor_name='+visitor_name+'&whom_to_meet='+whom_to_meet+'&purpose='+purpose+'&address='+address+'&phone_no='+phone_no+'&department='+department;
	//alert(datString);
	$.ajax({
      type: "POST",
      url: "visitor_data_save.php",
      data: datString,
      success: function(data) {
        		  
       
		/////////create div forajax loading	

/////////create div forajax loading	
			
			
			//$("#visitor_search_button").click();



	//alert(data);
	
	 var dd=JSON.parse(data);
	  //alert(dd['qrcode']);
	 //console.log(data);
     $('#barcode_scan').attr('src',dd['qrcode']);
	 	
          $('#entry_date').val('');
		$('#visitor_name').val('');
		$('#whom_to_meet').val('');
		$('#purpose').val('');
		$('#address').val('');
		$('#phone_no').val('');
		$('#results').css('display','none');
	 $('#my_camera').css('display','block');
     $('#barcode_scan').attr('src','images/bercode.png');
	 window.location.href='visitorregistor.php';
	 
	/* var  mywindow = window.open('', 'my div', 'width=500');
	 mywindow.document.write('<html>');
                             mywindow.document.write('<head>'
                                 +'<link href="https://fonts.googleapis.com/css?family=Roboto:100,100i,300,300i,400,400i,500,500i,700,700i,900,900i" rel="stylesheet">'
+'<link rel="stylesheet" type="text/css" href="css/font-awesome.min.css">'
+'<link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">'
+'<link href="css/sb-admin.css" rel="stylesheet" type="text/css">'
+'<link rel="stylesheet" type="text/css" href="css/style.css">'
+'<link rel="stylesheet" type="text/css" href="css/sb-admin.css">'
                                 +'</head>');
                            mywindow.document.write('<body>');
                            
                            mywindow.document.write('<div class="border-style" style="width:30%;margin-left:5%;margin-right:10%;float:left;font-size:0.9em">');
                           //mywindow.document.write(document.getElementById("results").innerHTML);
						   var im=document.getElementById("results").innerHTML;
						   mywindow.document.write('<div>visitor name</div><div>'+visitor_name+'</div><div>'+im+'</div><div>Company Name:The Empire Jute Co. Ltd</div>');
                            mywindow.document.write('</div>'); 
                             
                           
                             mywindow.document.write('</body>');
                             mywindow.document.write('</html>');
                             mywindow.document.close(); // necessary for IE >= 10
                             mywindow.focus(); 
       
                             mywindow.print();
							 
			

    setTimeout(function(){  $("#reset_visitor").click();
     
	 }, 8000);		*/		

    	 
  	
},
		error: function() {
		alert("TimeOut error !");
       var url_r = "index.php";    
window.location.href = url_r;
   }
});	
	
return false;	
	});	
	
/*--end--*/	

$(document).on('click', '.saveout', function(e) {
//$("#saveout").click(function(event){
	
	//var entry_date=$('#entry_date').val();
	//var entry_time=$('#entry_time').val();	
	
	var rec_id=$(this).parent().siblings(".visitor_rec_id").text();
	//alert(rec_id);
	var id=$(this).parent().siblings(".visitor_rec_id");
	//var visitor_name=$(this).parent().siblings('.visitor_name').text();
	var remarks=$(this).parent('td').siblings('td').children('#remarks').val();
	//alert(remarks);
	//if(visitor_name=='')
	//{
	//	alert("Please Enter Visitor Name!");
		//return false;
	//}
	
	var datString='rec_id='+rec_id+'&remarks='+remarks;
	//alert(datString);
	$.ajax({
      type: "POST",
      url: "visitor_data_save_out.php",
      data: datString,
      success: function(data) {        
      id.parent().css('display','none');
	


	//alert(data);	
},
		error: function() {
		alert("TimeOut error !");
       var url_r = "index.php";    
window.location.href = url_r;
   }
});	
	
return false;	
	});



$("#visitor_search_button").click(function() {

 var form_date = $("#visitorform_date").val();	
 var to_date = $("#visitorto_date").val();
	
		var datString = 'form_date='+ form_date + '&to_date=' + to_date;
		
$.ajax({
      type: "POST",
      url: "fetch_visitor_datewise.php",
      data: datString,
      success: function(data) {
		
		
		document.getElementById("visitor_datewise").innerHTML = data;
					
$("#visitor_datewise td").click(function(event){
	
$("<div></div>").attr('id','load').appendTo('body');   
$("#load:hidden").fadeIn("slow");
		
            $("#visitor_datewise td").css("background-color", "");
			$(this).closest('tr').children('td').css({ 'background-color' : '#d0b173','font-weight' : 'bolder' });   //change select tr background color
	
    var select_rec_id= $(this).closest('tr').find('td:eq(2)').text();
    $("#visitor_rec_id").val(select_rec_id);
	
	var datString = 'select_rec_id='+select_rec_id; 
						 
	  $.ajax({
      type: "POST",
      url: "fetch_visitor_datewise_onselect.php",
      data: datString,
      success: function(data) { 
		
		document.getElementById("visitor_details").innerHTML = data;
		$("#load").hide();		
		$("#visitor_details td").click(function () 
		{  
                      		
		var selected_visitor_name= $(this).closest('tr').find('td:eq(1)').text();
		var selected_whom_to_meet= $(this).closest('tr').find('td:eq(2)').text();	
		var selected_purpose= $(this).closest('tr').find('td:eq(3)').text();
		var selected_address= $(this).closest('tr').find('td:eq(4)').text();
		var selected_phone_no= $(this).closest('tr').find('td:eq(5)').text();
		var selected_rec_id= $(this).closest('tr').find('td:eq(8)').text();				
	$("#visitor_name").val(selected_visitor_name);
	$("#whom_to_meet").val(selected_whom_to_meet);
	$("#purpose").val(selected_purpose);
	$("#address").val(selected_address);
	$("#phone_no").val(selected_phone_no);
	$("#visitor_rec_id").val(selected_rec_id);
	$("#visitor_details td").css("background-color", "");	
	$(this).closest('tr').children('td').css({ 'background-color' : '#d0b173','font-weight' : 'bolder' }); 
	$('#savein').hide();
	$('#saveout').show();

});	

	var YtableEmulator = document.getElementById('visitor_y-table-emulator');
var XtableEmulator = document.getElementById('visitor_x-table-emulator');
var table = document.getElementById('visitor_table_body');

YtableEmulator.style.height = table.clientHeight == 0 ? "0px" : table.clientHeight + "px";
XtableEmulator.style.width = table.clientWidth + "px";

var scrollablePanel = document.getElementById('visitor_scroll');
var headerContainer = document.getElementById('visitor_header-container');
//var footerContainer = document.getElementById('footer-container');
var YfakeScrollablePanel = document.getElementById('visitor_y-fake-scroll');
var XfakeScrollablePanel = document.getElementById('visitor_x-fake-scroll');


YfakeScrollablePanel.style.top = headerContainer.clientHeight == 0 ? "0px" : headerContainer.clientHeight + "px";
scrollablePanel.onscroll = function (e) {
  	XfakeScrollablePanel.scrollTop = scrollablePanel.scrollTop;
}
YfakeScrollablePanel.onscroll = function (e) {
  	scrollablePanel.scrollTop = YfakeScrollablePanel.scrollTop;
}
XfakeScrollablePanel.onscroll = function (e) {
  	scrollablePanel.scrollLeft = XfakeScrollablePanel.scrollLeft;
	headerContainer.scrollLeft = XfakeScrollablePanel.scrollLeft;
	footerContainer.scrollLeft = XfakeScrollablePanel.scrollLeft;
}
	}					 
					 
	});	
});
},
		error: function() {
		alert("TimeOut error !");
       var url_r = "index.php";    
window.location.href = url_r;
   }
});
return false;
});

/*--end--*/

$("#reset_visitor").click(function() {
$('#visitorform_date').val(strDate);
$('#visitorto_date').val(strDate);
$("#visitor_rec_id").val('');
$("#visitor_name").val('');
$("#visitor_name").focus();
$("#whom_to_meet").val('');
$("#purpose").val('');
$("#address").val('');
$("#phone_no").val('');
$('#savein').show();
$('#saveout').hide();
$("#visitor_search_button").click();
$("#visitor_details td").css("background-color", "");
$("#visitor_datewise td").css("background-color", "");

});

/*---end---*/




/*---end---*/
/*--jute entry--search--*/

/*$("#jute_entry_search_button").click(function() {

 var form_date = $("#jute_entry_form_date").val();	
 var to_date = $("#jute_entry_to_date").val();
	
		var datString = 'form_date='+ form_date + '&to_date=' + to_date;
		
$.ajax({
      type: "POST",
      url: "fetch_jute_entry_datewise.php",
      data: datString,
      success: function(data) {
		$('#myModal').modal('hide');
		$('table').css('display','block'); 
		$('#tablepagesh').css('display','block');
		$('#toppagesh').css('display','none');
		//$('.allheadingstyle').css('display','none');
		//$('#hrd').css('display','none');
		//$('#dtl').css('display','none');
		
		/*alert(JSON.parse(data));
		console.log(data);
		
		var jsonData = JSON.parse(data);
for (var i = 0; i < jsonData.hrd.length; i++) {
    var counter = jsonData.hrd[i];
    
}
console.log(counter);

	
		var jsonData = JSON.parse(data);
for (var i = 0; i < jsonData.dtl.length; i++) {
    var counter2 = jsonData.dtl[i];
    
}
console.log(counter2);*/


		/*alert(JSON.parse(data));
		//console.log(JSON.parse(data));
		
		var res=JSON.parse(data[dtl]);
		
		console(JSON.parse(data[dtl]));
		
		alert(data[dtl].length);*/
		
		
		/*document.getElementById("shwidth").innerHTML = data;
		
		////////////////////on select each td from search///////////////////


		
		},
		error: function() {
		alert("TimeOut error !");
       var url_r = "index.php";    
       window.location.href = url_r;
        }
		});
		return false;
		});*/
		
/*--end--*/

/*---jute entry save out---*/


	
	
$(document).on('click', '#finish_saveout', function(e) {

	var data=[];
	   
	  var entry_date=$('.entry_date').val();
	  var entry_time=$('#entry_time').val();
	  var finish_challan_no=$('#finish_challan_no').val();
	  //alert(finish_challan_no);
       if(finish_challan_no==''){
		   alert('Please input challan no');
		   return false;
		   
	   }else{}

	   var order_no=$('#order_no').val();
      
	   
      var finish_challan_date=$('#finish_challan_date').val();
       if(finish_challan_date==''){
		   alert('Please input challan date');
		   return false;
		   
	   }else{}


	   var finish_vehicle_no=$('#finish_vehicle_no').val();
       if(finish_vehicle_no==''){
		   alert('Please input vehicle no');
		   return false;
		   
	   }else{}
	   
	   var finish_driver_name=$('#finish_driver_name').val();
       if(finish_driver_name==''){
		   alert('Please input driver name');
		   return false;
		   
	   }else{}
	   
	   
	   var transporter=$('#transporter').val();
       if(transporter==''){
		   alert('Please input transporter');
		   return false;
		   
	   }else{}
	   
	   
	   var whom_to_dispatch=$('#whom_to_dispatch').val();
       if(whom_to_dispatch==''){
		   alert('Please input whom to dispatch');
		   return false;
		   
	   }else{}
	   
	   var gross_weight=$('#gross_weight').val();
	   if(gross_weight==''){}else{
       if(isNaN(gross_weight)){
			alert('Please Enter Number');
			return false;
			
		}else{}
	   
	   }
	   var actual_weight=$('#actual_weight').val();
       if(actual_weight==''){}else{
	   if(isNaN(actual_weight)){
			alert('Please Enter Number');
			return false;
			
		}else{}
	   }

	   
	  var rec_id=$("#hrd_id").val();
	  var dtl =new Array(rec_id,finish_challan_no,finish_challan_date,finish_vehicle_no,finish_driver_name,transporter,whom_to_dispatch,entry_date,entry_time,gross_weight,actual_weight,order_no);
      data.push(dtl);
  
      console.log(data);
	  
		
	if(data.length > 0){ 
	
	$.ajax({
      type: "POST",
      url: "finishing_dispatch_saveout.php",
      data: {jsondata:data},	


	
      success: function(data) {
	  //$("#load").hide();	
		
	//$("#finish_reset").click();
	//$("#finish_search_button").click();
      alert(data);
	  window.location.href='finishingsaleregister.php';
},
		error: function() {
		alert("TimeOut error !");
       var url_r = "index.php";    
window.location.href = url_r;
   }
});

	}else{
		
		alert('Please select row');
		
	}

return false;
});

$(document).on('click', '#finish_del', function(e) {
//$("#finish_del").click(function(event){
	
	var data=[];
   
$(".update").each(function(){
	if($(this).is(':checked')){
		
		var rec_id=$(this).parent().siblings(".hdr_id").text();
	  // alert(rec_id);


	$(".up_date_dtl").each(function(){
        


       if($(this).is(':checked')){
	
	//var entry_date=$(this).parent().siblings().children('#entry_date_td').val();
	  //var entry_time=$('#entry_time').val();	
	
	  var dtl =new Array(rec_id);
      data.push(dtl);
  
     console.log(data);
	   }else{}
	  }); 
	  
	   }else{}
	  }); 

	
if(data.length > 0){
$.ajax({
      type: "POST",
      url: "finishing_dispatch_delete.php",
      data:  {jsondata:data},
      success: function(res) {
        

	
			alert(res);
			for(var i=0;i<data.length;i++){
				var id=data[i][0];
				$('.dtl_'+id).css('display','none');
				
			}
			},
		error: function() {
		alert("TimeOut error !");
       var url_r = "index.php";    
window.location.href = url_r;
   }
			});
			
			
}else{}			
			
			return false;
			});	




function store_reset() {
//$('.entry_date').val('');
$('#item_name').val('');
$('#store_quantity').val('');
$('#store_dept').val('');
$('#store_uom').val('');
$("#store_challan_no").val('');
$('#store_challan_date').val('');
$('#store_vehicle_no').val('');
$('#store_driver_name').val('');
$('#store_supp_name').val('');
$('#remarks').val('');


$("#dtl_id").val('');
$("#hdr_id").val('');
$("#perm_id").val('');



 return false;
	}


/*$("#store_saveout").click(function(event){
	
	var entry_date=$('#entry_date').val();
	var entry_time=$('#entry_time').val();	
	var rec_id=$("#hdr_id").val();
		
	var datString='entry_date='+entry_date+'&entry_time='+entry_time+'&rec_id='+rec_id;
	//alert(datString);
	$.ajax({
      type: "POST",
      url: "store_entry_register_saveout.php",
      data: datString,
      success: function(data) {        

		/////////create div forajax loading	
//$("<div></div>").attr('id','load').appendTo('body');   
//$//("#load:hidden").fadeIn("slow");
/////////create div forajax loading	
		    //$("#store_reset").click();
			//$("#po_search_button").click();		
	
//$("#load").hide();		
		alert(data);		
		},
		error: function() {
		alert("TimeOut error !");
       var url_r = "index.php";    
window.location.href = url_r;
   }
	});	
return false;	
	});
	
	/*--end--*/

	
	


/*--end---*/
/*$(document).on('click', '#store_saveout', function(e) {	


	
	//var entry_date=$(this).parent().siblings().children('#entry_date_td').val();
	var entry_time=$('#entry_time').val();	
	var rec_id=$(this).parent().siblings("#hdr_id").text();
		
	var datString='entry_time='+entry_time+'&rec_id='+rec_id;
	alert(datString);
	$.ajax({
      type: "POST",
      url: "store_entry_register_saveout.php",
      data: datString,
      success: function(data) {        

		    //$("#store_reset").click();
			//$("#po_search_button").click();		
	
		
		alert(data);		
		},
		error: function() {
		alert("TimeOut error !");
       var url_r = "index.php";    
window.location.href = url_r;
   }
	});	
return false;	
	});
	
	*/
	
	$("#store_del").click(function(event){
		 var data=[];
		 var id=[];
		
		$(".update").each(function(){
        


       if($(this).is(':checked')){
		    $(this).parent().siblings().children('input').removeAttr("disabled");
			
	     var rec_id=$(this).parent().siblings(".hdr_id").text();
		 var del_id=$(this).parent().siblings(".hdr_id");
	      //alert(rec_id);
		  if(rec_id=='')
				{
				alert("Please Select a Record!");
				}
				else
				{
				if(confirm('Are you sure you want to delete?')){
						   var dtl =new Array(rec_id);
						  data.push(dtl);
						  id.push(del_id);
						  
				}else{}	
                }				
		   
	      }else{}
	   
		});
		
		
		
		


if(data.length > 0){


$.ajax({
      type: "POST",
      url: "store_entry_register_delete.php",
       data: {jsondata:data},
      success: function(res) {
		  
		  
		  
        for(var i=0;i<data.length;i++){
				var id=data[i][0];
				$('.dtl_'+id).css('display','table-row');
				
			}

/////////create div forajax loading			
					


	alert(res);
},
		error: function() {
		alert("TimeOut error !");
       var url_r = "index.php";    
window.location.href = url_r;
   }
});
}else{}
return false;
});




$(document).on('click', '#saverow', function(e) {

   var data=[];
   
   var dtl_arr=[];
   

		
		 var rec_id=$("#hrd_id").val();
	     //alert(rec_id);
	   
	   //$('.dtl_'+rec_id).css('display','block');

   var broker_name=((($('#broker').val()).replace(/~\|~/gi, "~!~")).replace(/\|\|/gi, "!|")); 
   //alert(broker_name);
	if(broker_name=='')
		{
		alert("Please Enter Broker Name!");
		return false;
		}

	
	
	var broker_address=((($('#broker_address').val()).replace(/~\|~/gi, "~!~")).replace(/\|\|/gi, "!|")); 
	if(broker_address=='')
		{
		alert("Please Enter Broker Address!");
		return false;
		}

	var po_no=((($('#po_no').val()).replace(/~\|~/gi, "~!~")).replace(/\|\|/gi, "!|")); 
	
		
		var gross_weight=((($('#gross_weight').val()).replace(/~\|~/gi, "~!~")).replace(/\|\|/gi, "!|"));
		if(gross_weight==''){}else{
		if(isNaN(gross_weight)){
			alert('Please Enter Number');
			return false;
			
		 }else{}
		}
		
		var net_weight=((($('#net_weight').val()).replace(/~\|~/gi, "~!~")).replace(/\|\|/gi, "!|"));
		if(net_weight!==''){
		if(isNaN(net_weight)){
			alert('Please Enter Number');
			return false;
			
		}else{}
		
		}else{}
		var accutual_weight=((($('#accutual_weight').val()).replace(/~\|~/gi, "~!~")).replace(/\|\|/gi, "!|"));
		if(accutual_weight==''){}else{
		if(isNaN(accutual_weight)){
			alert('Please Enter Number');
			return false;
			
		}else{}
		
		}
		//alert(net_weight);
		//alert(accutual_weight);
		
		var mukam=((($('#mukam').val()).replace(/~\|~/gi, "~!~")).replace(/\|\|/gi, "!|")); 
	if(mukam=='')
		{
		alert("Please Enter Mukam!");
		return false;
		}
		
		
		var driver_name=((($('#driver_name').val()).replace(/~\|~/gi, "~!~")).replace(/\|\|/gi, "!|")); 
	if(driver_name=='')
		{
		alert("Please Enter Driver Name!");
		return false;
		}

	var transporter=((($('#transporter').val()).replace(/~\|~/gi, "~!~")).replace(/\|\|/gi, "!|")); 
	if(transporter=='')
		{
		alert("Please Enter Transporter!");
		return false;
		}

		
		var vehicle_no=((($('#vehicle_no').val()).replace(/~\|~/gi, "~!~")).replace(/\|\|/gi, "!|")); 
	if(vehicle_no=='')
		{
		alert("Please Enter Vehicle No!");
		return false;
		}

		
		
		
			
		var fin_year=((($('#fin_year').val()).replace(/~\|~/gi, "~!~")).replace(/\|\|/gi, "!|")); 
	if(fin_year=='')
		{
		alert("Please Enter Fin year!");
		return false;
		}
		
		
		var dtl_arr_new=[];
		
	  $(".up_date_dtl_new").each(function(){
	    if($(this).is(':checked')){
		
		
		var marka=((($(this).parent().siblings('td').children('.marka').val()).replace(/~\|~/gi, "~!~")).replace(/\|\|/gi, "!|")); 	
	
	

       var quality=((($(this).parent().siblings('td').children('.quality').val()).replace(/~\|~/gi, "~!~")).replace(/\|\|/gi, "!|")); 	
	
       var quantity=((($(this).parent().siblings('td').children('.quantity').val()).replace(/~\|~/gi, "~!~")).replace(/\|\|/gi, "!|")); 
       
	   if(quantity==''){}else{
	   if(isNaN(quantity)){
			alert('Please Enter Number');
			return false;
			
		}else{}	   
	   }
	   var uom=((($(this).parent().siblings('td').children('.uom').val()).replace(/~\|~/gi, "~!~")).replace(/\|\|/gi, "!|")); 	
	   var weight=((($(this).parent().siblings('td').children('.weight').val()).replace(/~\|~/gi, "~!~")).replace(/\|\|/gi, "!|")); 	
	   var remarks=((($(this).parent().siblings('td').children('.remarks').val()).replace(/~\|~/gi, "~!~")).replace(/\|\|/gi, "!|")); 	
	
	
	
	  var c =new Array('',marka,quality,quantity,uom,weight,remarks);
      dtl_arr_new.push(c);
	
	     
	  
	   }else{}
	 
	

   });	
	      
	
	
	
	
	
		
		
	  var dtl =new Array(rec_id,broker_name,broker_address,transporter,vehicle_no,driver_name,fin_year,mukam,po_no,gross_weight,net_weight,accutual_weight);
      data.push(dtl);
	  
	  console.log(dtl);
       /*end-----*/
	   $(".up_date_dtl").each(function(){
	    if($(this).is(':checked')){
		var dtl_id=$(this).parent().parent().attr('class');
		
		var marka=((($(this).parent().siblings('td').children('.marka').val()).replace(/~\|~/gi, "~!~")).replace(/\|\|/gi, "!|")); 	
	
	

       var quality=((($(this).parent().siblings('td').children('.quality').val()).replace(/~\|~/gi, "~!~")).replace(/\|\|/gi, "!|")); 	
	
       var quantity=((($(this).parent().siblings('td').children('.quantity').val()).replace(/~\|~/gi, "~!~")).replace(/\|\|/gi, "!|")); 
        
		if(quantity==''){}else{
		if(isNaN(quantity)){
			alert('Please Enter Number');
			return false;
			
		}else{}		   
	   }
	   var uom=((($(this).parent().siblings('td').children('.uom').val()).replace(/~\|~/gi, "~!~")).replace(/\|\|/gi, "!|")); 	
	   var weight=((($(this).parent().siblings('td').children('.weight').val()).replace(/~\|~/gi, "~!~")).replace(/\|\|/gi, "!|")); 	
	   var remarks=((($(this).parent().siblings('td').children('.remarks').val()).replace(/~\|~/gi, "~!~")).replace(/\|\|/gi, "!|")); 	
	
	
	
	  var b =new Array(rec_id,marka,quality,quantity,uom,weight,remarks,dtl_id);
      dtl_arr.push(b);
	
	     
	  
	   }else{}
	 
	

   });	
	       

		
     
	  
	  console.log(data);
	   //console.log(dtl_arr);

	  if(data.length > 0){
       
   var all =JSON.stringify(data);
	var sub=JSON.stringify(dtl_arr);

    var add_dtl=JSON.stringify(dtl_arr_new);


		
		$.ajax({
      type: "POST",
      url: "jute_entry_register_data_edit.php",
	  data:'hrd='+all+'&dtl='+sub+'&add_dtl='+add_dtl ,
 
      success: function(res) {
		  
		  //$('#toppagesh').css('display','none');
	  //$("#load").hide();	
		
	//$("#finish_reset").click();
	//$("#finish_search_button").click();
	//alert(res);
	
	
  alert('Data Updated Successfully!');
  window.location.href='jute_entry_register.php';

},
		error: function() {
		alert("TimeOut error !");
		alert("TimeOut error !");
       var url_r = "index.php";    
window.location.href = url_r;
   }
});

	  }else{ alert('Please Select a Row');}



return false;
});
	
	
	

	
	
	/*end*/
	
	
	
	
$(document).on('click', '#savetab', function(e) {
	  
     var data=[];
   
     var dtl_arr=[];
	var dtl_arr_new=[];
		   
		   //$(this).parent().siblings().children('input').removeAttr("disabled");
	var rec_id=$("#hrd_id").val();
	//alert(rec_id);
	//$('.dtl_'+rec_id).css('display','block');

var store_vehicle_no=((($('#store_vehicle_no').val()).replace(/~\|~/gi, "~!~")).replace(/\|\|/gi, "!|")); 	
	if(store_vehicle_no=='')
	{
		alert("Please Store vehicle No !");
		$('#store_vehicle_no').focus();
		return false;
	}





	
	
	
	var store_supp_name=((($('#store_supp_name').val()).replace(/~\|~/gi, "~!~")).replace(/\|\|/gi, "!|")); 	
	if(store_supp_name=='')
	{
		alert("Please Store Challan Date!");
		$('#store_supp_name').focus();
		return false;
	}
	
	
	
	var store_driver_name=((($('#store_driver_name').val()).replace(/~\|~/gi, "~!~")).replace(/\|\|/gi, "!|")); 	
	if(store_driver_name=='')
	{
		alert("Please Store  Driver Name!");
		$('#store_driver_name').focus();
		return false;
	}
	
	var po=((($('#po').val()).replace(/~\|~/gi, "~!~")).replace(/\|\|/gi, "!|")); 	
	
	
	var remarks=((($('#remarks').val()).replace(/~\|~/gi, "~!~")).replace(/\|\|/gi, "!|")); 	
	
	
	var dtl =new Array(rec_id,store_vehicle_no,store_supp_name,store_driver_name,po,remarks);
     data.push(dtl);
	 
	 
	 
	 


 
 
	
	////
	
	$(".up_date_new_dtl").each(function(){
	if($(this).is(':checked')){
		
		//alert(dtl_id);
	  // $(".dtl_"+rec_id).each(function(){	

	   
	  /*alert($(this).closest('td').find('.marka').val());
	  var marka=((($(this).closest('td').find('.marka').val()).replace(/~\|~/gi, "~!~")).replace(/\|\|/gi, "!|")); 
	  
	if(marka=='')
		{
		alert("Please Enter markas!");
		return false;
		}
		*/
		var item_name=((($(this).parent().siblings('td').children('.item_name').val()).replace(/~\|~/gi, "~!~")).replace(/\|\|/gi, "!|")); 	
	
	  //alert(item_name);
	if(item_name=='')
	{
		alert("Please Item Name !");
		//$('#item_name').focus();
		//return false;
	}
	

var store_department=((($(this).parent().siblings('td').children('.store_department').val()).replace(/~\|~/gi, "~!~")).replace(/\|\|/gi, "!|")); 	
	if(store_department=='')
	{
		//alert("Please Store department !");
		//$('#store_department').focus();
		//return false;
	}

var store_quantity=((($(this).parent().siblings('td').children('.store_quantity').val()).replace(/~\|~/gi, "~!~")).replace(/\|\|/gi, "!|")); 

        if(store_quantity==''){}else{
		if(isNaN(store_quantity)){
			alert('Please Enter Number');
			return false;
			
		}else{}
       } 		
	if(store_quantity=='')
	{
		//alert("Please Store Quantity !");
		//$('#store_quantitye').focus();
		//return false;
	}
	
	
	
	
	var store_uom=((($(this).parent().siblings('td').children('.store_uom').val()).replace(/~\|~/gi, "~!~")).replace(/\|\|/gi, "!|")); 	
	if(store_uom=='')
	{
		alert("Please Store Quantity !");
		$('#store_uom').focus();
		return false;
	}
	
	
	var c =new Array(rec_id,item_name,store_department,store_quantity,store_uom);
      dtl_arr_new.push(c);
	
		
	  
	  
	  
	     
	  
	   }else{}
	 
	

   });	
	
	$(".up_date_dtl").each(function(){
	if($(this).is(':checked')){
		var dtl_id=$(this).parent().parent().attr('class');
		//alert(dtl_id);
	  // $(".dtl_"+rec_id).each(function(){	

	   
	  /*alert($(this).closest('td').find('.marka').val());
	  var marka=((($(this).closest('td').find('.marka').val()).replace(/~\|~/gi, "~!~")).replace(/\|\|/gi, "!|")); 
	  
	if(marka=='')
		{
		alert("Please Enter markas!");
		return false;
		}
		*/
		var item_name=((($(this).parent().siblings('td').children('.item_name').val()).replace(/~\|~/gi, "~!~")).replace(/\|\|/gi, "!|")); 	
	
	  //alert(item_name);
	if(item_name=='')
	{
		alert("Please Item Name !");
		//$('#item_name').focus();
		//return false;
	}
	

var store_department=((($(this).parent().siblings('td').children('.store_department').val()).replace(/~\|~/gi, "~!~")).replace(/\|\|/gi, "!|")); 	
	if(store_department=='')
	{
		//alert("Please Store department !");
		//$('#store_department').focus();
		//return false;
	}

var store_quantity=((($(this).parent().siblings('td').children('.store_quantity').val()).replace(/~\|~/gi, "~!~")).replace(/\|\|/gi, "!|"));

if(store_quantity==''){}else{
if(isNaN(store_quantity)){
			alert('Please Enter Number');
			return false;
			
		}else{} 


}		
	if(store_quantity=='')
	{
		//alert("Please Store Quantity !");
		//$('#store_quantitye').focus();
		//return false;
	}
	
	
	
	
	var store_uom=((($(this).parent().siblings('td').children('.store_uom').val()).replace(/~\|~/gi, "~!~")).replace(/\|\|/gi, "!|")); 	
	if(store_uom=='')
	{
		alert("Please Store Quantity !");
		$('#store_uom').focus();
		return false;
	}
	
	
	var b =new Array(rec_id,item_name,store_department,store_quantity,store_uom,dtl_id);
      dtl_arr.push(b);
	
		
	  
	  
	  
	     
	  
	   }else{}
	 
	

   });	
	
	

		
    
	  
       
  

console.log(data);
console.log(dtl_arr);


		
		
	
	
	if(data.length > 0){
		 var all =JSON.stringify(data);
	     var sub=JSON.stringify(dtl_arr);
		 var sub_new=JSON.stringify(dtl_arr_new);
		
	$.ajax({
      type: "POST",
      url: "store_entry_register_edit.php",
	  data:'hrd='+all+'&dtl='+sub+'&dtl_new='+sub_new ,
 
      success: function(res) {
	    //alert(res);
       alert('Data Successfully Updated!');
	   window.location.href='store_entry_register.php';
		/*for(var i=0;i<data.length;i++){
				var id=data[i][0];
				$('.dtl_'+id).css('display','none');
				
			}*/
},
		error: function() {
		alert("TimeOut error !");
       var url_r = "index.php";    
window.location.href = url_r;
   }
});


	}else{}


return false;
});


	$(document).on('click', '#jute_entry_saveout', function(e) {
		
		var data=[];
	
	
	//var entry_date=$('.entry_date').val();
	  //var entry_time=$('#entry_time').val();	
	  var rec_id=$("#hrd_id").val();
	  //alert(rec_id);
	  var dtl =new Array(rec_id);
      data.push(dtl);
  
     console.log(data);
	  
	
	var datString = 'jsondata=' +data;
	if(data.length > 0){
	$.ajax({
      type: "POST",
      url: "jute_entry_register_data_save_out.php",
      data: {jsondata:data},
      success: function(res) {   
           


		
		alert(res);	
		window.location.href='jute_entry_register.php';
		
		
		},
		error: function() {
		alert("TimeOut error !");
       var url_r = "index.php";    
window.location.href = url_r;
   }
	});
	

	}else{}
return false;	
	});
	
	
	
	
	$(document).on('click', '#store_saveout', function(e) {	

    var data=[];
	

       
	
	//var entry_date=$(this).parent().siblings().children('#entry_date_td').val();
	  var entry_time=$('#entry_time').val();	
	  var rec_id=$("#hrd_id").val();
	  var dtl =new Array(rec_id);
      data.push(dtl);
  
     console.log(data);
	   
		
		
	   //alert(rec_id);
	//var datString='entry_time='+entry_time+'&rec_id='+rec_id;
	//var datString = 'jsondata=' +data;
	
	//alert(datString);
	if(data.length > 0){
	$.ajax({
      type: "POST",
      url: "store_entry_register_saveout.php",
      data: {jsondata:data},
      success: function(data) { 
         window.location.href='store_entry_register.php';	  

		    //$("#store_reset").click();
			//$("#po_search_button").click();		
	
		
	alert(data);		
		},
		error: function() {
		alert("TimeOut error !");
       var url_r = "index.php";    
window.location.href = url_r;
   }
	});
	}else{}	
return false;	
	});
	
	
	
	
//$("#jute_entry_del").click(function(event){
$(document).on('click', '#jute_entry_del', function(e) {

 
   
  var data=[];
   
$(".update").each(function(){
	if($(this).is(':checked')){
		
		var rec_id=$(this).parent().siblings(".hdr_id").text();
	   //alert(rec_id);


	$(".up_date_dtl").each(function(){
        


       if($(this).is(':checked')){
	
	//var entry_date=$(this).parent().siblings().children('#entry_date_td').val();
	  //var entry_time=$('#entry_time').val();	
	  ///var rec_id=$(this).parent().siblings(".hdr_id").text();
	  var dtl =new Array(rec_id);
      data.push(dtl);
  
     console.log(data);
	   }else{}
	  }); 
	  
	   }else{}
	  }); 
		
	//var datString='entry_time='+entry_time+'&rec_id='+rec_id;
	//var datString = 'jsondata=' +data;
if(data.length > 0){
$.ajax({
      type: "POST",
      url: "jute_entry_register_delete.php",
      data:  {jsondata:data},
      success: function(res) {
        
		/////////create div forajax loading	


	//alert(res);
	for(var i=0;i<data.length;i++){
				var id=data[i][0];
				$('.dtl_'+id).css('display','table-row');
				
			}
	
},
		error: function() {
		alert("TimeOut error !");
       var url_r = "index.php";    
window.location.href = url_r;

		}
   
});

}else{}

return false;
});

	
/*end of document ready*/	
	
});