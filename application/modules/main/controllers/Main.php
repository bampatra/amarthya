<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Main extends MX_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->model('Main_model');
        if(!$this->session->userdata('id_web_user') || $_SESSION['is_admin'] == '0'){
            redirect(base_url('home/admin_login'));
        }

    }

    function index()
    {

        $this->load->view('template/admin_header');
        $this->load->view('index');
        $this->load->view('template/admin_footer');
    }



    function logout(){
        unset(
            $_SESSION['id_web_user'],
            $_SESSION['email_web_user'],
            $_SESSION['is_admin'],
            $_SESSION['id_so_m']
        );

        $this->load->helper('url');
        redirect(base_url('home/admin_login'), 'refresh');
    }



}
?>