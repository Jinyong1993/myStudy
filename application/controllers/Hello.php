<?php

class Hello extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/userguide3/general/urls.html
	 */
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
		
		$isError = false;
		$arr = array();
		
		$search = !empty($_GET['search']);

		if(!is_numeric($year) || !is_numeric($month) || ($year > 9999 || $month > 13) || ($year < 0 || $month < 0)){
			echo "잘못된 형식입니다.";
			$isError = true;
		}

		$first_date = "$year-$month-01";
		$time = strtotime($first_date);
		$start = date('w', $time);
		$total_day = date('t', $time);

		$this->load->database();
		$query = $this->db->query("select * from calendar where year=$year and month=$month;");

		foreach($query->result() as $row){
			var_dump($row);
		}

		$data = array(
			'isError' => $isError,
			'year' => $year,
			'month' => $month,
			'total_day' => $total_day,
			'time' => $time,
			'query' => $query->result(),
		);
		
		$this->load->view("test", $data);
	}
}
