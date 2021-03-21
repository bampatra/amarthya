<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Main extends MX_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->model('Main_model');
//        if(!$this->session->userdata('id_web_user') || $_SESSION['is_admin'] == '0'){
//            redirect(base_url('home/admin_login'));
//        }

    }

    function index()
    {

        $this->load->view('template/admin_header');
        $this->load->view('index');
        $this->load->view('template/admin_footer');
    }

    function stok_in_out(){
        $this->load->view('template/admin_header');


        $product = $this->Main_model->get_product_by_id($_GET['product']);

        if($product->num_rows() == 0){
            // product not found error
        } else {
            $data['product'] = $product->result_array();
            $this->load->view('stok_in_out', $data);
        }

        $this->load->view('template/admin_footer');
    }

    function get_stok_in_out(){

        $id_product = strtoupper(trim(htmlentities($_REQUEST['id_product'], ENT_QUOTES)));

        // server-side pagination
        $draw = $_REQUEST['draw'];
        $length = $_REQUEST['length'];
        $start = $_REQUEST['start'];
        $search = $_REQUEST['search']["value"];

        $total = $this->Main_model->get_product_stok_in_out($id_product)->num_rows();

        $output = array();
        $output['draw'] = $draw;
        $output['recordsTotal'] = $output['recordsFiltered'] = $total;
        $output['data']=array();

        if($search!=""){
//            $this->db->like("CONCAT()",$search);
        }

        $this->db->limit($length,$start);

        $output['data'] = $this->Main_model->get_product_stok_in_out($id_product)->result_object();

        echo json_encode($output);


    }

    function get_stok_in_out_by_id(){
        $id = htmlentities($_REQUEST['id_stok_in_out'], ENT_QUOTES);
        $data = $this->Main_model->get_stok_in_out_by_id($id);
        echo json_encode($data->row());
        return;
    }

    function add_stok_in_out(){
        $id_stok_in_out = strtoupper(trim(htmlentities($_REQUEST['id_stok_in_out'], ENT_QUOTES)));
        $tipe_in_out = strtoupper(trim(htmlentities($_REQUEST['tipe_in_out'], ENT_QUOTES)));
        $stok_in_out = strtoupper(trim(htmlentities($_REQUEST['stok_in_out'], ENT_QUOTES)));
        $tgl_in = strtoupper(trim(htmlentities($_REQUEST['tgl_in'], ENT_QUOTES)));
        $tgl_out = strtoupper(trim(htmlentities($_REQUEST['tgl_out'], ENT_QUOTES)));
        $tgl_expired = strtoupper(trim(htmlentities($_REQUEST['tgl_expired'], ENT_QUOTES)));
        $catatan_in_out = strtoupper(trim(htmlentities($_REQUEST['catatan_in_out'], ENT_QUOTES)));
        $id_product = strtoupper(trim(htmlentities($_REQUEST['id_product'], ENT_QUOTES)));

        //validation
        $error = array();

        $this->db->trans_begin();

        if($tipe_in_out == "IN"){

            if(empty($tgl_in)){
                array_push($error, "invalid-tanggalin");
            }

            if(empty($tgl_expired)){
                array_push($error, "invalid-tanggalexpired");
            }

        } else if($tipe_in_out == "OUT") {

            if(empty($tgl_out)){
                array_push($error, "invalid-tanggalout");
            }

        } else {
            array_push($error, "invalid-tipe");
        }

        if(empty($stok_in_out) || !is_numeric($stok_in_out)){
            array_push($error, "invalid-stok");
        }

        if(!empty($error)){
            $return_arr = array("Status" => 'FORMERROR', "Error" => $error);
            $this->db->trans_rollback();
            echo json_encode($return_arr);
            return;
        }


        $data = compact('id_product','tipe_in_out', 'stok_in_out',
                            'tgl_in', 'tgl_out', 'tgl_expired', 'catatan_in_out');

        if($this->Main_model->get_stok_in_out_by_id($id_stok_in_out)->num_rows() == 0){

            if($this->Main_model->add_stok_in_out($data)){
                $this->db->trans_commit();
                $return_arr = array("Status" => 'OK', "Message" => '');
            } else {
                $this->db->trans_rollback();
                $return_arr = array("Status" => 'ERROR', "Message" => 'Gagal menambahkan data');
            }
        } else {
            if($this->Main_model->update_stok_in_out($data, $id_stok_in_out)){
                $this->db->trans_commit();
                $return_arr = array("Status" => 'OK', "Message" => '');
            } else {
                $this->db->trans_rollback();
                $return_arr = array("Status" => 'ERROR', "Message" => 'Gagal mengupdate data');
            }

        }

        echo json_encode($return_arr);


    }

    function product(){
        $this->load->view('template/admin_header');
        $this->load->view('product');
        $this->load->view('template/admin_footer');
    }

    function get_product(){

        // server-side pagination
        $draw = $_REQUEST['draw'];
        $length = $_REQUEST['length'];
        $start = $_REQUEST['start'];
        $search = $_REQUEST['search']["value"];

        $total = $this->Main_model->get_product()->num_rows();

        $output = array();
        $output['draw'] = $draw;
        $output['recordsTotal'] = $output['recordsFiltered'] = $total;
        $output['data']=array();


        $this->db->limit($length,$start);


        $output['data'] = $this->Main_model->get_product($search)->result_object();

        echo json_encode($output);
    }

    function vendor(){
        $this->load->view('template/admin_header');
        $this->load->view('vendor');
        $this->load->view('template/admin_footer');
    }

    function get_product_by_id(){
        $id = htmlentities($_REQUEST['id_product'], ENT_QUOTES);
        $data = $this->Main_model->get_product_by_id($id);
        echo json_encode($data->row());
        return;
    }

    function add_product(){

        $id_product = strtoupper(trim(htmlentities($_REQUEST['id_product'], ENT_QUOTES)));
        $nama_product = trim(htmlentities($_REQUEST['nama_product'], ENT_QUOTES));
        $SKU_product = strtoupper(trim(htmlentities($_REQUEST['SKU_product'], ENT_QUOTES)));
        $satuan_product = strtoupper(trim(htmlentities($_REQUEST['satuan_product'], ENT_QUOTES)));
        $HP_product = strtoupper(trim(htmlentities($_REQUEST['HP_product'], ENT_QUOTES)));
        $HJ_product = strtoupper(trim(htmlentities($_REQUEST['HJ_product'], ENT_QUOTES)));
        $HR_product = strtoupper(trim(htmlentities($_REQUEST['HR_product'], ENT_QUOTES)));

        //validation
        $error = array();

        $this->db->trans_begin();

        if(empty($nama_product)){
            array_push($error, "invalid-namaproduct");
        }

        if(empty($satuan_product)){
            array_push($error, "invalid-satuanproduct");
        }

        if(empty($HP_product) || !is_numeric($HP_product)){
            array_push($error, "invalid-HP");
        }

        if(empty($HJ_product) || !is_numeric($HJ_product)){
            array_push($error, "invalid-HJ");
        }

        if(empty($HR_product) || !is_numeric($HR_product)){
            array_push($error, "invalid-HR");
        }

        if(!empty($error)){
            $return_arr = array("Status" => 'FORMERROR', "Error" => $error);
            $this->db->trans_rollback();
            echo json_encode($return_arr);
            return;
        }

        $data = compact('nama_product', 'SKU_product', 'satuan_product',
                        'HJ_product', 'HR_product', 'HP_product');


        if($this->Main_model->get_product_by_id($id_product)->num_rows() == 0){
            if($this->Main_model->nama_product_check($nama_product)->num_rows() > 0){
                $return_arr = array("Status" => 'EXIST');
                $this->db->trans_rollback();
                echo json_encode($return_arr);
                return;
            }

            if($this->Main_model->add_product($data)){
                $this->db->trans_commit();
                $return_arr = array("Status" => 'OK', "Message" => 'Berhasil ditambahkan');
            } else {
                $this->db->trans_rollback();
                $return_arr = array("Status" => 'ERROR', "Message" => 'Gagal menambahkan produk');
            }
        } else {
            if($this->Main_model->update_product($data, $id_product)){
                $this->db->trans_commit();
                $return_arr = array("Status" => 'OK', "Message" => 'Berhasil diupdate');
            } else {
                $this->db->trans_rollback();
                $return_arr = array("Status" => 'ERROR', "Message" => 'Gagal mengupdate produk');
            }

        }

        echo json_encode($return_arr);

    }

    function get_vendor(){
        $data = $this->Main_model->get_vendor();
        echo json_encode($data->result_object());
        return;
    }

    function get_vendor_by_id(){
        $id = htmlentities($_REQUEST['id_vendor'], ENT_QUOTES);
        $data = $this->Main_model->get_vendor_by_id($id);
        echo json_encode($data->row());
        return;
    }

    function add_vendor(){

        $id_vendor = strtoupper(trim(htmlentities($_REQUEST['id_vendor'], ENT_QUOTES)));
        $nama_vendor = strtoupper(trim(htmlentities($_REQUEST['nama_vendor'], ENT_QUOTES)));
        $alamat_vendor = strtoupper(trim(htmlentities($_REQUEST['alamat_vendor'], ENT_QUOTES)));
        $no_hp_vendor = strtoupper(trim(htmlentities($_REQUEST['no_hp_vendor'], ENT_QUOTES)));
        $email_vendor = strtoupper(trim(htmlentities($_REQUEST['email_vendor'], ENT_QUOTES)));
        $catatan_vendor = strtoupper(trim(htmlentities($_REQUEST['catatan_vendor'], ENT_QUOTES)));

        //validation
        $error = array();

        $this->db->trans_begin();

        if(empty($nama_vendor)){
            array_push($error, "invalid-namavendor");
        }

        if(empty($alamat_vendor)){
            array_push($error, "invalid-alamatvendor");
        }

        if(empty($no_hp_vendor)){
            array_push($error, "invalid-nohp");
        }

        if(!empty($error)){
            $return_arr = array("Status" => 'FORMERROR', "Error" => $error);
            $this->db->trans_rollback();
            echo json_encode($return_arr);
            return;
        }

        $data = compact('nama_vendor', 'alamat_vendor',
            'no_hp_vendor', 'email_vendor', 'catatan_vendor');


        if($this->Main_model->get_vendor_by_id($id_vendor)->num_rows() == 0){
            if($this->Main_model->nama_vendor_check($nama_vendor)->num_rows() > 0){
                $return_arr = array("Status" => 'EXIST');
                $this->db->trans_rollback();
                echo json_encode($return_arr);
                return;
            }

            if($this->Main_model->add_vendor($data)){
                $this->db->trans_commit();
                $return_arr = array("Status" => 'OK', "Message" => '');
            } else {
                $this->db->trans_rollback();
                $return_arr = array("Status" => 'ERROR', "Message" => 'Gagal menambahkan vendor');
            }
        } else {
            if($this->Main_model->update_vendor($data, $id_vendor)){
                $this->db->trans_commit();
                $return_arr = array("Status" => 'OK', "Message" => '');
            } else {
                $this->db->trans_rollback();
                $return_arr = array("Status" => 'ERROR', "Message" => 'Gagal mengupdate staff');
            }
        }

        echo json_encode($return_arr);

    }

    function customer(){
        $this->load->view('template/admin_header');
        $this->load->view('customer');
        $this->load->view('template/admin_footer');
    }

    function get_customer(){
        $data = $this->Main_model->get_customer();
        echo json_encode($data->result_object());
        return;
    }

    function get_customer_by_id(){
        $id = htmlentities($_REQUEST['id_customer'], ENT_QUOTES);
        $data = $this->Main_model->get_customer_by_id($id);
        echo json_encode($data->row());
        return;
    }

    function add_customer(){

        $id_customer = strtoupper(trim(htmlentities($_REQUEST['id_customer'], ENT_QUOTES)));
        $nama_customer = strtoupper(trim(htmlentities($_REQUEST['nama_customer'], ENT_QUOTES)));
        $alamat_customer = strtoupper(trim(htmlentities($_REQUEST['alamat_customer'], ENT_QUOTES)));
        $no_hp_customer = strtoupper(trim(htmlentities($_REQUEST['no_hp_customer'], ENT_QUOTES)));
        $email_customer = strtoupper(trim(htmlentities($_REQUEST['email_customer'], ENT_QUOTES)));
        $catatan_customer = strtoupper(trim(htmlentities($_REQUEST['catatan_customer'], ENT_QUOTES)));

        //validation
        $error = array();

        $this->db->trans_begin();

        if(empty($nama_customer)){
            array_push($error, "invalid-namacustomer");
        }

        if(empty($alamat_customer)){
            array_push($error, "invalid-alamatcustomer");
        }

        if(empty($no_hp_customer)){
            array_push($error, "invalid-nohp");
        }

        if(!empty($error)){
            $return_arr = array("Status" => 'FORMERROR', "Error" => $error);
            $this->db->trans_rollback();
            echo json_encode($return_arr);
            return;
        }

        $data = compact('nama_customer', 'alamat_customer',
                            'no_hp_customer', 'email_customer', 'catatan_customer');


        if($this->Main_model->get_customer_by_id($id_customer)->num_rows() == 0){
            if($this->Main_model->nama_customer_check($nama_customer)->num_rows() > 0){
                $return_arr = array("Status" => 'EXIST');
                $this->db->trans_rollback();
                echo json_encode($return_arr);
                return;
            }

            if($this->Main_model->add_customer($data)){
                $this->db->trans_commit();
                $return_arr = array("Status" => 'OK', "Message" => '');
            } else {
                $this->db->trans_rollback();
                $return_arr = array("Status" => 'ERROR', "Message" => 'Gagal menambahkan customer');
            }
        } else {
            if($this->Main_model->update_customer($data, $id_customer)){
                $this->db->trans_commit();
                $return_arr = array("Status" => 'OK', "Message" => '');
            } else {
                $this->db->trans_rollback();
                $return_arr = array("Status" => 'ERROR', "Message" => 'Gagal mengupdate staff');
            }
        }

        echo json_encode($return_arr);

    }

    function staff(){
        $data['posisi_list'] = $this->Main_model->get_posisi()->result_object();

        $this->load->view('template/admin_header');
        $this->load->view('staff', $data);
        $this->load->view('template/admin_footer');
    }


    function get_staff(){
        $data = $this->Main_model->get_staff();
        echo json_encode($data->result_object());
        return;
    }

    function get_staff_by_id(){
        $id = htmlentities($_REQUEST['id_staff'], ENT_QUOTES);
        $data = $this->Main_model->get_staff_by_id($id);
        echo json_encode($data->row());
        return;
    }

    function add_staff(){

        $id_staff = strtoupper(trim(htmlentities($_REQUEST['id_staff'], ENT_QUOTES)));
        $nama_staff = strtoupper(trim(htmlentities($_REQUEST['nama_staff'], ENT_QUOTES)));
        $tgl_lahir_staff = strtoupper(trim(htmlentities($_REQUEST['tgl_lahir_staff'], ENT_QUOTES)));
        $alamat_staff = strtoupper(trim(htmlentities($_REQUEST['alamat_staff'], ENT_QUOTES)));
        $no_hp_staff = strtoupper(trim(htmlentities($_REQUEST['no_hp_staff'], ENT_QUOTES)));
        $id_posisi = strtoupper(trim(htmlentities($_REQUEST['id_posisi'], ENT_QUOTES)));
        $salary_staff = strtoupper(trim(htmlentities($_REQUEST['salary'], ENT_QUOTES)));
        $no_rek_staff = strtoupper(trim(htmlentities($_REQUEST['no_rek_staff'], ENT_QUOTES)));
        $nama_bank_staff = strtoupper(trim(htmlentities($_REQUEST['nama_bank_staff'], ENT_QUOTES)));
        $tgl_join_staff = strtoupper(trim(htmlentities($_REQUEST['tgl_join_staff'], ENT_QUOTES)));

        //validation
        $error = array();

        $this->db->trans_begin();

        if(empty($nama_staff)){
            array_push($error, "invalid-namastaff");
        }

        if(empty($tgl_lahir_staff) || !preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/",$tgl_lahir_staff)){
            array_push($error, "invalid-tanggallahir");
        }

        if(empty($alamat_staff)){
            array_push($error, "invalid-alamat");
        }

        if(empty($no_hp_staff)){
            array_push($error, "invalid-nohp");
        }

        if(empty($id_posisi) || $id_posisi == "none"){
            array_push($error, "invalid-posisi");
        }

        if(empty($salary_staff)){
            array_push($error, "invalid-salary");
        }

        if(empty($no_rek_staff)){
            array_push($error, "invalid-norek");
        }

        if(empty($nama_bank_staff)){
            array_push($error, "invalid-namabank");
        }

        if(empty($tgl_join_staff)){
            array_push($error, "invalid-tanggaljoin");
        }

        if(!empty($error)){
            $return_arr = array("Status" => 'FORMERROR', "Error" => $error);
            $this->db->trans_rollback();
            echo json_encode($return_arr);
            return;
        }


        $data = compact('nama_staff','tgl_lahir_staff', 'alamat_staff', 'no_hp_staff', 'id_posisi',
                            'salary_staff', 'no_rek_staff', 'nama_bank_staff', 'tgl_join_staff');



        if($this->Main_model->get_staff_by_id($id_staff)->num_rows() == 0){
            if($this->Main_model->nama_staff_check($nama_staff)->num_rows() > 0){
                $return_arr = array("Status" => 'EXIST');
                $this->db->trans_rollback();
                echo json_encode($return_arr);
                return;
            }

            if($this->Main_model->add_staff($data)){
                $this->db->trans_commit();
                $return_arr = array("Status" => 'OK', "Message" => '');
            } else {
                $this->db->trans_rollback();
                $return_arr = array("Status" => 'ERROR', "Message" => 'Gagal menambahkan staff');
            }
        } else {
            if($this->Main_model->update_staff($data, $id_staff)){
                $this->db->trans_commit();
                $return_arr = array("Status" => 'OK', "Message" => '');
            } else {
                $this->db->trans_rollback();
                $return_arr = array("Status" => 'ERROR', "Message" => 'Gagal mengupdate staff');
            }
        }

        echo json_encode($return_arr);

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