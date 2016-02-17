<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Travel extends CI_Controller {

    public function index()
    {
        $data['title'] = "My Real Title";
        $data['heading'] = "My Real Heading";
        $this->load->helper("url");
        $data['css_path'] = base_url('include/css');
        $data['images_path'] = base_url('include/images');
        $this->load->view('travel_stone_view', $data);
    }

    public function admin_manage()
    {
        parent::__construct();

        $this->load->database();
        $this->load->helper('url');

        $this->load->library('grocery_CRUD');

        $crud = new grocery_CRUD();

        $crud->set_theme('datatables');
        $crud->set_table('travel');
    //    $crud->set_relation('officeCode','offices','city');
   //     $crud->display_as('officeCode','Office City');
        $crud->set_subject('Travel');

   //     $crud->required_fields('lastName');

    //    $crud->set_field_upload('file_url','assets/uploads/files');

        $output = $crud->render();

        $this->_example_output($output);
    }

    public function _example_output($output = null)
    {
        $this->load->view('example.php',$output);
    }

    public function offices()
    {
        $output = $this->grocery_crud->render();

        $this->_example_output($output);
    }

}
