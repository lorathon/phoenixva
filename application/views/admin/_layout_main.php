<?php $this->load->view('admin/components/page_header')?>
<!-- START: admin/_layout_main.php -->
  <body>
    <?php $this->load->view('admin/components/page_navigation_bar')?>
    
    <div class="container-fluid">
        <div class="row-fluid">
            <!-- Main Column -->
            <div class="span1"></div>
            <div class="span10"><?php echo set_breadcrumb(); ?></div>
            <div class="span1"></div>
        </div>
        <?php if($alert == TRUE)
        { ?>
        <div class="row-fluid">
          <div class="span1"></div>
          <div class="alert alert-<?php echo $alert_type?> span9">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <strong><?php echo ucfirst($alert_type)?>!</strong>  <?php echo $alert_message ?>
          </div>
          <div class="span1"></div>
        </div>
        <?php } ?>
        </div>
        <div class="row-fluid">
          <div class="span1"></div>
            <div class="span9">                
                <?php $this->load->view($subview);  //Subview is set in controller ?>
            </div>
            <!-- Sidebar -->
            <div class="span1">
                <section>
                    <i class="icon-user"></i> <?php echo $userdata['name']; ?> <br>
                    <?php echo anchor('/auth/logout/', '<i class="icon-off"></i> Logout'); ?>
                </section>
                <hr></hr>
                    <?php if(isset($sidebar)) $this->load->view($sidebar); //Sidebar set in controller?>
            </div>
          <div class="span1"></div>
        </div>
    </div>    
<!-- END: admin/_layout_main.php -->
<?php $this->load->view('admin/components/page_footer')?>
 