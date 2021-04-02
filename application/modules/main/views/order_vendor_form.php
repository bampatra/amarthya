

<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">Formulir Order Vendor</h1>
    <br>
    <!-- DataTales Example -->

    <div class="wrapper">
        <div class="one">
            <h6> Vendor </h6>
            <div class="green-line"></div>
            <div id="vendor_info" style="font-size: 14px">
            </div>
            <span class="link pilih-vendor"> Edit Vendor </span>

            <div class="form-check" style="margin-top: 5px;">
                <input class="form-check-input" type="checkbox" id="is_in_store">
                <label class="form-check-label" for="flexSwitchCheckDefault">In Store (Tanpa Pick Up)</label>
            </div>


        </div>
        <div class="two">
            <h6> Informasi Pesanan </h6>
            <div class="green-line"></div>
            <div class="form-group row">
                <label class="col-sm-3 col-form-label col-form-label-sm">Tgl Order</label>
                <div class="col-sm-9">
                    <input type="date" id="tgl_order_vendor" name="tgl_order_vendor" class="form-control form-control-sm form-active-control">
                </div>
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
                <textarea id="catatan_order_vendor" name="catatan_order_vendor" class="form-control form-active-control"> </textarea>
            </div>

        </div>
        <div class="two">
            <table style="border-spacing: 0 10px; border-collapse:separate; width: 100%;">

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
            <div class="form-group row">
                <div class="col-sm-12">
                    <input type="checkbox" id="is_paid_vendor">
                    Sudah Dibayar
                </div>

            </div>


            <div class="form-group row" id="ref_payment_form" style="visibility: hidden;">
                <label class="col-sm-2 col-form-label col-form-label-sm">Ref. </label>
                <div class="col-sm-10">
                    <input type="text" id="payment_detail" name="payment_detail" class="form-control form-control-sm form-active-control">
                </div>
            </div>
        </div>
    </div>
    <br>
    <button class="btn btn-primary save" style="width: 100%;  font-size: 14px;">Simpan Pesanan</button>
    <br><br><br><br><br>


    <div class="modal fade" tabindex="-1" role="dialog" id="vendor-modal" style="z-index: 5000">
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
                        <table class="table table-bordered" id="vendorDataTable" width="100%" cellspacing="0">
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
                                <th style="display: none;"> HP </th>
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
                                <input class="form-check-input" type="radio" name="tipe_harga" id="HP" value="HP" checked>
                                <label class="form-check-label" for="inlineRadio1">HP</label>
                            </div>
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

    var tipe_harga = 'HP';
    var temp_harga = 0, subtotal = 0, temp_product;
    var selected_vendor, temp_product;

    item_lists = [];
    get_product();
    set_harga();


    document.title = "Formulir Order Vendor - Amarthya Group";


    $('.save').click(function(e){

        if(confirm("Pastikan semua data sudah benar. Data tidak bisa diedit setelah disimpan. Lanjutkan?")){
            $('.loading').css("display", "block");
            $('.Veil-non-hover').fadeIn();

            $.ajax({
                type: 'POST', // define the type of HTTP verb we want to use (POST for our form)
                url: admin_url + 'add_order_vendor', // the url where we want to POST// our data object
                dataType: 'json',
                data: {
                    id_vendor: selected_vendor,
                    catatan_order_vendor: $('#catatan_order_vendor').val(),
                    tgl_order_vendor: $('#tgl_order_vendor').val(),
                    is_paid_vendor: $('#is_paid_vendor').prop("checked"),
                    payment_detail: $('#payment_detail').val(),
                    is_in_store: $('#is_in_store').prop("checked"),
                    order_vendor_s: item_lists
                },
                success: function (response) {
                    if(response.Status == "OK"){
                        window.location.href = admin_url + 'order_vendor_detail?no=' + response.Message;
                    } else if(response.Status == "ERROR" ){
                        show_snackbar(response.Message);

                        $('.loading').css("display", "none");
                        $('.Veil-non-hover').fadeOut();
                    }


                }
            })
        }

    })

    $('#is_paid_vendor').click(function(){
        if(this.checked) {
            $('#ref_payment_form').css("visibility", "visible");
            $('#is-paid-alert').html('<div class="alert alert-success alert-payment" role="alert">\n' +
                '                            <strong>LUNAS</strong>\n' +
                '                        </div>');
        } else {
            $('#ref_payment_form').css("visibility", "hidden");
            $('#is-paid-alert').html('<div class="alert alert-danger alert-payment" role="alert">\n' +
                '                            <strong>BELUM DIBAYAR</strong>\n' +
                '                        </div>');
        }
    })



    $('.pilih-vendor').click(function(){
        get_vendor();
    })

    $('.add-item').click(function(){
        $('#subtotal-item').html('');

        temp_product = 0;
        temp_harga = 0;
        $('#qty_order').val(1);
        $('.nama-product').val('');
        $('#product-modal').modal('toggle');


    })

    function set_harga(){
        $('#grandtotal').html(convertToRupiah(subtotal));
    }

    function get_product(){
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
                url     : admin_url + 'get_product',
                type    : 'POST',
            },
            createdRow: function ( row, data, index ) {
                $('td', row).eq(0).css("display", "none");
                $('td', row).eq(1).css("display", "none");
                $('td', row).eq(2).css("display", "none");
            },
            columns: [
                {"data": "id_product"},
                {"data": "HP_product"},
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

    $('#productDataTable').on( 'click', 'tbody tr', function () {
        data = $('#productDataTable').DataTable().row( this ).data();
        $('.nama-product').val(data.nama_product);

        temp_product = data.id_product;
        temp_harga = data.HP_product;
        temp_subtotal = parseFloat($('#qty_order').val()) * parseFloat(temp_harga);


        $('#subtotal-item').html(convertToRupiah(temp_subtotal));
    });



    $('#qty_order').keyup(function(){
        update_price()
    })

    function update_price(){
        temp_subtotal = parseFloat($('#qty_order').val()) * parseFloat(temp_harga);
        $('#subtotal-item').html(convertToRupiah(Math.round(temp_subtotal)));
    }


    $('.save-item').click(function(){
        id_product = temp_product;
        nama_product = $('.nama-product').val();
        qty_order = $('#qty_order').val();
        harga_order = temp_harga;
        total_order = parseFloat(qty_order) * parseFloat(temp_harga);


        item_lists.push({
            id_product  : id_product,
            nama_product: nama_product,
            qty_order   : qty_order,
            harga_order : harga_order,
            total_order : total_order
        });

        $('#product-modal').modal('hide');
        refresh_item();

        subtotal += total_order;
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
    function get_vendor(){
        $('.loading').css("display", "block");
        $('.Veil-non-hover').fadeIn();

        $('#vendorDataTable').DataTable().destroy();
        $('#vendorDataTable').DataTable({
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
                url     : admin_url + 'get_vendor',
                type    : 'POST',
            },
            createdRow: function ( row, data, index ) {
                $('td', row).eq(0).css("display", "none");
                $('td', row).eq(1).css("display", "none");
                $('td', row).eq(2).css("display", "none");
                $('td', row).eq(3).css("display", "none");
            },
            columns: [
                {"data": "id_vendor"},
                {"data": "no_hp_vendor"},
                {"data": "nama_vendor"},
                {"data": "alamat_vendor"},
                {
                    "data": {
                        "nama_vendor":"nama_vendor",
                        "alamat_vendor":"alamat_vendor"
                    },
                    mRender : function(data, type, full) {
                        html = '<strong>' + data.nama_vendor + '</strong><br>' +
                            '   <span>'+ data.alamat_vendor +'</span>';

                        return html;

                    }
                }

            ],
            initComplete: function (settings, json) {
                $('#vendor-modal').modal('toggle');
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


    $('#vendorDataTable').on( 'click', 'tbody tr', function () {
        id_vendor = $('#vendorDataTable').DataTable().row( this ).data().id_vendor;
        no_hp_vendor = $('#vendorDataTable').DataTable().row( this ).data().no_hp_vendor;
        nama_vendor = $('#vendorDataTable').DataTable().row( this ).data().nama_vendor;
        alamat_vendor = $('#vendorDataTable').DataTable().row( this ).data().alamat_vendor;


        html = '<strong>'+ nama_vendor +' ('+ no_hp_vendor +')</strong><br>' +
            '<span style="font-size: 12px">'+ alamat_vendor +'</span>';

        selected_vendor = id_vendor;

        $('#vendor_info').html(html)
        $('#vendor-modal').modal('hide');
    });




</script>
