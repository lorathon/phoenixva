<?php
$form_attributes = array(
		'class' => 'form-horizontal',
		'role'  => 'form',
);

$label_attributes = array(
		'class' => 'col-sm-4 control-label',
);

$field_class = 'form-control';

$old_password = array(
	'name'	=> 'old_password',
	'id'	=> 'old_password',
	'value' => set_value('old_password'),
	'size' 	=> 30,
);
$new_password = array(
	'name'	=> 'new_password',
	'id'	=> 'new_password',
	'maxlength'	=> $this->config->item('password_max_length', 'tank_auth'),
	'size'	=> 30,
);
$confirm_new_password = array(
	'name'	=> 'confirm_new_password',
	'id'	=> 'confirm_new_password',
	'maxlength'	=> $this->config->item('password_max_length', 'tank_auth'),
	'size' 	=> 30,
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
	<?php echo form_open($this->uri->uri_string(), $form_attributes); ?>
	<div class="form-group">
		<div class="col-sm-3">
			<?php echo form_label('Old Password', $old_password['id']); ?>
		</div>
		<div class="col-sm-6">
			<?php echo form_password($old_password); ?>
		</div>
		<div class="col-sm-3">
			<?php echo form_error($old_password['name']); ?>
			<?php echo isset($errors[$old_password['name']])?$errors[$old_password['name']]:''; ?>
		</div>
	</div>
	<div class="form-group">
		<div class="col-sm-3">
			<?php echo form_label('New Password', $new_password['id']); ?>
		</div>
		<div class="col-sm-6">
			<?php echo form_password($new_password); ?>
		</div>
		<div class="col-sm-3">
			<?php echo form_error($new_password['name']); ?>
			<?php echo isset($errors[$new_password['name']])?$errors[$new_password['name']]:''; ?>
		</div>
	</div>
	<div class="form-group">
		<div class="col-sm-3">
			<?php echo form_label('Confirm New Password', $confirm_new_password['id']); ?>
		</div>
		<div class="col-sm-6">
			<?php echo form_password($confirm_new_password); ?>
		</div>
		<div class="col-sm-3">
			<?php echo form_error($confirm_new_password['name']); ?>
			<?php echo isset($errors[$confirm_new_password['name']])?$errors[$confirm_new_password['name']]:''; ?>
		</div>
	</div>
	<div class="form-group">
		<div class="col-sm-offset-2 col-sm-10">
			<?php echo form_submit('change', 'Change Password'); ?>
		</div>
	</div>
	<?php echo form_close(); ?>
</div>