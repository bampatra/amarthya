<?php
class Main_model extends CI_Model
{

    function get_HP_food(){
        $sql = "SELECT a.id_menu, d.nama_menu, SUM(a.qty_bahan * b.HP_product) AS harga_bahan
                FROM menu_bahan_eatery a
                INNER JOIN menu_eatery d ON a.id_menu = d.id_menu
                INNER JOIN product b ON a.id_product = b.id_product
                WHERE (d.kategori_menu <> '14' && d.kategori_menu <> '16')
                GROUP BY a.id_menu";

        $query = $this->db->query($sql);
        return $query;
    }

    function get_menu_price($id_menu){
        $sql = "SELECT HJ_menu, HJ_online_menu
                FROM menu_eatery
                WHERE id_menu = '$id_menu'";

        $query = $this->db->query($sql);
        return $query;

    }

    function get_order_eatery_m($search = null, $status = "all", $payment = "all", $length = 10000000000, $start = 0){
        $sql = "SELECT * 
                FROM order_eatery_m a
                INNER JOIN metode_pembayaran b ON a.metode_pembayaran = b.html_id
                WHERE a.void = '0'
                    AND (a.is_paid = '{$status}' || 'all' = '{$status}')
                    AND (a.metode_pembayaran = '{$payment}' || 'all' = '{$payment}')";

        if($search != "" || $search != null){
            $sql .= " AND CONCAT(a.no_order_eatery) LIKE '%$search%'";
        }

        $sql .= " ORDER BY a.tgl_order DESC LIMIT {$start}, {$length}";

        $query = $this->db->query($sql);
        return $query;
    }

    function get_order_eatery_detail($no_order_eatery){
        $sql ="SELECT a.*, b.*, c.nama_menu
                FROM order_eatery_m a
                INNER JOIN order_eatery_s b ON a.id_order_eatery_m = b.id_order_eatery_m
                INNER JOIN menu_eatery c ON b.id_menu = c.id_menu
                WHERE a.no_order_eatery = '$no_order_eatery'";

        $query = $this->db->query($sql);
        return $query;
    }

    function get_order_eatery_by_no_order($no_order_eatery){
        $sql = "SELECT * FROM order_eatery_m
                WHERE no_order_eatery = '$no_order_eatery' AND void = '0'";

        $query = $this->db->query($sql);
        return $query;
    }

    function add_order_eatery_m($data){
        $input_data = array(
            'no_order_eatery' => $data['no_order_eatery'],
            'jenis_transaksi' => $data['jenis_transaksi'],
            'catatan_informasi' => $data['catatan_informasi'],
            'catatan_order' => $data['catatan_order'],
            'subtotal_order' => $data['subtotal_order'],
            'ongkir_order' => $data['ongkir_order'],
            'is_ongkir_kas' => $data['is_ongkir_kas'],
            'promosi' => $data['promosi'],
            'nominal_promosi' => $data['nominal_promosi'],
            'persen_promosi' => $data['persen_promosi'],
            'metode_pembayaran' => $data['metode_pembayaran'],
            'nominal_bayar' => $data['nominal_bayar'],
            'kembalian_bayar' => $data['kembalian_bayar'],
            'jenis_kartu' => $data['jenis_kartu'],
            'no_kartu' => $data['no_kartu'],
            'approval_kartu' => $data['approval_kartu'],
            'platform_QRIS' => $data['platform_QRIS'],
            'no_QRIS' => $data['no_QRIS'],
            'approval_QRIS' => $data['approval_QRIS'],
            'tax_order' => $data['tax_order'],
            'service_order' => $data['service_order'],
            'grand_total_order' => $data['grand_total_order'],
            'staff_order' => $data['staff_order'],
            'input_username' => $data['input_username'],
            'tgl_order' => $data['tgl_order'],
            'is_paid' => $data['is_paid'],
            'void' => $data['void'],
            'tipe_order' => $data['tipe_order']

        );

        $this->db->insert('order_eatery_m',$input_data);
        $insert_id = $this->db->insert_id();
        return $insert_id;
    }

    function add_order_eatery_s($data){
        $input_data = array(
            'id_order_eatery_m' => $data['id_order_eatery_m'],
            'id_menu' => $data['id_menu'],
            'HJ_menu' => $data['HJ_menu'],
            'qty_menu' => $data['qty_menu'],
            'is_free' => $data['is_free'],
            'total_order' => $data['total_order']
        );

        $this->db->insert('order_eatery_s',$input_data);
        $insert_id = $this->db->insert_id();
        return $insert_id;
    }

    function update_order_eatery_m($updated_data, $id_order_eatery_m){
        $this->db->where('id_order_eatery_m', $id_order_eatery_m);
        return $this->db->update('order_eatery_m',$updated_data);
    }

    function get_metode_pembayaran(){
        $sql = "SELECT * FROM metode_pembayaran";
        $query = $this->db->query($sql);
        return $query;
    }


    function get_brand_database($order_active = false){
        $sql = "SELECT * FROM brand";

        if($order_active){
            $sql .= " WHERE order_active = '1'";
        }

        $query = $this->db->query($sql);
        return $query;

    }

    function get_bahan_by_menu($id_menu){
        $sql = "SELECT a.id_menu_bahan_eatery, a.id_menu, a.qty_bahan, a.id_product, b.nama_product, b.satuan_product, b.HP_product	
                FROM menu_bahan_eatery a
                INNER JOIN product b ON a.id_product = b.id_product
                WHERE a.id_menu = '{$id_menu}'";

        $query = $this->db->query($sql);
        return $query;

    }

    function get_menu_eatery($kategori = "all", $search = null, $length = 10000000000, $start = 0){
        $sql = "SELECT a.*, b.nama_kategori, c.harga_bahan as HP_menu
                FROM menu_eatery a
                INNER JOIN kategori_eatery b ON a.kategori_menu = b.id_kategori_eatery
                INNER JOIN (
                    SELECT a.id_menu, a.qty_bahan, SUM(a.qty_bahan * b.HP_product) AS harga_bahan
                    FROM menu_bahan_eatery a
                    INNER JOIN product b ON a.id_product = b.id_product
                    GROUP BY a.id_menu
                )c ON a.id_menu = c.id_menu
                WHERE (a.kategori_menu = '{$kategori}' || 'all' = '{$kategori}')
                    AND a.active_menu = '1'";

        if($search != "" || $search != null){
            $sql .= " AND CONCAT(a.nama_menu, ' ', a.deskripsi_menu) LIKE '%$search%'";
        }

        $sql .= " ORDER BY a.nama_menu LIMIT {$start}, {$length}";

        $query = $this->db->query($sql);
        return $query;
    }


    function get_menu_by_id($id_menu){
        $sql = "SELECT * FROM menu_eatery
                WHERE id_menu = '{$id_menu}'";

        $query = $this->db->query($sql);
        return $query;

    }

    function add_menu_eatery($data){
        $input_data = array(
            'nama_menu' => $data['nama_menu'],
            'kategori_menu' => $data['kategori_menu'],
            'deskripsi_menu' => $data['deskripsi_menu'],
            'HJ_menu' => $data['HJ_menu'],
            'HJ_online_menu' => $data['HJ_online_menu'],
            'active_menu' => $data['active_menu']
        );

        $this->db->insert('menu_eatery',$input_data);
        $insert_id = $this->db->insert_id();
        return $insert_id;
    }

    function update_menu_eatery($updated_data, $id_menu){
        $this->db->where('id_menu', $id_menu);
        return $this->db->update('menu_eatery',$updated_data);
    }

    function delete_menu_eatery($id_menu){
        $sql = "DELETE FROM menu_eatery
                WHERE id_menu = '{$id_menu}'";

        $query = $this->db->query($sql);
        return $query;
    }

    function add_menu_bahan_eatery($data){
        $input_data = array(
            'id_menu' => $data['id_menu'],
            'id_product' => $data['id_product'],
            'qty_bahan' => $data['qty_bahan']
        );

        $this->db->insert('menu_bahan_eatery',$input_data);
        $insert_id = $this->db->insert_id();
        return $insert_id;
    }

    function update_menu_bahan_eatery($updated_data, $where_array){
        $this->db->where($where_array);
        return $this->db->update('menu_bahan_eatery',$updated_data);
    }

