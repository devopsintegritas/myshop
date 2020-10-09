<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Candidates extends MY_Controller
{    
    function __construct()
    {
        parent::__construct();
        if(!$this->is_admin_logged_in()) {   
            redirect(ADMIN_SITE_URL);
            exit();
        }
        
        $this->load->model('candidates_model');
    }
    
    public function index()
    {
        $data['header_title'] = 'Candidate List';

        if(USERS['CLIENT'] == $this->login_info['role'])  {
            $data['candidates'] = $this->candidates_model->get_address_details(array('client_id ' => $this->login_info['id'],'verification_status != ' => 'Clear','status != ' => 3));
        } else {
            $data['candidates'] = $this->candidates_model->get_address_details(array());
        }

        $this->load->view('dashboard/header',$data);            
        $this->load->view('dashboard/candidate_list');
        $this->load->view('dashboard/footer');
    }

    public function add_bulk_upload()
    {
        $data['header_title'] = 'Candidate Add/ Bulk Upload';
        $data['CMPRefNumber'] = $this->cmp_ref_no_generate();
        $data['sessionclient_id'] = $this->login_info['id'];
        $this->load->view('dashboard/header',$data);
        $this->load->view('dashboard/candidate_add_bulk_upload');
        $this->load->view('dashboard/footer');
    }

    protected function unique_row($mobile_no)
    {
        $public_key = random_string('alnum', 5);
        $private_key = random_string('alnum', 5);
        $where_arry = array('public_key' => $public_key,'private_key' => $private_key,'mobile_1' => $mobile_no);
        $check = $this->candidates_model->select(TRUE,array('id'),$where_arry);

        if(!empty($check)) {
            $this->unique_row($mobile_no);
        }
        return $where_arry;
    }
    
    public function delete_profile($eny_id)
    {   
        if(!empty($eny_id))
        {
            $id = decrypt($eny_id);   

            $success = $this->candidates_model->save(array('status' => 2,'modified_on' => date(DB_DATE_FORMAT),'modified_by' => $this->login_info['id']), array('id' => $id));
            if($success) {
                $this->cli_invite_mail();
                $json_array['status'] = SUCCESS_CODE;
                $json_array['message'] = 'Profile Deleted Successfully';
                $json_array['redirect'] = ADMIN_SITE_URL.'candidates/index';
            } else {
                $json_array['status'] = ERROR_CODE;
                $json_array['message'] = 'Unable to delete from Database,Please try again';
            }
            echo_json($json_array);
        }
    }

    protected function cmp_ref_no_generate()
    {
        $client_name = $this->login_info['id'];
        
        $result = $this->candidates_model->generate_cands_ref_no($client_name);

        if(str_word_count($result['client_name']) > 1) {
            $clientname = explode(' ', $result['client_name']);
            $cmpname = '';
            foreach($clientname as $key => $t)
            {
                if($key < 3) {
                    $cmpname .= (@$t[0]) ? @$t[0] : '';
                }
            }
        }
        else 
        {
            $cmpname = substr($result['client_name'],0,3);
        }
        return strtoupper($cmpname)."-".date('my')."-".$result['total_cands'];
    }

    protected function bulk_ref_no($CMPRefNumber)
    {
        $CMPRefNumber = explode('-', $CMPRefNumber);
        $increment = end($CMPRefNumber)+1;
        return $CMPRefNumber[0].'-'.$CMPRefNumber[1].'-'.$increment;
    }
    protected function cli_invite_mail() 
    {
        $this->load->library('email');
        $this->email->invite_mail_sms();
    }

    protected function cli_qc_reject_mail($reject_data) 
    {
        $this->load->library('email');
        $this->email->qc_reject_mail($reject_data);
    }

    public function candidates_profile()
    {
        $json_array['status'] = ERROR_CODE;
        $json_array['message'] = 'Direct access not allowed';

        if($this->input->is_ajax_request())
        {
            $this->form_validation->set_rules('client_id','ID','required');
            $this->form_validation->set_rules('candidate_name','Name','required');
            $this->form_validation->set_rules('email_id','Email ID','required');
            $this->form_validation->set_rules('mobile_1','Mobile Number','required');
            $this->form_validation->set_rules('reference_no','Reference Number','required');
            $this->form_validation->set_rules('address','Address','required');
            $this->form_validation->set_rules('address_type','Address Type','required');

            if ($this->form_validation->run() == FALSE)
            {
                $json_array['status'] = ERROR_CODE;
                $json_array['message'] = validation_errors('','');
            }
            else
            {
                $frm_details = $this->input->post();    
                
                $uni_keys = $this->unique_row($frm_details['mobile_1']);

                if(!empty($uni_keys)) {
                    
                    $details_arry = array("client_id"   => $frm_details['client_id'],
                                    "candidate_name"    => $frm_details['candidate_name'],
                                    "email_id"          => $frm_details['email_id'],
                                    "mobile_1"          => $frm_details['mobile_1'],
                                    'CMPRefNumber'      => $frm_details['CMPRefNumber'],
                                    "reference_no"      => $frm_details['reference_no'],
                                    "address"           => $frm_details['address'],
                                    "address_type"      => $frm_details['address_type'],
                                    "created_on"        => date(DB_DATE_FORMAT),
                                    "created_by"        => $this->login_info['id'],
                                    "public_key"        => $uni_keys['public_key'],
                                    "private_key"       => $uni_keys['private_key'],
                                    "status"            => STATUS_ACTIVE,
                                    'ip_address'        => $this->input->ip_address()
                                );
                    
                    $result = $this->candidates_model->save($details_arry);

                    if($result)
                    {
                        $this->cli_invite_mail();
                        $json_array['status'] = SUCCESS_CODE;
                        $json_array['message'] = 'Profile Created Successfully';
                        $json_array['redirect'] = ADMIN_SITE_URL.'candidates/add_bulk_upload';
                    }
                    else
                    {
                        $json_array['status'] = ERROR_CODE;
                        $json_array['message'] = 'Unable to store in Database,Please try again';
                    }
                }
                else
                {
                    $json_array['status'] = ERROR_CODE;
                    $json_array['message'] = "Key's are not generated,Please try again";
                }
            }
        }
        echo_json($json_array);
    }

    public function bulk_upload()
    {
        $json_array['status'] = ERROR_CODE;
        $json_array['message'] = 'Direct access not allowed';

        if($this->input->is_ajax_request())
        {
            $this->form_validation->set_rules('file_title','Title','required');
            
            if ($this->form_validation->run() == FALSE)
            {
                $json_array['status'] = ERROR_CODE;
                $json_array['message'] = validation_errors('','');
            }
            else
            {
                $frm_details = $this->input->post();    
                $details_arry = array('title' => $frm_details['file_title'],
                                    'file_name'  => '',
                                    'created_by' => $this->login_info['id'],
                                    'created_on' => date(DB_DATE_FORMAT),
                                    'csv_row_count' => STATUS_DEACTIVE,
                                    'status'    => STATUS_DEACTIVE);

                if (!empty($_FILES['bulk_upload_file'])) {

                    $this->load->library('file_upload');
                    $config_array = array('file_upload_path' => CANDIDATES_UPATH,'file_permission' => 'csv','file_size' => BULK_UPLOAD_MAX_SIZE_MB*2000,'file_data' => $_FILES['bulk_upload_file']);

                    $file_uplod_return = $this->file_upload->file_uplod($config_array);
                    
                    if($file_uplod_return['status'] === TRUE) {

                        $details_arry['file_name'] = $file_uplod_return['file_name'];

                        $handle = fopen($_FILES['bulk_upload_file']['tmp_name'],"r");
                        $csv_row_count = 0;
                        $insert_batch_arry = array();
                        $CMPRefNumber = $this->cmp_ref_no_generate();
                        $first_time = 1;
                        while (($row = fgetcsv($handle, 10000, ",")) != FALSE)
                        {
                            $csv_row_count++;
                            if ($csv_row_count == 1) {
                                continue;
                            }

                            if($first_time > 1)
                                $CMPRefNumber = $this->bulk_ref_no($CMPRefNumber);
                            
                            $first_time++;

                            $row = array_map('trim', $row);
                            show($row);
                            $row = array_walk($row, create_function('&$val', '$val = trim($val);'));  
                            show($row);
                            die;
                            // $row[0] => Reference Number
                            // $row[1] => Full Name
                            // $row[2] => Email ID
                            // $row[3] => Mobile Number
                            // $row[4] => Address
                            // $row[5] => Address Type

                            if(!empty($uni_keys) && $row[3] != "") {
                            
                                $uni_keys = $this->unique_row($row[3]);

                                $insert_batch_arry[] = array(
                                                "client_id"   => $this->login_info['id'],
                                                "CMPRefNumber"      => $CMPRefNumber,
                                                "candidate_name"    => $row[1],
                                                "email_id"          => $row[2],
                                                "mobile_1"          => $row[3],
                                                "reference_no"      => $row[0],
                                                "address"           => $row[4],
                                                "address_type"      => $row[5],
                                                "created_on"        => date(DB_DATE_FORMAT),
                                                "created_by"        => $this->login_info['id'],
                                                "public_key"        => $uni_keys['public_key'],
                                                "private_key"       => $uni_keys['private_key'],
                                                "status"            => STATUS_ACTIVE,
                                                'ip_address'        => $this->input->ip_address()
                                            );
                            }
                        }

                        
                        if(!empty($insert_batch_arry)) {
                            $this->candidates_model->insert_batch($insert_batch_arry);
                            //invite_main_sms_helper();
                        }
                        $details_arry['csv_row_count'] = $csv_row_count;
                        $details_arry['status'] = STATUS_ACTIVE;

                    }else {
                        $json_array['file_error'] = $file_uplod_return['message'];
                    }
                }

                $result = $this->common_model->save('bulk_details',$details_arry);

                if($result)
                {
                    $json_array['status'] = SUCCESS_CODE;
                    $json_array['message'] = 'Profile Created Successfully';
                    $json_array['redirect'] = ADMIN_SITE_URL.'candidates/index';
                }
                else
                {
                    $json_array['status'] = ERROR_CODE;
                    $json_array['message'] = 'Unable to store in Database,Please try again';
                }
            }
        }

        echo_json($json_array);
    }

    public function view_details($id = '')
    {
        $id = decrypt($id);

        $details = $this->candidates_model->get_address_details(array('candidate_details.id' => $id));
        
        if(!empty($details))
        {   
            $details = $details[0];
            $data['js_library'] = array('html2canvas.js');
            $data['header_title'] = $details['candidate_name'].' Profile';
            $data['details'] = $details;

            $this->load->view('dashboard/header',$data);
            $this->load->view('dashboard/candidate_edit');
            $this->load->view('dashboard/footer');
        } 
        else 
        {
            $this->custom404();
        }
    }

    public function update_details()
    {
        $json_array['status'] = ERROR_CODE;
        $json_array['message'] = 'Database error, please try again';

        if($this->input->is_ajax_request())
        {
            $this->form_validation->set_rules('client_id', 'ID', 'required');
            $this->form_validation->set_rules('client_name', 'Client Name', 'required');

            $this->form_validation->set_rules('first_name', 'First Name', 'required');
            $this->form_validation->set_rules('address', 'Address', 'required');

            $this->form_validation->set_message('min_length','Password content min 8 charecter long');
            $this->form_validation->set_message('matches','Comfirm password same as Password');

            $this->form_validation->set_message('alpha_number_dot','Sorry, only letters (a-z), numbers (0-9), and periods (.) are allowed.');

            if ($this->form_validation->run() == FALSE)
            {
                $json_array['status'] = ERROR_CODE;
                $json_array['message'] = validation_errors('','');
            }
            else
            {
                $frm_details = $this->input->post();    

                $details_arry = array("client_name"     => $frm_details['client_name'],
                                "first_name"        => $frm_details['first_name'],
                                "last_name"         => $frm_details['last_name'],
                                "mobile_no"         => $frm_details['mobile_no'],
                                "address"           => $frm_details['address'],
                                "city"              => $frm_details['city'],
                                "last_updated_on"   => date(DB_DATE_FORMAT),
                                "last_updated_by"   => $this->login_info['id'],
                                "status"            => $frm_details['status']
                            );

                if($frm_details['crm_password'] != "" ) {
                    $details_arry['password'] = create_password($frm_details['crm_password']);
                }

                if (!empty($_FILES['profile_pic'])) {

                    $this->load->library('file_upload');
                    $config_array = array('file_upload_path' => CLIENT_LOGO_UPATH,'file_permission' => 'jpeg|jpg|png','file_size' => BULK_UPLOAD_MAX_SIZE_MB*2000,'file_data' => $_FILES['profile_pic']);

                    $file_uplod_return = $this->file_upload->file_uplod($config_array);
                    
                    if($file_uplod_return['status'] === TRUE) {
                        $details_arry['profile_pic'] = $file_uplod_return['file_name'];
                    }else {
                        $json_array['file_error'] = $file_uplod_return['message'];
                    }
                }
                
                $where = array('id' => $frm_details['client_id']);

                $result = $this->clients_model->save($details_arry,$where);

                if($result)
                {
                    $json_array['status'] = SUCCESS_CODE;
                    $json_array['message'] = 'Record Inserted Successfully';
                }
                else
                {
                    $json_array['status'] = ERROR_CODE;
                    $json_array['message'] = 'Unable to store in Database,Please try again';
                }
            }
        }
        echo_json($json_array);
    }

    public function is_email_exits()
    {
        if($this->input->is_ajax_request()) 
        {
            $email_id = $this->input->post('email_id');

            $result = $this->clients_model->select(TRUE,array('id'),array('email_id' => $email_id));

            if(!empty($result)) {
                echo 'false';
            } else {
                echo "true";
            }
        }
    }

    public function canidate_qc()
    {
        $json_array['status'] = ERROR_CODE;
        $json_array['message'] = 'Database error, please try again';

        if($this->input->is_ajax_request())
        {
    
            $this->form_validation->set_rules('verification_status', 'Status', 'required');
            $this->form_validation->set_rules('comment', 'Comment', 'required');
            $this->form_validation->set_rules('cands_update_id', 'ID', 'required');

            if ($this->form_validation->run() == FALSE)
            {
                $json_array['status'] = ERROR_CODE;
                $json_array['message'] = validation_errors('','');
            }
            else
            {
                
                // 'period_stay' => $frm_details['period_stay'],
                // 'period_to' => $frm_details['period_to'],
                // 'verifier_name' => $frm_details['verifier_name'],
                // 'relation_verifier_name' => $frm_details['relation_verifier_name'],
                // 'candidate_remarks' => $frm_details['candidate_remarks'],
                // "candidate_name"    => $frm_details['candidate_name'],
                // "email_id"          => $frm_details['email_id'],
                // "mobile_1"          => $frm_details['mobile_1'],
                // "reference_no"      => $frm_details['reference_no'],
                // "address"           => $frm_details['address'],
                // "address_type"      => $frm_details['address_type']

                $frm_details = $this->input->post();    
                $file_upload_path = CANDIDATES_UPATH.$frm_details['public_key'].$frm_details['private_key'].'/capture_map.png';

                $this->base64_to_jpeg($frm_details['mapdataUrl'],$file_upload_path);
                $details_arry = array('verification_status'=> $frm_details['verification_status'],
                                    'comment' => $frm_details['comment'],
                                    'qc_status' => $frm_details['qc_status'],
                                    'verification_update_on' => date(DB_DATE_FORMAT)
                                );
                $where_arry = array('id' => $frm_details['cands_update_id']);

                $qc_data = array('candidate_id' => $frm_details['cands_update_id'],'qc_status' => $frm_details['qc_status'],'qc_message' => $frm_details['qc_status_fail_message'],'created_on' => date(DB_DATE_FORMAT),'created_by' => $this->login_info['id']);

                $this->common_model->save('qc_status',$qc_data);

                if($frm_details['qc_status'] == 'Fail SMS Send Again') {
                    $this->cli_qc_reject_mail($frm_details);
                    $details_arry['status'] = 4; // QC Rejected
                }
                
                $update = $this->candidates_model->save($details_arry,$where_arry);
                if($update) {
                    $json_array['status'] = SUCCESS_CODE;
                    $json_array['message'] = 'Record Inserted Successfully';
                } else {
                    $json_array['status'] = ERROR_CODE;
                    $json_array['message'] = 'Unable to store in Database,Please try again';
                }
            }
        }
        echo_json($json_array);
    }

    public function base64_to_jpeg($base64_string, $output_file) {
        // open the output file for writing
        $ifp = fopen( $output_file, 'wb' ); 

        // split the string on commas
        // $data[ 0 ] == "data:image/png;base64"
        // $data[ 1 ] == <actual base64 string>
        $data = explode( ',', $base64_string );

        // we could add validation here with ensuring count( $data ) > 1
        fwrite( $ifp, base64_decode( $data[ 1 ] ) );

        // clean up the file resource
        fclose( $ifp ); 

        return $output_file; 
    }
}