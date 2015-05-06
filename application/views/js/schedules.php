<script type="text/javascript">
   
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
            url: '<?php echo base_url();?>schedules/reorder_bids'
        });
    }
});
</script>
