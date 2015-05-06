<?php $this->load->helper('html'); ?>

<header class="page-header">
        <h2>Aircraft Substitution Chart</h2>
</header>

<!-- start: page -->

<div class="panel-actions">
    <a href="<?php echo site_url('admin/fleet/create_sub/') ?>" class="fa fa-plus-square"></a>
</div>

<?php echo $this->table->generate(); ?>