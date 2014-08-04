<section>
    <h4>Information</h4>
    <?php if(isset($new_users)) echo anchor('/admin/user/new_users/', '<i class="icon-bell"></i> '. count($new_users).' New Users'); ?>
</section>