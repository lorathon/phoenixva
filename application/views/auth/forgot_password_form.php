<?php
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
<?php echo form_open($this->uri->uri_string()); ?>
<div class="modal-header">
    <h3>Send new password</h3>
    <p>Please provide the email address that you signed up with.</p>
</div>
<table class="table">
	<tr>
		<td><?php echo form_label($login_label, $login['id']); ?></td>
		<td><?php echo form_input($login); ?></td>
		<td style="color: red;"><?php echo form_error($login['name']); ?><?php echo isset($errors[$login['name']])?$errors[$login['name']]:''; ?></td>
	</tr>
    <tr>
        <td colspan="3" align="center"><?php echo form_submit('reset', 'Get a new password', 'class = "btn btn-primary btn-block"'); ?></td>
    </tr>
</table>
<?php echo form_close(); ?>