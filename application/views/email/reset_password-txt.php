Hi<?php if (strlen($username) > 0) { ?> <?php echo $username; ?><?php } ?>,

Your password has been changed. If you did not change your password or request
that your password be changed, please contact us immediately.
<?php if (strlen($username) > 0) { ?>

Your username: <?php echo $username; ?>
<?php } ?>

Your email address: <?php echo $email; ?>

<?php if ($admin): ?>
Your new password: <?php echo $new_password; ?>
Please log in and change this password right away.
<?php endif; ?>

Thank you,
The <?php echo $site_name; ?> Team