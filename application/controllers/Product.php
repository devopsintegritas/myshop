<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Product extends CI_Controller {

	var $login_info = array();
	public function __construct()
    {
        parent::__construct();
        $this->session->set_userdata('previous_url', current_url());
        $this->login_info = $this->session->userdata('user');
        $this->load->model(array('product_model'));
    }

	public function index($product_name = false,$product_id = false)
	{
		if($product_name && $product_id) 
		{
			$product_detail = $this->product_model->homepage_product(array('products.id' => $product_id));

			if(!empty($product_detail)) {
				$data['product_detail'] = $product_detail[0];
				$data['header_title'] = $data['product_detail']['product_name'].' Product';
				$this->load->view('header',$data);
				$this->load->view('product_detail');
				$this->load->view('footer');
			}else {
				$data['header_title'] = 'Page not found';
				$this->load->view('header',$data);
				$this->load->view('custom_404');
				$this->load->view('footer');
			}
		}
		else 
		{
			$data['header_title'] = 'Caterory Details';
			$this->load->view('header',$data);
			$this->load->view('product_listing');
			$this->load->view('footer');
		}
	}
}
