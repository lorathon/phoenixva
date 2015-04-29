<?php
$form_attributes = array(
    'class' => 'form-horizontal form-bordered',
    'role' => 'form',
    'id' => 'form',
);

$label_attributes = array(
    'class' => 'col-md-3 control-label',
); 

$field_class = 'form-control';

$dep_airport = array(
    'name' => 'dep_airport',
    'id' => 'dep_airport',
    'class' => $field_class,
    'placeholder' => 'Select Departure Airport'
);

$dep_airport_id = array(
    'name' => 'dep_airport_id',
    'id' => 'dep_airport_id',
    'class' => $field_class,
    'type' => 'hidden'
);
?>

<div class="container">
    <div class="row">
	<?php $this->load->helper('form'); ?>

	<?php echo form_open_multipart('test/results', $form_attributes); ?>
	
	<?php echo form_input($dep_airport_id); ?>

	<div class="form-group">
	    <?php echo form_label('Departure Airport', $dep_airport['id'], $label_attributes); ?>
	    <div class="col-md-6"><?php echo form_input($dep_airport); ?></div>
	</div>
	
	<div class="form-group">
	    <div class="col-md-12">
		<?php echo form_submit('save', 'Save / Edit', 'class = "btn btn-primary btn-block"'); ?>
	    </div>
	</div>

	<?php echo form_close(); ?>
    </div>
</div>

