<!DOCTYPE html> 
<html lang="en"> 
<head> 
<meta charset="utf-8"> 
<title>Forgot Your Password | BtcIdea</title> 
<link href="/css/bootstrap.min.css" rel="stylesheet">
</head>
<?php echo $header; ?>
			<div class="container">	
				<form action="" method="POST" class="well form-vertical">
					<fieldset>
					  <legend>Forgot Your Password</legend>
					  <span class="control-label">A password reset request has been sent to <strong><?php echo ($email ? $email : ''); ?></strong></span>
					</fieldset>
				</form>
			</div>
	<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
<?php echo $footer; ?>
</html>
