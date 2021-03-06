<?php
	$this->load->helper('html');
	if (isset($airport))
	{
		$show_admin = (isset($userdata['name']) && $userdata['is_manager']);
		$own_hub = (isset($userdata['name']) && ($airport->id == $userdata['hub']));
	}
?>
<div class="container">
	<?php if (isset($body)): ?>
	<div class="row" role="navigation">
		<ul class="nav nav-tabs">
			<li role="presentation" 
				<?php if (uri_string() == 'hubs/'.$icao): ?>
					class="active"
				<?php endif; ?>
			>
				<?php echo anchor('/hubs/'.$icao, 'Crew Center Home'); ?>
			</li>
			<li role="presentaton"
				<?php if (uri_string() == 'hubs/'.$icao.'/logbook'): ?>
					class="active"
				<?php endif; ?>
			>
				<?php echo anchor('/hubs/'.$icao.'/logbook', 'Logbook'); ?>
			</li>
			<?php foreach ($pages as $slug => $page_name): ?>
				<li role="presentation"
					<?php if (uri_string() == "hubs/{$icao}/".substr($slug,9)):?>
						class="active"
					<?php endif;?>
				>
					<?php echo anchor('/hubs/'.$slug, $page_name); ?>
				</li>
			<?php endforeach; ?>
			<?php if ($show_admin): ?>
				<li role="presentation">
					<?php echo anchor("/admin/articles/create_hub/{$icao}", '<i class="fa fa-plus-square" title="Add Page"></i>'); ?>
				</li>
			<?php endif; ?>
		</ul>
	</div>
	<div class="row">
		<div class="col-md-8">
			<div id="page-body">
				<?php echo $body; ?>
			</div>
		</div>
		<div class="col-md-4">
			<?php if ($show_admin): ?>
			<div class="featured-box featured-box-red">
				<?php if (! $own_hub): ?>
					<div class="alert alert-danger">
						<p>WARNING: THIS IS NOT YOUR HUB</p>
				<?php endif;?>
				<div class="box-content">
					<h2>Hub Admin</h2>
					<ul class="nav nav-pills">
						<li role="presentation">
							<?php echo anchor("/admin/articles/edit_hub/{$icao}/{$page}", 'Edit This Page', button('warning')); ?>
						</li>
						<?php if (isset($article_id)): ?>
							<li role="presentation">
								<?php echo anchor("/admin/articles/delete/{$article_id}", 'Delete This Page', button('danger')); ?>
							</li>
						<?php endif;?>
					</ul>
				</div>
				<div class="box-content">
					<h2>Pending Transfers</h2>
					<?php if (!$transfers): ?>
						<p>No incoming transfer requests.</p>
					<?php else: ?>
						<table class="table table-hover table-condensed">
							<thead>
								<tr>
									<td>Pilot</td>
									<td>Flights</td>
									<td>Hours</td>
									<td>Options</td>
								</tr>
							</thead>
							<tbody>
								<?php foreach ($transfers as $transfer): ?>
								<tr>
									<td>
									<?php echo user($transfer); ?>
									<?php if ($transfer->is_premium()): ?>
											<i class="fa fa-star" title="Premium Member"></i>
									<?php endif; ?>
									</td>
									<td>
										<?php echo $transfer->get_user_stats()->total_flights(); ?>
									</td>
									<td>
										<?php echo format_hours($transfer->get_user_stats()->total_hours()); ?>
									</td>
									<td>
										<?php echo anchor('hubs/transfer_approve/'.$transfer->id.'/'.$airport->id, '<i class="fa fa-thumbs-up" title="Accept"></i>', button('success')); ?>
										<?php echo anchor('hubs/transfer_reject/'.$transfer->id.'/'.$airport->id, '<i class="fa fa-thumbs-down" title="Reject"></i>', button('danger')); ?>
									</td>
								</tr>
								<?php endforeach; ?>
							</tbody>
						</table>
					<?php endif; ?>
					<?php if (! $own_hub): ?></div><?php endif; ?>
				</div>
			</div>
			<?php endif; ?>
			<div class="featured-box featured-box-green">
				<div class="box-content">
					<h2>Active Pilots</h2>
					<?php if (!$pilots): ?>
						<p>This crew center has no active pilots.</p>
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
					<?php if (isset($userdata['name'])): ?>
						<?php if (!$own_hub): ?>
							<?php echo anchor('hubs/transfer/'.$userdata['user_id'].'/'.$airport->id, 'Request Transfer To This Hub', button('warning')); ?>
						<?php endif; ?>
					<?php else: ?>
						<?php echo anchor('auth/register', 'Join PVA Now!', button('success')); ?>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</div>
	<?php else: // Default text ?>
	<div class="row">
		<div class="col-md-12">
			<p class="lead">
				Phoenix Virtual Airways operates from <?php echo count($hubs); ?>
				crew centers located throughout the world. Each pilot selects a
				crew center when joining and has opportunities to transfer to
				other crew centers throughout their career. With Phoenix Virtual
				Airways, you never have to visit your crew center. Crew centers
				provide a means of grouping our pilots into teams for various
				contests and events. 
			</p>
			<p>Click on a crew center to learn more about it.</p>
			<ul>
				<?php foreach ($hubs as $key => $value): ?>
					<li><?php echo anchor('hubs/'.$key, $value); ?></li>
				<?php endforeach; ?>
			</ul>
		</div>
	</div>
	<?php endif; ?>
</div>