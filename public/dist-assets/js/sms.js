$(document).ready(function(){
	
	$('docs-date').on('changeDate', function(ev){
		$(this).datepicker('hide');
	});
});
function getMenuDit(mid,links){
	
	$.ajax({
		url : site_url + 'logfile/getMenuDit/',
		type: "GET",        
		dataType: "html",
		data:{mid:mid,links:links},
		success: function(data)
		{
			window.location.href =  site_url + links;
		}
		
	});
}


function formatDate(date) {
     var d = new Date(date),
         month = '' + (d.getMonth() + 1),
         day = '' + d.getDate(),
         year = d.getFullYear();

     if (month.length < 2) month = '0' + month;
     if (day.length < 2) day = '0' + day;

     return [day, month , year].join('-');
 }

function simpleArray(target){
    var arr = [];
    $.each(target, function(i, e){
        $.each(e, function(key, val){
            arr.push(key);
            arr.push(val);
        });
    });
    return arr;
}

function upperCaseF(a){
	setTimeout(function(){
		a.value = a.value.toUpperCase();
	}, 1);
}
function lowercaseF(a){
	setTimeout(function(){
		a.value = a.value.toLowerCase();
	}, 1);
}
function validate(event) {
 
  var controlKeys = [8, 9, 13, 37, 39, 46];
  var isControlKey = controlKeys.join(",").match(new RegExp(event.which));
  if (!event.which || (48 <= event.which && event.which <= 57) || isControlKey) {
	return;
  } else {
	event.preventDefault();
  }
}
function validate1(event) {
 
  var controlKeys = [8];
  var isControlKey = controlKeys.join(",").match(new RegExp(event.which));
  if (!event.which || (48 <= event.which && event.which <= 57) || isControlKey) {
	return;
  } else {
	event.preventDefault();
  }
}
function validateSpNum(obj){		
	var yourInput = $(obj).val();
	re = /[`~!@#$%^&*(0-9)_|+\-=?;:'",.<>\{\}\[\]\\\/]/gi;
	var isSplChar = re.test(yourInput);
	if(isSplChar)
	{
		var no_spl_char = yourInput.replace(/[`~!@#$%^&*(0-9)_|+\-=?;:'",.<>\{\}\[\]\\\/]/gi, '');
		$(obj).val(no_spl_char);
	}
}
function validateText(obj){		
	var yourInput = $(obj).val();
	re = /[`~!@#$%^&*()_|+\-=?;:'",.<>\{\}\[\]\\\/]/gi;
	var isSplChar = re.test(yourInput);
	if(isSplChar)
	{
		var no_spl_char = yourInput.replace(/[`~!@#$%^&*()_|+\-=?;:'",.<>\{\}\[\]\\\/]/gi, '');
		$(obj).val(no_spl_char);
	}
}
function isValidEmailAddress(emailAddress) {
	var pattern = /^([a-z\d!#$%&'*+\-\/=?^_`{|}~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]+(\.[a-z\d!#$%&'*+\-\/=?^_`{|}~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]+)*|"((([ \t]*\r\n)?[ \t]+)?([\x01-\x08\x0b\x0c\x0e-\x1f\x7f\x21\x23-\x5b\x5d-\x7e\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|\\[\x01-\x09\x0b\x0c\x0d-\x7f\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))*(([ \t]*\r\n)?[ \t]+)?")@(([a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|[a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF][a-z\d\-._~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]*[a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])\.)+([a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|[a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF][a-z\d\-._~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]*[a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])\.?$/i;
	if(pattern.test(emailAddress)){
		
	}else{
		alert("invalid email");
		$('#email').val('');
	}
	
	
};