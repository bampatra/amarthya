

<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">Formulir Order</h1>
    <br>
    <!-- DataTales Example -->

    <div class="wrapper">
        <div class="one">
            <h6> Customer </h6>
            <div class="green-line"></div>
            <div id="customer_info" style="font-size: 14px">
            </div>
            <span class="link pilih-customer"> Edit Customer </span>

            <div class="form-check" style="margin-top: 5px;">
                <input class="form-check-input" type="checkbox" id="is_in_store">
                <label class="form-check-label" for="flexSwitchCheckDefault">In Store (Tanpa Delivery)</label>
            </div>


        </div>
        <div class="two">
            <h6> Informasi Pesanan </h6>
            <div class="green-line"></div>
            <div class="form-group row">
                <label class="col-sm-3 col-form-label col-form-label-sm">Tgl Order</label>
                <div class="col-sm-9">
                    <input type="date" id="tgl_order" name="tgl_order" class="form-control form-control-sm form-active-control">
                </div>
            </div>

            <div class="form-group row">
                <label class="col-sm-3 col-form-label col-form-label-sm">Brand</label>
                <div class="col-sm-9">
                    <select id="brand_order" name="brand_order" class="form-control form-active-control form-control-sm">
                        <option value="KA"> Kedai Amarthya </option>
                        <option value="AF"> Amarthya Fashion </option>
                        <option value="AHF"> Amarthya Healthy Food </option>
                        <option value="AH"> Amarthya Herbal </option>
                    </select>
                </div>
                <div class="invalid-feedback invalid-brand">Data tidak valid</div>
            </div>
        </div>
    </div>


    <div class="three" >
        <h6> Detail Pesanan </h6>


        <div id="item-lists">
        </div>


        <button class="btn btn-primary-empty add-item" style="width: 100%;  font-size: 11px; margin-top: 20px;">Tambah Pesanan</button>
    </div>

    <div class="wrapper" style="margin-top: 10px">
        <div class="one">
            <div class="form-group" >
                <label class="col-form-label">Catatan</label>
                <textarea id="catatan_order" name="catatan_order" class="form-control form-active-control"> </textarea>
            </div>
            <div class="form-group row">
                <div class="col-sm-9">
                    <input type="checkbox" id="is_tentative">
                    TENTATIVE
                </div>

            </div>

        </div>
        <div class="two">
            <table style="border-spacing: 0 10px; border-collapse:separate; width: 100%;">
                <tr class="no-hover-style">
                    <td style="width: 10%"> <span class="link payment-detail"> Edit </span> </td>
                    <td style="text-align: right;width: 45%; font-size:13px" valign="top">
                        Subtotal
                    </td>
                    <td valign="top" style="text-align: right; width: 45%; font-size:13px" id="subtotal"> ... </td>
                </tr>
                <tr class="no-pointer">
                    <td style="width: 10%"> </td>
                    <td style="text-align: right;width: 45%; font-size:13px" valign="top" id="ongkir-type">
                        Ongkos Kirim
                    </td>
                    <td valign="top" style="text-align: right; width: 45%; font-size:13px" id="ongkir"> ... </td>
                </tr>
                <tr class="no-pointer">
                    <td style="width: 10%"> </td>
                    <td style="text-align: right;width: 45%; font-size:13px" valign="top">
                        Diskon
                    </td>
                    <td valign="top" style="text-align: right; width: 45%; font-size:13px" id="diskon"> ... </td>
                </tr>
                <tr class="no-pointer">
                    <td style="width: 10%"> </td>
                    <td style="text-align: right;width: 45%;" valign="top">
                        Grand Total
                    </td>
                    <td valign="top" style="text-align: right; width: 45%; font-size: 18px; font-weight: bold" id="grandtotal"> ... </td>
                </tr>
                <tr class="no-pointer">
                    <td colspan="3" id="is-paid-alert">
                        <div class="alert alert-danger alert-payment" role="alert">
                            <strong>BELUM BAYAR</strong>
                        </div>
                    </td>
                </tr>


            </table>
        </div>
    </div>
    <br>
    <button class="btn btn-primary save" style="width: 100%;  font-size: 14px;">Simpan Pesanan</button>
    <br><br><br><br><br>


    <div class="modal fade" tabindex="-1" role="dialog" id="customer-modal" style="z-index: 5000">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Customer</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="customerDataTable" width="100%" cellspacing="0">
                            <thead>
                            <tr>
                                <th style="display: none;"> ID </th>
                                <th style="display: none;"> No HP </th>
                                <th style="display: none;"> Nama Customer </th>
                                <th style="display: none;"> Alamat Customer </th>
                                <th> Customer </th>
                            </tr>
                            </thead>
                            <tbody id="main-content">

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" tabindex="-1" role="dialog" id="product-modal" style="z-index: 5000">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="productDataTable" width="100%" cellspacing="0">
                            <thead>
                            <tr>
                                <th style="display: none;"> ID </th>
                                <th style="display: none;"> HJ </th>
                                <th style="display: none;"> HR </th>
                                <th style="display: none;"> HP </th>
                                <th style="display: none;"> STOK </th>
                                <th style="display: none;"> Nama Product </th>
                                <th> Product </th>
                            </tr>
                            </thead>
                            <tbody id="main-content">

                            </tbody>
                        </table>
                    </div>
                    <br>

                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label col-form-label-sm">Product</label>
                        <div class="col-sm-10">
                            <input disabled type="text" value="" class="form-control form-control-sm form-active-control nama-product">
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
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="tipe_harga" id="HJ" value="HJ" checked>
                                <label class="form-check-label" for="inlineRadio1">HJ</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="tipe_harga" id="HR" value="HR" >
                                <label class="form-check-label" for="inlineRadio1">HR</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="tipe_harga" id="HP" value="HP">
                                <label class="form-check-label" for="inlineRadio1">HP</label>
                            </div>
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

    <div class="modal fade" tabindex="-1" role="dialog" id="payment-modal" style="z-index: 5000">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Payment</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label col-form-label-sm">Ongkos Kirim</label>
                        <div class="col-sm-9">
                            <input type="number" id="ongkir_order" name="ongkir_order" class="form-control form-control-sm form-active-control">
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
                        <label class="col-sm-3 col-form-label col-form-label-sm">Diskon</label>
                        <div class="col-sm-9">
                            <input type="number" id="diskon_order" name="diskon_order" class="form-control form-control-sm form-active-control">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label col-form-label-sm"></label>
                        <div class="col-sm-9">
                            <input type="checkbox" id="is_paid">
                            Sudah Dibayar
                        </div>

                    </div>


                    <div class="form-group row payment-info" id="ref_payment_form" style="display: none">
                        <label class="col-sm-3 col-form-label col-form-label-sm">Ref. Payment</label>
                        <div class="col-sm-12">
                            <input type="text" id="payment_detail" name="payment_detail" class="form-control form-control-sm form-active-control">
                        </div>
                    </div>

                    <div class="form-group row payment-info" style="display:none">
                        <label class="col-sm-6 col-form-label col-form-label-sm">Tipe Transaksi</label>
                        <div class="col-sm-10">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="tipe_order" id="REK" value="REK" checked>
                                <label class="form-check-label" for="inlineRadio1">Rekening</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="tipe_order" id="TUNAI" value="TUNAI" >
                                <label class="form-check-label" for="inlineRadio1">Tunai</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="tipe_order" id="FREE" value="FREE" >
                                <label class="form-check-label" for="inlineRadio1">Free</label>
                            </div>
                        </div>
                    </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary save-payment">Simpan</button>
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

    .product-item{
        border: 1px solid rgba(20,143,143,0.3);
        padding: 7px;
        margin-top:5px;
    }

    .alert-payment{
        padding: 0.2rem 0.75rem;
        margin: 0;
        text-align: right
    }
