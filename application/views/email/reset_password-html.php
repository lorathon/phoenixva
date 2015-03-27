<p>
Your password has been changed. If you did not change your password or request
that your password be changed, please contact us immediately.
</p>
<p>
<?php if (strlen($username) > 0): ?>
Your username: <?php echo $username; ?><br />
<?php endif; ?>
Your email address: <?php echo $email; ?><br />
<?php if ($admin): ?>
Your new password: <?php echo $new_password; ?><br />
<strong>Please log in and change this password right away.</strong>
<?php endif; ?>
</p>
<p>
Thank you,<br />
The <?php echo $site_name; ?> Team
</p>