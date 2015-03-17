<?php
$this->load->helper('html');
$show_admin = (isset($userdata['name']) && $userdata['is_manager']);
$admin_shown = FALSE;

$form_attributes = array(
    'class' => 'form-horizontal form-bordered',
    'role' => 'form',
);

$label_attributes = array(
    'class' => 'col-md-3 control-label',
);

$field_class = 'form-control';

$award = array(
    'name' => 'award',
    'id' => 'award',
    'class' => $field_class,
);
?>

<div class="container">
    
	<?php if ($errors): ?>
	    <div class="alert alert-danger">
		<?php foreach ($errors as $error): ?>
		    <p><?php echo $error; ?></p>
		<?php endforeach; ?>
	    </div>
	<?php else: ?>
    
	<?php if (isset($help)): ?>
	    <div class="alert alert-info">
		    <?php echo $help; ?>
	    </div>
	<?php endif; ?>        

        <!-- Start Award Modal Form -->
	<?php if ($userdata['is_manager']) : ?>
	    <div class="row">
		<div class="col-md-4 col-md-offset-5">	
		    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog">
			    <div class="modal-content">
				<div class="modal-header">
				    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				    <h4 class="modal-title" id="myModalLabel">Grant Award</h4>
				</div>
				<div class="modal-body">
				    <?php echo form_open_multipart('private/profile/grant_award', $form_attributes); ?>

				    <?php echo form_hidden('user_id', $this->data['user_id']); ?>

				    <div class="form-group">
					<?php echo form_label('Award', $award['id'], $label_attributes); ?>
					<div class="col-md-6">
					    <?php echo form_dropdown('award_id', $awards, NULL, "class='{$field_class}'"); ?>
					</div>
				    </div>
				</div>
				<div class="modal-footer">
				    <div class="form-group">
					<div class="col-md-6 col-md-offset-3">
					    <?php echo form_submit('save', 'Grant Award', 'class = "btn btn-primary btn-block"'); ?>
					</div>
				    </div>

				    <?php echo form_close(); ?>
				</div>
			    </div>
			</div>
		    </div>
		</div>
	    </div>
	<?php endif ?>
        <!-- End Award Modal Form -->

        <!-- Start Awards row -->
	<?php foreach ($types as $type => $awards) : ?>
	    <div class="row">
		<div class="<?php if ($show_admin) { echo "col-md-8"; } else { echo "col-md-12"; }  ?> >">
		    <div class="featured-box featured-box-green">
			<div class="box-content">
			    <h2><?php echo $type ?> Awards</h2>			
			    <table class="table mb-none">
				<thead>
				    <tr>
					<th>Award</th>
					<th>Name</th>
					<th>Description</th>
					<th>Received</th>
					<?php if ($userdata['is_admin']) : ?>
	    				<th>Revoke</th>
					<?php endif; ?>
				    </tr>
				</thead>
				<tbody>
				    <?php foreach ($awards as $award): ?>
					<?php if (!is_object($award)) continue; ?>
	    			    <tr>

					    <?php
					    $image_properties = array(
						'src' => $award->img_folder . $award->award_image,
						//'class' => 'post_images',
						'width' => $award->img_width,
						    //'height' => '200',
					    );
					    ?>

	    				<td><?php echo img($image_properties) ?></td>
	    				<td><?php echo $award->name; ?></td>
	    				<td><?php echo $award->description; ?></td>
	    				<td><?php echo $award->created; ?></td>
					    <?php if ($userdata['is_manager']) : ?>
						<td><?php echo anchor('private/profile/revoke_award/' . $award->id . '/' . $this->data['user_id'], '<i class="fa fa-trash"></i> Revoke', button_delete('danger')); ?></td>
					    <?php endif; ?>
	    			    </tr>
				    <?php endforeach; ?>
				</tbody>
			    </table>
			</div>
		    </div>
		</div>
		<div class="col-md-4">

                <!-- If user is admin show options -->
                <?php if ($show_admin  && !$admin_shown): ?>
                    <div class="featured-box featured-box-red">
                        <div class="box-content">
                            <h2>Award Admin</h2>
                            <ul class="nav nav-pills">
                                <li role="presentation">
				    <a href="#" data-toggle="modal"data-target="#myModal">Grant Award</a>
				</li>
                            </ul>
                        </div>
                    </div>
		<?php $admin_shown = TRUE; ?>
                <?php endif; ?>
                <!-- end: admin options -->
		</div>
	    </div>
	    <!-- End Awards row --> 

	<?php endforeach; ?>            
    <?php endif; ?>
</div>