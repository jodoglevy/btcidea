<?php
class UserData extends CI_Model {
	function __construct() {
        parent::__construct();
        $this->load->library('session');
    }
    
    function confirmEmail($email, $token) {
		$this->load->database();
		
		$email = strtolower($email);
		
		if(strlen($email) < 1) return "Please enter an email address";
		elseif(!isValidEmailAddress($email)) return "Please enter a valid email address";
		else {
            $results = $this->db->query("SELECT * FROM tbl_users WHERE"
                . " EmailAddressHash = " . $this->db->escape(hash("sha256", $email))
                . " AND Token = " . $this->db->escape($token)
            );
            
            if($results->num_rows() === 0) return "The email address or token is invalid";
            else {
                $newToken = md5(uniqid(rand(), true));
            
                $results = $this->db->query("UPDATE tbl_users SET"
                    . " IsConfirmed=" . $this->db->escape(1)
                    . ", Token=" . $this->db->escape($newToken)
                    . " WHERE EmailAddressHash = " . $this->db->escape(hash("sha256", $email)) . " AND Token = " . $this->db->escape($token)
                );
            
                return NULL;
            }
        }
    }
    
    function resetToken($email) {
        $this->load->database();
        
        $token = md5(uniqid(rand(), true));
        
        $results = $this->db->query("UPDATE tbl_users SET"
            . " Token=" . $this->db->escape($token)
            . ", TokenCreated = NOW()"
            . " WHERE EmailAddressHash = " . $this->db->escape(hash("sha256", $email))
        );
        
        if($this->db->affected_rows() == 0) return NULL;
        else return $token;
    }
	
	function changePassword($email, $token, $passwordPlainText, $passwordPlainTextConfirm) {
		$this->load->database();
		
		$email = strtolower($email);
		
		if(strlen($email) < 1) return "Please enter an email address";
		elseif(!isValidEmailAddress($email)) return "Please enter a valid email address";
		elseif(strlen($passwordPlainText) < 1) return "Please enter a password";
		elseif($passwordPlainText != $passwordPlainTextConfirm) return "Your password and your confirm password are not the same";
		else {
            $results = $this->db->query("SELECT * FROM tbl_users WHERE"
                . " EmailAddressHash = " . $this->db->escape(hash("sha256", $email))
                . " AND Token = " . $this->db->escape($token)
            );
            
            if($results->num_rows() === 0) return "The email address or token is invalid";
            else {
                $data = $results->row_array(0);
                
                if(strtotime('- 3 days') > strtotime($data["TokenCreated"])) {
                    return "This password recovery request has expired";
                }
                else {
                    $newToken = md5(uniqid(rand(), true));
                    $passwordHash = $this->getBcryptHash($passwordPlainText);
                    
                    $results = $this->db->query("UPDATE tbl_users SET"
                        . " Password=" . $this->db->escape($passwordHash)
                        . ", Token=" . $this->db->escape($newToken)
                        . ", TokenCreated = NOW()"
                        . " WHERE EmailAddressHash = " . $this->db->escape(hash("sha256", $email)) . " AND Token = " . $this->db->escape($token)
                    );
                    
                    return NULL;
                }
            }
        }
    }
	
	function sendForgotPasswordRequest($fypURL, $email) {
		$this->load->model('awsses');
        $this->load->database();
		
		$email = strtolower($email);
		
		if(strlen($email) < 1) return "Please enter an email address";
		elseif(!isValidEmailAddress($email)) return "Please enter a valid email address";
		else {
            $results = $this->db->query("SELECT * FROM tbl_users WHERE EmailAddressHash = " . $this->db->escape(hash("sha256", $email)));
            
            if($results->num_rows() === 0) return NULL; // Don't show error if user does not exist. Otherwise we are exposing users using this site
            $data = $results->row_array(0);
            
            if(!$data["IsConfirmed"]) return "You must confirm this email address before you can reset your password.";
            else {
                $newToken = $this->resetToken($email);

                $to = $email;
                $subject = "BtcIdea: Forgot Your Password";
                $message = "To change your password, please go to " . $fypURL . "?email=" . $email . "&token=" . $newToken;
                
                $this->awsses->sendEmail($to, $subject, $message);
                
                return NULL;
            }
        }
	}
	
