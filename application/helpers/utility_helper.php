<?php defined('BASEPATH') OR exit('No direct script access allowed');
if ( ! function_exists('show'))
{
  function show($data,$exit = FALSE)
  {
    echo "<pre>";
    print_r($data);
    echo "</pre>";
    if($exit)
    {
      exit();
    }
  }
}

if ( ! function_exists('product_url'))
{
  function product_url($name,$id) {

    $url = SITE_URL.'product/';
    $str = str_replace(' ', '-', $name);
    $str = str_replace("'", '', $str);
    $str = str_replace('(', '', $str);
    $str = str_replace(')', '', $str);
    $str = str_replace('.', '-', $str);
    $str = str_replace(',', '-', $str);
    $str = str_replace('--', '-', $str);
    return $url.$str.'/'.$id;
  }
}

if ( ! function_exists('case_convert'))
{
    function case_convert($array = array(), $conver_to = 'ucwords') {
        $array = array_map('strtolower', $array);
        return array_map($conver_to, $array);
    }
}

if ( ! function_exists('convert_to_single_dimension_array'))
{
  function convert_to_single_dimension_array($multi_dimension_array,$key_from_multi,$value_from_multi)
  { //useful to convert into id=>value pair
      $single_dimension_array = Array();
    foreach ($multi_dimension_array as $multi_dimension)
    {
      $key = $multi_dimension[$key_from_multi];
      $value = $multi_dimension[$value_from_multi];
      $single_dimension_array[$key] = $value;
    }
    return $single_dimension_array;
  }
}

if ( ! function_exists('folder_exist'))
{
  function folder_exist($folder_path)
  {
      return (file_exists($folder_path) AND is_dir($folder_path));
  }
}

if ( ! function_exists('echo_json'))
{
  function echo_json($json_array,$set_csrf_token = TRUE)
  {
    $CI =& get_instance();
    if($set_csrf_token === TRUE)
    {
      $json_array['intersrftkn'] = $CI->security->get_csrf_hash();
    }
    header('Content-Type:application/json');
    echo json_encode($json_array);
    exit;
  }
}

if ( ! function_exists('convert_display_to_db_date'))
{
  function convert_display_to_db_date($str_display_date,$display_date_format = DISPLAY_DATE_FORMATDATE,$db_date_format = DB_ONLY_DATE_FORMAT)
  {
    /* This function is useful when one wants to save form dates to database */
    /* Caution: All Formats should be supported by DateTime::createFromFormat */
    /* Format of $display_date_format must be match with Format of $str_display_date */
    if(empty($db_date_format))
    {
      $db_date_format = DB_ONLY_DATE_FORMAT;
    }
    if(empty($display_date_format))
    {
      $display_date_format = DISPLAY_DATE_FORMATDATE;
    }
    $is_valid_date = FALSE;
    $new_display_date  = DateTime::createFromFormat($display_date_format, $str_display_date);
    if($new_display_date && $new_display_date->format($display_date_format) == $str_display_date)
    {
      $is_valid_date = TRUE;
    }
    if($is_valid_date)
    {
      return $new_display_date->format($db_date_format);
    }
    else
    {
      return NULL;
    }
  }
}

if ( ! function_exists('convert_db_to_display_date'))
{
  function convert_db_to_display_date($str_db_date,$db_date_format = false,$display_date_format = false)
  {
    /* This function is useful when one wants to display dates from db  */
    /* Caution: All Formats should be supported by DateTime::createFromFormat */
    /* Format of $db_date_format must be match with Format of $str_db_date */
    if(empty($db_date_format)) {
        $db_date_format = DB_DATE_FORMAT;
    }
    if(empty($display_date_format)) {
        $display_date_format = DISPLAY_DATE_FORMAT12;
    }
    $is_valid_date = FALSE;
    $new_db_date  = DateTime::createFromFormat($db_date_format, $str_db_date);
    if($new_db_date && $new_db_date->format($db_date_format) == $str_db_date)
    {
      $is_valid_date = TRUE;
    }
    if($is_valid_date)
    {
      return $new_db_date->format($display_date_format);
    }
    else
    {
      return '';
    }
  }
}

if ( ! function_exists('record_db_error'))
{
  function record_db_error($query)
  {
    /*This function writes database error into logs */
    if(ENVIRONMENT !== 'production') {
      return;
    }
    $CI =& get_instance();
    $db_error_info = $CI->db->call_function('error',$CI->db->conn_id);
    if(!$db_error_info)
    {
      return;
    }
    $mysql_error_no = $CI->db->call_function('errno',$CI->db->conn_id);
    $backtrace_array = debug_backtrace(FALSE);
    $backtrace_array = $backtrace_array[1];
    $file = $backtrace_array['file'];
    $line = $backtrace_array['line'];
    $message = sprintf("DB Error(%s) %s occurred at  %s -> %s with query:%s",$mysql_error_no,$db_error_info,$backtrace_array['class'],$backtrace_array['function'],$query);
    $CI->lib_log->error_handler('db',$message,$file,$line);
  }
}

if ( ! function_exists('create_password'))
{
  function create_password($password)
  {
    $options = ['cost' => 12];
      return password_hash($password, PASSWORD_BCRYPT, $options);
  }
}
if ( ! function_exists('encrypt'))
{
  function encrypt($id)
  {
      $CI =& get_instance();
      return $CI->encrypt->encode($id);
  }
}
if ( ! function_exists('decrypt'))
{
  function decrypt($encrypted_id)
  {
      $CI =& get_instance();
      return $CI->encrypt->decode($encrypted_id);
  }
}
function get_date_from_timestamp($value)
{
  if($value != "" && $value != "NA" && $value != "WIP")
  {
    return date('Y-m-d',strtotime($value));
  }
  else
  {
    return "";
  }
}