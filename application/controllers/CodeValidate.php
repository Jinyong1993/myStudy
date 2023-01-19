<?php

class CodeValidate extends CI_Controller {

    public function __constructor()
    {
        parent::__construct();
        $this->load->library('session');
        $this->load->helper('form');
        $this->load->helper('url');
    }

    public function index()
    {

        $this->load->view('validate_password_code_view');
    }

    public function pass_validate()
    {
        
    }

}

?>