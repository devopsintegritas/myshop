<?php defined('BASEPATH') OR exit('No direct script access allowed.');
class File_upload {
    protected $CI;
    public function __construct()
    {
        $this->CI =& get_instance();
    }

    public function file_uplod($config_array)
    {
        $msgs = array();
        if(!folder_exist($config_array['file_upload_path'])) {
            mkdir($config_array['file_upload_path'],0777);
        } else if(!is_writable($config_array['file_upload_path'])) {
            $msgs['status'] = FALSE;
            $msgs['message'] = 'Problem while uploading';
        }

        if($config_array['file_data']['name']) 
        {
            $file_name = $config_array['file_data']['name'];
            $file_info  = pathinfo($file_name);
            $new_file_name = preg_replace('/[[:space:]]+/', '_', $file_info['filename']);
            $new_file_name = str_replace('.','_',$new_file_name.'_'.DATE(UPLOAD_FILE_DATE_FORMAT));
            $new_file_name = $new_file_name.'.'.strtolower($file_info['extension']);
            $_FILES['attchment']['name'] = $new_file_name;
            $_FILES['attchment']['tmp_name'] = $config_array['file_data']['tmp_name'];
            $_FILES['attchment']['error'] = $config_array['file_data']['error'];
            $_FILES['attchment']['size'] = $config_array['file_data']['size'];
            $config['upload_path'] = $config_array['file_upload_path'];
            $config['file_name'] = $new_file_name;
            $config['allowed_types'] = $config_array['file_permission'];
            $config['file_ext_tolower'] = TRUE;
            $config['remove_spaces'] = TRUE;
            $config['maintain_ratio'] = TRUE;
            if(isset($config_array['resize']) && !empty($config_array['resize']))
            {
                $config['width']    = $config_array['resize']['width'];
                $config['height']   = $config_array['resize']['height'];
            }
            $config['max_size'] = $config_array['file_size'];
            $this->CI->load->library('upload',$config);
            $this->CI->upload->initialize($config);
            if($this->CI->upload->do_upload('attchment')) {
                $msgs['status'] = TRUE;
                $msgs['message'] = 'Successfully Uploaded';
                $msgs['file_name'] = $new_file_name;
            } else {   
                $msgs['status'] = FALSE;
                $msgs['message'] = $this->CI->upload->display_errors('','');
            }
        }
        else 
        {
            $msgs['status'] = FALSE;
            $msgs['message'] = 'Uplaod File';
        }
        return $msgs;
    }

    public function file_upload_multiple($config_array)
    {
        $file_array = $error_msgs =array();
        for($i = 0; $i < $config_array['files_count']; $i++)
        {
            if($config_array['file_data']['name'][$i]) 
            {
                $file_name = $config_array['file_data']['name'][$i];
                $file_info  = pathinfo($file_name);
                $new_file_name = preg_replace('/[[:space:]]+/', '_', $file_info['filename']);
                $new_file_name = $new_file_name.'_'.DATE(UPLOAD_FILE_DATE_FORMAT);
                $new_file_name = strtolower(str_replace('.','_',$new_file_name));
                $file_extension = $file_info['extension'];
                $new_file_name = $new_file_name.'.'.strtolower($file_extension);
                $_FILES['attchment']['name'] = $new_file_name;
                $_FILES['attchment']['tmp_name'] = $config_array['file_data']['tmp_name'][$i];
                $_FILES['attchment']['error'] = $config_array['file_data']['error'][$i];
                $_FILES['attchment']['size'] = $config_array['file_data']['size'][$i];
                $config['upload_path'] = $config_array['file_upload_path'];
                $config['file_name'] = $new_file_name;
                $config['allowed_types'] = $config_array['file_permission'];
                $config['file_ext_tolower'] = TRUE;
                $config['remove_spaces'] = TRUE;
                $config['max_size'] = $config_array['file_size'];
                $this->CI->load->library('upload',$config);
                $this->CI->upload->initialize($config);
                if($this->CI->upload->do_upload('attchment'))
                {
                    array_push($file_array,array(
                            'og_name'=> $file_name,
                            'name'=> $new_file_name,
                            $config_array['component_name'] => $config_array['file_id'])
                    );
                }
                else
                {   
                    array_push($error_msgs,$this->CI->upload->display_errors('',''));
                }
            }
        }
        return array('success'=>$file_array,'fail'=>$error_msgs);
    }

