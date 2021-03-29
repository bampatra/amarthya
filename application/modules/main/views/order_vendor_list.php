

<!-- Begin Page Content -->
<div class="container-fluid">

<!--    <button class="btn btn-primary add" style="background: #a50000; color: white; width: 300px;"> Tambah Order </button>-->
    <br>
    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Daftar Order Vendor</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                    <tr>
                        <th style="display: none;"> No. Order Vendor </th>
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

    $('#collapseUser').addClass('show');
    $('#navbar-user').addClass('active');

    get_order_m();

    //get all products
    function get_order_m(){
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
                url     : admin_url + 'get_order_vendor_m',
                type    : 'POST',
            },
            createdRow: function ( row, data, index ) {
                $('td', row).eq(0).css("display", "none");
            },
            columns: [
                {"data": "no_order_vendor"},
                {
                    "data": {
                        "nama_vendor":"nama_vendor",
                        "no_hp_vendor":"no_hp_vendor",
                        "alamat_vendor":"alamat_vendor",
                        "tgl_order_vendor":"tgl_order_vendor",
                        "grand_total_order":"grand_total_order",
                        "is_paid_vendor": "is_paid_vendor",
                        "no_order_vendor": "no_order_vendor"
                    },
                    mRender : function(data, type, full) {

                        var temp_date = new Date(data.tgl_order_vendor);

                        html = '<div class="detail-row">' +
                                '<div class="detail-column">' +
                            '       <span>'+ temp_date.getDate() + '/' + (temp_date.getMonth() + 1) + '/' + temp_date.getFullYear() +'</span><br>' +
                                    '<strong>'+ data.no_order_vendor +'</strong>\n' +
                                    '<p>'+ data.nama_vendor +'</p>' +
                                '</div>' +
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

        $("#edit-info").attr("href", admin_url + 'order_vendor_detail?no=' + rowData.no_order_vendor);

        var temp_date = new Date(rowData.tgl_order_vendor);

        html_info_customer = '<span>'+ temp_date.getDate() + '/' + (temp_date.getMonth() + 1) + '/' + temp_date.getFullYear() +'</span><br>\n' +
            '                <strong>'+ rowData.no_order_vendor +'</strong><br>\n' +
            '                <span>'+ rowData.nama_vendor +' ('+ rowData.no_hp_vendor +')</span><br>\n' +
                            '<span>'+ rowData.alamat_vendor +'</span><br>';

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
            url         : admin_url + 'get_order_vendor_s', // the url where we want to POST// our data object
            dataType    : 'json',
            data        : {id_order_vendor_m: rowData.id_order_vendor_m},
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
                        '                                <span style="font-size: 9px;">'+ data.qty_order_vendor +' x '+ convertToRupiah(data.harga_order_vendor) +' </span> <br>\n' +
                        '                                <strong style="font-size: 13px;">'+ convertToRupiah(data.total_order_vendor) +'</strong>\n' +
                        '                            </td>\n' +
                        '                        </tr>\n' +
                        '                    </table>\n' +
                        '                </div>';

                })

                $('#info_items').html(html_info_items);

            }
        })

        $('#info_catatan').html("Catatan: " + rowData.catatan_order_vendor);


        html_info_payment = '<table style="border-spacing: 0 10px; border-collapse:separate; width: 100%;">\n' +
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
