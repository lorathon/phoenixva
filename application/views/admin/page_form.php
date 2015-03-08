<?php
$this->load->helper('form');

$form_attributes = array(
		'class' => 'form-horizontal',
		'role'  => 'form',
);

$label_attributes = array(
		'class' => 'col-sm-4 control-label',
);

$field_class = 'form-control';

$title = array(
		'name'  => 'pagetitle',
		'id'    => 'pagetitle',
		'value' => set_value('pagetitle', $pagetitle),
		'class' => $field_class,
);

$slug = array(
		'name'  => 'slug',
		'id'    => 'slug',
		'value' => set_value('slug', $slug),
		'class' => $field_class,
);

$body = array(
		'name'  => 'pagebody',
		'id'    => 'pagebody',
		'value' => set_value('pagebody', $pagebody),
		'rows'  => '40',
		'class' => $field_class,
);

// Set up the editor using jQuery
$id = $body['id'];
$style = base_url('assets/sceditor/jquery.sceditor.default.min.css');
$GLOBALS['page_script'] = 
		"$(function() {
			$('#{$id}').sceditor({
				plugins: 'bbcode',
				style: '{$style}'
			});
		});";

?>
<div class="container">
	<?php echo validation_errors('<div class="alert alert-danger" role="alert">','</div>'); ?>
	<?php echo form_open('admin/articles/edit', $form_attributes); ?>
	<div class="col-md-8">
		<div class="form-group">
			<?php echo form_label('Page Title', $title['id'], $label_attributes); ?>
			<div class="col-sm-8"><?php echo form_input($title); ?></div>
		</div>
		<div class="form-group">
			<?php echo form_label('URL Slug', $slug['id'], $label_attributes); ?>
			<div class="col-sm-8"><?php	echo form_input($slug);	?></div>
		</div>
		<div class="form-group">
			<?php echo form_label('Content', $body['id']); ?>
			<div><?php echo form_textarea($body); ?></div>
		</div>
	</div>
	<div class="col-md-4">
		<div class="form-group">
			<div class="col-sm-offset-2 col-sm-10">
				<?php echo form_submit('submit', 'Save Page', 'class = "btn btn-primary btn-block"'); ?>
				<?php echo form_reset('reset', 'Reset', 'class = "btn btn-danger btn-block"'); ?>
			</div>
		</div>
	</div>
	<?php echo form_close(); ?>
</div>
