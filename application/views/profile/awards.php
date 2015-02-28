<?php
$form_attributes = array(
    'class' => 'form-horizontal form-bordered',
    'role'  => 'form',
);

$label_attributes = array(
    'class' => 'col-md-3 control-label',
); 

$field_class = 'form-control';

$award = array(
    'name'	    => 'award',
    'id'	    => 'award',
    'class'         => $field_class,
);
?>


<div class="container">
	<?php if ($errors):?>
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
		<?php endif;?>
    
    <!-- Start Award Form -->
    <div class="row">
        <div class="col-md-4">
            <section class="panel">
                <header class="panel-heading">
                    <h2 class="panel-title">Grant Award</h2>
                </header>
                <div class="panel-body">

                    <?php echo form_open_multipart('private/profile/grant_award', $form_attributes); ?>

                    <?php echo form_hidden('user_id', $this->data['user_id']); ?>
                    
                    <div class="form-group">
                        <?php echo form_label('Award', $award['id'], $label_attributes); ?>
                        <div class="col-md-6">
                            <?php echo form_dropdown('award_id', $awards, NULL, "class='{$field_class}'"); ?>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <div class="col-md-12">
                            <?php echo form_submit('save', 'Grant Award', 'class = "btn btn-primary btn-block"'); ?>
                        </div>
                    </div>

                    <?php echo form_close(); ?>
                </div>
            </section>
        </div>
    </div>
 <!-- End Award Form -->   

                
                <!-- Awards row -->
                <?php foreach ($types as $type => $awards) : ?>
		<div class="row">
			<div class="col-xs-12">
				<div class="panel panel-info">
					<div class="panel-heading"><?php echo $type ?> Awards</div>
					<div class="panel-body">
                                            <table class="table mb-none">
                                                <thead>
                                                    <tr>
                                                        <th>Award</th>
                                                        <th>Name</th>
                                                        <th>Description</th>
                                                        <th>Received</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php foreach ($awards as $award): ?>
                                                    <?php if(!is_object($award)) continue; ?>
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
                                                    </tr>
							<?php endforeach; ?>
                                                </tbody>
                                            </table>
					</div>
				</div>
			</div>
		</div><!-- End Awards row -->  
                <?php endforeach; ?>
                                

	<?php endif; ?>
</div>