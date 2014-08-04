<!-- START: admin/components/page_header.php -->
<!DOCTYPE html>
<html>
  <head>
    <title><?php echo $meta_title?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
        
    <!-- Twitter Bootstrap -->
    <link href="<?php echo base_url('css/bootstrap.min.css');?>" rel="stylesheet" media="screen">
    
    <!-- jQuery -->
    <script type="text/javascript" src="<?php echo base_url('assets/grocery_crud/js/jquery-1.10.2.min.js');?>"></script>
        
    <!-- jQuery_UI -->
    <link type="text/css" rel="stylesheet" href="<?php echo base_url('assets/grocery_crud/css/ui/simple/jquery-ui-1.10.1.custom.min.css');?>" />
    <script src="<?php echo base_url('assets/grocery_crud/js/jquery_plugins/ui/jquery-ui-1.10.3.custom.min.js');?>"></script>
    
    <!-- Grocery CRUD -->
    <?php if(isset($css_files)) : ?>
      <?php foreach($css_files as $file): ?>
      <link type="text/css" rel="stylesheet" href="<?php echo $file; ?>" />
      <?php endforeach; ?>
    <?php endif; ?>
    <?php if(isset($js_files)) : ?>
      <?php foreach($js_files as $file): ?>
      <script src="<?php echo $file; ?>"></script>
      <?php endforeach; ?>
    <?php endif; ?>
    
  </head>
<!-- START: admin/components/page_header.php -->
