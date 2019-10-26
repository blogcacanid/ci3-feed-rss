<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Rss extends CI_Controller{
    
    private $limit = 10;
    
    function __construct(){
        parent::__construct();
	$this->load->helper(array('xml','text'));
        $this->load->model(array('M_rss' => 'rss'));
    }

    public function index(){
        $data = array(
            'encoding'          => 'utf-8',
            'feed_name'         => 'blog.cacan.id',
            'feed_url'          => 'https://blog.cacan.id/feed/',
            'page_description' 	=> 'Blog Cacan',
            'page_language' 	=> 'en-ca',
            'creator_email' 	=> 'blog.cacan.id@gmail.com',
            'posts'             => $this->rss->get_posts($this->limit)
        );
        header("Content-Type: application/rss+xml");
        $this->load->vars($data);
        $this->load->view('v_rss');
    }
    
}
