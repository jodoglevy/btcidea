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
<?php echo $footer; ?>