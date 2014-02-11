<?php
class SiteData extends CI_Model {
	function header() {
		$this->load->model('userdata');
		$username = $this->userdata->getLoggedInUserEmail();
		
		if($username) {
			$userHTML = '<a class="btn" href="/account"><i class="icon-user"></i> ' . $username . '</a>';
		}
		else {
			$userHTML = '<a class="btn" href="/site/login">Login</a>';
			$userHTML .= '<a class="btn" href="/site/register">Sign Up</a>';
		}
		
		$header = '<body style="background-color: rgb(177, 219, 233)">
		<div id="main">
			<div class="navbar navbar-fixed-top">
			  <div class="navbar-inner">
				<div class="container-fluid">
				  <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				  </a>
				  <a class="brand" style="color: ivory"; href="/"><img style="vertical-align:top; padding-right:7px; padding-top: 2px;" src="/img/logo-black-24x18.png"/>Btc Idea</a>
				  <div class="btn-group pull-right">' . $userHTML . '</div>
				  <div class="nav-collapse">
					<ul class="nav">
					  <li><a href="/">Home</a></li>
					  <li><a href="/site/about">About</a></li>
					  <li><a href="/site/pricing">Pricing</a></li>
					  <li><a href="/site/contact">Contact</a></li>
					  <li style="padding-top: 9px; padding-left: 300px;"><!-- Facebook like button here --></li>
					  <li style="padding-top: 9px;"><!-- Twitter button goes here --></li>
					</ul>
				  </div><!--/.nav-collapse -->
				</div>
			  </div>
			</div>
		</div>
		<br /><br />';
			
		return $header;
	}
	
	function footer() {
		$footer = "
		<script type=\"text/javascript\">
			!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=\"//platform.twitter.com/widgets.js\";fjs.parentNode.insertBefore(js,fjs);}}(document,\"script\",\"twitter-wjs\");
		</script>
		
		<script type=\"text/javascript\">
		  var _gaq = _gaq || [];
		  _gaq.push(['_setAccount', '']);
		  _gaq.push(['_trackPageview']);

		  (function() {
			var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
			ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
			var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
		  })();

		</script>
		
		<script type='text/javascript'>
		  var uvOptions = {};
		  (function() {
			var uv = document.createElement('script'); uv.type = 'text/javascript'; uv.async = true;
			uv.src = ('https:' == document.location.protocol ? 'https://' : 'http://') + 'widget.uservoice.com/nSxSqX48Z6bC5GLOkCNg.js';
			var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(uv, s);
		  })();
		</script>
		
		</body>";
		return $footer;
	}
}
?>
