<div class="container">
	<?php if (isset($body)): ?>
	<div class="row">
		<ul class="nav nav-tabs">
			<li role="presentation" 
				<?php if (uri_string() == 'hubs/'.$icao): ?>
					class="active"
				<?php endif; ?>
			>
				<?php echo anchor('/hubs/'.$icao, 'Crew Center Home'); ?>
			</li>
			<?php foreach ($pages as $slug => $page): ?>
				<li role="presentation"
					<?php if (uri_string() == 'hubs/'.$slug):?>
						class="active"
					<?php endif;?>
				>
					<?php echo anchor('/hubs/'.$slug, $page); ?>
				</li>
			<?php endforeach; ?>
		</ul>
	</div>
	<div class="row">
		<div class="col-md-8">
			<?php echo $body; ?>
		</div>
		<div class="col-md-4">
			<div class="featured-box featured-box-green">
				<div class="box-content">
					<h2>Pilots</h2>
					<?php if (!$pilots): ?>
						<p>This crew center has no pilots.</p>
					<?php else: ?>
						<table class="table table-hover table-condensed">
							<thead>
								<tr>
									<td>ID</td>
									<td>Name</td>
									<td>Flights</td>
								</tr>
							</thead>
							<tbody>
								<?php foreach ($pilots as $pilot): ?>
								<tr>
									<td><?php echo $pilot->id; ?></td>
									<td>
									<?php echo anchor('/private/profile/view/'.$pilot->id, $pilot->name); ?>
									<?php if ($pilot->is_premium()): ?>
											<i class="fa fa-star" title="Premium Member"></i>
									<?php endif; ?>
									</td>
									<td>
										<?php echo $pilot->get_user_stats()->total_flights(); ?>
									</td>
								</tr>
								<?php endforeach; ?>
							</tbody>
						</table>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</div>
	<?php else: ?>
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