    function delete_menu_bahan_eatery($id_menu, $ids_product){
        $sql = "DELETE FROM menu_bahan_eatery
                WHERE id_menu = '{$id_menu}'
                    AND id_product NOT IN ('".$ids_product."')";

        $query = $this->db->query($sql);
        return $query;
    }

    function get_kategori_eatery(){
        $sql ="SELECT *
               FROM kategori_eatery a";

        $query = $this->db->query($sql);
        return $query;
    }

    function get_izin($id_staff = 'all', $status = "all", $search = null, $length = 10000000000, $start = 0){
        $sql = "SELECT a.*, b.nama_staff, b.id_staff,
                    DATE_FORMAT(a.tgl_start_izin, '%Y-%m-%dT%H:%i:%s') AS custom_tgl_start,
                    DATE_FORMAT(a.tgl_end_izin, '%Y-%m-%dT%H:%i:%s') AS custom_tgl_end
                FROM izin a
                INNER JOIN staff b ON a.id_staff = b.id_staff
                WHERE (a.id_staff = '{$id_staff}' || 'all' = '{$id_staff}')
                    AND (a.status_izin = '{$status}' || 'all' = '{$status}')
                    AND a.status_izin <> '3'";

        if($search != "" || $search != null){
            $sql .= " AND CONCAT(b.nama_staff, ' ', a.alasan_izin, ' ', a.tgl_start_izin, ' ', a.tgl_end_izin) LIKE '%$search%'";
        }

        $sql .= " ORDER BY a.tgl_start_izin DESC LIMIT {$start}, {$length}";

        $query = $this->db->query($sql);
        return $query;
    }

    function add_izin($data){
        $input_data = array(
            'id_staff' => $data['id_staff'],
            'tgl_start_izin' => $data['tgl_start_izin'],
            'tgl_end_izin' => $data['tgl_end_izin'],
            'alasan_izin' => $data['alasan_izin'],
            'keterangan_manager' => $data['keterangan_manager'],
            'id_staff_approval' => $data['id_staff_approval'],
            'status_izin' => $data['status_izin']
        );

        $this->db->insert('izin',$input_data);
        $insert_id = $this->db->insert_id();
        return $insert_id;
    }

    function update_izin($updated_data, $id_izin){
        $this->db->where('id_izin', $id_izin);
        return $this->db->update('izin',$updated_data);
    }

    function get_izin_by_id($id_izin){
        $sql ="SELECT *
               FROM izin a
               WHERE a.id_izin = '{$id_izin}'";

        $query = $this->db->query($sql);
        return $query;
    }

    function periode_ongkir_per_staff($tgl_awal, $tgl_akhir){
        $sql = "SELECT b.nama_staff, IFNULL(c.ongkir_salary, 0) as ongkir_salary
                FROM staff b
                LEFT JOIN (
                    SELECT SUM(b.ongkir_order) as ongkir_salary, a.id_staff
                    FROM delivery a
                    INNER JOIN order_m b ON a.id_order_m = b.id_order_m
                    WHERE (a.tgl_delivery BETWEEN '".$tgl_awal."' AND '".$tgl_akhir."')
                    GROUP BY a.id_staff
                ) c ON b.id_staff = c.id_staff
                WHERE b.nama_staff <> 'TEST USER'
                ORDER BY ongkir_salary DESC";

        $query = $this->db->query($sql);
        return $query;
    }

	function dashboard_data($today, $month){

        $datetime = new DateTime($today);
        $datetime->modify('+1 day');
        $end_date = $datetime->format('Y-m-d H:i:s');


		$sql = "SELECT 'daily_sales' AS title, t1.tgl_order AS detail, SUM(IFNULL(t2.data,0) + IFNULL(t3.data,0)) as data
				FROM (
					SELECT '{$today}' AS tgl_order
				)t1
				LEFT JOIN (
					SELECT id_order_m, tgl_order, SUM(subtotal_order - diskon_order) as data
					FROM order_m
					WHERE status_order = '1'
						AND is_paid = '1'
						AND tgl_order = '{$today}'
					GROUP BY tgl_order
				)t2 ON t1.tgl_order = t2.tgl_order
                LEFT JOIN (
                    SELECT id_order_eatery_m, DATE(tgl_order) AS tgl_order, SUM(subtotal_order - nominal_promosi + tax_order + service_order) as data
                    FROM order_eatery_m
                    WHERE void = '0'
                        AND is_paid = '1'
                        AND DATE(tgl_order) = '{$today}'
                        GROUP BY DATE(tgl_order)
                )t3 ON t1.tgl_order = t3.tgl_order
				UNION
				/* penjualan bulanan yang sudah dibayar, tidak termasuk ongkir */
				SELECT 'monthly_sales' AS title, t2.month as detail, SUM(IFNULL(t2.data,0) + IFNULL(t3.data,0)) as data
				FROM (
					SELECT '{$month}' AS month
				)t1
				LEFT JOIN (
					SELECT id_order_m, MONTH(tgl_order) as month, SUM(subtotal_order - diskon_order) as data
					FROM order_m
					WHERE status_order = '1'
						AND is_paid = '1'
						AND MONTH(tgl_order) = '{$month}'
					GROUP BY MONTH(tgl_order)
				)t2 ON t1.month = t2.month
                LEFT JOIN (
                    SELECT id_order_eatery_m, MONTH(tgl_order) as month, SUM(subtotal_order - nominal_promosi + tax_order + service_order) as data
                    FROM order_eatery_m
                    WHERE void = '0'
                        AND is_paid = '1'
                        AND MONTH(tgl_order) = '{$month}'
                        GROUP BY MONTH(tgl_order)
                )t3 ON t1.month = t3.month
				UNION
				/*Delivery belum dikirim*/
				SELECT 'delivery_to_do' AS title, '' AS detail, COUNT(*) as data
				FROM delivery
				WHERE status_delivery = '0'
				UNION
				/*Pick Up belum diambil*/
				SELECT 'pick_up_to_do' AS title, '' AS detail, COUNT(*) as data
				FROM pick_up
				WHERE status_pick_up = '0'
				UNION
				/*Pesanan belum dibayar*/
				SELECT 'unpaid_order' AS title, '' AS detail, COUNT(*) as data
				FROM order_m
				WHERE status_order = '1'
					AND is_paid = '0'
				UNION
				/*Vendor belum dibayar*/
				SELECT 'unpaid_order_vendor' AS title, '' AS detail, COUNT(*) as data
				FROM order_vendor_m
				WHERE status_order_vendor = '1'
					AND is_paid_vendor = '0'";

		$query = $this->db->query($sql);
        return $query;
	}

    function get_top_10_product_per_customer($id_customer){
        $sql = "SELECT d.id_customer, d.nama_customer, b.id_product, b.nama_product , SUM(a.qty_order) as total_qty_order
                FROM order_s a
                INNER JOIN product b ON a.id_product = b.id_product
                INNER JOIN order_m c ON a.id_order_m = c.id_order_m
                INNER JOIN customer d ON c.id_customer = d.id_customer
                WHERE d.id_customer = '{$id_customer}'
                    AND c.status_order = '1'
                GROUP BY d.id_customer, b.id_product
                ORDER BY SUM(a.qty_order) DESC
                LIMIT 0, 10";

        $query = $this->db->query($sql);
        return $query;
    }

    function get_top_10_product_per_vendor($id_vendor){
        $sql = "SELECT d.id_vendor, d.nama_vendor, b.id_product, b.nama_product , SUM(a.qty_order_vendor) as total_qty_order
                FROM order_vendor_s a
                INNER JOIN product b ON a.id_product = b.id_product
                INNER JOIN order_vendor_m c ON a.id_order_vendor_m = c.id_order_vendor_m
                INNER JOIN vendor d ON c.id_vendor = d.id_vendor
                WHERE d.id_vendor = '{$id_vendor}'
                    AND status_order_vendor = '1'
                GROUP BY d.id_vendor, b.id_product
                ORDER BY SUM(a.qty_order_vendor) DESC
                LIMIT 0, 10";

        $query = $this->db->query($sql);
        return $query;
    }

