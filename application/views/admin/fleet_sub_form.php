<?php
$form_attributes = array(
    'class' => 'form-horizontal form-bordered',
    'role'  => 'form',
);

$label_attributes = array(
    'class' => 'col-md-3 control-label',
); 

$field_class = 'form-control';

$id = array(
    'name'	    => 'id',
    'id'	    => 'id',
    'value'	    => set_value($record->id),
    'class'         => $field_class,
);

$designation = array(
    'name'	    => 'designation',
    'id'	    => 'designation',
    'value'	    => set_value('designation', $record->designation),
    'class'         => $field_class,
);

$manufacturer = array(
    'name'	    => 'manufacturer',
    'id'	    => 'manufacturer',
    'value'	    => set_value('manufacturer', $record->manufacturer),
    'class'         => $field_class,
);

$equips = array(
    'name'	    => 'equips',
    'id'	    => 'equips',
    'value'	    => set_value('img_folder', $record->equips),
    'class'         => $field_class,
    'rows'	    => 4,
);

$hours_needed = array(
    'name'	    => 'hours_needed',
    'id'	    => 'hours_needed',
    'value'	    => set_value('hours_needed', $record->hours_needed),
    'class'         => $field_class,
);

$category = array(
    'name'	    => 'category',
    'id'	    => 'category',
    'value'	    => set_value('category', $record->category),
    'class'         => $field_class,
);

$rated = array(
    'name'	    => 'rated',
    'id'	    => 'rated',
    'value'	    => TRUE,
    'checked'	    => $record->rated ? TRUE : FALSE,
    'class'         => $field_class,
);

?>

<div class="container">    
    <div class="row">
        <div class="col-md-4">
            <section class="panel">
                <header class="panel-heading">
                    <h2 class="panel-title">Aircraft Sub Form</h2>
                </header>
                <div class="panel-body">

                    <?php echo form_open_multipart('admin/fleet/create_sub', $form_attributes); ?>

                    <?php echo form_hidden('id', $record->id); ?>

                    <div class="form-group">
                        <?php echo form_label('Designation', $designation['id'], $label_attributes); ?>
                        <div class="col-md-6"><?php echo form_input($designation); ?></div>
                    </div>

                    <div class="form-group">
                        <?php echo form_label('Manufacturer', $manufacturer['id'], $label_attributes); ?>
                        <div class="col-md-6"><?php echo form_input($manufacturer); ?></div>
                    </div>
                    
                    <div class="form-group">
                        <?php echo form_label('Airframes', $equips['id'], $label_attributes); ?>
                        <div class="col-md-6"><?php echo form_textarea($equips); ?></div>
                    </div>                    
		    
		    <div class="form-group">
                        <?php echo form_label('Hours Needed', $hours_needed['id'], $label_attributes); ?>
                        <div class="col-md-6">
                            <?php echo form_dropdown('hours_needed', $hours, $hours_needed['value'], "class='{$field_class}'"); ?>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <?php echo form_label('A/C Category', $category['id'], $label_attributes); ?>
                        <div class="col-md-6">
			    <?php echo form_dropdown('category', $aircraft_cat, $category['value'], "class='{$field_class}'"); ?>
			</div>
                    </div>
		    
		    <div class="form-group">
                        <?php echo form_label('TR Rated', $rated['id'], $label_attributes); ?>
                        <div class="col-md-6"><?php echo form_checkbox($rated); ?></div>
                    </div>

                    <div class="form-group">
                        <div class="col-md-12">
                            <?php echo form_submit('save', 'Save / Edit', 'class = "btn btn-primary btn-block"'); ?>
                        </div>
                    </div>

                    <?php echo form_close(); ?>
                </div>
            </section>
        </div>
    </div>
</div>

