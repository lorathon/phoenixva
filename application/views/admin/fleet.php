<header class="page-header">
        <h2>Fleet</h2>
</header>

<!-- start: page -->
<div class="row">
    <div class="col-md-12">
        <section class="panel">
            <header class="panel-heading">
                <div class="panel-actions">
                    <a href="<?php echo site_url('admin/fleet/create_aircraft/') ?>" class="fa fa-plus-square"></a>
                </div>

                <h2 class="panel-title">View All Aircraft</h2>
            </header>
            <div class="panel-body">
                <div class="table-responsive">
                    <table class="table table-hover mb-none">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Category</th>
                                <th>Carrier Count</th>
                                <th>Operator Count</th>
                                <th>Total Flight Count</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($fleet as $aircraft): ?>
                                <tr>
                                    <td><?php echo $aircraft->id ?></td>
                                    <td><?php echo $aircraft->equip ?></td>
                                    <td><?php echo $aircraft->category ?></td>
                                    <td><?php echo $aircraft->carrier_count ?></td>
                                    <td><?php echo $aircraft->operator_count ?></td>				    
				    <td><?php echo $aircraft->flight_count ?></td>                           
                                    <td align="center">
                                        <?php echo anchor('admin/fleet/create_aircraft/' . $aircraft->id,'<i class="fa fa-pencil"></i> Edit', button('info')); ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>

                        </tbody>
                    </table>
                </div>
            </div>
        </section>
    </div>
</div>