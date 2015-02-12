<header class="page-header">
        <h2>Ranks</h2>
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
                 <h2 class="panel-title">View All Ranks</h2>
                 <?php echo anchor('admin/ranks/new_rank/','NEW RANK')?>
            </header>
            <div class="panel-body">
                <table>
                    <tr>
                        <th>ID</th>
                        <th>Rank</th>
                        <th>Pay Rate</th>
                        <th>Min Hours</th>
                        <th>Image</th>
                        <th>Edit</th>
                        <th>Delete</th>
                    </tr>
                    <?php foreach($ranks as $rank): ?>
                    <tr>
                        <td><?php echo $rank->id?></td>
                        <td><?php echo $rank->rank?></td>
                        <td><?php echo $rank->pay_rate?></td>
                        <td><?php echo $rank->min_hours?></td>
                        
                        <?php
                            $image_properties = array(
                                'src' => './images/rank/'.$rank->rank_image,
                                //'class' => 'post_images',
                                'width' => '100',
                                //'height' => '200',
                            );
                        ?>
                        
                        <td><?php echo img($image_properties)?></td>
                        <td><?php echo anchor('admin/ranks/new_rank/'.$rank->id,'EDIT')?></td>
                        <td>DELETE</td>
                    </tr>
                    <?php endforeach; ?>
                </table>               
                
            </div>
    </section>
</div>
</div>