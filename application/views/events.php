<?php
	$this->load->helper('html');
	$show_admin = (isset($userdata['name']) && $userdata['is_manager']);
?>
<div class="container">
	<?php if (isset($body)): ?>
	<div class="row">
		<ul class="nav nav-tabs">
			<li role="presentation" 
				<?php if (uri_string() == 'events/'.$id): ?>
					class="active"
				<?php endif; ?>
			>
				<?php echo anchor('/events/'.$id, 'Event Home'); ?>
			</li>
			<li role="presentaton"
				<?php if (uri_string() == 'events/'.$id.'/logbook'): ?>
					class="active"
				<?php endif; ?>
			>
				<?php echo anchor('/events/'.$id.'/logbook', 'Logbook'); ?>
			</li>
			<?php foreach ($pages as $slug => $page): ?>
				<li role="presentation"
					<?php if (uri_string() == "events/{$id}/".substr($slug,9)):?>
						class="active"
					<?php endif;?>
				>
					<?php echo anchor('/events/'.$slug, $page); ?>
				</li>
			<?php endforeach; ?>
			<?php if ($show_admin): ?>
				<li role="presentation">
					<?php echo anchor("/private/events/create-page/{$id}", '<i class="fa fa-plus-square" title="Add Page"></i>'); ?>
				</li>
			<?php endif; ?>
		</ul>
	</div>
	<div class="row">
		<div class="col-md-8">
			<?php echo $body; ?>
		</div>
		<div class="col-md-4">
		    
			<!-- If user is admin show options -->
			<?php if ($show_admin): ?>
			<div class="featured-box featured-box-red">
				<div class="box-content">
					<h2>Event Admin</h2>
					<ul class="nav nav-pills">
						<li role="presentation">
							<?php echo anchor("/private/events/create-event/{$id}", 'Edit Event Details'); ?>
						</li>
						<li role="presentation">
							<?php echo anchor("/private/events/edit-page/{$id}/{$page}", 'Edit This Page'); ?>
						</li>
					</ul>
				</div>
			</div>
			<?php endif; ?>
			<!-- end: options -->
		    
			<!-- If the event has been completed show results -->
			<?php if ($event->completed) : ?>
			<div class="featured-box featured-box-quartenary">
				<div class="box-content">
					<h2>Event Results</h2>
					<table class="table table-hover table-condensed">
					    <?php if($event->user_id_1) : ?>
					    <tr>
						<td>1st Place: </td>
						<td><?php echo user($user_1); ?></td>
					    </tr>
					    <?php endif; ?>
					    <?php if($event->user_id_2) : ?>
					    <tr>
						<td>2nd Place: </td>
						<td><?php echo user($user_3); ?></td>
					    </tr>
					    <?php endif; ?>
					    <?php if($event->user_id_3) : ?>
					    <tr>
						<td>3rd Place: </td>
						<td><?php echo user($user_3); ?></td>
					    </tr>
					    <?php endif; ?>
					</table>
				</div>
			</div>		    
			<?php endif; ?>
			<!-- end: results -->
		    
			<!-- Show event details -->
			<div class="featured-box featured-box-blue">
				<div class="box-content">
					<h2>Event Details</h2>
					<table class="table table-hover table-condensed">
					    <tr>
						<td>Event Type: </td>
						<td><?php echo $event_type; ?></td>
					    </tr>
					    <tr>
						<td>Start: </td>
						<td><?php echo $event->time_start; ?></td>
					    </tr>
					    <tr>
						<td>End: </td>
						<td><?php echo $event->time_end; ?></td>
					    </tr>
					    
					    <?php if($event->waiver_js) : ?>
					    <tr>
						<td>Jumpseat Waiver: </td>
						<td><i class="fa fa-check"/></td>
					    </tr>
					    <?php endif; ?>
					    
					    <?php if($event->waiver_cat) : ?>
					    <tr>
						<td>CAT Waiver: </td>
						<td><i class="fa fa-check"/></td>
					    </tr>
					    <?php endif; ?>
					    
					    <?php if($event->airline_id) : ?>
					    <tr>
						<td>Airline: </td>
						<td><?php echo $airline; ?></td>
					    </tr>
					    <?php endif; ?>
					    
					    <?php if($event->airport_id) : ?>
					    <tr>
						<td>Airport: </td>
						<td><?php echo $airport; ?></td>
					    </tr>
					    <?php endif; ?>					    
					    
					    <tr>
						<td>A/C Category: </td>
						<td><?php echo $aircraft[$event->aircraft_cat_id]; ?></td>
					    </tr>					    
					    
					    <?php if($event->landing_rate != 0) : ?>
					    <tr>
						<td>Target Landing Rate: </td>
						<td><?php echo $event->landing_rate; ?></td>
					    </tr>
					    <?php endif; ?>
					    
					    <?php if($event->flight_time) : ?>
					    <tr>
						<td>Min Flight Time: </td>
						<td><?php echo $event->flight_time; ?> Hours</td>
					    </tr>
					    <?php endif; ?>
					    
					    <?php if($event->bonus_1) : ?>
					    <tr>
						<td>1st Place Bonus: </td>
						<td><?php echo $event->bonus_1; ?> Hours</td>
					    </tr>
					    <?php endif; ?>
					    
					    <?php if($event->bonus_2) : ?>
					    <tr>
						<td>2nd Place Bonus: </td>
						<td><?php echo $event->bonus_2; ?> Hours</td>
					    </tr>
					    <?php endif; ?>
					    
					    <?php if($event->bonus_3) : ?>
					    <tr>
						<td>3rd Place Bonus: </td>
						<td><?php echo $event->bonus_3; ?> Hours</td>
					    </tr>
					    <?php endif; ?>
					</table>
				</div>
			</div>
			<!-- end: details -->
			
			<!-- Show Pilot Participants -->
			<div class="featured-box featured-box-green">
				<div class="box-content">
					<h2>Active Pilots</h2>
					<?php if (!$pilots): ?>
						<p>This event has no participating pilots.</p>
					<?php else: ?>
						<table class="table table-hover table-condensed">
							<thead>
								<tr>
									<td>Pilot</td>
									<td>Flights</td>
									<td>Hours</td>
								</tr>
							</thead>
							<tbody>
								<?php foreach ($pilots as $pilot): ?>
								<tr>
									<td>
									<?php echo user($pilot); ?>
									<?php if ($pilot->is_premium()): ?>
											<i class="fa fa-star" title="Premium Member"></i>
									<?php endif; ?>
									</td>
									<td>
										<?php echo $pilot->get_user_stats()->total_flights(); ?>
									</td>
									<td>
										<?php echo format_hours($pilot->get_user_stats()->total_hours()); ?>
									</td>
								</tr>
								<?php endforeach; ?>
							</tbody>
						</table>
					<?php endif; ?>
				</div>
			</div>
			<!-- end: participants -->
			
		</div>
	</div>
    
    
	<?php else: // Default text ?>
	<div class="row">
		<div class="col-md-12">
			<p class="lead">
			</p>
			<p>Click on an event to learn more about it.</p>
			<ul>
				<?php foreach ($events as $key => $value): ?>
					<li><?php echo anchor('events/'.$key, $value); ?></li>
				<?php endforeach; ?>
			</ul>
		</div>
	</div>
	<?php endif; ?>
</div>