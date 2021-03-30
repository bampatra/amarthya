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

    function update_pick_up(){

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


        $this->db->limit($length,$start);


        $output['data'] = $this->Main_model->get_pick_up($search)->result_object();

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

        $this->load->view('template/admin_header');
        $this->load->view('pickup_form');
        $this->load->view('template/admin_footer');
    }

    // ================== HALF DONE ====================
    function add_pick_up(){

        // TODO: check if staff exists

        if(!isset($_REQUEST['id_staff'])){
            $return_arr = array("Status" => 'ERROR', "Message" => 'Staff tidak boleh kosong');
            echo json_encode($return_arr);
            return;
        }

        // TODO: check if order exists
        // TODO: check if vendor and order matches

        if(!isset($_REQUEST['id_order_vendor_m'])){
            $return_arr = array("Status" => 'ERROR', "Message" => 'Order tidak boleh kosong');
            echo json_encode($return_arr);
            return;
        }

        // TODO: check if the chosen order udah ada data pickup (all status except status_pick_up 2-dibatalkan)
        // TODO: check if the chosen order statusnya tidak 0-dibatalkan


        date_default_timezone_set('Asia/Singapore');

        $id_vendor = strtoupper(trim(htmlentities($_REQUEST['id_vendor'], ENT_QUOTES)));
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


        $this->db->limit($length,$start);

        if(!isset($_GET['pick_up'])){
            $total = $this->Main_model->get_order_vendor_m()->num_rows();
            $output['data'] = $this->Main_model->get_order_vendor_m($search)->result_object();
        } else {
            $total = $this->Main_model->get_order_vendor_m_pickup()->num_rows();
            $output['data'] = $this->Main_model->get_order_vendor_m_pickup($search)->result_object();
        }

        $output['recordsTotal'] = $output['recordsFiltered'] = $total;

        echo json_encode($output);
    }

    function update_order_vendor(){

        $no_order = strtoupper(trim(htmlentities($_REQUEST['no_order'], ENT_QUOTES)));
        $catatan_order_vendor = strtoupper(trim(htmlentities($_REQUEST['catatan_order_vendor'], ENT_QUOTES)));
        $tgl_order_vendor = strtoupper(trim(htmlentities($_REQUEST['tgl_order_vendor'], ENT_QUOTES)));
        $is_paid_vendor = strtoupper(trim(htmlentities($_REQUEST['is_paid_vendor'], ENT_QUOTES)));
        $payment_detail = strtoupper(trim(htmlentities($_REQUEST['payment_detail'], ENT_QUOTES)));


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

        }

        $data_m_update = compact('grand_total_order');

        if($this->Main_model->update_order_vendor_m($data_m_update, $id_order_vendor_m)){


            // =========== Extra Features ============

            // Add stok in out after order => add_stok_in_out


            // =======================================


            $this->db->trans_commit();
            $return_arr = array("Status" => 'OK', "Message" => $no_order_vendor);
        } else {
            $return_arr = array("Status" => 'ERROR', "Message" => 'Terjadi kesalahan sistem. Hubungi Admin (code: form_3)');
        }


        echo json_encode($return_arr);



    }

    function order_vendor_form()
    {

        $this->load->view('template/admin_header');
        $this->load->view('order_vendor_form');
        $this->load->view('template/admin_footer');
    }

    function update_delivery(){
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


        $this->db->limit($length,$start);


        $output['data'] = $this->Main_model->get_delivery($search)->result_object();

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

        $this->load->view('template/admin_header');
        $this->load->view('delivery_form');
        $this->load->view('template/admin_footer');
    }

    function order_list(){
        $this->load->view('template/admin_header');
        $this->load->view('order_list');
        $this->load->view('template/admin_footer');
    }

    // ================== HALF DONE ====================
    function add_delivery(){

        // TODO: check if staff exists

        if(!isset($_REQUEST['id_staff'])){
            $return_arr = array("Status" => 'ERROR', "Message" => 'Staff tidak boleh kosong');
            echo json_encode($return_arr);
            return;
        }

        // TODO: check if order exists
        // TODO: check if customer and order matches

        if(!isset($_REQUEST['id_order_m'])){
            $return_arr = array("Status" => 'ERROR', "Message" => 'Order tidak boleh kosong');
            echo json_encode($return_arr);
            return;
        }

        // TODO: check if the chosen order udah ada data delivery (all status except status_delivery 3-dibatalkan)
        // TODO: check if the chosen order statusnya tidak 0-dibatalkan


        date_default_timezone_set('Asia/Singapore');

        $id_customer = strtoupper(trim(htmlentities($_REQUEST['id_customer'], ENT_QUOTES)));
        $alamat_delivery = strtoupper(trim(htmlentities($_REQUEST['alamat_delivery'], ENT_QUOTES)));
        $no_hp_delivery = strtoupper(trim(htmlentities($_REQUEST['no_hp_delivery'], ENT_QUOTES)));
        $id_order_m = strtoupper(trim(htmlentities($_REQUEST['id_order_m'], ENT_QUOTES)));
        $tgl_delivery = strtoupper(trim(htmlentities($_REQUEST['tgl_delivery'], ENT_QUOTES)));
        $catatan_delivery = trim(htmlentities($_REQUEST['catatan_delivery'], ENT_QUOTES));
        $id_staff = strtoupper(trim(htmlentities($_REQUEST['id_staff'], ENT_QUOTES)));

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


        $this->db->limit($length,$start);


        if(!isset($_GET['delivery'])){
            $total = $this->Main_model->get_order_m()->num_rows();
            $output['data'] = $this->Main_model->get_order_m($search)->result_object();
        } else {
            $total = $this->Main_model->get_order_m_deliv()->num_rows();
            $output['data'] = $this->Main_model->get_order_m_deliv($search)->result_object();
        }

        $output['recordsTotal'] = $output['recordsFiltered'] = $total;

        echo json_encode($output);
    }

    function update_order_m(){

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
            $return_arr = array("Status" => 'OK', "Message" => '');
        } else {
            $this->db->trans_rollback();
            $return_arr = array("Status" => 'ERROR', "Message" => 'Gagal mengupdate data');
        }

        echo json_encode($return_arr);

    }

    function order_detail(){
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

        $this->load->view('template/admin_header');
        $this->load->view('order_form');
        $this->load->view('template/admin_footer');
    }

    // ================== HALF DONE ====================
    function add_order(){

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

        }


        // update price
        if(filter_var($is_ongkir_kas, FILTER_VALIDATE_BOOLEAN)){
            $grand_total_order = floatval($subtotal_order)  - floatval($diskon_order);
        } else {
            $grand_total_order = floatval($subtotal_order) + floatval($ongkir_order) - floatval($diskon_order);
        }

        $data_m_update = compact('subtotal_order', 'grand_total_order');

        if($this->Main_model->update_order_m($data_m_update, $id_order_m)){


            // =========== Extra Features ============

            // Add stok in out after sales => add_stok_in_out


            // =======================================





            $this->db->trans_commit();
            $return_arr = array("Status" => 'OK', "Message" => $no_order);
        } else {
            $return_arr = array("Status" => 'ERROR', "Message" => 'Terjadi kesalahan sistem. Hubungi Admin (code: form_3)');
        }


        echo json_encode($return_arr);

    }

    function stok_in_out(){
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
        $search = trim(htmlentities($_REQUEST['search']["value"], ENT_QUOTES));

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


        $this->db->limit($length,$start);

        $output['data'] = $this->Main_model->get_vendor($search)->result_object();

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


        $this->db->limit($length,$start);

        $output['data'] = $this->Main_model->get_customer($search)->result_object();

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
        $nama_customer = strtoupper(trim(htmlentities($_REQUEST['nama_customer'], ENT_QUOTES)));
        $alamat_customer = strtoupper(trim(htmlentities($_REQUEST['alamat_customer'], ENT_QUOTES)));
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
        $data['posisi_list'] = $this->Main_model->get_posisi()->result_object();

        $this->load->view('template/admin_header');
        $this->load->view('staff', $data);
        $this->load->view('template/admin_footer');
    }


    function get_staff(){
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

    function randStr($length = 10) {
        return substr(str_shuffle(str_repeat($x='ABCDEFGHJKMNPQRSTUVWXYZ', ceil($length/strlen($x)) )),1,$length);
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