    function laporan_pick_up_per_staff($id_staff, $start_date, $end_date, $search = null, $length = 10000000000, $start = 0){
        $sql = "SELECT b.nama_vendor, c.no_order_vendor, c.grand_total_order, c.tgl_order_vendor, a.*
                FROM pick_up a
                INNER JOIN order_vendor_m c ON a.id_order_vendor_m = c.id_order_vendor_m
                INNER JOIN vendor b ON a.id_vendor = b.id_vendor
                WHERE a.id_staff = '{$id_staff}'
                    AND (tgl_pick_up BETWEEN '{$start_date}' AND '{$end_date}')";

        if($search != "" || $search != null){
            $sql .= " AND CONCAT(b.nama_vendor, ' ', c.no_order_vendor, ' ', a.alamat_pick_up) LIKE '%$search%'";
        }

        $sql .= " ORDER BY a.timestamp_pick_up DESC LIMIT {$start}, {$length}";

        $query = $this->db->query($sql);
        return $query;
    }

    function laporan_delivery_per_staff($id_staff, $start_date, $end_date, $search = null, $length = 10000000000, $start = 0){
        $sql = "SELECT b.nama_customer, c.no_order, c.grand_total_order, c.ongkir_order, c.tgl_order, a.*
                FROM delivery a
                INNER JOIN order_m c ON a.id_order_m = c.id_order_m
                INNER JOIN customer b ON a.id_customer = b.id_customer
                WHERE a.id_staff = '{$id_staff}'
                    AND (tgl_delivery BETWEEN '{$start_date}' AND '{$end_date}')";

        if($search != "" || $search != null){
            $sql .= " AND CONCAT(b.nama_customer, ' ', c.no_order, ' ', a.alamat_delivery) LIKE '%$search%'";
        }

        $sql .= " ORDER BY a.timestamp_delivery DESC LIMIT {$start}, {$length}";

        $query = $this->db->query($sql);
        return $query;
    }

    function add_jurnal_umum($data){
        $input_data = array(
            'tgl_jurnal_umum' => $data['tgl_jurnal_umum'],
            'keterangan_jurnal_umum' => $data['keterangan_jurnal_umum'],
            'debet_jurnal_umum' => $data['debet_jurnal_umum'],
            'kredit_jurnal_umum' => $data['kredit_jurnal_umum'],
            'tipe_jurnal_umum' => $data['tipe_jurnal_umum'],
            'brand_jurnal_umum' => $data['brand_jurnal_umum']
        );

        $this->db->insert('jurnal_umum',$input_data);
        $insert_id = $this->db->insert_id();
        return $insert_id;
    }

    function update_jurnal_umum($updated_data, $id_jurnal_umum){
        $this->db->where('id_jurnal_umum', $id_jurnal_umum);
        return $this->db->update('jurnal_umum',$updated_data);
    }

    function delete_jurnal_umum($id_jurnal_umum){
        $sql = "DELETE FROM jurnal_umum
                WHERE id_jurnal_umum = '{$id_jurnal_umum}'";

        $query = $this->db->query($sql);
        return $query;
    }

    function get_jurnal_umum_by_id($id_jurnal_umum){
        $sql ="SELECT *
               FROM jurnal_umum a
               WHERE a.id_jurnal_umum = '{$id_jurnal_umum}'";

        $query = $this->db->query($sql);
        return $query;
    }

    function add_problem_solving($data){
        $input_data = array(
            'kode_problem_solving' => $data['kode_problem_solving'],
            'no_order_problem_solving' => $data['no_order_problem_solving'],
            'topik_problem_solving' => $data['topik_problem_solving'],
            'detail_problem_solving' => $data['detail_problem_solving'],
            'solusi_problem_solving' => $data['solusi_problem_solving'],
            'timestamp_create' => $data['timestamp_create'],
            'timestamp_solusi' => $data['timestamp_solusi'],
            'username_create' => $data['username_create'],
            'username_solusi' => $data['username_solusi'],
            'active_problem_solving' => $data['active_problem_solving']
        );

        $this->db->insert('problem_solving',$input_data);
        $insert_id = $this->db->insert_id();
        return $insert_id;
    }

    function update_problem_solving($updated_data, $id_problem_solving){
        $this->db->where('id_problem_solving', $id_problem_solving);
        return $this->db->update('problem_solving',$updated_data);
    }


    function get_problem_by_id($id_problem_solving){
        $sql ="SELECT *
               FROM problem_solving a
               WHERE a.id_problem_solving = '{$id_problem_solving}' 
                    AND a.active_problem_solving = '1'";

        $query = $this->db->query($sql);
        return $query;
    }

    function get_problem_solving($status = "all", $search = null, $length = 10000000000, $start = 0){
        $sql = "SELECT *
                FROM problem_solving a
                WHERE a.active_problem_solving = '1'";

        if($status == "all"){
            $sql .= " AND 1 = 1";
        } else if ($status == "unsolved") {
            $sql .= " AND (a.solusi_problem_solving = '' || a.solusi_problem_solving IS NULL)";
        } else if ($status == "solved") {
            $sql .= " AND (a.solusi_problem_solving != '' && a.solusi_problem_solving IS NOT NULL)";
        }

        if($search != "" || $search != null){
            $sql .= " AND CONCAT(a.no_order_problem_solving, ' ', a.detail_problem_solving, ' ', a.kode_problem_solving, ' ', a.topik_problem_solving) LIKE '%$search%'";
        }

        $sql .= " ORDER BY a.id_problem_solving DESC LIMIT {$start}, {$length}";

        $query = $this->db->query($sql);
        return $query;
    }


    function laporan_purchase($start_date, $end_date, $search = '', $length = 10000000000, $start = 0){
        $sql = "SELECT a.id_vendor,a.nama_vendor, IFNULL(b.total_order,0) as total_order
                FROM vendor a
                LEFT JOIN (
                            SELECT id_vendor, SUM(grand_total_order) as total_order
                    FROM order_vendor_m
                    WHERE status_order_vendor = '1' AND is_paid_vendor = '1' 
                      AND (tgl_order_vendor BETWEEN '{$start_date}' AND '{$end_date}') 
                    GROUP BY id_vendor
                )b ON a.id_vendor = b.id_vendor
                WHERE a.nama_vendor NOT LIKE '%TEST%'";

        if($search != "" || $search != null){
            $sql .= " AND a.nama_vendor LIKE '%{$search}%'";
        }

        $sql.= " ORDER BY a.nama_vendor LIMIT {$start}, {$length}";

        $query = $this->db->query($sql);
        return $query;
    }

    function laporan_sales($start_date, $end_date, $search = '', $length = 10000000000, $start = 0){
        $sql = "SELECT a.id_customer, a.nama_customer, IFNULL(b.total_order, 0) as total_order, IFNULL(b.ongkir_order, 0) as ongkir_order, IFNULL(b.total_order, 0) - IFNULL(b.ongkir_order, 0) AS total_belanja
                FROM customer a
                LEFT JOIN (
                            SELECT a.id_customer, SUM(a.grand_total_order) as total_order, b.ongkir_order
                    FROM order_m a
                    LEFT JOIN (
                            SELECT id_customer, SUM(ongkir_order) as ongkir_order
                        FROM order_m
                        WHERE status_order = '1' AND is_paid = '1' AND is_ongkir_kas = '0'
                        AND (tgl_order BETWEEN '{$start_date}' AND '{$end_date}') 
                        GROUP BY id_customer
                    )b ON a.id_customer = b.id_customer
                    WHERE a.status_order = '1' AND a.is_paid = '1'
                        AND (a.tgl_order BETWEEN '{$start_date}' AND '{$end_date}') 
                    GROUP BY a.id_customer
                )b ON a.id_customer = b.id_customer
                WHERE a.nama_customer NOT LIKE '%TEST%'";

        if($search != "" || $search != null){
            $sql .= " AND a.nama_customer LIKE '%{$search}%'";
        }

        $sql.= " ORDER BY a.nama_customer LIMIT {$start}, {$length}";

        $query = $this->db->query($sql);
        return $query;
    }

