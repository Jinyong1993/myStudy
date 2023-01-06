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
		$arr = array();

		$this->load->database();
		$query = $this->db->query("select * from calendar where year=$year and month=$month;");

		
		foreach($query->result() as $row){
			$arr[$row->day] = $row;
			// var_dump($row->text);
		}
		return $arr;
	}

	public function insert()
	{
		$year = $this->input->post("year");
		$month = $this->input->post("month");
		$text = $this->input->post("text_save");
		$color = $this->input->post("color");
		$isError = false;

		// $insert = array(
		// 	'year' => $year,
		// 	'month' => $month,
		// 	'text' => $text,
		// );

		$select = $this->select($year, $month);

		if(!is_numeric($year) || !is_numeric($month) || ($year > 9999 || $month > 13) || ($year < 0 || $month < 0)){
			echo "잘못된 형식입니다.";
			$isError = true;
		}

		$this->load->database();
		foreach($text as $day => $in){
			$data = array(
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
				$this->db->where('year', $year);
				$this->db->where('month', $month);
				$this->db->where('day', $day+1);
				$this->db->update('calendar');
			}
			else
			{
				$this->db->insert('calendar', $data);
			}
		}

		$this->load->helper('url');
		redirect("https://localhost:10443/sample/index.php/Hello/calendar?year=$year&month=$month");
		// $query = $this->db->insert('calendar', $insert);

	}
}
