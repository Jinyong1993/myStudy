<?php

class SendingMail extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->library('session');
        $this->load->helper('form');
        $this->load->helper('url');
    }

    public function index() {
        $this->load->helper('form');
        $this->load->view('contact_email_form.php');
    }

    public function send_mail() {
        $code = null;
        
        //　ビューから入力された値
        $account = $this->input->post('account');
        $email = $this->input->post('email');

        //　データベース
        $this->load->database();
        $this->db->select('account, email, user_id')
                 ->from('user')
                 ->where('account', $account)
                 ->where('email', $email);
        $query = $this->db->get();
        $result = $query->first_row();

        $user_id = $result->user_id;
        
        // 失敗時 null
        if(empty($result)){
            $error_message = "アカウント又は、メールアドレスがただしくありません。";
            $this->session->set_flashdata("error", $error_message);
            redirect("https://localhost:10443/sample/index.php/SendingMail/index");
        }

        // 成功時
        if(!empty($result)){
            // create random code
            $code = rand(000000,999999);

            // database
            $this->load->database();



            // user_id

            // nyuryokupage
            redirect("https://localhost:10443/sample/index.php/SendingMail/index");
        }


        // メール
        // $from_email = "メールアドレス";
        // $to_email = $this->input->post('email');

        // //Load email library
        // $this->load->library('email');

        // $config = array();
        // $config['protocol'] = 'smtp';
        // $config['smtp_host'] = '送信メールサーバ';
        // $config['smtp_user'] = 'メールアドレス';
        // $config['smtp_pass'] = 'パスワード';
        // $config['smtp_port'] = '587';
        // $this->email->initialize($config);
        // $this->email->set_newline("\r\n");

        // $this->email->from($from_email, '名前');
        // $this->email->to($to_email);
        // $this->email->subject('メールテスト');
        // $this->email->message('The email send using codeigniter library');

        // //Send mail
        // if($this->email->send())
        //     $this->session->set_flashdata("email_sent","Congragulation Email Send Successfully.");
        // else{
        //     $this->session->set_flashdata("email_sent","You have encountered an error");
        //     var_dump($this->email->print_debugger);
        // }
        // $this->load->view('contact_email_form');
    }
}

?>