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

$iata = array(
    'name'	    => 'iata',
    'id'	    => 'iata',
    'value'	    => set_value('iata', $record->iata),
    'class'         => $field_class,
    'readonly'	    => 'readonly',
);

$icao = array(
    'name'	    => 'icao',
    'id'	    => 'icao',
    'value'	    => set_value('icao', $record->icao),
    'class'         => $field_class,
    'readonly'	    => 'readonly',
);

$name = array(
    'name'	    => 'name',
    'id'	    => 'name',
    'value'	    => set_value('name', $record->name),
    'class'         => $field_class,
    'placeholder'   => 'Name of aircraft',
    'required'      => 'required',    
);

$category = array(
    'name'	    => 'category',
    'id'	    => 'category',
    'value'	    => set_value('category', $record->category),
    'class'         => $field_class,
);

$regional = array(
    'name'	    => 'regional',
    'id'	    => 'regional',
    'value'	    => TRUE,
    'checked'	    => $record->regional ? TRUE : FALSE,
    'class'	    => $field_class,
);

$turboprop = array(
    'name'	    => 'turboprop',
    'id'	    => 'turboprop',
    'value'	    => TRUE,
    'checked'	    => $record->turboprop ? TRUE : FALSE,
    'class'	    => $field_class,
);

$jet = array(
    'name'	    => 'jet',
    'id'	    => 'jet',
    'value'	    => TRUE,
    'checked'	    => $record->jet ? TRUE : FALSE,
    'class'	    => $field_class,
);

$widebody = array(
    'name'	    => 'widebody',
    'id'	    => 'widebody',
    'value'	    => TRUE,
    'checked'	    => $record->widebody ? TRUE : FALSE,
    'class'	    => $field_class,
);

$pax_first = array(
    'name'	    => 'pax_first',
    'id'	    => 'pax_first',
    'value'	    => set_value('pax_first', $record->pax_first),
    'class'         => $field_class,
);

$pax_business = array(
    'name'	    => 'pax_business',
    'id'	    => 'pax_business',
    'value'	    => set_value('pax_business', $record->pax_business),
    'class'         => $field_class,
);

$pax_economy = array(
    'name'	    => 'pax_economy',
    'id'	    => 'pax_economy',
    'value'	    => set_value('pax_economy', $record->pax_economy),
    'class'         => $field_class,
);

$max_cargo = array(
    'name'	    => 'max_cargo',
    'id'	    => 'max_cargo',
    'value'	    => set_value('max_cargo', $record->max_cargo),
    'class'         => $field_class,
);

$oew = array(
    'name'	    => 'oew',
    'id'	    => 'oew',
    'value'	    => set_value('oew', $record->oew),
    'class'         => $field_class,
);

$mzfw = array(
    'name'	    => 'mzfw',
    'id'	    => 'mzfw',
    'value'	    => set_value('mzfw', $record->mzfw),
    'class'         => $field_class,
);

$mlw = array(
    'name'	    => 'mlw',
    'id'	    => 'mlw',
    'value'	    => set_value('mlw', $record->mlw),
    'class'         => $field_class,
);

$mtow = array(
    'name'	    => 'mtow',
    'id'	    => 'mtow',
    'value'	    => set_value('mtow', $record->mtow),
    'class'         => $field_class,
);
?>

<header class="page-header">
    <h2>Edit Airframe</h2>
</header>

