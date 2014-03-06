<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author Hsinyu
 */
class DB_md extends CI_Model{

    function getCount($params){
        return $this->db->get_where($this->table,$params)->num_rows();
    }

    function get_one($where, $fields = '*')
    {
        
        return $this->db->select($fields)->from($this->table)->where($where)->get()->row_array();
    }

    function insert($params = array()){
        $this->db->insert($this->table,$params);
    }

    function insert_batch($params){
        $this->db->insert_batch($this->table,$params);
    }

    function getData($params = array(),$order = array(),$limit=0,$offset=0){
        // if(!empty($order)){
        //     $this->db->order_by($order[0], $order[1]); 
        // }
        foreach($order as $ok=>$ov):
            $this->db->order_by($ok, $ov); 
        endforeach;
        if($limit!=0 OR $offset!=0){
            $this->db->limit($limit, $offset);
        }
        $data = $this->db->get_where($this->table,$params)->result_array();
        return $data;
    }

    function getLike($params = array(),$order = array(),$limit=0,$offset=0){
        foreach($order as $ok=>$ov):
            $this->db->order_by($ok, $ov); 
        endforeach;
        if($limit!=0 OR $offset!=0){
            $this->db->limit($limit, $offset);
        }
        $data = $this->db->like($params)->get($this->table)->result_array();
        return $data;
    }

    function update($params = array(),$where=array()){
        $this->db->where($where);
        
        return $this->db->update($this->table,$params);
    }

    function delete($params = array()){
        return $this->db->delete($this->table,$params);
    }

    function countAll($table){
        return $this->db->count_all($table);
    }
}

?>
