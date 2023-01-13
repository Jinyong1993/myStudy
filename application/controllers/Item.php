<?php
defined('BASEPATH') OR exit('No direct script access allowed');
  
class Item extends CI_Controller {
   
   /**
    * Get All Data from this method.
    *
    * @return Response
   */
   public function __construct() {
      parent::__construct(); 
  
      $this->load->library('form_validation');
      $this->load->library('session');
   }
   
   /**
    * Create from display on this method.
    *
    * @return Response
   */
   public function index()
   {
      $this->load->view('regi_view');
   }
   
   /**
    * Store Data from this method.
    *
    * @return Response
   */
   public function itemForm()
   {
        $this->form_validation->set_rules('account', 'Account', 'required');
        $this->form_validation->set_rules('password', 'Password', 'required');
        $this->form_validation->set_rules('name', 'Name', 'required');
        $this->form_validation->set_rules('age', 'Age', 'required');
        $this->form_validation->set_rules('gender', 'Gender', 'required');
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email');
        $this->form_validation->set_message('required', 'error');
   
        if ($this->form_validation->run() == FALSE){
            $this->load->view('regi_view'); 
        }else{
            $this->load->view('login_view');
           echo json_encode(['success'=>'Record added successfully.']);
        }
    }
}