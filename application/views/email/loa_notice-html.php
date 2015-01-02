<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head><title>You have been placed on LOA for <?php echo $site_name; ?></title></head>
<body>
<div style="max-width: 800px; margin: 0; padding: 30px 0;">
<table width="80%" border="0" cellpadding="0" cellspacing="0">
<tr>
<td width="5%"></td>
<td align="left" width="95%" style="font: 13px/18px Arial, Helvetica, sans-serif;">
<h2 style="font: normal 20px/23px Arial, Helvetica, sans-serif; 
           margin: 0; 
           padding: 0 0 18px; 
           color: black;">You have been placed on LOA for <?php echo $site_name; ?></h2>
You have been placed on a Leave of Absence (LOA) for <?php echo $site_name; ?>.<br />
<br />
To return to full duty, either log in to the 
<a href="<?php echo site_url(''); ?>" style="color: #3366cc;"><?php echo $site_name; ?></a>
website and follow the instructions to remove yourself from LOA, or file a PIREP.<br />
<br />
You received this email, because it was requested by a 
<a href="<?php echo site_url(''); ?>" style="color: #3366cc;"><?php echo $site_name; ?></a> 
user. If you have received this by mistake, please log in to the website and 
follow the instructions to remove yourself from LOA.<br />
<br />
<br />
Thank you,<br />
The <?php echo $site_name; ?> Team
</td>
</tr>
</table>
</div>
</body>
</html>