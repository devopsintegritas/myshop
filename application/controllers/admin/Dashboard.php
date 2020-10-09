<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends MY_Controller {
	
	function __construct() {
        parent::__construct();
    }

	public function index()
    {   
        if($this->is_admin_logged_in())
        {
            $data['header_title'] = "Dashboard";
            $this->load->view('dashboard/header',$data);
            $this->load->view('dashboard/index',$data);
            $this->load->view('dashboard/footer');
        }
        else
        {
            $this->login_view();
        }
    }

    public function login() {

        $json_array = array();
        if ($this->is_admin_logged_in()) {
            redirect(ADMIN_SITE_URL . 'dashboard/');
        }
        
        if ($this->input->is_ajax_request()) {
            
            if($this->input->cookie('csrf_token',true) === $this->input->post('csrf_token') && $this->input->post('csrf_token') === $this->session->userdata('csrf_token') ) 
            { 
                $this->form_validation->set_error_delimiters('', '');
                $this->form_validation->set_rules('email_id', 'Email', 'required');
                $this->form_validation->set_rules('password', 'Password', 'required');
                if ($this->form_validation->run() == false) {
    
                    $json_array['status'] = ERROR_CODE;
                    $json_array['message'] = validation_errors('', '');
    
                } else {
    
                    $this->load->model('clients_model');
                    $login_details = $this->input->post();
                    
                    $result_array = $this->clients_model->select(TRUE,array('id','client_name','email_id','password','first_name','last_name','mobile_no','address','city','profile_pic','status','role'),array('email_id' => $login_details['email_id'],'status' => STATUS_ACTIVE));

                    $login_verified = false;
    
                    if (!empty($result_array)) {
                        $login_verified = password_verify($login_details['password'], $result_array['password']);
    
                        if ($login_verified === true) {
                            
                            $this->session->set_userdata(array(
                                    'admin'=>array('id' => $result_array['id'],
                                    'client_name' => $result_array['client_name'],
                                    'email_id' => $result_array['email_id'],
                                    'first_name' => $result_array['first_name'],
                                    'last_name' => $result_array['last_name'],
                                    'mobile_no' => $result_array['mobile_no'],
                                    'role' => $result_array['role'],
                                    'admin_logged_in' => TRUE)
                            ));

                            $cookie_data = array('id' => $result_array['id']);
                            $this->set_admin_login_cookie($cookie_data);
                            $cookie = array(
                                'name'   => 'lastloggnedin',
                                'value'  => $result_array['client_name'],
                                'expire' => time()+60*60*24*30,
                                'path'   => '/',
                                'prefix' => '',
                            );
                            set_cookie($cookie);
                            $json_array['status'] = SUCCESS_CODE;
                            $json_array['message'] = "Successfully logged in";
                            $json_array['redirect'] = ADMIN_SITE_URL;
                        } else {
                            $json_array['status'] = ERROR_CODE;
                            $json_array['message'] = "Invalid Credentials";
                        }
    
                    } else {
    
                        $json_array['status'] = ERROR_CODE;
                        $json_array['message'] = "Invalid Credentials";
                    }
                }
            }
            else 
            {
                $json_array['redirect'] = ADMIN_SITE_URL;
                $json_array['status'] = 400;
                $json_array['message'] = "You don't have permission to access";
            }
            echo_json($json_array);

        } else {
            $this->login_view();
        }
    }

    public function login_view()
    {
        $data['csrf_token'] = md5(uniqid());
        $cookie = array(
            'name'   => 'csrf_token',
            'value'  => $data['csrf_token'],
            'path'   => '/',
            'prefix' => '',
        );
        set_cookie($cookie);
        $this->session->set_userdata('csrf_token',$data['csrf_token']);
        $lastloggedin = ($this->input->cookie('lastloggnedin',true)) ? ' '.ucwords($this->input->cookie('lastloggnedin',true)).'!' : '!';
        $data['lastloggnedin'] = $lastloggedin;
        $this->load->view('dashboard/login',$data);
    }
 
    public function logout()
    {
        if ($this->is_admin_logged_in()) {
            $this->logout_admin();
        }
        redirect(ADMIN_SITE_URL);
    }
}