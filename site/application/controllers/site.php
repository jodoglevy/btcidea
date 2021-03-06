<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Site extends CI_Controller
{
	public function index()
	{
        $this->load->model('sitedata');
        
		$this->load->view('site/homepage', array(
			'header' => $this->sitedata->header("Home"),
			'footer' => $this->sitedata->footer()
		));
    }
	
	public function contact()
	{
        $this->load->model('sitedata');
        
		$this->load->view('site/contact', array(
			'header' => $this->sitedata->header("Contact Us"),
			'footer' => $this->sitedata->footer()
		));
    }
	
	public function terms()
	{
        $this->load->model('sitedata');
        
		$this->load->view('site/terms', array(
			'header' => $this->sitedata->header("Terms of Use"),
			'footer' => $this->sitedata->footer()
		));
    }
	
	public function privacy()
	{
        $this->load->model('sitedata');
        
		$this->load->view('site/privacy', array(
			'header' => $this->sitedata->header("Privacy Policy"),
			'footer' => $this->sitedata->footer()
		));
    }
	
	public function about()
	{
        $this->load->model('sitedata');
        
		$this->load->view('site/about', array(
			'header' => $this->sitedata->header("About"),
			'footer' => $this->sitedata->footer()
		));
    }
	
	public function login() {
		$this->load->model('sitedata');
		$this->load->model('userdata');
        
		$this->load->helper('url');
		
		if($this->userdata->isLoggedIn()) return redirect('/account');
		
		$after = $this->input->get_post('after', TRUE);
		$email = $this->input->post('email', TRUE);
        $password = $this->input->post('password');
        
        if(!$after) $after = '/account';
        
		$error = NULL;
		
		if($email) {
            // login request submitted
			
            $error = $this->userdata->login(
				$email,
				$password
			);
			
			if(!$error)	return redirect($after);
			else if($error == "Login credentials are invalid") {
				$error .= ".<br />Forgot your password? <a href='/site/fyp?email=" . $email . "'>Generate a new one</a>";
			}
            else if($error == "You must confirm this email address to log in.") {
                $error .= "<br /><a href='/site/resendconfirmationemail?email=" . $email . "'>Resend confirmation email</a>";
            }
		}
		
        // initial page load or error logging in
        
		$this->load->view('site/login', array(
			'email' => $email,
			'error' => $error,
			'after' => $after,
			'header' => $this->sitedata->header("Log In"),
			'footer' => $this->sitedata->footer()
		));
	}
	
	public function logout() {
		$this->load->model('userdata');
        
		$this->load->helper('url');
		
		$this->userdata->logout();
        
		redirect('/');
	}
	
	public function register() {
		$this->load->model('sitedata');
		$this->load->model('userdata');
        
		$this->load->helper('url');
		
		if($this->userdata->isLoggedIn()) return redirect('/account');
		
		$email = $this->input->post('email', TRUE);
        $password = $this->input->post('password');
		$password2 = $this->input->post('password2');
		
        $pageTitle = "Sign Up";
        
        $error = NULL;
        
		if($email) {
            // register request submitted
        
			$error = $this->userdata->add(
				$email,
				$password,
				$password2,
                "https://" . getDomain() . "/site/confirmemail"
			);
			
			if(!$error) {
				return $this->load->view('site/confirmemailsent', array(
					'email' => $email,
					'header' => $this->sitedata->header($pageTitle),
					'footer' => $this->sitedata->footer()
				));
			}
		}
		
        // initial page load or error registering
        
		$this->load->view('site/register', array(
			'email' => $email,
			'error' => $error,
			'header' => $this->sitedata->header($pageTitle),
			'footer' => $this->sitedata->footer()
		));
	}
	
	public function passwordrecover() {
		$this->load->model('sitedata');
		$this->load->model('userdata');
        
		$this->load->helper('url');
				
		if($this->userdata->isLoggedIn()) return redirect('/account');
		
		$email = $this->input->post('email', TRUE);
		$token = $this->input->post('token', TRUE);
		$password = $this->input->post('password');
		$password2 = $this->input->post('password2');
        
        $error = NULL;
        
		if($email) {
			// password reset request submitted
            
            $error = $this->userdata->changePassword(
				$email,
				$token,
				$password,
				$password2
			);
			
			if(!$error) {
				$this->userdata->login(
					$email,
					$password
				);
				
				return redirect('/account');
			}
            else if($error == "This password recovery request has expired" || $error == "The email address or token is invalid") {
                $error .= ".<br /><a href='/site/fyp?email=" . $email . "'>Request a new password</a>";
            }
		}
        
        // initial page load or error resetting password
        
        $email = $this->input->get_post('email', TRUE);
        $token = $this->input->get_post('token', TRUE);
    
        $this->load->view('site/passwordrecover', array(
            'token' => $token,
            'email' => $email,
            'error' => $error,
            'header' => $this->sitedata->header("Reset Your Password"),
            'footer' => $this->sitedata->footer()
        ));
	}
	
	public function fyp() {
		$this->load->model('sitedata');
		$this->load->model('userdata');
		$this->load->helper('url');
		
		if($this->userdata->isLoggedIn()) return redirect('/account');
		
		$email = $this->input->post('email', TRUE);
		
        $pageTitle = "Forgot Your Password";
        
        $error = NULL;
        
		if($email) {
            // forgot your password request submitted
        
			$error = $this->userdata->sendForgotPasswordRequest(
				"https://" . getDomain() . "/site/passwordrecover",
				$email
			);
			
			if(!$error) {
				return $this->load->view('site/fypsent', array(
					'email' => $email,
					'header' => $this->sitedata->header($pageTitle),
					'footer' => $this->sitedata->footer()
				));
			}
            else if($error == "You must confirm this email address before you can reset your password.") {
                $error .= "<br /><a href='/site/resendconfirmationemail?email=" . $email . "'>Resend confirmation email</a>";
            }
		}
		
        // initial page load or error submitting forgot your password request
        
        $email = $this->input->get_post('email', TRUE);
            
        $this->load->view('site/fyp', array(
            'email' => $email,
            'error' => $error,
            'header' => $this->sitedata->header($pageTitle),
            'footer' => $this->sitedata->footer()
        ));
	}
    
    public function confirmEmail() {
        $this->load->model('userdata');
        $this->load->helper('url');
        
        $email = $this->input->get('email', TRUE);
        $token = $this->input->get('token', TRUE);
        
        $error = $this->userdata->confirmEmail($email, $token);

        return redirect('/site/login');
    }
    
    public function resendConfirmationEmail() {
        $this->load->model('sitedata');
		$this->load->model('userdata');
        
		$email = $this->input->get('email', TRUE);
        
		if($email) {        
			$token = $this->userdata->resetToken(
				$email
			);
            
            if($token) {
                $this->userdata->sendConfirmationEmail(
                    $email,
                    $token,
                    "https://" . getDomain() . "/site/confirmemail"
                );
            }
			
            return $this->load->view('site/confirmemailsent', array(
                'email' => $email,
                'header' => $this->sitedata->header("Sign Up"),
                'footer' => $this->sitedata->footer()
            ));
		}
    }
}

function getDomain()
{
    $CI =& get_instance();
    return preg_replace("/^[\w]{2,6}:\/\/([\w\d\.\-]+).*$/","$1", $CI->config->slash_item('base_url'));
}
?>
