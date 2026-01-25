 $(function(){
    $('.slide-toggle').on('click', function(){
        $('#sidebar').toggle('slide', { direction: 'left' }, 1000);
        $('#main-content').animate({
            'margin-left' : $('#main-content').css('margin-left') == '0px' ? '-110px' : '0px'
        }, 1000);
    });
});



  tday=new Array("Sunday","Monday","Tuesday","Wednesday","Thursday","Friday","Saturday");
tmonth=new Array("January","February","March","April","May","June","July","August","September","October","November","December");

function GetClock(){
var d=new Date();
var nday=d.getDay(),nmonth=d.getMonth(),ndate=d.getDate(),nyear=d.getFullYear();
var nhour=d.getHours(),nmin=d.getMinutes(),nsec=d.getSeconds(),ap;

if(nhour==0){ap=" AM";nhour=12;}
else if(nhour<12){ap=" AM";}
else if(nhour==12){ap=" PM";}
else if(nhour>12){ap=" PM";nhour-=12;}

if(nmin<=9) nmin="0"+nmin;
if(nsec<=9) nsec="0"+nsec;

document.getElementById('clockbox').innerHTML=""+nhour+":"+nmin+":"+nsec+ap+"";
}

window.onload=function(){
GetClock();
setInterval(GetClock,1000);
}

$( function() {
    $( ".datepicker" ).datepicker({ dateFormat: 'dd-mm-yy' });
	$( ".datepicker" ).datepicker("setDate", new Date());
	
  } );
  function horzScrollbarDetect() {
  
  var $scrollable = $('.scrollable')
  var $innerDiv = $('.scrollable > div');
  
  if ($innerDiv.outerWidth() < $innerDiv.get(0).scrollWidth) {
    
    $scrollable.addClass('is-scrollable');
    console.log('Scrollbar, WOOT!')
    
  } else {
    
    $scrollable.removeClass('is-scrollable');
    console.log('There is no scrollbar, only Zuul');
    
  }
  
}

$(document).ready(function() {
  
  horzScrollbarDetect();
  console.log('document. boom. ready.')
  
});

$(window).resize(function() {
  
  horzScrollbarDetect();
  console.log('window resized');
  
});