<?php

class Hello extends CI_Controller {

	public function __construct()
    {
		parent::__construct();
		$this->load->database();
        $this->load->library('session');
		$this->load->helper('url');

		$user_id = $this->session->userdata('user_id');
		if(!isset($user_id))
		{
			$this->load->model('LoginModel');
        	$this->LoginModel->logout();
		} else {
			$this->db->select('user_id');
			$this->db->from('user');
			$this->db->where('user_id', $user_id);
			$query = $this->db->get();
			$row = $query->first_row();

			if(!isset($row))
			{
				$this->load->model('LoginModel');
        		$this->LoginModel->logout();
			}
		}

    }
	
	public function index()
	{
		$today = date('Y-m-d-H-i-s');
		$message = "Hello World";
		$data = array("today" => $today, "message" => $message);
		$this->load->view('test', $data);
	}

	public function calendar()
	{
		$year = $this->input->get("year") ?: date('Y');
		$month = $this->input->get("month") ?: date('m');
		
		$arr = array();

		$isError = $this->validate($year, $month);

		if($isError){
			$this->load->helper('url');
			redirect("https://localhost:10443/sample/index.php/Hello/calendar");
		}
		
		$search = !empty($_GET['search']);

		$first_date = "$year-$month-01";
		$time = strtotime($first_date);
		$start = date('w', $time);
		$total_day = date('t', $time);

		$select = $this->select($year, $month);

		$data = array(
			'isError' => $isError,
			'year' => $year,
			'month' => $month,
			'total_day' => $total_day,
			'time' => $time,
			'select' => $select,
		);
		
		$this->load->view("test", $data);
	}

	private function select($year, $month)
	{
		$this->load->library('session');
		$user_id = $this->session->userdata('user_id');

		$arr = array();

		$this->load->database();
		$this->db->select('user_id, year, month, day, text, color');
		$this->db->from('calendar');
		$this->db->where('user_id', $user_id);
		$this->db->where('year', $year);
		$this->db->where('month', $month);
		$query = $this->db->get();

		foreach($query->result() as $row){
			$arr[$row->day] = $row;
			// var_dump($row->text);
		}
		return $arr;
	}

	public function insert()
	{
		$this->load->library('session');
		$user_id = $this->session->userdata('user_id');

		$year = $this->input->post("year");
		$month = $this->input->post("month");
		$text = $this->input->post("text_save");
		$color = $this->input->post("color");

		$isError = $this->validate($year, $month);
		if($isError){
			$this->load->helper('url');
			redirect("https://localhost:10443/sample/index.php/Hello/calendar");
		}

		// $insert = array(
		// 	'year' => $year,
		// 	'month' => $month,
		// 	'text' => $text,
		// );

		$select = $this->select($year, $month);

		$this->load->database();

		foreach($text as $day => $in){
			$data = array(
				'user_id' => $user_id,
				'year' => $year,
				'month' => $month,
				'day' => $day+1,
				'text' => $in,
				'color' => $color[$day],
			);
			if(isset($select[$day+1]))
			{
				$this->db->set('text', $in);
				$this->db->set('color', $color[$day]);
				$this->db->where('user_id', $user_id);
				$this->db->where('year', $year);
				$this->db->where('month', $month);
				$this->db->where('day', $day+1);
				$this->db->update('calendar');
			} else {
				$this->db->insert('calendar', $data);
			}
		}

		$this->load->helper('url');
		redirect("https://localhost:10443/sample/index.php/Hello/calendar?year=$year&month=$month");
		// $query = $this->db->insert('calendar', $insert);

	}

	public function calendar_ajax_controller(){
		$this->load->library('session');
		$user_id = $this->session->userdata('user_id');

		$year = $this->input->post('year');
		$month = $this->input->post('month');
		$day = $this->input->post('day');
		$text = $this->input->post('text');
		$color = $this->input->post('color');

		$isError = $this->validate($year, $month);
		if($isError){
			$this->load->helper('url');
			echo "エラー";
			return;
		}
		// log_message('debug', $text);
		
		$data = array(
			'user_id' => $user_id,
			'year' => $year,
			'month' => $month,
			'day' => $day,
			'text' => $text,
			'color' => $color,
		);

		$select = $this->select($year, $month);
		$this->load->database();

		if(isset($select[$day])){
			$this->db->set('text', $text);
			$this->db->set('color', $color);
			$this->db->where('user_id', $user_id);
			$this->db->where('year', $year);
			$this->db->where('month', $month);
			$this->db->where('day', $day);
			$this->db->update('calendar');
		} else {
			$this->db->insert('calendar', $data);
		}
		echo "処理しました。";
	}

	public function ajax_test() {
		$data = array();
		$this->load->view('ajax_view_test', $data);
	}

	public function ajax_controller(){
		$number1 = $_POST['number1'];
		$number2 = $_POST['number2'];

		$result = $number1 + $number2;

		echo $result;
	}

	private function validate($year, $month){
		if(!is_numeric($year) || !is_numeric($month) || ($year > 9999 || $month > 12) || ($year < 1 || $month < 1)){
			return true;
		} else {
			return false;
		}
	}
}
