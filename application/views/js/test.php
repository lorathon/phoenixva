<script type="text/javascript">
$('#sortable').sortable({
    axis: 'y',
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
