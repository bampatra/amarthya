

<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">Slip Gaji</h1>
    <br>
    <!-- DataTales Example -->

    <div class="wrapper">
        <div class="three">
            <h6> Periode </h6>
            <div class="green-line"></div>
            <form id="periode-form">
                <div class="form-group row mb-2">
                    <label class="col-sm-3 col-form-label col-form-label-sm">Awal/Akhir</label>
                    <div class="col-sm-9">
                        <select id="awal_akhir_salary" name="awal_akhir_salary" class="form-control form-control-sm form-active-control" data-live-search="true">
                            <option value="AWAL">AWAL</option>
                            <option value="AKHIR">AKHIR</option>
                        </select>
                    </div>
                </div>
                <div class="form-group row mb-2">
                    <label class="col-sm-3 col-form-label col-form-label-sm">Bulan</label>
                    <div class="col-sm-9">
                        <select id="bulan_salary" name="bulan_salary" class="form-control form-control-sm form-active-control" data-live-search="true" style="">
                           <?php
                               for ($i = 1; $i <= 12; $i++) {
                                   $timestamp = mktime(0, 0, 0, $i);
                                   $label = date("F", $timestamp);

                                   if(date("m") == $i){
                                       $html .= '<option selected value="' . $i . '">' . $label . '</option>"n"';
                                   } else {
                                       $html .= '<option value="' . $i . '">' . $label . '</option>"n"';
                                   }

                               }
                               //close the select tag
                               $html .= "</select>";

                               echo $html;

                           ?>
                        </select>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-3 col-form-label col-form-label-sm">Tahun</label>
                    <div class="col-sm-9">
                        <input type="number" value="<?php echo date("Y"); ?>" id="tahun_salary" name="tahun_salary" class="form-control form-control-sm form-active-control">
                    </div>
                </div>
            </form>
        </div>
    </div>
    <br>
    <a id="print-link" target="_blank"><button class="btn btn-primary print" style="width: 100%;  font-size: 14px;">Cetak Slip Gaji</button></a>


    <br><br><br><br><br>


</div>
<!-- /.container-fluid -->

</div>
<!-- End of Main Content -->


<style>
    tr:hover{
        cursor: pointer;
        background: rgba(20,143,143,0.5);
        transition: background-color 0.15s ease-in-out;
    }

    .product-item{
        border: 1px solid rgba(20,143,143,0.3);
        padding: 7px;
        margin-top:5px;
    }

    .alert-payment{
        padding: 0.2rem 0.75rem;
        margin: 0;
        text-align: left
    }

    .form-group{
        margin-bottom: 0;
    }

    span{
        font-size: 13px;
    }
</style>
<script>
    document.title = "Slip Gaji - Amarthya Group";

    $(document).ready(function(){
        $('#print-link').attr("href", admin_url +
            "pdf_slip_gaji?staff=" + <?php echo $this->session->userdata('id_staff')?> +
                "&periode=" + $('#awal_akhir_salary').val() +
            "&bulan=" + $('#bulan_salary').val() +
            "&tahun=" + $('#tahun_salary').val());
    })

    $('#awal_akhir_salary').change(function(){
        $('#print-link').attr("href", admin_url +
            "pdf_slip_gaji?staff=" + <?php echo $this->session->userdata('id_staff')?> +
                "&periode=" + $('#awal_akhir_salary').val() +
            "&bulan=" + $('#bulan_salary').val() +
            "&tahun=" + $('#tahun_salary').val());
    })



</script>

<!-- Page level custom scripts -->

<!-- <script src="<?php echo base_url('assets/js/startbootstrap/demo/datatables-demo.js');?>"></script>-->


