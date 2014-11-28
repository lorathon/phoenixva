				<div class="container">

					<div class="row">
						<div class="col-md-12">
							<p class="lead">
								Thank you for your application to join Phoenix Virtual Airways!
							</p>
							<p>
								<strong>Check your <?php echo $email; ?> email address for an 
								activation link.</strong> 
								Once you click that link we will begin a background investigation and 
								you may be asked to	provide more information. You will be on probation 
								during this	period, but you will be able to start flying with us.
							</p>
							<p>
								If your email address is correct and you do not receive the email
								within a few minutes, you can contact support at
								<?php echo safe_mailto('helpdesk@phoenixva.org'); ?> or by visiting
								<?php echo anchor('http://helpdesk.phoenixva.org'); ?> and logging a
								ticket. <strong>Whenever you contact support be sure to include your
								pilot ID#, <?php echo $user_id_full; ?>.</strong>
							</p>
							<p>
								You have been assigned pilot ID# <?php echo $user_id_full; ?>. You can use
								this ID as your callsign when flying online or you can use the callsign
								of whatever airline you are flying.
							</p>
							<?php if ($transfer_hours > 0): ?>
							<p>
								As part of the background checks we will verify your request to transfer
								<?php echo $transfer_hours; ?> hours from 
								<?php echo anchor($transfer_link); ?>. If
								the link does not work or we cannot verify your hours, the transfer
								hours will be removed.
							</p>
							<?php endif; ?>
						</div>
					</div>
				</div>
