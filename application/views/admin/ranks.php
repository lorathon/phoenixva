<header class="page-header">
        <h2>Ranks</h2>
</header>

<!-- start: page -->
<div class="row">
    <div class="col-md-12">
        <section class="panel">
            <header class="panel-heading">
                <div class="panel-actions">
                    <a href="<?php echo site_url('admin/ranks/create_rank/') ?>" class="fa fa-plus-square"></a>
                    <!--
                    <a href="#" class="fa fa-caret-down"></a>
                    <a href="#" class="fa fa-times"></a>
                    -->
                </div>

                <h2 class="panel-title">View All Ranks</h2>
            </header>
            <div class="panel-body">
                <div class="table-responsive">
                    <table class="table table-hover mb-none">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Rank</th>
                                <th>Pay Rate</th>
                                <th>Min Hours</th>
                                <th>Image</th>
                                <th>Options</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($ranks as $rank): ?>
                                <tr>
                                    <td><?php echo $rank->id ?></td>
                                    <td><?php echo $rank->rank ?></td>
                                    <td><?php echo $rank->pay_rate ?></td>
                                    <td><?php echo $rank->min_hours ?></td>

                                    <?php
                                    $image_properties = array(
                                        'src' => $paths['Rank'] . $rank->rank_image,
                                        //'class' => 'post_images',
                                        'width' => '100',
                                        //'height' => '200',
                                    );
                                    ?>

                                    <td><?php echo img($image_properties) ?></td>                                    
                                    <td align="center">
                                        <?php echo anchor('admin/ranks/create_rank/' . $rank->id,'<i class="fa fa-pencil"></i> Edit', button('info')); ?>
                                        <?php echo anchor('admin/ranks/delete_rank/' . $rank->id,'<i class="fa fa-trash"></i> Delete', button('danger')); ?>
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