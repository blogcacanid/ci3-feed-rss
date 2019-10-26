<?php defined('BASEPATH') OR exit('No direct script access allowed');

class M_Bidang extends CI_Model {
    
    var $table = 'bidang';
    var $pkey = 'bidang_id';
    var $select_list = "b.bidang_id,d.departemen_id,d.nama_departemen,b.nama_bidang,b.add_date";
    var $select_view = "b.bidang_id,d.departemen_id,d.nama_departemen,b.nama_bidang,b.add_by,b.add_date,b.edit_by,b.edit_date";
    var $select_export = "d.nama_departemen,b.nama_bidang,b.add_by,b.add_date,b.edit_by,b.edit_date";
    var $order_default = "d.level_departemen ASC, d.nama_departemen ASC, b.nama_bidang ASC";

    public function __construct(){
        parent::__construct();
        $this->load->database();
    }

    function _get_where() {
        if (!empty($this->input->get('x_departemen_id', true))) { $this->dba->where('d.departemen_id',$this->input->get('x_departemen_id', true)); }
        if (!empty($this->input->get('x_nama_bidang', true))) { $this->dba->like('b.nama_bidang',$this->input->get('x_nama_bidang', true)); }
        if (!empty($this->input->get('x_add_by', true))) { $this->dba->where('b.add_by',$this->input->get('x_add_by', true)); }
        if (!empty($this->input->get('x_add_date', true))) { $this->dba->where('Date(b.add_date)',$this->input->get('x_add_date', true)); }
        if (!empty($this->input->get('x_edit_by', true))) { $this->dba->where('b.edit_by',$this->input->get('x_edit_by', true)); }
        if (!empty($this->input->get('x_edit_date', true))) { $this->dba->where('Date(b.edit_date)',$this->input->get('x_edit_date', true)); }
    }
    
    function _get_order() {
        $this->dba->order_by($this->order_default, FALSE);
    }

    function _get_list_combo($departemen_id) {
        $this->dba = $this->load->database('dba', true);
        $this->dba->select("bidang_id, nama_bidang");
        $this->dba->where('departemen_id', $departemen_id);
        $this->dba->order_by('nama_bidang ASC');
        $result = $this->dba->get($this->table)->result(); // Tampilkan semua data bidang berdasarkan id departemen
        return $result; 
    }

    function _get_list_query() {
        $this->dba = $this->load->database('dba', true);
        $this->dba->select($this->select_list, FALSE);
        $this->dba->join('departemen d','b.departemen_id = d.departemen_id','inner');        
    }

    function _get_list_num_rows() {
        $this->_get_list_query();
        $result = $this->dba->count_all_results($this->table.' b');
        return $result;
    }

    function get_list($limit, $offset) {
        if ($offset > 0) {
            $offset = ($offset - 1) * $limit;
        }
        $this->_get_list_query();
        $this->_get_order();
        $result['rows'] = $this->dba->get($this->table.' b', $limit, $offset);
        $result['num_rows'] = $this->_get_list_num_rows();
        return $result;
    }

    // SEARCH
    function _get_search_query() {
        $this->_get_list_query();
        if (!empty($this->input->get('search_by', true))) { 
            if ($this->input->get('search_by') == 1){
                $this->dba->like('d.nama_departemen',$this->input->get('keywords', false)); 
            }elseif ($this->input->get('search_by') == 2){
                $this->dba->like('b.nama_bidang',$this->input->get('keywords', false)); 
            }
        }
    }

    function _get_search_num_rows() {
        $this->_get_search_query();
        $result = $this->dba->count_all_results($this->table.' b');
        return $result;
    }

    function get_search($limit, $offset) {
        if ($offset > 0) {
            $offset = ($offset - 1) * $limit;
        }
        $this->_get_search_query();
        $this->_get_order();
        $result['rows'] = $this->dba->get($this->table.' b', $limit, $offset);
        $result['num_rows'] = $this->_get_search_num_rows();
        return $result;
    }

    // ASEARCH
    function _get_asearch_query() {
        $this->_get_list_query();
        $this->_get_where();
    }

    function _get_asearch_num_rows() {
        $this->dba = $this->load->database('dba', true);
        $this->_get_asearch_query();
        $result = $this->dba->count_all_results($this->table.' b');
        return $result;
    }

    function get_asearch($limit, $offset) {
        if ($offset > 0) {
            $offset = ($offset - 1) * $limit;
        }
        $this->_get_asearch_query();
        $this->_get_order();
        $result['rows'] = $this->dba->get($this->table.' b', $limit, $offset);
        $result['num_rows'] = $this->_get_asearch_num_rows();
        return $result;
    }

    function _get_export_query() {
        $this->dba = $this->load->database('dba', true);
        $this->dba->select($this->select_export, FALSE);
        $this->dba->join('departemen d','b.departemen_id = d.departemen_id','inner');        
        $this->_get_where();
        $this->_get_order();
    }

    function export_xls(){
        $this->_get_export_query();
        $query = $this->dba->get($this->table.' b');
        if($query->num_rows() > 0)
            return $query->result_array();
        else
            return null;
    }

    function export_csv(){
        $this->_get_export_query();
        $query = $this->dba->get($this->table.' b');
        return $query;
    }

    function export_pdf(){
        $this->_get_export_query();
        $query = $this->dba->get($this->table.' b');
        return $query->result();
    }

    public function add($data){
        $this->dba = $this->load->database('dba', true);
        $this->dba->insert($this->table, $data);
        return $this->dba->insert_id();
    }

    function valid_unique($fields,$value){
        $this->dba = $this->load->database('dba', true);
        $query = $this->dba->get_where($this->table, array($fields => $value));
        if ($query->num_rows() > 0){
            return TRUE;
        }else{
            return FALSE;
        }
    }

    public function get_by_id($id){
        $this->dba = $this->load->database('dba', true);
        $this->dba->select($this->select_view, FALSE);
        $this->dba->join('departemen d','b.departemen_id = d.departemen_id','inner');        
        $this->dba->from($this->table.' b');
        $this->dba->where($this->pkey,$id);
        $query = $this->dba->get();
        return $query->row();
    }

    public function edit($where, $data){
        $this->dba = $this->load->database('dba', true);
        $this->dba->update($this->table, $data, $where);
        return $this->dba->affected_rows();
    }

    public function delete_by_id($id){
        $this->dba = $this->load->database('dba', true);
        $this->dba->where($this->pkey, $id);
        $this->dba->delete($this->table);
    }




    
}
