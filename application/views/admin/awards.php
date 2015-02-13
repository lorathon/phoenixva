<header class="page-header">
        <h2>Awards</h2>
</header>

<!-- start: page -->
<div class="row">
    <div class="col-md-6">

        <?php if ($alert) : ?>
            <div class="alert alert-<?php echo $alert_type ?>">            
                <p><?php echo $alert_message ?></p>
            </div>
        <?php endif; ?>

        <section class="panel">
            <header class="panel-heading">
                <div class="panel-actions">
                    <a href="<?php echo site_url('admin/awards/create_award/') ?>" class="fa fa-plus-square"></a>
                    <a href="#" class="fa fa-caret-down"></a>
                    <a href="#" class="fa fa-times"></a>
                </div>

                <h2 class="panel-title">View All Awards</h2>
            </header>
            <div class="panel-body">
                <div class="table-responsive">
                    <table class="table mb-none">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Description</th>
                                <th>Type</th>
                                <th>Image</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($awards as $award): ?>
                                <tr>
                                    <td><?php echo $award->id ?></td>
                                    <td><?php echo $award->name ?></td>
                                    <td><?php echo $award->descrip ?></td>
                                    <td><?php echo $types[$award->type] ?></td>

                                    <?php
                                    $image_properties = array(
                                        'src' => $paths['Award'] . $award->award_image,
                                        //'class' => 'post_images',
                                        'width' => '55',
                                            //'height' => '200',
                                    );
                                    ?>

                                    <td><?php echo img($image_properties) ?></td>                                    
                                    <td align="center">
                                        <a href="<?php echo site_url('admin/awards/create_award/' . $award->id) ?>" class="fa fa-pencil"></a>
                                        <a href="#" class="fa fa-trash-o"></a>
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