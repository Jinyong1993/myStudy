<?php

class Login extends CI_Controller {

    public function __construct()
    {
        parent::__construct();

        $this->load->database();
        $this->load->library('form_validation');
        $this->load->library('session');
        $this->load->helper('url');
    }

    public function index ()
    {
        $this->load->view("login_view.php");
    }

    public function login_proc ()
    {
        $account = $this->input->post('account');
        $password = $this->input->post('password');

        $login_success = $this->login_validate($password, $account);
		if(!empty($login_success)){
			$this->load->helper('url');
            $this->session->set_userdata('user_id', $login_success->user_id);
			redirect("https://localhost:10443/sample/index.php/Hello/calendar");
		} else {
            redirect("https://localhost:10443/sample/index.php/Login/index");
        }
    }

    public function logout()
    {
        $this->load->model('LoginModel');
        $this->LoginModel->logout();
    }

    public function regis()
    {
        $user_data = $this->regis_proc();
        $this->load->view('regi_view', $user_data);
    }

    private function regis_proc()
    {
        $submit = $this->input->post('submit');
        $account = $this->input->post('account');
        $password = $this->input->post('password');
        $name = $this->input->post('name');
        $age = $this->input->post('age');
        $gender = $this->input->post('gender');
        $email = $this->input->post('email');
        $regdate = date("Y/m/d-H:i:s");
        $is_error = false;

        $user_data = array(
            'account' => $account,
            'name' => $name,
            'age' => $age,
            'gender' => $gender,
            'email' => $email,
            'regdate' => $regdate,
        );
        
        if(!$submit)
        {
            return $user_data;
        }

        $this->form_validation->set_rules('account', 'アカウント', 'required|is_unique[user.account]');
        $this->form_validation->set_rules('password', 'パスワード', 'required|min_length[4]');
        $this->form_validation->set_rules('confirm_password', 'パスワード', 'required|matches[password]');
        $this->form_validation->set_rules('name', '名前', 'required');
        $this->form_validation->set_rules('age', '年齢', 'required|numeric');
        $this->form_validation->set_rules('gender', '性別', 'required');
        $this->form_validation->set_rules('email', 'メール', 'required|valid_email|is_unique[user.email]');

        if ($this->form_validation->run() == FALSE){
            $is_error = true;
            $user_data['is_error'] = $is_error;
            return $user_data;
        } else {

            if(isset($user_data))
            {
                // ID重複チェック
                // $this->db->from('user')
                // ->where('account', $account);
                // $result = $this->db->count_all_results();
                $password_hash = password_hash($password, PASSWORD_DEFAULT);
                $user_data['password'] = $password_hash;
                // if($result != 0)
                // {
                //     return $user_data;
                // }
                
                $this->db->insert('user', $user_data);
                // redirect("https://localhost:10443/sample/index.php/Login/index");
            }
            redirect("https://localhost:10443/sample/index.php/Login/index");
            // $this->load->view('login_view');
            echo json_encode(['success'=>'Record added successfully.']);
        }
        
        

        // if((!isset($account) || $account))
        // {
        //     $err_message = "error";
        //     $is_error = true;

        //     $user_data['err_message'] = $err_message;
        //     $user_data['is_error'] = $is_error;
        //     return $user_data;
        // }

        
    }

    public function user_info()
    {
        $this->load->library('session');
        $user_id = $this->session->userdata('user_id');
        var_dump($user_id);

        $this->load->database();
        $this->db->from('user');
        $this->db->where('user_id',$user_id);
        $query = $this->db->get();
        $result = $query->first_row();

        var_dump($result);

        $user_info = array(
            'result' => $result,
        );

        $this->load->view('user_info_view.php', $user_info);
    }

    public function user_info_proc()
    {
        $this->load->library('session');
        $user_id = $this->session->userdata('user_id');
        
        $password = $this->input->post('password');
        $password_change = $this->input->post('password_change');
        $email_change = $this->input->post('email_change');
        
        $password_validate = $this->login_validate($password, null, $user_id);
		if(!$password_validate){
            $err_message = "パスワードを確認して下さい。";
            $this->session->set_userdata('validate', $validate);
            redirect("https://localhost:10443/sample/index.php/Login/user_info");
		}
        
        $this->form_validation->set_rules('password_change', 'パスワード', 'min_length[4]');
        $this->form_validation->set_rules('password_change_confirm', 'パスワード', 'matches[password_change]');
        $this->form_validation->set_rules('email_change', 'メール', 'valid_email|is_unique[user.email]');
        $this->form_validation->set_rules('email_change_confirm', 'メール', 'matches[email_change]');
        
        if ($this->form_validation->run() == FALSE){
            $validate = validation_errors();
            $this->session->set_userdata('validate', $validate);
            redirect("https://localhost:10443/sample/index.php/Login/user_info");
        } else {
            
            $this->load->database();
            // 이거 안먹힘
            if(isset($password_change))
            {
                $password_hash = password_hash($password_change, PASSWORD_DEFAULT);
                $this->db->set('password', $password_hash);
            }

            if(isset($email_change)) {
                $this->db->set('email', $email_change);
            }

            $this->db->where('user_id', $user_id);
            $this->db->update('user');

            $success = true;
            $this->session->set_userdata('success', $success);

            redirect("https://localhost:10443/sample/index.php/Login/user_info");
        }
    }
    
    public function login_validate($password, $account=null, $user_id=null)
    {
        if(isset($account))
        {
            $this->db->where('account', $account);
        } elseif(isset($user_id)) {
            $this->db->where('user_id', $user_id);
        } else {
            return false;
        }

        $this->db->select('password, user_id');
        $this->db->from('user');
        $query = $this->db->get();
        $password_hash = $query->first_row();

        if(!isset($password_hash))
        {
            return false;
        }
        
        $is_match = password_verify($password, $password_hash->password);
        
        if($is_match)
        {
            return $password_hash;
        } else {
            return false;
        } 
    }

    // function special_check($str)
    // {
    //     $pattern = "/[#\&\\+\-%@=\/\\\:;,\.\'\"\^`~\_|\!\/\?\*$#<>()\[\]\{\}]/i";
    //     if(preg_match($pattern, $str, $match))
    //     {
    //     return false;
    //     }

    //     return true;
    // }

    // function password_check($password)
    // {
    //     $pattern = "/^[0-9A-Za-z]+$/";
    //     if(preg_match($pattern, $password, $match))
    //     {
    //         return false;
    //     }

    //     return true;
    // }
}