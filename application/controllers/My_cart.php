<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class My_cart extends MY_User_Controller {

	var $login_info = array();
	public function __construct()
    {
        parent::__construct();
        $this->session->set_userdata('previous_url', current_url());
        $this->login_info = $this->session->userdata('user');
        $this->load->model(array('product_model'));
        $this->load->library('cart');
    }

    public function index()
	{
		$data['my_cart'] = $this->cart->contents();
		$data['header_title'] = 'My cart to E-Commerce';
		$this->load->view('header',$data);
		$this->load->view('my_cart');
		$this->load->view('footer');
	}

	public function add_to_cart()
	{
		$json_array['status'] = ERROR_CODE;
        $json_array['message'] = 'Direct access not allowed';
		$details = $this->input->post('details');
		$details = json_decode($details,true);

		if(!empty($details) && isset($details)) {
			$img = explode('|', $details['product_images']);
			$data = array(
			        'id'      => $details['id'],
			        'SKU_id'  => $details['SKU_id'],
			        'qty'     => 1,
			        'price'   => $details['product_cost'],
			        'image'   => $img[0],
			        'name'    => $details['product_name']
			);

			$insert = $this->cart->insert($data);
			
			if($insert) {
				$this->session->set_userdata('my_cart_count', count($this->cart->contents()));
				$json_array['status'] = SUCCESS_CODE;
				$json_array['product_count'] = count($this->cart->contents());
                $json_array['message'] = 'Prodcut added to cart';
			}else {
				$json_array['status'] = ERROR_CODE;
        		$json_array['message'] = 'Unable to add to cart';
			}

		}else {
			$json_array['status'] = ERROR_CODE;
        	$json_array['message'] = 'Details missing, please try again';
		}
		echo_json($json_array);
	}

	public function remove_from_cart()
	{
		$json_array['status'] = ERROR_CODE;
        $json_array['message'] = 'Direct access not allowed';

		if($this->input->is_ajax_request())
        {
        	$key = $this->input->post('key');
        	if(!empty($key)) {
        		$data = array( 'rowid' => $key,'qty'   => 0);
        		$this->cart->update($data);
        		$this->session->set_userdata('my_cart_count', count($this->cart->contents()));
        		$json_array['product_count'] = count($this->cart->contents());
        		$json_array['status'] = SUCCESS_CODE;
                $json_array['message'] = 'Prodcut removed from cart';
	        }else {
	        	$json_array['status'] = ERROR_CODE;
	        	$json_array['message'] = 'Details missing, please try again';    
	        }
        }
        
        echo_json($json_array);
	}

	public function frm_checkout()
	{
		$json_array['status'] = ERROR_CODE;
        $json_array['message'] = 'Direct access not allowed';

        if($this->input->is_ajax_request())
        {
        	$mobile = $this->input->post('mobile_number');

        	$this->form_validation->set_rules('name',"Name",'required');
            $this->form_validation->set_rules('mobile_number',"Mobile Number",'trim|required');
            $this->form_validation->set_rules('plat_house_no',"Flat/House No",'required');
            $this->form_validation->set_rules('area',"Area",'required');
            $this->form_validation->set_rules('landmark',"Landmark",'required');
            $this->form_validation->set_rules('city',"City",'required');
        	$this->form_validation->set_rules('pincode',"Pincode",'required');
            	
            if ($this->form_validation->run() == FALSE)
            {
                $json_array['status'] = ERROR_CODE;
                $json_array['message'] = validation_errors('','');
            }
            else
            {
                $frm_details = $this->input->post();

				if($frm_details['userID'] == '') {

	            	$this->load->model('register_model');
	                	$details = array('full_name' => $frm_details['name'],
									'mobile_no' => rand(1000000000,9999999999),
									'email_id' => 'guest@myshop.com',
									'gender'	=> '',
									'password' => 'guest@',
									'user_type' => 'quest',
									'status'    => STATUS_ACTIVE,
									'created_on' => date(DB_DATE_FORMAT)
	                			);
	                $user_details_id = $this->register_model->save($details);
	            }
	            else {
	            	$user_details_id =  $frm_details['userID'];
	            }

	            foreach ($this->cart->contents() as $key => $carts) {

	            	$order_details[] = array(
	            			'user_details_id' => $user_details_id,
							'product_id' => $carts['id'],
							'qty' => $carts['qty'],
							'purchare_price'    => $carts['subtotal'],
							'plat_house_no'	=> $frm_details['plat_house_no'],
							'area'	=> $frm_details['area'],
							'landmark'	=> $frm_details['landmark'],
							'city'	=> $frm_details['city'],
							'pincode'	=> $frm_details['pincode'],
							'state'	=> $frm_details['state'],
							'payment_method' => 'COD',
							'transaction_id'    => '',
							'discount'	=> '',
							'coupon_code'    => '',
							'purchared_on' => date(DB_DATE_FORMAT)
            			);
	            	$data[] = array( 'rowid' => $key,'qty'   => 0);
	            }

	            $status = $this->product_model->insert_batch_product($order_details);
	            if($status) {

	            	if(isset($data) && !empty($data)){
	            		$this->cart->update($data);
	            	}
        			
	            	$json_array['status'] = SUCCESS_CODE;
                	$json_array['message'] = 'Order successfully placed';
	            }else {
	            	$json_array['status'] = ERROR_CODE;
	        		$json_array['message'] = 'Details missing, please try again';    
	            }
            }
        }
       	
       	echo_json($json_array);
	}
}
