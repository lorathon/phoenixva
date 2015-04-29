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

<style>
    .ui-autocomplete {
	position: absolute;
	top: 100%;
	left: 0;
	z-index: 1000;
	float: left;
	display: none;
	min-width: 160px;
	_width: 160px;
	padding: 4px 0;
	margin: 2px 0 0 0;
	list-style: none;
	background-color: #ffffff;
	border-color: #ccc;
	border-color: rgba(0, 0, 0, 0.2);
	border-style: solid;
	border-width: 1px;
	-webkit-border-radius: 5px;
	-moz-border-radius: 5px;
	border-radius: 5px;
	-webkit-box-shadow: 0 5px 10px rgba(0, 0, 0, 0.2);
	-moz-box-shadow: 0 5px 10px rgba(0, 0, 0, 0.2);
	box-shadow: 0 5px 10px rgba(0, 0, 0, 0.2);
	-webkit-background-clip: padding-box;
	-moz-background-clip: padding;
	background-clip: padding-box;
	*border-right-width: 2px;
	*border-bottom-width: 2px;

	.ui-menu-item > a.ui-corner-all {
	    display: block;
	    padding: 3px 15px;
	    clear: both;
	    font-weight: normal;
	    line-height: 18px;
	    color: #555555;
	    white-space: nowrap;

	    &.ui-state-hover, &.ui-state-active {
		color: #ffffff;
		text-decoration: none;
		background-color: #0088cc;
		border-radius: 0px;
		-webkit-border-radius: 0px;
		-moz-border-radius: 0px;
		background-image: none;
	    }
	}
    }
</style>

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

