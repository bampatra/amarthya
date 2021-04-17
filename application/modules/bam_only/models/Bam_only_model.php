<?php
class Bam_only_model extends CI_Model
{

    function freebies_list(){
        $sql = "SELECT  b.nama_product, a.qty_order, c.no_order, c.tgl_order, d.nama_customer
                FROM order_s a
                INNER JOIN product b ON a.id_product = b.id_product
                INNER JOIN order_m c ON a.id_order_m = c.id_order_m
                INNER JOIN customer d ON c.id_customer = d.id_customer
                WHERE a.is_free = '1'";

        $query = $this->db->query($sql);
        return $query;
    }

    function hampir_expired(){

    }

}
?>