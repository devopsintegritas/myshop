<?php defined('BASEPATH') OR exit('No direct script access allowed');
class MY_Controller extends CI_Controller {

    var $cookie_secret_key  = 'p8I22o7zC73hS2YQ9sRlb2Eew1yTB15Vx';
    var $cookie_admin_secret_key  = 'p8I6o6zC73hS2YQ9sRlb2Eew1yTB15Vx';
    var $login_info = array();

    public function __construct()
    {
        parent::__construct();
        
        $this->load->helper(array('cookie'));
        $this->load->model('common_model');
        $this->session->set_userdata('last_url',current_url());
        $this->login_info = $this->session->userdata('admin');
    }

    
    protected function is_admin_logged_in()
    {
        $is_admin_logged_in = FALSE;
        if(!$this->verify_admin_login_cookie())
        {
            $this->logout_admin();
            return $is_admin_logged_in;
        }
        $admin_data = $this->session->userdata('admin');
        if($admin_data)
        {
           $id =  $admin_data['id'];
           $logged_in =  (bool) $admin_data['admin_logged_in'];
           $is_admin_logged_in  = ($id AND $logged_in);
        }
        return $is_admin_logged_in;
    }
   
    protected function logout_admin()
    {
        delete_cookie('smember');
        if(!empty($this->session->userdata())) {
            $this->session->unset_userdata(array('admin'));
        }
    }

    protected function encrypt($str)
    {
        return $this->encryption->encrypt($str);
    }

    protected function decrypt($encrypted_str)
    {
        return $this->encryption->decrypt($encrypted_str);
    }

    protected function get_login_cookie()
    {
        $cookie_name = 'member';
        if(!$this->verify_login_cookie()) {
            return "";
        }

        list($encrypted_cookie,$hash) = explode(':', get_cookie($cookie_name));
        $salt= $this->session->userdata('ctkn');
        $salt = base64_decode($salt);
        $decrypted_cookie = $this->encrypt->decode($encrypted_cookie, $this->cookie_secret_key);
        return unserialize($decrypted_cookie);
    }

    protected function get_admin_login_cookie()
    {
        $cookie_name = 'smember';
        if(!$this->verify_admin_login_cookie())
        {
            return "";
        }
        list($encrypted_cookie,$hash) = explode(':', get_cookie($cookie_name));
        $admin_data = $this->session->userdata('admin');
        $salt= $admin_data['catkn'];
        $salt = base64_decode($salt);
        $decrypted_cookie = $this->encrypt->decode($encrypted_cookie, $this->cookie_admin_secret_key);
        return unserialize($decrypted_cookie);
    }

    
    protected  function verify_admin_login_cookie()
    {
        $verified = FALSE;
        $cookie_name = 'smember';
        $admin_data = $this->session->userdata('admin');
        if(empty($admin_data)) {
            return $verified;
        }
        $session_salt = $admin_data['catkn'];
        if(empty($session_salt)) {
            return $verified;
        }
        if(is_array(explode(':', get_cookie($cookie_name)))) {
            list($encrypted_cookie,$hash) = explode(':', get_cookie($cookie_name));
            if(!empty($hash)) {
                $session_salt = base64_decode($session_salt);
                if($hash == hash_hmac('sha256', $encrypted_cookie, $session_salt)) {
                    $verified = TRUE;
                }
            }
        }
        return $verified;
    }

    protected function set_login_cookie($cookie_values)
    {
        $cookie_name = 'member';
        $cookie_value = serialize($cookie_values);
        $encrypted_cookie = $this->encrypt($cookie_value,$this->cookie_secret_key);
        $salt = uniqid(mt_rand(), true);
        $encrypted_cookie .= ':'.hash_hmac('sha256', $encrypted_cookie, $salt);
        $name   = $cookie_name;
        $value  = $encrypted_cookie;
        $expire = time()+86500;
        $path  = '/';
        setcookie($name,$value,$expire,$path);
        $this->session->set_userdata(array(
                            'ctkn' => base64_encode($salt)
        ));
    }

