<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends MY_User_Controller {

	var $login_info = array();
	public function __construct()
    {
    	parent::__construct();
        $this->session->set_userdata('previous_url', current_url());
        $this->login_info = $this->session->userdata('user');
        $this->load->model(array('common_model'));
    }

	public function index()
	{
		$this->load->model('product_model');
		$data['feature_product'] = $this->product_model->homepage_product();
		$data['latest_product'] = $this->product_model->homepage_product(array('is_latest_product' => 1));

		$data['header_title'] = 'Welcome to E-Commerce';
		$this->load->view('header',$data);
		$this->load->view('home');
		$this->load->view('footer');
	}

	public function register()
	{
		if(!empty($this->login_info)) {
			redirect(SITE_URL);
		} else {
			$data['header_title'] = 'Registration to E-Commerce';
			//SELECT category_name, (SELECT GROUP_CONCAT(category_name) FROM category as sub_cat WHERE category.id = sub_cat.is_parent_category) FROM `category` WHERE category.is_parent_category = 0
			$this->load->view('header',$data);
			$this->load->view('register');
			$this->load->view('footer');
		}
	}

	public function frm_register()
    {
    	$json_array['status'] = ERROR_CODE;
        $json_array['message'] = 'Direct access not allowed';

        if($this->input->is_ajax_request())
        {
        	$mobile = $this->input->post('mobile_number');

        	$this->form_validation->set_rules('name',"Name",'required');
            $this->form_validation->set_rules('mobile_number',"Mobile Number",'trim|required|is_unique[user_details.mobile_no]');
            $this->form_validation->set_rules('email',"Email ID",'required|valid_email');
            $this->form_validation->set_rules('password',"Password",'required');
            $this->form_validation->set_rules('password_confirm',"Confirm Password",'required');
            $this->form_validation->set_rules('agree',"Accept Term and Conditions",'required');
            	
            $this->form_validation->set_message('min_length','%s min %s charecter long');
            $this->form_validation->set_message('matches','Comfirm password same as Password');
            $this->form_validation->set_message('is_unique',"$mobile mobile number exists, please login or use diffrent number.");

            if ($this->form_validation->run() == FALSE)
            {
                $json_array['status'] = ERROR_CODE;
                $json_array['message'] = validation_errors('','');
            }
            else
            {
            	$this->load->model('register_model');

                $frm_details = $this->input->post();
                $details = array('full_name' => $frm_details['name'],
								'mobile_no' => $frm_details['mobile_number'],
								'email_id' => $frm_details['email'],
								'gender'	=> $frm_details['gender'],
								'password' => create_password($frm_details['password_confirm']),
								'user_type' => 'Register',
								'status'    => STATUS_ACTIVE,
								'created_on' => date(DB_DATE_FORMAT)
                			);
                $insert_status = $this->register_model->save($details);
                if($insert_status) {
                	$json_array['status'] = SUCCESS_CODE;
                    $json_array['message'] = 'Record inserted Successfully';
                    $json_array['redirect'] = SITE_URL.'login';
                }else {
                	$json_array['status'] = ERROR_CODE;
                    $json_array['message'] = 'Unable to store in Database,Please try again';
                }
            }
        }
       	
       	echo_json($json_array);
    }

	public function is_username_exits()
    {
        if($this->input->is_ajax_request())
        {
            $result = $this->common_model->select('user_details',TRUE,array('id'),array('mobile_no' => $this->input->post('mobile_no')));

            echo (!empty($result) ? 'false' : 'true');
        }
        else
        {
            echo 'false';
        }
    }
    public function logout()
    {
        if ($this->is_user_logged_in()) {	
        	$this->logout_user();
        }
        redirect(SITE_URL);
    }
}
