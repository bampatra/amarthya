<?php
class Home_model extends CI_Model
{

    function is_registered($username, $password){
        $query = $this->db->query("SELECT * FROM user 
                                    WHERE password = '{$password}'
                                    AND username = '{$username}'");

        return $query;
    }

    function get_staff(){
        $sql = "SELECT *,
                DATE_FORMAT(a.tgl_lahir_staff, '%Y-%m-%d') AS custom_tgl_lahir,
                DATE_FORMAT(a.tgl_join_staff, '%Y-%m-%d') AS custom_tgl_join
                FROM staff a
                INNER JOIN posisi b ON a.id_posisi = b.id_posisi
                WHERE a.id_staff NOT IN (
                    SELECT id_staff
                    FROM user
                )
                ORDER BY a.nama_staff";

        $query = $this->db->query($sql);
        return $query;;

    }

    function add_user($data){
        $input_data = array(
            'id_staff' => $data['id_staff'],
            'username' => $data['username'],
            'password' => $data['password']
        );

        $this->db->insert('user',$input_data);
        $insert_id = $this->db->insert_id();
        return $insert_id;
    }
}
?>