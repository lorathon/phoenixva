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

$description = array(
    'name'	    => 'description',
    'id'	    => 'description',
    'value'	    => set_value('description', $record->description),
    'class'         => $field_class,
    'rows'          => 4,
);

$award_type_id = array(
    'name'	    => 'award_type_id',
    'id'	    => 'award_type_id',
    'value'	    => set_value('award_type_id', $record->award_type_id),
    'class'         => $field_class,
);

?>

<div class="container">
    <div class="row">
        <div class="col-md-4">
            <section class="panel">
                <header class="panel-heading">
                    <h2 class="panel-title">Award Form</h2>
                </header>
                <div class="panel-body">

                    <?php echo form_open_multipart('admin/awards/create_award', $form_attributes); ?>

                    <?php echo form_hidden('id', $record->id); ?>

                    <div class="form-group">
                        <?php echo form_label('Name', $name['id'], $label_attributes); ?>
                        <div class="col-md-6"><?php echo form_input($name); ?></div>
                    </div>

                    <div class="form-group">
                        <?php echo form_label('Award Image', $award_image['id'], $label_attributes); ?>
                        <div class="col-md-6"><?php echo form_input($award_image); ?></div>
                    </div>

                    <div class="form-group">
                        <?php echo form_label('Description', $description['id'], $label_attributes); ?>
                        <div class="col-md-6"><?php echo form_textarea($description); ?></div>
                    </div>
                    
                    <div class="form-group">
                        <?php echo form_label('Award Type', $award_type_id['id'], $label_attributes); ?>
                        <div class="col-md-6">
                            <?php echo form_dropdown('award_type_id', $award_types, $award_type_id['value'], "class='{$field_class}'"); ?>
                        </div>
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
