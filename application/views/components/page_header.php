<!-- START: components/page_header.php -->

<!DOCTYPE html>
<html>
  <head>
	<title><?php echo $meta_title?></title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
		
	<!-- Twitter Bootstrap -->
	<link href="<?php echo base_url('css/bootstrap.min.css');?>" rel="stylesheet" media="screen">
	
	<!-- jQuery -->
	<script type="text/javascript" src="<?php echo base_url('js/jquery-1.9.1.min.js');?>"></script>
	
	<!-- Datepicker -->
	<link href="<?php echo base_url('css/datepicker.css');?>" rel="stylesheet" media="screen">
	<script type="text/javascript" src="<?php echo base_url('js/bootstrap-datepicker.js');?>"></script>     
	
	<!-- TinyMCE -->
	<script type="text/javascript" src="<?php echo base_url('js/tinymce/tinymce.min.js');?>"></script>
	<script type="text/javascript">
		tinymce.init({
				selector: "textarea",
				plugins: [
						"advlist autolink link image lists charmap print preview hr anchor pagebreak spellchecker",
						"searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking",
						"table contextmenu directionality emoticons template textcolor paste fullpage textcolor"
				],
		
				toolbar1: "newdocument fullpage | bold italic underline strikethrough | alignleft aligncenter alignright alignjustify | styleselect formatselect fontselect fontsizeselect",
				toolbar2: "cut copy paste | searchreplace | bullist numlist | outdent indent blockquote | undo redo | link unlink anchor image media code | inserttime preview | forecolor backcolor",
				toolbar3: "table | hr removeformat | subscript superscript | charmap emoticons | print fullscreen | ltr rtl | spellchecker | visualchars visualblocks nonbreaking template pagebreak restoredraft",
		
				menubar: false,
				toolbar_items_size: 'small',
		
				style_formats: [
						{title: 'Bold text', inline: 'b'},
						{title: 'Red text', inline: 'span', styles: {color: '#ff0000'}},
						{title: 'Red header', block: 'h1', styles: {color: '#ff0000'}},
						{title: 'Example 1', inline: 'span', classes: 'example1'},
						{title: 'Example 2', inline: 'span', classes: 'example2'},
						{title: 'Table styles'},
						{title: 'Table row 1', selector: 'tr', classes: 'tablerow1'}
				],
		
				templates: [
						{title: 'Test template 1', content: 'Test 1'},
						{title: 'Test template 2', content: 'Test 2'}
				]
		});
	</script>
  </head>
<!-- END: components/page_header.php -->
