<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class Bam_only extends MX_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->model('Bam_only_model');
    }

    function freebies_list(){
        echo json_encode($this->Bam_only_model->freebies_list()->result_object());
    }

    function hampir_expired(){

    }

}
?>