    public function single_file_upload_with_types($config_array)
    {
        $msgs = array();

        if($config_array['file_data']['name']) 
        {
            $file_name = $config_array['file_data']['name'];
            $file_info  = pathinfo($file_name);
            $new_file_name = preg_replace('/[[:space:]]+/', '_', $file_info['filename']);
            $new_file_name = $new_file_name.'_'.DATE(UPLOAD_FILE_DATE_FORMAT);
            $new_file_name = strtolower(str_replace('.','_',$new_file_name));
            $file_extension = strtolower($file_info['extension']);
            $new_file_name = $new_file_name.'.'.$file_extension;
            $_FILES['attchment']['name'] = $new_file_name;
            $_FILES['attchment']['tmp_name'] = $config_array['file_data']['tmp_name'];
            $_FILES['attchment']['error'] = $config_array['file_data']['error'];
            $_FILES['attchment']['size'] = $config_array['file_data']['size'];
            $config['upload_path'] = $config_array['file_upload_path'];
            $config['file_name'] = $new_file_name;
            $config['allowed_types'] = $config_array['file_permission'];
            $config['file_ext_tolower'] = TRUE;
            $config['remove_spaces'] = TRUE;
            $config['max_size'] = $config_array['file_size'];
            $this->CI->load->library('upload',$config);
            $this->CI->upload->initialize($config);
            if($this->CI->upload->do_upload('attchment'))
            {
                $gbr = $this->CI->upload->data();
                $this->_create_thumbs($gbr['file_name'],$config_array['file_upload_path']);

                $msgs['status'] = TRUE;
                $msgs['message'] = 'Successfully Uploaded';
                $msgs['og_name'] = $file_name;
                $msgs['name'] = $new_file_name;
            }
            else {   
                $msgs['status'] = FALSE;
                $msgs['message'] = $this->CI->upload->display_errors('','');
            }
        
        }
        return $msgs;
    }

    public function file_upload_with_types($config_array)
    {
        $file_array = $error_msgs =array();
        for($i = 0; $i < $config_array['files_count']; $i++)
        {
            if($config_array['file_data']['name'][$i]) 
            {
                $file_name = $config_array['file_data']['name'][$i];
                $file_info  = pathinfo($file_name);
                $new_file_name = preg_replace('/[[:space:]]+/', '_', $file_info['filename']);
                $new_file_name = $new_file_name.'_'.DATE(UPLOAD_FILE_DATE_FORMAT);
                $new_file_name = strtolower(str_replace('.','_',$new_file_name));
                $file_extension = strtolower($file_info['extension']);
                $new_file_name = $new_file_name.'.'.$file_extension;
                $_FILES['attchment']['name'] = $new_file_name;
                $_FILES['attchment']['tmp_name'] = $config_array['file_data']['tmp_name'][$i];
                $_FILES['attchment']['error'] = $config_array['file_data']['error'][$i];
                $_FILES['attchment']['size'] = $config_array['file_data']['size'][$i];
                $config['upload_path'] = $config_array['file_upload_path'];
                $config['file_name'] = $new_file_name;
                $config['allowed_types'] = $config_array['file_permission'];
                $config['file_ext_tolower'] = TRUE;
                $config['remove_spaces'] = TRUE;
                $config['max_size'] = $config_array['file_size'];
                $this->CI->load->library('upload',$config);
                $this->CI->upload->initialize($config);
                if($this->CI->upload->do_upload('attchment'))
                {
                    $gbr = $this->CI->upload->data();
                    $this->_create_thumbs($gbr['file_name'],$config_array['file_upload_path']);
                    array_push($file_array,array(
                            'og_name'=> $file_name,
                            'name'=> $new_file_name,
                            $config_array['component_name'] => $config_array['file_id'])
                    );
                }
                else
                {   
                    array_push($error_msgs,$this->CI->upload->display_errors('',''));
                }
            }
        }
        return array('success'=>$file_array,'fail'=>$error_msgs);
    }
    protected function _create_thumbs($file_name,$path){
        $config = array(
            // Large Image
            array(
                'image_library' => 'GD2',
                'source_image'  => $path.$file_name,
                'maintain_ratio'=> FALSE,
                'width'         => 700,
                'height'        => 467,
                'new_image'     => $path.'/mobile/'.$file_name
                ),
            // Medium Image
            array(
                'image_library' => 'GD2',
                'source_image'  => $path.$file_name,
                'maintain_ratio'=> FALSE,
                'width'         => 200,
                'height'        => 125,
                'new_image'     => $path.'/thumb/'.$file_name
                ),
            // Small Image
            array(
                'image_library' => 'GD2',
                'source_image'  => $path.$file_name,
                'maintain_ratio'=> FALSE,
                'width'         => 100,
                'height'        => 67,
                'new_image'     => $path.'/medium/'.$file_name
            ));
        $this->CI->load->library('image_lib', $config[0]);
        foreach ($config as $item){
            $this->CI->image_lib->initialize($item);
            if(!$this->CI->image_lib->resize()){
                return false;
            }
            $this->CI->image_lib->clear();
        }
    }
}