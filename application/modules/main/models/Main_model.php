<?php
class Main_model extends CI_Model
{

    function get_posisi(){
        $sql = "SELECT * FROM posisi ORDER BY nama_posisi";

        $query = $this->db->query($sql);
        return $query;
    }


    function get_order_vendor_detail($no_order){

        $sql = "SELECT *, DATE_FORMAT(a.tgl_order_vendor, '%Y-%m-%d') AS custom_tgl_order_vendor, a.id_order_vendor_m, c.id_vendor
                FROM order_vendor_m a
                INNER JOIN order_vendor_s b ON a.id_order_vendor_m = b.id_order_vendor_m
                INNER JOIN product d ON b.id_product = d.id_product
                LEFT JOIN vendor c ON a.id_vendor = c.id_vendor
                LEFT JOIN pick_up e ON a.id_order_vendor_m = e.id_order_vendor_m
                WHERE a.no_order_vendor = '".$no_order."'";

        $query = $this->db->query($sql);
        return $query;
    }

    function get_order_vendor_m($search = null){
        $sql = "SELECT *
                FROM order_vendor_m a
                INNER JOIN vendor b ON a.id_vendor = b.id_vendor";

        if($search != "" || $search != null){
            $sql .= " WHERE CONCAT(b.nama_vendor, a.no_order_vendor, b.alamat_vendor, a.tgl_order_vendor) LIKE '%$search%'";
        }

        $sql .= " ORDER BY a.tgl_order_vendor DESC";

        $query = $this->db->query($sql);
        return $query;
    }

    function get_order_vendor_s($id_order_vendor_m){
        $sql = "SELECT *
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
            'is_in_store'=> $data['is_in_store']
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

    function get_delivery($search = null){
        $sql = "SELECT *
                FROM delivery a
                INNER JOIN customer b ON a.id_customer = b.id_customer
                INNER JOIN order_m c ON a.id_order_m = c.id_order_m
                INNER JOIN staff d ON a.id_staff = d.id_staff";

        if($search != "" || $search != null){
            $sql .= " WHERE CONCAT(b.nama_customer, c.no_order, a.alamat_deliver, c.tgl_order) LIKE '%$search%'";
        }

        $sql .= " ORDER BY a.tgl_delivery DESC";

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

    function get_order_m($search = null){
        $sql = "SELECT *
                FROM order_m a
                INNER JOIN customer b ON a.id_customer = b.id_customer";

        if($search != "" || $search != null){
            $sql .= " WHERE CONCAT(b.nama_customer, a.no_order, b.alamat_customer, a.tgl_order) LIKE '%$search%'";
        }

        $sql .= " ORDER BY a.tgl_order DESC";

        $query = $this->db->query($sql);
        return $query;
    }

    function get_order_m_deliv($search = null){
        $sql = "SELECT *, a.id_order_m
                FROM order_m a
                INNER JOIN customer b ON a.id_customer = b.id_customer
                LEFT JOIN delivery c ON a.id_order_m = c.id_order_m
                WHERE (c.status_delivery IS NULL OR c.status_delivery = '3')";

        if($search != "" || $search != null){
            $sql .= " AND CONCAT(b.nama_customer, a.no_order, b.alamat_customer, a.tgl_order) LIKE '%$search%'";
        }

        $sql .= " ORDER BY a.tgl_order DESC";

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

    function get_order_detail($no_order){

        $sql = "SELECT *, DATE_FORMAT(a.tgl_order, '%Y-%m-%d') AS custom_tgl_order
                FROM order_m a
                INNER JOIN order_s b ON a.id_order_m = b.id_order_m
                INNER JOIN product d ON b.id_product = d.id_product
                LEFT JOIN customer c ON a.id_customer = c.id_customer
                LEFT JOIN delivery e ON a.id_order_m = e.id_order_m
                WHERE a.no_order = '".$no_order."'";

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
            'is_changeable' => $data['is_changeable']
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

    function get_product_stok_in_out($id_product){
        $sql = "SELECT id_stok_in_out, tipe_in_out, stok_in_out, catatan_in_out,
                    DATE_FORMAT(tgl_in, '%Y-%m-%d') AS custom_tgl_in,
                    DATE_FORMAT(tgl_out, '%Y-%m-%d') AS custom_tgl_out,
                    DATE_FORMAT(tgl_expired, '%Y-%m-%d') AS custom_tgl_expired
                FROM stok_in_out
                WHERE id_product = '".$id_product."'
                ORDER BY id_stok_in_out DESC";

        $query = $this->db->query($sql);
        return $query;

    }

    function get_stok_in_out_by_id($id){
        $sql = "SELECT id_stok_in_out, tipe_in_out, stok_in_out, catatan_in_out,
                    DATE_FORMAT(tgl_in, '%Y-%m-%d') AS custom_tgl_in,
                    DATE_FORMAT(tgl_out, '%Y-%m-%d') AS custom_tgl_out,
                    DATE_FORMAT(tgl_expired, '%Y-%m-%d') AS custom_tgl_expired
                FROM stok_in_out
                WHERE id_stok_in_out = '".$id."'";

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
            'catatan_in_out' => $data['catatan_in_out']
        );

        $this->db->insert('stok_in_out',$input_data);
        $insert_id = $this->db->insert_id();
        return $insert_id;
    }

    function update_stok_in_out($updated_data, $id_stok_in_out){
        $this->db->where('id_stok_in_out', $id_stok_in_out);
        return $this->db->update('stok_in_out',$updated_data);
    }

    function get_product($search = null){

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
                WHERE a.active_product = '1'";

        if($search != "" || $search != null){
            $sql .= " and a.nama_product LIKE '%$search%'";
        }

        $sql .= " ORDER BY a.nama_product";

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
            'HR_product' => $data['HR_product']
        );

        $this->db->insert('product',$input_data);
        $insert_id = $this->db->insert_id();
        return $insert_id;
    }

    function update_product($updated_data, $id_product){
        $this->db->where('id_product', $id_product);
        return $this->db->update('product',$updated_data);
    }

    function get_vendor($search = null){

        $sql = "SELECT *
                FROM vendor";

        if($search != "" || $search != null){
            $sql .= " WHERE nama_vendor LIKE '%$search%'";
        }

        $sql .= " ORDER BY nama_vendor";

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

    function get_customer($search = null){
        $sql = "SELECT *
                FROM customer";

        if($search != "" || $search != null){
            $sql .= "WHERE nama_customer LIKE '%$search%'";
        }

        $sql .= " ORDER BY nama_customer";

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
            'salary_staff' => $data['salary'],
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