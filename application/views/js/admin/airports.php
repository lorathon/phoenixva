<script type="text/javascript">

    $(document).ready(function() {
        $('#airports').dataTable( {
            "processing": true,
            "serverSide": true,
            "pagingType": "full_numbers",
            "lengthMenu": [ 15, 25, 40, 50 ],
            "pageLength": 25,
            "ajax": {
                "url": "<?php echo base_url('admin/airports/datatable_airports'); ?>",
                "type": "POST",
                "data": function ( data ) {
                    data.<?=$this->security->get_csrf_token_name()?> = "<?=$this->security->get_csrf_hash()?>"
                }
            },
            "columns": [
                { "data": "fs",
                  "className": "center" },
              
                { "data": "iata",
                  "className": "center" },
              
                { "data": "icao",
                  "className": "center" },
              
                { "data": "name" },
                
                { "data": "city" },
              
                { "data": "state_code",
                  "className": "center" },
                
                { "data": "country_name",
                  "className": "center" },
              
                { "data": "utc_offset",
                  "className": "center" },
                
                { "data": "elevation",
                  "className": "center" },
                
                { "data": "classification",
                  "className": "center" },
                
                { "data": "active",
                  "className": "center" },
              
                { "data": "port_type",
                  "className": "center" },
              
                { "data": "id",
                  "className": "center",
                  "render": function ( data ) {
                    return '<a href="<?php echo base_url();?>admin/airframes/create_airport/' + data + '">Edit</button>';
                }
            }
            ]
        } );
    } );
</script>