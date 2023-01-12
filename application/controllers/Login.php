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
        $this->load->view('regi_view.php');
    }

    public function regis_proc()
    {
        $account = $this->input->post('account');
        $password = $this->input->post('password');
        $name = $this->input->post('name');
        $age = $this->input->post('age');
        $gender = $this->input->post('gender');
        $email = $this->input->post('email');
        $regdate = date("Y/m/d-H:i:s");

        $password_hash = password_hash($password, PASSWORD_DEFAULT);
        
        // ID重複チェック
        $this->db->select('account');
        $this->db->from('user');
        $this->db->where('account', $account);
        

        $user_data = array(
            'account' => $account,
            'password' => $password_hash,
            'name' => $name,
            'age' => $age,
            'gender' => $gender,
            'email' => $email,
            'regdate' => $regdate,
        );

        if(isset($user_data))
        {
            $this->db->insert('user', $user_data);
            redirect("https://localhost:10443/sample/index.php/Login/index");
        }
    }
}