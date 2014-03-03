<?php echo $header; ?>
	<div class="container">	
		<form action="" method="POST" class="well form-vertical">
			<fieldset>
			  <legend>Reset Your Password</legend>
			  <br />
			  <?php if(isset($error)) echo '<div class="alert alert-error">' . $error . '</div>'; ?>
			  <div class="control-group">
				<label class="control-label" for="email">Your Email Address</label>
				<div class="controls">
				  <input type="text" class="input" id="email" name="email" value="<?php echo ($email ? $email : ''); ?>" />
				</div>
			  </div>
			  <div class="control-group">
				<label class="control-label" for="password">Your New Password</label>
				<div class="controls">
				  <input type="password" class="input" id="password" name="password" value="" />
				</div>
			  </div>
			  <div class="control-group">
				<label class="control-label" for="password2">Please confirm your new password</label>
				<div class="controls">
				  <input type="password" class="input" id="password2" name="password2" value="" />
				</div>
			  </div>
			  <div class="form-actions">
				<input type="hidden" name="token" value="<?php echo ($token ? $token : ''); ?>" />
				<button type="submit" class="btn btn-primary">Submit</button>
			  </div>
			</fieldset>
		</form>
	</div>
<?php echo $footer; ?>