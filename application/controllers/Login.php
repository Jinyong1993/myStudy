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

        $this->db->select('user_id, account, password');
        $this->db->from('user');
        $this->db->where('account', $account);
        $this->db->where('password', $password);
        $query = $this->db->get();

        $row = $query->first_row();

        if(empty($row))
        {
			redirect("https://localhost:10443/sample/index.php/Login/index");
        } else {
            $this->session->set_userdata('user_id', $row->user_id);
			redirect("https://localhost:10443/sample/index.php/Hello/calendar");
        }
    }

    public function logout()
    {
        $this->load->model('LoginModel');
        $this->LoginModel->logout();
    }
}