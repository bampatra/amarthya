

<!-- Begin Page Content -->
<div class="container-fluid">

<!--    <button class="btn btn-primary add" style="background: #a50000; color: white; width: 300px;"> Tambah Order </button>-->
    <br>
    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Daftar Transaksi</h6>
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
                        <select id="metode_pembayaran" name="metode_pembayaran" class="form-control form-control-sm form-active-control" data-live-search="true">
                            <option value="all">Semua Metode Pembayaran</option>
                            <?php
                            foreach($metode_pembayaran as $payment){
                                echo "<option value='".$payment->html_id."'>".$payment->nama_metode_pembayaran."</option>";
                            }

                            ?>
                        </select>
                    </div>
                </form>


                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                    <tr class="no-hover-style">
                        <th> Tgl Order </th>
                        <th> No Order </th>
                        <th> Jenis Transaksi </th>
                        <th> Grand Total </th>
                        <th style="width: 10%"> Tax </th>
                        <th> Service </th>
                        <th> Payment </th>
                        <th> Status  </th>
                    </tr>
                    </thead>
                    <tbody id="main-content">

                    </tbody>
                </table>
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

    document.title = "Daftar Transaksi Eatery - Amarthya Group";

    var status = "all", payment = "all";

    $('#is_paid').change(function(){
        status = $(this).val();
        get_order_m(status, payment);
    })

    $('#metode_pembayaran').change(function(){
        payment = $(this).val();
        get_order_m(status, payment);
    })

    const urlParams = new URLSearchParams(location.search);
    if(urlParams.has('status')){
        get_order_m(urlParams.get('status'));
        $('#is_paid').val(urlParams.get('status'));
        status = urlParams.get('status');
    } else {
        get_order_m();
    }


    function get_order_m(status = "all", payment = "all"){
        $('.loading').css("display", "block");
        $('.Veil-non-hover').fadeIn();

        $('#dataTable').DataTable().destroy();
        $('#dataTable').DataTable({
            processing: true,
            serverSide: true,
            responsive: true,
            lengthChange: false,
            pageLength: 20,
            searching: true,
            bInfo: false,
            language: {
                search: ""
            },
            pagingType: "simple",
            ajax: {
                url     : admin_url + 'get_order_eatery_m?status=' + status + '&payment=' + payment,
                type    : 'POST',
            },
            createdRow: function ( row, data, index ) {
                // $('td', row).eq(0).css("display", "none");
            },
            columns: [
                {
                    "data": {"tgl_order_eatery": "tgl_order_eatery"},
                    mRender : function(data, type, full) {
                        let dateTimeParts= data.tgl_order.split(/[- :]/);
                        dateTimeParts[1]--;
                        const temp_date = new Date(...dateTimeParts);

                        return temp_date.getDate() + '/' + (temp_date.getMonth() + 1) + '/' + temp_date.getFullYear() +
                                ' ' + temp_date.getHours() + ':' + temp_date.getMinutes();

                    }
                },
                {
                    "data": {
                        "no_order_eatery": "no_order_eatery",
                        "jenis_transaksi": "jenis_transaksi",
                        "catatan_informasi": "catatan_informasi"
                    },
                    mRender : function(data, type, full) {
                        if(data.jenis_transaksi == "GoFood" || data.jenis_transaksi == "GrabFood"){
                            return data.catatan_informasi;
                        } else {
                            return data.no_order_eatery;
                        }

                    }
                },
                {"data": "jenis_transaksi"},
                {
                    "data": {"grand_total_order": "grand_total_order"},
                    mRender : function(data, type, full) {
                        return convertToRupiah(data.grand_total_order);

                    }
                },
                {
                    "data": {"tax_order": "tax_order"},
                    mRender : function(data, type, full) {
                        return convertToRupiah(data.tax_order);

                    }
                },
                {
                    "data": {"service_order": "service_order"},
                    mRender : function(data, type, full) {
                        return convertToRupiah(data.service_order);

                    }
                },
                {"data": "nama_metode_pembayaran"},
                {
                    "data": {"is_paid": "is_paid"},
                    mRender : function(data, type, full) {
                        if(data.is_paid == "0"){
                            html = '<div class="alert alert-danger alert-payment" role="alert">\n' +
                                '                            <strong>BELUM BAYAR</strong>\n' +
                                '                        </div>';
                        } else {
                            html = '<div class="alert alert-success alert-payment" role="alert">\n' +
                                '                            <strong>LUNAS</strong>\n' +
                                '                        </div>';
                        }

                        return html;

                    }
                },
                // {
                //     "data": {
                //         "tgl_order":"tgl_order",
                //         "grand_total_order":"grand_total_order",
                //         "is_paid": "is_paid",
                //         "no_order_eatery": "no_order_eatery"
                //     },
                //     mRender : function(data, type, full) {
                //
                //         let dateTimeParts= data.tgl_order.split(/[- :]/);
                //         dateTimeParts[1]--;
                //         const temp_date = new Date(...dateTimeParts);
                //
                //         html = '<div class="detail-row">' +
                //                 '<div class="detail-column">' +
                //             '       <span>'+ temp_date.getDate() + '/' + (temp_date.getMonth() + 1) + '/' + temp_date.getFullYear() +'</span><br>' +
                //                     '<strong>'+ data.no_order_eatery +'</strong>\n<br>';
                //
                //
                //         html += '</div>' +
                //                 '<div class="detail-column" style="text-align: left">' +
                //                     '<strong style="font-size: 11px;">Total Order</strong>\n' +
                //                     '<h6>'+ convertToRupiah(data.grand_total_order) +'</h6>';
                //
                //         if(data.is_paid == "0"){
                //             html += '<div class="alert alert-danger alert-payment" role="alert">\n' +
                //                 '                            <strong>BELUM BAYAR</strong>\n' +
                //                 '                        </div>';
                //         } else {
                //             html += '<div class="alert alert-success alert-payment" role="alert">\n' +
                //                 '                            <strong>LUNAS</strong>\n' +
                //                 '                        </div>';
                //         }
                //
                //         html += '</div>' +
                //             '</div>';
                //
                //         return html;
                //
                //     }
                // }

            ],
            initComplete: function (settings, json) {
                $('.loading').css("display", "none");
                $('.Veil-non-hover').fadeOut();
            }
        });
    }

    $('#dataTable').on( 'click', 'tbody tr', function () {
        rowData = $('#dataTable').DataTable().row( this ).data();
        window.open(admin_url + 'POS_transaksi_detail?no=' + rowData.no_order_eatery);

    });






</script>
