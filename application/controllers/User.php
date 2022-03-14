<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends CI_Controller {

	public function index() {
		$this->load->model('user_model');
		$users = $this->user_model->all();
		$data = array();
		$data['users'] = $users;
		$this->load->view('list', $data);
	}
	
	public function create()
	{
		$this->load->model('user_model');
        $this->form_validation->set_rules('name','Name','required');
        $this->form_validation->set_rules('email','Email','required|valid_email');
        
		if($this->form_validation->run()==false) {
			$this->load->view('create');
		} else {

			//Save record to db
			$formArray['name'] = $this->input->post('name');
			$formArray['email'] = $this->input->post('email');
			$formArray['created_at'] = date('Y-m-d');

			$this->user_model->create($formArray);
			$this->session->set_flashdata('success','Record added successfully');
			redirect(base_url().'user/index');

		}
	}
	public function edit($userId) {
		$this->load->model('user_model');
		$user = $this->user_model->getUser($userId);
		$data = array();
		$data['user'] = $user;

		$this->form_validation->set_rules('name','Name','required');
        $this->form_validation->set_rules('email','Email','required|valid_email');

		if($this->form_validation->run()==false) {
			$this->load->view('edit', $data);
		} else {

			
			$formArray = array();
			$formArray['name'] = $this->input->post('name');
			$formArray['email'] = $this->input->post('email');
			$this->user_model->updateUser($userId, $formArray);
			$this->session->set_flashdata('success','Record upadate successfully');
			
			
			redirect(base_url().'user/index');

		}
	}
	public function delete ($userId) {
		$this->load->model('user_model');
		$user = $this->user_model->getUser($userId);

		if(empty($user)) {
			$this->session->set_flashdata('failure','Record not found in database');
			
			
			redirect(base_url().'user/index');
		}
		$this->user_model->deleteUser($userId);
		$this->session->set_flashdata('success','Record deleted successfully');
		redirect(base_url().'user/index');

	}
}
