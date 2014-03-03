<?php echo $header; ?>
	<div class="container">	
		<form action="" method="POST" class="well form-vertical">
			<fieldset>
			  <legend>Create Your Free <strong>Btc Idea</strong> Account</legend>
			  <br />
			  <?php if(isset($error)) echo '<div class="alert alert-error">' . $error . '</div>'; ?>
			  <div class="control-group">
				<label class="control-label" for="email">Your Email Address</label>
				<div class="controls">
				  <input type="text" class="input" id="email" name="email" value="<?php echo ($email ? $email : ''); ?>" />
				</div>
			  </div>
			  <div class="control-group">
				<label class="control-label" for="password">Your Password</label>
				<div class="controls">
				  <input type="password" class="input" id="password" name="password" value="" />
				</div>
			  </div>
			  <div class="control-group">
				<label class="control-label" for="password2">Please confirm your password</label>
				<div class="controls">
				  <input type="password" class="input" id="password2" name="password2" value="" />
				</div>
			  </div>
			  <div class="form-actions">
				<button type="submit" class="btn btn-primary">Create Account</button>
			  </div>
			  <span>By clicking 'Create Account' you agree to the <a href="/site/terms">Terms of Use</a> and <a href="/site/privacy">Privacy Policy</a></span>
			</fieldset>
		</form>
	</div>
<?php echo $footer; ?>