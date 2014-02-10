<?php

class AwsSes extends CI_Model {

	function sendEmail($to, $subject, $message) {
        $this->load->library('email');
        
        $smtpCreds = json_decode(getenv("CUSTOMCONNSTR_AwsSesSmtp"), TRUE);
        
        $config = array(
            'protocol' => 'smtp',
            'smtp_host' => 'ssl://email-smtp.us-east-1.amazonaws.com',
            'smtp_user' => $smtpCreds["SMTP_Username"],
            'smtp_pass' => $smtpCreds["SMTP_Password"],
            'smtp_port' => 465,
            'mailtype' => 'html',
            'newline' => "\r\n"
        );

        $this->email->initialize($config);
        
        $this->email->from('jodoglevy@gmail.com', 'Joe Levy');
        $this->email->to($to);
        $this->email->subject($subject);
        $this->email->message($message);

        $this->email->send();
        
        // for troubleshooting
        // echo $this->email->print_debugger();
    }
}
?>
