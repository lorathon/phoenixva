<p>
You have changed your email address for <?php echo $site_name; ?>.<br />
Follow this link to confirm your new email address:<br />
<b><a href="<?php echo site_url('/auth/reset_email/'.$user_id.'/'.$new_email_key); ?>" style="color: #3366cc;">Confirm your new email</a></b>
</p>
<p>
Link doesn't work? Copy the following link to your browser address bar:<br />
<nobr><a href="<?php echo site_url('/auth/reset_email/'.$user_id.'/'.$new_email_key); ?>" style="color: #3366cc;"><?php echo site_url('/auth/reset_email/'.$user_id.'/'.$new_email_key); ?></a></nobr>
</p>
<p>
Your email address: <?php echo $new_email; ?>
</p>
<p>
You received this email because it was requested by a 
<a href="<?php echo site_url(''); ?>" style="color: #3366cc;"><?php echo $site_name; ?></a> 
user. If you have received this by mistake, please DO NOT click the confirmation 
link, and simply delete this email. After a short time, the request will be 
removed from the system.
</p>
<p>
Thank you,<br />
The <?php echo $site_name; ?> Team
</p>