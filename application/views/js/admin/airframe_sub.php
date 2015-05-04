<script type="text/javascript">
    $(document).ready(function() {
        $('#big_table').dataTable( {
            "processing": true,
            "serverSide": true,
            "pagingType": "full_numbers",
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
                    return '<a href="<?php echo base_url();?>admin/airframes/create_sub/' + data + '" class="btn btn-xs btn-primary" role="button"><i class="fa fa-pencil fa-1"></i> Edit</button>';
                }
            }
            ]
        } );
} );
</script>