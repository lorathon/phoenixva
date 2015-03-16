<?php
$this->load->helper('html');
$show_admin = (isset($userdata['name']) && $userdata['is_manager']);

$type_id = 0;
$type_name = NULL;
?>
<div class="container">
    <div class="row">
	<ul class="nav nav-tabs">
	    
	    <?php foreach($award_types as $type) : ?> 		
		<li role="presentation" 
		<?php if (uri_string() == 'awards/' . $type->id): ?>
		    class="active"
		    <?php
		    $type_id = $type->id;
		    $type_name = $type->name;
		    ?>
		    <?php endif; ?> >
			<?php echo anchor('/awards/' . $type->id, $type->name); ?> 
		</li>	    
	    <?php endforeach; ?>
		
	    <?php if ($show_admin): ?>
		<li role="presentation">
		    <?php echo anchor("/private/awards/create-type", '<i class="fa fa-plus-square" title="Add Type"></i>'); ?>
		</li>
	    <?php endif; ?>
		
	</ul>            
    </div>
    <div class="row">
	<div class="<?php if ($show_admin) { echo "col-md-8"; } else { echo "col-md-12"; }  ?> >">
	    <div class="table-responsive">
                    <table class="table table-hover mb-none">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Description</th>
                                <th>Users</th>
                                <th>Image</th>
				<?php if($show_admin) : ?>
                                <th>Actions</th>
				<?php endif; ?>
                            </tr>
                        </thead>
                        <tbody>
			    <?php if($awards) : ?>
                            <?php foreach ($awards as $award): ?>
                                <tr>
				    <td><?php echo anchor('awards/view/' . $award->id, $award->name); ?></td>
                                    <td><?php echo $award->description ?></td>
                                    <td><?php echo $award->users ?></td>

                                    <?php
                                    $image_properties = array(
                                        'src' => $award->img_folder . $award->award_image,
                                        'width' => '55',
                                    );
                                    ?>

                                    <td><?php echo img($image_properties) ?></td>   
				    <?php if($show_admin) : ?>
                                    <td align="center">					
					<?php echo anchor('private/awards/create-award/' . $award->id,'<i class="fa fa-pencil"></i> Edit', button('info')); ?>					
                                    </td>
				    <?php endif; ?>
                                </tr>
                            <?php endforeach; ?>
			    <?php else : ?>
				<tr>
				    <td colspan="5">There are no awards in this award type.</td>
				</tr>
			    <?php endif; ?>
                        </tbody>
                    </table>
                </div>
	</div>
	<div class="col-md-4">

                <!-- If user is admin show options -->
                <?php if ($show_admin): ?>
                    <div class="featured-box featured-box-red">
                        <div class="box-content">
                            <h2>Award Admin</h2>
                            <ul class="nav nav-pills">
                                <li role="presentation">
                                    <?php echo anchor("/private/awards/create-award", 'Create Award'); ?>
                                </li>
				<li role="presentation">
                                    <?php echo anchor("/private/awards/create-type/" . $type_id, 'Edit ' . $type_name . ' Type'); ?>
                                </li>
                            </ul>
                        </div>
                    </div>
                <?php endif; ?>
                <!-- end: admin options -->
	</div>
    </div>
</div>