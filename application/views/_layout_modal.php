<?php $this->load->view('components/page_header')?>

<!-- START: _layout_modal.php -->

<body style="background: #555">
    <div class="modal show" role="dialog">
        
        <?php $this->load->view($subview);  //Subview is set in controller ?>
        <div class="modal-footer">
            &copy: <?php echo $meta_title?>
        </div> 
    </div>    

<!-- END: _layout_modal.php -->    

<?php $this->load->view('components/page_footer')?>