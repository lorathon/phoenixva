<script type="text/javascript">

$(function(){
  $("#operator").autocomplete({
    source: "<?php echo base_url();?>airlines/autocomplete",
    select: function( event, ui ) {
	    var span = document.getElementById('operator_code');
            $( "#operator" ).val( ui.item.name);
            $( "#operator_id" ).val( ui.item.id);
	    document.getElementById("operator_code").textContent = ui.item.fs;
    }
  });
});

$(function(){
  $("#carrier").autocomplete({
    source: "<?php echo base_url();?>airlines/autocomplete",
    select: function( event, ui ) {
            $( "#carrier" ).val( ui.item.name);
            $( "#carrier_id" ).val( ui.item.id);
    }
  });
});

$(function(){
  $("#dep_airport").autocomplete({
    source: "<?php echo base_url();?>airports/autocomplete",
    select: function( event, ui ) {
            $( "#dep_airport" ).val( ui.item.name);
            $( "#dep_airport_id" ).val( ui.item.id);
    }
  });
});

$(function(){
  $("#arr_airport").autocomplete({
    source: "<?php echo base_url();?>airports/autocomplete",
    select: function( event, ui ) {
            $( "#arr_airport" ).val( ui.item.name);
            $( "#arr_airport_id" ).val( ui.item.id);
    }
  });
});

$(function(){
  $("#aircraft_sub").autocomplete({
    source: "<?php echo base_url();?>fleet/autocomplete_sub",
    select: function( event, ui ) {
            $( "#aircraft_sub" ).val( ui.item.name);
            $( "#aircraft_sub_id" ).val( ui.item.id);
    }
  });
});
   
var fixHelper = function(e, ui) {
    ui.children().each(function() {
        $(this).width($(this).width());
    });
    return ui;
};
 
$('#bidTable tbody').sortable({
    axis: 'y',
    helper: fixHelper, 
    update: function (event, ui) {
	
        var data = $(this).sortable('serialize');
	
        $.ajax({   
	    data: data,
            type: 'GET',
            url: '<?php echo base_url();?>private/schedules/reorder_bids'
        });
    }
});
</script>
