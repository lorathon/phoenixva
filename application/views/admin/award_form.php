<?php
$form_attributes = array(
    'class' => 'form-horizontal',
    'role'  => 'form',
);

$label_attributes = array(
    'class' => 'col-sm-1 control-label',
); 

$field_class = 'form-control';

$id = array(
    'name'	    => 'id',
    'id'	    => 'id',
    'value'	    => set_value($record->id),
    'class'         => $field_class,
);

$name = array(
    'name'	    => 'name',
    'id'	    => 'name',
    'value'	    => set_value('name', $record->name),
    'class'         => $field_class,
);

$award_image = array(
    'name'	    => 'award_image',
    'id'	    => 'award_image',
    'value'	    => set_value('award_image', $record->award_image),
    'class'         => $field_class,
);

$descrip = array(
    'name'	    => 'descrip',
    'id'	    => 'descrip',
    'value'	    => set_value('descrip', $record->descrip),
    'class'         => $field_class,
);

$type = array(
    'name'	    => 'type',
    'id'	    => 'type',
    'value'	    => set_value('type', $record->type),
    'class'         => $field_class,
);

?>

<div class="container">
    
    <?php if ($errors):?>
        <div class="alert alert-danger">            
            <p><?php echo $errors; ?></p>
	</div>
    <?php endif; ?>     
    
    <?php echo form_open_multipart('admin/awards/create_award', $form_attributes); ?>
    
        <?php echo form_hidden('id', $record->id); ?>
    
        <div class="form-group">
            <?php echo form_label('Name', $name['id'], $label_attributes); ?>
            <div class="col-sm-2"><?php echo form_input($name); ?></div>
	</div>
    
        <div class="form-group">
            <?php echo form_label('Award Image', $award_image['id'], $label_attributes); ?>
            <div class="col-sm-2"><?php echo form_input($award_image); ?></div>
	</div>
    
        <div class="form-group">
            <?php echo form_label('Description', $descrip['id'], $label_attributes); ?>
            <div class="col-sm-2"><?php echo form_input($descrip); ?></div>
	</div>
        
        <div class="form-group">
            <?php echo form_label('Award Type', $type['id'], $label_attributes); ?>
            <div class="col-sm-2">
		<?php echo form_dropdown('type',$types,set_value('type'),"class='{$field_class}'"); ?>
            </div>
	</div>
    
        <div class="form-group">
            <div class="col-sm-3">
                <?php echo form_submit('save', 'Save / Edit', 'class = "btn btn-primary btn-block"'); ?>
            </div>
        </div>

    <?php echo form_close(); ?>
</div>

