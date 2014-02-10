<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Site extends CI_Controller
{
	public function index()
	{
        $this->load->model('sitedata');
		$this->load->view('site/homepage', array(
			'header' => $this->sitedata->header(),
			'footer' => $this->sitedata->footer()
		));
    }
	
	public function contact()
	{
        $this->load->model('sitedata');
		$this->load->view('site/contact', array(
			'header' => $this->sitedata->header(),
			'footer' => $this->sitedata->footer()
		));
    }
	
	public function terms()
	{
        $this->load->model('sitedata');
		$this->load->view('site/terms', array(
			'header' => $this->sitedata->header(),
			'footer' => $this->sitedata->footer()
		));
    }
	
	public function privacy()
	{
        $this->load->model('sitedata');
		$this->load->view('site/privacy', array(
			'header' => $this->sitedata->header(),
			'footer' => $this->sitedata->footer()
		));
    }
	
	public function about()
	{
        $this->load->model('sitedata');
		$this->load->view('site/about', array(
			'header' => $this->sitedata->header(),
			'footer' => $this->sitedata->footer()
		));
    }
	
	public function login() {
		$this->load->model('sitedata');
		$this->load->model('userdata');
		$this->load->helper('url');
		
		if($this->userdata->isLoggedIn()) return redirect('/campaign');
		
		$after = $this->input->get_post('after', TRUE);
		$email = $this->input->post('email', TRUE);
		$error = NULL;
		
		if($this->input->post('email')) {
			$error = $this->userdata->login(
				$this->input->post('email', TRUE),
				$this->input->post('password')
			);
			
			if(!$error)	return redirect($after);
			else if($error == "That password is incorrect") {
				$error .= ".<br />Forgot your password? <a href='/site/fyp?email=" . $email . "'>Generate a new one.</a>";
			}
		}
		
		$this->load->view('site/login', array(
			'email' => $email,
			'error' => $error,
			'after' => $after,
			'header' => $this->sitedata->header(),
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
		
		if($this->userdata->isLoggedIn()) return redirect('/');
		
		$error = NULL;
		$email = $this->input->post('email', TRUE);
		
		if($email) {
			$error = $this->userdata->add(
				$email,
				$this->input->post('password'),
				$this->input->post('password2')
			);
			
			if(!$error) {
				$this->userdata->login(
					$email,
					$this->input->post('password')
				);
				
				return redirect('/');
			}
		}
		
		$this->load->view('site/register', array(
			'email' => $email,
			'error' => $error,
			'header' => $this->sitedata->header(),
			'footer' => $this->sitedata->footer()
		));
	}
	
	public function passwordrecover() {
		$this->load->model('sitedata');
		$this->load->model('userdata');
		$this->load->helper('url');
				
		if($this->userdata->isLoggedIn()) return redirect('/campaign');
		
		$error = NULL;
		$email = $this->input->post('email', TRUE);
		$token = $this->input->post('token', TRUE);
		
		if($email) {
			$error = $this->userdata->changePassword(
				$email,
				$token,
				$this->input->post('password'),
				$this->input->post('password2')
			);
			
			if(!$error) {
				$this->userdata->login(
					$email,
					$this->input->post('password')
				);
				
				return redirect('/campaign');
			}
		}
		
		$email = $this->input->get_post('email', TRUE);
		$token = $this->input->get_post('token', TRUE);
		
		$this->load->view('site/passwordrecover', array(
			'token' => $token,
			'email' => $email,
			'error' => $error,
			'header' => $this->sitedata->header(),
			'footer' => $this->sitedata->footer()
		));
	}
	
	public function fyp() {
		$this->load->model('sitedata');
		$this->load->model('userdata');
		$this->load->helper('url');
		
		if($this->userdata->isLoggedIn()) return redirect('/campaign');
		
		$error = NULL;
		$email = $this->input->post('email', TRUE);
		
		if($email) {
			$error = $this->userdata->sendForgotPasswordRequest(
				"noreply@dialasmile.me",
				"www.dialasmile.me/site/passwordrecover",
				$email
			);
			
			if(!$error) {
				return $this->load->view('site/fypsent', array(
					'email' => $email,
					'header' => $this->sitedata->header(),
					'footer' => $this->sitedata->footer()
				));
			}
		}
		
		$email = $this->input->get_post('email', TRUE);
		
		$this->load->view('site/fyp', array(
			'email' => $email,
			'error' => $error,
			'header' => $this->sitedata->header(),
			'footer' => $this->sitedata->footer()
		));
	}
}
?>
