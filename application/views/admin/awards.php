<header class="page-header">
        <h2>Awards</h2>
</header>

<!-- start: page -->
<div class="row">

<div class="col-md-12">
    
    <?php if($alert) : ?>
        <div class="alert alert-<?php echo $alert_type?>">            
            <p><?php echo $alert_message?></p>
        </div>
    <?php endif; ?>
    
    <section class="panel panel-featured panel-featured-primary">
            <header class="panel-heading">
                    

                    <h2 class="panel-title">View All Awards</h2>
                    <?php echo anchor('admin/awards/create_award/','NEW AWARD')?>
            </header>
            <div class="panel-body">
                <table>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Description</th>
                        <th>Type</th>
                        <th>Image</th>
                        <th>Edit</th>
                        <th>Delete</th>
                        
                    </tr>
                    <?php foreach($awards as $award): ?>
                    <tr>
                        <td><?php echo $award->id?></td>
                        <td><?php echo $award->name?></td>
                        <td><?php echo $award->descrip?></td>
                        <td><?php echo $types[$award->type]?></td>
                        
                        <?php
                            $image_properties = array(
                                'src' => $paths['Award'].$award->award_image,
                                //'class' => 'post_images',
                                'width' => '55',
                                //'height' => '200',
                            );
                        ?>
                        
                        <td><?php echo img($image_properties)?></td>
                        <td><?php echo anchor('admin/awards/create_award/'.$award->id,'EDIT')?></td>
                        <td>DELETE</td>
                    </tr>
                    <?php endforeach; ?>
                </table>               
                
            </div>
    </section>
</div>
</div>