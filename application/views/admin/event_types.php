<header class="page-header">
        <h2>Event Types</h2>
</header>

<!-- start: page -->
<div class="row">
    <div class="col-md-12">
        <section class="panel">
            <header class="panel-heading">
                <div class="panel-actions">
                    <a href="<?php echo site_url('admin/events/create_event_type/') ?>" class="fa fa-plus-square"></a>
                </div>

                <h2 class="panel-title">View All Event Types</h2>
            </header>
            <div class="panel-body">
                <div class="table-responsive">
                    <table class="table table-hover mb-none">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Description</th>
                                <th>Color</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($types as $type): ?>
                                <tr>
                                    <td><?php echo $type->id ?></td>
                                    <td><?php echo $type->name ?></td>
                                    <td><?php echo $type->description ?></td>
                                    <td><div class="text-<?php echo $calendar_colors[$type->color_id]; ?>"><?php echo ucfirst($calendar_colors[$type->color_id]); ?></div></td>
                                    <td align="center">
                                        <?php echo anchor('admin/events/create_event_type/' . $type->id,'<i class="fa fa-pencil"></i> Edit', button('info')); ?>
                                        <?php echo anchor('admin/events/delete_event_type/' . $type->id,'<i class="fa fa-trash"></i> Delete', button_delete('danger')); ?>
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