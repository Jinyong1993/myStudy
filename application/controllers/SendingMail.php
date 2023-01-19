<?php

class SendingMail extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->library('session');
        $this->load->helper('form');
        $this->load->helper('url');
        $this->load->library('form_validation');
    }

    public function index() {
        $this->load->helper('form');
        $this->load->view('contact_email_form.php');
        $this->session->unset_userdata('passwordreset');
        $this->session->unset_userdata('limit');
        $this->session->unset_userdata('code');
    }

    public function send_mail() {
        //　ビューから入力された値
        $user_id = $this->session->userdata('passwordreset');
        $account = $this->input->post('account');
        $email = $this->input->post('email');

        if(!isset($user_id)){
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

            //　ユーザID収得後削除
            $this->db->where('user_id', $user_id);
            $this->db->delete('password_reset');

            $limit_second = 600;

            // ランダムコード生成
            $code = rand(000000,999999);
            $this->session->set_userdata('code', $code);

            $create_timestamp = time();
            $limit_time = $create_timestamp + $limit_second;
            $limit = date('Y/m/d H:i:s',$limit_time);

            $this->load->database();
            $this->db->set('user_id', $user_id);
            $this->db->set('code', $code);
            $this->db->set('limit', $limit);
            $this->db->insert('password_reset');

            // メール
            $from_email = "jeong-jin-young@ib-system.co.jp";
            // $to_email = $this->input->post('email');
    
            //Load email library
            $this->load->library('email');
    
            $config = array();
            $config['protocol'] = 'smtp';
            $config['smtp_host'] = '';
            $config['smtp_user'] = '';
            $config['smtp_pass'] = '';
            $config['smtp_port'] = '587';
            $this->email->initialize($config);
            $this->email->set_newline("\r\n");
    
            $this->email->from($from_email, 'ジンヨン');
            $this->email->to($email);
            $this->email->subject($account.'様、パスワードの変更案内メールです。');
            $this->email->message('６桁のコードを入力して、パスワードの変更を進めて下さい。 ['.$code.']  '.$limit);    
            //Send mail
            if($this->email->send())
            {
                $this->session->set_flashdata("email_sent_success","パスワードの変更案内メールを送信しました。");
            }
        } else {
            $this->load->database();
            $this->db->from('password_reset')
                     ->where('user_id', $user_id);
            $query = $this->db->get();
            $result = $query->first_row();
            $limit = $result->limit;
        }           
        

        $this->session->set_userdata('passwordreset', $user_id);
        $this->session->set_userdata('limit', $limit);

        $data = array(
            'limit' => $limit,
        );

        $this->load->view('validate_password_code_view', $data);
    }

    public function pass_validate()
    {
        // コードでユーザーIDを収得
        $user_id = $this->session->userdata('passwordreset');
        $code_sess_save = $this->session->userdata('code');
        $limit = $this->session->userdata('limit');
        $code = $this->input->post('code');

        $limit_time = strtotime($limit);
        $time = time();

        // サーバ時間と比較し、過ぎたらデータ削除、セッションを消す
        if($limit_time <= $time){
            $this->load->database();
            $this->db->from('password_reset')
                     ->where('user_id', $user_id)
                     ->where('limit', $limit)
                     ->delete('password_reset');
            $this->session->unset_userdata('passwordreset');
            $this->session->set_flashdata('time_over', '認証コードを有効期間内に入力しなければいけません。');
            redirect('https://localhost:10443/sample/index.php/SendingMail/index');
        }

        if($code != $code_sess_save)
        {
            $message = "確認用コードが一致されていません。もう一度確認して下さい。";
            $this->session->set_flashdata("pass_validate_fail", $message);
            redirect('https://localhost:10443/sample/index.php/SendingMail/send_mail');
        } else {
            $this->session->unset_userdata('code');
            $this->load->view('password_change_view');
        }
    }

    public function pass_change()
    {
        $this->load->database();
        $user_id = $this->session->userdata('passwordreset');
        $password = $this->input->post('password');

        $this->form_validation->set_rules('password', 'パスワード', 'required|min_length[4]');
        $this->form_validation->set_rules('password_confirm', 'パスワード', 'required|matches[password]');

        $password_hash = password_hash($password, PASSWORD_DEFAULT);

        if ($this->form_validation->run() == FALSE){
            $validate = validation_errors();
            $this->session->set_userdata('password_change_validate', $validate);
            redirect("https://localhost:10443/sample/index.php/SendingMail/pass_validate");
        }

        $this->db->set('password', $password_hash);
        $this->db->where('user_id', $user_id);
        $this->db->update('user');

        $this->load->view('login_view');
    }
}


// // メール
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

?>