	$('#education').keydown( function(e) {
        var key = e.charCode ? e.charCode : e.keyCode ? e.keyCode : 0;
        if(key == 13) {
            e.preventDefault();			
            $("#remarks").focus();
			
        }
    });

 	
$('#education').bind('input',function(){
var rate=$("#education").val();
var netwtml=$("#education").val();
//rate=parseFloat(rate);
//netwtml=parseFloat(netwtml);

	alert("aaaa");
	//if(isNaN(rate))
//		{
//			rate=0;
//		}
//	if(isNaN(netwtml))
//		{
//			netwtml=0;
//		}
 
//var gramt=(netwtml-rate)+1;

//$("#nobales").val(gramt);
});

$('#invno').bind('input',function(){
 var rate=$("#education").val();

	alert(rate);
	//if(isNaN(rate))
//		{
//			rate=0;
//		}
//	if(isNaN(netwtml))
//		{
//			netwtml=0;
//		}
 
//var gramt=(netwtml-rate)+1;

//$("#nobales").val(gramt);
});


$("#savein").click(function(event){

	var entry_date=$('.entry_date').val();
		alert("Please Entery Date!");
 	
	if(entry_date=='')
	{
		alert("Please Entery Date!");
		return false;
	}




});