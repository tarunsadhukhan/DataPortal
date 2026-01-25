$("#stlupload").click(function(event) {
    event.preventDefault();

    var formData = new FormData();
    formData.append('periodfromdate', $('#stlfromdt').val());
    formData.append('periodtodate', $('#stltodt').val());
    formData.append('fileupload', $('#fileupload')[0].files[0]);

    $.ajax({
        url: "<?php echo base_url('Data_entry_2/stlupload'); ?>",
        type: "POST",
        data: formData,
        processData: false,  // Prevent jQuery from automatically transforming the data into a query string
        contentType: false,  // Ensure the request is sent as multipart/form-data
        dataType: "json",
        success: function(response) {
            if (response.success) {
                alert('Record Updated Successfully');
                $('#record_id').val(0);
            } else {
                alert('No Data');
            }
        }
    });
});



    