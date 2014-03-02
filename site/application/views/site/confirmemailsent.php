<!DOCTYPE html> 
<html lang="en"> 
<head> 
<meta charset="utf-8"> 
<title>Sign Up | BtcIdea</title> 
<link href="/css/bootstrap.min.css" rel="stylesheet">
</head>
<?php echo $header; ?>
			<div class="container">	
				<form action="" method="POST" class="well form-vertical">
					<fieldset>
					  <legend>Create Your Free <strong>Btc Idea</strong> Account</legend>
					  <span class="control-label">Thanks for signing up!
                      <br /><br />
                      A confirmation email has been sent to <strong><?php echo ($email ? $email : ''); ?></strong>.
                      Please confirm your email address to start using Btc Idea.</span>
					</fieldset>
				</form>
			</div>
	<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
<?php echo $footer; ?>
</html>
