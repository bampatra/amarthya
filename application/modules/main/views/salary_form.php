

<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">Salary</h1>
    <br>
    <!-- DataTales Example -->

    <div class="wrapper">
        <div class="one">
            <h6> Staff </h6>
            <div class="green-line"></div>
            <div id="staff_info" style="font-size: 14px">
                <div class="form-group row" >
                    <div class="col-sm-12">
                        <select id="id_staff" name="id_staff" class="form-control form-control-sm form-active-control selectpicker" data-live-search="true">
                            <option value="none"> -- Pilih Staff -- </option>
                            <?php foreach ($staffs as $staff) { ?>
                                <option value="<?php echo $staff->id_staff; ?>">
                                    <?php echo $staff->nama_staff; ?>
                                </option>


                            <?php } ?>
                        </select>
                    </div>
                </div>
            </div>
            


        </div>
        <div class="two">
            <h6> Periode </h6>
            <div class="green-line"></div>
            <div class="form-group row mb-2">
                <label class="col-sm-3 col-form-label col-form-label-sm">Awal/Akhir</label>
                <div class="col-sm-9">
                    <select id="awal_akhir_salary" name="awal_akhir_salary" class="form-control form-control-sm form-active-control selectpicker" data-live-search="true">
                        <option value="AWAL">AWAL</option>
                        <option value="AKHIR">AKHIR</option>
                    </select>
                </div>
            </div>
            <div class="form-group row mb-2">
                <label class="col-sm-3 col-form-label col-form-label-sm">Bulan</label>
                <div class="col-sm-9">
                    <select id="bulan_salary" name="bulan_salary" class="form-control form-control-sm form-active-control selectpicker" data-live-search="true">
                        <?php

                        for ($i = 0; $i < 12; ) {
                            $date_str = date('M', strtotime("+ $i++ months"));
                            echo "<option value=$i>".$date_str ."</option>";

                        } ?>
                    </select>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-3 col-form-label col-form-label-sm">Tahun</label>
                <div class="col-sm-9">
                    <input type="number" value="<?php echo date("Y"); ?>" id="tahun_salary" name="tahun_salary" class="form-control form-control-sm form-active-control">
                </div>
            </div>

        </div>
    </div>


    <div class="three" >
        <h6> Detail Order </h6>
        <div id="order-data"></div><br>
        <div id="item-lists"></div>
        <span class="link pilih-order"> Pilih Order </span>

 </div>

    <br>
    <button class="btn btn-primary save" style="width: 100%;  font-size: 14px;">Simpan Data</button>
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

<!-- Page level custom scripts -->

<!-- <script src="<?php echo base_url('assets/js/startbootstrap/demo/datatables-demo.js');?>"></script>-->

<script>

    var selected_order, selected_staff, selected_customer;


    $('#collapseUser').addClass('show');
    $('#navbar-user').addClass('active');


    $('.save').click(function(e){

        if(confirm("Pastikan semua data sudah benar. Lanjutkan?")){
            $('.loading').css("display", "block");
            $('.Veil-non-hover').fadeIn();

            $.ajax({
                type: 'POST', // define the type of HTTP verb we want to use (POST for our form)
                url: admin_url + 'save_salary', // the url where we want to POST// our data object
                dataType: 'json',
                data: {
                    id_customer: selected_customer,
                    alamat_delivery: $('#alamat_delivery').val(),
                    no_hp_delivery: $('#no_hp_delivery').val(),
                    id_order_m: selected_order,
                    tgl_delivery: $('#tgl_delivery').val(),
                    catatan_delivery: $('#catatan_delivery').val(),
                    id_staff: selected_staff
                },
                success: function (response) {
                    if(response.Status == "OK"){
                        window.location.href = admin_url + 'delivery_list';
                    } else if(response.Status == "ERROR" ){
                        show_snackbar(response.Message);

                        $('.loading').css("display", "none");
                        $('.Veil-non-hover').fadeOut();
                    }
                }
            })
        }

    })





</script>