    function laporan_produk($start_date, $end_date, $search = '', $length = 10000000000, $start = 0){
        $sql = "SELECT a.id_product, a.nama_product, a.brand_product,
                    CASE (b.stok_out MOD 1 > 0)
                        WHEN TRUE THEN ROUND(b.stok_out, 2)
                        ELSE IFNULL(b.stok_out, 0)
                    END AS stok_out, 
                    CASE (e.stok_in MOD 1 > 0)
                        WHEN TRUE THEN ROUND(e.stok_in, 2)
                        ELSE IFNULL(e.stok_in, 0)
                    END AS stok_in, 
                    IFNULL(c.total_sales, 0) AS total_sales,
                    IFNULL(d.total_purchase, 0) AS total_purchase,
                    a.satuan_product
                FROM product a
                LEFT JOIN (
                    SELECT id_product, SUM(stok_in_out) AS stok_out
                    FROM stok_in_out 
                    WHERE tipe_in_out = 'OUT'
                        AND (tgl_out BETWEEN '{$start_date}' AND '{$end_date}') 
                    GROUP BY id_product
                )b ON a.id_product = b.id_product
                LEFT JOIN (
                    SELECT a.id_product, SUM(a.total_order) AS total_sales
                    FROM order_s a
                    INNER JOIN order_m b ON a.id_order_m = b.id_order_m
                    WHERE b.status_order = '1'
                         AND (b.tgl_order BETWEEN '{$start_date}' AND '{$end_date}') 
                    GROUP BY a.id_product
                )c ON c.id_product = a.id_product
                LEFT JOIN (
                    SELECT a.id_product, SUM(a.total_order_vendor) AS total_purchase
                    FROM order_vendor_s a
                    INNER JOIN order_vendor_m b ON a.id_order_vendor_m = b.id_order_vendor_m
                    WHERE b.status_order_vendor = '1'
                        AND (b.tgl_order_vendor BETWEEN '{$start_date}' AND '{$end_date}') 
                    GROUP BY a.id_product
                )d ON d.id_product = a.id_product
                LEFT JOIN (
                    SELECT id_product, SUM(stok_in_out) AS stok_in
                    FROM stok_in_out 
                    WHERE tipe_in_out = 'IN'
                        AND (tgl_in BETWEEN '{$start_date}' AND '{$end_date}') 
                    GROUP BY id_product
                )e ON e.id_product = a.id_product
                WHERE a.nama_product NOT LIKE '%TEST%' AND a.brand_product <> 'BAHAN'";

        if($search != "" || $search != null){
            $sql .= " AND a.nama_product LIKE '%{$search}%'";
        }

        $sql.= " ORDER BY a.nama_product LIMIT {$start}, {$length}";

        $query = $this->db->query($sql);
        return $query;
    }

    function laporan_menu($start_date, $end_date, $search = '', $length = 10000000000, $start = 0){
        $sql = "SELECT a.id_menu, a.nama_menu,
                        IFNULL(b.total_qty, 0) AS total_qty,
                        IFNULL(b.total_order, 0) AS total_order
                FROM menu_eatery a
                LEFT JOIN (
                    SELECT a.id_menu, SUM(a.qty_menu) AS total_qty, SUM(a.total_order) AS total_order
                    FROM order_eatery_s a
                    INNER JOIN order_eatery_m b ON a.id_order_eatery_m = b.id_order_eatery_m
                    WHERE b.void <> '1'
                        AND (b.tgl_order BETWEEN '{$start_date}' AND '{$end_date}') 
                    GROUP BY a.id_menu
                )b ON a.id_menu = b.id_menu";

        if($search != "" || $search != null){
            $sql .= " AND a.nama_menu LIKE '%{$search}%'";
        }

        $sql.= " ORDER BY a.nama_menu LIMIT {$start}, {$length}";

        $query = $this->db->query($sql);
        return $query;
    }

    function jurnal_umum_gabung($start_date, $end_date, $brand_order, $tipe_order, $cash_flow, $excel, $length = 10000000000, $start = 0){

            $datetime = new DateTime($end_date);
            $datetime->modify('+1 day');
            $new_end_date = $datetime->format('Y-m-d H:i:s');

        $sql = "SELECT *, DATE_FORMAT(a.tgl_order, '%Y-%m-%d') AS custom_tgl
                FROM (
                    SELECT a.*, (@mutasi := @mutasi + IF(a.DEBET <> 0, a.DEBET, (-1 * a.KREDIT))) AS MUTASI, (@count := @count + 1) AS COUNT
                    FROM (
                        SELECT a.id_order_m AS ID, a.tgl_order, a.no_order, a.grand_total_order AS DEBET, 0 AS KREDIT, 'ordercustomer' AS TIPE, a.brand_order, a.tipe_order, b.nama_customer AS NAMA
                        FROM order_m a
                        INNER JOIN customer b ON a.id_customer = b.id_customer
                        WHERE a.is_paid = '1' AND a.status_order = '1' 
                          AND (a.tgl_order BETWEEN '{$start_date}' AND '{$end_date}') 
                          AND (a.brand_order = '{$brand_order}' || 'all' = '{$brand_order}')
                          AND (a.tipe_order = '{$tipe_order}' || 'all' = '{$tipe_order}') 
                          AND ('IN' = '{$cash_flow}' || 'all' = '{$cash_flow}')
                        UNION 
                        SELECT a.id_order_vendor_m as ID, a.tgl_order_vendor, a.no_order_vendor, 0 AS DEBET, a.grand_total_order AS KREDIT, 'ordervendor' AS TIPE, a.brand_order, a.tipe_order, b.nama_vendor AS NAMA
                        FROM order_vendor_m a
                        INNER JOIN vendor b ON a.id_vendor = b.id_vendor
                        WHERE a.is_paid_vendor = '1' AND a.status_order_vendor = '1' 
                          AND (a.tgl_order_vendor BETWEEN '{$start_date}' AND '{$end_date}') 
                          AND (a.brand_order = '{$brand_order}' || 'all' = '{$brand_order}')
                          AND (a.tipe_order = '{$tipe_order}' || 'all' = '{$tipe_order}')
                          AND ('OUT' = '{$cash_flow}' || 'all' = '{$cash_flow}')
                        UNION
                        SELECT id_jurnal_umum as ID, tgl_jurnal_umum, keterangan_jurnal_umum, debet_jurnal_umum AS DEBET, kredit_jurnal_umum AS KREDIT, 'datajurnal' AS TIPE, brand_jurnal_umum AS brand_order, tipe_jurnal_umum AS tipe_order, 'empty' AS NAMA
                        FROM jurnal_umum
                        WHERE (tgl_jurnal_umum BETWEEN '{$start_date}' AND '{$end_date}') 
                          AND (brand_jurnal_umum = '{$brand_order}' || 'all' = '{$brand_order}')
                          AND (tipe_jurnal_umum = '{$tipe_order}' || 'all' = '{$tipe_order}')
                          AND IF('IN' = '{$cash_flow}', debet_jurnal_umum <> 0, IF('all' = '{$cash_flow}', 1=1, kredit_jurnal_umum <> 0))
                        UNION
                        SELECT a.id_order_eatery_m AS ID, a.tgl_order, a.no_order_eatery, a.grand_total_order AS DEBET, 0 AS KREDIT, 'eatery' AS TIPE, 'AHF' AS brand_order, a.tipe_order, CONCAT(a.jenis_transaksi,' (', a.catatan_informasi,')') AS NAMA
                        FROM order_eatery_m a
                        WHERE a.void = '0' AND a.is_paid = '1'
                            AND (a.tgl_order BETWEEN '{$start_date}' AND '{$new_end_date}')
                            AND ('IN' = '{$cash_flow}' || 'all' = '{$cash_flow}')
                            AND ('AHF' = '{$brand_order}' || 'all' = '{$brand_order}')
                            AND (a.tipe_order = '{$tipe_order}' || 'all' = '{$tipe_order}')
                    )a
                    CROSS JOIN (select @mutasi := 0) params
                    CROSS JOIN (select @count := 0) counter
                    ORDER BY a.tgl_order, ID
                )a ";

            if(!$excel){
                $sql.= " ORDER BY COUNT DESC LIMIT {$start}, {$length}";
            }


        $query = $this->db->query($sql);
        return $query;
    }


    function get_posisi(){
        $sql = "SELECT * FROM posisi ORDER BY nama_posisi";

        $query = $this->db->query($sql);
        return $query;
    }

    function delete_delivery($id_delivery){
        $sql = "DELETE FROM delivery
                WHERE id_delivery = '{$id_delivery}'";

        $query = $this->db->query($sql);
        return $query;
    }


    function delete_pick_up($id_pick_up){
        $sql = "DELETE FROM pick_up
                WHERE id_pick_up = '{$id_pick_up}'";

        $query = $this->db->query($sql);
        return $query;
    }

