<?php
class Main_model extends CI_Model
{

    function get_posisi(){
        $sql = "SELECT * FROM posisi ORDER BY nama_posisi";

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

        if($search != "" || $search == null){
            $sql .= "and a.nama_product LIKE '%$search%'";
        }

        $sql .= "ORDER BY a.nama_product";

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

    function get_vendor(){
        $sql = "SELECT *
                FROM vendor 
                ORDER BY nama_vendor";

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

    function get_customer(){
        $sql = "SELECT *
                FROM customer 
                ORDER BY nama_customer";

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


    function get_staff(){
        $sql = "SELECT staff.*, posisi.nama_posisi
                FROM staff 
                INNER JOIN posisi ON staff.id_posisi = posisi.id_posisi
                ORDER BY staff.nama_staff";

        $query = $this->db->query($sql);
        return $query;
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