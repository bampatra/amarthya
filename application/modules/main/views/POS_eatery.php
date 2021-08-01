

<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">Point of Sales - Amarthya Eatery</h1>
    <br>
    <!-- DataTales Example -->

    <div id="first">

        <div class="wrapper">
            <div class="one" style="min-height: 170px !important;">
                <h6> Informasi Pesanan </h6>
                <div class="green-line"></div>

                <div class="form-group row">
                    <label class="col-sm-4 col-form-label col-form-label-sm">Jenis</label>
                    <div class="col-sm-8">
                        <select id="jenis_transaksi" name="jenis_transaksi" class="form-control form-active-control form-control-sm">
                            <?php
                            foreach($jenis_transaksi as $jenis){
                                echo "<option value='".$jenis->kode_jenis."'>".$jenis->nama_jenis."</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="invalid-feedback invalid-brand">Data tidak valid</div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-4 col-form-label col-form-label-sm" id="label-catatan-informasi">No. Meja</label>
                    <div class="col-sm-8">
                        <input type="text" value="" name="catatan-informasi" id="catatan-informasi" class="form-control form-control-sm form-active-control catatan-informasi">
                    </div>
                </div>

            </div>
            <div class="two" style="min-height: 170px !important;">
                <h6> Informasi Tambahan </h6>
                <div class="green-line"></div>
                <div class="form-group row" >
                    <div class="col-sm-12">
                        <select id="staff_order" name="staff_order" class="form-control form-control-sm form-active-control selectpicker" data-live-search="true">
                            <option value="none"> -- Pilih Staff -- </option>
                            <?php foreach ($staffs as $staff) { ?>
                                <option value="<?php echo $staff->id_staff; ?>">
                                    <?php echo $staff->nama_staff; ?>
                                </option>


                            <?php } ?>
                        </select>
                    </div>
                </div>
                <input type="text" id="catatan_order" name="catatan_order" class="form-control form-control-sm form-active-control" placeholder="Tulis catatan disini...">

            </div>
        </div>


        <div class="three" >
            <h6> Detail Pesanan </h6>


            <div id="item-lists">
            </div>


            <button class="btn btn-primary-empty add-item" style="width: 100%;  font-size: 11px; margin-top: 20px;">Tambah Pesanan</button>
        </div>

        <br>

        <button class="btn btn-primary save-for-later" style="width: 50%;  font-size: 14px;">Simpan</button>
        <button class="btn btn-primary continue-to-payment" style="width: 49%;  font-size: 14px;">Lanjutkan ke Pembayaran</button>

    </div>


    <div class="wrapper payment-wrapper" style=" display: none;">
        <span class="link back-to-first">Kembali</span><br>
        <div class="two">

            <h6> Pembayaran </h6>
            <div class="green-line"></div>

            <div class="form-group row">
                <label class="col-sm-3 col-form-label col-form-label-sm">Subtotal</label>
                <div class="col-sm-9">
                    <input disabled type="text" id="subtotal" name="subtotal" class="form-control form-control-sm form-active-control input-rupiah">
                    <input type="hidden" id="hidden_subtotal" name="hidden_subtotal" class="hidden_form">
                </div>
            </div>

            <div class="form-group row">
                <label class="col-sm-3 col-form-label col-form-label-sm">Ongkos Kirim</label>
                <div class="col-sm-9">
                    <input type="text" id="ongkir_order" name="ongkir_order" class="form-control form-control-sm form-active-control input-rupiah">
                    <input type="hidden" id="hidden_ongkir_order" name="hidden_ongkir_order" class="hidden_form">
                </div>
            </div>


            <div class="form-group row">
                <label class="col-sm-3 col-form-label col-form-label-sm"></label>
                <div class="col-sm-9">
                    <input type="checkbox" id="is_ongkir_kas">
                    Ongkir dari Kas
                </div>

            </div>

            <div class="form-group row">
                <label class="col-sm-3 col-form-label col-form-label-sm">Tax (10%)</label>
                <div class="col-sm-9">
                    <input disabled type="text" id="tax_order" name="tax_order" class="form-control form-control-sm form-active-control input-rupiah">
                    <input type="hidden" id="hidden_tax_order" name="hidden_tax_order" class="hidden_form">
                </div>
            </div>

            <div class="form-group row">
                <label class="col-sm-3 col-form-label col-form-label-sm">Service (5%)</label>
                <div class="col-sm-9">
                    <input disabled type="text" id="service_order" name="service_order" class="form-control form-control-sm form-active-control input-rupiah">
                    <input type="hidden" id="hidden_service_order" name="hidden_service_order" class="hidden_form">
                </div>
            </div>

            <div class="form-group row">
                <label class="col-sm-3 col-form-label col-form-label-sm">Promosi</label>
                <div class="col-sm-9">
                    <select id="promosi" name="promosi" class="form-control form-active-control form-control-sm">
                        <option value="none">-- Tidak ada promosi --</option>
                        <option value="inputmanual">Input manual</option>
                    </select>
                </div>
            </div>

            <div class="form-group row" id="promosi-inputmanual" style="display: none;">
                <label class="col-sm-3 col-form-label col-form-label-sm">Input Manual Diskon</label>
                <div class="col-sm-5">
                    <div class="input-group input-group-sm mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="basic-addon1">Rp. </span>
                        </div>
                        <input type="text" id="nominal_promosi" name="nominal_promosi" class="form-control form-control-sm form-active-control input-rupiah diskon">
                        <input type="hidden" id="hidden_nominal_promosi" name="hidden_nominal_promosi" class="hidden_form">
                    </div>

                </div>
                <div class="col-sm-4">
                    <div class="input-group input-group-sm mb-3">
                        <input type="number" id="persen_promosi" name="persen_promosi" class="form-control form-control-sm form-active-control diskon">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="basic-addon1">%</span>
                        </div>
                    </div>
                </div>
            </div>

            <div id="payment">

                <div class="form-group row" id="ref_payment_form">
                    <label class="col-sm-3 col-form-label col-form-label-sm">Metode Pembayaran</label>
                    <div class="col-sm-9">
                        <select id="metode_pembayaran" name="metode_pembayaran" class="form-control form-active-control form-control-sm">
                            <option value="none">-- Pilih metode pembayaran --</option>
                            <?php
                            foreach($metode_pembayaran_list as $method){
                                echo "<option value='".$method->html_id."'>".$method->nama_metode_pembayaran."</option>";
                            }

                            ?>
                        </select>
                    </div>
                </div>

                <div id="payment-cash" class="payment-type">
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label col-form-label-sm">Nominal</label>
                        <div class="col-sm-9">
                            <input type="text" id="nominal_bayar" name="nominal_bayar" class="form-control form-control-sm form-active-control input-rupiah">
                            <input type="hidden" id="hidden_nominal_bayar" name="hidden_nominal_bayar" class="hidden_form">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label col-form-label-sm">Kembalian</label>
                        <div class="col-sm-9">
                            <input disabled type="text" id="kembalian_bayar" name="kembalian_bayar" class="form-control form-control-sm form-active-control input-rupiah">
                            <input type="hidden" id="hidden_kembalian_bayar" name="hidden_kembalian_bayar" class="hidden_form">
                        </div>
                    </div>
                </div>

                <div id="payment-EDC" class="payment-type">
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label col-form-label-sm">Bank / Tipe (contoh: BCA / Visa)</label>
                        <div class="col-sm-9">
                            <input type="text" id="jenis_kartu" name="jenis_kartu" class="form-control form-control-sm form-active-control">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label col-form-label-sm">No. Card</label>
                        <div class="col-sm-9">
                            <input type="text" id="no_kartu" name="no_kartu" class="form-control form-control-sm form-active-control">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label col-form-label-sm">Approval No.</label>
                        <div class="col-sm-9">
                            <input type="text" id="approval_kartu" name="approval_kartu" class="form-control form-control-sm form-active-control">
                        </div>
                    </div>
                </div>

                <div id="payment-QRIS" class="payment-type">
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label col-form-label-sm">Platform (contoh: OVO)</label>
                        <div class="col-sm-9">
                            <input type="text" id="platform_QRIS" name="platform_QRIS" class="form-control form-control-sm form-active-control">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label col-form-label-sm">No. HP (jika lewat e-wallet)</label>
                        <div class="col-sm-9">
                            <input type="text" id="no_QRIS" name="no_QRIS" class="form-control form-control-sm form-active-control">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label col-form-label-sm">Approval No.</label>
                        <div class="col-sm-9">
                            <input type="text" id="approval_QRIS" name="approval_QRIS" class="form-control form-control-sm form-active-control">
                        </div>
                    </div>
                </div>

            </div>

            <div style="text-align: right; width: 99%">
                <span style="font-size: 14px"> Grand Total </span>
                <h2 id="grand_total">Rp. 0</h2>
            </div>

            <button class="btn btn-primary save" style="width: 100%;  font-size: 14px;">Selesai</button>

        </div>
    </div>

    <br><br><br><br><br>


    <div class="modal fade" tabindex="-1" role="dialog" id="menu-modal" style="z-index: 5000">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="menuDataTable" width="100%" cellspacing="0">
                            <thead>
                            <tr>
                                <th style="display: none;"> ID </th>
                                <th> Menu </th>
                            </tr>
                            </thead>
                            <tbody id="main-content">

                            </tbody>
                        </table>
                    </div>
                    <br>

                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label col-form-label-sm">Menu</label>
                        <div class="col-sm-10">
                            <input disabled type="text" value="" class="form-control form-control-sm form-active-control nama-menu">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label col-form-label-sm">Qty</label>
                        <div class="col-sm-4">
                            <input type="number" id="qty_order" name="qty_order" value="1" class="form-control form-control-sm form-active-control">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label col-form-label-sm"></label>
                        <div class="col-sm-10">
                            <input type="checkbox" id="is_free" name="is_free">
                            <label class="form-check-label" for="flexSwitchCheckDefault">Free</label>
                        </div>
                    </div>

                    <div style="float: right;">
                        <span style="font-size: 12px;">Total</span><br>
                        <h4 id="subtotal-item"></h4>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary save-item" style="font-size:12px">Tambah</button>
                </div>

            </div>
        </div>
    </div>




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

    .menu-item{
        border: 1px solid rgba(20,143,143,0.3);
        padding: 7px;
        margin-top:5px;
    }

    .payment-type{
        display: none;
    }


</style>

<!-- Page level custom scripts -->

<!-- <script src="<?php echo base_url('assets/js/startbootstrap/demo/datatables-demo.js');?>"></script>-->

<script>

    var temp_HJ = 0, temp_menu = 0, temp_object;
    var subtotal = 0, ongkir = 0, diskon = 0, grandtotal = 0, tax = 0, service = 0;

    $('.continue-to-payment').click(function(){
        $('#first').css("display", "none");
        $('.payment-wrapper').fadeIn();
        $(window).scrollTop(0);
    })

    $('.back-to-first').click(function(){
        $('#first').fadeIn();
        $('.payment-wrapper').css("display", "none");
        $(window).scrollTop(0);
    })

    $('#jenis_transaksi').focus(function() {
        prev_val = $(this).val();
    }).change(function(){



        if(item_lists.length > 0) {
            if(confirm("Semua data akan dihapus. Lanjutkan?")){
                item_lists = [];
                refresh_item();
                subtotal = 0;
                set_harga();

                if ($(this).val() == "Dine In") {
                    $('#label-catatan-informasi').html("No. Meja");
                } else if ($(this).val() == "Take Away") {
                    $('#label-catatan-informasi').html("Nama Customer");
                } else if ($(this).val() == "GoFood" || $(this).val() == "GrabFood") {
                    $('#label-catatan-informasi').html("No. Pesanan / Driver");
                } else if ($(this).val() == "Delivery") {
                    $('#label-catatan-informasi').html("-- Kosongkan --");
                }

            } else {
                $(this).val(prev_val);
                return false;
            }
        } else {
            if ($(this).val() == "Dine In") {
                $('#label-catatan-informasi').html("No. Meja");
            } else if ($(this).val() == "Take Away") {
                $('#label-catatan-informasi').html("Nama Customer");
            } else if ($(this).val() == "GoFood" || $(this).val() == "GrabFood") {
                $('#label-catatan-informasi').html("No. Pesanan / Driver");
            } else if ($(this).val() == "Delivery") {
                $('#label-catatan-informasi').html("-- Kosongkan --");
            }
        }

    })

    $('#metode_pembayaran').change(function(){

        $('.payment-type').css('display', 'none');

        if($(this).val() == "cash"){
            $('#payment-cash').css('display', 'block');
        } else if ($(this).val() == "edc-bca" || $(this).val() == "edc-mandiri" ){
            $('#payment-EDC').css('display', 'block');
        } else if ($(this).val() == "qris"){
            $('#payment-QRIS').css('display', 'block');
        }
    })

    $('#promosi').change(function(){
        if($(this).val() == 'inputmanual'){
            $('#promosi-inputmanual').css("display", "flex");
        } else {
            $('#promosi-inputmanual').css("display", "none");
        }

        set_harga();
    })

    $('#is_ongkir_kas').click(function() {
        if(this.checked) {
            $('#ongkir-type').html('Ongkos Kirim (dari kas)');
        } else {
            $('#ongkir-type').html('Ongkos Kirim');
        }
        set_harga();
    })

    $('#ongkir_order').change(function(){
        set_harga();
    })

    $('.diskon').change(function(){
        calculate_disc();
    })

    $('#nominal_promosi').change(function(){
        percentage = Math.round((parseInt($('#hidden_nominal_promosi').val()) / parseInt(subtotal)) * 100)
        $('#persen_promosi').val(percentage);

        set_harga()
    })

    $('#persen_promosi').change(function(){

        nominal_promosi = parseInt(subtotal) * parseInt($(this).val()) / 100

        $('#nominal_promosi').val(formatRupiah(convertToRupiah(parseInt(nominal_promosi)), 'Rp. ', $('#nominal_promosi').parent().find('.hidden_form')));

        set_harga()

    })

    $('#nominal_bayar').change(function(){
        kembalian = $('#hidden_nominal_bayar').val() - parseInt(grandtotal);
        $('#kembalian_bayar').val(formatRupiah(convertToRupiah(kembalian), 'Rp. ', $('#kembalian_bayar').parent().find('.hidden_form')));


    })

    function calculate_disc(){

    }


    item_lists = [];
    get_menu("all");


    $('.input-rupiah').keyup(function(){
        $(this).val(formatRupiah(this.value, 'Rp. ', $(this).parent().find('.hidden_form')));
    })

    $('#qty_order').keyup(function(){
        temp_subtotal = parseInt($('#qty_order').val()) * parseInt(temp_HJ);
        $('#subtotal-item').html(convertToRupiah(Math.round(temp_subtotal)));
    })

    /* Fungsi formatRupiah */
    function formatRupiah(angka, prefix, hidden_form = null){
        var number_string = angka.replace(/[^,\d]/g, '').toString(),
            split   		= number_string.split(','),
            sisa     		= split[0].length % 3,
            rupiah     		= split[0].substr(0, sisa),
            ribuan     		= split[0].substr(sisa).match(/\d{3}/gi);


        if(hidden_form != null){
            hidden_form.val(number_string);
        }


        // tambahkan titik jika yang di input sudah menjadi angka ribuan
        if(ribuan){
            separator = sisa ? '.' : '';
            rupiah += separator + ribuan.join('.');
        }

        rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
        return prefix == undefined ? rupiah : (rupiah ? 'Rp. ' + rupiah : '');
    }

    // =======================================================================

    document.title = "Point of Sales - Amarthya Group";

    function get_menu(kategori = "all"){
        $('#menuDataTable').DataTable().destroy();
        $('#menuDataTable').DataTable({
            processing: true,
            serverSide: true,
            responsive: true,
            lengthChange: false,
            searching: true,
            pageLength: 5,
            bInfo: false,
            language: {
                search: ""
            },
            pagingType: "simple",
            ajax: {
                url     : admin_url + 'get_menu_eatery?kategori=' + kategori,
                type    : 'POST',
            },
            createdRow: function ( row, data, index ) {
                $('td', row).eq(0).css("display", "none");
            },
            columns: [
                {"data": "id_menu"},
                {
                    "data": {
                        "nama_menu":"nama_menu",
                        "nama_kategori":"nama_kategori"
                    },
                    mRender : function(data, type, full) {
                        html = data.nama_menu +'<br> <span style="font-size: 11px">'+ data.nama_kategori +'</span>';
                        return html;
                    }
                }

            ],
            initComplete: function (settings, json) {

            }
        });

    }

    $('.save-for-later').click(function(){
        show_snackbar('Fitur belum tersedia');
    })

    $('.save').click(function(e){
        if(confirm("Pastikan semua data sudah benar. Lanjutkan?")){
            $('.loading').css("display", "block");
            $('.Veil-non-hover').fadeIn();

            $.ajax({
                type: 'POST', // define the type of HTTP verb we want to use (POST for our form)
                url: admin_url + 'add_order_eatery?savelater=false', // the url where we want to POST// our data object
                dataType: 'json',
                data: {
                    jenis_transaksi: $('#jenis_transaksi').val(),
                    catatan_informasi: $('#catatan-informasi').val(),
                    staff_order: $('#staff_order').val(),
                    catatan_order: $('#catatan_order').val(),
                    hidden_ongkir_order: $('#hidden_ongkir_order').val(),
                    is_ongkir_kas: $('#is_ongkir_kas').prop("checked"),
                    promosi: $('#promosi').val(),
                    hidden_nominal_promosi: $('#hidden_nominal_promosi').val(),
                    persen_promosi: $('#persen_promosi').val(),
                    metode_pembayaran: $('#metode_pembayaran').val(),
                    hidden_nominal_bayar: $('#hidden_nominal_bayar').val(),
                    jenis_kartu: $('#jenis_kartu').val(),
                    no_kartu: $('#no_kartu').val(),
                    approval_kartu: $('#approval_kartu').val(),
                    platform_QRIS: $('#platform_QRIS').val(),
                    no_QRIS: $('#no_QRIS').val(),
                    approval_QRIS: $('#approval_QRIS').val(),
                    order_s: item_lists
                },
                success: function (response) {


                    if(response.Status == "OK"){
                        window.location.href = admin_url + 'POS_transaksi_list';
                    } else if(response.Status == "ERROR" ){
                        show_snackbar(response.Message);

                        $('.loading').css("display", "none");
                        $('.Veil-non-hover').fadeOut();
                    }


                }
            })
        }

    })


    $('.add-item').click(function(){
        temp_menu = 0;
        temp_HJ = 0;

        $('#subtotal-item').html('');

        $('#is_free').prop("checked", false);
        $('#qty_order').val(1);
        $('.nama-menu').val('');
        $('#menu-modal').modal('toggle');


    })

    function set_harga(){
        $('#subtotal').val(formatRupiah(convertToRupiah(subtotal), 'Rp. ', $('#subtotal').parent().find('.hidden_form')));

        if($('#promosi').val() == 'inputmanual'){
            diskon = parseInt($('#hidden_nominal_promosi').val()) || 0;
        } else {
            diskon = 0;
        }

        if($('#jenis_transaksi').val() == "GrabFood" || $('#jenis_transaksi').val() == "GoFood"){
            tax = 0;
            service = 0;
        } else {
            service = parseInt((parseInt(subtotal) - parseInt(diskon)) * 5 / 100);
            tax = parseInt((parseInt(subtotal) - parseInt(diskon) + service) * 10 / 100);
            
        }


        $('#tax_order').val(formatRupiah(convertToRupiah(tax), 'Rp. ', $('#tax_order').parent().find('.hidden_form')));
        $('#service_order').val(formatRupiah(convertToRupiah(service), 'Rp. ', $('#service_order').parent().find('.hidden_form')));

        ongkir = $('#hidden_ongkir_order').val() || 0;

        

        if($('#is_ongkir_kas').prop("checked")) {
            grandtotal = parseInt(subtotal) - parseInt(diskon);
        } else {
            grandtotal = parseInt(subtotal) + parseInt(ongkir) - parseInt(diskon);
        }

        tax_and_service = tax + service;

        grandtotal += tax_and_service;

        $('#grand_total').html(convertToRupiah(grandtotal));

    }



    $('#menuDataTable').on( 'click', 'tbody tr', function () {
        data = $('#menuDataTable').DataTable().row( this ).data();
        $('.nama-menu').val(data.nama_menu);temp_HJ = data.HJ_menu;
        is_free = $('#is_free').prop("checked");

        if($('#jenis_transaksi').val() == "Dine In" || $('#jenis_transaksi').val() == "Take Away"){
            temp_HJ = data.HJ_menu;
        } else {
            temp_HJ = data.HJ_online_menu
        }

        temp_menu = data.id_menu;


        temp_subtotal = parseInt($('#qty_order').val()) * parseInt(temp_HJ);

        if(is_free){
            temp_subtotal = 0;
        }

        $('#subtotal-item').html(convertToRupiah(temp_subtotal));
    });


    $('.save-item').click(function(){

        if(temp_menu == 0){
            return;
        }

        id_menu = temp_menu;
        nama_menu = $('.nama-menu').val();
        qty_order = $('#qty_order').val();
        harga_order = temp_HJ;
        total_order = Math.round(parseInt(qty_order) * parseInt(harga_order));
        is_free = $('#is_free').prop("checked");


        if(is_free){
            total_order = 0;
            nama_menu = $('.nama-menu').val() + ' (FREE)';
        }

        item_lists.push({
            id_menu     : id_menu,
            nama_menu   : nama_menu,
            qty_order   : qty_order,
            harga_order : harga_order,
            total_order : total_order,
            is_free     : is_free
        });


        $('#menu-modal').modal('hide');
        refresh_item();

        subtotal += Math.round(total_order);
        set_harga();



    })

    function refresh_item(){
        html = '';
        $('#item-lists').html(html);
        item_lists.forEach(function(item, index){
            html += '<div class="menu-item"">\n' +
                '            <table width="100%">\n' +
                '                <tr class="no-hover-style">\n' +
                '                    <td> '+ item.nama_menu +' <br><span onclick="delete_item('+ index +')" class="link">Hapus</span></td>\n' +
                '                    <td style="text-align: right">\n' +
                '                        <span style="font-size: 9px;">'+ item.qty_order +' x '+ convertToRupiah(item.harga_order) +'</span> <br>\n' +
                '                        <strong style="font-size: 13px;">'+ convertToRupiah(item.total_order) +'</strong>\n' +
                '                    </td>\n' +
                '                </tr>\n' +
                '\n' +
                '            </table>\n' +
                '        </div>';


        })
        $('#item-lists').html(html);
    }


    function delete_item(index){
        if(confirm("Hapus item?")){

            subtotal -= item_lists[index].total_order;

            item_lists.splice(index, 1);

            set_harga();
            refresh_item();
        }
    }




</script>
