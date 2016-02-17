<?php

class authentication {

    protected $ci;

    public function __construct(){
        $this->ci = &get_instance();
        $this->ci->load->library('session');
    }

    public function admin_login($email, $password)
    {

    //    $ci->load->model('authentication_Model');
    //    $authen_model = new authentication_Model();
        $password = mysql_real_escape_string($password);
        $email = mysql_real_escape_string($email);

        $sql = "SELECT * FROM admin WHERE email = ? AND password = ?";
    //    $sql = "SELECT * FROM admin WHERE email = '" . $email . "' AND  password = '" . $password . "'";

        $query = $this->ci->db->query($sql, array($email, $password));
    //    $query = $this->ci->db->query($sql, array());

        $res = array();
        foreach($query->result() as $res){}
        if($query->num_rows > 0)
        {
            $this->ci->session->set_userdata('is_admin', true);

            $cookie = array(
                'name'   => 'username',
                'value'  => $res->username,
                'expire' => '86500',
            //    'domain' => '.some-domain.com',
                'path'   => '/',
                'prefix' => 'admin_',
            //    'secure' => TRUE
            );
            $this->ci->input->set_cookie($cookie);
        }
        return $res;
    }

    public function is_admin()
    {
        $is_admin = $this->ci->session->userdata('is_admin');
        if(!$is_admin){
            redirect('/admin/index/', 'refresh');
            exit();
        }
    }

}