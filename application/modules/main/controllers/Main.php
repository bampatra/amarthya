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

        $this->load->view('template/admin_header');
        $this->load->view('index');
        $this->load->view('template/admin_footer');
    }

    function slip_gaji(){

//        $id_staff = $this->session->userdata('id_staff');

        $this->load->view('template/admin_header');
        $this->load->view('slip_gaji');
        $this->load->view('template/admin_footer');

    }

    function delete_delivery(){
        if($this->session->userdata('is_admin') == "0"){
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
        if($this->session->userdata('is_admin') == "0"){
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

        if($this->session->userdata('is_admin') == "0"){
            redirect(base_url('main'));
        }

        $data['staffs'] = $this->Main_model->get_staff()->result_object();

        $this->load->view('template/admin_header');
        $this->load->view('salary_form', $data);
        $this->load->view('template/admin_footer');
    }

    function get_staff_salary(){

        if($this->session->userdata('is_admin') == "0"){
            redirect(base_url('main'));
        }

        date_default_timezone_set('Asia/Singapore');

        $id_staff = htmlentities($_REQUEST['id_staff'], ENT_QUOTES);
        $awal_akhir_salary = htmlentities($_REQUEST['awal_akhir_salary'], ENT_QUOTES);
        $bulan_salary = htmlentities($_REQUEST['bulan_salary'], ENT_QUOTES);
        $tahun_salary = htmlentities($_REQUEST['tahun_salary'], ENT_QUOTES);

        // Periode Delivery
        if($awal_akhir_salary == "AWAL"){

            $tgl_awal = $tahun_salary."-".sprintf('%02d', $bulan_salary)."-01";
            $tgl_akhir = $tahun_salary."-".sprintf('%02d', $bulan_salary)."-15";

        } else if($awal_akhir_salary == "AKHIR"){
            $tgl_awal = $tahun_salary."-".sprintf('%02d', $bulan_salary)."-16";
            $tgl_akhir = $tahun_salary."-".sprintf('%02d', $bulan_salary)."-".date('t');
        }

        $data = $this->Main_model->get_staff_salary($id_staff, $awal_akhir_salary, $bulan_salary, $tahun_salary, $tgl_awal, $tgl_akhir);
        echo json_encode($data->row());
        return;
    }

    function save_salary(){

        if($this->session->userdata('is_admin') == "0"){
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

        if($this->session->userdata('is_admin') == "0"){
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

        if($this->session->userdata('is_admin') == "0"){
            redirect(base_url('main'));
        }

        $this->load->view('template/admin_header');

        if(isset($_GET['id'])){

            $data_pick_up = $this->Main_model->get_pick_up_by_id(htmlentities($_GET['id'], ENT_QUOTES));

            if($data_pick_up->num_rows() == 0){

            } else {
                $data['pick_up'] = $data_pick_up->result_object();
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

        if($this->session->userdata('is_admin') == "0"){
            $output['data'] = $this->Main_model->get_pick_up($search, false, $this->session->userdata('id_staff'), $length, $start)->result_object();
        } else {
            $output['data'] = $this->Main_model->get_pick_up($search, true, 0, $length, $start)->result_object();
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
        if($this->session->userdata('is_admin') == "0"){
            redirect(base_url('main'));
        }

        $this->load->view('template/admin_header');
        $this->load->view('pickup_form');
        $this->load->view('template/admin_footer');
    }

    function add_pick_up(){

        if($this->session->userdata('is_admin') == "0"){
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

        $alamat_pick_up = strtoupper(trim(htmlentities($_REQUEST['alamat_pick_up'], ENT_QUOTES)));
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

        if($this->session->userdata('is_admin') == "0"){
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
        if($this->session->userdata('is_admin') == "0"){
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
        // server-side pagination
        $draw = $_REQUEST['draw'];
        $length = $_REQUEST['length'];
        $start = $_REQUEST['start'];
        $search = trim(htmlentities($_REQUEST['search']["value"], ENT_QUOTES));

        $output = array();
        $output['draw'] = $draw;

        $output['data']=array();

        if(!isset($_GET['pick_up'])){
            $total = $this->Main_model->get_order_vendor_m()->num_rows();
            $output['data'] = $this->Main_model->get_order_vendor_m($search, $length, $start)->result_object();
        } else {
            $total = $this->Main_model->get_order_vendor_m_pickup()->num_rows();
            $output['data'] = $this->Main_model->get_order_vendor_m_pickup($search, $length, $start)->result_object();
        }

        $output['recordsTotal'] = $output['recordsFiltered'] = $total;

        echo json_encode($output);
    }

    function update_order_vendor(){

        if($this->session->userdata('is_admin') == "0"){
            redirect(base_url('main'));
        }

        $no_order = strtoupper(trim(htmlentities($_REQUEST['no_order'], ENT_QUOTES)));
        $catatan_order_vendor = strtoupper(trim(htmlentities($_REQUEST['catatan_order_vendor'], ENT_QUOTES)));
        $tgl_order_vendor = strtoupper(trim(htmlentities($_REQUEST['tgl_order_vendor'], ENT_QUOTES)));
        $is_paid_vendor = strtoupper(trim(htmlentities($_REQUEST['is_paid_vendor'], ENT_QUOTES)));
        $payment_detail = trim(htmlentities($_REQUEST['payment_detail'], ENT_QUOTES));


        if(empty($tgl_order_vendor)){
            $return_arr = array("Status" => 'ERROR', "Message" => 'Tanggal order tidak boleh kosong');
            echo json_encode($return_arr);
            return;
        }

        $order_vendor_m_data = $this->Main_model->get_order_vendor_detail($no_order)->row();


        $is_paid_vendor = ($is_paid_vendor == true ? "1" : "0");

        // cant change if product is on delivery or delivered or deleted, but can change payment
        if($order_vendor_m_data->status_pick_up == '0' || $order_vendor_m_data->status_pick_up == '1' || $order_vendor_m_data->status_order_vendor == '0'){
            $updated_data = compact('is_paid_vendor', 'payment_detail');
        } else {
            $updated_data = compact('catatan_order_vendor', 'tgl_order_vendor', 'is_paid_vendor', 'payment_detail');
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

        if($this->session->userdata('is_admin') == "0"){
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

        $no_order_vendor = "V".date("Ymdhis").$this->randStr(2);
        $grand_total_order = 0;
        $status_order_vendor = '1';

        $is_paid_vendor = ($is_paid_vendor == true ? "1" : "0");
        $is_in_store = ($is_in_store == true ? "1" : "0");

        $data_m = compact('id_vendor', 'no_order_vendor', 'catatan_order_vendor', 'tgl_order_vendor',
                            'grand_total_order', 'status_order_vendor', 'is_paid_vendor', 'payment_detail', 'is_in_store');

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
        if($this->session->userdata('is_admin') == "0"){
            redirect(base_url('main'));
        }

        $this->load->view('template/admin_header');
        $this->load->view('order_vendor_form');
        $this->load->view('template/admin_footer');
    }

    function update_delivery(){

        if($this->session->userdata('is_admin') == "0"){
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

        if($this->session->userdata('is_admin') == "0"){
            redirect(base_url('main'));
        }

        $this->load->view('template/admin_header');

        if(isset($_GET['id'])){

            $data_delivery = $this->Main_model->get_delivery_by_id(htmlentities($_GET['id'], ENT_QUOTES));

            if($data_delivery->num_rows() == 0){

            } else {
                $data['delivery'] = $data_delivery->result_object();
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


        if($this->session->userdata('is_admin') == "0"){
            $output['data'] = $this->Main_model->get_delivery($search, false, $this->session->userdata('id_staff'), $length, $start)->result_object();
        } else {
            $output['data'] = $this->Main_model->get_delivery($search, true, 0, $length, $start)->result_object();
        }


        echo json_encode($output);
    }

    function update_delivery_status(){
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
        if($this->session->userdata('is_admin') == "0"){
            redirect(base_url('main'));
        }

        $this->load->view('template/admin_header');
        $this->load->view('delivery_form');
        $this->load->view('template/admin_footer');
    }

    function order_list(){

        if($this->session->userdata('is_admin') == "0"){
            redirect(base_url('main'));
        }

        $this->load->view('template/admin_header');
        $this->load->view('order_list');
        $this->load->view('template/admin_footer');
    }

    function add_delivery(){

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

        $alamat_delivery = strtoupper(trim(htmlentities($_REQUEST['alamat_delivery'], ENT_QUOTES)));
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
        // server-side pagination
        $draw = $_REQUEST['draw'];
        $length = $_REQUEST['length'];
        $start = $_REQUEST['start'];
        $search = trim(htmlentities($_REQUEST['search']["value"], ENT_QUOTES));

        $output = array();
        $output['draw'] = $draw;
        $output['data']=array();



        if(!isset($_GET['delivery'])){
            $total = $this->Main_model->get_order_m()->num_rows();
            $output['data'] = $this->Main_model->get_order_m($search, $length, $start)->result_object();
        } else {
            $total = $this->Main_model->get_order_m_deliv()->num_rows();
            $output['data'] = $this->Main_model->get_order_m_deliv($search, $length, $start)->result_object();
        }

        $output['recordsTotal'] = $output['recordsFiltered'] = $total;

        echo json_encode($output);
    }

    function update_order_m(){

        if($this->session->userdata('is_admin') == "0"){
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

        // ERROR    : if not tentative but no date
        // OK       : if tentative but no date
        if(!$is_tentative && empty($tgl_order)){
            $return_arr = array("Status" => 'ERROR', "Message" => 'Tanggal order tidak boleh kosong');
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
            $data = compact('is_paid', 'payment_detail');
        } else {
            $data = compact('catatan_order', 'tgl_order', 'is_tentative', 'ongkir_order', 'is_ongkir_kas',
                'diskon_order', 'is_paid', 'payment_detail', 'grand_total_order');
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

        if($this->session->userdata('is_admin') == "0"){
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
        if($this->session->userdata('is_admin') == "0"){
            redirect(base_url('main'));
        }

        $this->load->view('template/admin_header');
        $this->load->view('order_form');
        $this->load->view('template/admin_footer');
    }

    function add_order(){

        if($this->session->userdata('is_admin') == "0"){
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
                            'is_paid', 'payment_detail', 'is_in_store', 'is_tentative', 'is_changeable');

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

        if($this->session->userdata('is_admin') == "0"){
            echo "Unauthorized";
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
        $id = htmlentities($_REQUEST['id_stok_in_out'], ENT_QUOTES);
        $data = $this->Main_model->get_stok_in_out_by_id($id);
        echo json_encode($data->row());
        return;
    }

    function add_stok_in_out(){

        if($this->session->userdata('is_admin') == "0"){
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

    function product(){

        if($this->session->userdata('is_admin') == "0"){
            redirect(base_url('main'));
        }

        $this->load->view('template/admin_header');
        $this->load->view('product');
        $this->load->view('template/admin_footer');
    }

    function get_product(){

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


        $output['data'] = $this->Main_model->get_product($search, $length, $start)->result_object();

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

        if($this->session->userdata('is_admin') == "0"){
            redirect(base_url('main'));
        }

        $id_product = strtoupper(trim(htmlentities($_REQUEST['id_product'], ENT_QUOTES)));
        $nama_product = trim(htmlentities($_REQUEST['nama_product'], ENT_QUOTES));
        $SKU_product = strtoupper(trim(htmlentities($_REQUEST['SKU_product'], ENT_QUOTES)));
        $satuan_product = trim(htmlentities($_REQUEST['satuan_product'], ENT_QUOTES));
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
        $this->load->view('template/admin_header');
        $this->load->view('customer');
        $this->load->view('template/admin_footer');
    }

    function get_customer(){

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

        if($this->session->userdata('is_admin') == "0"){
            redirect(base_url('main'));
        }

        $data['posisi_list'] = $this->Main_model->get_posisi()->result_object();

        $this->load->view('template/admin_header');
        $this->load->view('staff', $data);
        $this->load->view('template/admin_footer');
    }

    function get_staff(){

        if($this->session->userdata('is_admin') == "0"){
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

        if($this->session->userdata('is_admin') == "0"){
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
                $pdf->Image(base_url('assets/images/logopdf.jpg'), 10, 10, 48, 22 ,'');
                $x = $pdf->GetX();

                $pdf->Cell(59 ,5,'INVOICE',0,1, 'R');//end of line

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

                $pdf->Cell(130 ,$payment_detail_height,'BCA 0490409181 An. A. A.A. Arina Saraswati Hardy',0,0);
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
                $pdf->Image(base_url('assets/images/logopdf.jpg'), 10, 10, 48, 22 ,'');
                $x = $pdf->GetX();

                $pdf->Cell(59 ,5,'TANDA TERIMA',0,1, 'R');//end of line

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

            // Periode Delivery
            if($awal_akhir_salary == "AWAL"){
                $tgl_awal = $tahun_salary."-".sprintf('%02d', $bulan_salary)."-01";
                $tgl_akhir = $tahun_salary."-".sprintf('%02d', $bulan_salary)."-15";

                $start = "01-".sprintf('%02d', $bulan_salary)."-".$tahun_salary;
                $end = "15-".sprintf('%02d', $bulan_salary)."-".$tahun_salary;

            } else if($awal_akhir_salary == "AKHIR"){
                $tgl_awal = $tahun_salary."-".sprintf('%02d', $bulan_salary)."-16";
                $tgl_akhir = $tahun_salary."-".sprintf('%02d', $bulan_salary)."-".date('t');

                $start = "16-".sprintf('%02d', $bulan_salary)."-".$tahun_salary;
                $end = date('t').sprintf('%02d', $bulan_salary)."-".$tahun_salary;
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
        $final_filename = time()."Vendor";
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

        }
    }





}
?>