    protected function set_admin_login_cookie($cookie_values)
    {
        delete_cookie('smember');
        $cookie_name = 'smember';
        $cookie_value = serialize($cookie_values);
        $encrypted_cookie = $this->encrypt($cookie_value,$this->cookie_secret_key);
        $salt = uniqid(mt_rand(), true);
        $encrypted_cookie .= ':'.hash_hmac('sha256', $encrypted_cookie, $salt);
        $expire = time()+86500;
        $path  = '/';
        $a = setcookie($cookie_name,$encrypted_cookie,$expire,$path);
        $admin_data = $this->session->userdata('admin');
        $admin_data['catkn'] =  base64_encode($salt);
        $this->session->set_userdata(array('admin' => $admin_data));
    }
}
class MY_User_Controller extends CI_Controller {

    var $cands_session;
    var $cookie_secret_key  = 't9I22o7zC73hS2YQ9sRlb2Eew1yUB15Qx';

    public function __construct()
    {
        parent::__construct();
        $this->load->helper('cookie');
        $this->cands_session = $this->session->userdata('cands_session');
    }
    
    protected  function verify_cands_cookie()
    {
        $cookie_name = 'cands_session';
        return get_cookie($cookie_name);
    }

    protected function logout_cands()
    {
        if(!empty($this->session->userdata())) {
            $this->session->unset_userdata('cands_session');
        }
        delete_cookie('cands_session');
    }

    protected function is_cands_logged_in()
    {
        $is_cands_logged_in = FALSE;
        if(!$this->verify_cands_cookie())
        {
            $this->logout_cands();
            return $is_cands_logged_in;
        }
        $cands_session_data = $this->session->userdata('cands_session');
        if($cands_session_data)
        {
           $id =  $cands_session_data['id'];
           $logged_in =  (bool) $cands_session_data['logged_in'];
           $is_cands_logged_in  = ($id AND $logged_in);
        }
        return $is_cands_logged_in;
    }

    protected function encrypt($str)
    {
        return $this->encryption->encrypt($str);
    }

    protected function decrypt($encrypted_str)
    {
        return $this->encryption->decrypt($encrypted_str);
    }

    protected function set_user_login_cookie($cookie_values)
    {
        delete_cookie('usermember');
        $cookie_name = 'usermember';
        $cookie_value = serialize($cookie_values);
        $encrypted_cookie = $this->encrypt($cookie_value,$this->cookie_secret_key);
        $salt = uniqid(mt_rand(), true);
        $encrypted_cookie .= ':'.hash_hmac('sha256', $encrypted_cookie, $salt);
        $expire = time()+86500;
        $path  = '/';
        $a = setcookie($cookie_name,$encrypted_cookie,$expire,$path);
        $admin_data = $this->session->userdata('user');
        $admin_data['catkn'] =  base64_encode($salt);
        $this->session->set_userdata(array('user' => $admin_data));
    }

    protected function is_user_logged_in()
    {
        if(!$this->verify_login_cookie())
        {
            $this->logout_user();
            return FALSE;
        }
        $id =  $this->session->userdata('id');
        $logged_in =  (bool) $this->session->userdata('logged_in');
        return ($id AND $logged_in);
    }

    protected  function verify_login_cookie()
    {
        $verified = FALSE;
        $hash = FALSE;
        $cookie_name = 'usermember';
        $session_salt = $this->session->userdata('catkn');

        if(empty($session_salt)) {
            return $verified;
        }
        list($encrypted_cookie,$hash) = explode(':', get_cookie($cookie_name));
        if(!empty($hash)) {
            $session_salt = base64_decode($session_salt);
            if($hash == hash_hmac('sha256', $encrypted_cookie, $session_salt)) {
               $verified = TRUE;
            }
        }
        return $verified;
    }

    protected function logout_user()
    {
        if(!empty($this->session->userdata())) {
            $this->session->unset_userdata('user');
        }
        delete_cookie('usermember');
    }
}