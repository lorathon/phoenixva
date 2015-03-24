<?php
$form_attributes = array(
    'class' => 'form-horizontal form-bordered',
    'role'  => 'form',
    'id'    => 'form',
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
    'placeholder'   => 'Name of airline',
    'required'      => 'required'
    
);

$iata = array(
    'name'	    => 'iata',
    'id'	    => 'iata',
    'value'	    => set_value('iata', $record->iata),
    'class'         => $field_class,    
    'placeholder'   => 'IATA Code',
);

$icao = array(
    'name'	    => 'icao',
    'id'	    => 'icao',
    'value'	    => set_value('icao', $record->icao),
    'class'         => $field_class,
    'placeholder'   => 'ICAO Code',
);

$active = array(
    'name'	    => 'active',
    'id'	    => 'active',
    'value'	    => TRUE,
    'checked'	    => $record->active ? TRUE : FALSE,
    'class'	    => $field_class,
);

$category = array(
    'name'	    => 'category',
    'id'	    => 'category',
    'value'	    => set_value('category', $record->category),
    'class'         => $field_class,
);

?>

<div class="container">
    <div class="row">
        <div class="col-md-8">
            <section class="panel">
                <header class="panel-heading">
                    <h2 class="panel-title">Airline Form</h2>
                </header>
                <div class="panel-body">

                    <?php echo form_open_multipart('private/airlines/edit-airline', $form_attributes); ?>

                    <?php echo form_hidden('id', $record->id); ?>

                    <div class="form-group">
                        <label class="col-md-3 control-label">Name <span class="required">*</span></label>
                        <div class="col-md-6"><?php echo form_input($name); ?></div>
                    </div>
		    
		    <div class="form-group">
                        <?php echo form_label('IATA Code', $iata['id'], $label_attributes); ?>
                        <div class="col-md-6"><?php echo form_input($iata); ?></div>
                    </div>
		    
		    <div class="form-group">
                        <?php echo form_label('ICAO Code', $icao['id'], $label_attributes); ?>
                        <div class="col-md-6"><?php echo form_input($icao); ?></div>
                    </div>
		    
		    <div class="form-group">
                        <?php echo form_label('Active', $active['id'], $label_attributes); ?>
                        <div class="col-md-6"><?php echo form_checkbox($active); ?></div>
                    </div>
                    
                    <div class="form-group">
                        <?php echo form_label('Category', $category['id'], $label_attributes); ?>
                        <div class="col-md-6">
                            <?php echo form_dropdown('category', $categories, $category['value'], "class='{$field_class}'"); ?>
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
