<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Product_model extends CI_Model {

    function __construct()
    {
        $this->tableName = 'products';
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

    public function homepage_product($where_array = false)
    {
        $this->db->select("products.*,GROUP_CONCAT(product_images.image_name SEPARATOR '|') as product_images");

        $this->db->from('products');

        $this->db->join("product_images",'product_images.products_id = products.id','left');
        if($where_array)
            $this->db->where($where_array);

        $this->db->group_by('products.id');
        $this->db->limit(4);
        $result = $this->db->get();

        record_db_error($this->db->last_query());
        
        return $result->result_array();
    }

    public function insert_batch_product($arrdata){
        if(!empty($arrdata)) {
            return $this->db->insert_batch('purchare_details' , $arrdata);
        } else {
            return false;
        }
    }
}
?>