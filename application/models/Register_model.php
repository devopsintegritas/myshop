<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Register_model extends CI_Model {

    function __construct()
    {
        $this->tableName = 'user_details';
        $this->primaryKey = 'id';
    }

    public function select($return_as_strict_row,$select_array, $where_array=array())
    {
        $this->db->select($select_array);
        $this->db->from($this->tableName);
        if(!empty($where_array)) {
           $this->db->where($where_array); 
        }
        $result_array = $this->db->get()->result_array();   
        if($return_as_strict_row)
        {
            if(count($result_array)==1) // ensure only one record has been previously inserted
            {
                $result_array  = $result_array[0];
            }
        }
        return $result_array;
    }

    public function save($arrdata, $arrwhere = array())
    {
        if(!empty($arrwhere))
        {
            foreach ($arrwhere as $field => $value) 
            {
                $this->db->where($field,$value);    
            }
            return $this->db->update($this->tableName, $arrdata);
        }
        else
        {
            $this->db->insert($this->tableName, $arrdata);    
            return $this->db->insert_id();
        }
    }

    public function delete($arrwhere)
    {
        return $this->db->delete($this->tableName, $arrwhere);    
    }

}
?>