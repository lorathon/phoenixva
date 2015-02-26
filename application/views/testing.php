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

                
                <!-- Awards row -->
		<div class="row">
			<div class="col-xs-12">
				<div class="panel panel-info">
					<div class="panel-heading"><?php echo $heading ?> Awards</div>
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
                                

	<?php endif; ?>
</div>