$(document).ready(function () {

    $("#njmmenuclick").click(function(event){
               event.preventDefault();     
                var att_payschm =  $('#att_payschm').val();
                var getmenu =  $('#getmenu').val();
                periodfromdate = $('#advpfromdt').val();
                periodtodate = $('#advptodt').val();
                payschemeName = $('#payschemename').val();
                periodfromdate= periodfromdate.substring(8)+'-'+periodfromdate.substring(5,7)+'-'+periodfromdate.substring(0,4);
                periodtodate= periodtodate.substring(8)+'-'+periodtodate.substring(5,7)+'-'+periodtodate.substring(0,4);
                alert('new js');
                alert(getmenu);
                var hd1 = '';
                 if (getmenu == 1) {
                alert('process new js');
                     njmcntwagesprocessdata(event);

                    }
            });


function njmcntwagesprocessdata() {
  event.preventDefault(); 
  var periodfromdate= $('#njmcntfromdt').val();
  var periodtodate= $('#njmcnttodt').val();
  var att_payschm =  $('#att_payschm').val();
alert(att_payschm);
  // Calculate difference in days
  var fromDate = new Date(periodfromdate);
  var toDate = new Date(periodtodate);
  var timeDiff = toDate.getTime() - fromDate.getTime();
  var diffDays = Math.ceil(timeDiff / (1000 * 3600 * 24)) + 1; // number of full days

  // Check desired difference
  var desiredDiff = 28;  // Change this as per your requirement
  if (diffDays < desiredDiff) {
      alert("The difference between From and To dates must be exactly " + diffDays + '---' + desiredDiff + " days.");
      return; // Exit and do not proceed further
  }
//   showSpinnerCounter();

  $.ajax({
      url: "<?php echo base_url('Njmwagesprocess/njmcntwagesprocessdata'); ?>",
      type: "POST",
      data: {periodfromdate : periodfromdate, periodtodate : periodtodate, att_payschm: att_payschm},
      dataType: "json",
      success: function(response) {
          var savedata = (response.savedata);
          if (response.success) {
  //              hideSpinnerCounter();

            alert('Record Updated Successfully');
              $('#record_id').val(0);


          } else {
              alert('No Data');
          }
      }
  });
}







        });
