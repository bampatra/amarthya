

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
                <a target="_blank" href="" id="print-link">
                    <button class="btn btn-primary print mt-3 col-sm-12" style="font-size: 14px;">Cetak Slip Gaji</button>
                </a>

            </div>
            


        </div>
        <div class="two">
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


    <div class="three" >
        <h6> Detail Salary </h6>
        <div class="green-line"></div>
        <div>
            <form id="salary-form">
                <div class="form-group row mb-2">
                    <label class="col-sm-3 col-form-label col-form-label-sm">Gaji</label>
                    <div class="col-sm-9">
                        <input disabled type="number" id="salary_staff" name="tahun_salary" class="form-control form-control-sm form-active-control">
                    </div>
                </div>
                <div class="form-group row mb-2">
                    <label class="col-sm-3 col-form-label col-form-label-sm">Upah Delivery</label>
                    <div class="col-sm-9">
                        <input disabled type="number" id="ongkir_staff" name="ongkir_staff" class="form-control form-control-sm form-active-control">
                    </div>
                </div>
                <div class="form-group row mb-2">
                    <label class="col-sm-3 col-form-label col-form-label-sm">Lembur</label>
                    <div class="col-sm-9">
                        <input type="number" id="lembur_salary" name="lembur_salary" class="form-control form-control-sm form-active-control">
                    </div>
                </div>
                <div class="form-group row mb-2">
                    <label class="col-sm-3 col-form-label col-form-label-sm">Fee Penjualan</label>
                    <div class="col-sm-9">
                        <input type="number" id="fee_penjualan_salary" name="fee_penjualan_salary" class="form-control form-control-sm form-active-control">
                    </div>
                </div>
                <div class="form-group row mb-2">
                    <label class="col-sm-3 col-form-label col-form-label-sm">Kuota Internet</label>
                    <div class="col-sm-9">
                        <input type="number" id="kuota_internet_salary" name="kuota_internet_salary" class="form-control form-control-sm form-active-control">
                    </div>
                </div>
                <div class="form-group row mb-2">
                    <label class="col-sm-3 col-form-label col-form-label-sm">Kas Bon</label>
                    <div class="col-sm-9">
                        <input type="number" id="kas_bon_salary" name="kas_bon_salary" class="form-control form-control-sm form-active-control">
                    </div>
                </div>
                <div class="form-group row mb-2">
                    <label class="col-sm-3 col-form-label col-form-label-sm">Potongan Kas Bon</label>
                    <div class="col-sm-9">
                        <input type="number" id="potongan_kas_bon_salary" name="potongan_kas_bon_salary" class="form-control form-control-sm form-active-control">
                    </div>
                </div>
                <div class="form-group row mb-2">
                    <label class="col-sm-3 col-form-label col-form-label-sm">THR</label>
                    <div class="col-sm-9">
                        <input type="number" id="THR_salary" name="THR_salary" class="form-control form-control-sm form-active-control">
                    </div>
                </div>
                <div class="form-group row mb-2">
                    <label class="col-sm-3 col-form-label col-form-label-sm">Lain-lain</label>
                    <div class="col-sm-9">
                        <input type="number" id="lain_lain_salary" name="lain_lain_salary" class="form-control form-control-sm form-active-control">
                    </div>
                </div>
                <div class="form-group row mb-2">
                    <label class="col-sm-3 col-form-label col-form-label-sm">Deskripsi</label>
                    <div class="col-sm-9">
                        <textarea id="catatan_lain_lain" name="catatan_lain_lain" class="form-control form-control-sm form-active-control"></textarea>
                    </div>
                </div>
            </form><br>

            <div style="text-align: right; width: 99%">
                <span style="font-size: 14px"> Total Gaji </span>
                <h2 id="total_gaji">Rp. 0</h2>
            </div>

        </div>

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

    $('#id_staff, #awal_akhir_salary, #bulan_salary, #tahun_salary').change(function(){
        if($('#id_staff').val() == "none"){
            return;
        }

        $('#print-link').attr("href", admin_url + "pdf_slip_gaji?staff=" + $('#id_staff').val() + "&periode=" + $('#awal_akhir_salary').val() + "&bulan=" + $('#bulan_salary').val() + "&tahun=" + $('#tahun_salary').val())

        $('.loading').css("display", "block");
        $('.Veil-non-hover').fadeIn();
        $.ajax({
            type: 'POST', // define the type of HTTP verb we want to use (POST for our form)
            url: admin_url + 'get_staff_salary', // the url where we want to POST// our data object
            dataType: 'json',
            data: {
                id_staff: $('#id_staff').val(),
                awal_akhir_salary: $('#awal_akhir_salary').val(),
                bulan_salary: $('#bulan_salary').val(),
                tahun_salary: $('#tahun_salary').val()
            },
            success: function (data) {
                console.log(data)

                $("#salary-form").trigger("reset");

                $('#salary_staff').val(data.salary_staff);
                $('#ongkir_staff').val(data.ongkir_salary);


                $('#lembur_salary').val(data.lembur_salary);
                $('#fee_penjualan_salary').val(data.fee_penjualan_salary);
                $('#kuota_internet_salary').val(data.kuota_internet_salary);
                $('#kas_bon_salary').val(data.kas_bon_salary);
                $('#potongan_kas_bon_salary').val(data.potongan_kas_bon_salary);
                $('#THR_salary').val(data.THR_salary);
                $('#lain_lain_salary').val(data.lain_lain_salary);
                $('#catatan_lain_lain').val(data.catatan_lain_lain);

                total_gaji = parseFloat(data.salary_staff) +
                            (parseFloat(data.ongkir_salary) || 0) +
                            (parseFloat(data.lembur_salary) || 0) +
                            (parseFloat(data.fee_penjualan_salary) || 0) +
                            (parseFloat(data.kuota_internet_salary) || 0) +
                            (parseFloat(data.kas_bon_salary) || 0) +
                            (parseFloat(data.THR_salary) || 0) +
                            (parseFloat(data.lain_lain_salary) || 0) -
                            (parseFloat(data.potongan_kas_bon_salary) || 0);

                $('#total_gaji').html(convertToRupiah(total_gaji));

                $('.loading').css("display", "none");
                $('.Veil-non-hover').fadeOut();

            }
        })
    })


    $('.save').click(function(e){

        if(confirm("Pastikan semua data sudah benar. Lanjutkan?")){
            $('.loading').css("display", "block");
            $('.Veil-non-hover').fadeIn();

            $.ajax({
                type: 'POST', // define the type of HTTP verb we want to use (POST for our form)
                url: admin_url + 'save_salary', // the url where we want to POST// our data object
                dataType: 'json',
                data: $('#salary-form, #periode-form').serialize() + '&id_staff=' + $('#id_staff').val(),
                success: function (response) {
                    show_snackbar(response.Message);

                    if(response.Status == "OK"){
                        total_gaji = parseFloat($('#salary_staff').val()) +
                            (parseFloat($('#ongkir_staff').val()) || 0) +
                            (parseFloat($('#lembur_salary').val()) || 0) +
                            (parseFloat($('#fee_penjualan_salary').val()) || 0) +
                            (parseFloat($('#kuota_internet_salary').val()) || 0) +
                            (parseFloat($('#kas_bon_salary').val()) || 0) +
                            (parseFloat($('#THR_salary').val()) || 0) +
                            (parseFloat($('#lain_lain_salary').val()) || 0) -
                            (parseFloat($('#potongan_kas_bon_salary').val()) || 0);

                        $('#total_gaji').html(convertToRupiah(total_gaji));
                    }

                    $('.loading').css("display", "none");
                    $('.Veil-non-hover').fadeOut();
                }
            })
        }
    })





</script>
