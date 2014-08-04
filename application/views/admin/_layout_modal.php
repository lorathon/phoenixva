<?php $this->load->view('admin/components/page_header')?>
<!-- admin/_layout_modal.php -->
<body style="background: #555">
    <div class="modal show" role="dialog">
        
        <?php $this->load->view($subview);  //Subview is set in controller ?>
        <div class="modal-footer">
            &copy: <?php echo $meta_title?>
        </div> 
    </div>    

<?php $this->load->view('admin/components/page_footer')?>