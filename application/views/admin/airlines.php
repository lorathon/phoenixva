<header class="page-header">
        <h2>Airlines</h2>
</header>

<!-- start: page -->
<div class="row">
    <div class="col-md-12">
        <section class="panel">
            <header class="panel-heading">
                <div class="panel-actions">
                    <!--<a href="<?php echo site_url('admin/airlines/create_airline/') ?>" class="fa fa-plus-square"></a>-->
                </div>

                <h2 class="panel-title"><?php echo $title?></h2>
            </header>
            <div class="panel-body">
                <div class="table-responsive">
                    <table class="table table-hover mb-none">
                        <thead>
                            <tr>
                                <th>ID</th>
				<th>ICAO</th>
				<th>IATA</th>
                                <th>Name</th>
				<th>Category</th>
                                <!--<th>Actions</th>-->
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($rows as $row): ?>
                                <tr>
                                    <td><?php echo $row->id ?></td>
                                    <td><?php echo $row->icao ?></td>
                                    <td><?php echo $row->iata ?></td>
                                    <td><?php echo $row->name ?></td>
                                    <td><?php echo $row->category_name ?></td>                          
                                    <!--<td align="center">
                                        <?php echo anchor('admin/airlines/create_airline/' . $row->id,'<i class="fa fa-pencil"></i> Edit', button('info')); ?>
                                    </td>-->
                                </tr>
                            <?php endforeach; ?>

                        </tbody>
                    </table>
                </div>
            </div>
        </section>
    </div>
</div>