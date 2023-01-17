<?php

class SendingMail extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->library('session');
        $this->load->helper('form');
    }

    public function index() {
        $this->load->helper('form');
        $this->load->view('contact_email_form.php');
    }

    public function send_mail() {
        $from_email = "メールアドレス";
        $to_email = $this->input->post('email');

        //Load email library
        $this->load->library('email');

        $config = array();
        $config['protocol'] = 'smtp';
        $config['smtp_host'] = '送信メールサーバ';
        $config['smtp_user'] = 'メールアドレス';
        $config['smtp_pass'] = 'パスワード';
        $config['smtp_port'] = '587';
        $this->email->initialize($config);
        $this->email->set_newline("\r\n");

        $this->email->from($from_email, 'Identification');
        $this->email->to($to_email);
        $this->email->subject('Send Email Codeigniter');
        $this->email->message('The email send using codeigniter library');

        //Send mail
        if($this->email->send())
            $this->session->set_flashdata("email_sent","Congragulation Email Send Successfully.");
        else{
            $this->session->set_flashdata("email_sent","You have encountered an error");
            var_dump($this->email->print_debugger);
        }
        $this->load->view('contact_email_form');
    }
}

?>