<?php
class Content_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->db = $this->load->database('default',true);
        $this->tableName = 'content';
    }

    public function insert($fields)
    {
        $this->db->insert($this->tableName, $fields);
        return $this->db->insert_id();
    }

    public function getWhere($where)
    {
        $this->db->where($where);
        $query = $this->db->get($this->tableName);
        return $query->result_array();
    }
}