<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin extends CI_Controller {

    public $var;

    public function __construct()
    {
        parent::__construct();
        $this->load->helper("url");

        $data['path'] = array(
            'host_path' => base_url('index.php'),
            'css_path' => base_url('include/css/admin'),
            'images_path' => base_url('include/images/admin'),
            'js_path' => base_url('include/js'),
        );

        $this->load->library('authentication');

    //    $data['host_path'] = base_url('index.php');
    //    $data['css_path'] = base_url('include/css/admin');
    //    $data['images_path'] = base_url('include/images/admin');
    //    $data['js_path'] = base_url('include/js');

        $this->var = $data;
        $this->load->database();
        $this->load->helper('url');

        $this->load->library('session');
        $this->load->helper('cookie');

        $this->load->library('grocery_CRUD');
    }


    public function index()
    {
        $is_admin = $this->session->userdata('is_admin');
        if($is_admin){
        //    print_r($this->var); die;
            $this->var['txt']['info']['username'] = $this->input->cookie('admin_username', TRUE);
            $this->load->view('admin_main_panel', $this->var);
        }else{
            $info_login = $this->input->post(null, true);
            if( !$info_login['email'] && !$info_login['password'] ){
                $this->load->view('admin_login_panel', $this->var);
            }else{
                $res_authen = $this->authentication->admin_login($info_login['email'], $info_login['password']);
                if( !empty($res_authen) )
                {
                    // success login
                    $this->var['txt']['info']['username'] = $res_authen->username;
                    $this->load->view('admin_main_panel', $this->var);
                }else{
                    $this->var['txt']['error']['login'] = 'Email or Password wrong !';
                    $this->load->view('admin_login_panel', $this->var);
                }
            }
        }
    }

    public function course()
    {
        $this->authentication->is_admin();

        $crud = new grocery_CRUD();

        $crud->set_table('courses');
        //      $crud->columns('customerName','contactLastName','phone','city','country','salesRepEmployeeNumber','creditLimit');
        /*      $crud->display_as('salesRepEmployeeNumber','from Employeer')
                  ->display_as('customerName','Name')
                  ->display_as('contactLastName','Last Name'); */
        $crud->set_subject('Course');
        //      $crud->set_relation('salesRepEmployeeNumber','employees','lastName');

        $crud->set_field_upload('image','assets/uploads/files');

        $output = $crud->render();
        $this->var['txt']['info']['username'] = $this->input->cookie('admin_username', TRUE);
        foreach($this->var as $key => $item){
            $output->$key = $item;
        }

        $this->load->view('admin_course_panel.php',$output);

    }



    public function logout()
    {
        $this->session->unset_userdata('is_admin');
        delete_cookie("admin_username");
        redirect('/admin/index/', 'refresh');
    //    $this->load->view('admin_login_panel', $this->var);
    }
}