<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Common_model extends CI_Model {

    public function select($tableName, $return_as_strict_row,$select_array, $where_array=array(),$order_by = false)
    {
        $this->db->select($select_array);
        $this->db->from($tableName);
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

    public function save($tableName,$arrdata, $arrwhere = array())
    {
        if(!empty($arrwhere))
        {
            foreach ($arrwhere as $field => $value) 
            {
                $this->db->where($field,$value);    
            }
            return $this->db->update($tableName, $arrdata);
        }
        else
        {
            $this->db->insert($tableName, $arrdata);    
            return $this->db->insert_id();
        }
    }

    public function delete($tableName, $arrwhere)
    {
        return $this->db->delete($tableName, $arrwhere);    
    }
}
?>