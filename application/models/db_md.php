<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author Hsinyu
 */
class DB_md extends CI_Model{
	
	function getCount($table,$params){
		return $this->db->get_where($table,$params)->num_rows();
	}

    function get_one($table, $where, $fields = '*')
    {
        
        return $this->db->select($fields)->from($table)->where($where)->get()->row_array();
    }

    function insert($table,$params = array()){
        $this->db->insert($table,$params);
    }

    function getData($table,$params = array(),$order = array(),$limit=0,$offset=0){
        if(!empty($order)){
            $this->db->order_by($order[0], $order[1]); 
        }
        if($limit!=0 OR $offset!=0){
            $this->db->limit($limit, $offset);
        }
        $data = $this->db->get_where($table,$params)->result_array();
        return $data;
    }

    function update($table,$params = array(),$where=array()){
        $this->db->where($where);
        
        return $this->db->update($table,$params);
    }

    function delete($table,$params = array()){
        return $this->db->delete($table,$params);
    }

    function useSql($sql){
        $this->db->query($sql);
        // return $data;
    }

    function countAll($table){
        return $this->db->count_all($table);
    }
}

?>
