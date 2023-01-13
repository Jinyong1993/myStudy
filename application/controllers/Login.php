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

        $this->db->select('password, user_id');
        $this->db->from('user');
        $this->db->where('account', $account);
        $query = $this->db->get();
        $password_hash = $query->first_row();

        if(!isset($password_hash))
        {
            redirect("https://localhost:10443/sample/index.php/Login/index");
        }
        
        $is_match = password_verify($password, $password_hash->password);
        
        if($is_match)
        {
            $this->session->set_userdata('user_id', $password_hash->user_id);
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