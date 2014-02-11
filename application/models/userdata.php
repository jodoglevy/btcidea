<?php
class UserData extends CI_Model {
	function __construct() {
        parent::__construct();
        $this->load->library('session');
    }
	
	function changePassword($email, $token, $passwordPlainText, $passwordPlainTextConfirm) {
		$this->load->database();
		
		$email = strtolower($email);
		
		if(strlen($email) < 1) return "Please enter an email address";
		elseif(!isValidEmailAddress($email)) return "Please enter a valid email address";
		elseif(strlen($passwordPlainText) < 1) return "Please enter a password";
		elseif($passwordPlainText != $passwordPlainTextConfirm) return "Your password and your confirm password are not the same";
		else {
            $salt = md5(uniqid(rand(), true));
            $newToken = md5(uniqid(rand(), true));
            $passwordHash = hash("sha256", $passwordPlainText . $salt);
            
            $results = $this->db->query("UPDATE tbl_users SET"
                . " Password=" . $this->db->escape($passwordHash)
                . ", Salt=" . $this->db->escape($salt)
                . ", Token=" . $this->db->escape($newToken)
                . " WHERE EmailAddress = " . $this->db->escape($email) . " AND Token = " . $this->db->escape($token)
            );
            
            return NULL;
        }
    }
	
	function sendForgotPasswordRequest($fypURL, $email) {
		$this->load->model('awsses');
        $this->load->database();
		
		$email = strtolower($email);
		
		if(strlen($email) < 1) return "Please enter an email address";
		elseif(!isValidEmailAddress($email)) return "Please enter a valid email address";
		else {
            $results = $this->db->query("SELECT * FROM tbl_users WHERE EmailAddress = " . $this->db->escape($email));
            
            if($results->num_rows() === 0) return NULL; // Don't show error if user does not exist. Otherwise we are exposing users using this site
            $data = $results->row_array(0);
            
            $to = $email;
            $subject = "BtcIdea: Forgot Your Password";
            $message = "To change your password, please go to " . $fypURL . "?email=" . $email . "&token=" . $data['Token'] ;
            
            $this->awsses->sendEmail($to, $subject, $message);
            
            return NULL;
        }
	}
	
	function add($email, $passwordPlainText, $passwordPlainTextConfirm) {
		$this->load->database();
		
		$email = strtolower($email);
		
		if(strlen($email) < 1) return "Please enter an email address";
		if(!isValidEmailAddress($email)) return "Please enter a valid email address";
		if(strlen($passwordPlainText) < 1) return "Please enter a password";
		if($passwordPlainText != $passwordPlainTextConfirm) return "Your password and your confirm password are not the same";
		else {
            $results = $this->db->query("SELECT * FROM tbl_users WHERE EmailAddress = " . $this->db->escape($email));
            if($results->num_rows() !== 0) return "There is already an account with this email address";
            
            $salt = md5(uniqid(rand(), true));
            $token = md5(uniqid(rand(), true));
            $passwordHash = hash("sha256", $passwordPlainText . $salt);
            
            $this->db->query("INSERT INTO tbl_users (EmailAddress, Password, Salt, Token, Created) VALUES ("
                .$this->db->escape($email)
                .",".$this->db->escape($passwordHash)
                .",".$this->db->escape($salt)
                .",".$this->db->escape($token)
                .",NOW())"
            );
            
            return NULL;
        }
	}
	
	function login($email, $passwordPlainText) {
		$this->load->database();
		
		$email = strtolower($email);
		
		if(strlen($email) < 1) return "Please enter an email address";
		if(!isValidEmailAddress($email)) return "Please enter a valid email address";
		if(strlen($passwordPlainText) < 1) return "Please enter a password";
		else {
            $results = $this->db->query("SELECT * FROM tbl_users WHERE EmailAddress = " . $this->db->escape($email));
            if($results->num_rows() === 0) return "Login credentials are invalid";
            
            $data = $results->row_array(0);
            $passwordHash = hash("sha256", $passwordPlainText . $data['Salt']);
            
            if($passwordHash != $data['Password']) return "Login credentials are invalid";
            
            $sessionData = array(
                'userID' => $data['Key'],
                'email' => $email,
                'loggedIn' => TRUE
            );
            
            $this->session->set_userdata($sessionData);
            return NULL;
        }
	}
	
	function isLoggedIn() {
		$logged = $this->session->userdata('loggedIn');
        
		if ($logged) return TRUE;
		else return FALSE;
	}
	
	function logout() {
		$this->session->sess_destroy();
	}
	
	function getLoggedInUserEmail() {
		return $this->session->userdata('email');
	}
	
	function getLoggedInUserID() {
		return $this->session->userdata('userID');
	}	
}

/**
 * @param $email - the value to check
 * @return true iff the value is in valid email address format.
 */
function isValidEmailAddress($email) {
	return filter_var($email, FILTER_VALIDATE_EMAIL) && preg_match('/@.+\./', $email);
}

?>
