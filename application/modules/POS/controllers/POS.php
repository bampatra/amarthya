<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Spipu\Html2Pdf\Html2Pdf;

class POS extends MX_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->model('POS_model');
    }

    function index(){

    }

    function front_staff(){

        $data['file_destination'] = 'front_staff';
        $this->load->view('index', $data);
    }



}
?>