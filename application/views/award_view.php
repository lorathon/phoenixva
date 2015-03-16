<?php
$this->load->helper('html');
?>

<div class="container">
    <div class="row">
	<div class="col-md-8">
	    <div class="featured-box featured-box-green">
		<div class="box-content">
		    <h2>Users</h2>
		    <table class="table table-hover table-condensed">
			<thead>
			    <tr>
				<td>Pilot</td>
				<td>Flights</td>
				<td>Hours</td>
				<td>Granted</td>
				<?php if ($userdata['is_manager']) : ?>
				<td>Options</td>
				<?php endif; ?>
			    </tr>
			</thead>
			<tbody>
			    <?php if ($users) : ?>
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
				<td>
				    <?php echo date("m/d/Y", strtotime($user->granted)); ?>
				</td>
				<?php if ($userdata['is_manager']) : ?>
				<td>
				    <?php echo anchor('private/profile/revoke_award/' . $award->id . '/' . $user->id, '<i class="fa fa-trash"></i> Revoke', button_delete('danger')); ?>
				</td>
				<?php endif; ?>
			    </tr>
			    <?php endforeach; ?>
			    <?php else : ?>
			    <tr>
				<td colspan="4">
				    There are no users that have received this award.
				</td>
			    </tr>
			    
			    <?php endif; ?>
			</tbody>
		    </table>	
		</div>
	    </div>
	</div>
	<div class="col-md-4">
	<div class="featured-box featured-box-blue">
	    <div class="box-content">
		<h2>Award Details</h2>
		<table class="table table-hover table-condensed">
		    <tr>
			<td>Name: </td>
			<td><?php echo $award->name ?></td>
		    </tr>
		    <tr>
			<td>Description: </td>
			<td><?php echo $award->description; ?></td>
		    </tr>
		    <tr>
			<td>Type: </td>
			<td><?php echo $award_type->name; ?></td>
		    </tr>
		    <tr>
			<td colspan="2">
			    <?php echo img($award_type->img_folder . $award->award_image); ?>
			</td>
		    </tr>
		</table>
	    </div>
	</div>
	</div>
    </div>    
</div>