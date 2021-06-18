

<!-- Begin Page Content -->
<div class="container-fluid">

<!--    <button class="btn btn-primary add" style="background: #a50000; color: white; width: 300px;"> Tambah Order </button>-->
    <br>
    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Daftar Order</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <form class="form-inline" style="margin-bottom: 3px;">
                    <div class="form-group" style="margin-right: 5px;">
                        <select id="is_paid" name="is_paid" class="form-control form-control-sm form-active-control" data-live-search="true">
                            <option value="all">Semua Status</option>
                            <option value="0">Belum Bayar</option>
                            <option value="1">Lunas</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <select id="brand_product" name="brand_product" class="form-control form-control-sm form-active-control" data-live-search="true">
                            <?php
                            foreach($brands as $brand){
                                echo "<option value='".$brand->kode_brand."'>".$brand->nama_brand."</option>";
                            }

                            ?>
                        </select>
                    </div>
                </form>


                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                    <tr class="no-hover-style">
                        <th style="display: none;"> No. Order </th>
                        <th> Order </th>
                    </tr>
                    </thead>
                    <tbody id="main-content">

                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" tabindex="-1" role="dialog" id="detail-modal" style="z-index: 5000">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="info_customer"> </div>
                <div id="info_items"></div>
                <div id="info_catatan" style="font-size: 13px; margin-top: 5px"></div>
                <br>
                <div style="text-align: right" id="info_payment"></div>
                <a id="edit-info" target="_blank"><span class="link"> Edit </span></a>

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

    .detail-row{
        display: table;
        width: 100%; /*Optional*/
        table-layout: fixed; /*Optional*/
        border-spacing: 10px; /*Optional*/
    }

    .detail-column{
        display: table-cell;
    }

    .alert-payment{
        padding: 0.2rem 0.75rem;
        margin: 0;
        text-align: right;
        width: fit-content;
        font-size: 11px;
    }

    .product-item{
        border: 1px solid rgba(20,143,143,0.3);
        padding: 7px;
        margin-top:5px;
    }

    span{
        font-size: 13px !important;
    }

</style>

<!-- Page level custom scripts -->

<!-- <script src="<?php echo base_url('assets/js/startbootstrap/demo/datatables-demo.js');?>"></script>-->

