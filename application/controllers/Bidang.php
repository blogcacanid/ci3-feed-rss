<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Bidang extends CI_Controller{
    
    private $limit = 10;
    var $module_top_title = 'Master Data Organisasi';
    var $module_title = 'Bidang';
    var $module_name = 'bidang';
    var $module_menu = 'Bidang';
    
    function __construct(){
        parent::__construct();

        $this->load->library('fpdf');
        $this->load->model(array(
                'M_Bidang'      => 'bidang',
                'M_Departemen'  => 'departemen'
            ));
        $this->load->library('tank_auth_groups','','tank_auth');
        if (!$this->tank_auth->is_logged_in()) {
            redirect('/auth/login/');
        }
    }

    function index($offset=0){
        if (!$this->tank_auth->is_logged_in()){
            redirect('/auth/login/');
        }else{
            $this->session->set_userdata('EW_BACK_URL'.$this->module_menu, current_full_url());
            $data = array(
                    'pagetoptitle'  => $this->module_top_title,
                    'pagetitle'     => $this->module_title,
                    'halaman'       => $this->module_name,
                    'module_menu'   => $this->module_menu,
                    'menu_active'   => 'bidang',
                    'list_icon'     => 'fa fa-bezier-curve',
                    'form_action1'  => site_url($this->module_name.'/search'),
                    'form_action2'  => site_url($this->module_name.'/a_search'),
                );
            $data['departemen'] = $this->departemen->_get_list_combo(); // combo departemen
            $result = $this->bidang->get_list($this->limit, $offset);
            $data['sSQL'] = $result['rows'];
            $data['num_results'] = $result['num_rows'];
            // load pagination library
            $this->load->library('pagination');
            $config = array(
                'base_url'          => site_url($this->module_name.'/index'),
                'total_rows'        => $data['num_results'],
                'per_page'          => $this->limit,
                'uri_segment'       => 3,
                'use_page_numbers'  => TRUE,
                'num_links'         => 5,
                'first_link'        => '<i class="fa fa-fast-backward" aria-hidden="true"></i>',
                'last_link'         => '<i class="fa fa-fast-forward" aria-hidden="true"></i>',
                'next_link'         => '<i class="fa fa-forward" aria-hidden="true"></i>',
                'prev_link'         => '<i class="fa fa-backward" aria-hidden="true"></i>',
                // Menyesuaikan untuk Twitter Bootstrap 3.2.0.
                'full_tag_open'     => '<ul class="pagination pagination-sm m-0 float-right">',
                'full_tag_close'    => '</ul>',
                'num_tag_open'      => '<li class="page-item page-link">',
                'num_tag_close'     => '</li>',
                'cur_tag_open'      => '<li class="disabled"><li class="active"><a class="page-link" href="#">',
                'cur_tag_close'     => '<span class="sr-only"></span></a></li>',
                'next_tag_open'     => '<li class="page-item page-link">',
                'next_tag_close'    => '</li>',
                'prev_tag_open'     => '<li class="page-item page-link">',
                'prev_tag_close'    => '</li>',
                'first_tag_open'    => '<li class="page-item page-link">',
                'first_tag_close'   => '</li>',
                'last_tag_open'     => '<li class="page-item page-link">',
                'last_tag_close'    => '</li>',
            );
            $this->pagination->initialize($config);
            $data['pagination'] = $this->pagination->create_links();
            $this->load->view('design/header', $data);
            $this->load->view('design/sidebar', $data);
            $this->load->view($this->module_name.'/list', $data);
            $this->load->view('design/footer');
        }
    }
    
    function search($offset=0){
        if (!$this->tank_auth->is_logged_in()) {
            redirect('/auth/login/');
        } else{
            $this->session->set_userdata('EW_BACK_URL'.$this->module_menu, current_full_url());
            $data = array(
                    'pagetoptitle'  => $this->module_top_title,
                    'pagetitle'     => $this->module_title,
                    'halaman'       => $this->module_name,
                    'module_menu'   => $this->module_menu,
                    'menu_active'   => 'bidang',
                    'list_icon'     => 'fa fa-bezier-curve',
                    'form_action1'  => site_url($this->module_name.'/search'),
                    'form_action2'  => site_url($this->module_name.'/a_search'),
                );
            $data['departemen'] = $this->departemen->_get_list_combo(); // combo departemen
            $result = $this->bidang->get_search($this->limit, $offset);
            $data['sSQL'] = $result['rows'];
            $data['num_results'] = $result['num_rows'];
            // load pagination library
            $this->load->library('pagination');
            $config = array(
                'base_url'          => site_url($this->module_name.'/search'),
                'total_rows'        => $data['num_results'],
                'per_page'          => $this->limit,
                'uri_segment'       => 3,
                'use_page_numbers'  => TRUE,
                'num_links'         => 5,
                'first_link'        => '<i class="fa fa-fast-backward" aria-hidden="true"></i>',
                'last_link'         => '<i class="fa fa-fast-forward" aria-hidden="true"></i>',
                'next_link'         => '<i class="fa fa-forward" aria-hidden="true"></i>',
                'prev_link'         => '<i class="fa fa-backward" aria-hidden="true"></i>',
                // Menyesuaikan untuk Twitter Bootstrap 3.2.0.
                'full_tag_open'     => '<ul class="pagination pagination-sm m-0 float-right">',
                'full_tag_close'    => '</ul>',
                'num_tag_open'      => '<li class="page-item page-link">',
                'num_tag_close'     => '</li>',
                'cur_tag_open'      => '<li class="disabled"><li class="active"><a class="page-link" href="#">',
                'cur_tag_close'     => '<span class="sr-only"></span></a></li>',
                'next_tag_open'     => '<li class="page-item page-link">',
                'next_tag_close'    => '</li>',
                'prev_tag_open'     => '<li class="page-item page-link">',
                'prev_tag_close'    => '</li>',
                'first_tag_open'    => '<li class="page-item page-link">',
                'first_tag_close'   => '</li>',
                'last_tag_open'     => '<li class="page-item page-link">',
                'last_tag_close'    => '</li>',
            );
            if (count($_GET) > 0) {
                $config['suffix'] = '?' . http_build_query($_GET, '', "&");
            }
            $config['first_url'] = $config['base_url'] . '?' . http_build_query($_GET);
            $this->pagination->initialize($config);
            $data['pagination'] = $this->pagination->create_links();
            $this->load->view('design/header');
            $this->load->view('design/sidebar', $data);
            $this->load->view($this->module_name.'/list',$data);
            $this->load->view('design/footer');
        }
    }

    function a_search($offset=0){
        if (!$this->tank_auth->is_logged_in()){
            redirect('/auth/login/');
        }else{
            $this->session->set_userdata('EW_BACK_URL'.$this->module_menu, current_full_url());
            $data = array(
                    'pagetoptitle'  => $this->module_top_title,
                    'pagetitle'     => $this->module_title,
                    'halaman'       => $this->module_name,
                    'module_menu'   => $this->module_menu,
                    'menu_active'   => 'bidang',
                    'list_icon'     => 'fa fa-list',
                    'form_action1'  => site_url($this->module_name.'/search'),
                    'form_action2'  => site_url($this->module_name.'/a_search'),
                );
            $data['departemen'] = $this->departemen->_get_list_combo(); // combo departemen
            if ((!empty($this->input->get('x_departemen_id', true))) || (!empty($this->input->get('x_nama_bidang', true))) ||
                    (!empty($this->input->get('x_add_by', true))) || (!empty($this->input->get('x_add_date', true)))||
                    (!empty($this->input->get('x_edit_by', true))) || (!empty($this->input->get('x_edit_date', true))) ) {
                if ($this->input->get('x_option', true) == 1) { // View Record
                    $result = $this->bidang->get_asearch($this->limit, $offset);
                    $data['sSQL'] = $result['rows'];
                    $data['num_results'] = $result['num_rows'];
                    // load pagination library
                    $this->load->library('pagination');
                    $config = array(
                        'base_url'          => site_url($this->module_name.'/a_search'),
                        'total_rows'        => $data['num_results'],
                        'per_page'          => $this->limit,
                        'uri_segment'       => 3,
                        'use_page_numbers'  => TRUE,
                        'num_links'         => 5,
                        'first_link'        => '<i class="fa fa-fast-backward" aria-hidden="true"></i>',
                        'last_link'         => '<i class="fa fa-fast-forward" aria-hidden="true"></i>',
                        'next_link'         => '<i class="fa fa-forward" aria-hidden="true"></i>',
                        'prev_link'         => '<i class="fa fa-backward" aria-hidden="true"></i>',
                        // Menyesuaikan untuk Twitter Bootstrap 3.2.0.
                        'full_tag_open'     => '<ul class="pagination pagination-sm m-0 float-right">',
                        'full_tag_close'    => '</ul>',
                        'num_tag_open'      => '<li class="page-item page-link">',
                        'num_tag_close'     => '</li>',
                        'cur_tag_open'      => '<li class="disabled"><li class="active"><a class="page-link" href="#">',
                        'cur_tag_close'     => '<span class="sr-only"></span></a></li>',
                        'next_tag_open'     => '<li class="page-item page-link">',
                        'next_tag_close'    => '</li>',
                        'prev_tag_open'     => '<li class="page-item page-link">',
                        'prev_tag_close'    => '</li>',
                        'first_tag_open'    => '<li class="page-item page-link">',
                        'first_tag_close'   => '</li>',
                        'last_tag_open'     => '<li class="page-item page-link">',
                        'last_tag_close'    => '</li>',
                    );
                    if (count($_GET) > 0) {
                        $config['suffix'] = '?' . http_build_query($_GET, '', "&");
                    }
                    $config['first_url'] = $config['base_url'] . '?' . http_build_query($_GET);
                    $this->pagination->initialize($config);
                    $data['pagination'] = $this->pagination->create_links();
                    $this->load->view('design/header', $data);
                    $this->load->view('design/sidebar', $data);
                    $this->load->view($this->module_name.'/a_search', $data);
                    $this->load->view('design/footer');
                }elseif ($this->input->get('x_option', true) == 2) { // Export to XLS
                    $filename = 'xls-'.$this->module_menu.'_'.date('Ymd').'-'.date('His');
                    $query['exportfile'] = $filename.'.xls';
                    $query['records'] = $this->bidang->export_xls();
                        $this->load->view($this->module_name.'/xls_view',$query);
                }elseif ($this->input->get('x_option', true) == 3) { // Export CSV
                    $filename = 'csv-'.$this->module_menu.'_'.date('Ymd').'-'.date('His');
                    $list = $this->bidang->export_csv();
                    $data[0] = array('No.','Nama Departemen','Nama Bidang','Add By','Add Date','Edit By','Edit Date');
                    $i=0;
                    $data = array();
                    foreach ($list->result() as $row) {      
                        $data[++$i] = array(
                            $i,
                            list_char_export($row->nama_departemen),
                            list_char_export($row->nama_bidang),
                            list_char_export($row->add_by),
                            $row->add_date,
                            list_char_export($row->edit_by),
                            $row->edit_date,
                        );
                    } 
                    $this->load->helper('csv');
                    echo array_to_csv($data,$filename.'.csv');                  
                    die();
                }elseif ($this->input->get('x_option', true) == 4) { // Export to PDF
                    $data = array(
                            'pagetitle'     => $this->module_title,
                            'halaman'       => $this->module_name,
                            'module_menu'   => $this->module_menu,
                            'menu_active'   => 'bidang',
                            'list_icon'     => 'fa fa-list',
                            'form_action1'  => site_url($this->module_name.'/search'),
                            'form_action2'  => site_url($this->module_name.'/a_search'),
                        );
                    $data['sSQL'] = $this->bidang->export_pdf();
                    $this->load->view($this->module_name.'/pdf_view',$data);
                }
            }else{
                $this->load->view('design/header', $data);
                $this->load->view('design/sidebar', $data);
                $this->load->view($this->module_name.'/a_search', $data);
                $this->load->view('design/footer');
            }
        }
    }

    public function add_form(){
        if (!$this->tank_auth->is_logged_in()){
            redirect('/auth/login/');
        }else{
            $data = array(
                'pagetitle' => 'Add '.$this->module_title,
                'list_icon' => 'fa fa-plus-circle'
            );
            $data['departemen'] = $this->departemen->_get_list_combo(); // combo departemen
            $this->load->view($this->module_name.'/add_form', $data);
        }
    }    
    
    public function add_record(){
        $data = array();
        $data['error_string'] = array();
        $data['inputerror'] = array();
        $data['status'] = TRUE;
        if($this->input->post('nama_bidang') == null || $this->input->post('nama_bidang') == ''){
            $data['inputerror'][] = 'nama_bidang';
            $data['error_string'][] = 'Nama bidang is required';
            $data['status'] = FALSE;
        }
        if($this->bidang->valid_unique('nama_bidang',$this->input->post('nama_bidang')) === TRUE){ // cek database untuk entry yang sama memakai valid_entry()
            $data['inputerror'][] = 'nama_bidang';
            $data['error_string'][] = 'Nama bidang already use';
            $data['status'] = FALSE;
        }else{
            $data['inputerror'][] = '';
            $data['error_string'][] = '';
            $data['status'] = TRUE;
        }
        if($this->input->post('departemen_id') == null || $this->input->post('departemen_id') == ''){
            $data['inputerror'][] = 'departemen_id';
            $data['error_string'][] = 'Level bidang is required';
            $data['status'] = FALSE;
        }
        if($data['status'] === FALSE){
            echo json_encode($data);
            exit();
        }
        $data_arr = array(
            'nama_bidang'   => list_char($this->input->post('nama_bidang')),
            'departemen_id' => $this->input->post('departemen_id'),
            'add_by'        => list_char($this->tank_auth->get_username()),
            'add_date'      => date('Y-m-d H:i:s')
        );
        $this->bidang->add($data_arr);
        $this->session->set_flashdata('message','
                <div class="alert alert-success alert-dismissable">
                    <i class="fa fa-check"></i>
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <b>Add Record.</b> Data successfully inserted.
                </div>
            ');
        echo json_encode(array("status" => TRUE));
    }

    public function view($id){
        if (!$this->tank_auth->is_logged_in()) {
            redirect('/auth/login/');
        }else{
            $data = array(
                'pagetitle'     => 'View '.$this->module_title.' # '.$id,
                'list_icon'     => 'fa fa-eye',
                'recid'         => $id
            );
            $data['qRecord']  = $this->bidang->get_by_id($id);
            if (empty($data['qRecord']))
                redirect('e404/popup');
            $this->load->view($this->module_name.'/view', $data);
        }
    }    
     
    public function edit_form($id){
        if (!$this->tank_auth->is_logged_in()) {
            redirect('/auth/login/');
        }else{
            $data = array(
                'pagetitle'     => 'Edit '.$this->module_title.' # '.$id,
                'list_icon'     => 'fa fa-pencil-square',
                'recid'         => $id
            );
            $data['qRecord']  = $this->bidang->get_by_id($id);
            if (empty($data['qRecord']))
                redirect('e404');
            $data['departemen'] = $this->departemen->_get_list_combo(); // combo departemen
            $this->load->view($this->module_name.'/edit_form', $data);
        }
    }    
    
    public function edit_record(){
        $data = array();
        $data['error_string'] = array();
        $data['inputerror'] = array();
        $data['status'] = TRUE;
        if($this->input->post('departemen_id') == null || $this->input->post('departemen_id') == ''){
            $data['inputerror'][] = 'departemen_id';
            $data['error_string'][] = 'Level bidang is required';
            $data['status'] = FALSE;
        }
        if($this->input->post('nama_bidang') == null || $this->input->post('nama_bidang') == ''){
            $data['inputerror'][] = 'nama_bidang';
            $data['error_string'][] = 'Nama bidang is required';
            $data['status'] = FALSE;
        }
        if (strtolower($this->input->post('nama_bidang_old')) === strtolower($this->input->post('nama_bidang'))){
            $data['inputerror'][] = '';
            $data['error_string'][] = '';
            $data['status'] = TRUE;
        }else{
            if($this->pendidikan->valid_unique('nama_bidang',$this->input->post('nama_bidang')) === TRUE){ // cek database untuk entry yang sama memakai valid_entry()
                $data['inputerror'][] = 'nama_bidang';
                $data['error_string'][] = 'Nama bidang already use';
                $data['status'] = FALSE;
            }else{
                $data['inputerror'][] = '';
                $data['error_string'][] = '';
                $data['status'] = TRUE;
            }
        }
        if($data['status'] === FALSE){
            echo json_encode($data);
            exit();
        }
        $data_arr = array(
            'nama_bidang'   => list_char($this->input->post('nama_bidang')),
            'departemen_id' => $this->input->post('departemen_id'),
            'edit_by'       => list_char($this->tank_auth->get_username()),
            'edit_date'     => date('Y-m-d H:i:s')
        );
        $this->bidang->edit(array('bidang_id' => $this->input->post('bidang_id')), $data_arr);
        $this->session->set_flashdata('message','
                <div class="alert alert-success alert-dismissable">
                    <i class="fa fa-check"></i>
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <b>Edit Record.</b> Data successfully updated.
                </div>
            ');
        echo json_encode(array("status" => TRUE));
    }

    public function delete_form($id){
        if (!$this->tank_auth->is_logged_in()) {
            redirect('/auth/login/');
        }else{
            $data = array(
                'pagetitle'     => 'Delete '.$this->module_title.' # '.$id,
                'list_icon'     => 'fa fa-trash',
                'form_delete'   => site_url($this->module_name.'/delete_record'),
                'recid'         => $id
            );
            $data['qRecord']  = $this->bidang->get_by_id($id);
            if (empty($data['qRecord']))
                redirect('e404');
            $this->load->view($this->module_name.'/delete_form', $data);
        }
    }    
    
    public function delete_record(){
        if (!$this->tank_auth->is_logged_in()) {
            redirect('/auth/login/');
        }else{
            $this->bidang->delete_by_id($this->input->post('bidang_id'));
            $this->session->set_flashdata('message','
                    <div class="alert alert-success alert-dismissable">
                        <i class="fa fa-check"></i>
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        <b>Delete Record.</b> Data successfully deleted.
                    </div>
                ');
            redirect($this->session->userdata('EW_BACK_URL'.$this->module_menu));
        }
    }


}

/* End of file bidang.php */
/* Location: ./application/controllers/bidang.php */