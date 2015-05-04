<script type="text/javascript">
    $(document).ready(function() {
        $('#big_table').dataTable( {
            "processing": true,
            "serverSide": true,
            "pagingType": "full_numbers",
            "lengthMenu": [ 15, 25, 40, 50 ],
            "pageLength": 25,
            "ajax": {
                "url": "<?php echo base_url('admin/airframes/datatable_view_sub'); ?>",
                "type": "POST",
                "data": function ( data ) {
                    data.<?=$this->security->get_csrf_token_name()?> = "<?=$this->security->get_csrf_hash()?>"
                }
            },
            "columns": [
                { "data": "id" },
                { "data": "designation" },
                { "data": "manufacturer" },
                { "data": "equips" },
                { "data": "hours_needed" },
                { "data": "category" },
                {
                  "data": "id",
                  "className": "center",
                  "render": function ( data ) {
                    return '<a href="<?php echo base_url();?>admin/airframes/create_sub/' + data + '">Edit</button>';
                }
            }
            ]
        } );
} );
</script>