<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class Home extends MX_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->model('Home_model');
        $this->load->model('main/Main_model');
    }

    function register(){
        $data['staffs'] = $this->Home_model->get_staff()->result_object();
        $this->load->view('register', $data);
    }


    function add_user(){

        $id_staff = trim(htmlentities($_REQUEST['id_staff'], ENT_QUOTES));
        $username = trim(htmlentities($_REQUEST['username'], ENT_QUOTES));
        $password = trim(htmlentities($_REQUEST['password'], ENT_QUOTES));

        if($id_staff == 'none'){
            $return_arr = array("Status" => 'ERROR', "Message" => '');
            echo json_encode($return_arr);
            return;
        }

        if(empty($username)){
            $return_arr = array("Status" => 'ERROR', "Message" => '');
            echo json_encode($return_arr);
            return;
        }

        if(empty($password)){
            $return_arr = array("Status" => 'ERROR', "Message" => '');
            echo json_encode($return_arr);
            return;
        }

        $password = md5($password);

        $data = compact('id_staff', 'username', 'password');

        if($this->Home_model->add_user($data)){
            $return_arr = array("Status" => 'OK', "Message" => '');
        } else {
            $return_arr = array("Status" => 'ERROR', "Message" => '');
        }

        echo json_encode($return_arr);
        return;
    }

    function login(){
        if(isset($_SESSION['id_staff'])){
            redirect(base_url('main'));
        } else {
            $this->load->view('admin_login');
        }
    }

    function final_login(){
        $username = htmlentities($_REQUEST['username'], ENT_QUOTES);
        $password_web_user = htmlentities($_REQUEST['password'], ENT_QUOTES);
        date_default_timezone_set('Asia/Singapore');

        $password = md5($password_web_user);

        $result = $this->Home_model->is_registered($username, $password);
        if($result->num_rows() > 0){
            $data_row = $result->row();

            $user_data = array(
                'id_user' => $data_row->id_user,
                'id_staff' => $data_row->id_staff,
                'is_admin' => $data_row->is_admin,
                'username' => $data_row->username
            );

            $this->session->set_userdata($user_data);
            $return_arr = array("Status" => 'OK', "Message" => '');

        } else {
            $return_arr = array("Status" => 'ERROR', "Message" => 'User tidak ditemukan! Pastikan username dan password sudah benar.');
        }

        echo json_encode($return_arr);
        return;
    }

    function session(){
        print_r($_SESSION);
    }


}
?>