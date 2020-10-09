<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends MY_User_Controller {
	
    var $login_info = array();
	public function __construct()
    {
        parent::__construct();
        $this->login_info = $this->session->userdata('user');
    }

    public function login()
    {
        if(!empty($this->login_info)) {
            redirect(SITE_URL);
        } else {
            $data['header_title'] = 'Login to E-Commerce';
            $this->load->view('header',$data);
            $this->load->view('login');
            $this->load->view('footer');
        }
    }

	public function frm_login()
    {
    	$json_array['status'] = ERROR_CODE;
        $json_array['message'] = 'Direct access not allowed';

        if($this->input->is_ajax_request())
        {
        	$this->form_validation->set_rules('mobile_no',"Mobile Number",'trim|required');
            $this->form_validation->set_rules('password',"Password",'required');
            
            if ($this->form_validation->run() == FALSE)
            {
                $json_array['status'] = ERROR_CODE;
                $json_array['message'] = validation_errors('','');
            }
            else
            {
            	$this->load->model('register_model');
            	$frm_details = $this->input->post();

                $where = array('mobile_no' => $frm_details['mobile_no']);

                $result_array = $this->register_model->select(TRUE,array('*'),$where);

                $login_verified = false;

                if(!empty($result_array)) { 
                	
                	$login_verified = password_verify($frm_details['password'],$result_array['password']);

                	if ($login_verified === true) {

	                	$this->session->set_userdata(array(
                                    'user'=>array('id' => $result_array['id'],
                                    'full_name' => $result_array['full_name'],
                                    'email_id' => $result_array['email_id'],
                                    'gender' => $result_array['gender'],
                                    'mobile_no' => $result_array['mobile_no'],
                                    'user_type' => $result_array['user_type'],
                                    'user_logged_in' => TRUE)
                            ));

                            $cookie_data = array('id' => $result_array['id']);
                            $this->set_user_login_cookie($cookie_data);
                            $cookie = array(
                                'name'   => 'username',
                                'value'  => $result_array['full_name'],
                                'expire' => time()+60*60*24*30,
                                'path'   => '/',
                                'prefix' => '',
                            );
                            set_cookie($cookie);
                            $json_array['status'] = SUCCESS_CODE;
                            $json_array['message'] = "Successfully logged in";
                            $json_array['redirect_url'] = SITE_URL;
                            $json_array['last_visit_url'] = $this->session->userdata('previous_url');

	                }else {
    
                        $json_array['status'] = ERROR_CODE;
                        $json_array['message'] = "Invalid Credentials";
                    }

                }else {
                	$json_array['status'] = ERROR_CODE;
                    $json_array['message'] = 'Mobile Number not registered';
                }
            }
        }
       	
       	echo_json($json_array);
    }
}
