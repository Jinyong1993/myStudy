<?php

class Hello extends CI_Controller {

	public function __construct()
    {
		parent::__construct();
		$this->load->database();
        $this->load->library('session');
		$this->load->library('form_validation');
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
		$month = $this->input->get("month") ?: date('n');

		$user_id = $this->session->userdata('user_id');
		$this->db->from('color')
				 ->where('user_id', $user_id);
		$query = $this->db->get();
		$color_result = $query->result();
		
		$arr = array();

		$isError = $this->validate($year, $month);

		if($isError){
			$this->load->helper('url');
			redirect("https://localhost:10443/sample/index.php/Hello/calendar");
		}
		
		$search = !empty($_GET['search']);

		$day = 1;
		$first_date = "$year-$month-$day";
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
			'color_result' => $color_result,
		);
		
		$this->load->view("test", $data);
	}

	private function select($year, $month)
	{
		$user_id = $this->session->userdata('user_id');

		$arr = array();

		$this->load->database();
		$this->db->select('calendar.id, calendar.user_id, calendar.year, calendar.month, calendar.day, calendar.title, calendar.text, color.color_id, color.color_color');
		$this->db->from('calendar');
		$this->db->where('calendar.user_id', $user_id);
		$this->db->where('calendar.year', $year);
		$this->db->where('calendar.month', $month);
		$this->db->join('color', 'color.color_id=calendar.color_id', 'left');
		$query = $this->db->get();

		foreach($query->result() as $row){
			$arr[$row->day][] = $row;
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
		$color_id = $this->input->post("color_id");

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
				'color_id' => $color_id[$day],
			);
			if(isset($select[$day+1]))
			{
				$this->db->set('text', $in);
				$this->db->set('color_id', $color_id[$day]);
				$this->db->where('user_id', $user_id);
				$this->db->where('year', $year);
				$this->db->where('month', $month);
				$this->db->where('day', $day+1);
				$this->db->update('calendar');
			} else {
				$this->db->insert('calendar', $data);
			}
		}

		redirect("https://localhost:10443/sample/index.php/Hello/calendar?year=$year&month=$month");
		// $query = $this->db->insert('calendar', $insert);

	}

	public function search_ajax_controller()
	{
		$user_id = $this->session->userdata('user_id');
		$color_id = $this->input->get('color_id');
		$text = $this->input->get('text');

		// マイカラー選択がある場合
		if(!empty($color_id)){
			$this->db->where('calendar.color_id', $color_id);
		}
		$this->db->from('calendar');
		$this->db->like('calendar.text', $text);
		$this->db->where('calendar.user_id', $user_id);
		$this->db->join('color', 'color.color_id=calendar.color_id', 'left');
		$query = $this->db->get();
		$result = $query->result();

		echo json_encode($result);
	}

	public function title_ajax_controller()
	{
		$user_id = $this->session->userdata('user_id');
		$id = $this->input->get('id');

		$this->db->from('calendar');
		$this->db->where('user_id', $user_id);
		$this->db->where('id', $id);
		$query = $this->db->get();
		$result = $query->first_row();

		echo json_encode($result);
	}

	public function color_edit_ajax()
	{
		$user_id = $this->session->userdata('user_id');
		$color_id = $this->input->post('color_id');
		$color_name = $this->input->post('color_name');
		$color_note = $this->input->post('color_note');
		$color_color = $this->input->post('color_color');

		$color_data = array(
			'user_id' => $user_id,
			'color_name' => $color_name,
			'color_note' => $color_note,
			'color_color' => $color_color,
		);

		$response = array(
			'success' => true
		);

		$this->form_validation->set_rules('color_name', '名前', 'required');
		if ($this->form_validation->run() == FALSE){
			$response['success'] = false;
			$response['error'] = validation_errors();
			echo json_encode($response);
			return;
		}

		if(!empty($color_id)){
			$this->db->from('color');
			$this->db->set('color_name', $color_name);
			$this->db->set('color_note', $color_note);
			$this->db->set('color_color', $color_color);
			$this->db->where('user_id', $user_id);
			$this->db->where('color_id', $color_id);
			$this->db->update('color');
		} else {
			$this->db->insert('color', $color_data);
		}


		echo json_encode($response);
	}

	public function color_del_ajax()
	{
		$user_id = $this->session->userdata('user_id');
		$color_id = $this->input->post('color_id');

		$this->db->from('color')
				 ->where('user_id', $user_id)
				 ->where('color_id', $color_id)
				 ->delete('color');
		
		$response = array(
			'success' => true
		);

		echo json_encode($response);
	}

	public function plus_ajax_controller()
	{
		$user_id = $this->session->userdata('user_id');
		$id = $this->input->post('id');
		$year = $this->input->post('year');
		$month = $this->input->post('month');
		$day = $this->input->post('day');
		$title = $this->input->post('title');
		$text = $this->input->post('text');
		$color_id = $this->input->post('color_id');

		$this->db->from('calendar');
		$this->db->where('user_id', $user_id);
		$this->db->where('id', $id);
		$query = $this->db->get();
		$result = $query->first_row();

		$response = array(
			"success" => true
		);
		
		$this->form_validation->set_rules('title', 'タイトル', 'required');
		if ($this->form_validation->run() == FALSE){
			$response["success"] = false;
			$response["error"] = validation_errors();
			echo json_encode($response);
			return;
		}

		$isError = $this->validate($year, $month);
		if($isError){
			$response["success"] = false;
			$response["error"] = "エラー";
			echo json_encode($response);
			return;
		}

		if(is_array($day)){
			foreach($day as $d){
				$plus_data = array(
					'user_id' => $user_id,
					'year' => $year,
					'month' => $month,
					'day' => $d,
					'title' => $title,
					'text' => $text,
					'color_id' => $color_id,
				);
				if(isset($result)){
					$this->db->set('title', $title);
					$this->db->set('text', $text);
					$this->db->set('color_id', $color_id);
					$this->db->where('user_id', $user_id);
					$this->db->where('id', $id);
					$this->db->update('calendar');
				} else {
					$this->db->insert('calendar', $plus_data);
				}
			}
		} elseif(isset($result)) {
			$plus_data = array(
				'user_id' => $user_id,
				'year' => $year,
				'month' => $month,
				'day' => $day,
				'title' => $title,
				'text' => $text,
				'color_id' => $color_id,
			);
			$this->db->set('title', $title);
			$this->db->set('text', $text);
			$this->db->set('color_id', $color_id);
			$this->db->where('user_id', $user_id);
			$this->db->where('id', $id);
			$this->db->update('calendar');
		} else {
			$plus_data = array(
				'user_id' => $user_id,
				'year' => $year,
				'month' => $month,
				'day' => $day,
				'title' => $title,
				'text' => $text,
				'color_id' => $color_id,
			);
			if(isset($result)){
				$this->db->set('title', $title);
				$this->db->set('text', $text);
				$this->db->set('color_id', $color_id);
				$this->db->where('user_id', $user_id);
				$this->db->where('id', $id);
				$this->db->update('calendar');
			} else {
				$this->db->insert('calendar', $plus_data);
			}
		}

		echo json_encode($response);
	}

	public function delete_ajax(){
		$user_id = $this->session->userdata('user_id');
		$id = $this->input->post('id');
		$year = $this->input->post('year');
		$month = $this->input->post('month');
		$day = $this->input->post('day');
		$title = $this->input->post('title');
		$text = $this->input->post('text');
		$color_id = $this->input->post('color_id');

		if(is_array($day) || is_array($id)){
			foreach($day as $d){
				foreach($id as $i){
					$plus_data = array(
						'id' => $i,
						'user_id' => $user_id,
						'year' => $year,
						'month' => $month,
						'day' => $d,
						'title' => $title,
						'text' => $text,
						'color_id' => $color_id,
					);
					$this->db->from('calendar');
					$this->db->where('user_id', $user_id)
							->where('id', $i);
					$this->db->delete('calendar');
				}
			}
		} else {
			$this->db->from('calendar');
			$this->db->where('user_id', $user_id)
					 ->where('id', $id);
			$this->db->delete('calendar');
		}

		$response = array(
			"success" => true
		);

		echo json_encode($response);
	}

	public function color_save_ajax()
	{
		$user_id = $this->session->userdata('user_id');
		$color_name = $this->input->post('color_name');
		$color_note = $this->input->post('color_note');
		$color_color = $this->input->post('color_color');

		$color_data = array(
			'user_id' => $user_id,
			'color_name' => $color_name,
			'color_note' => $color_note,
			'color_color' => $color_color,
		);

		$response = array(
			'success' => true
		);

		$this->form_validation->set_rules('color_name', '名前', 'required');
		if ($this->form_validation->run() == FALSE){
			$response['success'] = false;
			$response['error'] = validation_errors();
			echo json_encode($response);
			return;
		}

		$this->db->insert('color', $color_data);

		echo json_encode($response);
	}

	public function my_color_ajax()
	{
		$user_id = $this->session->userdata('user_id');
		$color_id = $this->input->get('color_id');

		$this->db->from('color')
				 ->where('user_id', $user_id)
				 ->where('color_id', $color_id);
		$query = $this->db->get();
		$color_result = $query->first_row();
		
		echo json_encode($color_result);
	}
	
	private function validate($year, $month){
		if(!is_numeric($year) || !is_numeric($month) || ($year > 9999 || $month > 12) || ($year < 1 || $month < 1)){
			return true;
		} else {
			return false;
		}
	}
}