<div class="container">
    <div class="row">
        <div class="col-md-8">
            <section class="panel">
                <header class="panel-heading">
                    <h2 class="panel-title"><?php echo $record->name; ?></h2>
                </header>
                <div class="panel-body">

                    <?php echo form_open_multipart('admin/airframes/edit_airframe', $form_attributes); ?>

                    <?php echo form_hidden('id', $record->id); ?>
		    
		    <div class="form-group">
                        <?php echo form_label('IATA', $iata['id'], $label_attributes); ?>
                        <div class="col-md-6"><?php echo form_input($iata); ?></div>
                    </div>
		    
		    <div class="form-group">
                        <?php echo form_label('ICAO', $icao['id'], $label_attributes); ?>
                        <div class="col-md-6"><?php echo form_input($icao); ?></div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-3 control-label">Name <span class="required">*</span></label>
                        <div class="col-md-6"><?php echo form_input($name); ?></div>
                    </div>
		    
		    <div class="form-group">
                        <?php echo form_label('A/C Category', $category['id'], $label_attributes); ?>
                        <div class="col-md-6">
			    <?php echo form_dropdown('category', $aircraft_cat, $category['value'], "class='{$field_class}'"); ?>
			</div>
                    </div>
		    
		    <div class="form-group">
                        <?php echo form_label('Regional', $regional['id'], $label_attributes); ?>
                        <div class="col-md-6"><?php echo form_checkbox($regional); ?></div>
                    </div>
		    
		    <div class="form-group">
                        <?php echo form_label('Turboprop', $turboprop['id'], $label_attributes); ?>
                        <div class="col-md-6"><?php echo form_checkbox($turboprop); ?></div>
                    </div>
		    
		    <div class="form-group">
                        <?php echo form_label('Jet', $jet['id'], $label_attributes); ?>
                        <div class="col-md-6"><?php echo form_checkbox($jet); ?></div>
                    </div>
		    
		    <div class="form-group">
                        <?php echo form_label('Widebody', $widebody['id'], $label_attributes); ?>
                        <div class="col-md-6"><?php echo form_checkbox($widebody); ?></div>
                    </div>
		    
		    <div class="form-group">
			<?php echo form_label('1st Class Seats', $pax_first['id'], $label_attributes); ?>
                        <div class="col-md-6"><?php echo form_input($pax_first); ?></div>
                    </div>
		    
		    <div class="form-group">
			<?php echo form_label('Business Class Seats', $pax_business['id'], $label_attributes); ?>
                        <div class="col-md-6"><?php echo form_input($pax_business); ?></div>
                    </div>
		    
		    <div class="form-group">
			<?php echo form_label('Economy Class Seats', $pax_economy['id'], $label_attributes); ?>
                        <div class="col-md-6"><?php echo form_input($pax_economy); ?></div>
                    </div>
		    
		    <div class="form-group">
			<?php echo form_label('Cargo Capacity', $max_cargo['id'], $label_attributes); ?>
                        <div class="col-md-6">
			    <div class="input-group mb-md">
				<?php echo form_input($max_cargo); ?>
				<span class="input-group-addon btn-warning">lbs</span>
			    </div>
			</div>
                    </div>
		    
		    <div class="form-group">
			<?php echo form_label('OEW', $oew['id'], $label_attributes); ?>
                        <div class="col-md-6">
			    <div class="input-group mb-md">
				<?php echo form_input($oew); ?>
				<span class="input-group-addon btn-warning">lbs</span>
			    </div>
			</div>
                    </div>
		    
		    <div class="form-group">
			<?php echo form_label('MZFW', $mzfw['id'], $label_attributes); ?>
                        <div class="col-md-6">
			    <div class="input-group mb-md">
				<?php echo form_input($mzfw); ?>
				<span class="input-group-addon btn-warning">lbs</span>
			    </div>
			</div>
                    </div>
		    
		    <div class="form-group">
			<?php echo form_label('MTOW', $mtow['id'], $label_attributes); ?>
                        <div class="col-md-6">
			    <div class="input-group mb-md">
				<?php echo form_input($mtow); ?>
				<span class="input-group-addon btn-warning">lbs</span>
			    </div>
			</div>
                    </div>
		    
		    <div class="form-group">
			<?php echo form_label('MLW', $mlw['id'], $label_attributes); ?>
                        <div class="col-md-6">
			    <div class="input-group mb-md">
				<?php echo form_input($mlw); ?>
				<span class="input-group-addon btn-warning">lbs</span>
			    </div>
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
