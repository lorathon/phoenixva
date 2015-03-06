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

$description = array(
    'name'	    => 'description',
    'id'	    => 'description',
    'value'	    => set_value('description', $record->description),
    'class'         => $field_class,
    'rows'          => 4,
);

$img_folder = array(
    'name'	    => 'img_folder',
    'id'	    => 'img_folder',
    'value'	    => set_value('img_folder', $record->img_folder),
    'class'         => $field_class,
);

$img_width = array(
    'name'	    => 'img_width',
    'id'	    => 'img_width',
    'value'	    => set_value('img_width', $record->img_width),
    'class'         => $field_class,
);

$img_height = array(
    'name'	    => 'img_height',
    'id'	    => 'img_height',
    'value'	    => set_value('img_height', $record->img_height),
    'class'         => $field_class,
);

?>

<div class="container">
    <div class="row">
        <div class="col-md-4">
            <section class="panel">
                <header class="panel-heading">
                    <h2 class="panel-title">Award Type Form</h2>
                </header>
                <div class="panel-body">

                    <?php echo form_open_multipart('admin/awards/create_award_type', $form_attributes); ?>

                    <?php echo form_hidden('id', $record->id); ?>

                    <div class="form-group">
                        <?php echo form_label('Name', $name['id'], $label_attributes); ?>
                        <div class="col-md-6"><?php echo form_input($name); ?></div>
                    </div>

                    <div class="form-group">
                        <?php echo form_label('Description', $description['id'], $label_attributes); ?>
                        <div class="col-md-6"><?php echo form_textarea($description); ?></div>
                    </div>
                    
                    <div class="form-group">
                        <?php echo form_label('Image Folder', $img_folder['id'], $label_attributes); ?>
                        <div class="col-md-6"><?php echo form_input($img_folder); ?></div>
                    </div>
                    
                    <div class="form-group">
                        <?php echo form_label('Image Width', $img_width['id'], $label_attributes); ?>
                        <div class="col-md-6"><?php echo form_input($img_width); ?></div>
                    </div>
                    
                    <div class="form-group">
                        <?php echo form_label('Image Height', $img_height['id'], $label_attributes); ?>
                        <div class="col-md-6"><?php echo form_input($img_height); ?></div>
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

