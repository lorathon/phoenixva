<?php
$form_attributes = array(
		'class' => 'form-horizontal',
		'role'  => 'form',
);

$label_attributes = array(
		'class' => 'col-sm-4 control-label',
);

$field_class = 'form-control';

$login = array(
	'name'	=> 'login',
	'id'	=> 'login',
	'value' => set_value('login'),
	'maxlength'	=> 80,
	'size'	=> 30,
);
if ($login_by_username AND $login_by_email) {
	$login_label = 'Email or login';
} else if ($login_by_username) {
	$login_label = 'Login';
} else {
	$login_label = 'Email';
}
$password = array(
	'name'	=> 'password',
	'id'	=> 'password',
	'size'	=> 30,
);
$remember = array(
	'name'	=> 'remember',
	'id'	=> 'remember',
	'value'	=> 1,
	'checked'	=> set_value('remember'),
	'style' => 'margin:0;padding:0',
);

$recaptcha = array(
		'name' => 'recaptcha_response_field',
		'id'   => 'recaptcha_response_field',
		'class' => $field_class,
);


$captcha = array(
	'name'	=> 'captcha',
	'id'	=> 'captcha',
	'maxlength'	=> 8,
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
	<div class="col-md-offset-4 col-md-4">
		<?php echo form_open('auth/login'); ?>
		<div class="form-group">
			<?php echo form_label($login_label, $login['id'], $label_attributes); ?>
			<div class="col-sm-8"><?php echo form_input($login); ?></div>
		</div>
		<div class="form-group">
			<?php echo form_label('Password', $password['id'], $label_attributes); ?>
			<div class="col-sm-8"><?php echo form_password($password); ?></div>
		</div>
		<?php if ($show_captcha): ?>
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
		<div class="checkbox">
    		<label>
      			<?php echo form_checkbox($remember); ?> <?php echo form_label('Remember me', $remember['id']); ?>
    		</label>
  		</div>
  		<div class="row">
  			<div class="col-md-12 text-center">
  				<?php echo anchor('/auth/forgot_password/', 'Forgot password'); ?> | 
                <?php if ($this->config->item('allow_registration', 'tank_auth')) echo anchor('/auth/register/', 'Register'); ?>
  			</div>
  		</div>
  		<div class="form-group">
			<div class="col-sm-offset-2 col-sm-10">
				<?php echo form_submit('submit', 'Login', 'class = "btn btn-primary btn-block"'); ?>
			</div>
		</div>
		<?php echo form_close(); ?>
	</div>
</div>