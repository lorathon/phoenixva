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
		<div class="row">
			<!-- First column -->
			<div class="col-sm-6 col-md-4">
				<div class="panel panel-info">
					<div class="panel-body">
						<p class="text-center">
							<img src="<?php echo $avatar; ?>" class="avatar" />
						</p>
						<h4 class="text-center text-uppercase">
							<?php if ($is_premium): ?>
								<i class="fa fa-star" title="Premium Member"></i>
							<?php endif; ?>
							<?php echo $name; ?>
						</h4>
						<dl class="dl-horizontal">
							<dt>Joined</dt>
							<dd><?php echo $joined; ?></dd>
							<?php if ($own_profile OR $userdata['admin'] > 49): ?>
								<dt>Birthday</dt>
								<dd><?php echo $birthday; ?></dd>
							<?php endif; ?>
							<dt>Rank</dt>
							<dd><?php echo $rank; ?></dd>
							<dt>Crew Center</dt>
							<dd>
								<?php echo $hub; ?>
								<?php echo anchor('hubs/transfer','<i class="fa fa-exclamation-circle"></i> Transfer'); ?>
							</dd>
							<?php if ($raw_status < 4): ?>
								<dt>Valid Thru</dt>
								<dd>2015-09-22</dd>
							<?php endif; ?>
						</dl>
					</div>
					<div class="panel-footer">
						<?php if ( ! $own_profile && $ipbuser_id > 0): ?>
							<p class="text-center">
								<?php echo anchor(
										'http://www.phoenixva.org/forums/index.php?app=members&module=messaging&section=send&do=form&fromMemberID='.$ipbuser_id,
										'<i class="fa fa-envelope"></i> Send Message',
										button('default')); ?>
							</p>
						<?php endif; ?>				 
						<?php if ($own_profile OR $userdata['admin'] > 49): ?>
							<p class="text-center">
								<?php echo anchor('auth/change_password/'.$user_id,'Change Password', button('default')); ?>
								<?php if ($own_profile && ! $is_premium): ?>
									<?php echo anchor('private/profile/premium','<i class="fa fa-star"></i> Go Premium', button('success')); ?>
								<?php endif; ?>
							</p>
							<p class="text-center">
								<?php
									if ($raw_status != 7)
									{
										if ($raw_status >= 4)
										{
											echo anchor('auth/return/'.$user_id,'Re-activate', button('success'));
										}
										else
										{
											echo anchor('auth/loa/'.$user_id,'LOA', button('warning'));
											echo "\n";
											echo anchor('auth/retire/'.$user_id,'Retire', button('danger'));
										}	
									}
								?>
							</p>
						<?php endif;?>
						<?php if (! $own_profile && $userdata['admin'] > 49): ?>
							<hr />
							<p class="text-center">
							<?php 
								if ($raw_status == 7)
								{
									echo anchor('admin/users/unban/'.$user_id, 'Unban', button('success'));
								} 
								else 
								{
									echo anchor('admin/users/warn/'.$user_id, '<i class="fa fa-exclamation-triangle"></i> Warn', button('warning'));
									if ($userdata['admin'] > 98)
									{
										echo "\n";
										echo anchor('admin/users/ban/'.$user_id, '<i class="fa fa-ban"></i> Ban', button('danger'));
									}
								}
							?>
							</p>
						<?php endif; ?>
					</div>
				</div>
				<div class="panel panel-info">
					<div class="panel-heading">Upcoming Bids</div>
					<div class="panel-body">
						<?php foreach ($bids as $bid): ?>
							
						<?php endforeach; ?>
					</div>
					<div class="panel-footer">
						<p class="text-center">
						<?php
							if ($own_profile)
							{
								echo anchor('private/brief', '<i class="fa fa-plane"></i> Flight Brief', button('success'));
								echo "\n";
								echo anchor('private/bid/pilot/'.$user_id, 'Bids', button('default'));
								echo "\n";
								echo anchor('private/schedules', 'Schedules', button('default'));
							}
						?>
						</p>
					</div>
				</div>
			</div>
			
			<!-- Second column -->
			<div class="col-sm-6 col-md-4">
				<div class="panel panel-info">
					<div class="panel-heading">Career Details</div>
					<div class="panel-body">
						<dl class="dl-horizontal">
							<dt>Status</dt>
							<dd><?php echo $status; ?>
							<dt>Location</dt>
							<dd><?php echo $location; ?></dd>
							<dt>PVA Flights</dt>
							<dd><?php echo $total_flights; ?></dd>
						</dl>
						<hr />
						<dl class="dl-horizontal">	
							<dt>Total Hours</dt>
							<dd class="text-right"><?php echo $total_hours; ?></dd>
							<dt>PVA Hours</dt>
							<dd class="text-right"><?php echo $flight_hours; ?></dd>
							<dt>Transfer Hours</dt>
							<dd class="text-right"><?php echo $transfer_hours; ?></dd>
							<dt>Bonus Hours</dt>
							<dd class="text-right"><?php echo $bonus_hours; ?></dd>
							<dt>Typerating Hours</dt>
							<dd class="text-right"><?php echo $type_hours; ?></dd>
						</dl>
						<hr />
						<dl class="dl-horizontal">
							<dt>Earnings</dt>
							<dd class="text-right">$ <?php echo $total_pay; ?></dd>
						</dl>
						<hr />
						<dl class="dl-horizontal">
							<dt>Airlines Flown</dt>
							<dd class="text-right">
								<?php echo $airlines_flown; ?>
								<?php echo anchor('stats/pilot_airline/'.$user_id,'Details'); ?>
							</dd>
							<dt>Airports Visited</dt>
							<dd class="text-right">
								<?php echo $airports_flown; ?>
								<?php echo anchor('stats/pilot_airports/'.$user_id,'Details'); ?>
							</dd>
							<dt>Aircraft Flown</dt>
							<dd class="text-right">
								<?php echo $aircraft_flown; ?>
								<?php echo anchor('stats/pilot_aircraft/'.$user_id,'Details'); ?>
							</dd>
						</dl>
					</div>
				</div>
				<div class="panel panel-info">
					<div class="panel-heading">Logbook</div>
					<div class="panel-body">
						<?php foreach ($logs as $log): ?>
						
						<?php endforeach; ?>
					</div>
				</div>
			</div>
			
			<!-- Third column -->
			<div class="col-sm-6 col-md-4">
				<div class="panel panel-info">
					<div class="panel-heading">Next Rank</div>
					<div class="panel-body">
						<?php if ($next_rank !== FALSE): ?>
							<dl class="dl-horizontal">
								<dt><?php echo $next_rank; ?></dt>
								<dd><?php echo $next_rank_hours; ?> hours</dd>
							</dl>
							<div class="progress">
								<div class="progress-bar progress-bar-success" 
								     style="width: <?php echo $next_rank_percent; ?>%">
								</div>
							</div>
							<div class="progress-label">
								<span class="min">0</span>
								<span class="max"><?php echo $next_rank_hours; ?></span>
							</div>
							<p class="text-center"><?php echo $next_rank_to_go; ?> hours to go</p>
						<?php endif; ?>
					</div>
				</div>
				<div class="panel panel-info">
					<div class="panel-heading">Your Stats</div>
					<div class="panel-body">
						<p class="text-center">Flights</p>
						<div class="progress">
							<div class="progress-bar progress-bar-warning"
							     style="width: <?php echo $early_percent; ?>%">
							     Early (<?php echo $early_flights; ?>)
							</div>
							<div class="progress-bar progress-bar-success"
							     style="width: <?php echo $ontime_percent; ?>%">
							     On Time (<?php echo $ontime_flights; ?>)
							</div>
							<div class="progress-bar progress-bar-danger"
							     style="width: <?php echo $delayed_percent; ?>%">
								Delayed (<?php echo $delayed_flights; ?>)
							</div>
						</div>
						<div class="progress-label">
							<span class="min">0</span>
							<span class="max"><?php echo $total_flights; ?></span>
						</div>
						<dl class="dl-horizontal">
							<dt>Total Flights</dt>
							<dd class="text-right"><?php echo $total_flights; ?></dd>
							<dt>Early Flights</dt>
							<dd class="text-right"><?php echo $early_flights; ?></dd>
							<dt>Ontime Flights</dt>
							<dd class="text-right"><?php echo $ontime_flights; ?></dd>
							<dt>Delayed Flights</dt>
							<dd class="text-right"><?php echo $delayed_flights; ?></dd>
						</dl>
						<hr />
						<p class="text-center">Landings</p>
						<div class="progress">
							<div class="progress-bar progress-bar-success"
							     style="width: <?php echo $landing_success; ?>%">
							</div>
							<div class="progress-bar progress-bar-warning"
							     style="width: <?php echo $landing_warning; ?>%">
							</div>
							<div class="progress-bar progress-bar-danger"
							     style="width: <?php echo $landing_danger; ?>%">
							</div>
						</div>
						<div class="progress-label">
							<span class="min">0</span>
							<span class="max">1000</span>
						</div>
						<dl class="dl-horizontal">
							<dt>Average</dt>
							<dd class="text-right"><?php echo $landing_avg; ?> fpm</dd>
							<dt>Softest</dt>
							<dd class="text-right"><?php echo $landing_softest; ?> fpm</dd>
							<dt>Hardest</dt>
							<dd class="text-right"><?php echo $landing_hardest; ?> fpm</dd>
						</dl>
					</div>
				</div>
			</div>
		</div>
	<?php endif; ?>
</div>
