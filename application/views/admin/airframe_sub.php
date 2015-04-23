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
                    <table class="datatable table table-striped table-bordered table-condensed table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Designation</th>
                                <th>Manufacturer</th>
                                <th>Airframes</th>
                                <th>Hours Needed</th>
                                <th>Category</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($airframe_subs as $row): ?>
                                <tr>
                                    <td><?php echo $row->id ?></td>
                                    <td><?php echo $row->designation ?></td>
                                    <td><?php echo $row->manufacturer ?></td>
                                    <td><?php echo $row->equips ?></td>
                                    <td><?php echo $row->hours_needed ?></td>   
				    <td><?php echo $cat[$row->category] ?></td>
                                    <td align="center">
                                        <?php echo anchor('admin/airframes/create_sub/' . $row->id,'<i class="fa fa-pencil"></i> Edit', button('btn btn-xs btn-info')); ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>

                        </tbody>
                    </table>
            </div>
        </section>
    </div>
</div>