    function get_staff_salary($id_staff, $awal_akhir_salary, $bulan_salary, $tahun_salary, $tgl_awal, $tgl_akhir){
        $sql = "SELECT *, b.id_staff
                FROM staff b
                INNER JOIN posisi d ON b.id_posisi = d.id_posisi
                LEFT JOIN salary a ON a.id_staff = b.id_staff  
                  AND a.`awal_akhir_salary` = '".$awal_akhir_salary."' 
                  AND a.`bulan_salary` = '".$bulan_salary."' 
                  AND a.`tahun_salary` = '".$tahun_salary."'
                LEFT JOIN (
                    SELECT SUM(b.ongkir_order) as ongkir_salary, a.id_staff
                    FROM delivery a
                    INNER JOIN order_m b ON a.id_order_m = b.id_order_m
                    WHERE a.id_staff = '".$id_staff."' 
                        AND a.tgl_delivery BETWEEN '".$tgl_awal."' AND '".$tgl_akhir."'
                    GROUP BY a.id_staff
                ) c ON b.id_staff = c.id_staff
                WHERE b.id_staff = '".$id_staff."' ";

        $query = $this->db->query($sql);
        return $query;
    }

    function get_salary_only($id_staff, $awal_akhir_salary, $bulan_salary, $tahun_salary){
        $sql = "SELECT *
                FROM salary a
                WHERE a.id_staff = '".$id_staff."' 
                  AND a.`awal_akhir_salary` = '".$awal_akhir_salary."' 
                  AND a.`bulan_salary` = '".$bulan_salary."' 
                  AND a.`tahun_salary` = '".$tahun_salary."'";

        $query = $this->db->query($sql);
        return $query;
    }

    function add_salary($data){
        $input_data = array(
            'id_staff' => $data['id_staff'],
            'awal_akhir_salary' => $data['awal_akhir_salary'],
            'bulan_salary' => $data['bulan_salary'],
            'tahun_salary' => $data['tahun_salary'],
            'fee_penjualan_salary' => $data['fee_penjualan_salary'],
            'lembur_salary' => $data['lembur_salary'],
            'kas_bon_salary' => $data['kas_bon_salary'],
            'potongan_kas_bon_salary' => $data['potongan_kas_bon_salary'],
            'THR_salary' => $data['THR_salary'],
            'lain_lain_salary' => $data['lain_lain_salary'],
            'catatan_lain_lain' => $data['catatan_lain_lain'],
            'kuota_internet_salary' => $data['kuota_internet_salary']

        );

        $this->db->insert('salary',$input_data);
        $insert_id = $this->db->insert_id();
        return $insert_id;
    }

    function update_salary($updated_data, $id_salary){
        $this->db->where('id_salary', $id_salary);
        return $this->db->update('salary',$updated_data);
    }

    function get_pick_up_by_id($id){
        $sql = "SELECT *, 
                DATE_FORMAT(a.tgl_pick_up, '%Y-%m-%d') AS custom_tgl_pick_up,
                DATE_FORMAT(c.tgl_order_vendor, '%d/%m/%Y') AS custom_tgl_order_vendor
                FROM pick_up a
                INNER JOIN vendor b ON a.id_vendor = b.id_vendor
                INNER JOIN order_vendor_m c ON a.id_order_vendor_m = c.id_order_vendor_m
                INNER JOIN staff d ON a.id_staff = d.id_staff
                WHERE a.id_pick_up = '".$id."'";

        $query = $this->db->query($sql);
        return $query;

    }

    function get_pick_up_by_order_vendor_m($id_order_vendor_m){
        $sql = "SELECT *
                FROM pick_up a
                WHERE a.id_order_vendor_m = '".$id_order_vendor_m."'";

        $query = $this->db->query($sql);
        return $query;
    }

    function get_pick_up($search = null, $admin = true, $id_staff = 0, $length = 10000000000, $start = 0, $status = "all"){
        $sql = "SELECT *, a.id_pick_up
                FROM pick_up a
                INNER JOIN vendor b ON a.id_vendor = b.id_vendor
                INNER JOIN order_vendor_m c ON a.id_order_vendor_m = c.id_order_vendor_m
                INNER JOIN staff d ON a.id_staff = d.id_staff
                WHERE (a.status_pick_up = '{$status}' || 'all' = '{$status}')";

        if($search != "" || $search != null){
            if(!$admin){
                $sql .= " AND CONCAT(b.nama_vendor, c.no_order_vendor, a.alamat_pick_up, c.tgl_order_vendor) LIKE '%$search%'
                            AND d.id_staff = '".$id_staff."'";
            } else {
                $sql .= " AND CONCAT(b.nama_vendor, c.no_order_vendor, a.alamat_pick_up, c.tgl_order_vendor, d.nama_staff) LIKE '%$search%'";
            }

        } else {
            if(!$admin){
                $sql .= " AND d.id_staff = '".$id_staff."'";
            }
        }

        $sql .= " ORDER BY a.tgl_pick_up DESC LIMIT {$start}, {$length}";

        $query = $this->db->query($sql);
        return $query;
    }

    function add_pick_up($data){
        $input_data = array(
            'id_vendor' => $data['id_vendor'],
            'alamat_pick_up' => $data['alamat_pick_up'],
            'no_hp_pick_up' => $data['no_hp_pick_up'],
            'id_order_vendor_m' => $data['id_order_vendor_m'],
            'tgl_pick_up' => $data['tgl_pick_up'],
            'catatan_pick_up' => $data['catatan_pick_up'],
            'status_pick_up' => $data['status_pick_up'],
            'id_staff' => $data['id_staff']
        );

        $this->db->insert('pick_up',$input_data);
        $insert_id = $this->db->insert_id();
        return $insert_id;
    }

    function update_pick_up($updated_data, $id_pick_up){
        $this->db->where('id_pick_up', $id_pick_up);
        return $this->db->update('pick_up',$updated_data);
    }

    function get_order_vendor_detail($no_order){

        $sql = "SELECT *, DATE_FORMAT(a.tgl_order_vendor, '%Y-%m-%d') AS custom_tgl_order_vendor, a.id_order_vendor_m, c.id_vendor
                FROM order_vendor_m a
                INNER JOIN order_vendor_s b ON a.id_order_vendor_m = b.id_order_vendor_m
                INNER JOIN product d ON b.id_product = d.id_product
                LEFT JOIN vendor c ON a.id_vendor = c.id_vendor
                /*LEFT JOIN pick_up e ON a.id_order_vendor_m = e.id_order_vendor_m*/
                LEFT JOIN (
                    SELECT a.id_order_vendor_m, a.status_pick_up, b.nama_staff 
                    FROM pick_up a
                    INNER JOIN staff b ON a.id_staff = b.id_staff
                )e ON a.id_order_vendor_m = e.id_order_vendor_m
                WHERE a.no_order_vendor = '".$no_order."' AND a.status_order_vendor = '1'";

        $query = $this->db->query($sql);
        return $query;
    }

    function get_order_vendor_m_by_id($id_order_vendor_m){
        $sql = "SELECT *, a.id_order_vendor_m, a.id_vendor
                FROM order_vendor_m a
                INNER JOIN vendor b ON a.id_vendor = b.id_vendor
                WHERE a.id_order_vendor_m = '".$id_order_vendor_m."'";


        $query = $this->db->query($sql);
        return $query;
    }

    function get_order_vendor_m($search = null, $length = 10000000000, $start = 0, $status = 'all', $brand = "all"){
        $sql = "SELECT *, a.id_order_vendor_m, a.id_vendor
                FROM order_vendor_m a
                INNER JOIN vendor b ON a.id_vendor = b.id_vendor
                WHERE a.status_order_vendor = '1' 
                  AND (a.is_paid_vendor = '{$status}' || 'all' = '{$status}')
                  AND (a.brand_order = '{$brand}' || 'all' = '{$brand}')";

        if($search != "" || $search != null){
            $sql .= " AND CONCAT(b.nama_vendor, a.no_order_vendor, b.alamat_vendor, a.tgl_order_vendor) LIKE '%$search%'";
        }

        $sql .= " ORDER BY a.tgl_order_vendor DESC LIMIT {$start}, {$length}";

        $query = $this->db->query($sql);
        return $query;
    }

