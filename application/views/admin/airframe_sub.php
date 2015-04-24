<?php $this->load->helper('html'); ?>

<header class="page-header">
        <h2>Aircraft Substitution</h2>
</header>

<!-- start: page -->
<div class="row">
    <div class="col-md-12">
        <section class="panel">
            <header class="panel-heading">
                <div class="panel-actions">
                    <a href="<?php echo site_url('admin/fleet/create_sub/') ?>" class="fa fa-plus-square"></a>
                </div>

                <h2 class="panel-title">View All Aircraft Substitutions</h2>
            </header>
            <div class="panel-body">

                <?php echo $this->table->generate(); ?>
                
            </div>
        </section>
    </div>
</div>