<?php
$this->load->helper('html');
$show_admin = (isset($userdata['name']) && $userdata['is_manager']);
?>

<div class="container">
    <?php if(isset($ranks)) : ?>
    
    <!-- Start: Rank List -->
    <div class="row">
	<div class="<?php if ($show_admin) { echo "col-md-8"; } else { echo "col-md-12"; }  ?> >">
	    <p class="lead">
		Phoenix Virtual Airways pilots have the opportunity to earn <?php echo count($ranks); ?>
		ranks during their career.  Each rank opens up new opportunities
		for the pilot who has achieved that rank.  From new airframes to more
		responsibility.  
	    </p>
	    <p>Click on a pilot rank to learn more about it.</p>
	    <ul>
		<?php foreach ($ranks as $rank): ?>
		    <li><?php echo anchor('ranks/' . $rank->id, $rank->rank); ?></li>
		<?php endforeach; ?>
	    </ul>
	</div> 
	
	<!-- Start: Admin Options -->
	<?php if($show_admin) : ?>
	<div class="col-md-4">
	    <div class="featured-box featured-box-red">
		<div class="box-content">
		    <h2>Rank Admin</h2>
		    <ul class="nav nav-pills">
			<li role="presentation">
			    <?php echo anchor("/private/ranks/create-rank", 'Create Rank'); ?>
			</li>
		    </ul>
		</div>
	    </div>
	</div> 
	<?php endif; ?>
	<!-- End: Admin Options -->
    </div>
    <!-- End: Rank List -->
    
    <?php else : ?>
    
    <!-- Start: Rank Detail -->
    <div class="row">
	<div class="col-md-8">
	    <div class="featured-box featured-box-green">
		<div class="box-content">
		    <h2>Pilots</h2>
		    <div class="table-responsive">
			<table class="table table-hover mb-none">  
			    <thead>
				<tr>
				    <th>Name</th>
				    <th>Flights</th>
				    <th>Hours</th>
				</tr>
			    </thead>
			    <tbody>
				<?php if($users) : ?>
				<?php foreach ($users as $user): ?>
				    <tr>
					<td>
					    <?php echo user($user); ?>
					    <?php if ($user->is_premium()): ?>
						<i class="fa fa-star" title="Premium Member"></i>
					    <?php endif; ?>
					</td>
					<td>
					    <?php echo $user->get_user_stats()->total_flights(); ?>
					</td>
					<td>
					    <?php echo format_hours($user->get_user_stats()->total_hours()); ?>
					</td>
				    </tr>
				<?php endforeach; ?>
				<?php else : ?>
				    <tr>
					<td colspan="5">There are no pilots with this rank.</td>
				    </tr>
				<?php endif; ?>
			    </tbody>
			</table>
		    </div>
		</div>
	    </div>	    
	</div>  
	
	<div class="col-md-4">
	
    <!-- Start: Admin Options -->
	<?php if($show_admin) : ?>
	    <div class="featured-box featured-box-red">	
		<div class="box-content">
		    <h2>Rank Admin</h2>
		    <ul class="nav nav-pills">
			<li role="presentation">
			    <?php echo anchor("/private/ranks/create-rank/" . $rank->id, 'Edit Rank'); ?>
			</li>
			<li role="presentation">
			    <?php echo anchor_delete("/private/ranks/delete-rank/" . $rank->id, 'Delete Rank'); ?>
			</li>
		    </ul>
		</div> 
	    </div>
	<?php endif; ?>
	<!-- End: Admin Options -->
	
	    <div class="featured-box featured-box-blue">
		<div class="box-content">
		    <h2>Rank Detail</h2>
		    <table class="table table-hover table-condensed">
			<tr>
			    <td>Abbreviation: </td>
			    <td><?php echo $rank->short; ?></td>
			</tr>
			<tr>
			    <td>Minimum Hours: </td>
			    <td><?php echo $rank->min_hours; ?></td>
			</tr>
			<tr>
			    <td>Pay Rate: </td>
			    <td><?php echo $rank->pay_rate; ?></td>
			</tr>
			<tr>
			    <td colspan="2">
				<?php
                                    $image_properties = array(
                                        'src' => $img_folders['Rank'] . $rank->rank_image,
                                        'width' => '100',
                                    );
                                    ?>
				<?php echo img($image_properties) ?>
			    </td>
			</tr>
		    </table>
		</div>
	    </div>
	</div> 
    </div>   
    
    <!-- End: Rank Detail -->
    <?php endif; ?>
</div> 