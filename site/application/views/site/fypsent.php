<?php echo $header; ?>
	<div class="container">	
		<form action="" method="POST" class="well form-vertical">
			<fieldset>
			  <legend>Forgot Your Password</legend>
			  <span class="control-label">A password reset request has been sent to <strong><?php echo ($email ? $email : ''); ?></strong>, if that user exists.</span>
			</fieldset>
		</form>
	</div>
<?php echo $footer; ?>