    function get_order_vendor_m_pickup($search = null, $length = 10000000000, $start = 0, $status = 'all', $brand = "all"){
        $sql = "SELECT *, a.id_order_vendor_m, a.id_vendor
                FROM order_vendor_m a
                INNER JOIN vendor b ON a.id_vendor = b.id_vendor
                LEFT JOIN pick_up c ON a.id_order_vendor_m = c.id_order_vendor_m
                WHERE (c.status_pick_up IS NULL OR c.status_pick_up = '2')
                    AND a.is_in_store = '0'
                    AND a.status_order_vendor = '1' 
                    AND (a.is_paid_vendor = '{$status}' || 'all' = '{$status}')
                    AND (a.brand_order = '{$brand}' || 'all' = '{$brand}')";

        if($search != "" || $search != null){
            $sql .= " AND CONCAT(b.nama_vendor, a.no_order_vendor, b.alamat_vendor, a.tgl_order_vendor) LIKE '%$search%'";
        }

        $sql .= " ORDER BY a.tgl_order_vendor DESC LIMIT {$start}, {$length}";

        $query = $this->db->query($sql);
        return $query;
    }

    function get_order_vendor_m_by_vendor($id_customer, $start_date, $end_date, $length = 10000000000, $start = 0){
        $sql = "SELECT *
                FROM order_vendor_m
                WHERE id_vendor = '{$id_customer}'
                    AND (tgl_order_vendor BETWEEN '{$start_date}' AND '{$end_date}')
                    AND status_order_vendor = '1'
                ORDER BY tgl_order_vendor DESC LIMIT {$start}, {$length}";

        $query = $this->db->query($sql);
        return $query;
    }

    function get_order_vendor_s($id_order_vendor_m){
        $sql = "SELECT *, a.id_order_vendor_s
                FROM order_vendor_s a
                INNER JOIN product b ON a.id_product = b.id_product
                WHERE a.id_order_vendor_m = '".$id_order_vendor_m."'";

        $query = $this->db->query($sql);
        return $query;
    }

    function add_order_vendor_m($data){
        $input_data = array(
            'id_vendor' => $data['id_vendor'],
            'no_order_vendor' => $data['no_order_vendor'],
            'catatan_order_vendor' => $data['catatan_order_vendor'],
            'tgl_order_vendor' => $data['tgl_order_vendor'],
            'grand_total_order'=> $data['grand_total_order'],
            'status_order_vendor'=> $data['status_order_vendor'],
            'is_paid_vendor'=> $data['is_paid_vendor'],
            'payment_detail'=> $data['payment_detail'],
            'is_in_store'=> $data['is_in_store'],
            'tipe_order'=> $data['tipe_order'],
            'brand_order' => $data['brand_order'],
            'diskon_order_vendor' => $data['diskon_order_vendor']
        );

        $this->db->insert('order_vendor_m',$input_data);
        $insert_id = $this->db->insert_id();
        return $insert_id;
    }

    function update_order_vendor_m($updated_data, $id_order_vendor_m){
        $this->db->where('id_order_vendor_m', $id_order_vendor_m);
        return $this->db->update('order_vendor_m',$updated_data);
    }

    function add_order_vendor_s($data){
        $input_data = array(
            'id_order_vendor_m' => $data['id_order_vendor_m'],
            'id_product' => $data['id_product'],
            'qty_order_vendor' => $data['qty_order_vendor'],
            'harga_order_vendor' => $data['harga_order_vendor'],
            'total_order_vendor' => $data['total_order_vendor']
        );

        $this->db->insert('order_vendor_s',$input_data);
        $insert_id = $this->db->insert_id();
        return $insert_id;
    }

    function update_order_vendor_s($updated_data, $id_order_vendor_s){
        $this->db->where('id_order_vendor_s', $id_order_vendor_s);
        return $this->db->update('order_vendor_s',$updated_data);
    }

    function get_delivery_by_id($id){
        $sql = "SELECT *, 
                DATE_FORMAT(a.tgl_delivery, '%Y-%m-%d') AS custom_tgl_delivery,
                DATE_FORMAT(c.tgl_order, '%d/%m/%Y') AS custom_tgl_order
                FROM delivery a
                INNER JOIN customer b ON a.id_customer = b.id_customer
                INNER JOIN order_m c ON a.id_order_m = c.id_order_m
                INNER JOIN staff d ON a.id_staff = d.id_staff
                WHERE a.id_delivery = '".$id."'";

        $query = $this->db->query($sql);
        return $query;

    }

    function get_delivery_by_id_order_m($id_order_m){
        $sql = "SELECT *
                FROM delivery a
                WHERE a.id_order_m = '".$id_order_m."'";

        $query = $this->db->query($sql);
        return $query;
    }

    function get_delivery($search = null, $admin = true, $id_staff = 0, $length = 10000000000, $start = 0, $status = "all"){
        $sql = "SELECT *, a.id_delivery
                FROM delivery a
                INNER JOIN customer b ON a.id_customer = b.id_customer
                INNER JOIN order_m c ON a.id_order_m = c.id_order_m
                INNER JOIN staff d ON a.id_staff = d.id_staff
                WHERE (a.status_delivery = '{$status}' || 'all' = '{$status}')";

        if($search != "" || $search != null){
            if(!$admin){
                $sql .= " AND CONCAT(b.nama_customer, c.no_order, a.alamat_delivery, c.tgl_order) LIKE '%$search%'
                            AND d.id_staff = '".$id_staff."'";
            } else {
                $sql .= " AND CONCAT(b.nama_customer, c.no_order, a.alamat_delivery, c.tgl_order, d.nama_staff) LIKE '%$search%'";
            }

        } else {
            if(!$admin){
                $sql .= " AND d.id_staff = '".$id_staff."'";
            }
        }


        $sql .= " ORDER BY a.tgl_delivery DESC LIMIT {$start}, {$length}";

        $query = $this->db->query($sql);
        return $query;
    }

    function add_delivery($data){
        $input_data = array(
            'id_customer' => $data['id_customer'],
            'alamat_delivery' => $data['alamat_delivery'],
            'no_hp_delivery' => $data['no_hp_delivery'],
            'id_order_m' => $data['id_order_m'],
            'tgl_delivery' => $data['tgl_delivery'],
            'catatan_delivery' => $data['catatan_delivery'],
            'status_delivery' => $data['status_delivery'],
            'id_staff' => $data['id_staff']
        );

        $this->db->insert('delivery',$input_data);
        $insert_id = $this->db->insert_id();
        return $insert_id;
    }

    function update_delivery($updated_data, $id_delivery){
        $this->db->where('id_delivery', $id_delivery);
        return $this->db->update('delivery',$updated_data);
    }


    function get_order_s($id_order_m){
        $sql = "SELECT *
                FROM order_s a
                INNER JOIN product b ON a.id_product = b.id_product
                WHERE a.id_order_m = '".$id_order_m."'";

        $query = $this->db->query($sql);
        return $query;
    }

    function get_order_m_by_customer($id_customer, $start_date, $end_date, $length = 10000000000, $start = 0){
        $sql = "SELECT *
                FROM order_m
                WHERE id_customer = '{$id_customer}'
                    AND (tgl_order BETWEEN '{$start_date}' AND '{$end_date}')
                    AND status_order = '1'
                ORDER BY tgl_order DESC, id_order_m DESC LIMIT {$start}, {$length}";

        $query = $this->db->query($sql);
        return $query;
    }

    function get_order_m_by_id($id_order_m){
        $sql = "SELECT *
                FROM order_m a
                INNER JOIN customer b ON a.id_customer = b.id_customer
                WHERE a.id_order_m = '".$id_order_m."'";

        $query = $this->db->query($sql);
        return $query;
    }

    function get_order_m_join($list_of_IDs){
        $sql = "SELECT * 
                FROM order_s a
                INNER JOIN product b ON a.id_product = b.id_product
                INNER JOIN order_m c ON a.id_order_m = c.id_order_m
                WHERE a.id_order_m IN ($list_of_IDs)";

        $query = $this->db->query($sql);
        return $query;
    }

