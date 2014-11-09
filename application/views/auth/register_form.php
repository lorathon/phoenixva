<?php
$form_attributes = array(
		'class' => 'form-horizontal',
		'role'  => 'form',
		);

$label_attributes = array(
		'class' => 'col-sm-4 control-label',
		); 

$field_class = 'form-control';

if ($use_username) {
	$username = array(
		'name'	    => 'username',
		'id'	    => 'username',
		'value'     => set_value('username'),
		'maxlength'	=> $this->config->item('username_max_length', 'tank_auth'),
		'size'	    => 30,
			'class' => $field_class,
	);
}

$first_name = array(
	'name'	    => 'first_name',
	'id'	    => 'first_name',
	'value'	    => set_value('first_name'),
	'maxlength'	=> 100,
	'size'	    => 30,
		'class' => $field_class,
);

$last_name = array(
	'name'	    => 'last_name',
	'id'	    => 'last_name',
	'value'	    => set_value('last_name'),
	'maxlength'	=> 100,
	'size'	    => 30,
		'class' => $field_class,
);

$birth_date = array(
	'name'	    => 'birth_date',
	'id'	    => 'birth_date',
	'value'	    => set_value('birth_date'),
	'maxlength'	=> 80,
	'size'	    => 30,
	'class'     => $field_class.' datepicker',
		'placeholder' => 'YYYY-MM-DD',
);

$email = array(
	'name'	    => 'email',
	'id'	    => 'email',
	'value'	    => set_value('email'),
	'maxlength'	=> 80,
	'size'	    => 30,
		'class' => $field_class,
);

$crew_center = array(
		'name'  => 'crew_center',
		'id'    => 'crew_center',
		'value' => set_value('crew_center'),
		'class' => $field_class,
		);

$location = array(
		'name'  => 'location',
		'id'    => 'location',
		'value' => set_value('location'),
		'class' => $field_class,
		);

$password = array(
	'name'	    => 'password',
	'id'	    => 'password',
	'value'     => set_value('password'),
	'maxlength'	=> $this->config->item('password_max_length', 'tank_auth'),
	'size'	    => 30,
		'class' => $field_class,
);

$confirm_password = array(
	'name'	    => 'confirm_password',
	'id'	    => 'confirm_password',
	'value'     => set_value('confirm_password'),
	'maxlength'	=> $this->config->item('password_max_length', 'tank_auth'),
	'size'	    => 30,
		'class' => $field_class,
);

$transfer_hours = array(
		'name'  => 'transfer_hours',
		'id'    => 'transfer_hours',
		'value' => set_value('transfer_hours'),
		'class' => $field_class,
		'placeholder' => 'Maximum 150',
		);

$transfer_link = array(
		'name'  => 'transfer_link',
		'id'    => 'transfer_link',
		'value' => set_value('transfer_link'),
		'class' => $field_class,
		'placeholder' => 'http://exampleva.com/yourprofile',
		);

$heard_about = array(
		'name'  => 'heard_about',
		'id'    => 'heard_about',
		'value' => set_value('heard_about'),
		'class' => $field_class,
		'placeholder' => 'Google, Flightsim.com, AVSIM, friend, etc.',
		);

$recaptcha = array(
		'name' => 'recaptcha_response_field',
		'id'   => 'recaptcha_response_field',
		'class' => $field_class,
		);

