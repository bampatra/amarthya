

<!-- Begin Page Content -->
<div class="container-fluid">

<!--    <button class="btn btn-primary add" style="background: #a50000; color: white; width: 300px;"> Tambah Order </button>-->
    <br>
    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Daftar Delivery</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                    <tr>
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
                <br>
                <div id="info_catatan" style="font-size: 13px; margin-top: 5px"></div>
                <div id="info_catatan_delivery" style="font-size: 13px; margin-top: 5px"></div>
                <br>
                <div style="text-align: right" id="info_payment"></div>
                <a id="edit-info" target="_blank"><span class="link"> Edit </span></a>

            </div>
            <div class="modal-footer">

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
        text-align: left;
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
                url     : admin_url + 'get_delivery',
                type    : 'POST',
            },
            createdRow: function ( row, data, index ) {
                $('td', row).eq(0).css("display", "none");
            },
            columns: [
                {"data": "id_delivery"},
                {
                    "data": {
                        "nama_customer":"nama_customer",
                        "no_hp_delivery":"no_hp_deliver",
                        "alamat_delivery":"alamat_delivery",
                        "tgl_delivery":"tgl_delivery",
                        "grand_total_order":"grand_total_order",
                        "status_delivery": "status_delivery",
                        "no_order": "no_order",
                        "nama_staff":"nama_staff",
                        "timestamp_otw":"timestamp_otw",
                        "timestamp_delivery":"timestamp_delivery",
                        "ongkir_order":"ongkir_order"
                    },
                    mRender : function(data, type, full) {

                        var temp_date = new Date(data.tgl_delivery);

                        html = '<div class="detail-row">' +
                                '<div class="detail-column">' +
                            '       <span>'+ temp_date.getDate() + '/' + (temp_date.getMonth() + 1) + '/' + temp_date.getFullYear() +'</span><br>' +
                                    '<strong>'+ data.no_order +'</strong><br>' +
                                    '<span>'+ data.nama_customer +'</span>' +
                            '       <p>Ongkir: '+ convertToRupiah(data.ongkir_order) +'</p>';


                        if(data.status_delivery == "0"){
                            html += '<div class="alert alert-danger alert-payment mobile-only" role="alert">\n' +
                                '                            <strong>BELUM DIANTAR</strong>\n' +
                                '                        </div>';
                        } else if(data.status_delivery == "1"){
                            html += '<div class="alert alert-warning alert-payment mobile-only" role="alert">\n' +
                                '                            <strong>OTW SIS</strong><br>' + data.timestamp_otw +
                                '                        </div>';
                        } else if(data.status_delivery == "2"){
                            html += '<div class="alert alert-success alert-payment mobile-only" role="alert">\n' +
                                '                            <strong>TERKIRIM</strong><br>' + data.timestamp_delivery +
                                '                        </div>';
                        }


                        html += '</div>' +
                                '<div class="detail-column desktop-and-tablet" style="text-align: left">' +
                                    '<strong style="font-size: 11px;">Status</strong>';

                        if(data.status_delivery == "0"){
                            html += '<div class="alert alert-danger alert-payment" role="alert">\n' +
                                '                            <strong>BELUM DIANTAR</strong>\n' +
                                '                        </div>';
                        } else if(data.status_delivery == "1"){
                            html += '<div class="alert alert-warning alert-payment" role="alert">\n' +
                                '                            <strong>OTW SIS</strong><br>' + data.timestamp_otw +
                                '                        </div>';
                        } else if(data.status_delivery == "2"){
                            html += '<div class="alert alert-success alert-payment" role="alert">\n' +
                                '                            <strong>TERKIRIM</strong><br>' + data.timestamp_delivery +
                                '                        </div>';
                        }

                        html += '</div></div>';

                        html += '<div class="detail-row"><table style="width: 100%">' +
                            '       <tr class="no-pointer"><td>Driver: </td><td><span>'+ data.nama_staff +'<br>('+ data.no_hp_staff +')</span></td></tr>' +
                            '       <tr class="no-pointer"><td>Alamat: </td><td><span>'+ data.alamat_delivery +'</span></td></tr>' +
                            '       <tr class="no-pointer"><td>No HP: </td><td><span>'+ data.no_hp_delivery +'</span></td></tr>' +
                                '</table></div>';




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

        $("#edit-info").attr("href", admin_url + 'delivery_detail?id=' + rowData.id_delivery);

        var temp_date = new Date(rowData.tgl_order);

        html_info_customer = '<span>'+ temp_date.getDate() + '/' + (temp_date.getMonth() + 1) + '/' + temp_date.getFullYear() +'</span><br>\n' +
            '                <strong>'+ rowData.no_order +'</strong><br>\n' +
            '                <span>'+ rowData.nama_customer +' ('+ rowData.no_hp_customer +')</span><br>\n' +
                            '<span>'+ rowData.alamat_customer +'</span><br>';

        if(rowData.status_delivery == "0"){
            html_info_customer += '<div class="alert alert-danger alert-payment" role="alert">\n' +
                '                            <strong>BELUM DIANTAR</strong>\n' +
                '                        </div>';
        } else if(rowData.status_delivery == "1"){
            html_info_customer += '<div class="alert alert-warning alert-payment" role="alert">\n' +
                '                            <strong>OTW SIS</strong>\n' +
                '                        </div>';
        } else if(rowData.status_delivery == "2"){
            html_info_customer += '<div class="alert alert-success alert-payment" role="alert">\n' +
                '                            <strong>TERKIRIM</strong>\n' +
                '                        </div>';
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
                        '                                <span style="font-size: 9px;">'+ data.qty_order + ' ' +data.satuan_product +' </span> <br>\n' +
                        '                        </tr>\n' +
                        '                    </table>\n' +
                        '                </div>';

                })

                $('#info_items').html(html_info_items);

            }
        })

        $('#info_catatan').html("Catatan Pesanan: " + rowData.catatan_order);

        html_catatan_delivery = "Catatan Delivery: " + rowData.catatan_delivery;

        if(rowData.status_delivery == '1'){
            html_catatan_delivery += "<br>Waktu Driver Berangkat: " + rowData.timestamp_otw;
        } else if (rowData.status_delivery == '2') {
            html_catatan_delivery += "<br>Waktu Driver Berangkat: " + rowData.timestamp_otw;
            html_catatan_delivery += "<br>Waktu Sampai: " + rowData.timestamp_delivery;
        }

        $('#info_catatan_delivery').html(html_catatan_delivery);

        //
        // html_info_payment = '<table style="border-spacing: 0 10px; border-collapse:separate; width: 100%;">\n' +
        //     '                        <tr class="no-hover-style">' +
        //     '                            <td style="width: 10%"> </td>\n' +
        //     '                            <td style="text-align: right;width: 45%; font-size:13px" valign="top">\n' +
        //     '                                Subtotal\n' +
        //     '                            </td>\n' +
        //     '                            <td valign="top" style="text-align: right; width: 45%; font-size:13px" id="subtotal">'+ convertToRupiah(rowData.subtotal_order) +'</td>\n' +
        //     '                        </tr>\n' +
        //     '                        <tr class="no-pointer">\n' +
        //     '                            <td style="width: 10%"> </td>\n' +
        //     '                            <td style="text-align: right;width: 45%; font-size:13px" valign="top" id="ongkir-type">\n';
        //
        // if(rowData.is_ongkir_kas == "1"){
        //     html_info_payment += "Ongkos Kirim (dari kas)";
        // } else {
        //     html_info_payment += "Ongkos Kirim";
        // }
        //
        //
        // html_info_payment +=    '     </td>\n' +
        //     '                         <td valign="top" style="text-align: right; width: 45%; font-size:13px" id="ongkir">'+ convertToRupiah(rowData.ongkir_order) +'</td>\n' +
        //     '                        </tr>\n' +
        //     '                        <tr class="no-pointer">\n' +
        //     '                            <td style="width: 10%"> </td>\n' +
        //     '                            <td style="text-align: right;width: 45%; font-size:13px" valign="top">\n' +
        //     '                                Diskon\n' +
        //     '                            </td>\n' +
        //     '                            <td valign="top" style="text-align: right; width: 45%; font-size:13px" id="diskon">'+ convertToRupiah(rowData.diskon_order) +'</td>\n' +
        //     '                        </tr>\n' +
        //     '                        <tr class="no-pointer">\n' +
        //     '                            <td style="width: 10%"> </td>\n' +
        //     '                            <td style="text-align: right;width: 45%;" valign="top">\n' +
        //     '                                Grand Total\n' +
        //     '                            </td>\n' +
        //     '                            <td valign="top" style="text-align: right; width: 45%; font-size: 18px; font-weight: bold" id="grandtotal">'+ convertToRupiah(rowData.grand_total_order) +'</td>\n' +
        //     '                        </tr>\n' +
        //     '                    </table>'


        $('#info_customer').html(html_info_customer);
        // $('#info_payment').html(html_info_payment);

        if(rowData.status_delivery == '0'){
            action_button = '<button type="button" class="btn btn-primary update-status" id="otw">Gas!</button>';
        } else if (rowData.status_delivery == '1') {
            action_button = '<button type="button" class="btn btn-danger update-status" id="cancel">Batalkan</button>' +
                '<button type="button" class="btn btn-primary update-status" id="done">Selesai</button>';
        }



        $('.modal-footer').html(action_button);

        $('#detail-modal').modal('toggle');

        $('.update-status').click(function(){

            $('.loading').css("display", "block");
            $('.Veil-non-hover').fadeIn();

            tipe_status = $(this).attr('id');

            if(tipe_status == 'otw'){
                status = '1'
            } else if(tipe_status == 'cancel') {
                status = '0'
            } else if(tipe_status == 'done'){
                status = '2'
            }

            $.ajax({
                type: 'POST', // define the type of HTTP verb we want to use (POST for our form)
                url: admin_url + 'update_delivery_status', // the url where we want to POST// our data object
                dataType: 'json',
                data: {status: status, id_delivery: rowData.id_delivery},
                success: function (response) {
                    if(response.Status == "OK"){
                        $('#detail-modal').modal('hide');
                        get_order_m();
                        show_snackbar(response.Message);
                    } else if(response.Status == "ERROR" ){
                        show_snackbar(response.Message);
                    }

                }
            })
        })

    });

    $('.add').click(function (e) {
        window.open(admin_url + 'order_form');
    })





</script>
