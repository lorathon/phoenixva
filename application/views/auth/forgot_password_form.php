<?php
$form_attributes = array(
		'class' => 'form-horizontal',
		'role'  => 'form',
);

$label_attributes = array(
		'class' => 'col-sm-4 control-label',
);
$login = array(
	'name'	=> 'login',
	'id'	=> 'login',
	'value' => set_value('login'),
	'maxlength'	=> 80,
	'size'	=> 30,
);
if ($this->config->item('use_username', 'tank_auth')) {
	$login_label = 'Email or login';
} else {
	$login_label = 'Email';
}
?>
<div class="container">
    <h3>Send new password</h3>
    <p>Please provide the email address that you signed up with.</p>
	<?php echo form_open($this->uri->uri_string(), $form_attributes); ?>
	<div class="form-group">
		<div class="col-sm-3">
			<?php echo form_label($login_label, $login['id']); ?>
		</div>
		<div class="col-sm-6">
			<?php echo form_input($login); ?>
		</div>
		<div class="col-sm-3">
			<?php echo form_error($login['name']); ?><?php echo isset($errors[$login['name']])?$errors[$login['name']]:''; ?>
		</div>
	</div>
	<div class="form-group">
		<div class="col-sm-offset-2 col-sm-10">
			<?php echo form_submit('reset', 'Get a new password', 'class = "btn btn-primary btn-block"'); ?>
		</div>
	</div>
	<?php echo form_close(); ?>
</div>