	function add($email, $passwordPlainText, $passwordPlainTextConfirm, $confirmEmailURL = NULL) {
        $this->load->database();
		
		$email = strtolower($email);
		
		if(strlen($email) < 1) return "Please enter an email address";
		elseif(!isValidEmailAddress($email)) return "Please enter a valid email address";
		elseif(strlen($passwordPlainText) < 1) return "Please enter a password";
		elseif($passwordPlainText != $passwordPlainTextConfirm) return "Your password and your confirm password are not the same";
		else {
            $emailHash = hash("sha256", $email);

            $results = $this->db->query("SELECT * FROM tbl_users WHERE EmailAddressHash = " . $this->db->escape($emailHash));
            if($results->num_rows() !== 0) return "There is already an account with this email address";
            
            $token = md5(uniqid(rand(), true));
            $passwordHash = $this->getBcryptHash($passwordPlainText);
            
            $this->db->query("INSERT INTO tbl_users (EmailAddress, EmailAddressHash, Password, Token, TokenCreated, Created) VALUES ("
                .$this->db->escape($this->encrypt->encode($email))
                .",".$this->db->escape($emailHash)
                .",".$this->db->escape($passwordHash)
                .",".$this->db->escape($token)
                .",NOW()"
                .",NOW())"
            );
            
            if($confirmEmailURL) {
               $this->sendConfirmationEmail($email, $token, $confirmEmailURL);
            }
            
            return NULL;
        }
	}
    
    function sendConfirmationEmail($email, $token, $confirmEmailURL) {
        $this->load->model('awsses');
        $this->load->database();
        
        $email = strtolower($email);

        $results = $this->db->query("SELECT * FROM tbl_users WHERE"
            . " EmailAddressHash = " . $this->db->escape(hash("sha256", $email))
            . " AND Token = " . $this->db->escape($token)
            . " AND IsConfirmed = 0"
        );

        if($results->num_rows() !== 0) {
            $to = $email;
            $subject = "BtcIdea: Confirm Your Email Address";
            $message = "To confirm your email address, please go to " . $confirmEmailURL . "?email=" . $email . "&token=" . $token;
        
            $this->awsses->sendEmail($to, $subject, $message);
        }
    }
	
	function login($email, $passwordPlainText) {
		$this->load->database();
		
		$email = strtolower($email);
		
		if(strlen($email) < 1) return "Please enter an email address";
		if(!isValidEmailAddress($email)) return "Please enter a valid email address";
		if(strlen($passwordPlainText) < 1) return "Please enter a password";
		else {
            $results = $this->db->query("SELECT * FROM tbl_users WHERE EmailAddressHash = " . $this->db->escape(hash("sha256", $email)));
            if($results->num_rows() === 0) return "Login credentials are invalid";
            
            $data = $results->row_array(0);
            
            if(!$this->verifyPasswordAgainstBcryptHash($passwordPlainText, $data['Password'])) return "Login credentials are invalid";
            elseif(!$data["IsConfirmed"]) return "You must confirm this email address to log in.";
            else {
                $sessionData = array(
                    'userID' => $data['Key'],
                    'email' => $email,
                    'loggedIn' => TRUE
                );
                
                $this->session->set_userdata($sessionData);
                return NULL;
            }
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
    
    function getBcryptHash($password) {
        $options = array('cost' => 11);
        return password_hash($password, PASSWORD_BCRYPT, $options);
    }

    function verifyPasswordAgainstBcryptHash($password, $hash) {
        return password_verify($password, $hash);
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