$captcha = array(
	'name'	    => 'captcha',
	'id'	    => 'captcha',
	'maxlength'	=> 8,
		'class' => $field_class,
);
?>
<div class="container">
	<?php if ($errors):?>
		<div class="alert alert-danger">
			<?php foreach ($errors as $error): ?>
				<p><?php echo $error; ?></p>
			<?php endforeach; ?>
		</div>
	<?php endif; ?>
	<?php echo validation_errors('<div class="alert alert-danger" role="alert">','</div>'); ?>
	<div class="col-md-8">
		<?php echo form_open('auth/register', $form_attributes); ?>
		<div class="form-group">
			<?php if ($use_username): ?>
				<?php echo form_label('Username', $username['id'], $label_attributes); ?>
				<div class="col-sm-8"><?php echo form_input($username); ?></div>
			<?php endif; ?>
		</div>
		<div class="form-group">
			<?php echo form_label('First Name', $first_name['id'], $label_attributes); ?>
			<div class="col-sm-8"><?php echo form_input($first_name); ?></div>
		</div>
		<div class="form-group">
			<?php echo form_label('Last Name', $last_name['id'], $label_attributes); ?>
			<div class="col-sm-8"><?php echo form_input($last_name); ?></div>
		</div>
		<div class="form-group">
			<?php echo form_label('Birthday', $birth_date['id'], $label_attributes); ?>
			<div class="col-sm-8"><?php echo form_input($birth_date); ?></div>
		</div>
		<div class="form-group">
			<?php echo form_label('Email Address', $email['id'], $label_attributes); ?>
			<div class="col-sm-8"><?php echo form_input($email); ?></div>
		</div>
		<div class="form-group">
			<?php echo form_label('Desired Crew Center', $crew_center['id'], $label_attributes); ?>
			<div class="col-sm-8">
				<?php echo form_dropdown('crew_center',$hubs,set_value('crew_center'),"class='{$field_class}'"); ?>
			</div>
		</div>
		<div class="form-group">
			<?php echo form_label('Your Location', $location['id'], $label_attributes); ?>
			<div class="col-sm-8"><?php echo form_input($location); ?></div>
		</div>
		<div class="form-group">
			<?php echo form_label('Password', $password['id'], $label_attributes); ?>
			<div class="col-sm-8"><?php echo form_password($password); ?></div>
		</div>
		<div class="form-group">
			<?php echo form_label('Confirm Password', $confirm_password['id'], $label_attributes); ?>
			<div class="col-sm-8"><?php echo form_password($confirm_password); ?></div>
		</div>
		<div class="form-group">
			<?php echo form_label('Hours To Transfer', $transfer_hours['id'], $label_attributes); ?>
			<div class="col-sm-8"><?php echo form_input($transfer_hours); ?></div>
		</div>
		<div class="form-group">
			<?php echo form_label('Link To Verify Hours', $transfer_link['id'], $label_attributes); ?>
			<div class="col-sm-8"><?php echo form_input($transfer_link); ?></div>
		</div>
		<div class="form-group">
			<?php echo form_label('How Did You Hear About Us?', $heard_about['id'], $label_attributes); ?>
			<div class="col-sm-8"><?php echo form_input($heard_about); ?></div>
		</div>
		<?php if ($captcha_registration): ?>
				<?php if ($use_recaptcha): ?>
					<div class="form-group">
						<div class="col-sm-offset-4 col-sm-6"><div id="recaptcha_image"></div></div>
						<div class="col-sm-2">
							<a href="javascript:Recaptcha.reload()">Get another CAPTCHA</a>
							<div class="recaptcha_only_if_image"><a href="javascript:Recaptcha.switch_type('audio')">Get an audio CAPTCHA</a></div>
							<div class="recaptcha_only_if_audio"><a href="javascript:Recaptcha.switch_type('image')">Get an image CAPTCHA</a></div>
						</div>
					</div>
					<div class="form-group">
						<div class="col-sm-4 control-label recaptcha_only_if_image">Enter the words above</div>
						<div class="col-sm-4 control-label recaptcha_only_if_audio">Enter the numbers you hear</div>
						<div class="col-sm-8"><?php echo form_input($recaptcha); ?></div>
					</div>
					<div class="form-group">
						<?php echo $recaptcha_html; ?>
					</div>
				<?php else: ?>
					<div class="form-group">
						<p class="help-block">Enter the code exactly as it appears:</p>
						<div class="col-sm-offset-2 col-sm-10">
							<?php echo $captcha_html; ?>
						</div>
					</div>
					<div class="form-group">
						<?php echo form_label('Confirmation Code', $captcha['id'], $label_attributes); ?>
						<div class="col-sm-10"><?php echo form_input($captcha); ?></div>
					</div>
				<?php endif; ?>
		<?php endif; ?>
		<div class="form-group">
			<div class="col-sm-offset-2 col-sm-10">
				<?php echo form_submit('register', 'Join Now!', 'class = "btn btn-primary btn-block"'); ?>
			</div>
		</div>
		
		<?php echo form_close(); ?>
	</div>
	<div class="col-md-4">
		<p class="lead">Some quick points to know...</p>
		<ol>
			<li>No experience is needed, we welcome pilots of all skills.</li>
			<li>PVA and our community predominately read/write <strong>English</strong>. If you 
			are not comfortable with English, PVA might not be a good fit for 
			you. You will be required to join our Forum and make a few posts so 
			you can know us and we can meet you.</li>
			<li>All applications are subject to an online background check. 
			Please allow up to one week for your application to be processed. 
			You may be asked additional questions during this process. Please 
			make sure the email address you provide is working and check your 
			spam filters to make sure our emails are arriving.</li>
			<li>If you have transfer hours, you must provide a direct link to 
			verify the hours. Screenshots will not be accepted.</li>
		</ol>
	</div>
</div>

<script>
$('.datepicker').datepicker({format: 'yyyy-mm-dd', viewMode: 2});
$('.datepicker').css('z-index', 99999999999999);
</script>