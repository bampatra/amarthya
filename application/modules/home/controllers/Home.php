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
        $password = trim(htmlentities($_REQUEST['username'], ENT_QUOTES));

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

    function admin_login(){
        if(isset($_SESSION['id_web_user'])){
            if($_SESSION['is_admin'] == '1'){
                redirect(base_url('main'));
            } else {
                redirect(base_url('home'));
            }
        } else {
            $this->load->view('admin_login');
        }
    }

    function admin_final_login(){
        $creds = htmlentities($_REQUEST['creds'], ENT_QUOTES);
        $password_web_user = htmlentities($_REQUEST['password'], ENT_QUOTES);
        date_default_timezone_set('Asia/Singapore');
        $date  = date('t/m/Y');
        $bulan  = date('m');
        $tahun  = date('Y');

        $password = md5($password_web_user);

        $result = $this->Home_model->is_registered($creds, $password);
        if($result->num_rows() > 0){
            $data_row = $result->row();

            if($data_row->is_admin == '1'){
                $user_data = array('id_web_user' => $data_row->id_web_user,
                    'email_web_user' => $data_row->email_web_user,
                    'is_admin' => $data_row->is_admin,
                    'awal_periode' => '01/01/'.$tahun,
                    'akhir_periode' => $date,
                    'laporan_bulan' => $bulan,
                    'laporan_tahun' => $tahun,
                    'default_kas' => '101',
                    'default_lembaga' => 'Yayasan Ari Prshanti Nilayam'
                );

                $this->session->set_userdata($user_data);
                $return_arr = array("Status" => 'OK', "Message" => '');
            } else {
                $return_arr = array("Status" => 'ERROR', "Message" => 'Unauthorized!');
            }

        } else {
            $return_arr = array("Status" => 'ERROR', "Message" => 'User tidak ditemukan! Pastikan email dan password sudah benar.');
        }

        echo json_encode($return_arr);
        return;
    }


}
?>