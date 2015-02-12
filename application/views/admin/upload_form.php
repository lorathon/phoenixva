<?php
$form_attributes = array(
    'class' => 'form-horizontal',
    'role'  => 'form',
);

$label_attributes = array(
    'class' => 'col-sm-1 control-label',
); 

$field_class = 'form-control';

$file_name = array(
    'name'	    => 'file_name',
    'id'	    => 'file_name',
    'value'	    => set_value('file_name'),
    //'maxlength'     => 100,
    'size'	    => 30,
    'class'         => $field_class,
);

$upload_path = array(
    'name'  => 'upload_path',
    'id'    => 'upload_path',
    'value' => set_value('upload_path'),
    'class' => $field_class,
);
?>

<div class="container">
    
    <?php if ($errors):?>
        <div class="alert alert-danger">            
            <p><?php echo $errors; ?></p>
	</div>
    <?php endif; ?> 
    
    <?php echo form_open_multipart('admin/upload/do_upload', $form_attributes); ?>
    
        <div class="form-group">
            <?php echo form_label('File Name', $file_name['id'], $label_attributes); ?>
            <div class="col-sm-2"><?php echo form_input($file_name); ?></div>
	</div>
    
        <div class="form-group">
            <?php echo form_label('Upload Path', $upload_path['id'], $label_attributes); ?>
            <div class="col-sm-2">
		<?php echo form_dropdown('upload_path',$paths,set_value('upload_path'),"class='{$field_class}'"); ?>
            </div>
	</div>
    
        <div class="form-group">	
            <div class="col-sm-2"><input type="file" name="userfile" size="60" /></div>        
        </div>
    
        <div class="form-group">
            <div class="col-sm-3">
                <?php echo form_submit('upload', 'Submit', 'class = "btn btn-primary btn-block"'); ?>
            </div>
        </div>

    <?php echo form_close(); ?>
</div>

