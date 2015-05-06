<script type="text/javascript">
    $(document).ready(function() {
        $('#big_table').dataTable( {
            "processing": true,
            "serverSide": true,
            "pagingType": "full_numbers",
            "lengthMenu": [ 15, 25, 40, 50 ],
            "pageLength": 25,
            "ajax": {
                "url": "<?php echo base_url('admin/airframes/datatable_airframes'); ?>",
                "type": "POST",
                "data": function ( data ) {
                    data.<?=$this->security->get_csrf_token_name()?> = "<?=$this->security->get_csrf_hash()?>"
                }
            },
            "columns": [
                { "data": "iata",
                  "className": "center" },
              
                { "data": "icao",
                  "className": "center" },
              
                { "data": "name" },
                
                { "data": getPassengers,
                  "className": "center",
                  "sortable": false,
                  "searchable": false },
              
                { "data": "payload",
                  "className": "center",
                  "render": function ( data ) {
                      if ( data === "0" ) {
                          return '<font color="#ff0000">0</font>';
                      } else {
                          return data;
                      }
                  }
                },
                
                { "data": "oew",
                  "className": "center",
                  "render": function ( data ) {
                      if ( data === "0" ) {
                          return '<font color="#ff0000">0</font>';
                      } else {
                          return data;
                      }
                  }
                },
                { "data": "mzfw",
                  "className": "center",
                  "render": function ( data ) {
                      if ( data === "0" ) {
                          return '<font color="#ff0000">0</font>';
                      } else {
                          return data;
                      }
                  }
                },
                
                { "data": "mtow",
                  "className": "center",
                  "render": function ( data ) {
                      if ( data === "0" ) {
                          return '<font color="#ff0000">0</font>';
                      } else {
                          return data;
                      }
                  }
                },
                
                { "data": "mlw",
                  "className": "center",
                  "render": function ( data ) {
                      if ( data === "0" ) {
                          return '<font color="#ff0000">0</font>';
                      } else {
                          return data;
                      }
                  }
                },
                
                { "data": "aircraft_sub_id",
                  "className": "center" },
                { "data": "category",
                  "className": "center" },
                { "data": "id",
                  "className": "center",
                  "render": function ( data ) {
                    return '<a href="<?php echo base_url();?>admin/airframes/create_airframe/' + data + '">Edit</button>';
                }
            }
            ]
        } );
} );

// create string for seating type
function getPassengers(data, type, dataToSet) {
    return data.pax_first + " | " + data.pax_business + " | " + data.pax_economy;
};
</script>