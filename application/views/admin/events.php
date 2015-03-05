<header class="page-header">
        <h2>Events</h2>
</header>

<!-- start: page -->
<div class="row">
    <div class="col-md-12">
        <section class="panel">
            <header class="panel-heading">
                <div class="panel-actions">
                    <a href="<?php echo site_url('admin/events/create_event/') ?>" class="fa fa-plus-square"></a>
                </div>

                <h2 class="panel-title">View All Events</h2>
            </header>
            <div class="panel-body">
                <div class="table-responsive">
                    <table class="table table-hover mb-none">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Description</th>
                                <th>Type</th>
                                <th>Start</th>
                                <th>End</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
			    			    
                            <?php foreach ($events as $event): ?>
                                <tr>
                                    <td><?php echo $event->id ?></td>
                                    <td><?php echo $event->name ?></td>
                                    <td><?php echo $event->description ?></td>
                                    <td><?php echo $event->type ?></td>
				    <td><?php echo date("Y-m-d", strtotime($event->time_start)) ?></td>
				    <td><?php echo date("Y-m-d", strtotime($event->time_end)) ?></td>        
                                    <td align="center">
                                        <?php echo anchor('admin/events/create_event/' . $event->id,'<i class="fa fa-pencil"></i> Edit', button('info')); ?>
                                        <?php echo anchor('admin/events/delete_event/' . $event->id,'<i class="fa fa-trash"></i> Delete', button_delete('danger')); ?>
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