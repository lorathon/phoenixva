<?php $this->load->view('components/page_header')?>

<!-- START: _layout_main.php -->

  <body>
<div class="container-fluid">
	<div class="row-fluid">
		<div class="span12">
            <!--
			 <a id="modal-803941" href="#modal-container-803941" role="button" class="btn" data-toggle="modal">Launch demo modal</a>
			
			<div id="modal-container-803941" class="modal hide fade" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
				<div class="modal-header">
					 <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
					<h3 id="myModalLabel">
						Modal header
					</h3>
				</div>
				<div class="modal-body">
					<p>
						One fine body…
					</p>
				</div>
				<div class="modal-footer">
					 <button class="btn" data-dismiss="modal" aria-hidden="true">Close</button> <button class="btn btn-primary">Save changes</button>
				</div>
			</div>-->
			<?php $this->load->view('components/page_navigation_bar')?>
			<div class="hero-unit">
				<h1>
					Hello, world!
				</h1>
				<p>
					This is a template for a simple marketing or informational website. It includes a large callout called the hero unit and three supporting pieces of content. Use it as a starting point to create something more unique.
				</p>
				<p>
					<a class="btn btn-primary btn-large" href="#">Learn more »</a>
				</p>
			</div>
			
			<!-- Alert Area -->
			<?php if($this->session->flashdata('success') != ''): ?>
			<div class="alert alert-success alert-dismissable"">
				 <button type="button" class="close" data-dismiss="alert">×</button>
				 <strong>Success!</strong> - <?php echo $this->session->flashdata('success')?>
			</div>
			<?php endif; ?>
			
			<?php if($this->session->flashdata('info') != ''): ?>
			<div class="alert alert-info alert-dismissable"">
				 <button type="button" class="close" data-dismiss="alert">×</button>
				 <strong>Information!</strong> - <?php echo $this->session->flashdata('info')?>
			</div>
			<?php endif; ?>
			
			<?php if($this->session->flashdata('warning') != ''): ?>
			<div class="alert alert-warning alert-dismissable"">
				 <button type="button" class="close" data-dismiss="alert">×</button>
				 <strong>Warning!</strong> - <?php echo $this->session->flashdata('warning')?>
			</div>
			<?php endif; ?>
			
			<?php if($this->session->flashdata('error') != ''): ?>
			<div class="alert alert-error alert-dismissable"">
				 <button type="button" class="close" data-dismiss="alert">×</button>
				 <strong>Error!</strong> - <?php echo $this->session->flashdata('error')?>
			</div>
			<?php endif; ?>	
			<!-- End Alert -->
			
		</div>
	</div>
    
    <!-- Breadcrumb -->
	<div class="row-fluid">
		<div class="span12">
			<ul class="breadcrumb"><?php echo set_breadcrumb(); ?></ul>
		</div>
	</div>
    
    <!-- Nav list -->
	<div class="row-fluid">
			<?php $this->load->view('components/page_navigation_list')?>
		<div class="span8">
			<?php $this->load->view($midview); ?>
		</div>
		<div class="span2">
			<?php $this->load->view($rightview); ?>
		</div>
	</div>
	<div class="row-fluid">
		<div class="span4">
			<div class="tabbable" id="tabs-449338">
				<ul class="nav nav-tabs">
					<li class="active">
						<a href="#panel-508708" data-toggle="tab">Section 1</a>
					</li>
					<li>
						<a href="#panel-985219" data-toggle="tab">Section 2</a>
					</li>
				</ul>
				<div class="tab-content">
					<div class="tab-pane active" id="panel-508708">
						<p>
							I'm in Section 1.
						</p>
					</div>
					<div class="tab-pane" id="panel-985219">
						<p>
							Howdy, I'm in Section 2.
						</p>
					</div>
				</div>
			</div>
		</div>
		<div class="span4">
			<h2>
				Heading
			</h2>
			<p>
				Donec id elit non mi porta gravida at eget metus. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus. Etiam porta sem malesuada magna mollis euismod. Donec sed odio dui.
			</p>
			<p>
				<a class="btn" href="#">View details »</a>
			</p>
		</div>
		<div class="span4">
			<h2>
				Heading
			</h2>
			<p>
				Donec id elit non mi porta gravida at eget metus. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus. Etiam porta sem malesuada magna mollis euismod. Donec sed odio dui.
			</p>
			<p>
				<a class="btn" href="#">View details »</a>
			</p>
		</div>
	</div>
</div>

<!-- END: _layout_main.php -->
        
<?php $this->load->view('components/page_footer')?>
 