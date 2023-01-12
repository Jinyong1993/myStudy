<?php

class Login extends CI_Controller {

    public function __construct()
    {
        parent::__construct();

        $this->load->database();
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

    public function regis_proc()
    {
        $submit = $this->input->post('submit');
        $account = $this->input->post('account'); // 숫자나 특수기호로 시작 못하고 특수문자 못들어감
        $password = $this->input->post('password'); // 숫자나 특수기호로 시작 못하고 특수문자 못들어감
        $name = $this->input->post('name'); // 숫자인거 못들어감
        $age = $this->input->post('age');
        $gender = $this->input->post('gender');
        $email = $this->input->post('email');
        $regdate = date("Y/m/d-H:i:s");
        $err_message = "";
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

        if(!isset($account) && !isset($password) && (is_numeric($name) && !isset($name)) && !isset($age) && !isset($gender) && !isset($email))
        {
            $err_message = "error";
            $is_error = true;

            $user_data['err_message'] = $err_message;
            $user_data['is_error'] = $is_error;
            return $user_data;
        }

        // ID重複チェック
        $this->db->from('user')
        ->where('account', $account);
        $result = $this->db->count_all_results();
        $password_hash = password_hash($password, PASSWORD_DEFAULT);
        $user_data['password'] = $password_hash;
        
        if($result != 0)
        {
            return $user_data;
        }


        if(isset($user_data))
        {
            $this->db->insert('user', $user_data);
            redirect("https://localhost:10443/sample/index.php/Login/index");
        }
    }
}