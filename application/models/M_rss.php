<?php defined('BASEPATH') OR exit('No direct script access allowed');

class M_rss extends CI_Model {
    
    public function __construct(){
        parent::__construct();
        $this->load->database();
    }

    function get_posts($limit){
        $query = $this->db->get('tbl_posts', $limit)->result();
        return $query;
    }
    
}