<script>

    document.title = "Daftar Order - Amarthya Group";

    var status = "all", brand_order = "all";

    $('#is_paid').change(function(){
        status = $(this).val();
        get_order_m(status, brand_order);
    })

    $('#brand_product').change(function(){
        brand_order = $(this).val();
        get_order_m(status, brand_order);
    })

    const urlParams = new URLSearchParams(location.search);
    if(urlParams.has('status')){
        get_order_m(urlParams.get('status'));
        $('#is_paid').val(urlParams.get('status'));
        status = urlParams.get('status');
    } else {
        get_order_m();
    }


    function get_order_m(status = "all", brand_order = "all"){
        $('.loading').css("display", "block");
        $('.Veil-non-hover').fadeIn();

        $('#dataTable').DataTable().destroy();
        $('#dataTable').DataTable({
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
                url     : admin_url + 'get_order_m?status=' + status + '&brand=' + brand_order,
                type    : 'POST',
            },
            createdRow: function ( row, data, index ) {
                $('td', row).eq(0).css("display", "none");
            },
            columns: [
                {"data": "no_order"},
                {
                    "data": {
                        "nama_customer":"nama_customer",
                        "no_hp_customer":"no_hp_customer",
                        "alamat_customer":"alamat_customer",
                        "tgl_order":"tgl_order",
                        "grand_total_order":"grand_total_order",
                        "is_paid": "is_paid",
                        "no_order": "no_order",
                        "brand_order": "brand_order"
                    },
                    mRender : function(data, type, full) {

                        let dateTimeParts= data.tgl_order.split(/[- :]/);
                        dateTimeParts[1]--;
                        const temp_date = new Date(...dateTimeParts);

                        html = '<div class="detail-row">' +
                                '<div class="detail-column">' +
                            '       <span>'+ temp_date.getDate() + '/' + (temp_date.getMonth() + 1) + '/' + temp_date.getFullYear() +'</span><br>' +
                                    '<strong>'+ data.no_order +'</strong>\n<br>' +
                                    '<span>'+ data.nama_customer +'</span><br>';

                        if(data.brand_order == "KA"){
                            html += '<img src="<?php echo base_url('assets/images/logopdf.jpg');?>" style="float: left; margin-top: 3px" width="48px" height="22px">';
                        } else if(data.brand_order == "AH"){
                            html += '<img src="<?php echo base_url('assets/images/amarthya_herbal.png');?>" style="float: left; margin-top: 3px" width="40px" height="40px">';
                        } else if(data.brand_order == "AF"){
                            html += '<img src="<?php echo base_url('assets/images/fashion.png');?>" style="float: left; margin-top: 3px" left="48px" height="48px">';
                        } else if(data.brand_order == "AHF"){
                            html += '<img src="<?php echo base_url('assets/images/phonto.PNG');?>" style="float: left; margin-top: 3px" left="40px" height="40px">';
                        }



                        html += '</div>' +
                                '<div class="detail-column" style="text-align: left">' +
                                    '<strong style="font-size: 11px;">Total Order</strong>\n' +
                                    '<h6>'+ convertToRupiah(data.grand_total_order) +'</h6>';

                        if(data.is_paid == "0"){
                            html += '<div class="alert alert-danger alert-payment" role="alert">\n' +
                                '                            <strong>BELUM BAYAR</strong>\n' +
                                '                        </div>';
                        } else {
                            html += '<div class="alert alert-success alert-payment" role="alert">\n' +
                                '                            <strong>LUNAS</strong>\n' +
                                '                        </div>';
                        }

                        html += '</div>' +
                                // '<div class="detail-column desktop-only" style="text-align: right">' +
                                //     // '<button type="button" class="btn btn-info mr-1"></button>' +
                                //     '<a role="button" class="btn btn-warning btn-sm mr-1" target="_blank" href="'+ admin_url +'order_detail?no='+ data.no_order +'"><i class="fa fa-info-circle" aria-hidden="true"></i></a>' +
                                //     '<a role="button" class="btn btn-success btn-sm mr-1" target="_blank"><i class="fa fa-print" aria-hidden="true"></i></a>' +
                                //     '<a role="button" class="btn btn-danger btn-sm mr-1"><i class="fa fa-trash" aria-hidden="true"></i></a>' +
                                //     // '<button type="button" class="btn btn-dark"></button>' +
                                // '</div>' +
                            '</div>';

                        return html;

                    }
                }

            ],
            initComplete: function (settings, json) {
                $('.loading').css("display", "none");
                $('.Veil-non-hover').fadeOut();
            }
        });
    }

    $('#dataTable').on( 'click', 'tbody tr', function () {
        $('#info_items').html("Memuat...");
        rowData = $('#dataTable').DataTable().row( this ).data();

        $("#edit-info").attr("href", admin_url + 'order_detail?no=' + rowData.no_order);

        let dateTimeParts= rowData.tgl_order.split(/[- :]/);
        dateTimeParts[1]--;
        const temp_date = new Date(...dateTimeParts);

        html_info_customer = '<span>'+ temp_date.getDate() + '/' + (temp_date.getMonth() + 1) + '/' + temp_date.getFullYear() +'</span><br>\n' +
            '                <strong>'+ rowData.no_order +'</strong> <a target="_blank" href="'+ admin_url +'pdf_order?no='+ rowData.no_order +'"><span class="link"> (Print Invoice) </span></a><br>' +
            '                <span>'+ rowData.nama_customer +' ('+ rowData.no_hp_customer +')</span><br>\n' +
                            '<span>'+ rowData.alamat_customer +'</span><br>';


        if(rowData.is_paid == "1"){
            html_info_customer += '<div class="alert alert-success alert-payment" role="alert">\n' +
                '                            <strong>LUNAS</strong>\n' +
                '                        </div>';
        } else {
            html_info_customer += '<div class="alert alert-danger alert-payment" role="alert">\n' +
    '                                <strong>BELUM BAYAR</strong>\n' +
    '                              </div>';
        }

        html_info_customer += '<br> <span> Order: </span><br>';




        $.ajax({
            type        : 'POST', // define the type of HTTP verb we want to use (POST for our form)
            url         : admin_url + 'get_order_s', // the url where we want to POST// our data object
            dataType    : 'json',
            data        : {id_order_m: rowData.id_order_m},
            success     : function(data){
                html_info_items = "";

                data.forEach(function(data){

                    html_info_items += '<div class="product-item">\n' +
                        '                    <table width="100%">\n' +
                        '                        <tr class="no-hover-style">\n' +
                        '                            <td>\n';

                    if(data.is_free == "1"){
                        html_info_items += data.nama_product + ' (FREE)';
                    } else {
                        html_info_items += data.nama_product;
                    }



                    html_info_items +=  '            </td>\n' +
                        '                            <td style="text-align: right">\n' +
                        '                                <span style="font-size: 9px;">'+ data.qty_order +' x '+ convertToRupiah(data.harga_order) +' </span> <br>\n' +
                        '                                <strong style="font-size: 13px;">'+ convertToRupiah(data.total_order) +'</strong>\n' +
                        '                            </td>\n' +
                        '                        </tr>\n' +
                        '                    </table>\n' +
                        '                </div>';

                })

                $('#info_items').html(html_info_items);

            }
        })

        $('#info_catatan').html("Catatan: " + rowData.catatan_order);


        html_info_payment = '<table style="border-spacing: 0 10px; border-collapse:separate; width: 100%;">\n' +
            '                        <tr class="no-hover-style">' +
            '                            <td style="width: 10%"> </td>\n' +
            '                            <td style="text-align: right;width: 45%; font-size:13px" valign="top">\n' +
            '                                Subtotal\n' +
            '                            </td>\n' +
            '                            <td valign="top" style="text-align: right; width: 45%; font-size:13px" id="subtotal">'+ convertToRupiah(rowData.subtotal_order) +'</td>\n' +
            '                        </tr>\n' +
            '                        <tr class="no-pointer">\n' +
            '                            <td style="width: 10%"> </td>\n' +
            '                            <td style="text-align: right;width: 45%; font-size:13px" valign="top" id="ongkir-type">\n';

        if(rowData.is_ongkir_kas == "1"){
            html_info_payment += "Ongkos Kirim (dari kas)";
        } else {
            html_info_payment += "Ongkos Kirim";
        }


        html_info_payment +=    '     </td>\n' +
            '                         <td valign="top" style="text-align: right; width: 45%; font-size:13px" id="ongkir">'+ convertToRupiah(rowData.ongkir_order) +'</td>\n' +
            '                        </tr>\n' +
            '                        <tr class="no-pointer">\n' +
            '                            <td style="width: 10%"> </td>\n' +
            '                            <td style="text-align: right;width: 45%; font-size:13px" valign="top">\n' +
            '                                Diskon\n' +
            '                            </td>\n' +
            '                            <td valign="top" style="text-align: right; width: 45%; font-size:13px" id="diskon">'+ convertToRupiah(rowData.diskon_order) +'</td>\n' +
            '                        </tr>\n' +
            '                        <tr class="no-pointer">\n' +
            '                            <td style="width: 10%"> </td>\n' +
            '                            <td style="text-align: right;width: 45%;" valign="top">\n' +
            '                                Grand Total\n' +
            '                            </td>\n' +
            '                            <td valign="top" style="text-align: right; width: 45%; font-size: 18px; font-weight: bold" id="grandtotal">'+ convertToRupiah(rowData.grand_total_order) +'</td>\n' +
            '                        </tr>\n' +
            '                    </table>'


        $('#info_customer').html(html_info_customer);
        $('#info_payment').html(html_info_payment);


        $('#detail-modal').modal('toggle');
    });

    $('.add').click(function (e) {
        window.open(admin_url + 'order_form');
    })





</script>
