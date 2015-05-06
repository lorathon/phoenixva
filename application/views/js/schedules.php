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

        // POST to server using $.post or $.ajax
        $.ajax({
            data: data,
            type: 'POST',
            url: '<?php echo base_url();?>test/results'
        });
    }
});

</script>
