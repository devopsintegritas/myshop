<?php defined('BASEPATH') OR exit('No direct script access allowed.');
class MY_Email extends CI_Email {
    
    protected $CI;
    protected $SMS_key;
    public function __construct(array $config = array())
    {
        parent::__construct($config);
        $this->CI =& get_instance();
        $this->SMS_key = '373552756472617472616e733535381594897349';
    }

    public function qc_reject_mail($detail) {
        $baseurl ="http://smpp.webtechsolution.co/http-tokenkeyapi.php?authentic-key=".$this->SMS_key."&senderid=DIGVER&route=1";

        $login_url = SITE_URL.'v/'.$detail['public_key'].'/'.$detail['private_key'];
        $sms_content = $detail['qc_status_fail_message']." ".$login_url;
        $sms_content = urlencode($sms_content);
        $sms_url = $baseurl.'&number='.$detail['mobile_1'].'&message='.$sms_content;
        $file_responce = file($sms_url);
    }

    public function invite_mail_sms() 
    {
        $this->CI->load->model('common_model');

        $details_arry = $this->CI->common_model->select_candidates();
        $mobile_array = $email_array = array();

        $baseurl ="http://smpp.webtechsolution.co/http-tokenkeyapi.php?authentic-key=".$this->SMS_key."&senderid=DIGVER&route=1";

        foreach ($details_arry as $key => $detail) {

            $login_url = SITE_URL.'v/'.$detail['public_key'].'/'.$detail['private_key'];
            $sms_content = "Please click on the link below and fill for address verification ".$login_url;
            $sms_content = urlencode($sms_content);
            $sms_url = $baseurl.'&number='.$detail['mobile_1'].'&message='.$sms_content;

            $file_responce = file($sms_url);

            if(!empty($file_responce)) {

                $return_responce = explode(':' ,$file_responce[0]);
                if(count($return_responce) > 1) {
                    $mobile_array[] = $detail['id'];
                }
            }

            // $curl_url = "http://115.112.52.186:2086/bgvApi/ClickApi.php?customerNumber=".$detail['mobile_1']."&customerName=Srinivas&clientName=test";
            //candidate_name
            // $cURLConnection = curl_init();

            // curl_setopt($cURLConnection, CURLOPT_URL, $curl_url);
            // curl_setopt($cURLConnection, CURLOPT_RETURNTRANSFER, true);

            // $phoneList = curl_exec($cURLConnection);
            // curl_close($cURLConnection);

            $detail['login_url'] = $login_url;
            $view_data['email_info'] = $detail;

            $message = $this->CI->load->view('email_tem/invite_mail', $view_data, TRUE);
           
            $this->CI->email->from('info@digitverify.com');
            $this->CI->email->to($detail['email_id']);       
            $this->CI->email->subject('Invitation');
            $this->CI->email->message($message);
            
            if($this->CI->email->send())  {
                $email_array[] = $detail['id'];
            }
        }

        if(!empty($mobile_array)) {
            $this->CI->common_model->update_candidates_in(array('is_sms_sent' => STATUS_ACTIVE,'cron_status' => STATUS_ACTIVE),$mobile_array);
        }
        
        if(!empty($email_array)) {
            $this->CI->common_model->update_candidates_in(array('is_mail_sent' => STATUS_ACTIVE,'cron_status' => STATUS_ACTIVE),$email_array);
        }
        return true;
    }

    public function profile_complete() 
    {
        $view_data['email_info'] = $detail;

        $message = $this->CI->load->view('email_tem/profile_complete', $view_data, TRUE);
       
        $this->CI->email->from('info@digitverify.com');
        $this->CI->email->to('info@digitverify.com');
        $this->CI->email->cc('sales@mistitservices.com');
        $this->CI->email->bcc('msrinivas918@gmail.com');
        $this->CI->email->subject('Candidate Complated Profile - Mobile - '.$detail['mobile']);
        $this->CI->email->message($message);
        
        $this->CI->email->send();
        return true;
    }
    
    public function mail_sms()
    {
        $message = 'Welcome';
        $this->CI->email->from('info@digitverify.com');
        $this->CI->email->subject('Discrepancy Raise');
        $this->CI->email->to('msrinivas918@gmail.com');            
        $this->CI->email->message($message);
        $retunr = $this->CI->email->send();
        return $retunr;
    }
}