    <table class="table table-class table-striped" id="example">
        <thead>
            <tr>
                <th class="thfirst">First name</th>
                <th>Last name</th>
                <th>Position</th>
                <th>Office</th>
                <th>Age</th>
                <th>Start date</th>
                <th>Salary</th>
                <th>Extn.</th>
                <th class="thlast">E-mail</th>
            </tr>
        </thead>
        <tbody></tbody>
    </table>

    
<script>
$(document).ready(function() {

    var mainmenuId = '<?=$mainmenuId?>';
    var submenuId = '<?=$submenuId?>';
    var companyId = '<?=$companyId?>';
    var from_date = '<?=$from_date?>';
    var to_date = '<?=$to_date?>';
    var controller = '<?=$controller?>';
	table = $('#example').DataTable({

        "processing": true,
        "serverSide": true,
        "searching": false,
    "lengthChange":false,
        "order": [],
        "ajax": {
            "url": "<?php echo base_url() ?>/"+controller+'/ajax_list/'+mainmenuId+'/'+submenuId+'/'+companyId+'/'+from_date+'/'+to_date,
            "type": "POST",            
            "data": function ( data ) {

            }
        },
        "columnDefs": [
        {
            "targets": [ 0 ],
            "orderable": false,
        },
        ],

    });

    $('#btn-filter').click(function(){
        table.ajax.reload();
    });
    $('#btn-reset').click(function(){
        $('#form-filter')[0].reset();
        table.ajax.reload();
    });

});

</script>


