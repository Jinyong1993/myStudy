<?php

class LoginModel extends CI_Model {

    public function __construct()
    {
        parent::__construct();
    }

    public function logout()
    {
        $this->session->sess_destroy();
        redirect("https://localhost:10443/sample/index.php/Login/index");
    }
}

?>