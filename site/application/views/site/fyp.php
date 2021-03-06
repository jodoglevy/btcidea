<?php echo $header; ?>
	<div class="container">	
		<form action="" method="POST" class="well form-vertical">
			<fieldset>
			  <legend>Forgot Your Password</legend>
			  <span class="control-label">Enter your email address and we'll send you a password reset request.</span>
			  <br /><br />
			  <?php if(isset($error)) echo '<div class="alert alert-error">' . $error . '</div>'; ?>
			  <div class="control-group">
				<label class="control-label" for="email">Email Address</label>
				<div class="controls">
				  <input type="text" class="input" id="email" name="email" value="<?php echo ($email ? $email : ''); ?>" />
				</div>
			  </div>
			  <div class="form-actions">
				<button type="submit" class="btn btn-primary">Submit</button>
			  </div>
			</fieldset>
		</form>
	</div>
<?php echo $footer; ?>