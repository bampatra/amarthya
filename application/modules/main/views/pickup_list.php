

<!-- Begin Page Content -->
<div class="container-fluid">

<!--    <button class="btn btn-primary add" style="background: #a50000; color: white; width: 300px;"> Tambah Order </button>-->
    <br>
    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Daftar Pick Up</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <select id="status_pick_up" name="status_pick_up" class="form-control form-control-sm form-active-control" data-live-search="true" style="width: 40%; float: left;">
                    <option value="all">Semua</option>
                    <option value="0">Belum Pick Up</option>
                    <option value="1">Selesai</option>
                </select>
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
                <br>
                <div id="info_catatan" style="font-size: 13px; margin-top: 5px"></div>
                <div id="info_catatan_pick_up" style="font-size: 13px; margin-top: 5px"></div>
                <br>
                <div style="text-align: right" id="info_payment"></div>
                <?php if($this->session->userdata('is_admin') == "1" || $this->session->userdata('is_admin') == "3" ) { ?>
                    <a id="edit-info" target="_blank"><span class="link"> Edit </span></a>
                <?php } ?>

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

    document.title = "Daftar Pick Up - Amarthya Group";

    $('#status_pick_up').change(function(){
        get_order_vendor_m($(this).val());
    })

    const urlParams = new URLSearchParams(location.search);
    if(urlParams.has('status')){
        get_order_vendor_m(urlParams.get('status'));
        $('#status_pick_up').val(urlParams.get('status'));
    } else {
        get_order_vendor_m();
    }


    //get all products
    function get_order_vendor_m(status = "all"){
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
                url     : admin_url + 'get_pick_up?status=' + status,
                type    : 'POST',
            },
            createdRow: function ( row, data, index ) {
                $('td', row).eq(0).css("display", "none");
            },
            columns: [
                {"data": "id_pick_up"},
                {
                    "data": {
                        "nama_vendor":"nama_vendor",
                        "no_hp_pick_up":"no_hp_pick_up",
                        "alamat_pick_up":"alamat_pick_up",
                        "tgl_pick_up":"tgl_pick_up",
                        "grand_total_order":"grand_total_order",
                        "status_pick_up": "status_pick_up",
                        "no_order_vendor": "no_order_vendor",
                        "nama_staff":"nama_staff",
                        "timestamp_pick_up":"timestamp_pick_up"
                    },
                    mRender : function(data, type, full) {

                        let dateTimeParts= data.tgl_pick_up.split(/[- :]/);
                        dateTimeParts[1]--;
                        const temp_date = new Date(...dateTimeParts);

                        html = '<div class="detail-row">' +
                                '<div class="detail-column">' +
                            '       <span> Tgl. Pick Up: '+ temp_date.getDate() + '/' + (temp_date.getMonth() + 1) + '/' + temp_date.getFullYear() +'</span><br>' +
                                    '<strong>'+ data.no_order_vendor +'</strong><br>' +
                                    '<span>'+ data.nama_vendor +'</span>';


                        if(data.status_pick_up == "0"){
                            html += '<div class="alert alert-danger alert-payment mobile-only" role="alert">\n' +
                                '                            <strong>BELUM PICK UP</strong>\n' +
                                '                        </div>';
                        } else if(data.status_pick_up == "1"){
                            html += '<div class="alert alert-success alert-payment mobile-only" role="alert">\n' +
                                '                            <strong>SELESAI</strong><br>' + data.timestamp_pick_up +
                                '                        </div>';
                        }


                        html += '</div>' +
                                '<div class="detail-column desktop-and-tablet" style="text-align: left">' +
                                    '<strong style="font-size: 11px;">Status</strong>';

                        if(data.status_pick_up == "0"){
                            html += '<div class="alert alert-danger alert-payment" role="alert">\n' +
                                '                            <strong>BELUM PICK UP</strong>\n' +
                                '                        </div>';
                        } else if(data.status_pick_up == "1"){
                            html += '<div class="alert alert-success alert-payment" role="alert">\n' +
                                '                            <strong>SELESAI</strong><br>' + data.timestamp_pick_up +
                                '                        </div>';
                        }

                        html += '</div></div>';

                        if(<?php echo $this->session->userdata('is_admin')?> == "1" || <?php echo $this->session->userdata('is_admin')?> == "3")
                        {
                            html += '<div class="detail-row"><table style="width: 100%">' +
                                '       <tr class="no-pointer"><td style="width: 15%">Driver: </td><td><span>' + data.nama_staff + '<br>(' + data.no_hp_staff + ')</span></td></tr>' +
                                '       <tr class="no-pointer"><td>Alamat: </td><td><span>' + data.alamat_pick_up + '</span></td></tr>' +
                                '       <tr class="no-pointer"><td>No HP: </td><td><span>' + data.no_hp_pick_up + '</span></td></tr>' +
                                '</table></div>';
                        } else {
                            html += '<div class="detail-row"><table style="width: 100%">' +
                                '       <tr class="no-pointer"><td style="width: 15%">Alamat: </td><td><span>' + data.alamat_pick_up + '</span></td></tr>' +
                                '       <tr class="no-pointer"><td>No HP: </td><td><span>' + data.no_hp_pick_up + '</span></td></tr>' +
                                '</table></div>';
                        }



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

        <?php if($this->session->userdata('is_admin') != "1" && $this->session->userdata('is_admin') != "3") { ?>
            return;
        <?php } ?>


        $('#info_items').html("Memuat...");
        rowData = $('#dataTable').DataTable().row( this ).data();

        $("#edit-info").attr("href", admin_url + 'pick_up_detail?id=' + rowData.id_pick_up);

        let dateTimeParts= rowData.tgl_order_vendor.split(/[- :]/);
        dateTimeParts[1]--;
        const temp_date = new Date(...dateTimeParts);

        html_info_customer = '<span> Tgl. Order Vendor: '+ temp_date.getDate() + '/' + (temp_date.getMonth() + 1) + '/' + temp_date.getFullYear() +'</span><br>\n' +
            '                <strong>'+ rowData.no_order_vendor +'</strong><br>\n' +
            '                <span>'+ rowData.nama_vendor +' ('+ rowData.no_hp_pick_up +')</span><br>\n' +
                            '<span>'+ rowData.alamat_pick_up +'</span><br>';

        if(rowData.status_pick_up == "0"){
            html_info_customer += '<div class="alert alert-danger alert-payment" role="alert">\n' +
                '                            <strong>BELUM PICK UP</strong>\n' +
                '                        </div>';
        } else if(rowData.status_pick_up == "1"){
            html_info_customer += '<div class="alert alert-success alert-payment" role="alert">\n' +
                '                            <strong>SELESAI</strong>\n' +
                '                        </div>';
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
                        '                                <span style="font-size: 9px;">'+ data.qty_order_vendor + ' ' +data.satuan_product +' </span> <br>\n' +
                        '                        </tr>\n' +
                        '                    </table>\n' +
                        '                </div>';

                })

                $('#info_items').html(html_info_items);

            }
        })

        $('#info_catatan').html("Catatan Pesanan: " + rowData.catatan_order_vendor);

        html_catatan_pick_up = "Catatan Pick Up: " + rowData.catatan_pick_up;

        if(rowData.status_pick_up == '1'){
            html_catatan_pick_up += "<br>Waktu Pick Up: " + rowData.timestamp_pick_up;
        }

        $('#info_catatan_pick_up').html(html_catatan_pick_up);

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

        if(rowData.status_pick_up == '0'){
            action_button = '<button type="button" class="btn btn-primary update-status" id="done">Selesai</button>';
            $('.modal-footer').html(action_button);
        }





        $('#detail-modal').modal('toggle');

        $('.update-status').click(function(){

            if(confirm("Selesaikan Pick Up?")) {

                $('.loading').css("display", "block");
                $('.Veil-non-hover').fadeIn();

                tipe_status = $(this).attr('id');

                if (tipe_status == 'done') {
                    status = '1'
                }

                $.ajax({
                    type: 'POST', // define the type of HTTP verb we want to use (POST for our form)
                    url: admin_url + 'update_pick_up_status', // the url where we want to POST// our data object
                    dataType: 'json',
                    data: {status: status, id_pick_up: rowData.id_pick_up},
                    success: function (response) {
                        if (response.Status == "OK") {
                            $('#detail-modal').modal('hide');
                            get_order_vendor_m($('#status_pick_up').val());
                            show_snackbar(response.Message);
                        } else if (response.Status == "ERROR") {
                            show_snackbar(response.Message);
                            $('.loading').css("display", "none");
                            $('.Veil-non-hover').fadeOut();
                        }

                    }
                })
            }
        })

    });

    $('.add').click(function (e) {
        window.open(admin_url + 'order_form');
    })





</script>
