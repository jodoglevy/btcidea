<!DOCTYPE html> 
<html lang="en"> 
<head> 
<meta charset="utf-8"> 
<title>Login | Btc Idea</title> 
<link href="/css/bootstrap.min.css" rel="stylesheet">
</head>
<?php echo $header; ?>
			<div class="container">	
				<form action="" method="POST" class="well form-vertical">
					<fieldset>
					  <legend>Log In</legend>
					  <span class="control-label">Don't have an account yet? <a href="/site/register">Sign up for free!</a></span>
					  <br /><br />
					  <?php if(isset($error)) echo '<div class="alert alert-error">' . $error . '</div>'; ?>
					  <div class="control-group">
						<label class="control-label" for="email">Email Address</label>
						<div class="controls">
						  <input type="text" class="input" id="email" name="email" value="<?php echo ($email ? $email : ''); ?>" />
						</div>
					  </div>
					  <div class="control-group">
						<label class="control-label" for="password">Password</label>
						<div class="controls">
						  <input type="password" class="input" id="password" name="password" value="" />
						</div>
					  </div>
					  <input type="hidden" class="input" name="after" value="<?php echo ($after ? $after : '/'); ?>" />
					  <div class="form-actions">
						<button type="submit" class="btn btn-primary">Log In</button>
					  </div>
					</fieldset>
				</form>
			</div>
	<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
<?php echo $footer; ?>
</html>
