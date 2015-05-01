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
);

$icao = array(
    'name'	    => 'icao',
    'id'	    => 'icao',
    'value'	    => set_value('icao', $record->icao),
    'class'         => $field_class,
);

$name = array(
    'name'	    => 'name',
    'id'	    => 'name',
    'value'	    => set_value('name', $record->name),
    'class'         => $field_class,
);

$state_code = array(
    'name'	    => 'state_code',
    'id'	    => 'state_code',
    'value'	    => set_value('state_code', $record->state_code),
    'class'         => $field_class,
);

$country_code = array(
    'name'	    => 'country_code',
    'id'	    => 'country_code',
    'value'	    => set_value('country_code', $record->country_code),
    'class'         => $field_class,
);

$region_name = array(
    'name'	    => 'region_name',
    'id'	    => 'region_name',
    'value'	    => set_value('region_name', $record->region_name),
    'class'         => $field_class,
);

$utc_offset = array(
    'name'	    => 'utc_offset',
    'id'	    => 'utc_offset',
    'value'	    => set_value('utc_offset', $record->utc_offset),
    'class'         => $field_class,
);

$lat = array(
    'name'	    => 'lat',
    'id'	    => 'lat',
    'value'	    => set_value('lat', $record->lat),
    'class'         => $field_class,
);

$long = array(
    'name'	    => 'long',
    'id'	    => 'long',
    'value'	    => set_value('long', $record->long),
    'class'         => $field_class,
);

$elevation = array(
    'name'	    => 'elevation',
    'id'	    => 'elevation',
    'value'	    => set_value('elevation', $record->elevation),
    'class'         => $field_class,
);

$classification = array(
    'name'	    => 'classification',
    'id'	    => 'classification',
    'value'	    => set_value('classification', $record->classification),
    'class'         => $field_class,
);

$active = array(
    'name'	    => 'active',
    'id'	    => 'active',
    'value'	    => TRUE,
    'checked'	    => $record->active ? TRUE : FALSE,
    'class'	    => $field_class,
);

$port_type = array(
    'name'	    => 'port_type',
    'id'	    => 'port_type',
    'value'	    => set_value('port_type', $record->port_type),
    'class'         => $field_class,
);

$hub = array(
    'name'	    => 'hub',
    'id'	    => 'hub',
    'value'	    => TRUE,
    'checked'	    => $record->hub ? TRUE : FALSE,
    'class'	    => $field_class,
);

$delay_url = array(
    'name'	    => 'delay_url',
    'id'	    => 'delay_url',
    'value'	    => set_value('delay_url', $record->delay_url),
    'class'         => $field_class,
);

$weather_url = array(
    'name'	    => 'weather_url',
    'id'	    => 'weather_url',
    'value'	    => set_value('weather_url', $record->weather_url),
    'class'         => $field_class,
);

$version = array(
    'name'	    => 'version',
    'id'	    => 'version',
    'value'	    => set_value('version', $record->version),
    'class'         => $field_class,
);

$autocomplete = array(
    'name'	    => 'autocomplete',
    'id'	    => 'autocomplete',
    'value'	    => set_value('autocomplete', $record->autocomplete),
    'class'         => $field_class,
);
?>


<div class="container">
    <div class="row">
        <div class="col-md-8">
            <section class="panel">
                <header class="panel-heading">
                    <h2 class="panel-title"><?php echo $record->name; ?></h2>
                </header>
                <div class="panel-body">

                    <?php echo form_open_multipart('private/airports/edit-airport', $form_attributes); ?>

                    <?php echo form_hidden('id', $record->id); ?>
		    
		    <div class="form-group">
			<label class="col-md-3 control-label">Name <span class="required">*</span></label>
                        <div class="col-md-6"><?php echo form_input($name); ?></div>
                    </div>
		    
		    <div class="form-group">
			<?php echo form_label('IATA', $iata['id'], $label_attributes); ?>
                        <div class="col-md-6"><?php echo form_input($iata); ?></div>
                    </div>
		    
		    <div class="form-group">
			<?php echo form_label('ICAO', $icao['id'], $label_attributes); ?>
                        <div class="col-md-6"><?php echo form_input($icao); ?></div>
                    </div>
		    
		    <div class="form-group">
			<label class="col-md-3 control-label">Latitude <span class="required">*</span></label>
                        <div class="col-md-6"><?php echo form_input($lat); ?></div>
                    </div>
		    
		    <div class="form-group">
			<label class="col-md-3 control-label">Longitude <span class="required">*</span></label>
                        <div class="col-md-6"><?php echo form_input($long); ?></div>
                    </div>
		    
		    <div class="form-group">
			<?php echo form_label('Elevation', $elevation['id'], $label_attributes); ?>
                        <div class="col-md-6">
			    <div class="input-group mb-md">
				<?php echo form_input($elevation); ?>
				<span class="input-group-addon btn-warning">ft</span>
			    </div>
			</div>
                    </div>
		    
		    <div class="form-group">
                        <?php echo form_label('Active', $active['id'], $label_attributes); ?>
                        <div class="col-md-6"><?php echo form_checkbox($active); ?></div>
                    </div>
		    
		    <div class="form-group">
                        <?php echo form_label('Crew Center', $hub['id'], $label_attributes); ?>
                        <div class="col-md-6"><?php echo form_checkbox($hub); ?></div>
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
