<div class="container">
	<?php if ($errors):?>
		<div class="alert alert-danger">
			<?php foreach ($errors as $error): ?>
				<p><?php echo $error; ?></p>
			<?php endforeach; ?>
		</div>
	<?php else: ?>
		<div class="row">
			<div class="col-sm-6 col-md-4">
				<div class="panel panel-info">
					<div class="panel-body">
						<p class="text-center">
							<img src="<?php echo $avatar; ?>" class="avatar" />
						</p>
						<h4 class="text-center text-uppercase"><?php echo $name; ?></h4>
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
							<dd><?php echo $hub; ?></dd>
						</dl>
					</div>
					<div class="panel-footer">					 
						<?php if ($own_profile OR $userdata['admin'] > 49): ?>
							<p class="text-center">
								<?php echo anchor('auth/change_password/'.$user_id,'Change Password', button('default')); ?>
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
									echo anchor('admin/users/warn/'.$user_id, 'Warn', button('warning'));
									echo "\n";
									echo anchor('admin/users/ban/'.$user_id, 'Ban', button('danger'));
								}
							?>
							</p>
						<?php endif; ?>
					</div>
				</div>
			</div>
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
							<dd><?php echo $total_hours; ?></dd>
							<dt>PVA Hours</dt>
							<dd><?php echo $flight_hours; ?></dd>
							<dt>Transfer Hours</dt>
							<dd><?php echo $transfer_hours; ?></dd>
							<dt>Bonus Hours</dt>
							<dd><?php echo $bonus_hours; ?></dd>
							<dt>Typerating Hours</dt>
							<dd><?php echo $type_hours; ?></dd>
						</dl>
						<hr />
						<dl class="dl-horizontal">
							<dt>Earnings</dt>
							<dd>$ <?php echo $total_pay; ?></dd>
						</dl>
						<hr />
						<dl class="dl-horizontal">
							<dt>Airlines Flown</dt>
							<dd><?php echo $airlines_flown; ?></dd>
							<dt>Airports Visited</dt>
							<dd><?php echo $airports_flown; ?></dd>
							<dt>Aircraft Flown</dt>
							<dd><?php echo $aircraft_flown; ?></dd>
						</dl>
					</div>
				</div>
			</div>
			<div class="col-sm-6 col-md-4">
				<div class="panel panel-info">
					<div class="panel-heading">Next Rank</div>
					<div class="panel-body">
						<?php if ($next_rank !== FALSE): ?>
							<dl class="dl-horizontal">
								<dt><?php echo $next_rank; ?></dt>
								<dd><?php echo $next_rank_hours; ?> hours</dd>
							</dl>
							<div class="progress vertical">
								<div class="progress-bar progress-bar-success" 
								     style="width: <?php echo $next_rank_percent; ?>%">
								</div>
							</div>
							<p class="text-center"><?php echo $next_rank_to_go; ?> hours to go</p>
						<?php endif; ?>
					</div>
				</div>
			</div>
		</div>
	<?php endif; ?>
</div>