    function get_order_m($search = null, $length = 10000000000, $start = 0, $status = 'all', $brand = "all"){
        $sql = "SELECT *, a.id_order_m, b.id_customer
                FROM order_m a
                INNER JOIN customer b ON a.id_customer = b.id_customer
                WHERE a.status_order = '1' 
                  AND (a.is_paid = '{$status}' || 'all' = '{$status}')
                  AND (a.brand_order = '{$brand}' || 'all' = '{$brand}')";

        if($search != "" || $search != null){
            $sql .= " AND CONCAT(b.nama_customer, a.no_order, b.alamat_customer, a.tgl_order) LIKE '%$search%'";
        }

        $sql .= " ORDER BY a.tgl_order DESC, a.id_order_m DESC LIMIT {$start}, {$length}";

        $query = $this->db->query($sql);
        return $query;
    }

    function get_order_m_deliv($search = null, $length = 10000000000, $start = 0, $status = 'all', $brand = "all"){
        $sql = "SELECT *, a.id_order_m, b.id_customer
                FROM order_m a
                INNER JOIN customer b ON a.id_customer = b.id_customer
                LEFT JOIN delivery c ON a.id_order_m = c.id_order_m
                WHERE (c.status_delivery IS NULL OR c.status_delivery = '3')
                    AND a.is_in_store = '0'
                    AND a.status_order = '1' AND (a.is_paid = '{$status}' || 'all' = '{$status}')
                    AND (a.brand_order = '{$brand}' || 'all' = '{$brand}')";

        if($search != "" || $search != null){
            $sql .= " AND CONCAT(b.nama_customer, a.no_order, b.alamat_customer, a.tgl_order) LIKE '%$search%'";
        }

        $sql .= " ORDER BY a.tgl_order DESC, a.id_order_m DESC LIMIT {$start}, {$length}";

        $query = $this->db->query($sql);
        return $query;
    }

    function get_order_m_by_no_order($no_order){
        $sql = "SELECT *, a.id_order_m
                FROM order_m a
                LEFT JOIN delivery b ON a.id_order_m = b.id_order_m
                WHERE a.no_order = '".$no_order."'";

        $query = $this->db->query($sql);
        return $query;
    }

    function get_order_vendor_m_by_no_order($no_order){
        $sql = "SELECT *, a.id_order_vendor_m
                FROM order_vendor_m a
                WHERE a.no_order_vendor = '".$no_order."'";

        $query = $this->db->query($sql);
        return $query;
    }

    function get_order_detail($no_order){

        $sql = "SELECT *, DATE_FORMAT(a.tgl_order, '%Y-%m-%d') AS custom_tgl_order, a.id_order_m
                FROM order_m a
                INNER JOIN order_s b ON a.id_order_m = b.id_order_m
                INNER JOIN product d ON b.id_product = d.id_product
                LEFT JOIN customer c ON a.id_customer = c.id_customer
                /*LEFT JOIN delivery e ON a.id_order_m = e.id_order_m*/
                LEFT JOIN (
                    SELECT a.id_order_m, a.status_delivery, b.nama_staff 
                    FROM delivery a
                    INNER JOIN staff b ON a.id_staff = b.id_staff
                )e ON a.id_order_m = e.id_order_m
                WHERE a.no_order = '".$no_order."'
                    AND a.status_order = '1'";

        $query = $this->db->query($sql);
        return $query;
    }

    function add_order_m($data){
        $input_data = array(
            'id_customer' => $data['id_customer'],
            'no_order' => $data['no_order'],
            'catatan_order' => $data['catatan_order'],
            'tgl_order' => $data['tgl_order'],
            'subtotal_order' => $data['subtotal_order'],
            'ongkir_order'=> $data['ongkir_order'],
            'is_ongkir_kas'=> $data['is_ongkir_kas'],
            'diskon_order'=> $data['diskon_order'],
            'grand_total_order'=> $data['grand_total_order'],
            'status_order'=> $data['status_order'],
            'is_paid'=> $data['is_paid'],
            'payment_detail'=> $data['payment_detail'],
            'is_in_store'=> $data['is_in_store'],
            'is_tentative' => $data['is_tentative'],
            'is_changeable' => $data['is_changeable'],
            'tipe_order' => $data['tipe_order'],
            'brand_order' => $data['brand_order']
        );

        $this->db->insert('order_m',$input_data);
        $insert_id = $this->db->insert_id();
        return $insert_id;
    }

    function update_order_m($updated_data, $id_order_m){
        $this->db->where('id_order_m', $id_order_m);
        return $this->db->update('order_m',$updated_data);
    }

    function add_order_s($data){
        $input_data = array(
            'id_order_m' => $data['id_order_m'],
            'id_product' => $data['id_product'],
            'qty_order' => $data['qty_order'],
            'harga_order' => $data['harga_order'],
            'tipe_harga' => $data['tipe_harga'],
            'total_order' => $data['total_order'],
            'is_free' => $data['is_free']
        );

        $this->db->insert('order_s',$input_data);
        $insert_id = $this->db->insert_id();
        return $insert_id;
    }

    function update_order_s($updated_data, $id_order_s){
        $this->db->where('id_order_s', $id_order_s);
        return $this->db->update('order_s',$updated_data);
    }

    function get_product_price($id_product){
        $sql = "SELECT HJ_product, HP_product, HR_product
                FROM product
                WHERE id_product = '".$id_product."'";

        $query = $this->db->query($sql);
        return $query;
    }

    function get_product_stok_in_out($id_product, $length = 10000000000, $start = 0){
        $sql = "SELECT id_stok_in_out, tipe_in_out, stok_in_out, catatan_in_out,
                    DATE_FORMAT(tgl_in, '%Y-%m-%d') AS custom_tgl_in,
                    DATE_FORMAT(tgl_out, '%Y-%m-%d') AS custom_tgl_out,
                    DATE_FORMAT(tgl_expired, '%Y-%m-%d') AS custom_tgl_expired
                FROM stok_in_out
                WHERE id_product = '".$id_product."'
                ORDER BY GREATEST(tgl_in, tgl_out) DESC, id_stok_in_out DESC LIMIT {$start}, {$length}";

        $query = $this->db->query($sql);
        return $query;

    }

    function get_stok_in_out_by_id($id){
        $sql = "SELECT id_stok_in_out, tipe_in_out, stok_in_out, catatan_in_out,ref_order_m,
                    DATE_FORMAT(tgl_in, '%Y-%m-%d') AS custom_tgl_in,
                    DATE_FORMAT(tgl_out, '%Y-%m-%d') AS custom_tgl_out,
                    DATE_FORMAT(tgl_expired, '%Y-%m-%d') AS custom_tgl_expired
                FROM stok_in_out
                WHERE id_stok_in_out = '".$id."'";

        $query = $this->db->query($sql);
        return $query;
    }

    function delete_stok_in_out_by_id($id_stok_in_out){
        $sql = "DELETE FROM stok_in_out
                WHERE id_stok_in_out = '{$id_stok_in_out}'";

        $query = $this->db->query($sql);
        return $query;
    }

    function delete_stok_in_out_by_order($ref_order_m){
        $sql = "DELETE FROM stok_in_out
                WHERE ref_order_m = '{$ref_order_m}'";

        $query = $this->db->query($sql);
        return $query;
    }

    function delete_stok_in_out_by_product($id_product){
        $sql = "DELETE FROM stok_in_out
                WHERE id_product = '{$id_product}'";

        $query = $this->db->query($sql);
        return $query;
    }

    function add_stok_in_out($data){
        $input_data = array(
            'id_product' => $data['id_product'],
            'tipe_in_out' => $data['tipe_in_out'],
            'stok_in_out' => $data['stok_in_out'],
            'tgl_in' => $data['tgl_in'],
            'tgl_out' => $data['tgl_out'],
            'tgl_expired' => $data['tgl_expired'],
            'catatan_in_out' => $data['catatan_in_out'],
            'ref_order_m' => $data['ref_order_m']

        );

        $this->db->insert('stok_in_out',$input_data);
        $insert_id = $this->db->insert_id();
        return $insert_id;
    }

    function update_stok_in_out($updated_data, $id_stok_in_out){
        $this->db->where('id_stok_in_out', $id_stok_in_out);
        return $this->db->update('stok_in_out',$updated_data);
    }

    function get_suggest_bahan($search){
        $sql = "SELECT * FROM product 
                WHERE active_product = '1' 
                  AND brand_product = 'BAHAN'
                  AND nama_product LIKE '%".$search."%'";
        $query = $this->db->query($sql);
        return $query;
    }