</style>

<!-- Page level custom scripts -->

<!-- <script src="<?php echo base_url('assets/js/startbootstrap/demo/datatables-demo.js');?>"></script>-->

<script>

    var temp_HJ = 0, temp_HR = 0, temp_HP = 0, temp_product = 0;
    var chosen_price = temp_HJ, tipe_harga = 'HJ', tipe_order = 'REK';
    var subtotal = 0, ongkir = 0, diskon = 0;
    var selected_customer;

    item_lists = [];
    get_product("KA");
    set_harga();

    $('#brand_order').change(function(){
        get_product(this.value);
    })

    document.title = "Formulir Order - Amarthya Group";

    function get_product(brand = "KA"){
        $('#productDataTable').DataTable().destroy();
        $('#productDataTable').DataTable({
            processing: true,
            serverSide: true,
            responsive: true,
            lengthChange: false,
            searching: true,
            bInfo: false,
            language: {
                search: ""
            },
            pagingType: "simple",
            ajax: {
                url     : admin_url + 'get_product?brand=' + brand,
                type    : 'POST',
            },
            createdRow: function ( row, data, index ) {
                $('td', row).eq(0).css("display", "none");
                $('td', row).eq(1).css("display", "none");
                $('td', row).eq(2).css("display", "none");
                $('td', row).eq(3).css("display", "none");
                $('td', row).eq(4).css("display", "none");
                $('td', row).eq(5).css("display", "none");
            },
            columns: [
                {"data": "id_product"},
                {"data": "HJ_product"},
                {"data": "HR_product"},
                {"data": "HP_product"},
                {"data": "STOK"},
                {"data": "nama_product"},
                {
                    "data": {
                        "nama_product":"nama_product",
                        "STOK":"STOK"
                    },
                    mRender : function(data, type, full) {
                        html = data.nama_product +'<br> <span style="font-size: 11px">Stok: '+ data.STOK +'</span>';
                        return html;
                    }
                }

            ],
            initComplete: function (settings, json) {

            }
        });

    }

    $('input[type=radio][name=tipe_order]').change(function() {
        tipe_order = this.value;
    });

    $('.payment-detail').click(function(){
        $('#payment-modal').modal('toggle');
    })


    $('.save').click(function(e){
        if(confirm("Pastikan semua data sudah benar. Data tidak bisa diedit setelah disimpan. Lanjutkan?")){
            $('.loading').css("display", "block");
            $('.Veil-non-hover').fadeIn();

            $.ajax({
                type: 'POST', // define the type of HTTP verb we want to use (POST for our form)
                url: admin_url + 'add_order', // the url where we want to POST// our data object
                dataType: 'json',
                data: {
                    id_customer: selected_customer,
                    catatan_order: $('#catatan_order').val(),
                    tgl_order: $('#tgl_order').val(),
                    ongkir_order: $('#ongkir_order').val(),
                    is_ongkir_kas: $('#is_ongkir_kas').prop("checked"),
                    diskon_order: $('#diskon_order').val(),
                    is_paid: $('#is_paid').prop("checked"),
                    payment_detail: $('#payment_detail').val(),
                    is_in_store: $('#is_in_store').prop("checked"),
                    is_tentative: $('#is_tentative').prop("checked"),
                    tipe_order: tipe_order,
                    brand_order: $('#brand_order').val(),
                    order_s: item_lists
                },
                success: function (response) {
                    if(response.Status == "OK"){
                        window.location.href = admin_url + 'order_detail?no=' + response.Message;
                    } else if(response.Status == "ERROR" ){
                        show_snackbar(response.Message);

                        $('.loading').css("display", "none");
                        $('.Veil-non-hover').fadeOut();
                    }


                }
            })
        }

    })

    $('#is_paid').click(function(){
        if(this.checked) {
            $('.payment-info').css("display", "block");
            $('#is-paid-alert').html('<div class="alert alert-success alert-payment" role="alert">\n' +
                '                            <strong>LUNAS</strong>\n' +
                '                        </div>');
        } else {
            $('.payment-info').css("display", "none");
            $('#is-paid-alert').html('<div class="alert alert-danger alert-payment" role="alert">\n' +
                '                            <strong>BELUM DIBAYAR</strong>\n' +
                '                        </div>');
        }
    })

    $('#is_ongkir_kas').click(function() {
        if(this.checked) {
            $('#ongkir-type').html('Ongkos Kirim (dari kas)');
        } else {
            $('#ongkir-type').html('Ongkos Kirim');
        }
        set_harga();
    })

    $('.save-payment').click(function(){
        if($('#ongkir_order').val() != ""){
            ongkir = $('#ongkir_order').val()
        } else {
            ongkir = 0;
        }

        if($('#diskon_order').val() != ""){
            diskon = $('#diskon_order').val()
        } else {
            diskon = 0;
        }

        set_harga();
        $('#payment-modal').modal('hide');
    })

    $('.pilih-customer').click(function(){
        get_customer();
    })

    $('.add-item').click(function(){
        temp_product = 0;
        temp_HJ = 0;
        temp_HR = 0;
        temp_HP = 0;
        tipe_harga = 'HJ';
        chosen_price = temp_HJ;
        $('#subtotal-item').html('');

        $('#is_free').prop("checked", false);
        $('#qty_order').val(1);
        $('.nama-product').val('');
        $('#product-modal').modal('toggle');


    })

    function set_harga(){
        $('#subtotal').html(convertToRupiah(subtotal));
        $('#ongkir').html(convertToRupiah(ongkir));
        $('#diskon').html(convertToRupiah(diskon));

        if($('#is_ongkir_kas').prop("checked")) {
            grandtotal = parseFloat(subtotal) - parseFloat(diskon);
        } else {
            grandtotal = parseFloat(subtotal) + parseFloat(ongkir) - parseFloat(diskon);
        }

        $('#grandtotal').html(convertToRupiah(grandtotal));
    }

    $('#productDataTable').on( 'click', 'tbody tr', function () {
        data = $('#productDataTable').DataTable().row( this ).data();
        $('.nama-product').val(data.nama_product);
        is_free = $('#is_free').prop("checked");

        temp_HJ = data.HJ_product;
        temp_HR = data.HR_product;
        temp_HP = data.HP_product;
        temp_product = data.id_product;


        tipe_harga = $('input[type=radio][name=tipe_harga]:checked').val();

        if (tipe_harga == 'HJ') {
            chosen_price = temp_HJ;
        }
        else if (tipe_harga == 'HR') {
            chosen_price = temp_HR;
        }
        else if (tipe_harga == 'HP'){
            chosen_price = temp_HP;
        }

        temp_subtotal = parseFloat($('#qty_order').val()) * parseFloat(chosen_price);

        if(is_free){
            temp_subtotal = 0;
        }

        $('#subtotal-item').html(convertToRupiah(temp_subtotal));
    });

    $('#is_free').change(function() {
        update_price()
    });

    $('input[type=radio][name=tipe_harga]').change(function() {
        if (this.value == 'HJ') {
            chosen_price = temp_HJ;
        }
        else if (this.value == 'HR') {
            chosen_price = temp_HR;
        }
        else if (this.value == 'HP'){
            chosen_price = temp_HP;
        }

        tipe_harga = this.value;

        update_price()
    });

    $('#qty_order').keyup(function(){
        update_price()


    })

    function update_price(){
        if($('#is_free').prop("checked")){
            temp_subtotal = 0;
        } else {
            temp_subtotal = parseFloat($('#qty_order').val()) * parseFloat(chosen_price);
        }

        $('#subtotal-item').html(convertToRupiah(Math.round(temp_subtotal)));
    }


    $('.save-item').click(function(){

        if(temp_product == 0){
            return;
        }

        id_product = temp_product;
        nama_product = $('.nama-product').val();
        qty_order = $('#qty_order').val();
        harga_order = chosen_price;
        tipe_harga = tipe_harga;
        total_order = Math.round(parseFloat(qty_order) * parseFloat(harga_order));
        is_free = $('#is_free').prop("checked");

        // ======= validations  =======

        // Cek stok

        // ============================

        if(is_free){
            total_order = 0;
            nama_product = $('.nama-product').val() + ' (FREE)';
        }

        item_lists.push({
            id_product  : id_product,
            nama_product: nama_product,
            qty_order   : qty_order,
            harga_order : harga_order,
            tipe_harga  : tipe_harga,
            total_order : total_order,
            is_free     : is_free
        });

        console.log(item_lists);

        $('#product-modal').modal('hide');
        refresh_item();

        subtotal += Math.round(total_order);
        set_harga();



    })

    function refresh_item(){
        html = '';
        $('#item-lists').html(html);
        item_lists.forEach(function(item, index){
            html += '<div class="product-item"">\n' +
                '            <table width="100%">\n' +
                '                <tr class="no-hover-style">\n' +
                '                    <td> '+ item.nama_product +' <br><span onclick="delete_item('+ index +')" class="link">Hapus</span></td>\n' +
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

    //get all products
    function get_customer(){
        $('.loading').css("display", "block");
        $('.Veil-non-hover').fadeIn();

        $('#customerDataTable').DataTable().destroy();
        $('#customerDataTable').DataTable({
            processing: true,
            serverSide: true,
            responsive: true,
            lengthChange: false,
            searching: true,
            bInfo: false,
            language: {
                search: ""
            },
            pagingType: "simple",
            ajax: {
                url     : admin_url + 'get_customer',
                type    : 'POST',
            },
            createdRow: function ( row, data, index ) {
                $('td', row).eq(0).css("display", "none");
                $('td', row).eq(1).css("display", "none");
                $('td', row).eq(2).css("display", "none");
                $('td', row).eq(3).css("display", "none");
            },
            columns: [
                {"data": "id_customer"},
                {"data": "no_hp_customer"},
                {"data": "nama_customer"},
                {"data": "alamat_customer"},
                {
                    "data": {
                        "nama_customer":"nama_customer",
                        "alamat_customer":"alamat_customer"
                    },
                    mRender : function(data, type, full) {
                        html = '<strong>' + data.nama_customer + '</strong><br>' +
                            '   <span>'+ data.alamat_customer +'</span>';

                        return html;

                    }
                }

            ],
            initComplete: function (settings, json) {
                $('#customer-modal').modal('toggle');
                $('.loading').css("display", "none");
                $('.Veil-non-hover').fadeOut();
            }
        });
    }

    function delete_item(index){
        if(confirm("Hapus item?")){

            subtotal -= item_lists[index].total_order;

            item_lists.splice(index, 1);

            set_harga();
            refresh_item();
        }
    }


    $('#customerDataTable').on( 'click', 'tbody tr', function () {
        id_customer = $('#customerDataTable').DataTable().row( this ).data().id_customer;
        no_hp_customer = $('#customerDataTable').DataTable().row( this ).data().no_hp_customer;
        nama_customer = $('#customerDataTable').DataTable().row( this ).data().nama_customer;
        alamat_customer = $('#customerDataTable').DataTable().row( this ).data().alamat_customer;


        html = '<strong>'+ nama_customer +' ('+ no_hp_customer +')</strong><br>' +
            '<span style="font-size: 12px">'+ alamat_customer +'</span>';

        selected_customer = id_customer;

        $('#customer_info').html(html)
        $('#customer-modal').modal('hide');
    });




</script>
