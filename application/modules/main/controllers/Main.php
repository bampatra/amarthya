<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Dompdf\Dompdf;
use Dompdf\Options;
use Spipu\Html2Pdf\Html2Pdf;

class Main extends MX_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->model('Main_model');
        if(!$this->session->userdata('id_staff')){
            redirect(base_url('home/login'));
        }
    }

    function index()
    {

        date_default_timezone_set('Asia/Singapore');

        $today = date("Y-m-d");
        $year = date("Y");
        $month = date("m");
        $day = date("d");

        $data['dashboard_data'] = $this->Main_model->dashboard_data($today, $month)->result_object();

        if($day <= 15 ){

            $tgl_awal = $year."-".sprintf('%02d', $month)."-01";
            $tgl_akhir = $year."-".sprintf('%02d', $month)."-15";

        } else{
            $tgl_awal = $year."-".sprintf('%02d', $month)."-16";
            $tgl_akhir = $year."-".sprintf('%02d', $month)."-".date('t');
        }


        $data['ongkir_data'] = $this->Main_model->periode_ongkir_per_staff($tgl_awal, $tgl_akhir)->result_object();

        $this->load->view('template/admin_header');
        $this->load->view('index', $data);
        $this->load->view('template/admin_footer');
    }

    function fb_menu(){

        if($this->session->userdata('is_admin') != "1" && $this->session->userdata('is_admin') != "2" && $this->session->userdata('is_admin') != "5"){
            redirect(base_url('main'));
        }

        $data['kategori'] = $this->Main_model->get_kategori_eatery()->result_object();

        $this->load->view('template/admin_header');
        $this->load->view('fb_menu', $data);
        $this->load->view('template/admin_footer');
    }

    function get_menu_eatery(){

        if($this->session->userdata('is_admin') != "1" && $this->session->userdata('is_admin') != "2" && $this->session->userdata('is_admin') != "5"){
            redirect(base_url('main'));
        }

        $draw = $_REQUEST['draw'];
        $length = $_REQUEST['length'];
        $start = $_REQUEST['start'];
        $search = trim(htmlentities($_REQUEST['search']["value"], ENT_QUOTES));


        if(isset($_GET['kategori'])){
            $kategori = htmlentities($_GET['kategori'], ENT_QUOTES);
        } else {
            $kategori = "all";
        }

        $total = $this->Main_model->get_menu_eatery($kategori)->num_rows();

        $output = array();
        $output['draw'] = $draw;
        $output['recordsTotal'] = $output['recordsFiltered'] = $total;
        $output['data']=array();


        $output['data'] = $this->Main_model->get_menu_eatery($kategori, $search, $length, $start)->result_object();
        echo json_encode($output);
        return;
    }

    function fb_costing(){

        if($this->session->userdata('is_admin') != "1" && $this->session->userdata('is_admin') != "2" && $this->session->userdata('is_admin') != "5"){
            redirect(base_url('main'));
        }

        $data['kategori'] = $this->Main_model->get_kategori_eatery()->result_object();

        if(isset($_GET['menu'])){
            $id_menu = htmlentities($_GET['menu'], ENT_QUOTES);
            $get_data = $this->Main_model->get_menu_by_id($id_menu);

            if($get_data->num_rows() == 0){
                $this->load->view('template/admin_header');
                return;
            }

            $data['master'] = $get_data->row();
            $data['bahanbahan'] = $this->Main_model->get_bahan_by_menu($id_menu)->result_object();
        }


        $this->load->view('template/admin_header');
        $this->load->view('menu_eatery', $data);
        $this->load->view('template/admin_footer');
    }

    function save_menu(){

        if($this->session->userdata('is_admin') != "1" && $this->session->userdata('is_admin') != "2" && $this->session->userdata('is_admin') != "5"){
            redirect(base_url('main'));
        }

        $nama_menu = trim(htmlentities($_REQUEST['nama_menu'], ENT_QUOTES));
        $kategori_menu = trim(htmlentities($_REQUEST['kategori_menu'], ENT_QUOTES));
        $deskripsi_menu = trim(htmlentities($_REQUEST['deskripsi_menu'], ENT_QUOTES));
        $HJ_menu = trim(htmlentities($_REQUEST['HJ_menu'], ENT_QUOTES));
        $active_menu = '1';

        // === validations ===

        if(empty($nama_menu)){
            $return_arr = array("Status" => 'ERROR', "Message" => 'Nama Menu tidak boleh kosong');
            echo json_encode($return_arr);
            return;
        }

        if($kategori_menu == "none"){
            $return_arr = array("Status" => 'ERROR', "Message" => 'Kategori tidak boleh kosong');
            echo json_encode($return_arr);
            return;
        }

        if(empty($HJ_menu)){
            $return_arr = array("Status" => 'ERROR', "Message" => 'Harga Jual tidak boleh kosong');
            echo json_encode($return_arr);
            return;
        }

        if(empty($_REQUEST['id_product'])){
            $return_arr = array("Status" => 'ERROR', "Message" => 'Bahan tidak boleh kosong');
            echo json_encode($return_arr);
            return;
        }


        // ==== Add Menu Eatery ====

        $this->db->trans_begin();

        $data_menu = compact('nama_menu', 'kategori_menu', 'deskripsi_menu', 'HJ_menu', 'active_menu');

        $id_menu = $this->Main_model->add_menu_eatery($data_menu);

        if(!$id_menu){
            $this->db->trans_rollback();
            $return_arr = array("Status" => 'ERROR', "Message" => 'Gagal menyimpan data. Hubungi Admin (code: savemenu1)');
            echo json_encode($return_arr);
            return;
        }

        // =========================

        foreach($_REQUEST['id_product'] as $key=>$value){

            $id_product = trim(htmlentities($value, ENT_QUOTES));
            $qty_bahan = trim(htmlentities($_REQUEST['qty_bahan'][$key], ENT_QUOTES));

            // validations

            $get_price = $this->Main_model->get_product_price($id_product);

            if($get_price->num_rows() == 0){
                $this->db->trans_rollback();
                $return_arr = array("Status" => 'ERROR', "Message" => 'Salah satu bahan tidak ditemukan');
                echo json_encode($return_arr);
                return;
            }

            if($qty_bahan == 0 || !is_numeric($qty_bahan)){
                $this->db->trans_rollback();
                $return_arr = array("Status" => 'ERROR', "Message" => 'Kuantitas bahan tidak valid');
                echo json_encode($return_arr);
                return;
            }

//            $price = $get_price->row()->HP_product;


            // ==== Add Menu Bahan Eatery ====

            $data_bahan = compact('id_menu', 'id_product', 'qty_bahan');

            if(!$this->Main_model->add_menu_bahan_eatery($data_bahan)){
                $this->db->trans_rollback();
                $return_arr = array("Status" => 'ERROR', "Message" => 'Gagal menyimpan data. Hubungi Admin (code: savemenu1)');
                echo json_encode($return_arr);
                return;
            }

            // ===============================

        }

        $this->db->trans_commit();
        $return_arr = array("Status" => 'OK', "Message" => 'Berhasil tersimpan');
        echo json_encode($return_arr);

    }

    function delete_menu(){

        if($this->session->userdata('is_admin') != "1" && $this->session->userdata('is_admin') != "2" && $this->session->userdata('is_admin') != "5"){
            redirect(base_url('main'));
        }

        $id_menu = trim(htmlentities($_REQUEST['id_menu'], ENT_QUOTES));

        if($this->Main_model->get_menu_by_id($id_menu)->num_rows() == 0){
            $return_arr = array("Status" => 'ERROR', "Message" => 'Menu tidak ditemukan');
            echo json_encode($return_arr);
            return;
        }

        $this->db->trans_begin();

        if($this->Main_model->delete_menu_bahan_eatery($id_menu, '0')){

            if($this->Main_model->delete_menu_eatery($id_menu)){
                $this->db->trans_commit();
                $return_arr = array("Status" => 'OK', "Message" => 'Menu berhasil dihapus');
                echo json_encode($return_arr);
                return;

            } else {
                $this->db->trans_rollback();
                $return_arr = array("Status" => 'ERROR', "Message" => 'Menu tidak ditemukan');
                echo json_encode($return_arr);
                return;
            }

        } else {
            $this->db->trans_rollback();
            $return_arr = array("Status" => 'ERROR', "Message" => 'Menu tidak ditemukan');
            echo json_encode($return_arr);
            return;
        }

    }

    function update_menu(){

        if($this->session->userdata('is_admin') != "1" && $this->session->userdata('is_admin') != "2" && $this->session->userdata('is_admin') != "5"){
            redirect(base_url('main'));
        }

        $nama_menu = trim(htmlentities($_REQUEST['nama_menu'], ENT_QUOTES));
        $kategori_menu = trim(htmlentities($_REQUEST['kategori_menu'], ENT_QUOTES));
        $deskripsi_menu = trim(htmlentities($_REQUEST['deskripsi_menu'], ENT_QUOTES));
        $HJ_menu = trim(htmlentities($_REQUEST['HJ_menu'], ENT_QUOTES));
        $id_menu = trim(htmlentities($_REQUEST['id_menu'], ENT_QUOTES));


        $this->db->trans_begin();

        // === validations ===

        if($this->Main_model->get_menu_by_id($id_menu)->num_rows() == 0){
            $this->db->trans_rollback();
            $return_arr = array("Status" => 'ERROR', "Message" => 'Menu tidak ditemukan');
            echo json_encode($return_arr);
            return;
        }

        if(empty($nama_menu)){
            $return_arr = array("Status" => 'ERROR', "Message" => 'Nama Menu tidak boleh kosong');
            echo json_encode($return_arr);
            return;
        }

        if($kategori_menu == "none"){
            $return_arr = array("Status" => 'ERROR', "Message" => 'Kategori tidak boleh kosong');
            echo json_encode($return_arr);
            return;
        }

        if(empty($HJ_menu)){
            $return_arr = array("Status" => 'ERROR', "Message" => 'Harga Jual tidak boleh kosong');
            echo json_encode($return_arr);
            return;
        }

        if(empty($_REQUEST['id_product'])){
            $return_arr = array("Status" => 'ERROR', "Message" => 'Bahan tidak boleh kosong');
            echo json_encode($return_arr);
            return;
        }

        $updated_data = compact('nama_menu', 'kategori_menu', 'deskripsi_menu', 'HJ_menu');

        if($this->Main_model->update_menu_eatery($updated_data, $id_menu)){


            $current_bahan = $this->Main_model->get_bahan_by_menu($id_menu)->result_array();
            $arr_product = array_map (function($value){
                return $value['id_product'];
            } , $current_bahan);

            $arr_qty = array_map (function($value){
                return $value['qty_bahan'];
            } , $current_bahan);

            $new_bahan = implode("','", $_REQUEST['id_product']);

            // remove gone bahan
            if(!$this->Main_model->delete_menu_bahan_eatery($id_menu, $new_bahan)){
                $this->db->trans_rollback();
                $return_arr = array("Status" => 'ERROR', "Message" => 'Gagal mengubah data. Hubungi admin (code: deletebahan)');
                echo json_encode($return_arr);
                return;
            }

            // add new bahan

            // compare current and new, add the ones not in current
            foreach ($_REQUEST['id_product'] as $key=>$value){

                $id_product = trim(htmlentities($value, ENT_QUOTES));
                $qty_bahan = trim(htmlentities($_REQUEST['qty_bahan'][$key], ENT_QUOTES));

                if (!in_array($value, $arr_product)) {

                    $get_price = $this->Main_model->get_product_price($id_product);

                    if($get_price->num_rows() == 0){
                        $this->db->trans_rollback();
                        $return_arr = array("Status" => 'ERROR', "Message" => 'Salah satu bahan tidak ditemukan');
                        echo json_encode($return_arr);
                        return;
                    }

                    if($qty_bahan == 0 || !is_numeric($qty_bahan)){
                        $this->db->trans_rollback();
                        $return_arr = array("Status" => 'ERROR', "Message" => 'Kuantitas bahan tidak valid');
                        echo json_encode($return_arr);
                        return;
                    }

                    $data_bahan = compact('id_menu', 'id_product', 'qty_bahan');

                    if(!$this->Main_model->add_menu_bahan_eatery($data_bahan)){
                        $this->db->trans_rollback();
                        $return_arr = array("Status" => 'ERROR', "Message" => 'Gagal menyimpan data. Hubungi Admin (code: savemenu1)');
                        echo json_encode($return_arr);
                        return;
                    }

                } else {
                    //check if qty is different

                    if($qty_bahan != $arr_qty[$key]){

                        $updated_data = compact('qty_bahan');
                        $where_array = array('id_menu' => $id_menu, 'id_product' => $id_product);

                        if(!$this->Main_model->update_menu_bahan_eatery($updated_data, $where_array)){
                            $this->db->trans_rollback();
                            $return_arr = array("Status" => 'ERROR', "Message" => 'Gagal menyimpan data. Hubungi Admin (code: updateqty)');
                            echo json_encode($return_arr);
                            return;
                        }

                    }
                }

            }

            $this->db->trans_commit();
            $return_arr = array("Status" => 'OK', "Message" => 'Data berhasil dirubah');
            echo json_encode($return_arr);

        } else {
            $this->db->trans_rollback();
            $return_arr = array("Status" => 'ERROR', "Message" => 'Gagal mengupdate data. Hubungi admin (code: header)');
            echo json_encode($return_arr);
            return;
        }


    }

    function get_suggest_bahan(){
        $search = htmlentities($_REQUEST['search'], ENT_QUOTES);
        $suggest_data = $this->Main_model->get_suggest_bahan($search);
        $row = array();

        foreach($suggest_data->result_array() as $suggest) {
            $data = array("value" => $suggest['nama_product'], "id" => $suggest['id_product'], "price" => $suggest['HP_product'], "satuan" => $suggest['satuan_product']);
            array_push($row, $data);
        }

        echo json_encode($row);
    }

    function izin(){

        if($this->session->userdata('is_admin') == "1"){
            $data['staffs'] = $this->Main_model->get_staff()->result_object();
        } else {
            $data['staffs'] = $this->Main_model->get_staff_by_id($this->session->userdata('id_staff'))->result_object();
        }


        $this->load->view('template/admin_header');
        $this->load->view('izin', $data);
        $this->load->view('template/admin_footer');
    }

    function get_izin(){
        // server-side pagination
        $draw = $_REQUEST['draw'];
        $length = $_REQUEST['length'];
        $start = $_REQUEST['start'];
        $search = trim(htmlentities($_REQUEST['search']["value"], ENT_QUOTES));

        // If admin, can see all. If not, show theirs only
        if($this->session->userdata('is_admin') == "1"){
            $id_staff = "all";
        } else {
            $id_staff = $this->session->userdata('id_staff');
        }

        if(isset($_GET['status'])){
            $status = htmlentities($_GET['status'], ENT_QUOTES);
        } else {
            $status = "all";
        }

        $total = $this->Main_model->get_izin($id_staff, $status)->num_rows();

        $output = array();
        $output['draw'] = $draw;
        $output['recordsTotal'] = $output['recordsFiltered'] = $total;
        $output['data']=array();


        $output['data'] = $this->Main_model->get_izin($id_staff, $status, $search, $length, $start)->result_object();
        echo json_encode($output);
        return;
    }

    function add_izin(){

        $id_izin = strtoupper(trim(htmlentities($_REQUEST['id_izin'], ENT_QUOTES)));
        $id_staff = strtoupper(trim(htmlentities($_REQUEST['id_staff'], ENT_QUOTES)));
        $tgl_start_izin = strtoupper(trim(htmlentities($_REQUEST['tgl_start_izin'], ENT_QUOTES)));
        $tgl_end_izin = strtoupper(trim(htmlentities($_REQUEST['tgl_end_izin'], ENT_QUOTES)));
        $alasan_izin = trim(htmlentities($_REQUEST['alasan_izin'], ENT_QUOTES));

        $keterangan_manager = "";
        $id_staff_approval = "";
        $status_izin = '0';


        //validation
        $error = array();

        $this->db->trans_begin();

        //check if staff exists
        if ($this->Main_model->get_staff_by_id($id_staff)->num_rows() == 0){
            array_push($error, "invalid-staff");
        }

        if(strtotime($tgl_start_izin) === false){
            array_push($error, "invalid-startdate");
        }

        if(strtotime($tgl_end_izin) === false){
            array_push($error, "invalid-enddate");
        }

        if(empty($alasan_izin)){
            array_push($error, "invalid-alasan");
        }

        if(!empty($error)){
            $return_arr = array("Status" => 'FORMERROR', "Error" => $error);
            $this->db->trans_rollback();
            echo json_encode($return_arr);
            return;
        }

        // If not admin, must input for themselves.
        if($this->session->userdata('is_admin') != "1"){
            if($id_staff != $this->session->userdata('id_staff')){
                $return_arr = array("Status" => 'ERROR', "Message" => 'Unauthorized');
                echo json_encode($return_arr);
                return;
            }
        }

        $data = compact('id_staff', 'tgl_start_izin', 'tgl_end_izin', 'alasan_izin',
                        'keterangan_manager', 'id_staff_approval', 'status_izin');

        if($this->Main_model->get_izin_by_id($id_izin)->num_rows() == 0){

            if($this->Main_model->add_izin($data)){
                $this->db->trans_commit();
                $return_arr = array("Status" => 'OK', "Message" => '');
            } else {
                $this->db->trans_rollback();
                $return_arr = array("Status" => 'ERROR', "Message" => 'Gagal menambahkan data');
            }
        } else {
            if($this->Main_model->update_izin($data, $id_izin)){
                $this->db->trans_commit();
                $return_arr = array("Status" => 'OK', "Message" => '');
            } else {
                $this->db->trans_rollback();
                $return_arr = array("Status" => 'ERROR', "Message" => 'Gagal mengupdate data');
            }
        }

        echo json_encode($return_arr);

    }

    function action_izin(){
        if($this->session->userdata('is_admin') != "1"){
            $return_arr = array("Status" => 'ERROR', "Message" => 'Unauthorized');
            echo json_encode($return_arr);
            return;
        }

        $id_izin = strtoupper(trim(htmlentities($_REQUEST['id_izin'], ENT_QUOTES)));
        $action = strtoupper(trim(htmlentities($_REQUEST['action'], ENT_QUOTES)));
        $keterangan_manager = trim(htmlentities($_REQUEST['keterangan_manager'], ENT_QUOTES));
        $id_staff_approval = $this->session->userdata('username');

        $check_data = $this->Main_model->get_izin_by_id($id_izin);

        if($check_data->num_rows() > 0){

            if($check_data->row()->status_izin != "0"){
                $return_arr = array("Status" => 'ERROR', "Message" => 'Data sudah tidak bisa dirubah');
                echo json_encode($return_arr);
                return;
            }

            if($action == 'SETUJU'){
                $status_izin = '1';
            } else if($action == 'TOLAK') {
                $status_izin = '2';
            } else if($action == 'DELETE'){
                $status_izin = '3';
            } else {
                $return_arr = array("Status" => 'ERROR', "Message" => 'Aksi tidak valid');
                echo json_encode($return_arr);
                return;
            }

            if($status_izin != '3'){
               $updated_data = compact('keterangan_manager', 'id_staff_approval', 'status_izin');
            } else {
               $updated_data = compact('status_izin');
            }

            if($this->Main_model->update_izin($updated_data, $id_izin)){
                $this->db->trans_commit();
                $return_arr = array("Status" => 'OK', "Message" => '');
            } else {
                $this->db->trans_rollback();
                $return_arr = array("Status" => 'ERROR', "Message" => 'Gagal mengupdate data');
            }

        } else {
            $return_arr = array("Status" => 'ERROR', "Message" => 'Data tidak ditemukan');
        }

        echo json_encode($return_arr);
    }


    function riwayat_belanja(){

        if($this->session->userdata('is_admin') != "1" && $this->session->userdata('is_admin') != "2" ){
            redirect(base_url('main'));
        }

        $this->load->view('template/admin_header');
        if(isset($_GET['customer']) && isset($_GET['vendor'])){
            // invalid
            return;
        } else if(isset($_GET['customer'])){
            $customer = htmlentities($_GET['customer'], ENT_QUOTES);
            $data_cust = $this->Main_model->get_customer_by_id($customer);
            if($data_cust->num_rows() > 0){
                $data['top_10s'] = $this->Main_model->get_top_10_product_per_customer($customer)->result_object();
                $data['person'] = $data_cust->row();

                $this->load->view('riwayat_belanja', $data);
            } else {
                // customer not found
            }

        } else if(isset($_GET['vendor'])){
            $vendor = htmlentities($_GET['vendor'], ENT_QUOTES);
            $data_vendor = $this->Main_model->get_vendor_by_id($vendor);

            if($data_vendor->num_rows() > 0){
                $data['top_10s'] = $this->Main_model->get_top_10_product_per_vendor($vendor)->result_object();
                $data['person'] = $data_vendor->row();

                $this->load->view('riwayat_belanja_vendor', $data);
            } else {
                // vendor not found
            }
        } else {
            // invalid
            return;
        }
        $this->load->view('template/admin_footer');

    }

    function get_riwayat_belanja_vendor(){
        if($this->session->userdata('is_admin') != "1" && $this->session->userdata('is_admin') != "2" ){
            redirect(base_url('main'));
        }

        // server-side pagination
        $draw = $_REQUEST['draw'];
        $length = $_REQUEST['length'];
        $start = $_REQUEST['start'];

        $output = array();
        $output['draw'] = $draw;
        $output['data']=array();

        if(isset($_GET['start']) && isset($_GET['end']) && isset($_GET['vendor'])){

            $vendor = htmlentities($_GET['vendor'], ENT_QUOTES);
            $start_date = htmlentities($_GET['start'], ENT_QUOTES);
            $end_date = htmlentities($_GET['end'], ENT_QUOTES);

            $output['data'] = $this->Main_model->get_order_vendor_m_by_vendor($vendor, $start_date, $end_date, $length, $start)->result_object();
            $output['recordsTotal'] = $output['recordsFiltered'] = $this->Main_model->get_order_vendor_m_by_vendor($vendor, $start_date, $end_date)->num_rows();

        } else {
            $output['data'] = '';
            $output['recordsTotal'] = $output['recordsFiltered'] = 0;
        }
        echo json_encode($output);
    }

    function get_riwayat_belanja_customer(){
        if($this->session->userdata('is_admin') != "1" && $this->session->userdata('is_admin') != "2" ){
            redirect(base_url('main'));
        }

        // server-side pagination
        $draw = $_REQUEST['draw'];
        $length = $_REQUEST['length'];
        $start = $_REQUEST['start'];

        $output = array();
        $output['draw'] = $draw;
        $output['data']=array();

        if(isset($_GET['start']) && isset($_GET['end']) && isset($_GET['customer'])){

            $customer = htmlentities($_GET['customer'], ENT_QUOTES);
            $start_date = htmlentities($_GET['start'], ENT_QUOTES);
            $end_date = htmlentities($_GET['end'], ENT_QUOTES);

            $output['data'] = $this->Main_model->get_order_m_by_customer($customer, $start_date, $end_date, $length, $start)->result_object();
            $output['recordsTotal'] = $output['recordsFiltered'] = $this->Main_model->get_order_m_by_customer($customer, $start_date, $end_date)->num_rows();

        } else {
            $output['data'] = '';
            $output['recordsTotal'] = $output['recordsFiltered'] = 0;
        }
        echo json_encode($output);
    }

    function laporan_pick_up(){

        if($this->session->userdata('is_admin') != "1" && $this->session->userdata('is_admin') != "3"){
            redirect(base_url('main'));
        }

        $data['staffs'] = $this->Main_model->get_staff()->result_object();

        $this->load->view('template/admin_header');
        $this->load->view('pick_up_per_staff', $data);
        $this->load->view('template/admin_footer');
    }

    function get_laporan_pick_up(){
        if($this->session->userdata('is_admin') != "1" && $this->session->userdata('is_admin') != "3"){
            redirect(base_url('main'));
        }

        date_default_timezone_set('Asia/Singapore');


        $id_staff = htmlentities($_GET['staff'], ENT_QUOTES);
        $periode = htmlentities($_GET['periode'], ENT_QUOTES);
        $bulan = htmlentities($_GET['bulan'], ENT_QUOTES);
        $tahun = htmlentities($_GET['tahun'], ENT_QUOTES);

        $temp_date = new DateTime("$tahun-$bulan-01");

        if($periode == "AWAL"){

            $tgl_awal = $tahun."-".sprintf('%02d', $bulan)."-01";
            $tgl_akhir = $tahun."-".sprintf('%02d', $bulan)."-15";

        } else if($periode == "AKHIR"){
            $tgl_awal = $tahun."-".sprintf('%02d', $bulan)."-16";
            $tgl_akhir = $tahun."-".sprintf('%02d', $bulan)."-".$temp_date->format('t');
        }

        // server-side pagination
        $draw = $_REQUEST['draw'];
        $length = $_REQUEST['length'];
        $start = $_REQUEST['start'];
        $search = trim(htmlentities($_REQUEST['search']["value"], ENT_QUOTES));

        $total = $this->Main_model->laporan_pick_up_per_staff($id_staff, $tgl_awal, $tgl_akhir)->num_rows();

        $output = array();
        $output['draw'] = $draw;
        $output['recordsTotal'] = $output['recordsFiltered'] = $total;
        $output['data']=array();


        $output['data'] = $this->Main_model->laporan_pick_up_per_staff($id_staff, $tgl_awal, $tgl_akhir, $search, $length, $start)->result_object();
        echo json_encode($output);
        return;
    }

    function laporan_delivery(){

        if($this->session->userdata('is_admin') != "1" && $this->session->userdata('is_admin') != "3"){
            redirect(base_url('main'));
        }

        $data['staffs'] = $this->Main_model->get_staff()->result_object();

        $this->load->view('template/admin_header');
        $this->load->view('delivery_per_staff', $data);
        $this->load->view('template/admin_footer');
    }

    function get_laporan_delivery(){

        if($this->session->userdata('is_admin') != "1" && $this->session->userdata('is_admin') != "3" ){
            redirect(base_url('main'));
        }

        date_default_timezone_set('Asia/Singapore');


        $id_staff = htmlentities($_GET['staff'], ENT_QUOTES);
        $periode = htmlentities($_GET['periode'], ENT_QUOTES);
        $bulan = htmlentities($_GET['bulan'], ENT_QUOTES);
        $tahun = htmlentities($_GET['tahun'], ENT_QUOTES);

        $temp_date = new DateTime("$tahun-$bulan-01");

        if($periode == "AWAL"){

            $tgl_awal = $tahun."-".sprintf('%02d', $bulan)."-01";
            $tgl_akhir = $tahun."-".sprintf('%02d', $bulan)."-15";

        } else if($periode == "AKHIR"){
            $tgl_awal = $tahun."-".sprintf('%02d', $bulan)."-16";
            $tgl_akhir = $tahun."-".sprintf('%02d', $bulan)."-".$temp_date->format('t');
        }

        // server-side pagination
        $draw = $_REQUEST['draw'];
        $length = $_REQUEST['length'];
        $start = $_REQUEST['start'];
        $search = trim(htmlentities($_REQUEST['search']["value"], ENT_QUOTES));

        $total = $this->Main_model->laporan_delivery_per_staff($id_staff, $tgl_awal, $tgl_akhir)->num_rows();

        $output = array();
        $output['draw'] = $draw;
        $output['recordsTotal'] = $output['recordsFiltered'] = $total;
        $output['data']=array();


        $output['data'] = $this->Main_model->laporan_delivery_per_staff($id_staff, $tgl_awal, $tgl_akhir, $search, $length, $start)->result_object();
        echo json_encode($output);
        return;

    }

    function add_jurnal_umum(){

        if($this->session->userdata('is_admin') != "1" ){
            redirect(base_url('main'));
        }

        $cash_flow = trim(htmlentities($_REQUEST['cash_flow'], ENT_QUOTES));

        $id_jurnal_umum = strtoupper(trim(htmlentities($_REQUEST['id_jurnal_umum'], ENT_QUOTES)));
        $tgl_jurnal_umum = strtoupper(trim(htmlentities($_REQUEST['tgl_jurnal_umum'], ENT_QUOTES)));
        $keterangan_jurnal_umum = trim(htmlentities($_REQUEST['keterangan_jurnal_umum'], ENT_QUOTES));
        $tipe_jurnal_umum = trim(htmlentities($_REQUEST['tipe_jurnal_umum'], ENT_QUOTES));
        $brand_jurnal_umum = trim(htmlentities($_REQUEST['brand_jurnal_umum'], ENT_QUOTES));
        $nominal = strtoupper(trim(htmlentities($_REQUEST['nominal'], ENT_QUOTES)));

        //validation
        $error = array();

        $this->db->trans_begin();

        if(empty($tgl_jurnal_umum) || !preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/",$tgl_jurnal_umum)){
            array_push($error, "invalid-tanggal");
        }

        if(empty($keterangan_jurnal_umum)){
            array_push($error, "invalid-keterangan");
        }

        if($tipe_jurnal_umum != "REK" && $tipe_jurnal_umum != "TUNAI"){
            array_push($error, "invalid-tipe");
        }

        if(empty($brand_jurnal_umum) || ($brand_jurnal_umum != "NONE" && $brand_jurnal_umum != "AHF" && $brand_jurnal_umum != "AF" && $brand_jurnal_umum != "AH" && $brand_jurnal_umum != "KA")){
            array_push($error, "invalid-brand");
        }

        if(empty($nominal)){
            array_push($error, "invalid-nominal");
        }


        if($cash_flow == "debet"){
            $debet_jurnal_umum = $nominal;
            $kredit_jurnal_umum = 0;
        } else if($cash_flow == "kredit"){
            $kredit_jurnal_umum = $nominal;
            $debet_jurnal_umum = 0;
        } else {
            array_push($error, "invalid-cashflow");
        }

        if(!empty($error)){
            $return_arr = array("Status" => 'FORMERROR', "Error" => $error);
            $this->db->trans_rollback();
            echo json_encode($return_arr);
            return;
        }

        $data = compact('tgl_jurnal_umum', 'keterangan_jurnal_umum', 'debet_jurnal_umum',
                        'kredit_jurnal_umum', 'tipe_jurnal_umum', 'brand_jurnal_umum');


        if($this->Main_model->get_jurnal_umum_by_id($id_jurnal_umum)->num_rows() == 0){
            if($this->Main_model->add_jurnal_umum($data)){
                $this->db->trans_commit();
                $return_arr = array("Status" => 'OK', "Message" => 'Berhasil ditambahkan');
            } else {
                $this->db->trans_rollback();
                $return_arr = array("Status" => 'ERROR', "Message" => 'Gagal menambahkan data');
            }
        } else {
            if($this->Main_model->update_jurnal_umum($data, $id_jurnal_umum)){
                $this->db->trans_commit();
                $return_arr = array("Status" => 'OK', "Message" => 'Berhasil diupdate');
            } else {
                $this->db->trans_rollback();
                $return_arr = array("Status" => 'ERROR', "Message" => 'Gagal mengupdate data');
            }

        }

        echo json_encode($return_arr);

    }

    function delete_jurnal_umum(){
        if($this->session->userdata('is_admin') != "1" ){
            redirect(base_url('main'));
        }

        $id_jurnal_umum = trim(htmlentities($_REQUEST['id_jurnal_umum'], ENT_QUOTES));

        if($this->Main_model->get_jurnal_umum_by_id($id_jurnal_umum)->num_rows() > 0){

            if($this->Main_model->delete_jurnal_umum($id_jurnal_umum)){
                $return_arr = array("Status" => 'OK', "Message" => 'Berhasil dihapus');
            } else {
                $return_arr = array("Status" => 'ERROR', "Message" => 'Gagal menghapus data');
            }

        } else {
            $return_arr = array("Status" => 'ERROR', "Message" => 'Data sudah dihapus');
        }

        echo json_encode($return_arr);
    }


    function problem_solving(){

        if($this->session->userdata('is_admin') != "1" ){
            redirect(base_url('main'));
        }

        $this->load->view('template/admin_header');
        $this->load->view('problem_solving');
        $this->load->view('template/admin_footer');
    }

    function add_problem_solving(){

        if($this->session->userdata('is_admin') != "1" ){
            redirect(base_url('main'));
        }

        date_default_timezone_set('Asia/Singapore');

        $error = array();

        $id_problem_solving = trim(htmlentities($_REQUEST['id_problem_solving'], ENT_QUOTES));
        $kode_problem_solving = "P".date("Ymd").$this->randStr(5);
        $no_order_problem_solving = trim(htmlentities($_REQUEST['no_order_problem_solving'], ENT_QUOTES));
        $topik_problem_solving = trim(htmlentities($_REQUEST['topik_problem_solving'], ENT_QUOTES));
        $detail_problem_solving = trim(htmlentities($_REQUEST['detail_problem_solving'], ENT_QUOTES));
        $solusi_problem_solving = trim(htmlentities($_REQUEST['solusi_problem_solving'], ENT_QUOTES));
        $active_problem_solving = '1';

        if(empty($topik_problem_solving)){
            array_push($error, "invalid-topik");
        }

        if(empty($detail_problem_solving)){
            array_push($error, "invalid-detail");
        }

        if(!empty($no_order_problem_solving)){
            $check_order_customer = $this->Main_model->get_order_m_by_no_order($no_order_problem_solving);
            $check_order_vendor = $this->Main_model->get_order_vendor_m_by_no_order($no_order_problem_solving);

            if($check_order_customer->num_rows() == 0 && $check_order_vendor->num_rows() == 0){
                array_push($error, "invalid-pesanan");
            }
        }

        if(!empty($error)){
            $return_arr = array("Status" => 'FORMERROR', "Error" => $error);
            $this->db->trans_rollback();
            echo json_encode($return_arr);
            return;
        }

        if($this->Main_model->get_problem_by_id($id_problem_solving)->num_rows() == 0){
            $timestamp_create = date('Y-m-d H:i:s');
            $username_create = $this->session->userdata('username');

            if(!empty($solusi_problem_solving)){
                $timestamp_solusi = $timestamp_create;
                $username_solusi = $username_create;
            } else {
                $timestamp_solusi = "";
                $username_solusi = "";
            }

            $data = compact('kode_problem_solving', 'no_order_problem_solving', 'topik_problem_solving',
                            'detail_problem_solving', 'solusi_problem_solving', 'timestamp_create', 'timestamp_solusi',
                            'username_create', 'username_solusi', 'active_problem_solving');

            $this->db->trans_begin();

            if($this->Main_model->add_problem_solving($data)){
                $this->db->trans_commit();
                $return_arr = array("Status" => 'OK', "Message" => '');
            } else {
                $this->db->trans_rollback();
                $return_arr = array("Status" => 'ERROR', "Message" => 'Gagal menambahkan problem');
            }

        } else {
            if(!empty($solusi_problem_solving)){
                $timestamp_solusi = date('Y-m-d H:i:s');
                $username_solusi = $this->session->userdata('username');
            } else {
                $timestamp_solusi = "";
                $username_solusi = "";
            }

            $updated_data = compact('no_order_problem_solving', 'topik_problem_solving', 'detail_problem_solving',
                            'solusi_problem_solving', 'timestamp_solusi', 'username_solusi');

            if($this->Main_model->update_problem_solving($updated_data, $id_problem_solving)){
                $this->db->trans_commit();
                $return_arr = array("Status" => 'OK', "Message" => '');
            } else {
                $this->db->trans_rollback();
                $return_arr = array("Status" => 'ERROR', "Message" => 'Gagal mengupdate problem');
            }
        }

        echo json_encode($return_arr);


    }

    function delete_problem_solving(){

        if($this->session->userdata('is_admin') != "1" ){
            redirect(base_url('main'));
        }

        $id_problem_solving = strtoupper(trim(htmlentities($_REQUEST['id_problem_solving'], ENT_QUOTES)));
        $problem_data = $this->Main_model->get_problem_by_id($id_problem_solving);

        if($problem_data->num_rows() == 0){
            $return_arr = array("Status" => 'ERROR', "Message" => 'Data tidak ditemukan');
            echo json_encode($return_arr);
            return;
        }

        if($problem_data->row()->active_problem_solving == '0'){
            $return_arr = array("Status" => 'ERROR', "Message" => 'Data sudah dihapus');
            echo json_encode($return_arr);
            return;
        }

        $active_problem_solving = '0';
        $updated_data = compact('active_problem_solving');

        if($this->Main_model->update_problem_solving($updated_data, $id_problem_solving)){
            $this->db->trans_commit();
            $return_arr = array("Status" => 'OK', "Message" => 'Problem berhasil dihapus');
        } else {
            $this->db->trans_rollback();
            $return_arr = array("Status" => 'ERROR', "Message" => 'Gagal menghapus problem');
        }

        echo json_encode($return_arr);

    }

    function get_problem_solving(){

        if($this->session->userdata('is_admin') != "1" ){
            redirect(base_url('main'));
        }

        // server-side pagination
        $draw = $_REQUEST['draw'];
        $length = $_REQUEST['length'];
        $start = $_REQUEST['start'];
        $search = trim(htmlentities($_REQUEST['search']["value"], ENT_QUOTES));

        $total = $this->Main_model->get_problem_solving()->num_rows();

        $output = array();
        $output['draw'] = $draw;
        $output['recordsTotal'] = $output['recordsFiltered'] = $total;
        $output['data']=array();

        if(isset($_GET['status'])){
            $status = htmlentities($_GET['status'], ENT_QUOTES);
        } else {
            $status = "all";
        }

        $output['data'] = $this->Main_model->get_problem_solving($status, $search, $length, $start)->result_object();

        echo json_encode($output);
    }

    function laporan_transaksi(){
        if($this->session->userdata('is_admin') != "1" ){
            redirect(base_url('main'));
        }

        $this->load->view('template/admin_header');
        $this->load->view('jurnal_umum');
        $this->load->view('template/admin_footer');
    }

    function get_data_laporan(){

        if($this->session->userdata('is_admin') != "1" ){
            redirect(base_url('main'));
        }

        // server-side pagination
        $draw = $_REQUEST['draw'];
        $length = $_REQUEST['length'];
        $start = $_REQUEST['start'];

        $output = array();
        $output['draw'] = $draw;
        $output['data']=array();

        if(isset($_GET['start']) && isset($_GET['end']) && isset($_GET['brand']) && isset($_GET['tipe']) && isset($_GET['flow'])){

            $start_date = htmlentities($_GET['start'], ENT_QUOTES);
            $end_date = htmlentities($_GET['end'], ENT_QUOTES);
            $brand_order = htmlentities($_GET['brand'], ENT_QUOTES);
            $tipe_order = htmlentities($_GET['tipe'], ENT_QUOTES);
            $cash_flow = htmlentities($_GET['flow'], ENT_QUOTES);


            $output['data'] = $this->Main_model->jurnal_umum_gabung($start_date, $end_date, $brand_order, $tipe_order, $cash_flow, false, $length, $start)->result_object();
            $output['recordsTotal'] = $output['recordsFiltered'] = $this->Main_model->jurnal_umum_gabung($start_date, $end_date, $brand_order, $tipe_order, $cash_flow, false)->num_rows();


        } else {
            $output['data'] = '';
            $output['recordsTotal'] = $output['recordsFiltered'] = 0;
        }

        echo json_encode($output);
    }

    function laporan_produk(){

        if($this->session->userdata('is_admin') != "1" ){
            redirect(base_url('main'));
        }

        $this->load->view('template/admin_header');
        $this->load->view('laporan_produk');
        $this->load->view('template/admin_footer');
    }

    function get_laporan_produk(){

        if($this->session->userdata('is_admin') != "1" ){
            redirect(base_url('main'));
        }

        // server-side pagination
        $draw = $_REQUEST['draw'];
        $length = $_REQUEST['length'];
        $start = $_REQUEST['start'];
        $search = trim(htmlentities($_REQUEST['search']["value"], ENT_QUOTES));

        $output = array();
        $output['draw'] = $draw;
        $output['data']=array();

        if(isset($_GET['start']) && isset($_GET['end'])){

            $start_date = htmlentities($_GET['start'], ENT_QUOTES);
            $end_date = htmlentities($_GET['end'], ENT_QUOTES);


            $output['data'] = $this->Main_model->laporan_produk($start_date, $end_date, $search, $length, $start)->result_object();
            $output['recordsTotal'] = $output['recordsFiltered'] = $this->Main_model->laporan_produk($start_date, $end_date)->num_rows();


        } else {
            $output['data'] = '';
            $output['recordsTotal'] = $output['recordsFiltered'] = 0;
        }

        echo json_encode($output);
    }

    function laporan_sales(){

        if($this->session->userdata('is_admin') != "1" ){
            redirect(base_url('main'));
        }

        $this->load->view('template/admin_header');
        $this->load->view('laporan_sales');
        $this->load->view('template/admin_footer');
    }

    function get_laporan_sales(){

        if($this->session->userdata('is_admin') != "1" ){
            redirect(base_url('main'));
        }

        // server-side pagination
        $draw = $_REQUEST['draw'];
        $length = $_REQUEST['length'];
        $start = $_REQUEST['start'];
        $search = trim(htmlentities($_REQUEST['search']["value"], ENT_QUOTES));

        $output = array();
        $output['draw'] = $draw;
        $output['data']=array();

        if(isset($_GET['start']) && isset($_GET['end'])){

            $start_date = htmlentities($_GET['start'], ENT_QUOTES);
            $end_date = htmlentities($_GET['end'], ENT_QUOTES);


            $output['data'] = $this->Main_model->laporan_sales($start_date, $end_date, $search, $length, $start)->result_object();
            $output['recordsTotal'] = $output['recordsFiltered'] = $this->Main_model->laporan_sales($start_date, $end_date)->num_rows();


        } else {
            $output['data'] = '';
            $output['recordsTotal'] = $output['recordsFiltered'] = 0;
        }

        echo json_encode($output);
    }

    function laporan_purchase(){
        if($this->session->userdata('is_admin') != "1" ){
            redirect(base_url('main'));
        }

        $this->load->view('template/admin_header');
        $this->load->view('laporan_purchase');
        $this->load->view('template/admin_footer');
    }

    function get_laporan_purchase(){

        if($this->session->userdata('is_admin') != "1" ){
            redirect(base_url('main'));
        }

        // server-side pagination
        $draw = $_REQUEST['draw'];
        $length = $_REQUEST['length'];
        $start = $_REQUEST['start'];
        $search = trim(htmlentities($_REQUEST['search']["value"], ENT_QUOTES));

        $output = array();
        $output['draw'] = $draw;
        $output['data']=array();

        if(isset($_GET['start']) && isset($_GET['end'])){

            $start_date = htmlentities($_GET['start'], ENT_QUOTES);
            $end_date = htmlentities($_GET['end'], ENT_QUOTES);


            $output['data'] = $this->Main_model->laporan_purchase($start_date, $end_date, $search, $length, $start)->result_object();
            $output['recordsTotal'] = $output['recordsFiltered'] = $this->Main_model->laporan_purchase($start_date, $end_date)->num_rows();


        } else {
            $output['data'] = '';
            $output['recordsTotal'] = $output['recordsFiltered'] = 0;
        }

        echo json_encode($output);

    }

    function slip_gaji(){

//        $id_staff = $this->session->userdata('id_staff');

        $this->load->view('template/admin_header');
        $this->load->view('slip_gaji');
        $this->load->view('template/admin_footer');

    }

    function delete_product(){
        if($this->session->userdata('is_admin') != "1" ){
            redirect(base_url('main'));
        }

        $id_product = strtoupper(trim(htmlentities($_REQUEST['id_product'], ENT_QUOTES)));

        $product_data = $this->Main_model->get_product_by_id($id_product);

        if($product_data->num_rows() == 0){
            $return_arr = array("Status" => 'ERROR', "Message" => 'Data tidak ditemukan');
            echo json_encode($return_arr);
            return;
        }

        if($product_data->row()->active_product == '0'){
            $return_arr = array("Status" => 'ERROR', "Message" => 'Produk sudah dihapus');
            echo json_encode($return_arr);
            return;
        }

        // change active product

        $active_product = '0';
        $updated_data_product = compact('active_product');

        if($this->Main_model->update_product($updated_data_product, $id_product)){

            // delete stok in and out data
            // if($this->Main_model->delete_stok_in_out_by_product($id_product)) {
                $return_arr = array("Status" => 'OK', "Message" => 'Data berhasil dihapus');
                echo json_encode($return_arr);
                return;
            // } else {
            //     $return_arr = array("Status" => 'ERROR', "Message" => 'Gagal menghapus data stok');
            //     echo json_encode($return_arr);
            //     return;
            // }

        } else {
            $return_arr = array("Status" => 'ERROR', "Message" => 'Gagal menghapus produk');
            echo json_encode($return_arr);
            return;
        }


    }

    function delete_delivery(){
        if($this->session->userdata('is_admin') != "1" ){
            redirect(base_url('main'));
        }

        $id_delivery = strtoupper(trim(htmlentities($_REQUEST['id_delivery'], ENT_QUOTES)));

        $delivery_data = $this->Main_model->get_delivery_by_id($id_delivery);

        if($delivery_data->num_rows() == 0){
            $return_arr = array("Status" => 'ERROR', "Message" => 'Data tidak ditemukan');
            echo json_encode($return_arr);
            return;
        }

        if($delivery_data->row()->status_delivery != '0'){
            $return_arr = array("Status" => 'ERROR', "Message" => 'Data tidak bisa dihapus karena sedang dikirim atau sudah dikirm');
            echo json_encode($return_arr);
            return;
        }

        if($this->Main_model->delete_delivery($id_delivery)){
            $return_arr = array("Status" => 'OK', "Message" => 'Data berhasil dihapus');
            echo json_encode($return_arr);
            return;
        } else {
            $return_arr = array("Status" => 'ERROR', "Message" => 'Gagal menghapus data');
            echo json_encode($return_arr);
            return;
        }

    }

    function delete_pick_up(){
        if($this->session->userdata('is_admin') != "1" ){
            redirect(base_url('main'));
        }

        $id_pick_up = strtoupper(trim(htmlentities($_REQUEST['id_pick_up'], ENT_QUOTES)));

        //Check if editable
        $pick_up_data = $this->Main_model->get_pick_up_by_id($id_pick_up);

        if($pick_up_data->num_rows() == 0){
            $return_arr = array("Status" => 'ERROR', "Message" => 'Data pick up tidak ditemukan');
            echo json_encode($return_arr);
            return;
        }

        if($pick_up_data->row()->status_pick_up != '0'){
            $return_arr = array("Status" => 'ERROR', "Message" => 'Data tidak bisa dihapus karena sudah selesai pick up');
            echo json_encode($return_arr);
            return;
        }

        if($this->Main_model->delete_pick_up($id_pick_up)){
            $return_arr = array("Status" => 'OK', "Message" => 'Data berhasil dihapus');
            echo json_encode($return_arr);
            return;
        } else {
            $return_arr = array("Status" => 'ERROR', "Message" => 'Gagal menghapus data');
            echo json_encode($return_arr);
            return;
        }


    }

    function delete_stok_in_out(){
        if($this->session->userdata('is_admin') != "1" ){
            redirect(base_url('main'));
        }

        $id_stok_in_out = strtoupper(trim(htmlentities($_REQUEST['id_stok_in_out'], ENT_QUOTES)));

        //Check if editable
        $stok_in_out_data = $this->Main_model->get_stok_in_out_by_id($id_stok_in_out);

        if($stok_in_out_data->num_rows() == 0){
            $return_arr = array("Status" => 'ERROR', "Message" => 'Data stok tidak ditemukan');
            echo json_encode($return_arr);
            return;
        }

        if($this->Main_model->delete_stok_in_out_by_id($id_stok_in_out)){
            $return_arr = array("Status" => 'OK', "Message" => 'Data berhasil dihapus');
        } else {
            $return_arr = array("Status" => 'ERROR', "Message" => 'Gagal menghapus data');
        }

        echo json_encode($return_arr);
        return;


    }

    function update_status_order_vendor_m(){
        if($this->session->userdata('is_admin') == "0"){
            $return_arr = array("Status" => 'ERROR', "Message" => 'Gagal! Fitur khusus admin.');
            echo json_encode($return_arr);
            return;
        }

        $no_order = strtoupper(trim(htmlentities($_REQUEST['no_order'], ENT_QUOTES)));
        $status_order = trim(htmlentities($_REQUEST['status_order'], ENT_QUOTES));

        $order_vendor_m_data = $this->Main_model->get_order_vendor_detail($no_order)->row();

        if($order_vendor_m_data->status_order_vendor == "0"){
            $return_arr = array("Status" => 'ERROR', "Message" => 'Order sudah dihapus');
            echo json_encode($return_arr);
            return;
        }


        // check if order has delivery, else order sudah ada delivery
        if($order_vendor_m_data->status_pick_up == "0" || $order_vendor_m_data->status_pick_up == "1"){
            $return_arr = array("Status" => 'ERROR', "Message" => 'Order tidak bisa dihapus karena sudah ada pick up');
            echo json_encode($return_arr);
            return;
        }

        if($status_order == "delete"){
            $status_order_vendor = "0";
        } else {
            $return_arr = array("Status" => 'ERROR', "Message" => 'Status tidak valid!');
            echo json_encode($return_arr);
            return;
        }

        $updated_data = compact('status_order_vendor');

        if($this->Main_model->update_order_vendor_m($updated_data, $order_vendor_m_data->id_order_vendor_m)){

            // =========== Delete from stok_in_out ============

            if($this->Main_model->delete_stok_in_out_by_order($no_order)){
                $this->db->trans_commit();
                $return_arr = array("Status" => 'OK', "Message" => 'Data berhasil dihapus');
            } else {
                $this->db->trans_rollback();
                $return_arr = array("Status" => 'ERROR', "Message" => 'Gagal mengupdate data (ERR2)');
            }

            // ===========================================


        } else {
            $this->db->trans_rollback();
            $return_arr = array("Status" => 'ERROR', "Message" => 'Gagal mengupdate data (ERR1)');
        }

        echo json_encode($return_arr);

    }

    function update_status_order_m(){
        if($this->session->userdata('is_admin') == "0"){
            $return_arr = array("Status" => 'ERROR', "Message" => 'Gagal! Fitur khusus admin.');
            echo json_encode($return_arr);
            return;
        }

        $no_order = strtoupper(trim(htmlentities($_REQUEST['no_order'], ENT_QUOTES)));
        $status_order = trim(htmlentities($_REQUEST['status_order'], ENT_QUOTES));

        $order_m_data = $this->Main_model->get_order_m_by_no_order($no_order)->row();

        // check if order is status = 1, else order sudah dihapus
        if($order_m_data->status_order == "0"){
            $return_arr = array("Status" => 'ERROR', "Message" => 'Order sudah dihapus');
            echo json_encode($return_arr);
            return;
        }

        // check if order has delivery, else order sudah ada delivery
        if($order_m_data->status_delivery == "0" || $order_m_data->status_delivery == "1" || $order_m_data->status_delivery == "2"){
            $return_arr = array("Status" => 'ERROR', "Message" => 'Order tidak bisa dihapus karena sudah ada delivery');
            echo json_encode($return_arr);
            return;
        }

        if($status_order == "delete"){
            $status_order = "0";
        } else {
            $return_arr = array("Status" => 'ERROR', "Message" => 'Status tidak valid!');
            echo json_encode($return_arr);
            return;
        }

        $updated_data = compact('status_order');

        if($this->Main_model->update_order_m($updated_data, $order_m_data->id_order_m)){

            // =========== Delete from stok_in_out ============

            if($this->Main_model->delete_stok_in_out_by_order($no_order)){
                $this->db->trans_commit();
                $return_arr = array("Status" => 'OK', "Message" => 'Data berhasil dihapus');
            } else {
                $this->db->trans_rollback();
                $return_arr = array("Status" => 'ERROR', "Message" => 'Gagal mengupdate data (ERR2)');
            }

            // ===========================================

        } else {
            $this->db->trans_rollback();
            $return_arr = array("Status" => 'ERROR', "Message" => 'Gagal mengupdate data');
        }

        echo json_encode($return_arr);
    }

    function salary_form(){

        if($this->session->userdata('is_admin') != "1" ){
            redirect(base_url('main'));
        }

        $data['staffs'] = $this->Main_model->get_staff()->result_object();

        $this->load->view('template/admin_header');
        $this->load->view('salary_form', $data);
        $this->load->view('template/admin_footer');
    }

    function get_staff_salary(){

        if($this->session->userdata('is_admin') != "1" ){
            redirect(base_url('main'));
        }

        date_default_timezone_set('Asia/Singapore');

        $id_staff = htmlentities($_REQUEST['id_staff'], ENT_QUOTES);
        $awal_akhir_salary = htmlentities($_REQUEST['awal_akhir_salary'], ENT_QUOTES);
        $bulan_salary = htmlentities($_REQUEST['bulan_salary'], ENT_QUOTES);
        $tahun_salary = htmlentities($_REQUEST['tahun_salary'], ENT_QUOTES);

        $temp_date = new DateTime("$tahun_salary-$bulan_salary-01");

        // Periode Delivery
        if($awal_akhir_salary == "AWAL"){

            $tgl_awal = $tahun_salary."-".sprintf('%02d', $bulan_salary)."-01";
            $tgl_akhir = $tahun_salary."-".sprintf('%02d', $bulan_salary)."-15";

        } else if($awal_akhir_salary == "AKHIR"){
            $tgl_awal = $tahun_salary."-".sprintf('%02d', $bulan_salary)."-16";
            $tgl_akhir = $tahun_salary."-".sprintf('%02d', $bulan_salary)."-".$temp_date->format('t');
        }

        $data = $this->Main_model->get_staff_salary($id_staff, $awal_akhir_salary, $bulan_salary, $tahun_salary, $tgl_awal, $tgl_akhir);

        echo json_encode($data->row());
        return;
    }

    function save_salary(){

        if($this->session->userdata('is_admin') != "1" ){
            redirect(base_url('main'));
        }

        $id_staff = trim(htmlentities($_REQUEST['id_staff'], ENT_QUOTES));
        $awal_akhir_salary = trim(htmlentities($_REQUEST['awal_akhir_salary'], ENT_QUOTES));
        $bulan_salary = trim(htmlentities($_REQUEST['bulan_salary'], ENT_QUOTES));
        $tahun_salary = trim(htmlentities($_REQUEST['tahun_salary'], ENT_QUOTES));
        $fee_penjualan_salary = trim(htmlentities($_REQUEST['fee_penjualan_salary'], ENT_QUOTES));
        $lembur_salary = trim(htmlentities($_REQUEST['lembur_salary'], ENT_QUOTES));
        $kas_bon_salary = trim(htmlentities($_REQUEST['kas_bon_salary'], ENT_QUOTES));
        $potongan_kas_bon_salary = trim(htmlentities($_REQUEST['potongan_kas_bon_salary'], ENT_QUOTES));
        $THR_salary = trim(htmlentities($_REQUEST['THR_salary'], ENT_QUOTES));
        $lain_lain_salary = trim(htmlentities($_REQUEST['lain_lain_salary'], ENT_QUOTES));
        $catatan_lain_lain = trim(htmlentities($_REQUEST['catatan_lain_lain'], ENT_QUOTES));
        $kuota_internet_salary = trim(htmlentities($_REQUEST['kuota_internet_salary'], ENT_QUOTES));


        $this->db->trans_begin();

        if($awal_akhir_salary == "AWAL"){
            //from 1 to 15
        } else if($awal_akhir_salary == "AKHIR"){
            //from 15 to end of month
        }

        $salary_data = $this->Main_model->get_salary_only($id_staff, $awal_akhir_salary, $bulan_salary, $tahun_salary);

        if($salary_data->num_rows() == 0){

            $data = compact('id_staff', 'awal_akhir_salary','bulan_salary', 'tahun_salary',
                'fee_penjualan_salary', 'lembur_salary', 'kas_bon_salary', 'potongan_kas_bon_salary',
                'THR_salary', 'lain_lain_salary', 'catatan_lain_lain', 'kuota_internet_salary');

            if($this->Main_model->add_salary($data)){
                $this->db->trans_commit();
                $return_arr = array("Status" => 'OK', "Message" => 'Berhasil tersimpan');
            } else {
                $this->db->trans_rollback();
                $return_arr = array("Status" => 'ERROR', "Message" => 'ERROR: Gagal menyimpan data!');
            }

        } else {

            $id_salary = $salary_data->row()->id_salary;
            $updated_data = compact('fee_penjualan_salary', 'lembur_salary', 'kas_bon_salary', 'potongan_kas_bon_salary',
                'THR_salary', 'lain_lain_salary', 'catatan_lain_lain', 'kuota_internet_salary');

            if($this->Main_model->update_salary($updated_data, $id_salary)){
                $this->db->trans_commit();
                $return_arr = array("Status" => 'OK', "Message" => 'Berhasil tersimpan');
            } else {
                $this->db->trans_rollback();
                $return_arr = array("Status" => 'ERROR', "Message" => 'ERROR: Gagal menyimpan data!');
            }

        }

        echo json_encode($return_arr);

    }

    function update_pick_up(){

        if($this->session->userdata('is_admin') != "1" && $this->session->userdata('is_admin') != "3"){
            redirect(base_url('main'));
        }

        $alamat_pick_up = strtoupper(trim(htmlentities($_REQUEST['alamat_pick_up'], ENT_QUOTES)));
        $no_hp_pick_up = strtoupper(trim(htmlentities($_REQUEST['no_hp_pick_up'], ENT_QUOTES)));
        $tgl_pick_up = strtoupper(trim(htmlentities($_REQUEST['tgl_pick_up'], ENT_QUOTES)));
        $catatan_pick_up = trim(htmlentities($_REQUEST['catatan_pick_up'], ENT_QUOTES));
        $id_staff = strtoupper(trim(htmlentities($_REQUEST['id_staff'], ENT_QUOTES)));
        $id_pick_up = strtoupper(trim(htmlentities($_REQUEST['id_pick_up'], ENT_QUOTES)));

        if(empty($tgl_pick_up)){
            $return_arr = array("Status" => 'ERROR', "Message" => 'Tanggal pick up tidak boleh kosong');
            echo json_encode($return_arr);
            return;
        }

        //Check if editable
        $pick_up_data = $this->Main_model->get_pick_up_by_id($id_pick_up);

        if($pick_up_data->num_rows() == 0){
            $return_arr = array("Status" => 'ERROR', "Message" => 'Data pick up tidak ditemukan');
            echo json_encode($return_arr);
            return;
        }

        if($pick_up_data->row()->status_pick_up != '0'){
            $return_arr = array("Status" => 'ERROR', "Message" => 'Data tidak bisa diupdate karena sudah selesai pick up');
            echo json_encode($return_arr);
            return;
        }

        $updated_data = compact('alamat_pick_up', 'no_hp_pick_up', 'tgl_pick_up', 'catatan_pick_up', 'id_staff');

        $this->db->trans_begin();

        if($this->Main_model->update_pick_up($updated_data, $id_pick_up)){
            $this->db->trans_commit();
            $return_arr = array("Status" => 'OK', "Message" => 'Berhasil diupdate');
        } else {
            $this->db->trans_rollback();
            $return_arr = array("Status" => 'ERROR', "Message" => 'Gagal mengupdate data');
        }

        echo json_encode($return_arr);

    }

    function pick_up_detail(){

        if($this->session->userdata('is_admin') != "1" && $this->session->userdata('is_admin') != "3" ){
            redirect(base_url('main'));
        }

        $this->load->view('template/admin_header');

        if(isset($_GET['id'])){

            $data_pick_up = $this->Main_model->get_pick_up_by_id(htmlentities($_GET['id'], ENT_QUOTES));

            if($data_pick_up->num_rows() == 0){

            } else {
                $data['pick_up'] = $data_pick_up->result_object();
                $data['orders'] = $this->Main_model->get_order_vendor_s($data_pick_up->row()->id_order_vendor_m)->result_object();
                $this->load->view('pick_up_detail', $data);
            }


        } else {
            // kosong
        }

        $this->load->view('template/admin_footer');
    }

    function get_pick_up(){
        // server-side pagination
        $draw = $_REQUEST['draw'];
        $length = $_REQUEST['length'];
        $start = $_REQUEST['start'];
        $search = trim(htmlentities($_REQUEST['search']["value"], ENT_QUOTES));

        $total = $this->Main_model->get_pick_up()->num_rows();

        $output = array();
        $output['draw'] = $draw;
        $output['recordsTotal'] = $output['recordsFiltered'] = $total;
        $output['data']=array();

        if(isset($_GET['status'])){
            $status = htmlentities($_GET['status'], ENT_QUOTES);
        } else {
            $status = 'all';
        }

        if($this->session->userdata('is_admin') == "0"){
            $output['data'] = $this->Main_model->get_pick_up($search, false, $this->session->userdata('id_staff'), $length, $start, $status)->result_object();
        } else {
            $output['data'] = $this->Main_model->get_pick_up($search, true, 0, $length, $start, $status)->result_object();
        }


        echo json_encode($output);
    }

    function update_pick_up_status(){
        date_default_timezone_set('Asia/Singapore');

        $id_pick_up = trim(htmlentities($_REQUEST['id_pick_up'], ENT_QUOTES));
        $status_pick_up = trim(htmlentities($_REQUEST['status'], ENT_QUOTES));

        $pick_up_data = $this->Main_model->get_pick_up_by_id($id_pick_up);

        if($pick_up_data->num_rows() > 0){
            // If OTW is chosen (from NEW to OTW)
            if($status_pick_up == '1'){
                if($pick_up_data->row()->status_pick_up != '0'){
                    $return_arr = array("Status" => 'ERROR', "Message" => 'Tidak dapat mengubah data karena pick up sudah selesai');
                    echo json_encode($return_arr);
                    return;
                }
                else {
                    $timestamp_pick_up = date('Y-m-d H:i:s');
                    $updated_data = compact('status_pick_up', 'timestamp_pick_up');

                    if($this->Main_model->update_pick_up($updated_data, $id_pick_up)){
                        $return_arr = array("Status" => 'OK', "Message" => 'Berhasil! Status pick up: SELESAI');
                    } else {
                        $return_arr = array("Status" => 'ERROR', "Message" => 'Gagal mengupdate data. Hubungi admin (code: updateError1)');
                    }
                }
            }

        } else {
            $return_arr = array("Status" => 'ERROR', "Message" => 'Data pick up tidak ditemukan');
        }

        echo json_encode($return_arr);

    }

    function pick_up_list(){
        $this->load->view('template/admin_header');
        $this->load->view('pickup_list');
        $this->load->view('template/admin_footer');
    }

    function pick_up_form()
    {
        if($this->session->userdata('is_admin') != "1" && $this->session->userdata('is_admin') != "3"){
            redirect(base_url('main'));
        }

        $this->load->view('template/admin_header');
        $this->load->view('pickup_form');
        $this->load->view('template/admin_footer');
    }

    function add_pick_up(){

        if($this->session->userdata('is_admin') != "1" && $this->session->userdata('is_admin') != "3"){
            redirect(base_url('main'));
        }

        if(!isset($_REQUEST['id_staff'])){
            $return_arr = array("Status" => 'ERROR', "Message" => 'Staff tidak boleh kosong');
            echo json_encode($return_arr);
            return;
        }

        $id_staff = strtoupper(trim(htmlentities($_REQUEST['id_staff'], ENT_QUOTES)));

        //check if staff exists
        if ($this->Main_model->get_staff_by_id($id_staff)->num_rows() == 0){
            $return_arr = array("Status" => 'ERROR', "Message" => 'Staff tidak ditemukan');
            echo json_encode($return_arr);
            return;
        }

        if(!isset($_REQUEST['id_order_vendor_m'])){
            $return_arr = array("Status" => 'ERROR', "Message" => 'Order tidak boleh kosong');
            echo json_encode($return_arr);
            return;
        }

        $id_vendor = strtoupper(trim(htmlentities($_REQUEST['id_vendor'], ENT_QUOTES)));
        $id_order_vendor_m = strtoupper(trim(htmlentities($_REQUEST['id_order_vendor_m'], ENT_QUOTES)));

        $data_order_m = $this->Main_model->get_order_vendor_m_by_id($id_order_vendor_m);

        //check if order exists
        if($data_order_m->num_rows() == 0){
            $return_arr = array("Status" => 'ERROR', "Message" => 'Order Vendor tidak ditemukan');
            echo json_encode($return_arr);
            return;
        }

        // check if venodr and order matches
        if($id_vendor != $data_order_m->row()->id_vendor){
            $return_arr = array("Status" => 'ERROR', "Message" => 'ERROR: Data mismatch (1)');
            echo json_encode($return_arr);
            return;
        }

        //check if the chosen order udah ada data pick up
        if($this->Main_model->get_pick_up_by_order_vendor_m($id_order_vendor_m)->num_rows() > 0){
            $return_arr = array("Status" => 'ERROR', "Message" => 'Sudah ada pick up untuk pesanan ini');
            echo json_encode($return_arr);
            return;
        }

        //check if the chosen order statusnya tidak 0-dibatalkan
        if($data_order_m->row()->status_order_vendor != '1'){
            $return_arr = array("Status" => 'ERROR', "Message" => 'Order sudah dibatalkan');
            echo json_encode($return_arr);
            return;
        }


        date_default_timezone_set('Asia/Singapore');

        $alamat_pick_up = trim(htmlentities($_REQUEST['alamat_pick_up'], ENT_QUOTES));
        $no_hp_pick_up = strtoupper(trim(htmlentities($_REQUEST['no_hp_pick_up'], ENT_QUOTES)));
        $id_order_vendor_m = strtoupper(trim(htmlentities($_REQUEST['id_order_vendor_m'], ENT_QUOTES)));
        $tgl_pick_up = strtoupper(trim(htmlentities($_REQUEST['tgl_pick_up'], ENT_QUOTES)));
        $catatan_pick_up = trim(htmlentities($_REQUEST['catatan_pick_up'], ENT_QUOTES));
        $id_staff = strtoupper(trim(htmlentities($_REQUEST['id_staff'], ENT_QUOTES)));

        $status_pick_up = '0';

        if(empty($tgl_pick_up)){
            $return_arr = array("Status" => 'ERROR', "Message" => 'Tanggal pick up tidak boleh kosong');
            echo json_encode($return_arr);
            return;
        }

        $data = compact('id_vendor', 'alamat_pick_up', 'no_hp_pick_up', 'id_order_vendor_m', 'tgl_pick_up',
                        'catatan_pick_up', 'id_staff', 'status_pick_up');

        $this->db->trans_begin();

        if($this->Main_model->add_pick_up($data)){
            $this->db->trans_commit();
            $return_arr = array("Status" => 'OK', "Message" => 'Berhasil ditambahkan');
        } else {
            $this->db->trans_rollback();
            $return_arr = array("Status" => 'ERROR', "Message" => 'Gagal menambahkan pick up');
        }

        echo json_encode($return_arr);

    }

    function order_vendor_detail(){

        if($this->session->userdata('is_admin') != "1" && $this->session->userdata('is_admin') != "2" ){
            redirect(base_url('main'));
        }

        $this->load->view('template/admin_header');

        if(isset($_GET['no'])){

            $data_order = $this->Main_model->get_order_vendor_detail(htmlentities($_GET['no'], ENT_QUOTES));

            if($data_order->num_rows() == 0){
                // kosong
            } else {
                $data['orders'] = $data_order->result_object();
                $this->load->view('order_vendor_detail', $data);
            }

        } else {
            // kosong
        }

        $this->load->view('template/admin_footer');
    }

    function order_vendor_list(){
        if($this->session->userdata('is_admin') != "1" && $this->session->userdata('is_admin') != "2" ){
            redirect(base_url('main'));
        }

        $this->load->view('template/admin_header');
        $this->load->view('order_vendor_list');
        $this->load->view('template/admin_footer');
    }

    function get_order_vendor_s(){
        $id_order_vendor_m = htmlentities($_REQUEST['id_order_vendor_m'], ENT_QUOTES);
        $data = $this->Main_model->get_order_vendor_s($id_order_vendor_m);
        echo json_encode($data->result_object());
        return;

    }

    function get_order_vendor_m(){

        if($this->session->userdata('is_admin') == "0" ){
            redirect(base_url('main'));
        }

        // server-side pagination
        $draw = $_REQUEST['draw'];
        $length = $_REQUEST['length'];
        $start = $_REQUEST['start'];
        $search = trim(htmlentities($_REQUEST['search']["value"], ENT_QUOTES));

        $output = array();
        $output['draw'] = $draw;

        $output['data']=array();

        if(isset($_GET['status'])){
            $status = htmlentities($_GET['status'], ENT_QUOTES);
        } else {
            $status = 'all';
        }

        if(isset($_GET['brand'])){
            $brand = htmlentities($_GET['brand'], ENT_QUOTES);
        } else {
            $brand = "all";
        }

        if(!isset($_GET['pick_up'])){
            $total = $this->Main_model->get_order_vendor_m($search, 10000000000, 0, $status, $brand)->num_rows();
            $output['data'] = $this->Main_model->get_order_vendor_m($search, $length, $start, $status, $brand)->result_object();
        } else {
            $total = $this->Main_model->get_order_vendor_m_pickup($search, 10000000000, 0, $status, $brand)->num_rows();
            $output['data'] = $this->Main_model->get_order_vendor_m_pickup($search, $length, $start, $status, $brand)->result_object();
        }

        $output['recordsTotal'] = $output['recordsFiltered'] = $total;

        echo json_encode($output);
    }

    function update_order_vendor(){

        if($this->session->userdata('is_admin') != "1" && $this->session->userdata('is_admin') != "2" ){
            redirect(base_url('main'));
        }

        $no_order = strtoupper(trim(htmlentities($_REQUEST['no_order'], ENT_QUOTES)));
        $catatan_order_vendor = trim(htmlentities($_REQUEST['catatan_order_vendor'], ENT_QUOTES));
        $tgl_order_vendor = strtoupper(trim(htmlentities($_REQUEST['tgl_order_vendor'], ENT_QUOTES)));
        $is_paid_vendor = strtoupper(trim(htmlentities($_REQUEST['is_paid_vendor'], ENT_QUOTES)));
        $payment_detail = trim(htmlentities($_REQUEST['payment_detail'], ENT_QUOTES));
        $tipe_order = trim(htmlentities($_REQUEST['tipe_order'], ENT_QUOTES));
        $diskon_order_vendor = trim(htmlentities($_REQUEST['diskon_order_vendor'], ENT_QUOTES));


        if(empty($tgl_order_vendor)){
            $return_arr = array("Status" => 'ERROR', "Message" => 'Tanggal order tidak boleh kosong');
            echo json_encode($return_arr);
            return;
        }

        // ERROR    : if paid but invalid tipe_order
        if($is_paid_vendor && $tipe_order != "REK" && $tipe_order != "TUNAI" && $tipe_order != "FREE"){
            $return_arr = array("Status" => 'ERROR', "Message" => 'Tipe transaksi tidak valid');
            echo json_encode($return_arr);
            return;
        }

        // ERROR    : if diskon is not numeric
        if(!is_numeric($diskon_order_vendor) && !empty($diskon_order_vendor)){
            $return_arr = array("Status" => 'ERROR', "Message" => 'Diskon tidak valid');
            echo json_encode($return_arr);
            return;
        }

        $order_vendor_m_data = $this->Main_model->get_order_vendor_detail($no_order)->row();



        $is_paid_vendor = ($is_paid_vendor == true ? "1" : "0");

        // cant change if product is picked up or deleted, but can change payment
        if($order_vendor_m_data->status_pick_up == '0' || $order_vendor_m_data->status_pick_up == '1' || $order_vendor_m_data->status_order_vendor == '0'){
            $updated_data = compact('is_paid_vendor', 'payment_detail', 'tipe_order');
        } else {

            // ============= Update diskon ==============

            if(empty($diskon_order_vendor)){
                $diskon_order_vendor = 0;
            }

            $current_grand_total = $order_vendor_m_data->grand_total_order;
            $current_diskon = $order_vendor_m_data->diskon_order_vendor;

            $grand_total_order = ($current_grand_total + $current_diskon) - $diskon_order_vendor;

            // ==========================================

            $updated_data = compact('catatan_order_vendor', 'tgl_order_vendor', 'is_paid_vendor', 'payment_detail', 'tipe_order','diskon_order_vendor', 'grand_total_order');
        }



        if($this->Main_model->update_order_vendor_m($updated_data, $order_vendor_m_data->id_order_vendor_m)){
            $this->db->trans_commit();
            $return_arr = array("Status" => 'OK', "Message" => '');
        } else {
            $this->db->trans_rollback();
            $return_arr = array("Status" => 'ERROR', "Message" => 'Gagal mengupdate data');
        }

        echo json_encode($return_arr);


    }

    function add_order_vendor(){

        if($this->session->userdata('is_admin') != "1" && $this->session->userdata('is_admin') != "2" ){
            redirect(base_url('main'));
        }

        if(isset($_REQUEST['id_vendor'])){
            $id_vendor = strtoupper(trim(htmlentities($_REQUEST['id_vendor'], ENT_QUOTES)));
        } else {
            $id_vendor = '';
        }

        date_default_timezone_set('Asia/Singapore');

        $catatan_order_vendor = trim(htmlentities($_REQUEST['catatan_order_vendor'], ENT_QUOTES));
        $tgl_order_vendor = strtoupper(trim(htmlentities($_REQUEST['tgl_order_vendor'], ENT_QUOTES)));
        $is_paid_vendor = filter_var($_REQUEST['is_paid_vendor'], FILTER_VALIDATE_BOOLEAN);
        $payment_detail = trim(htmlentities($_REQUEST['payment_detail'], ENT_QUOTES));
        $is_in_store = filter_var($_REQUEST['is_in_store'], FILTER_VALIDATE_BOOLEAN);
        $tipe_order = trim(htmlentities($_REQUEST['tipe_order'], ENT_QUOTES));
        $brand_order = trim(htmlentities($_REQUEST['brand_order'], ENT_QUOTES));
        $diskon_order_vendor = trim(htmlentities($_REQUEST['diskon_order_vendor'], ENT_QUOTES));

        // ERROR    : if online purchase but no customer
        // OK       : if offline purchase with no customer
        if(!$is_in_store && empty($id_vendor)){
            $return_arr = array("Status" => 'ERROR', "Message" => 'Vendor tidak boleh kosong');
            echo json_encode($return_arr);
            return;
        }

        if(empty($tgl_order_vendor)){
            $return_arr = array("Status" => 'ERROR', "Message" => 'Tanggal pesanan tidak boleh kosong');
            echo json_encode($return_arr);
            return;
        }

        // ERROR    : if not order
        if(!isset($_REQUEST['order_vendor_s'])){
            $return_arr = array("Status" => 'ERROR', "Message" => 'Tidak ada pesanan');
            echo json_encode($return_arr);
            return;
        }

        // ERROR    : if paid but invalid tipe_order
        if($is_paid_vendor && $tipe_order != "REK" && $tipe_order != "TUNAI" && $tipe_order != "FREE"){
            $return_arr = array("Status" => 'ERROR', "Message" => 'Tipe transaksi tidak valid');
            echo json_encode($return_arr);
            return;
        }

        // ERROR    : if brand is not recognized
        if(empty($brand_order) || ($brand_order != "AHF" && $brand_order != "AF" && $brand_order != "AH" && $brand_order != "KA")){
            $return_arr = array("Status" => 'ERROR', "Message" => 'Brand tidak valid');
            echo json_encode($return_arr);
            return;
        }

        // ERROR    : if diskon is not numeric
        if(!is_numeric($diskon_order_vendor) && !empty($diskon_order_vendor)){
            $return_arr = array("Status" => 'ERROR', "Message" => 'Diskon tidak valid');
            echo json_encode($return_arr);
            return;
        }

        $no_order_vendor = "V".date("Ymdhis").$this->randStr(2);
        $grand_total_order = 0;
        $status_order_vendor = '1';

        $is_paid_vendor = ($is_paid_vendor == true ? "1" : "0");
        $is_in_store = ($is_in_store == true ? "1" : "0");

        $data_m = compact('id_vendor', 'no_order_vendor', 'catatan_order_vendor', 'tgl_order_vendor', 'diskon_order_vendor',
                            'grand_total_order', 'status_order_vendor', 'is_paid_vendor', 'payment_detail', 'is_in_store', 'tipe_order', 'brand_order');

        $this->db->trans_begin();


        $id_order_vendor_m = $this->Main_model->add_order_vendor_m($data_m);

        if(!$id_order_vendor_m){
            $this->db->trans_rollback();
            $return_arr = array("Status" => 'ERROR', "Message" => 'Terjadi kesalahan sistem. Hubungi Admin (code: form_1)');
            echo json_encode($return_arr);
            return;
        }

        foreach($_REQUEST['order_vendor_s'] as $order){

            $id_product = $order['id_product'];
            $qty_order_vendor = $order['qty_order'];


            $get_price = $this->Main_model->get_product_price($id_product)->row();
            $harga_order_vendor = $get_price->HP_product;
            $total_order_vendor = floatval($qty_order_vendor) * floatval($harga_order_vendor);

            $grand_total_order += $total_order_vendor;


            $data_s = compact('id_order_vendor_m', 'id_product', 'qty_order_vendor', 'harga_order_vendor','total_order_vendor');

            $id_order_vendor_s = $this->Main_model->add_order_vendor_s($data_s);

            if(!$id_order_vendor_s){
                $this->db->trans_rollback();
                $return_arr = array("Status" => 'ERROR', "Message" => 'Terjadi kesalahan sistem. Hubungi Admin (code: form_2)');
                echo json_encode($return_arr);
                return;
            }

            // =========== Add to stok_in_out ============

                $tipe_in_out = "IN";
                $stok_in_out = $qty_order_vendor;
                $tgl_in = $tgl_order_vendor;
                $id_product = $id_product;
                $catatan_in_out = "Masuk dari Order Vendor #{$no_order_vendor}";
                $ref_order_m = $no_order_vendor;

                $tgl_out = "";
                $tgl_expired = "";


                $data_in_out = compact('tipe_in_out', 'stok_in_out', 'tgl_in', 'id_product', 'catatan_in_out', 'ref_order_m',
                                        'tgl_out', 'tgl_expired');

                $id_stok_in_out = $this->Main_model->add_stok_in_out($data_in_out);

                if(!$id_stok_in_out){
                    $this->db->trans_rollback();
                    $return_arr = array("Status" => 'ERROR', "Message" => 'Terjadi kesalahan sistem. Hubungi Admin (code: form_4)');
                    echo json_encode($return_arr);
                    return;
                }

            // ===========================================

        }

        // update diskon
        if(!empty($diskon_order_vendor)){
            $grand_total_order -= $diskon_order_vendor;
        }

        $data_m_update = compact('grand_total_order');

        if($this->Main_model->update_order_vendor_m($data_m_update, $id_order_vendor_m)){
            $this->db->trans_commit();
            $return_arr = array("Status" => 'OK', "Message" => $no_order_vendor);
        } else {
            $return_arr = array("Status" => 'ERROR', "Message" => 'Terjadi kesalahan sistem. Hubungi Admin (code: form_3)');
        }


        echo json_encode($return_arr);



    }

    function order_vendor_form()
    {
        if($this->session->userdata('is_admin') != "1" && $this->session->userdata('is_admin') != "2" ){
            redirect(base_url('main'));
        }

        $this->load->view('template/admin_header');
        $this->load->view('order_vendor_form');
        $this->load->view('template/admin_footer');
    }

    function update_delivery(){

        if($this->session->userdata('is_admin') != "1" && $this->session->userdata('is_admin') != "3"){
            redirect(base_url('main'));
        }

        $alamat_delivery = strtoupper(trim(htmlentities($_REQUEST['alamat_delivery'], ENT_QUOTES)));
        $no_hp_delivery = strtoupper(trim(htmlentities($_REQUEST['no_hp_delivery'], ENT_QUOTES)));
        $tgl_delivery = strtoupper(trim(htmlentities($_REQUEST['tgl_delivery'], ENT_QUOTES)));
        $catatan_delivery = trim(htmlentities($_REQUEST['catatan_delivery'], ENT_QUOTES));
        $id_staff = strtoupper(trim(htmlentities($_REQUEST['id_staff'], ENT_QUOTES)));
        $id_delivery = strtoupper(trim(htmlentities($_REQUEST['id_delivery'], ENT_QUOTES)));

        if(empty($tgl_delivery)){
            $return_arr = array("Status" => 'ERROR', "Message" => 'Tanggal delivery tidak boleh kosong');
            echo json_encode($return_arr);
            return;
        }

        //Check if editable
        $delivery_data = $this->Main_model->get_delivery_by_id($id_delivery);

        if($delivery_data->num_rows() == 0){
            $return_arr = array("Status" => 'ERROR', "Message" => 'Data tidak ditemukan');
            echo json_encode($return_arr);
            return;
        }

        if($delivery_data->row()->status_delivery != '0'){
            $return_arr = array("Status" => 'ERROR', "Message" => 'Data tidak bisa diupdate karena sedang dikirim atau sudah dikirm');
            echo json_encode($return_arr);
            return;
        }

        $updated_data = compact('alamat_delivery', 'no_hp_delivery', 'tgl_delivery', 'catatan_delivery', 'id_staff');

        $this->db->trans_begin();

        if($this->Main_model->update_delivery($updated_data, $id_delivery)){
            $this->db->trans_commit();
            $return_arr = array("Status" => 'OK', "Message" => 'Berhasil diupdate');
        } else {
            $this->db->trans_rollback();
            $return_arr = array("Status" => 'ERROR', "Message" => 'Gagal mengupdate data');
        }

        echo json_encode($return_arr);

    }

    function delivery_detail(){

        if($this->session->userdata('is_admin') != "1" && $this->session->userdata('is_admin') != "3"){
            redirect(base_url('main'));
        }

        $this->load->view('template/admin_header');

        if(isset($_GET['id'])){

            $data_delivery = $this->Main_model->get_delivery_by_id(htmlentities($_GET['id'], ENT_QUOTES));

            if($data_delivery->num_rows() == 0){

            } else {
                $data['delivery'] = $data_delivery->result_object();
                $data['orders'] = $this->Main_model->get_order_s($data_delivery->row()->id_order_m)->result_object();
                $this->load->view('delivery_detail', $data);
            }


        } else {
            // kosong
        }

        $this->load->view('template/admin_footer');
    }

    function delivery_list(){

        $this->load->view('template/admin_header');
        $this->load->view('delivery_list');
        $this->load->view('template/admin_footer');
    }

    function get_delivery(){

        // server-side pagination
        $draw = $_REQUEST['draw'];
        $length = $_REQUEST['length'];
        $start = $_REQUEST['start'];
        $search = trim(htmlentities($_REQUEST['search']["value"], ENT_QUOTES));

        $total = $this->Main_model->get_delivery()->num_rows();

        $output = array();
        $output['draw'] = $draw;
        $output['recordsTotal'] = $output['recordsFiltered'] = $total;
        $output['data']=array();

        if(isset($_GET['status'])){
            $status = htmlentities($_GET['status'], ENT_QUOTES);
        } else {
            $status = 'all';
        }

        if($this->session->userdata('is_admin') != "1" && $this->session->userdata('is_admin') != "3"){
            $output['data'] = $this->Main_model->get_delivery($search, false, $this->session->userdata('id_staff'), $length, $start, $status)->result_object();
        } else {
            $output['data'] = $this->Main_model->get_delivery($search, true, 0, $length, $start, $status)->result_object();
        }


        echo json_encode($output);
    }

    function update_delivery_status(){
        if($this->session->userdata('is_admin') != "1" && $this->session->userdata('is_admin') != "3"){
            redirect(base_url('main'));
        }


        date_default_timezone_set('Asia/Singapore');

        $id_delivery = trim(htmlentities($_REQUEST['id_delivery'], ENT_QUOTES));
        $status_delivery = trim(htmlentities($_REQUEST['status'], ENT_QUOTES));

        $delivery_data = $this->Main_model->get_delivery_by_id($id_delivery);

        if($delivery_data->num_rows() > 0){

            // If OTW is chosen (from NEW to OTW)
            if($status_delivery == '1'){
                if($delivery_data->row()->status_delivery != '0'){
                    $return_arr = array("Status" => 'ERROR', "Message" => 'Tidak dapat mengubah data karena delivery sedang diantar, sudah sampai atau dibatalkan');
                    echo json_encode($return_arr);
                    return;
                }
                else {
                    $timestamp_otw = date('Y-m-d H:i:s');
                    $updated_data = compact('status_delivery', 'timestamp_otw');

                    if($this->Main_model->update_delivery($updated_data, $id_delivery)){
                        $return_arr = array("Status" => 'OK', "Message" => 'Berhasil! Status delivery: OTW');
                    } else {
                        $return_arr = array("Status" => 'ERROR', "Message" => 'Gagal mengupdate data. Hubungi admin (code: updateError1)');
                    }
                }
            }
            // If SELESAI is chosen (from OTW to SELESAI)
            else if ($status_delivery == '2') {
                if($delivery_data->row()->status_delivery != '1'){
                    $return_arr = array("Status" => 'ERROR', "Message" => 'Tidak dapat mengubah data karena delivery belum diantar, sudah sampai atau dibatalkan');
                    echo json_encode($return_arr);
                    return;
                } else {
                    $timestamp_delivery = date('Y-m-d H:i:s');
                    $updated_data = compact('status_delivery', 'timestamp_delivery');

                    if($this->Main_model->update_delivery($updated_data, $id_delivery)){
                        $return_arr = array("Status" => 'OK', "Message" => 'Berhasil! Status delivery: Selesai');
                    } else {
                        $return_arr = array("Status" => 'ERROR', "Message" => 'Gagal mengupdate data. Hubungi admin (code: updateError2)');
                    }
                }
            }
            // If BATAL is chosen (from OTW to NEW)
            else if ($status_delivery == '0') {
                if($delivery_data->row()->status_delivery != '1'){
                    $return_arr = array("Status" => 'ERROR', "Message" => 'Tidak dapat mengubah data karena delivery sudah sampai atau dibatalkan');
                    echo json_encode($return_arr);
                    return;
                }
                // If from OTW to NEW
                else{
                    $timestamp_otw = '';
                    $updated_data = compact('status_delivery', 'timestamp_otw');

                    if($this->Main_model->update_delivery($updated_data, $id_delivery)){
                        $return_arr = array("Status" => 'OK', "Message" => 'Delivery berhasil dibatalkan');
                    } else {
                        $return_arr = array("Status" => 'ERROR', "Message" => 'Gagal mengupdate data. Hubungi admin (code: updateError3)');
                    }
                }

            }
        } else {
            $return_arr = array("Status" => 'ERROR', "Message" => 'Data delivery tidak ditemukan');
        }

        echo json_encode($return_arr);


    }

    function delivery_form()
    {
        if($this->session->userdata('is_admin') != "1" && $this->session->userdata('is_admin') != "3"){
            redirect(base_url('main'));
        }

        $this->load->view('template/admin_header');
        $this->load->view('delivery_form');
        $this->load->view('template/admin_footer');
    }

    function order_list(){

        if($this->session->userdata('is_admin') != "1" && $this->session->userdata('is_admin') != "2" ){
            redirect(base_url('main'));
        }

        $this->load->view('template/admin_header');
        $this->load->view('order_list');
        $this->load->view('template/admin_footer');
    }

    function add_delivery(){

        if($this->session->userdata('is_admin') != "1" && $this->session->userdata('is_admin') != "3"){
            redirect(base_url('main'));
        }

        if(!isset($_REQUEST['id_staff'])){
            $return_arr = array("Status" => 'ERROR', "Message" => 'Staff tidak boleh kosong');
            echo json_encode($return_arr);
            return;
        }

        $id_staff = strtoupper(trim(htmlentities($_REQUEST['id_staff'], ENT_QUOTES)));

        //check if staff exists
        if ($this->Main_model->get_staff_by_id($id_staff)->num_rows() == 0){
            $return_arr = array("Status" => 'ERROR', "Message" => 'Staff tidak ditemukan');
            echo json_encode($return_arr);
            return;
        }

        if(!isset($_REQUEST['id_order_m'])){
            $return_arr = array("Status" => 'ERROR', "Message" => 'Order tidak boleh kosong');
            echo json_encode($return_arr);
            return;
        }

        $id_order_m = strtoupper(trim(htmlentities($_REQUEST['id_order_m'], ENT_QUOTES)));
        $id_customer = strtoupper(trim(htmlentities($_REQUEST['id_customer'], ENT_QUOTES)));

        $data_order_m = $this->Main_model->get_order_m_by_id($id_order_m);

        //check if order exists
        if($data_order_m->num_rows() == 0){
            $return_arr = array("Status" => 'ERROR', "Message" => 'Order tidak ditemukan');
            echo json_encode($return_arr);
            return;
        }

        // check if customer and order matches
        if($id_customer != $data_order_m->row()->id_customer){
            $return_arr = array("Status" => 'ERROR', "Message" => 'ERROR: Data mismatch (1)');
            echo json_encode($return_arr);
            return;
        }

        //check if the chosen order udah ada data delivery
        if($this->Main_model->get_delivery_by_id_order_m($id_order_m)->num_rows() > 0){
            $return_arr = array("Status" => 'ERROR', "Message" => 'Sudah ada delivery untuk pesanan ini');
            echo json_encode($return_arr);
            return;
        }

        //check if the chosen order statusnya tidak 0-dibatalkan
        if($data_order_m->row()->status_order != '1'){
            $return_arr = array("Status" => 'ERROR', "Message" => 'Order sudah dibatalkan');
            echo json_encode($return_arr);
            return;
        }

        date_default_timezone_set('Asia/Singapore');

        $alamat_delivery = trim(htmlentities($_REQUEST['alamat_delivery'], ENT_QUOTES));
        $no_hp_delivery = strtoupper(trim(htmlentities($_REQUEST['no_hp_delivery'], ENT_QUOTES)));
        $tgl_delivery = strtoupper(trim(htmlentities($_REQUEST['tgl_delivery'], ENT_QUOTES)));
        $catatan_delivery = trim(htmlentities($_REQUEST['catatan_delivery'], ENT_QUOTES));

        $status_delivery = '0';

        if(empty($tgl_delivery)){
            $return_arr = array("Status" => 'ERROR', "Message" => 'Tanggal delivery tidak boleh kosong');
            echo json_encode($return_arr);
            return;
        }

        $data = compact('id_customer', 'alamat_delivery', 'no_hp_delivery', 'id_order_m',
                        'tgl_delivery', 'catatan_delivery', 'status_delivery', 'id_staff');

        $this->db->trans_begin();

        if($this->Main_model->add_delivery($data)){
            $this->db->trans_commit();
            $return_arr = array("Status" => 'OK', "Message" => 'Berhasil ditambahkan');
        } else {
            $this->db->trans_rollback();
            $return_arr = array("Status" => 'ERROR', "Message" => 'Gagal menambahkan delivery');
        }

        echo json_encode($return_arr);

    }

    function get_order_s(){
        $id_order_m = htmlentities($_REQUEST['id_order_m'], ENT_QUOTES);
        $data = $this->Main_model->get_order_s($id_order_m);
        echo json_encode($data->result_object());
        return;

    }

    function get_order_m(){

        if($this->session->userdata('is_admin') == "0" ){
            redirect(base_url('main'));
        }

        // server-side pagination
        $draw = $_REQUEST['draw'];
        $length = $_REQUEST['length'];
        $start = $_REQUEST['start'];
        $search = trim(htmlentities($_REQUEST['search']["value"], ENT_QUOTES));

        $output = array();
        $output['draw'] = $draw;
        $output['data']=array();

        if(isset($_GET['status'])){
            $status = htmlentities($_GET['status'], ENT_QUOTES);
        } else {
            $status = 'all';
        }

        if(isset($_GET['brand'])){
            $brand = htmlentities($_GET['brand'], ENT_QUOTES);
        } else {
            $brand = "all";
        }

        if(!isset($_GET['delivery'])){
            $total = $this->Main_model->get_order_m($search, 10000000000, 0, $status, $brand)->num_rows();
            $output['data'] = $this->Main_model->get_order_m($search, $length, $start, $status, $brand)->result_object();
        } else {
            $total = $this->Main_model->get_order_m_deliv($search, $length, $start, $status, $brand)->num_rows();
            $output['data'] = $this->Main_model->get_order_m_deliv($search, $length, $start, $status, $brand)->result_object();
        }

        $output['recordsTotal'] = $output['recordsFiltered'] = $total;

        echo json_encode($output);
    }

    function update_order_m(){

        if($this->session->userdata('is_admin') != "1" && $this->session->userdata('is_admin') != "2" ){
            redirect(base_url('main'));
        }

        $this->db->trans_begin();

        $no_order = strtoupper(trim(htmlentities($_REQUEST['no_order'], ENT_QUOTES)));
        $catatan_order = trim(htmlentities($_REQUEST['catatan_order'], ENT_QUOTES));
        $tgl_order = strtoupper(trim(htmlentities($_REQUEST['tgl_order'], ENT_QUOTES)));
        $is_tentative = filter_var($_REQUEST['is_tentative'], FILTER_VALIDATE_BOOLEAN);
        $ongkir_order = strtoupper(trim(htmlentities($_REQUEST['ongkir_order'], ENT_QUOTES)));
        $is_ongkir_kas = filter_var($_REQUEST['is_ongkir_kas'], FILTER_VALIDATE_BOOLEAN);
        $diskon_order = strtoupper(trim(htmlentities($_REQUEST['diskon_order'], ENT_QUOTES)));
        $is_paid = filter_var($_REQUEST['is_paid'], FILTER_VALIDATE_BOOLEAN);
        $payment_detail = trim(htmlentities($_REQUEST['payment_detail'], ENT_QUOTES));
        $tipe_order = trim(htmlentities($_REQUEST['tipe_order'], ENT_QUOTES));

        // ERROR    : if not tentative but no date
        // OK       : if tentative but no date
        if(!$is_tentative && empty($tgl_order)){
            $return_arr = array("Status" => 'ERROR', "Message" => 'Tanggal order tidak boleh kosong');
            echo json_encode($return_arr);
            return;
        }

        // ERROR    : if paid but invalid tipe_order
        if($is_paid && $tipe_order != "REK" && $tipe_order != "TUNAI" && $tipe_order != "FREE"){
            $return_arr = array("Status" => 'ERROR', "Message" => 'Tipe transaksi tidak valid');
            echo json_encode($return_arr);
            return;
        }

        $order_m_data = $this->Main_model->get_order_m_by_no_order($no_order)->row();


        // cant change if product is on delivery or delivered or deleted, except payment
        if($order_m_data->status_order == '0'){
            $return_arr = array("Status" => 'ERROR', "Message" => 'Order tidak bisa diupdate karena sudah dibatalkan');
            echo json_encode($return_arr);
            return;
        }


        // process update

        $is_ongkir_kas = ($is_ongkir_kas == true ? "1" : "0");
        $is_paid = ($is_paid == true ? "1" : "0");
        $is_tentative = ($is_tentative == true ? "1" : "0");

        // update price
        if(filter_var($is_ongkir_kas, FILTER_VALIDATE_BOOLEAN)){
            $grand_total_order = floatval($order_m_data->subtotal_order)  - floatval($diskon_order);
        } else {
            $grand_total_order = floatval($order_m_data->subtotal_order) + floatval($ongkir_order) - floatval($diskon_order);
        }


        if($order_m_data->status_delivery != null && ($order_m_data->status_delivery == '1' || $order_m_data->status_delivery == '2')){
            $data = compact('is_paid', 'payment_detail', 'tipe_order');
        } else {
            $data = compact('catatan_order', 'tgl_order', 'is_tentative', 'ongkir_order', 'is_ongkir_kas',
                'diskon_order', 'is_paid', 'payment_detail', 'grand_total_order', 'tipe_order');
        }


        if($this->Main_model->update_order_m($data, $order_m_data->id_order_m)){
            $this->db->trans_commit();
            $return_arr = array("Status" => 'OK', "Message" => 'Data berhasil diupdate');
        } else {
            $this->db->trans_rollback();
            $return_arr = array("Status" => 'ERROR', "Message" => 'Gagal mengupdate data');
        }

        echo json_encode($return_arr);

    }

    function order_detail(){

        if($this->session->userdata('is_admin') != "1" && $this->session->userdata('is_admin') != "2" ){
            redirect(base_url('main'));
        }

        $this->load->view('template/admin_header');

        if(isset($_GET['no'])){

            $data_order = $this->Main_model->get_order_detail(htmlentities($_GET['no'], ENT_QUOTES));

            if($data_order->num_rows() == 0){
                // kosong
            } else {
                $data['orders'] = $data_order->result_object();
                $this->load->view('order_detail', $data);
            }

        } else {
            // kosong
        }

        $this->load->view('template/admin_footer');
    }

    function order_form()
    {
        if($this->session->userdata('is_admin') != "1" && $this->session->userdata('is_admin') != "2" ){
            redirect(base_url('main'));
        }

        $this->load->view('template/admin_header');
        $this->load->view('order_form');
        $this->load->view('template/admin_footer');
    }

    function add_order(){

        if($this->session->userdata('is_admin') != "1" && $this->session->userdata('is_admin') != "2" ){
            redirect(base_url('main'));
        }

        date_default_timezone_set('Asia/Singapore');

        if(isset($_REQUEST['id_customer'])){
            $id_customer = strtoupper(trim(htmlentities($_REQUEST['id_customer'], ENT_QUOTES)));
        } else {
            $id_customer = '';
        }

        $catatan_order = trim(htmlentities($_REQUEST['catatan_order'], ENT_QUOTES));
        $tgl_order = strtoupper(trim(htmlentities($_REQUEST['tgl_order'], ENT_QUOTES)));
        $ongkir_order = strtoupper(trim(htmlentities($_REQUEST['ongkir_order'], ENT_QUOTES)));
        $is_ongkir_kas = filter_var($_REQUEST['is_ongkir_kas'], FILTER_VALIDATE_BOOLEAN);
        $diskon_order = strtoupper(trim(htmlentities($_REQUEST['diskon_order'], ENT_QUOTES)));
        $is_paid = filter_var($_REQUEST['is_paid'], FILTER_VALIDATE_BOOLEAN);
        $payment_detail = trim(htmlentities($_REQUEST['payment_detail'], ENT_QUOTES));
        $tipe_order = trim(htmlentities($_REQUEST['tipe_order'], ENT_QUOTES));
        $brand_order = trim(htmlentities($_REQUEST['brand_order'], ENT_QUOTES));
        $is_in_store = filter_var($_REQUEST['is_in_store'], FILTER_VALIDATE_BOOLEAN);
        $is_tentative = filter_var($_REQUEST['is_tentative'], FILTER_VALIDATE_BOOLEAN);
        $is_changeable = '1';


        // ERROR    : if online purchase but no customer
        // OK       : if offline purchase with no customer
        if(!$is_in_store && empty($id_customer)){
            $return_arr = array("Status" => 'ERROR', "Message" => 'Customer tidak boleh kosong');
            echo json_encode($return_arr);
            return;
        }

        // ERROR    : if not tentative but no date
        // OK       : if tentative but no date
        if(!$is_tentative && empty($tgl_order)){
            $return_arr = array("Status" => 'ERROR', "Message" => 'Tanggal pesanan tidak boleh kosong');
            echo json_encode($return_arr);
            return;
        }

        // ERROR    : if not order
        if(!isset($_REQUEST['order_s'])){
            $return_arr = array("Status" => 'ERROR', "Message" => 'Tidak ada pesanan');
            echo json_encode($return_arr);
            return;
        }


        // ERROR    : if paid but invalid tipe_order
        if($is_paid && $tipe_order != "REK" && $tipe_order != "TUNAI" && $tipe_order != "FREE"){
            $return_arr = array("Status" => 'ERROR', "Message" => 'Tipe transaksi tidak valid');
            echo json_encode($return_arr);
            return;
        }

        // ERROR    : if brand is not recognized
        if(empty($brand_order) || ($brand_order != "AHF" && $brand_order != "AF" && $brand_order != "AH" && $brand_order != "KA")){
            $return_arr = array("Status" => 'ERROR', "Message" => 'Brand tidak valid');
            echo json_encode($return_arr);
            return;
        }

        $no_order = "C".date("Ymdhis").$this->randStr(2);
        $subtotal_order = 0;
        $grand_total_order = 0;
        $status_order = '1';


        $is_ongkir_kas = ($is_ongkir_kas == true ? "1" : "0");
        $is_paid = ($is_paid == true ? "1" : "0");
        $is_in_store = ($is_in_store == true ? "1" : "0");
        $is_tentative = ($is_tentative == true ? "1" : "0");

        $data_m = compact('id_customer', 'no_order', 'catatan_order', 'tgl_order', 'subtotal_order',
                            'ongkir_order', 'is_ongkir_kas', 'diskon_order', 'grand_total_order', 'status_order',
                            'is_paid', 'payment_detail', 'is_in_store', 'is_tentative', 'is_changeable', 'tipe_order', 'brand_order');

        $this->db->trans_begin();

        $id_order_m = $this->Main_model->add_order_m($data_m);

        if(!$id_order_m){
            $this->db->trans_rollback();
            $return_arr = array("Status" => 'ERROR', "Message" => 'Terjadi kesalahan sistem. Hubungi Admin (code: form_1)');
            echo json_encode($return_arr);
            return;
        }


        foreach($_REQUEST['order_s'] as $order){
            $is_free = filter_var($order['is_free'], FILTER_VALIDATE_BOOLEAN);

            $id_product = $order['id_product'];
            $qty_order = $order['qty_order'];
            $tipe_harga = $order['tipe_harga'];


            if(!$is_free){
                $get_price = $this->Main_model->get_product_price($id_product)->row();

                if($tipe_harga == "HJ"){
                    $harga_order = $get_price->HJ_product;
                } else if ($tipe_harga == "HR") {
                    $harga_order = $get_price->HR_product;
                } else if ($tipe_harga == "HP") {
                    $harga_order = $get_price->HP_product;
                }

                $total_order = floatval($qty_order) * floatval($harga_order);
            } else {
                $harga_order = 0;
                $total_order = 0;
            }

            $subtotal_order += $total_order;

            $is_free = ($is_free == true ? "1" : "0");

            $data_s = compact('id_order_m', 'id_product', 'qty_order', 'harga_order',
                                'tipe_harga', 'total_order', 'is_free');

            $id_order_s = $this->Main_model->add_order_s($data_s);

            if(!$id_order_s){
                $this->db->trans_rollback();
                $return_arr = array("Status" => 'ERROR', "Message" => 'Terjadi kesalahan sistem. Hubungi Admin (code: form_2)');
                echo json_encode($return_arr);
                return;
            }

            // =========== Add to stok_in_out ============

            $tipe_in_out = "OUT";
            $stok_in_out = $qty_order;
            $tgl_out = $tgl_order;
            $id_product = $id_product;
            $catatan_in_out = "Order #{$no_order}";
            $ref_order_m = $no_order;

            $tgl_in = "";
            $tgl_expired = "";


            $data_in_out = compact('tipe_in_out', 'stok_in_out', 'tgl_in', 'id_product', 'catatan_in_out', 'ref_order_m',
                'tgl_out', 'tgl_expired');

            $id_stok_in_out = $this->Main_model->add_stok_in_out($data_in_out);

            if(!$id_stok_in_out){
                $this->db->trans_rollback();
                $return_arr = array("Status" => 'ERROR', "Message" => 'Terjadi kesalahan sistem. Hubungi Admin (code: form_4)');
                echo json_encode($return_arr);
                return;
            }

            // ===========================================

        }


        // update price
        if(filter_var($is_ongkir_kas, FILTER_VALIDATE_BOOLEAN)){
            $grand_total_order = floatval($subtotal_order)  - floatval($diskon_order);
        } else {
            $grand_total_order = floatval($subtotal_order) + floatval($ongkir_order) - floatval($diskon_order);
        }

        $data_m_update = compact('subtotal_order', 'grand_total_order');

        if($this->Main_model->update_order_m($data_m_update, $id_order_m)){
            $this->db->trans_commit();
            $return_arr = array("Status" => 'OK', "Message" => $no_order);
        } else {
            $return_arr = array("Status" => 'ERROR', "Message" => 'Terjadi kesalahan sistem. Hubungi Admin (code: form_3)');
        }


        echo json_encode($return_arr);

    }

    function stok_in_out(){

        if($this->session->userdata('is_admin') != "1" && $this->session->userdata('is_admin') != "2" ){
            redirect(base_url('main'));
        }

        $this->load->view('template/admin_header');


        if(isset($_GET['product'])){
            $product = $this->Main_model->get_product_by_id(htmlentities($_GET['product'], ENT_QUOTES));

            if($product->num_rows() == 0){
                // product not found error
            } else {
                $data['product'] = $product->result_array();
                $this->load->view('stok_in_out', $data);
            }
        }
        else {

        }


        $this->load->view('template/admin_footer');
    }

    function get_stok_in_out(){
        if($this->session->userdata('is_admin') != "1" && $this->session->userdata('is_admin') != "2" ){
            redirect(base_url('main'));
        }

        $id_product = strtoupper(trim(htmlentities($_REQUEST['id_product'], ENT_QUOTES)));

        // server-side pagination
        $draw = $_REQUEST['draw'];
        $length = $_REQUEST['length'];
        $start = $_REQUEST['start'];
        $search = trim(htmlentities($_REQUEST['search']["value"], ENT_QUOTES));

        $total = $this->Main_model->get_product_stok_in_out($id_product)->num_rows();

        $output = array();
        $output['draw'] = $draw;
        $output['recordsTotal'] = $output['recordsFiltered'] = $total;
        $output['data']=array();

        $output['data'] = $this->Main_model->get_product_stok_in_out($id_product, $length, $start)->result_object();

        echo json_encode($output);


    }

    function get_stok_in_out_by_id(){
        if($this->session->userdata('is_admin') != "1" && $this->session->userdata('is_admin') != "2" ){
            redirect(base_url('main'));
        }

        $id = htmlentities($_REQUEST['id_stok_in_out'], ENT_QUOTES);
        $data = $this->Main_model->get_stok_in_out_by_id($id);
        echo json_encode($data->row());
        return;
    }

    function add_stok_in_out(){

        if($this->session->userdata('is_admin') != "1" && $this->session->userdata('is_admin') != "2" ){
            redirect(base_url('main'));
        }

        $id_stok_in_out = strtoupper(trim(htmlentities($_REQUEST['id_stok_in_out'], ENT_QUOTES)));
        $tipe_in_out = strtoupper(trim(htmlentities($_REQUEST['tipe_in_out'], ENT_QUOTES)));
        $stok_in_out = strtoupper(trim(htmlentities($_REQUEST['stok_in_out'], ENT_QUOTES)));
        $tgl_in = strtoupper(trim(htmlentities($_REQUEST['tgl_in'], ENT_QUOTES)));
        $tgl_out = strtoupper(trim(htmlentities($_REQUEST['tgl_out'], ENT_QUOTES)));
        $tgl_expired = strtoupper(trim(htmlentities($_REQUEST['tgl_expired'], ENT_QUOTES)));
        $catatan_in_out = trim(htmlentities($_REQUEST['catatan_in_out'], ENT_QUOTES));
        $id_product = strtoupper(trim(htmlentities($_REQUEST['id_product'], ENT_QUOTES)));

        $ref_order_m = 0;

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

        $in_out_data = $this->Main_model->get_stok_in_out_by_id($id_stok_in_out);

        if($in_out_data->num_rows() == 0){

            $data = compact('id_product','tipe_in_out', 'stok_in_out',
                'tgl_in', 'tgl_out', 'tgl_expired', 'catatan_in_out', 'ref_order_m');

            if($this->Main_model->add_stok_in_out($data)){
                $this->db->trans_commit();
                $return_arr = array("Status" => 'OK', "Message" => '');
            } else {
                $this->db->trans_rollback();
                $return_arr = array("Status" => 'ERROR', "Message" => 'Gagal menambahkan data');
            }
        } else {

            $ref_order_m = $in_out_data->row()->ref_order_m;

            $data = compact('id_product','tipe_in_out', 'stok_in_out',
                'tgl_in', 'tgl_out', 'tgl_expired', 'catatan_in_out', 'ref_order_m');

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

    function bahan_dasar(){
        if($this->session->userdata('is_admin') != "1" && $this->session->userdata('is_admin') != "2" && $this->session->userdata('is_admin') != "5"){
            redirect(base_url('main'));
        }

        $this->load->view('template/admin_header');
        $this->load->view('bahan_dasar');
        $this->load->view('template/admin_footer');
    }

    function product(){

        if($this->session->userdata('is_admin') != "1" && $this->session->userdata('is_admin') != "2" && $this->session->userdata('is_admin') != "4"){
            redirect(base_url('main'));
        }

        $this->load->view('template/admin_header');
        $this->load->view('product');
        $this->load->view('template/admin_footer');
    }

    function get_product(){

        if($this->session->userdata('is_admin') != "1" && $this->session->userdata('is_admin') != "2" && $this->session->userdata('is_admin') != "4"){
            redirect(base_url('main'));
        }

        // server-side pagination
        $draw = $_REQUEST['draw'];
        $length = $_REQUEST['length'];
        $start = $_REQUEST['start'];
        $search = trim(htmlentities($_REQUEST['search']["value"], ENT_QUOTES));

        $total = $this->Main_model->get_product()->num_rows();

        $output = array();
        $output['draw'] = $draw;
        $output['recordsTotal'] = $output['recordsFiltered'] = $total;
        $output['data']=array();

        if(isset($_GET['brand'])){
            $brand = htmlentities($_GET['brand'], ENT_QUOTES);
        } else {
            $brand = "all";
        }

        if(isset($_GET['stock_status'])){
            $stock_status = htmlentities($_GET['stock_status'], ENT_QUOTES);
        } else {
            $stock_status = "all";
        }

        $output['data'] = $this->Main_model->get_product($search, $length, $start, $brand, $stock_status)->result_object();

        echo json_encode($output);
    }

    function vendor(){

        if($this->session->userdata('is_admin') != "1" && $this->session->userdata('is_admin') != "2" ){
            redirect(base_url('main'));
        }

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

        if($this->session->userdata('is_admin') != "1" && $this->session->userdata('is_admin') != "2" && $this->session->userdata('is_admin') != "4"){
            redirect(base_url('main'));
        }

        $is_bahan = false;

        if(isset($_GET['bahan'])){
            if($_GET['bahan'] == "true"){
                $is_bahan = true;
            }
        }

        $id_product = strtoupper(trim(htmlentities($_REQUEST['id_product'], ENT_QUOTES)));
        $nama_product = trim(htmlentities($_REQUEST['nama_product'], ENT_QUOTES));
        $satuan_product = trim(htmlentities($_REQUEST['satuan_product'], ENT_QUOTES));
        $HP_product = strtoupper(trim(htmlentities($_REQUEST['HP_product'], ENT_QUOTES)));

        if($is_bahan){

            $SKU_product = "";
            $HJ_product = 0;
            $HR_product = 0;
            $brand_product = 'BAHAN';

        } else {
            $SKU_product = strtoupper(trim(htmlentities($_REQUEST['SKU_product'], ENT_QUOTES)));
            $HJ_product = strtoupper(trim(htmlentities($_REQUEST['HJ_product'], ENT_QUOTES)));
            $HR_product = strtoupper(trim(htmlentities($_REQUEST['HR_product'], ENT_QUOTES)));
            $brand_product = strtoupper(trim(htmlentities($_REQUEST['brand_product'], ENT_QUOTES)));
        }

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

        if(!$is_bahan){
            if(empty($HJ_product) || !is_numeric($HJ_product)){
                array_push($error, "invalid-HJ");
            }

            if(empty($HR_product) || !is_numeric($HR_product)){
                array_push($error, "invalid-HR");
            }

            if(empty($brand_product) || ($brand_product != "AHF" && $brand_product != "AF" && $brand_product != "AH" && $brand_product != "KA" && $brand_product != "BAHAN")){
                array_push($error, "invalid-brand");
            }
        }



        if(!empty($error)){
            $return_arr = array("Status" => 'FORMERROR', "Error" => $error);
            $this->db->trans_rollback();
            echo json_encode($return_arr);
            return;
        }

        $data = compact('nama_product', 'SKU_product', 'satuan_product',
                        'HJ_product', 'HR_product', 'HP_product', 'brand_product');


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

        if($this->session->userdata('is_admin') == "0" ){
            redirect(base_url('main'));
        }

        // server-side pagination
        $draw = $_REQUEST['draw'];
        $length = $_REQUEST['length'];
        $start = $_REQUEST['start'];
        $search = trim(htmlentities($_REQUEST['search']["value"], ENT_QUOTES));

        $total = $this->Main_model->get_vendor()->num_rows();


        $output = array();
        $output['draw'] = $draw;
        $output['recordsTotal'] = $output['recordsFiltered'] = $total;
        $output['data']=array();


        $output['data'] = $this->Main_model->get_vendor($search, $length, $start)->result_object();

        echo json_encode($output);
    }

    function get_vendor_by_id(){
        $id = htmlentities($_REQUEST['id_vendor'], ENT_QUOTES);
        $data = $this->Main_model->get_vendor_by_id($id);
        echo json_encode($data->row());
        return;
    }

    function add_vendor(){

        if($this->session->userdata('is_admin') != "1" && $this->session->userdata('is_admin') != "2" ){
            redirect(base_url('main'));
        }

        $id_vendor = strtoupper(trim(htmlentities($_REQUEST['id_vendor'], ENT_QUOTES)));
        $nama_vendor = trim(htmlentities($_REQUEST['nama_vendor'], ENT_QUOTES));
        $alamat_vendor = trim(htmlentities($_REQUEST['alamat_vendor'], ENT_QUOTES));
        $no_hp_vendor = strtoupper(trim(htmlentities($_REQUEST['no_hp_vendor'], ENT_QUOTES)));
        $email_vendor = strtoupper(trim(htmlentities($_REQUEST['email_vendor'], ENT_QUOTES)));
        $catatan_vendor = trim(htmlentities($_REQUEST['catatan_vendor'], ENT_QUOTES));
        $no_rekening_vendor = trim(htmlentities($_REQUEST['no_rekening_vendor'], ENT_QUOTES));
        $nama_bank_vendor = trim(htmlentities($_REQUEST['nama_bank_vendor'], ENT_QUOTES));

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

        $data = compact('nama_vendor', 'alamat_vendor', 'no_rekening_vendor', 'nama_bank_vendor',
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

        if($this->session->userdata('is_admin') != "1" && $this->session->userdata('is_admin') != "2" ){
            redirect(base_url('main'));
        }

        $this->load->view('template/admin_header');
        $this->load->view('customer');
        $this->load->view('template/admin_footer');
    }

    function get_customer(){

        if($this->session->userdata('is_admin') == "0" ){
            redirect(base_url('main'));
        }

        // server-side pagination
        $draw = $_REQUEST['draw'];
        $length = $_REQUEST['length'];
        $start = $_REQUEST['start'];
        $search = trim(htmlentities($_REQUEST['search']["value"], ENT_QUOTES));

        $total = $this->Main_model->get_customer()->num_rows();

        $output = array();
        $output['draw'] = $draw;
        $output['recordsTotal'] = $output['recordsFiltered'] = $total;
        $output['data']=array();


        $output['data'] = $this->Main_model->get_customer($search, $length, $start)->result_object();

        echo json_encode($output);

    }

    function get_customer_by_id(){
        $id = htmlentities($_REQUEST['id_customer'], ENT_QUOTES);
        $data = $this->Main_model->get_customer_by_id($id);
        echo json_encode($data->row());
        return;
    }

    function add_customer(){

        if($this->session->userdata('is_admin') != "1" && $this->session->userdata('is_admin') != "2" ){
            redirect(base_url('main'));
        }

        $id_customer = strtoupper(trim(htmlentities($_REQUEST['id_customer'], ENT_QUOTES)));
        $nama_customer = trim(htmlentities($_REQUEST['nama_customer'], ENT_QUOTES));
        $alamat_customer = trim(htmlentities($_REQUEST['alamat_customer'], ENT_QUOTES));
        $no_hp_customer = strtoupper(trim(htmlentities($_REQUEST['no_hp_customer'], ENT_QUOTES)));
        $email_customer = strtoupper(trim(htmlentities($_REQUEST['email_customer'], ENT_QUOTES)));
        $catatan_customer = trim(htmlentities($_REQUEST['catatan_customer'], ENT_QUOTES));

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

        if($this->session->userdata('is_admin') != "1" ){
            redirect(base_url('main'));
        }

        $data['posisi_list'] = $this->Main_model->get_posisi()->result_object();

        $this->load->view('template/admin_header');
        $this->load->view('staff', $data);
        $this->load->view('template/admin_footer');
    }

    function get_staff(){

        if($this->session->userdata('is_admin') == "0" ){
            redirect(base_url('main'));
        }


        // server-side pagination
        $draw = $_REQUEST['draw'];
        $length = $_REQUEST['length'];
        $start = $_REQUEST['start'];
        $search = trim(htmlentities($_REQUEST['search']["value"], ENT_QUOTES));

        $total = $this->Main_model->get_staff()->num_rows();

        $output = array();
        $output['draw'] = $draw;
        $output['recordsTotal'] = $output['recordsFiltered'] = $total;
        $output['data']=array();


        $this->db->limit($length,$start);

        $output['data'] = $this->Main_model->get_staff($search)->result_object();

        echo json_encode($output);
    }

    function get_staff_by_id(){
        $id = htmlentities($_REQUEST['id_staff'], ENT_QUOTES);
        $data = $this->Main_model->get_staff_by_id($id);
        echo json_encode($data->row());
        return;
    }

    function add_staff(){

        if($this->session->userdata('is_admin') != "1" ){
            redirect(base_url('main'));
        }

        $id_staff = strtoupper(trim(htmlentities($_REQUEST['id_staff'], ENT_QUOTES)));
        $nama_staff = trim(htmlentities($_REQUEST['nama_staff'], ENT_QUOTES));
        $tgl_lahir_staff = strtoupper(trim(htmlentities($_REQUEST['tgl_lahir_staff'], ENT_QUOTES)));
        $alamat_staff = strtoupper(trim(htmlentities($_REQUEST['alamat_staff'], ENT_QUOTES)));
        $no_hp_staff = strtoupper(trim(htmlentities($_REQUEST['no_hp_staff'], ENT_QUOTES)));
        $id_posisi = trim(htmlentities($_REQUEST['id_posisi'], ENT_QUOTES));
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

        if($id_posisi == "none"){
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

    function randStr($length = 10) {
        return substr(str_shuffle(str_repeat($x='ABCDEFGHJKMNPQRSTUVWXYZ', ceil($length/strlen($x)) )),1,$length);
    }

    function encode_img_base64($img_path){
        // Read image path, convert to base64 encoding
        $imageData = base64_encode(file_get_contents($img_path));

        // Format the image SRC:  data:{mime};base64,{data};
        $src = 'data: '.mime_content_type($img_path).';base64,'.$imageData;

        return $src;
    }

    function logout(){
        unset(
            $_SESSION['id_user'],
            $_SESSION['id_staff'],
            $_SESSION['is_admin'],
            $_SESSION['username']
        );

        $this->load->helper('url');
        redirect(base_url('home/login'), 'refresh');
    }

    function pdf_order(){

        require_once APPPATH.'third_party/fpdf182/fpdf.php';


        if(isset($_GET['no'])){

            $data_order = $this->Main_model->get_order_detail(htmlentities($_GET['no'], ENT_QUOTES));

            if($data_order->num_rows() == 0){
                // kosong

            } else {

                $data = $data_order->result_object();

//                $html2pdf = new Html2Pdf('P','A4','fr', true, 'UTF-8', array(15, 15, 15, 15), false);
//                $html2pdf->writeHTML($this->load->view('template/invoice', $data, true));
//                $html2pdf->output();

                $pdf = new FPDF('P','mm','A4');
                $pdf->AddPage();

                //set font
                $pdf->AddFont('Nunito','','Nunito-Regular.php');
                $pdf->AddFont('Nunito','B','Nunito-Bold.php');


                $pdf->SetFont('Nunito','B',20);

                //====================== HEADER ======================

                $pdf->Cell(130 ,5,'',0,0);

                if($data_order->row()->brand_order == "KA"){
                    $pdf->Image(base_url('assets/images/logopdf.jpg'), 10, 10, 48, 22 ,'');
                    $brand = "Kedai Amarthya";
                } else if ($data_order->row()->brand_order == "AH"){
                    $pdf->Image(base_url('assets/images/amarthya_herbal.png'), 10, 10, 30, 30 ,'');
                    $brand = "Amarthya Herbal";
                } else if ($data_order->row()->brand_order == "AHF") {
                    $pdf->Image(base_url('assets/images/phonto.PNG'), 10, 10, 30, 30 ,'');
                    $brand = "Amarthya Healthy Food";
                } else if ($data_order->row()->brand_order == "AF") {
                    $pdf->Image(base_url('assets/images/fashion.png'), 10, 0, 48, 48 ,'');
                    $brand = "Amarthya Fashion";
                }


                $x = $pdf->GetX();

                $pdf->Cell(59 ,5,'INVOICE',0,1, 'R');//end of line

                $pdf->SetFont('Nunito','B',11);

                $pdf->SetX($x);
                $pdf->Cell(59 ,5,'',0,1);//end of line

                $pdf->SetX($x);
                $pdf->Cell(25 ,5,'',0,0, 'R');
                $pdf->Cell(34 ,5,$brand,0,1, 'R');//end of line

                $pdf->SetFont('Nunito','',11);

                $pdf->SetX($x);
                $pdf->Cell(25 ,5,'',0,0, 'R');
                $pdf->Cell(34 ,5,'+62 819-3618-1788',0,1, 'R');//end of line

                $pdf->SetX($x);
                $pdf->Cell(25 ,5,'',0,0, 'R');
                $pdf->Cell(34 ,5,'amarthyagroup@gmail.com',0,1, 'R');//end of line

                $pdf->SetX($x);
                $pdf->Cell(25 ,5,'',0,0, 'R');
                $pdf->Cell(34 ,5,'Jalan Gelogor Indah IB, Gg Kresna No 1',0,1, 'R');//end of line

                //buat dummy cell untuk memberi jarak vertikal
                $pdf->Cell(189 ,10,'',0,1);//end of line


                // ================ ORDER INFO ===============

                $pdf->SetFont('Nunito','B',11);
                $pdf->Cell(100 ,5,'Ditagih Kepada',0,1);//end of line

                $pdf->SetFont('Nunito','',11);
                $pdf->Cell(25 ,5,$data_order->row()->nama_customer,0,0);
                $pdf->Cell(95 ,5,'',0,0);
                $pdf->Cell(25 ,5,"Invoice",0,0);
                $pdf->Cell(34 ,5,"#".$data_order->row()->no_order,0,1);



                $pdf->Cell(25 ,5,$data_order->row()->no_hp_customer,0,0);
                $pdf->Cell(95 ,5,'',0,0);
                $pdf->Cell(25 ,5,"Tanggal",0,0);
                $pdf->Cell(34 ,5,date_format(date_create($data_order->row()->tgl_order),"d/m/Y"),0,1);


                //buat dummy cell untuk memberi jarak vertikal
                $pdf->Cell(189 ,10,'',0,1);//end of line

                // ================ INVOICE HEADER ===============

                $pdf->SetFont('Nunito','',10.5);

                $pdf->setFillColor(222,237,247);
                $pdf->Cell(87 ,12,'Barang','',0, 'L', TRUE);
                $pdf->Cell(18 ,12,'Kuantitas','',0,'C', TRUE);
                $pdf->Cell(15 ,12,'Satuan','',0,'C', TRUE);
                $pdf->Cell(35 ,12,'Harga','',0,'R', TRUE);
                $pdf->Cell(35 ,12,'Jumlah','',1, 'R', TRUE);//end of line



                // ================ INVOICE DETAIL ===============

                $pdf->SetFont('Nunito','',10);

                $odd = true;
                foreach ($data as $order){

                    if($odd){
                        $pdf->setFillColor(255,255,255);
                        $odd = false;
                    } else {
                        $pdf->setFillColor(245,245,245);
                        $odd = true;
                    }

                    if($order->is_free == '0'){
                        $pdf->Cell(87 ,9,$order->nama_product,'',0, 'L', TRUE);
                    } else {
                        $pdf->Cell(87 ,9,$order->nama_product." (FREE)",'',0, 'L', TRUE);
                    }


                    $pdf->Cell(18 ,9,$order->qty_order,'',0,'C', TRUE);
                    $pdf->Cell(15 ,9,$order->satuan_product,'',0,'C', TRUE);
                    $pdf->Cell(35 ,9,"Rp. " . number_format($order->harga_order,2,',','.'),'',0,'R', TRUE);
                    $pdf->Cell(35 ,9,"Rp. " . number_format($order->total_order,2,',','.'),'',1, 'R', TRUE);//end of line
                }


                //buat dummy cell untuk memberi jarak vertikal
                $pdf->Cell(189 ,7,'Catatan: '.$order->catatan_order,'T',1);//end of line
                $pdf->Cell(189 ,2,'','',1);//end of line


                // ================ PAYMENT DETAIL ===============

                $payment_detail_height = 6;

                $pdf->SetFont('Nunito','B',10);
                $pdf->Cell(130 ,$payment_detail_height,'Instruksi Pembayaran',0,0);
                $pdf->SetFont('Nunito','',10);
                $pdf->Cell(25 ,$payment_detail_height,'Subtotal',0,0);
                $pdf->Cell(34 ,$payment_detail_height,"Rp. " . number_format($order->subtotal_order,2,',','.'),0,1,'R');//end of line

                $pdf->Cell(130 ,$payment_detail_height,'MNC Bank 206010001126284 An. Ngurah Bramantha Patra',0,0);
                $pdf->Cell(25 ,$payment_detail_height,'Pengiriman',0,0);

                if($data_order->row()->is_ongkir_kas == '0'){
                    $pdf->Cell(34 ,$payment_detail_height,"Rp. " . number_format($order->ongkir_order,2,',','.'),0,1,'R');//end of line
                } else {
                    $pdf->Cell(34 ,$payment_detail_height,"Rp. " . number_format(0,2,',','.'),0,1,'R');//end of line
                }


                $pdf->Cell(130 ,$payment_detail_height,'',0,0);
                $pdf->Cell(25 ,$payment_detail_height,'Diskon',0,0);
                $pdf->Cell(34 ,$payment_detail_height,"(Rp. " . number_format($order->diskon_order,2,',','.').")",0,1,'R');//end of line

                $pdf->SetFont('Nunito','B',10);

                $pdf->Cell(130 ,$payment_detail_height,'',0,0);
                $pdf->Cell(25 ,$payment_detail_height,'Total','B',0);
                $pdf->Cell(34 ,$payment_detail_height,"Rp. " . number_format($order->grand_total_order,2,',','.'),'B',1,'R');//end of line

                // ================ SIGNATURE ===============


                $pdf->Image(base_url('assets/images/ttdarina.png'), 20, $pdf->GetY()+5, 72, 33 ,'');

                $pdf->Cell(75 ,33,'','',1);//end of line
                $pdf->Cell(15 ,1,'','',0);//end of line
                $pdf->Cell(50 ,1,'','T',0);//end of line
                $pdf->Cell(15 ,1,'','',1);//end of line

                $pdf->SetFont('Nunito','',10);
                $pdf->Cell(75 ,5,date_format(date_create($data_order->row()->tgl_order),"d/m/Y"),'',0, 'C');

                $pdf->Output("I", "Invoice #".$data_order->row()->no_order." - ".$data_order->row()->nama_customer.".pdf");
            }

        } else {
            // kosong
        }

    }

    function pdf_tt(){

        require_once APPPATH.'third_party/fpdf182/fpdf.php';


        if(isset($_GET['no'])){

            $data_order = $this->Main_model->get_order_vendor_detail(htmlentities($_GET['no'], ENT_QUOTES));

            if($data_order->num_rows() == 0){
                // kosong

            } else {

                $data = $data_order->result_object();

//                $html2pdf = new Html2Pdf('P','A4','fr', true, 'UTF-8', array(15, 15, 15, 15), false);
//                $html2pdf->writeHTML($this->load->view('template/invoice', $data, true));
//                $html2pdf->output();

                $pdf = new FPDF('P','mm','A4');
                $pdf->AddPage();

                //set font
                $pdf->AddFont('Nunito','','Nunito-Regular.php');
                $pdf->AddFont('Nunito','B','Nunito-Bold.php');


                $pdf->SetFont('Nunito','B',20);

                //====================== HEADER ======================

                $pdf->Cell(130 ,5,'',0,0);

                if($data_order->row()->brand_order == "KA"){
                    $pdf->Image(base_url('assets/images/logopdf.jpg'), 10, 10, 48, 22 ,'');
                    $brand = "Kedai Amarthya";
                } else if ($data_order->row()->brand_order == "AH"){
                    $pdf->Image(base_url('assets/images/amarthya_herbal.png'), 10, 10, 30, 30 ,'');
                    $brand = "Amarthya Herbal";
                } else if ($data_order->row()->brand_order == "AHF") {
                    $pdf->Image(base_url('assets/images/phonto.PNG'), 10, 10, 30, 30 ,'');
                    $brand = "Amarthya Healthy Food";
                } else if ($data_order->row()->brand_order == "AF") {
                    $pdf->Image(base_url('assets/images/fashion.png'), 10, 0, 48, 48 ,'');
                    $brand = "Amarthya Fashion";
                }

                $x = $pdf->GetX();

                $pdf->Cell(59 ,5,'TANDA TERIMA',0,1, 'R');//end of line

                $pdf->SetFont('Nunito','B',11);

                $pdf->SetX($x);
                $pdf->Cell(59 ,5,'',0,1);//end of line

                $pdf->SetX($x);
                $pdf->Cell(25 ,5,'',0,0, 'R');
                $pdf->Cell(34 ,5,$brand,0,1, 'R');//end of line

                $pdf->SetFont('Nunito','',11);

                $pdf->SetX($x);
                $pdf->Cell(25 ,5,'',0,0, 'R');
                $pdf->Cell(34 ,5,'+62 819-3618-1788',0,1, 'R');//end of line

                $pdf->SetX($x);
                $pdf->Cell(25 ,5,'',0,0, 'R');
                $pdf->Cell(34 ,5,'amarthyagroup@gmail.com',0,1, 'R');//end of line

                $pdf->SetX($x);
                $pdf->Cell(25 ,5,'',0,0, 'R');
                $pdf->Cell(34 ,5,'Jalan Gelogor Indah IB, Gg Kresna No 1',0,1, 'R');//end of line

                //buat dummy cell untuk memberi jarak vertikal
                $pdf->Cell(189 ,10,'',0,1);//end of line


                // ================ ORDER INFO ===============

                $pdf->SetFont('Nunito','',10);
                $pdf->Cell(35 ,5,'Info Pembayaran:',0,0);
                $pdf->Cell(90 ,5,$data_order->row()->payment_detail,0,1);

                // grey line
                $pdf->setFillColor(201,201,201);
                $pdf->Cell(189 ,5,'',0,1, 'L', TRUE);

                $pdf->Cell(35 ,6,'Telah terima dari: ',0,0);
                $pdf->Cell(25 ,6,$data_order->row()->nama_vendor,0,1);

                $pdf->Cell(35 ,6,'No. Rek: ',0,0);
                $pdf->Cell(25 ,6,$data_order->row()->no_rekening_vendor." (".$data_order->row()->nama_bank_vendor.")",0,1);

                $pdf->Cell(35 ,6,'pada tanggal: ',0,0);
                $pdf->Cell(25 ,6,date_format(date_create($data_order->row()->tgl_order_vendor),"d/m/Y"),0,1);

                $pdf->Cell(35 ,6,'Berupa: ',0,0);


                // ================ INVOICE HEADER ===============

                $pdf->SetFont('Nunito','',10);

                $pdf->setFillColor(201,201,201);
                $pdf->Cell(54 ,6,'Barang','',0, 'L', TRUE);
//                $pdf->Cell(58 ,5,'','',0, 'L');
                $pdf->Cell(15 ,6,'Qty','',0,'C', TRUE);
                $pdf->Cell(15 ,6,'Satuan','',0,'C', TRUE);
                $pdf->Cell(35 ,6,'Harga','',0,'R', TRUE);
                $pdf->Cell(35 ,6,'Total','',1, 'R', TRUE);//end of line


                // ================ TANDA TERIMA DETAIL ===============

                $pdf->SetFont('Nunito','',10);
                $pdf->setFillColor(255,255,255);

                foreach ($data as $order){
                    $pdf->Cell(35 ,6,' ',0,0);
                    $pdf->Cell(54 ,6,$order->nama_product,'',0, 'L', TRUE);
                    $pdf->Cell(15 ,6,$order->qty_order_vendor,'',0,'C', TRUE);
                    $pdf->Cell(15 ,6,$order->satuan_product,'',0,'C', TRUE);
                    $pdf->Cell(35 ,6,"Rp. " . number_format($order->harga_order_vendor,2,',','.'),'',0,'R', TRUE);
                    $pdf->Cell(35 ,6,"Rp. " . number_format($order->total_order_vendor,2,',','.'),'',1, 'R', TRUE);//end of line
                }

                // ================ SIGNATURE ===============

                // dummy line
                $pdf->Cell(189 ,15,'',0, 1);

                $pdf->Cell(35 ,5,'Catatan',0,0);
                $pdf->Cell(35 ,5,": ".$data_order->row()->catatan_order_vendor,0,1);

                $pdf->Cell(35 ,5,'Diterima oleh',0,0);
                $pdf->Cell(35 ,5,': A.A.A. Saraswati Hardy ',0,1);

                $pdf->Cell(139 ,5,'',0,0, 'L', TRUE);
                $pdf->Cell(15 ,5,'Subtotal:',0,0, 'L', TRUE);
                $pdf->Cell(35 ,5,"Rp. " . number_format($data_order->row()->diskon_order_vendor + $data_order->row()->grand_total_order,2,',','.'),0,1, 'R', TRUE);

                $pdf->Cell(139 ,5,'',0,0, 'L', TRUE);
                $pdf->Cell(15 ,5,'Diskon:',0,0, 'L', TRUE);
                $pdf->Cell(35 ,5,"Rp. " . number_format($data_order->row()->diskon_order_vendor,2,',','.'),0,1, 'R', TRUE);

                $pdf->setFillColor(201,201,201);
                $pdf->Cell(139 ,5,'',0,0, 'L', TRUE);
                $pdf->SetFont('Nunito','B',10);
                $pdf->Cell(15 ,5,'Total:',0,0, 'L', TRUE);
                $pdf->Cell(35 ,5,"Rp. " . number_format($data_order->row()->grand_total_order,2,',','.'),0,1, 'R', TRUE);


                $pdf->SetFont('Nunito','',10);


//                $pdf->Cell(75 ,5,date_format(date_create($data_order->row()->tgl_order_vendor),"d/m/Y"),'',0, 'C');

                $pdf->Output("I", "Tanda Terima #".$data_order->row()->no_order_vendor." - ".$data_order->row()->nama_vendor.".pdf");
            }

        } else {
            // kosong
        }

    }

    function pdf_slip_gaji(){

        require_once APPPATH.'third_party/fpdf182/fpdf.php';

        if(isset($_GET['staff']) && isset($_GET['periode']) && isset($_GET['bulan']) && isset($_GET['tahun'])){

            date_default_timezone_set('Asia/Singapore');

            $id_staff = htmlentities($_GET['staff'], ENT_QUOTES);
            $awal_akhir_salary = htmlentities($_GET['periode'], ENT_QUOTES);
            $bulan_salary = htmlentities($_GET['bulan'], ENT_QUOTES);
            $tahun_salary = htmlentities($_GET['tahun'], ENT_QUOTES);

            $temp_date = new DateTime("$tahun_salary-$bulan_salary-01");

            // Periode Delivery
            if($awal_akhir_salary == "AWAL"){
                $tgl_awal = $tahun_salary."-".sprintf('%02d', $bulan_salary)."-01";
                $tgl_akhir = $tahun_salary."-".sprintf('%02d', $bulan_salary)."-15";

                $start = "01-".sprintf('%02d', $bulan_salary)."-".$tahun_salary;
                $end = "15-".sprintf('%02d', $bulan_salary)."-".$tahun_salary;

            } else if($awal_akhir_salary == "AKHIR"){
                $tgl_awal = $tahun_salary."-".sprintf('%02d', $bulan_salary)."-16";
                $tgl_akhir = $tahun_salary."-".sprintf('%02d', $bulan_salary)."-".$temp_date->format('t');

                $start = "16-".sprintf('%02d', $bulan_salary)."-".$tahun_salary;
                $end = $temp_date->format('t').sprintf('%02d', $bulan_salary)."-".$tahun_salary;
            }

            $data_salary = $this->Main_model->get_staff_salary($id_staff, $awal_akhir_salary, $bulan_salary, $tahun_salary, $tgl_awal, $tgl_akhir);

            // check if ID staff in parameters match logged in ID staff
            if($this->session->userdata('is_admin') == "0"){
                if($id_staff != $this->session->userdata('id_staff')){
                    echo "Unauthorized";
                    return;
                }
            }

            if($data_salary->num_rows() == 0){
                // empty


            } else {
                $data = $data_salary->row();

                $pdf = new FPDF('P','mm','A4');
                $pdf->AddPage();

                //set font
                $pdf->AddFont('Nunito','','Nunito-Regular.php');
                $pdf->AddFont('Nunito','B','Nunito-Bold.php');


                $pdf->SetFont('Nunito','B',20);

                //====================== HEADER ======================

                $pdf->Cell(130 ,5,'',0,0);
                $pdf->Image(base_url('assets/images/logopdf.jpg'), 10, 10, 48, 22 ,'');
                $x = $pdf->GetX();

                $pdf->Cell(59 ,5,'SLIP GAJI',0,1, 'R');//end of line

                $pdf->SetFont('Nunito','B',11);

                $pdf->SetX($x);
                $pdf->Cell(59 ,5,'',0,1);//end of line

                $pdf->SetX($x);
                $pdf->Cell(25 ,5,'',0,0, 'R');
                $pdf->Cell(34 ,5,'Kedai Amarthya',0,1, 'R');//end of line

                $pdf->SetFont('Nunito','',11);

                $pdf->SetX($x);
                $pdf->Cell(25 ,5,'',0,0, 'R');
                $pdf->Cell(34 ,5,'+62 819-3618-1788',0,1, 'R');//end of line

                $pdf->SetX($x);
                $pdf->Cell(25 ,5,'',0,0, 'R');
                $pdf->Cell(34 ,5,'kedai.amarthya@gmail.com',0,1, 'R');//end of line

                $pdf->SetX($x);
                $pdf->Cell(25 ,5,'',0,0, 'R');
                $pdf->Cell(34 ,5,'Jalan Gelogor Indah IB, Gg Kresna No 1',0,1, 'R');//end of line

                //buat dummy cell untuk memberi jarak vertikal
                $pdf->Cell(189 ,10,'',0,1);//end of line


                // ================ SALARY INFO ===============
                $pdf->SetFont('Nunito','',10);
                $pdf->setFillColor(226,239,218);

                $pdf->Cell(20 ,6,'Nama',"L,T",0, 'L', TRUE);
                $pdf->Cell(87 ,6,": ".$data->nama_staff,"T",0, 'L', TRUE);
                $pdf->Cell(20 ,6,"Periode","T",0, 'L', TRUE);
                $pdf->Cell(62 ,6,": ".$start." s/d ".$end,"T, R",1, 'L', TRUE);

                $pdf->Cell(20 ,6,'Jabatan ',"L,B",0, 'L', TRUE);
                $pdf->Cell(87 ,6,": ".$data->nama_posisi,"B",0, 'L', TRUE);
                $pdf->Cell(20 ,6,"No. Rek","B",0, 'L', TRUE);
                $pdf->Cell(62 ,6,": ".$data->no_rek_staff." (".$data->nama_bank_staff.") ","B, R",1, 'L', TRUE);


                // ================ SALARY HEADER ===============

                $pdf->SetFont('Nunito','',10);
                $pdf->setFillColor(217,225,242);

                $pdf->Cell(20 ,6,'No',1,0, 'C', TRUE);
                $pdf->Cell(87 ,6,'Keterangan',1,0, 'C', TRUE);
                $pdf->Cell(82 ,6,'Jumlah',1,1, 'C', TRUE);



                // ================ SALARY DETAIL ===============

                $pdf->SetFont('Nunito','',10);
                $pdf->setFillColor(255,255,255);



                $pdf->Cell(20 ,6,'1','T, L, R',0, 'R', TRUE);
                $pdf->Cell(87 ,6,'Gaji','T, L, R',0, 'L', TRUE);
                $pdf->Cell(82 ,6,"Rp. " . number_format($data->salary_staff,2,',','.'),'T, L, R',1, 'R', TRUE);


                $pdf->Cell(20 ,6,'2',' L, R',0, 'R', TRUE);
                $pdf->Cell(87 ,6,'Upah Delivery','L, R',0, 'L', TRUE);
                $pdf->Cell(82 ,6,"Rp. " . number_format($data->ongkir_salary,2,',','.'),'L, R',1, 'R', TRUE);

                $pdf->Cell(20 ,6,'3',' L, R',0, 'R', TRUE);
                $pdf->Cell(87 ,6,'Fee Penjualan','L, R',0, 'L', TRUE);
                $pdf->Cell(82 ,6,"Rp. " . number_format($data->fee_penjualan_salary,2,',','.'),'L, R',1, 'R', TRUE);

                $pdf->Cell(20 ,6,'4',' L, R',0, 'R', TRUE);
                $pdf->Cell(87 ,6,'Lembur','L, R',0, 'L', TRUE);
                $pdf->Cell(82 ,6,"Rp. " . number_format($data->lembur_salary,2,',','.'),'L, R',1, 'R', TRUE);

                $pdf->Cell(20 ,6,'5',' L, R',0, 'R', TRUE);
                $pdf->Cell(87 ,6,'Kas Bon','L, R',0, 'L', TRUE);
                $pdf->Cell(82 ,6,"Rp. " . number_format($data->kas_bon_salary,2,',','.'),'L, R',1, 'R', TRUE);

                $pdf->Cell(20 ,6,'6',' L, R',0, 'R', TRUE);
                $pdf->Cell(87 ,6,'Potongan Kas Bon','L, R',0, 'L', TRUE);
                $pdf->Cell(82 ,6,"(Rp. " . number_format($data->potongan_kas_bon_salary,2,',','.').")",'L, R',1, 'R', TRUE);

                $pdf->Cell(20 ,6,'7',' L, R',0, 'R', TRUE);
                $pdf->Cell(87 ,6,'THR','L, R',0, 'L', TRUE);
                $pdf->Cell(82 ,6,"Rp. " . number_format($data->THR_salary,2,',','.'),'L, R',1, 'R', TRUE);

                if($data->lain_lain_salary != 0 || !empty($data->lain_lain_salary)){
                    $pdf->Cell(20 ,6,'8',' L, R',0, 'R', TRUE);
                    $pdf->Cell(87 ,6,'Dana Kuota Internet','L, R',0, 'L', TRUE);
                    $pdf->Cell(82 ,6,"Rp. " . number_format($data->kuota_internet_salary,2,',','.'),'L, R',1, 'R', TRUE);

                    $pdf->Cell(20 ,6,'9',' L, R, B',0, 'R', TRUE);
                    $pdf->Cell(87 ,6,"Lain-lain (".$data->catatan_lain_lain.")",'L, R, B',0, 'L', TRUE);
                    $pdf->Cell(82 ,6,"Rp. " . number_format($data->lain_lain_salary,2,',','.'),'L, R, B',1, 'R', TRUE);

                } else {
                    $pdf->Cell(20 ,6,'8',' L, R, B',0, 'R', TRUE);
                    $pdf->Cell(87 ,6,'Dana Kuota Internet','L, R, B',0, 'L', TRUE);
                    $pdf->Cell(82 ,6,"Rp. " . number_format($data->kuota_internet_salary,2,',','.'),'L, R, B',1, 'R', TRUE);
                }

                $pdf->setFillColor(226,239,218);
                $pdf->SetFont('Nunito','B',10);

                $total_gaji = (empty($data->salary_staff)) ? 0 : $data->salary_staff +
                floatval((empty($data->ongkir_salary)) ? 0 : $data->ongkir_salary) +
                    floatval((empty($data->lembur_salary)) ? 0 : $data->lembur_salary) +
                        floatval((empty($data->fee_penjualan_salary)) ? 0 : $data->fee_penjualan_salary) +
                            floatval((empty($data->kuota_internet_salary)) ? 0 : $data->kuota_internet_salary) +
                                floatval((empty($data->kas_bon_salary)) ? 0 : $data->kas_bon_salary) +
                                    floatval((empty($data->THR_salary)) ? 0 : $data->THR_salary) +
                                        floatval((empty($data->lain_lain_salary)) ? 0 : $data->lain_lain_salary) -
                                            floatval((empty($data->potongan_kas_bon_salary)) ? 0 : $data->potongan_kas_bon_salary);

                $pdf->Cell(189 ,6,'Total: '."Rp. " . number_format($total_gaji,2,',','.'),1, 1, 'R', TRUE);


                // ================ SIGNATURE ===============

                // dummy line
                $pdf->Cell(189 ,15,'',0, 1);
                $pdf->SetFont('Nunito','',10);

                $pdf->Cell(20 ,6,'',0,0, 'R');
                $pdf->Cell(52 ,6,'Diterima',0,0, 'C');
                $pdf->Cell(25 ,6,'',0,0, 'R');
                $pdf->Cell(82 ,6,'Mengetahui',0,1, 'C');

                $pdf->Image(base_url('assets/images/ttdarina.png'), 125, $pdf->GetY()-1, 54, 25 ,'');

                $pdf->Cell(189 ,25,'',0, 1);

                $pdf->Cell(20 ,6,'',0,0, 'R');
                $pdf->Cell(52 ,6,$data->nama_staff,0,0, 'C');
                $pdf->Cell(25 ,6,'',0,0, 'R');
                $pdf->Cell(82 ,6,'Arina Hardy',0,1, 'C');



                $pdf->Output("I", "Slip Gaji - ".$data->nama_staff." (".$start." sampai ".$end.").pdf");

            }


        } else {

        }

    }

    function upload_excel(){
        $this->load->view('template/upload_excel');
    }

    function process_excel(){
        include APPPATH.'third_party/PHPExcel/PHPExcel.php';

//        $final_filename = time()."Customer";
        $final_filename = time()."Fashion";
        $config['upload_path'] = './assets/upload/excel/';
        $config['allowed_types'] = 'xls|xlsx|csv';
        $config['file_name'] = $final_filename;

        $this->load->library('upload');
        $this->upload->initialize($config);

        if ($this->upload->do_upload('excel')){
            $media = $this->upload->data();
            $inputFileName = 'assets/upload/excel/'.$media['file_name'];
            $isheet = 0;
            $irow = 3;
            $icol = 'A';

            try {
                $inputFileType = PHPExcel_IOFactory::identify($inputFileName);
                $objReader = PHPExcel_IOFactory::createReader($inputFileType);
                $objPHPExcel = $objReader->load($inputFileName);
            } catch(Exception $e) {
                die('Error loading file "'.pathinfo($inputFileName,PATHINFO_BASENAME).'": '.$e->getMessage());
            }

            $sheet = $objPHPExcel->getSheet(intval($isheet));
            $highestRow = $sheet->getHighestRow();
            $highestColumn = $sheet->getHighestColumn();

            for ($row = intval($irow); $row <= $highestRow; $row++){
                //  Read a row of data into an array
                $rowData = $sheet->rangeToArray($icol . $row . ':' . $highestColumn . $row,NULL,TRUE,FALSE);
                $rec_tbl[] = $rowData[0];
            }

//            print_r($rec_tbl);
//            return;

            //================== Customer Excel ===============

//            foreach($rec_tbl as $data){
//                set_time_limit(0);
//
//                $nama_customer = htmlentities(trim($data[1]));
//                $alamat_customer = htmlentities(trim($data[2]));
//                $no_hp_customer = htmlentities(trim($data[4]));
//
//                $email_customer = "";
//                $catatan_customer = "";
//
//                $customer_data = compact('nama_customer', 'alamat_customer', 'no_hp_customer', 'email_customer', 'catatan_customer');
//
//                $this->Main_model->add_customer($customer_data);
//
//            }

            //================== Vendor Excel ===============

//            foreach($rec_tbl as $data){
//                set_time_limit(0);
//
//                $nama_vendor = htmlentities(trim($data[1]));
//                $catatan_vendor = htmlentities(trim($data[2]));
//                $alamat_vendor = htmlentities(trim($data[3]));
//                $no_hp_vendor = htmlentities(trim($data[4]));
//                $no_rekening_vendor = htmlentities(trim($data[5]));
//                $nama_bank_vendor = htmlentities(trim($data[6]));
//
//                $email_vendor = "";
//
//                $vendor_data = compact('nama_vendor', 'alamat_vendor', 'no_hp_vendor', 'email_vendor',
//                                        'catatan_vendor', 'no_rekening_vendor', 'nama_bank_vendor');
//
//                $this->Main_model->add_vendor($vendor_data);
//
//            }

            //================== Product Excel ===============

            date_default_timezone_set('Asia/Singapore');

            foreach($rec_tbl as $data){
                set_time_limit(0);

                $nama_product = htmlentities(trim($data[1]));
                $SKU_product = htmlentities(trim($data[2]));
                $satuan_product = htmlentities(trim($data[3]));
                $HP_product = htmlentities(trim($data[4]));
                $HR_product = htmlentities(trim($data[5]));
                $HJ_product = htmlentities(trim($data[6]));
                $brand_product = "AF";

                $stok_in_out = htmlentities(trim($data[7]));

                $this->db->trans_begin();

                // add product
                $product_data = compact('nama_product', 'SKU_product', 'satuan_product', 'HP_product',
                                        'HR_product', 'HJ_product', 'brand_product');

                $id_product = $this->Main_model->add_product($product_data);

                if($id_product){

                    $tipe_in_out = "IN";
                    $tgl_in = date('Y-m-d H:i:s');

                    $catatan_in_out = "";
                    $ref_order_m = "";
                    $tgl_out = "";
                    $tgl_expired = "";


                    $data_in_out = compact('tipe_in_out', 'stok_in_out', 'tgl_in', 'id_product', 'catatan_in_out', 'ref_order_m',
                        'tgl_out', 'tgl_expired');

                    $id_stok_in_out = $this->Main_model->add_stok_in_out($data_in_out);

                    if(!$id_stok_in_out){
                        $this->db->trans_rollback();
                        $return_arr = array("Status" => 'ERROR', "Message" => 'Gagal menambahkan in out');
                        echo json_encode($return_arr);
                        return;
                    } else {
                        $this->db->trans_commit();
                    }

                } else {
                    $this->db->trans_rollback();
                    $return_arr = array("Status" => 'ERROR', "Message" => 'Gagal menambahkan product');
                    echo json_encode($return_arr);
                    return;

                }

            }

            $return_arr = array("Status" => 'OK', "Message" => 'Done');
            echo json_encode($return_arr);


        }
    }

    function excel_jurnal_umum(){

        if($this->session->userdata('is_admin') != "1"){
            echo "Unauthorized";
            return;
        }

        if(!isset($_GET['start']) || !isset($_GET['end']) || !isset($_GET['brand']) || !isset($_GET['tipe']) || !isset($_GET['flow'])){
            echo "Invalid data";
            return;
        }

        include APPPATH.'third_party/PHPExcel/PHPExcel.php';

        $startRow = 1;
        $objPHPExcel = new PHPExcel();

        $start_date = htmlentities($_GET['start'], ENT_QUOTES);
        $end_date = htmlentities($_GET['end'], ENT_QUOTES);
        $brand_order = htmlentities($_GET['brand'], ENT_QUOTES);
        $tipe_order = htmlentities($_GET['tipe'], ENT_QUOTES);
        $cash_flow = htmlentities($_GET['flow'], ENT_QUOTES);

        $data = $this->Main_model->jurnal_umum_gabung($start_date, $end_date, $brand_order, $tipe_order, $cash_flow, true)->result_object();

        if(empty($data)){
            echo "No data";
            return;
        }

        $objPHPExcel->setActiveSheetIndex(0);

        $objPHPExcel->getActiveSheet()->SetCellValue("A".$startRow, "Laporan Transaksi");
        $objPHPExcel->getActiveSheet()->getStyle("A$startRow")->getFont()->setBold( true );
        $objPHPExcel->getActiveSheet()->getStyle("A$startRow")->getFont()->setItalic( true );
        $objPHPExcel->getActiveSheet()->getStyle("A$startRow")->getFont()->setSize(14);

        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(6);
        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(18);
        $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(18);
        $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(37);
        $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(16);
        $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(16);
        $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(16);

        $startRow = 3;

        $objPHPExcel->getActiveSheet()->SetCellValue("B".$startRow, "Periode");
        $objPHPExcel->getActiveSheet()->SetCellValue("C".$startRow, "$start_date sampai dengan $end_date");

        $startRow++;

        $objPHPExcel->getActiveSheet()->SetCellValue("B".$startRow, "Brand");
        $objPHPExcel->getActiveSheet()->SetCellValue("C".$startRow, $this->get_brand($brand_order));

        $startRow++;

        $objPHPExcel->getActiveSheet()->SetCellValue("B".$startRow, "Pembayaran");
        $objPHPExcel->getActiveSheet()->SetCellValue("C".$startRow, $this->get_tipe_order($tipe_order));

        $startRow++;

        $objPHPExcel->getActiveSheet()->SetCellValue("B".$startRow, "Arus Kas");
        $objPHPExcel->getActiveSheet()->SetCellValue("C".$startRow, $this->get_arus_kas($cash_flow));

        $objPHPExcel->getActiveSheet()->getStyle("B3:D$startRow")->applyFromArray(
            array(
                'borders' => array(
                    'outline' => array(
                        'style' => PHPExcel_Style_Border::BORDER_THIN,
                        'color' => array('rgb' => 'DDDDDD')
                    )
                )
            )
        );

        $startRow+=2;

        $objPHPExcel->getActiveSheet()->SetCellValue("A".$startRow, "No");
        $objPHPExcel->getActiveSheet()->SetCellValue("B".$startRow, "Brand");
        $objPHPExcel->getActiveSheet()->SetCellValue("C".$startRow, "Tanggal");
        $objPHPExcel->getActiveSheet()->SetCellValue("D".$startRow, "Keterangan");
        $objPHPExcel->getActiveSheet()->SetCellValue("E".$startRow, "Debet");
        $objPHPExcel->getActiveSheet()->SetCellValue("F".$startRow, "Kredit");
        $objPHPExcel->getActiveSheet()->SetCellValue("G".$startRow, "Mutasi");
        $objPHPExcel->getActiveSheet()->SetCellValue("H".$startRow, "");

        $objPHPExcel->getActiveSheet()
            ->getStyle("E$startRow:G$startRow")
            ->getAlignment()
            ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

        $objPHPExcel->getActiveSheet()->getStyle("A$startRow:H$startRow")->getFill()->applyFromArray(array(
            'type' => PHPExcel_Style_Fill::FILL_SOLID,
            'startcolor' => array(
                'rgb' => 'C0BEBF'
            )
        ));

        $objPHPExcel->getActiveSheet()->getStyle("A$startRow:H$startRow")->getFont()->setBold( true );

        $startRow++;
        $data_start = $startRow;
        $no = 1;

        foreach($data as $row){
            $objPHPExcel->getActiveSheet()->SetCellValue("A".$startRow, $no);
            $objPHPExcel->getActiveSheet()->SetCellValue("B".$startRow, html_entity_decode($this->get_brand($row->brand_order), ENT_QUOTES,'UTF-8'));
            $objPHPExcel->getActiveSheet()->SetCellValue("C".$startRow, date("D, M j, Y",strtotime($row->tgl_order)));

            if($row->NAMA == "empty"){
                $objPHPExcel->getActiveSheet()->SetCellValue("D".$startRow, html_entity_decode($row->no_order, ENT_QUOTES,'UTF-8'));
            } else {
                $objPHPExcel->getActiveSheet()->SetCellValue("D".$startRow, html_entity_decode($row->no_order, ENT_QUOTES,'UTF-8')."\n".$row->NAMA);
            }



            $objPHPExcel->getActiveSheet()->SetCellValue("E".$startRow, (int)$row->DEBET);
            $objPHPExcel->getActiveSheet()->SetCellValue("F".$startRow, (int)$row->KREDIT);
            $objPHPExcel->getActiveSheet()->SetCellValue("G".$startRow, (int)$row->MUTASI);
            $objPHPExcel->getActiveSheet()->SetCellValue("H".$startRow, $this->get_tipe_order($row->tipe_order));

            if($no % 2 == 0){
                $objPHPExcel->getActiveSheet()->getStyle("A$startRow:H$startRow")->getFill()->applyFromArray(array(
                    'type' => PHPExcel_Style_Fill::FILL_SOLID,
                    'startcolor' => array(
                        'rgb' => 'F3F3F3'
                    )
                ));
            }

            $startRow++;
            $no++;
        }


        $objPHPExcel->getActiveSheet()
            ->getStyle("E$data_start:G$startRow")
            ->getAlignment()
            ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

        $objPHPExcel->getActiveSheet()->getStyle("E$data_start:G$startRow")->getNumberFormat()->setFormatCode('#,##0');
        $objPHPExcel->getActiveSheet()->getStyle("C$data_start:D$startRow")->getAlignment()->setWrapText(true);
        $objPHPExcel->getActiveSheet()->getStyle("A$data_start:H$startRow")->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);

        $objPHPExcel->getActiveSheet()->setShowGridlines(false);


        $filename = "Laporan Transaksi ($start_date sampai $end_date)";
        header('Content-Type: application/vnd.ms-excel'); //mime type
        header('Content-Disposition: attachment;filename="'.$filename.'.xlsx"'); //tell browser what's the file name
        header('Cache-Control: max-age=0'); //no cache
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save('php://output');

    }

    function excel_laporan_produk(){

        if($this->session->userdata('is_admin') != "1"){
            echo "Unauthorized";
            return;
        }

        if(!isset($_GET['start']) || !isset($_GET['end'])){
            echo "Invalid data";
            return;
        }

        include APPPATH.'third_party/PHPExcel/PHPExcel.php';

        $startRow = 1;
        $objPHPExcel = new PHPExcel();

        $start_date = htmlentities($_GET['start'], ENT_QUOTES);
        $end_date = htmlentities($_GET['end'], ENT_QUOTES);

        $data = $this->Main_model->laporan_produk($start_date, $end_date)->result_object();

        if(empty($data)){
            echo "No data";
            return;
        }

        $objPHPExcel->setActiveSheetIndex(0);

        $objPHPExcel->getActiveSheet()->SetCellValue("A".$startRow, "Laporan Produk");
        $objPHPExcel->getActiveSheet()->getStyle("A$startRow")->getFont()->setBold( true );
        $objPHPExcel->getActiveSheet()->getStyle("A$startRow")->getFont()->setItalic( true );
        $objPHPExcel->getActiveSheet()->getStyle("A$startRow")->getFont()->setSize(14);

        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(6);
        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(33);
        $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(18);
        $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(9);
        $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(9);

        $startRow = 3;

        $objPHPExcel->getActiveSheet()->SetCellValue("B".$startRow, "Periode");
        $startRow++;
        $objPHPExcel->getActiveSheet()->SetCellValue("B".$startRow, "$start_date sampai dengan $end_date");


        $objPHPExcel->getActiveSheet()->getStyle("B3:D$startRow")->applyFromArray(
            array(
                'borders' => array(
                    'outline' => array(
                        'style' => PHPExcel_Style_Border::BORDER_THIN,
                        'color' => array('rgb' => 'DDDDDD')
                    )
                )
            )
        );

        $startRow+=2;

        $objPHPExcel->getActiveSheet()->SetCellValue("A".$startRow, "No");
        $objPHPExcel->getActiveSheet()->SetCellValue("B".$startRow, "Produk");
        $objPHPExcel->getActiveSheet()->SetCellValue("C".$startRow, "Brand");
        $objPHPExcel->getActiveSheet()->SetCellValue("D".$startRow, "Stok Out");
        $objPHPExcel->getActiveSheet()->SetCellValue("E".$startRow, "Satuan");


        $objPHPExcel->getActiveSheet()->getStyle("A$startRow:E$startRow")->getFill()->applyFromArray(array(
            'type' => PHPExcel_Style_Fill::FILL_SOLID,
            'startcolor' => array(
                'rgb' => 'C0BEBF'
            )
        ));

        $objPHPExcel->getActiveSheet()->getStyle("A$startRow:E$startRow")->getFont()->setBold( true );

        $startRow++;
        $data_start = $startRow;
        $no = 1;

        foreach($data as $row){
            $objPHPExcel->getActiveSheet()->SetCellValue("A".$startRow, $no);
            $objPHPExcel->getActiveSheet()->SetCellValue("B".$startRow, $row->nama_product);
            $objPHPExcel->getActiveSheet()->SetCellValue("C".$startRow, html_entity_decode($this->get_brand($row->brand_product), ENT_QUOTES,'UTF-8'));
            $objPHPExcel->getActiveSheet()->SetCellValue("D".$startRow, $row->stok_out);
            $objPHPExcel->getActiveSheet()->SetCellValue("E".$startRow, $row->satuan_product);

            if($no % 2 == 0){
                $objPHPExcel->getActiveSheet()->getStyle("A$startRow:E$startRow")->getFill()->applyFromArray(array(
                    'type' => PHPExcel_Style_Fill::FILL_SOLID,
                    'startcolor' => array(
                        'rgb' => 'F3F3F3'
                    )
                ));
            }

            $startRow++;
            $no++;
        }


        $objPHPExcel->getActiveSheet()->getStyle("B$data_start:C$startRow")->getAlignment()->setWrapText(true);
        $objPHPExcel->getActiveSheet()->getStyle("A$data_start:E$startRow")->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);

        $objPHPExcel->getActiveSheet()->setShowGridlines(false);


        $filename = "Laporan Produk ($start_date sampai $end_date)";
        header('Content-Type: application/vnd.ms-excel'); //mime type
        header('Content-Disposition: attachment;filename="'.$filename.'.xlsx"'); //tell browser what's the file name
        header('Cache-Control: max-age=0'); //no cache
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save('php://output');

    }

    function excel_laporan_sales(){

        if($this->session->userdata('is_admin') != "1"){
            echo "Unauthorized";
            return;
        }

        if(!isset($_GET['start']) || !isset($_GET['end'])){
            echo "Invalid data";
            return;
        }

        include APPPATH.'third_party/PHPExcel/PHPExcel.php';

        $startRow = 1;
        $objPHPExcel = new PHPExcel();

        $start_date = htmlentities($_GET['start'], ENT_QUOTES);
        $end_date = htmlentities($_GET['end'], ENT_QUOTES);

        $data = $this->Main_model->laporan_sales($start_date, $end_date)->result_object();

        if(empty($data)){
            echo "No data";
            return;
        }

        $objPHPExcel->setActiveSheetIndex(0);

        $objPHPExcel->getActiveSheet()->SetCellValue("A".$startRow, "Laporan Sales per Customer");
        $objPHPExcel->getActiveSheet()->getStyle("A$startRow")->getFont()->setBold( true );
        $objPHPExcel->getActiveSheet()->getStyle("A$startRow")->getFont()->setItalic( true );
        $objPHPExcel->getActiveSheet()->getStyle("A$startRow")->getFont()->setSize(14);

        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(6);
        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(33);
        $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(15);
        $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(15);
        $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(15);

        $startRow = 3;

        $objPHPExcel->getActiveSheet()->SetCellValue("B".$startRow, "Periode");
        $startRow++;
        $objPHPExcel->getActiveSheet()->SetCellValue("B".$startRow, "$start_date sampai dengan $end_date");


        $objPHPExcel->getActiveSheet()->getStyle("B3:D$startRow")->applyFromArray(
            array(
                'borders' => array(
                    'outline' => array(
                        'style' => PHPExcel_Style_Border::BORDER_THIN,
                        'color' => array('rgb' => 'DDDDDD')
                    )
                )
            )
        );

        $startRow+=2;

        $objPHPExcel->getActiveSheet()->SetCellValue("A".$startRow, "No");
        $objPHPExcel->getActiveSheet()->SetCellValue("B".$startRow, "Customer");
        $objPHPExcel->getActiveSheet()->SetCellValue("C".$startRow, "Total Pesanan");
        $objPHPExcel->getActiveSheet()->SetCellValue("D".$startRow, "Ongkir");
        $objPHPExcel->getActiveSheet()->SetCellValue("E".$startRow, "Total Belanja");


        $objPHPExcel->getActiveSheet()->getStyle("A$startRow:E$startRow")->getFill()->applyFromArray(array(
            'type' => PHPExcel_Style_Fill::FILL_SOLID,
            'startcolor' => array(
                'rgb' => 'C0BEBF'
            )
        ));

        $objPHPExcel->getActiveSheet()->getStyle("A$startRow:E$startRow")->getFont()->setBold( true );

        $startRow++;
        $data_start = $startRow;
        $no = 1;

        foreach($data as $row){
            $objPHPExcel->getActiveSheet()->SetCellValue("A".$startRow, $no);
            $objPHPExcel->getActiveSheet()->SetCellValue("B".$startRow, $row->nama_customer);
            $objPHPExcel->getActiveSheet()->SetCellValue("C".$startRow, (int)$row->total_order);
            $objPHPExcel->getActiveSheet()->SetCellValue("D".$startRow, (int)$row->ongkir_order);
            $objPHPExcel->getActiveSheet()->SetCellValue("E".$startRow, (int)$row->total_belanja);

            if($no % 2 == 0){
                $objPHPExcel->getActiveSheet()->getStyle("A$startRow:E$startRow")->getFill()->applyFromArray(array(
                    'type' => PHPExcel_Style_Fill::FILL_SOLID,
                    'startcolor' => array(
                        'rgb' => 'F3F3F3'
                    )
                ));
            }

            $startRow++;
            $no++;
        }

        $objPHPExcel->getActiveSheet()
            ->getStyle("C$data_start:E$startRow")
            ->getAlignment()
            ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

        $objPHPExcel->getActiveSheet()->getStyle("C$data_start:E$startRow")->getNumberFormat()->setFormatCode('#,##0');
        $objPHPExcel->getActiveSheet()->getStyle("B$data_start:B$startRow")->getAlignment()->setWrapText(true);
        $objPHPExcel->getActiveSheet()->getStyle("A$data_start:E$startRow")->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);

        $objPHPExcel->getActiveSheet()->setShowGridlines(false);


        $filename = "Laporan Sales per Customer ($start_date sampai $end_date)";
        header('Content-Type: application/vnd.ms-excel'); //mime type
        header('Content-Disposition: attachment;filename="'.$filename.'.xlsx"'); //tell browser what's the file name
        header('Cache-Control: max-age=0'); //no cache
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save('php://output');

    }

    function excel_laporan_purchase(){

        if($this->session->userdata('is_admin') != "1"){
            echo "Unauthorized";
            return;
        }

        if(!isset($_GET['start']) || !isset($_GET['end'])){
            echo "Invalid data";
            return;
        }

        include APPPATH.'third_party/PHPExcel/PHPExcel.php';

        $startRow = 1;
        $objPHPExcel = new PHPExcel();

        $start_date = htmlentities($_GET['start'], ENT_QUOTES);
        $end_date = htmlentities($_GET['end'], ENT_QUOTES);

        $data = $this->Main_model->laporan_purchase($start_date, $end_date)->result_object();

        if(empty($data)){
            echo "No data";
            return;
        }

        $objPHPExcel->setActiveSheetIndex(0);

        $objPHPExcel->getActiveSheet()->SetCellValue("A".$startRow, "Laporan Sales per Customer");
        $objPHPExcel->getActiveSheet()->getStyle("A$startRow")->getFont()->setBold( true );
        $objPHPExcel->getActiveSheet()->getStyle("A$startRow")->getFont()->setItalic( true );
        $objPHPExcel->getActiveSheet()->getStyle("A$startRow")->getFont()->setSize(14);

        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(6);
        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(33);
        $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(15);

        $startRow = 3;

        $objPHPExcel->getActiveSheet()->SetCellValue("B".$startRow, "Periode");
        $startRow++;
        $objPHPExcel->getActiveSheet()->SetCellValue("B".$startRow, "$start_date sampai dengan $end_date");


        $objPHPExcel->getActiveSheet()->getStyle("B3:D$startRow")->applyFromArray(
            array(
                'borders' => array(
                    'outline' => array(
                        'style' => PHPExcel_Style_Border::BORDER_THIN,
                        'color' => array('rgb' => 'DDDDDD')
                    )
                )
            )
        );

        $startRow+=2;

        $objPHPExcel->getActiveSheet()->SetCellValue("A".$startRow, "No");
        $objPHPExcel->getActiveSheet()->SetCellValue("B".$startRow, "Vendor");
        $objPHPExcel->getActiveSheet()->SetCellValue("C".$startRow, "Total Pesanan");


        $objPHPExcel->getActiveSheet()->getStyle("A$startRow:C$startRow")->getFill()->applyFromArray(array(
            'type' => PHPExcel_Style_Fill::FILL_SOLID,
            'startcolor' => array(
                'rgb' => 'C0BEBF'
            )
        ));

        $objPHPExcel->getActiveSheet()->getStyle("A$startRow:C$startRow")->getFont()->setBold( true );

        $startRow++;
        $data_start = $startRow;
        $no = 1;

        foreach($data as $row){
            $objPHPExcel->getActiveSheet()->SetCellValue("A".$startRow, $no);
            $objPHPExcel->getActiveSheet()->SetCellValue("B".$startRow, $row->nama_vendor);
            $objPHPExcel->getActiveSheet()->SetCellValue("C".$startRow, (int)$row->total_order);

            if($no % 2 == 0){
                $objPHPExcel->getActiveSheet()->getStyle("A$startRow:C$startRow")->getFill()->applyFromArray(array(
                    'type' => PHPExcel_Style_Fill::FILL_SOLID,
                    'startcolor' => array(
                        'rgb' => 'F3F3F3'
                    )
                ));
            }

            $startRow++;
            $no++;
        }

        $objPHPExcel->getActiveSheet()
            ->getStyle("C$data_start:C$startRow")
            ->getAlignment()
            ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

        $objPHPExcel->getActiveSheet()->getStyle("C$data_start:C$startRow")->getNumberFormat()->setFormatCode('#,##0');
        $objPHPExcel->getActiveSheet()->getStyle("B$data_start:B$startRow")->getAlignment()->setWrapText(true);
        $objPHPExcel->getActiveSheet()->getStyle("A$data_start:C$startRow")->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);

        $objPHPExcel->getActiveSheet()->setShowGridlines(false);


        $filename = "Laporan Purchase per Vendor ($start_date sampai $end_date)";
        header('Content-Type: application/vnd.ms-excel'); //mime type
        header('Content-Disposition: attachment;filename="'.$filename.'.xlsx"'); //tell browser what's the file name
        header('Cache-Control: max-age=0'); //no cache
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save('php://output');

    }

    function excel_product(){
        if($this->session->userdata('is_admin') != "1" && $this->session->userdata('is_admin') != "2"){
            echo "Unauthorized";
            return;
        }

        if(!isset($_GET['brand']) || !isset($_GET['stock_status'])){
            echo "Invalid data";
            return;
        }

        date_default_timezone_set('Asia/Singapore');

        include APPPATH.'third_party/PHPExcel/PHPExcel.php';

        $startRow = 1;
        $objPHPExcel = new PHPExcel();

        $brand = htmlentities($_GET['brand'], ENT_QUOTES);
        $stock_status = htmlentities($_GET['stock_status'], ENT_QUOTES);

        $data = $this->Main_model->get_product(null, 10000000000, 0, $brand, $stock_status)->result_object();

        $objPHPExcel->setActiveSheetIndex(0);

        $objPHPExcel->getActiveSheet()->SetCellValue("A".$startRow, "Laporan Stok Produk");
        $objPHPExcel->getActiveSheet()->getStyle("A$startRow")->getFont()->setBold( true );
        $objPHPExcel->getActiveSheet()->getStyle("A$startRow")->getFont()->setItalic( true );
        $objPHPExcel->getActiveSheet()->getStyle("A$startRow")->getFont()->setSize(14);

        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(6);
        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(33);
        $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(10);
        $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(13);
        $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(13);
        $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(13);

        $startRow = 3;

        $objPHPExcel->getActiveSheet()->SetCellValue("A".$startRow, "No");
        $objPHPExcel->getActiveSheet()->SetCellValue("B".$startRow, "Product");
        $objPHPExcel->getActiveSheet()->SetCellValue("C".$startRow, "Stok");
        $objPHPExcel->getActiveSheet()->SetCellValue("D".$startRow, "HP");
        $objPHPExcel->getActiveSheet()->SetCellValue("E".$startRow, "HR");
        $objPHPExcel->getActiveSheet()->SetCellValue("F".$startRow, "HJ");

        $objPHPExcel->getActiveSheet()->getStyle("A$startRow:F$startRow")->getFill()->applyFromArray(array(
            'type' => PHPExcel_Style_Fill::FILL_SOLID,
            'startcolor' => array(
                'rgb' => 'C0BEBF'
            )
        ));

        $objPHPExcel->getActiveSheet()->getStyle("A$startRow:F$startRow")->getFont()->setBold( true );

        $startRow++;
        $data_start = $startRow;
        $no = 1;

        foreach($data as $row){
            $objPHPExcel->getActiveSheet()->SetCellValue("A".$startRow, $no);
            $objPHPExcel->getActiveSheet()->SetCellValue("B".$startRow, $row->nama_product);
            $objPHPExcel->getActiveSheet()->SetCellValue("C".$startRow, (int)$row->STOK);
            $objPHPExcel->getActiveSheet()->SetCellValue("D".$startRow, (int)$row->HP_product);
            $objPHPExcel->getActiveSheet()->SetCellValue("E".$startRow, (int)$row->HR_product);
            $objPHPExcel->getActiveSheet()->SetCellValue("F".$startRow, (int)$row->HJ_product);

            if($no % 2 == 0){
                $objPHPExcel->getActiveSheet()->getStyle("A$startRow:F$startRow")->getFill()->applyFromArray(array(
                    'type' => PHPExcel_Style_Fill::FILL_SOLID,
                    'startcolor' => array(
                        'rgb' => 'F3F3F3'
                    )
                ));
            }

            $startRow++;
            $no++;
        }

        $objPHPExcel->getActiveSheet()
            ->getStyle("C1:F$startRow")
            ->getAlignment()
            ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);


        $objPHPExcel->getActiveSheet()->getStyle("D$data_start:F$startRow")->getNumberFormat()->setFormatCode('#,##0');
        $objPHPExcel->getActiveSheet()->getStyle("B$data_start:B$startRow")->getAlignment()->setWrapText(true);
        $objPHPExcel->getActiveSheet()->getStyle("A$data_start:F$startRow")->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);

        $objPHPExcel->getActiveSheet()->setShowGridlines(false);


        $filename = "Laporan Stok Produk (".date('d-m-Y H:i:s').")";
        header('Content-Type: application/vnd.ms-excel'); //mime type
        header('Content-Disposition: attachment;filename="'.$filename.'.xlsx"'); //tell browser what's the file name
        header('Cache-Control: max-age=0'); //no cache
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save('php://output');

    }

    private function get_tipe_order($tipe){
        if($tipe == "REK"){
            return "Rek";
        } else if($tipe == "TUNAI"){
            return "Tunai";
        } else if ($tipe == "FREE"){
            return "Free";
        } else if($tipe == "all"){
            return "Semua Pembayaran";
        }
    }

    private function get_brand($brand){
        if($brand == "KA"){
            return "Kedai Amarthya";
        } else if ($brand == "AF") {
            return "Amarthya Fashion";
        } else if ($brand == "AHF") {
            return "Amarthya Healthy Food";
        } else if ($brand == "AH") {
            return "Amarthya Herbal";
        } else if ($brand == "all"){
            return "Semua Brand";
        }
    }

    private function get_arus_kas($arus){
        if($arus == "all"){
            return "Semua Arus Kas";
        } else if ($arus == "pemasukan"){
            return "Pemasukan";
        } else if ($arus == "pengeluaran"){
            return "Pengeluaran";
        }
    }


}
?>