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
		'name'  => 'title',
		'id'    => 'title',
		'value' => set_value('title'),
		'class' => $field_class,
);

$slug = array(
		'name'  => 'slug',
		'id'    => 'slug',
		'value' => set_value('slug'),
		'class' => $field_class,
);

$body = array(
		'name'  => 'pagebody',
		'id'    => 'pagebody',
		'value' => set_value('body'),
		'rows'  => '40',
		'cols'  => '100',
);

?>
<div class="container">
	<?php echo validation_errors('<div class="alert alert-danger" role="alert">','</div>'); ?>
	<div class="col-md-8">
		<?php echo form_open('admin/articles/edit', $form_attributes); ?>
		<div class="form-group">
			<?php echo form_label('Page Title', $title['id'], $label_attributes); ?>
			<div class="col-sm-8"><?php echo form_input($title); ?></div>
		</div>
		<div class="form-group">
			<?php echo form_label('URL Slug', $slug['id'], $label_attributes); ?>
			<div class="col-sm-8"><?php echo form_input($slug); ?></div>
		</div>
		<div class="form-group">
			<?php echo form_label('Content', $body['id']); ?>
			<div><?php echo form_textarea($body); ?></div>
		</div>
		<script>
		document.addEventListener("load", function() {
				$("#<?php echo $body['id']; ?>").sceditor({
					plugins: "bbcode",
					style: "<?php echo base_url('assets/sceditor/jquery.sceditor.default.min.css'); ?>"
				});
				alert('loaded');
			});
		</script>
		<div class="form-group">
			<div class="col-sm-offset-2 col-sm-10">
				<?php echo form_submit('submit', 'Save Page', 'class = "btn btn-primary btn-block"'); ?>
				<?php echo form_reset('reset', 'Reset', 'class = "btn btn-danger btn-block"'); ?>
			</div>
		</div>
		<?php echo form_close(); ?>
	</div>
</div>