    function get_product($search = null, $length = 10000000000, $start = 0, $brand = "all", $stock_status = "all"){

        $sql = "SELECT a.*, IFNULL(b.stok_in, 0) - IFNULL(c.stok_out, 0) as STOK
                FROM product a
                LEFT JOIN(
                    SELECT SUM(stok_in_out) as stok_in, id_product 
                    FROM stok_in_out
                    WHERE tipe_in_out = 'IN'
                    GROUP BY id_product
                )b ON a.id_product = b.id_product
                LEFT JOIN(
                    SELECT SUM(stok_in_out) as stok_out, id_product 
                    FROM stok_in_out
                    WHERE tipe_in_out = 'OUT'
                    GROUP BY id_product
                )c ON a.id_product = c.id_product
                WHERE a.active_product = '1'
                    AND (a.brand_product = '{$brand}' OR 'all' = '{$brand}')";

        if($brand != 'BAHAN'){
            $sql .= " AND a.brand_product <> 'BAHAN'";
        }


        if($search != "" || $search != null){
            $sql .= " AND a.nama_product LIKE '%$search%'";
        }

        if($stock_status == "more"){
            $sql .= " HAVING (STOK < -0.01 OR STOK > 0.01)";
        } else if ($stock_status == "none") {
            $sql .= " HAVING (STOK BETWEEN -0.01 AND 0.01)";
        }

        $sql .= " ORDER BY a.nama_product LIMIT {$start}, {$length}";

        $query = $this->db->query($sql);
        return $query;
    }

    function get_product_by_id($id){
        $sql = "SELECT a.*, IFNULL(b.stok_in, 0) - IFNULL(c.stok_out, 0) as STOK
                FROM product a
                LEFT JOIN(
                    SELECT SUM(stok_in_out) as stok_in, id_product 
                    FROM stok_in_out
                    WHERE tipe_in_out = 'IN'
                    GROUP BY id_product
                )b ON a.id_product = b.id_product
                LEFT JOIN(
                    SELECT SUM(stok_in_out) as stok_out, id_product 
                    FROM stok_in_out
                    WHERE tipe_in_out = 'OUT'
                    GROUP BY id_product
                )c ON a.id_product = c.id_product
                WHERE a.active_product = '1' AND a.id_product = '".$id."'
                ORDER BY a.nama_product";

        $query = $this->db->query($sql);
        return $query;
    }


    function nama_product_check($nama_product){
        $sql = "SELECT * 
                FROM product
                WHERE nama_product = '".$nama_product."'";

        $query = $this->db->query($sql);
        return $query;
    }

    function add_product($data){
        $input_data = array(
            'nama_product' => $data['nama_product'],
            'SKU_product' => $data['SKU_product'],
            'satuan_product' => $data['satuan_product'],
            'HP_product' => $data['HP_product'],
            'HJ_product' => $data['HJ_product'],
            'HR_product' => $data['HR_product'],
            'brand_product' => $data['brand_product']
        );

        $this->db->insert('product',$input_data);
        $insert_id = $this->db->insert_id();
        return $insert_id;
    }

    function update_product($updated_data, $id_product){
        $this->db->where('id_product', $id_product);
        return $this->db->update('product',$updated_data);
    }

    function get_vendor($search = null, $length = 10000000000, $start = 0){

        $sql = "SELECT *
                FROM vendor";

        if($search != "" || $search != null){
            $sql .= " WHERE CONCAT(nama_vendor, '', catatan_vendor) LIKE '%$search%'";
        }

        $sql .= " ORDER BY nama_vendor LIMIT {$start}, {$length}";

        $query = $this->db->query($sql);
        return $query;
    }

    function get_vendor_by_id($id){
        $sql = "SELECT *
                FROM vendor 
                WHERE id_vendor = '".$id."'
                ORDER BY nama_vendor";

        $query = $this->db->query($sql);
        return $query;
    }

    function nama_vendor_check($nama_vendor){
        $sql = "SELECT * FROM vendor
                WHERE nama_vendor = '".$nama_vendor."'";

        $query = $this->db->query($sql);
        return $query;
    }

    function add_vendor($data){
        $input_data = array(
            'nama_vendor' => $data['nama_vendor'],
            'alamat_vendor' => $data['alamat_vendor'],
            'no_hp_vendor' => $data['no_hp_vendor'],
            'email_vendor' => $data['email_vendor'],
            'no_rekening_vendor' => $data['no_rekening_vendor'],
            'nama_bank_vendor' => $data['nama_bank_vendor'],
            'catatan_vendor' => $data['catatan_vendor']
        );

        $this->db->insert('vendor',$input_data);
        $insert_id = $this->db->insert_id();
        return $insert_id;
    }

    function update_vendor($updated_data, $id_vendor){
        $this->db->where('id_vendor', $id_vendor);
        return $this->db->update('vendor',$updated_data);
    }

    function get_customer($search = null, $length = 10000000000, $start = 0){
        $sql = "SELECT *
                FROM customer";

        if($search != "" || $search != null){
            $sql .= " WHERE nama_customer LIKE '%$search%'";
        }

        $sql .= " ORDER BY nama_customer LIMIT {$start}, {$length}";

        $query = $this->db->query($sql);
        return $query;
    }

    function get_customer_by_id($id){
        $sql = "SELECT *
                FROM customer 
                WHERE id_customer = '".$id."'
                ORDER BY nama_customer";

        $query = $this->db->query($sql);
        return $query;
    }

    function nama_customer_check($nama_customer){
        $sql = "SELECT * FROM customer
                WHERE nama_customer = '".$nama_customer."'";

        $query = $this->db->query($sql);
        return $query;
    }

    function add_customer($data){
        $input_data = array(
            'nama_customer' => $data['nama_customer'],
            'alamat_customer' => $data['alamat_customer'],
            'no_hp_customer' => $data['no_hp_customer'],
            'email_customer' => $data['email_customer'],
            'catatan_customer' => $data['catatan_customer']
        );

        $this->db->insert('customer',$input_data);
        $insert_id = $this->db->insert_id();
        return $insert_id;
    }

    function update_customer($updated_data, $id_customer){
        $this->db->where('id_customer', $id_customer);
        return $this->db->update('customer',$updated_data);
    }


    function get_staff($search = null){
        $sql = "SELECT *,
                DATE_FORMAT(a.tgl_lahir_staff, '%Y-%m-%d') AS custom_tgl_lahir,
                DATE_FORMAT(a.tgl_join_staff, '%Y-%m-%d') AS custom_tgl_join
                FROM staff a
                INNER JOIN posisi b ON a.id_posisi = b.id_posisi";

        if($search != "" || $search != null){
            $sql .= " WHERE a.nama_staff LIKE '%$search%'";
        }

        $sql .= " ORDER BY a.nama_staff";

        $query = $this->db->query($sql);
        return $query;;

    }

    function get_staff_by_id($id){
        $sql = "SELECT staff.*, posisi.nama_posisi, 
                DATE_FORMAT(staff.tgl_lahir_staff, '%Y-%m-%d') AS custom_tgl_lahir,
                DATE_FORMAT(staff.tgl_join_staff, '%Y-%m-%d') AS custom_tgl_join
                FROM staff 
                INNER JOIN posisi ON staff.id_posisi = posisi.id_posisi
                WHERE staff.id_staff = '".$id."'
                ORDER BY staff.nama_staff";

        $query = $this->db->query($sql);
        return $query;
    }

    function nama_staff_check($nama_staff){
        $sql = "SELECT * FROM staff
                WHERE nama_staff = '".$nama_staff."'";

        $query = $this->db->query($sql);
        return $query;
    }

    function add_staff($data){
        $input_data = array(
            'nama_staff' => $data['nama_staff'],
            'tgl_lahir_staff' => $data['tgl_lahir_staff'],
            'alamat_staff' => $data['alamat_staff'],
            'no_hp_staff' => $data['no_hp_staff'],
            'id_posisi' => $data['id_posisi'],
            'salary_staff' => $data['salary_staff'],
            'no_rek_staff' => $data['no_rek_staff'],
            'nama_bank_staff' => $data['nama_bank_staff'],
            'tgl_join_staff' => $data['tgl_join_staff']
        );

        $this->db->insert('staff',$input_data);
        $insert_id = $this->db->insert_id();
        return $insert_id;
    }

    function update_staff($updated_data, $id_staff){
        $this->db->where('id_staff', $id_staff);
        return $this->db->update('staff',$updated_data);
    }

}
?>